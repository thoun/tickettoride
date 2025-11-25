<?php
 /**
  *------
  * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
  * TicketToRide implementation : © <Your name here> <Your email address here>
  * 
  * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
  * See http://en.boardgamearena.com/#!doc/Studio for more information.
  * -----
  * 
  * tickettoride.game.php
  *
  * This is the main file for your game logic.
  *
  * In this PHP file, you are going to defines the rules of the game.
  *
  */

namespace Bga\Games\TicketToRide;

use Bga\GameFramework\Components\Deck;
use Bga\GameFramework\Table;
use Bga\Games\TicketToRide\States\DealInitialDestinations;

require_once('framework-prototype/Helpers/Arrays.php');

require_once('constants.inc.php');
require_once(__DIR__.'/objects/map.php');
require_once(__DIR__.'/objects/route.php');
require_once(__DIR__.'/objects/destination.php');
require_once(__DIR__.'/objects/train-car.php');
require_once(__DIR__.'/objects/tunnel-attempt.php');

/*
 * Game main class.
 * For readability, main sections (util, action, state, args) have been splited into Traits with the section name on modules/php directory.
 */
class Game extends Table {
    use UtilTrait;
    use MapTrait;
    use DebugUtilTrait;

    public Deck $destinations;
    public Deck $trainCars;

    public \Map $map;

	function __construct() {
        parent::__construct();
        
        $this->initGameStateLabels(array_merge([
            LAST_TURN => 10, // last turn is the id of the player starting last turn, 0 if it's not last turn
        ]));
        
        $this->destinations = $this->deckFactory->createDeck('destination');
		
        $this->trainCars = $this->deckFactory->createDeck('traincar');
        $this->trainCars->autoreshuffle = true;
        $this->trainCars->autoreshuffle_trigger = ['obj' => $this, 'method' => 'trainCarDeckAutoReshuffle'];
	}

    /*
        setupNewGame:
        
        This method is called only once, when a new game is launched.
        In this method, you must setup the game according to the game rules, so that
        the game is ready to be played.
    */
    protected function setupNewGame($players, $options = []) {    
        // Set the colors of the players with HTML color code
        // The default below is red/green/blue/orange/brown
        // The number of colors defined here must correspond to the maximum number of players allowed for the gams
        $gameinfos = $this->getGameinfos();
        $default_colors = $gameinfos['player_colors'];
 
        // Create players
        // Note: if you added some extra field on "player" table in the database (dbmodel.sql), you can initialize it there.
        $sql = "INSERT INTO player (player_id, player_color, player_canal, player_name, player_avatar, player_remaining_train_cars) VALUES ";
        $values = [];
        foreach ($players as $playerId => $player) {
            $color = array_shift( $default_colors );
            $values[] = "('".$playerId."','$color','".$player['player_canal']."','".addslashes( $player['player_name'] )."','".addslashes( $player['player_avatar'] )."', ".$this->getMap()->trainCarsPerPlayer.")";
        }
        $sql .= implode(',', $values);
        $this->DbQuery($sql);
        $this->reattributeColorsBasedOnPreferences($players, $gameinfos['player_colors']);
        $this->reloadPlayersBasicInfos();
        
        /************ Start the game initialization *****/

        // Init global values with their initial values
        $this->setGameStateInitialValue(LAST_TURN, 0);
        
        // Init game statistics
        // 10+ : other
        $this->initStat('table', 'turnsNumber', 0);
        $this->initStat('player', 'turnsNumber', 0);
        $this->initStat('table', 'pointsWithClaimedRoutes', 0);
        $this->initStat('player', 'pointsWithClaimedRoutes', 0);
        $this->initStat('table', 'pointsWithDestinations', 0);
        $this->initStat('player', 'pointsWithDestinations', 0);
        $this->initStat('table', 'pointsWithCompletedDestinations', 0);
        $this->initStat('player', 'pointsWithCompletedDestinations', 0);
        $this->initStat('table', 'pointsLostWithUncompletedDestinations', 0);
        $this->initStat('player', 'pointsLostWithUncompletedDestinations', 0);
        // 20+ : train car cards
        $this->initStat('table', 'collectedTrainCarCards', 0);
        $this->initStat('player', 'collectedTrainCarCards', 0);
        $this->initStat('table', 'collectedHiddenTrainCarCards', 0);
        $this->initStat('player', 'collectedHiddenTrainCarCards', 0);
        $this->initStat('table', 'collectedVisibleTrainCarCards', 0);
        $this->initStat('player', 'collectedVisibleTrainCarCards', 0);
        $this->initStat('table', 'collectedVisibleLocomotives', 0);
        $this->initStat('player', 'collectedVisibleLocomotives', 0);
        $this->initStat('table', 'visibleCardsReplaced', 0);
        // 30+ : destination cards
        $this->initStat('table', 'drawDestinationsAction', 0);
        $this->initStat('player', 'drawDestinationsAction', 0);
        $this->initStat('table', 'completedDestinations', 0);
        $this->initStat('player', 'completedDestinations', 0);
        //$this->initStat('table', 'uncompletedDestinations', 0); // only computed at the end
        //$this->initStat('player', 'uncompletedDestinations', 0); // only computed at the end
        $this->initStat('player', 'keptInitialDestinationCards', 0); // player only
        $this->initStat('player', 'keptAdditionalDestinationCards', 0); // player only
        // 40+ : train cars (meeples)
        $this->initStat('table', 'claimedRoutes', 0);
        $this->initStat('player', 'claimedRoutes', 0);
        $this->initStat('table', 'playedTrainCars', 0);
        $this->initStat('player', 'playedTrainCars', 0);
        //$this->initStat('table', 'averageClaimedRouteLength', 0); // only computed at the end
        //$this->initStat('player', 'averageClaimedRouteLength', 0); // only computed at the end
        //$this->initStat('table', 'longestPath', 0); // only computed at the end
        //$this->initStat('player', 'longestPath', 0); // only computed at the end
        
        $expansionOption = $this->getExpansionOption();
        $isGlobetrotterBonusActive = $this->getMap()->isGlobetrotterBonusActive($expansionOption);
        $isLongestPathBonusActive = $this->getMap()->isLongestPathBonusActive($expansionOption);

        if ($isLongestPathBonusActive) {
            $this->playerStats->init('longestPathBonus', 0);
        }
        if ($isGlobetrotterBonusActive) {
            $this->playerStats->init('globetrotterBonus', 0);
        }

        // setup the initial game situation here

        $this->createDestinations();

        $this->createTrainCars();
        // give 4 to each player
        $this->giveInitialTrainCarCards(array_keys($players));

        // Activate first player (which is in general a good idea :) )
        $this->activeNextPlayer();

        return DealInitialDestinations::class;

        /************ End of the game initialization *****/
    }

    /*
        getAllDatas: 
        
        Gather all informations about current game situation (visible by the current player).
        
        The method is called each time the game interface is displayed to a player, ie:
        _ when the game starts
        _ when a player refreshes the game page (F5)
    */
    protected function getAllDatas(): array {
        $stateName = $this->gamestate->getCurrentMainState()->name; 
        $isEnd = $stateName === 'endScore' || $stateName === 'gameEnd';

        $expansionOption = $this->getExpansionOption();
        //var_dump($this->map);

        $result = [
            'map' => [
                'code' => $this->getMap()->code,
                'cities' => $this->getMap()->cities,
                'routes' => $this->getAllRoutes(),
                'destinations' => $this->getMap()->destinations,
                'bigCities' => $this->getMap()->getBigCities($expansionOption),
                'illustration' => $expansionOption,
                'preloadImages' => $this->getMap()->getPreloadImages($expansionOption),
                'locomotiveUsageRestriction' => $this->getMap()->locomotiveUsageRestriction,
                'minimumPlayerForDoubleRoutes' => $this->getMap()->minimumPlayerForDoubleRoutes,
                'multilingualPdfRulesUrl' => $this->getMap()->multilingualPdfRulesUrl,
                'rulesDifferences' => $this->getMap()->rulesDifferences,
                'vertical' => $this->getMap()->vertical,
            ],
        ];
    
        $currentPlayerId = $this->getCurrentPlayerId();    // !! We must only return informations visible by this player !!
    
        // Get information about players
        // Note: you can retrieve some extra field you added for "player" table in "dbmodel.sql" if you need it.
        $sql = "SELECT player_id id, player_score score, player_no playerNo FROM player ";
        $result['players'] = $this->getCollectionFromDb($sql);
  
        // Gather all information about current game situation (visible by player $currentPlayerId).

        $result['claimedRoutes'] = $this->getClaimedRoutes();
        $visibleTrainCards = $this->getVisibleTrainCarCards();
        $spotsCards = [];
        foreach($visibleTrainCards as $visibleTrainCard) {
            $spotsCards[$visibleTrainCard->location_arg] = $visibleTrainCard;
        }
        $result['visibleTrainCards'] = $spotsCards;

        // private data : current player hidden informations
        $result['handTrainCars'] = $this->getTrainCarsFromDb($this->trainCars->getCardsInLocation('hand', $currentPlayerId));
        $result['handDestinations'] = $this->getDestinationsFromDb($this->destinations->getCardsInLocation('hand', $currentPlayerId));
        $result['completedDestinations'] = $this->getDestinationsFromDb($this->destinations->getCards($this->getCompletedDestinationsIds($currentPlayerId)));

        // share informations (for player panels)
        foreach ($result['players'] as $playerId => &$player) {
            $player['playerNo'] = intval($player['playerNo']);
            $player['trainCarsCount'] = intval($this->trainCars->countCardInLocation('hand', $playerId));
            $player['destinationsCount'] = intval($this->destinations->countCardInLocation('hand', $playerId));
            $player['remainingTrainCarsCount'] = $this->getRemainingTrainCarsCount($playerId);

            if ($isEnd) {
                $player['completedDestinations'] = $this->getDestinationsFromDb($this->destinations->getCards($this->getCompletedDestinationsIds($playerId)));
                $player['uncompletedDestinations'] = $this->getDestinationsFromDb($this->destinations->getCards($this->getUnompletedDestinationsIds($playerId)));
                $player['longestPathLength'] = $this->getLongestPath($playerId)->length;
            } else {
                $player['completedDestinations'] = [];
                $player['uncompletedDestinations'] = [];
            }
        }

        // deck counters
        $result['trainCarDeckCount'] = $this->getRemainingTrainCarCardsInDeck();
        $result['destinationDeckCount'] = $this->getRemainingDestinationCardsInDeck();
        $result['trainCarDeckMaxCount'] = intval($this->getUniqueValueFromDB("select count(*) from `traincar`"));
        $result['destinationDeckMaxCount'] = intval($this->getUniqueValueFromDB("select count(*) from `destination`"));          

        $result['isGlobetrotterBonusActive'] = $this->getMap()->isGlobetrotterBonusActive($expansionOption);
        $result['isLongestPathBonusActive'] = $this->getMap()->isLongestPathBonusActive($expansionOption);
        
        $result['showTurnOrder'] = $this->tableOptions->get(SHOW_TURN_ORDER_OPTION) == 2;
        
        if ($isEnd) {
            $result['bestScore'] = max(array_map(fn($player) => intval($player['score']), $result['players']));
        } else {
            $result['lastTurn'] = $this->getGameStateValue(LAST_TURN) > 0;            
        }

  
        return $result;
    }

    /*
        getGameProgression:
        
        Compute and return the current game progression.
        The number returned must be an integer beween 0 (=the game just started) and
        100 (= the game is finished or almost finished).
    
        This method is called each time we are in a game state with the "updateGameProgression" property set to true 
        (see states.inc.php)
    */
    function getGameProgression() {
        $stateName = $this->gamestate->getCurrentMainState()->name; 
        if ($stateName === 'endScore' || $stateName === 'gameEnd') {
            // game is over
            return 100;
        }

        // ratio of remaining train cars (based on player with lowest count)
        return 100 * ($this->getMap()->trainCarsPerPlayer - $this->getLowestTrainCarsCount()) / $this->getMap()->trainCarsPerPlayer;
    }

    /**
     * Create cards, place 5 on table, and check revealed cards are valid.
     */
    public function createTrainCars() {
        for ($color = 0; $color <= 8; $color++) {
            $trainCars[] = [ 'type' => $color, 'type_arg' => null, 'nbr' => ($color == 0 ? $this->getMap()->numberOfLocomotiveCards : $this->getMap()->numberOfColoredCards)];
        }
        $this->trainCars->createCards($trainCars, 'deck');
        $this->trainCars->shuffle('deck');

        $this->placeNewTrainCarCardsOnTable(false);
        $this->checkTooMuchLocomotives();
    }

    /**
     * Give initial cards to each player.
     */
    public function giveInitialTrainCarCards(array $playersIds) {
		foreach ($playersIds as $playerId) {
            $this->trainCars->pickCards($this->getMap()->initialTrainCarCardsInHand, 'deck', $playerId);
        }
    }

    /**
     * List visible cards.
     * Optional filter can return only card play can draw.
     */
    public function getVisibleTrainCarCards(bool $limitToSelectableOnSecondPick = false) {
        $cards = $this->getTrainCarsFromDb($this->trainCars->getCardsInLocation('table'));

        if ($limitToSelectableOnSecondPick && $this->getMap()->visibleLocomotivesCountsAsTwoCards) {
            $cards = array_values(array_filter($cards, fn($card) => $card->type != 0));
        }

        return $cards;
    }

    /**
     * Draw 1 or 2 hidden cards, to player hand.
     */
    public function drawTrainCarCardsFromDeck(int $playerId, int $number, bool $isSecondCard = false) {
        if ($number != 1 && $number != 2) {
            throw new \BgaUserException("You must take one or two cards.");
        }
        
        if ($number == 2 && $isSecondCard) {
            throw new \BgaUserException("You must take one card.");
        }

        $remainingTrainCarCardsInDeck = $this->getRemainingTrainCarCardsInDeck(true);

        if ($number == 2 && $remainingTrainCarCardsInDeck == 1) {
            $number = 1;
        }

        if ($number > $remainingTrainCarCardsInDeck) {
            throw new \BgaUserException(self::_("You can't take train car cards because the deck is empty"));
        }

        $cards = $this->getTrainCarsFromDb($this->trainCars->pickCards($number, 'deck', $playerId));

        $this->notify->all('trainCarPicked', clienttranslate('${player_name} takes ${count} hidden train car card(s)'), [
            'playerId' => $playerId,
            'player_name' => $this->getPlayerNameById($playerId),
            'number' => $number,
            'count' => $number,
            'remainingTrainCarsInDeck' => $this->getRemainingTrainCarCardsInDeck(),
            'origin' => 0, // 0 means hidden
        ]);

        $this->notify->player($playerId, 'trainCarPicked', clienttranslate('You take hidden train car card(s) ${colors}'), [
            'playerId' => $playerId,
            'player_name' => $this->getPlayerNameById($playerId),
            'number' => $number,
            'count' => $number,
            'remainingTrainCarsInDeck' => $this->getRemainingTrainCarCardsInDeck(),
            'cards' => $cards,
            'colors' => array_map(fn($card) => $card->type, $cards),
            'origin' => 0, // 0 means hidden
        ]);

        return $number;
    }

    /**
     * Draw 1 visible card, to player hand.
     */
    public function drawTrainCarCardsFromTable(int $playerId, int $id, bool $isSecondCard = false) { // return card
        $card = $this->getTrainCarFromDb($this->trainCars->getCard($id));

        if ($card->location != 'table') {
            throw new \BgaUserException("You can't take this visible card.");
        }

        if ($isSecondCard && $card->type == 0 && $this->getMap()->visibleLocomotivesCountsAsTwoCards) {
            throw new \BgaUserException("You can't take a locomotive as a second card.");
        }

        $spot = $card->location_arg;

        $this->trainCars->moveCard($id, 'hand', $playerId);

        $this->notify->all('trainCarPicked', clienttranslate('${player_name} takes ${color}'), [
            'playerId' => $playerId,
            'player_name' => $this->getPlayerNameById($playerId),
            'number' => 1,
            'remainingTrainCarsInDeck' => $this->getRemainingTrainCarCardsInDeck(),
            'cards' => [$card],
            'color' => $card->type,
            'origin' => $spot,
        ]);

        $this->placeNewTrainCarCardOnTable($spot);

        $this->checkTooMuchLocomotives();

        return $card;
    }

    /**
     * get remaining cards in deck (can include discarded ones, to know how many cards player can pick).
     */
    public function getRemainingTrainCarCardsInDeck(bool $includeDiscard = false, bool $includeVisible = false) {
        $remaining = intval($this->trainCars->countCardInLocation('deck'));

        if ($includeDiscard || $remaining == 0) {
            $remaining += intval($this->trainCars->countCardInLocation('discard'));
        }
        if ($includeVisible) {
            $remaining += intval($this->trainCars->countCardInLocation('table'));
        }

        return $remaining;
    }

    /**
     * reset visible cards if there is 3 or more locomotives
     */
    private function checkTooMuchLocomotives(int $attempts = 0) {
        if ($this->getMap()->resetVisibleCardsWithLocomotives === null) {
            return;
        }

        $cards = $this->getVisibleTrainCarCards();
        $locomotives = count(array_filter($cards, fn($card) => $card->type == 0));
        if ($locomotives >= $this->getMap()->resetVisibleCardsWithLocomotives && $this->getRemainingTrainCarCardsInDeck(true) > 0) {
            if ($attempts >= 3) {
                $this->notify->all('log', clienttranslate('Three locomotives have been revealed multiples times in a row, they will stay visible'), []);
            } else {
                $this->trainCars->moveAllCardsInLocation('table', 'discard');
                $this->placeNewTrainCarCardsOnTable(true);

                $this->checkTooMuchLocomotives($attempts + 1);
            }
        }
    }

    /**
     * replace all visible cards
     */
    private function placeNewTrainCarCardsOnTable(bool $fromLocomotiveReset) {
        $cards = [];
        $spots = [];
        
        for ($i=1; $i<=5; $i++) {
            $newCard = $this->getTrainCarFromDb($this->trainCars->pickCardForLocation('deck', 'table', $i));
            if ($newCard !== null) {
                $cards[] = $newCard;
            }
            $spots[$i] = $newCard;
        }

        if ($fromLocomotiveReset) {
            $this->notify->all('highlightVisibleLocomotives', clienttranslate('Three locomotives have been revealed, visible train cards are replaced'), []);

            $this->tableStats->inc('visibleCardsReplaced', 1);
        }

        if (count($cards) > 0) {
            $this->notify->all('newCardsOnTable', '', [
                'cards' => $cards,
                'spotsCards' => $spots,
                'remainingTrainCarsInDeck' => $this->getRemainingTrainCarCardsInDeck(),
                'locomotiveRefill' => true,
            ]);
        }

        return $cards;
    }

    /**
     * replace a visible card
     */
    private function placeNewTrainCarCardOnTable(int $spot) {
        $card = $this->getTrainCarFromDb($this->trainCars->pickCardForLocation('deck', 'table', $spot));

        $this->notify->all('newCardsOnTable', '', [
            'cards' => [$card],
            'spotsCards' => [$spot => $card],
            'remainingTrainCarsInDeck' => $this->getRemainingTrainCarCardsInDeck(),
            'locomotiveRefill' => false,
        ]);
    }

    private function checkVisibleTrainCarCards() {
        if (intval($this->trainCars->countCardInLocation('table')) < 5 && $this->getRemainingTrainCarCardsInDeck(true) > 0) {
            $spots = [];
            
            for ($i=1; $i<=5; $i++) {
                $cards = $this->getTrainCarsFromDb($this->trainCars->getCardsInLocation('table', $i));
                if (count($cards) == 0) {
                    $newCard = $this->getTrainCarFromDb($this->trainCars->pickCardForLocation('deck', 'table', $i));
                    if ($newCard !== null) {
                        $cards[] = $newCard;
                    }
                    $spots[$i] = $newCard;
                }
            }
            if (count($spots) > 0) {
                $this->notify->all('newCardsOnTable', '', [
                    'spotsCards' => $spots,
                    'remainingTrainCarsInDeck' => $this->getRemainingTrainCarCardsInDeck(),
                    'locomotiveRefill' => false,
                ]);

                $this->checkTooMuchLocomotives();
            }
        }
    }

    public function canTakeASecondCard(?int $firstCardType) { // null if unknown/hidden
        if ($firstCardType === 0 && $this->getMap()->visibleLocomotivesCountsAsTwoCards) {
            // if the player chose a locomotive
            return false;
        }

        $remainingTrainCarCardsInDeck = $this->getRemainingTrainCarCardsInDeck(true);
        if ($remainingTrainCarCardsInDeck == 0) {
            // if there is no hidden card and all remaining visible cards are locomotives, it's impossible to take a second card
            $tableCards = $this->getVisibleTrainCarCards(true);
            return count($tableCards) > 0; 
        }

        return true;
    }

    public function trainCarDeckAutoReshuffle() {
        $this->notify->all('log', clienttranslate('The train car deck has been reshuffled'), []);
    }

    /**
     * Create destination cards.
     */
    public function createDestinations() {
        $expansionOption = $this->getExpansionOption();
        $destinations = $this->getMap()->getDestinationToGenerate($expansionOption);
        //debug($this->getMap()->code, $expansionOption, $destinations);

        foreach($destinations as $deck => $cards) {
            $this->destinations->createCards($cards, $deck);
            $this->destinations->shuffle($deck);
        }
    }
	
    /**
     * Pick destination cards for beginning choice.
     */
    public function pickInitialDestinationCards(int $playerId) {
        $expansionOption = $this->getExpansionOption();
        $pick = $this->getMap()->getInitialDestinationPick($expansionOption);

        $cards = [];
        foreach ($pick as $deck => $number) {
            $cards = array_merge($cards, $this->pickDestinationCards($playerId, $number, $deck));
        }

		return $cards;
    }	

    /**
     * Select kept destination cards for beginning choice. 
     * Unused destination cards are set back on the deck or discarded.
     */
    public function keepInitialDestinationCards(int $playerId, array $ids) {
		$this->keepDestinationCards($playerId, $ids, $this->getMap()->getInitialDestinationMinimumKept($this->getExpansionOption()), $this->getMap()->unusedInitialDestinationsGoToDeckBottom);
    }	
	
    /**
     * Pick destination cards for pick destination action.
     */
    public function pickAdditionalDestinationCards(int $playerId) {
		return $this->pickDestinationCards($playerId, $this->getMap()->getAdditionalDestinationCardNumber($this->getExpansionOption()));
    }	

    /**
     * Select kept destination cards for pick destination action. 
     * Unused destination cards are set back on the deck or discarded.
     */
    public function keepAdditionalDestinationCards(int $playerId, array $ids) {
		$this->keepDestinationCards($playerId, $ids, $this->getMap()->additionalDestinationMinimumKept, $this->getMap()->unusedAdditionalDestinationsGoToDeckBottom);
    }

    /**
     * Get destination picked cards (cards player can choose).
     */
    public function getPickedDestinationCards(int $playerId) {
        $cards = $this->getDestinationsFromDb($this->destinations->getCardsInLocation("pick$playerId"));
        return $cards;
    }

    /**
     * get remaining destination cards in deck.
     */
    public function getRemainingDestinationCardsInDeck() {
        $remaining = intval($this->destinations->countCardInLocation('deck'));

        if ($remaining == 0) {
            $remaining = intval($this->destinations->countCardInLocation('discard'));
        }

        return $remaining;
    }

    /**
     * place a number of destinations cards to pick$playerId.
     */
    private function pickDestinationCards($playerId, int $number, string $from = 'deck') {
        $cards = $this->getDestinationsFromDb($this->destinations->pickCardsForLocation($number, $from, "pick$playerId"));
        return $cards;
    }

    /**
     * move selected cards to player hand, and empty pick$playerId.
     */
    private function keepDestinationCards(int $playerId, array $ids, int $minimum, bool $toDeckBottom) {
        if (count($ids) < $minimum) {
            throw new \BgaUserException("You must keep at least $minimum cards.");
        }

        if (count($ids) > 0 && $this->getUniqueIntValueFromDB("SELECT count(*) FROM destination WHERE `card_location` != 'pick$playerId' AND `card_id` in (".implode(', ', $ids).")") > 0) {
            throw new \BgaUserException("Selected cards are not available.");
        }

        $this->destinations->moveCards($ids, 'hand', $playerId);

        $remainingCardsInPick = intval($this->destinations->countCardInLocation("pick$playerId"));
        if ($remainingCardsInPick > 0) {
            if ($toDeckBottom) {
                $this->destinations->shuffle("pick$playerId");
                // we put remaining cards in pick at the bottom of the deck
                $this->DbQuery("UPDATE destination SET `card_location_arg` = card_location_arg + $remainingCardsInPick WHERE `card_location` = 'deck'");
                $this->destinations->moveAllCardsInLocationKeepOrder("pick$playerId", 'deck');
            } else {
                // we discard remaining cards in pick
                $this->destinations->moveAllCardsInLocation("pick$playerId", 'void');
            }
        }

        $this->notify->all('destinationsPicked', clienttranslate('${player_name} keeps ${count} destinations'), [
            'playerId' => $playerId,
            'player_name' => $this->getPlayerNameById($playerId),
            'count' => count($ids),
            'number' => count($ids),
            'remainingDestinationsInDeck' => $this->getRemainingDestinationCardsInDeck(),
            '_private' => [
                $playerId => [
                    'destinations' => $this->getDestinationsFromDb($this->destinations->getCards($ids)),
                ],
            ],
        ]);
    }

    function applyClaimRoute(int $playerId, int $routeId, int $color, int $extraCardCost = 0) {
        $route = $this->getAllRoutes()[$routeId];
        $cardCost = $route->number + $extraCardCost;
        
        $remainingTrainCars = $this->getRemainingTrainCarsCount($playerId);
        $trainCarsHand = $this->getTrainCarsFromDb($this->trainCars->getCardsInLocation('hand', $playerId));
        $cardsToRemove = $this->canPayForRoute($route, $trainCarsHand, $remainingTrainCars, $color, $extraCardCost);

        $this->trainCars->moveCards(array_map(fn($card) => $card->id, $cardsToRemove), 'discard');

        // save claimed route
        self::DbQuery("INSERT INTO `claimed_routes` (`route_id`, `player_id`) VALUES ($routeId, $playerId)");

        // update score
        $points = $this->getMap()->routePoints[$route->number];
        $this->incScore($playerId, $points);

        self::DbQuery("UPDATE player SET `player_remaining_train_cars` = `player_remaining_train_cars` - $route->number WHERE player_id = $playerId");
        
        $this->notify->all('claimedRoute', clienttranslate('${player_name} gains ${points} point(s) by claiming route from ${from} to ${to} with ${number} train car(s) : ${colors}'), [
            'playerId' => $playerId,
            'player_name' => $this->getPlayerNameById($playerId),
            'points' => $points,
            'route' => $route,
            'from' => $this->getCityName($route->from),
            'to' => $this->getCityName($route->to),
            'number' => $cardCost,
            'removeCards' => $cardsToRemove,
            'colors' => array_map(fn($card) => $card->type, $cardsToRemove),
            'remainingTrainCars' => $this->getRemainingTrainCarsCount($playerId),
        ]);

        $this->playerStats->inc('claimedRoutes', 1, $playerId, updateTableStat: true);
        $this->playerStats->inc('playedTrainCars', $route->number, $playerId, updateTableStat: true);
        $this->playerStats->inc('pointsWithClaimedRoutes', $points, $playerId, updateTableStat: true);

        $this->checkCompletedDestinations($playerId);

        // in case there is less than 5 visible cards on the table, we refill with newly discarded cards
        $this->checkVisibleTrainCarCards();

        $this->gamestate->nextState('nextPlayer'); 
    }

    function endTunnelAttempt(bool $storedTunnelAttempt) {
        // put back tunnel cards
        $this->trainCars->moveAllCardsInLocation('tunnel', 'discard');

        if ($storedTunnelAttempt) {
            $this->deleteGlobalVariable(TUNNEL_ATTEMPT);
        }
    }
    
///////////////////////////////////////////////////////////////////////////////////:
////////// DB upgrade
//////////

    /*
        upgradeTableDb:
        
        You don't have to care about this until your game has been published on BGA.
        Once your game is on BGA, this method is called everytime the system detects a game running with your old
        Database scheme.
        In this case, if you change your Database scheme, you just have to apply the needed changes in order to
        update the game database and allow the game to continue to run with your new version.
    
    */
    
    function upgradeTableDb($from_version) {
        // $from_version is the current version of this game database, in numerical form.
        // For example, if the game was running with a release of your game named "140430-1345",
        // $from_version is equal to 1404301345
        
        // Example:
//        if( $from_version <= 1404301345 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "ALTER TABLE DBPREFIX_xxxxxxx ....";
//            $this->applyDbUpgradeToAllDB( $sql );
//        }
//        if( $from_version <= 1405061421 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "CREATE TABLE DBPREFIX_xxxxxxx ....";
//            $this->applyDbUpgradeToAllDB( $sql );
//        }
//        // Please add your future database scheme changes here
//
//


    }    
}
