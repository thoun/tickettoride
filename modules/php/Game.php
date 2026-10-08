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

require_once('framework-prototype/Helpers/Arrays.php');

require_once('constants.inc.php');
require_once(__DIR__.'/MapManager.php');

use Bga\GameFramework\Table;
use Bga\Games\TicketToRide\Objects\ClaimedRoute;
use Bga\Games\TicketToRide\Objects\Destination;
use Bga\Games\TicketToRide\Objects\Map;
use Bga\Games\TicketToRide\States\ChooseLegendaryCharacter;
use Bga\Games\TicketToRide\States\DealInitialDestinations;

const MAP_LIST = [
    1 => 'usa',
    2 => 'europe',
    3 => 'switzerland',
    4 => 'india',
    5 => 'nordiccountries',
    6 => 'teamasia',
    7 => 'legendaryasia',
    8 => 'africa',
    9 => 'netherlands',
    10 => 'uk',
    11 => 'pennsylvania',
    12 => 'france',
    13 => 'oldwest',
    14 => 'poland',
    15 => 'italy',
    16 => 'japan',
    17 => 'iberia',
    18 => 'southkorea',
    19 => 'germany',
    20 => 'northernlights',
];

/*
 * Game main class.
 */
class Game extends Table {
    use DebugUtilTrait;

    public MapManager $mapManager;
    public DestinationManager $destinationManager;
    public TrainCarManager $trainCarManager;
    public BuildingManager $buildingManager;
    public LegendaryCharacterManager $legendaryCharacterManager;

    public Map $map;

	function __construct() {
        parent::__construct();
        
        $this->initGameStateLabels(array_merge([
            LAST_TURN => 10, // last turn is the id of the player starting last turn, 0 if it's not last turn
        ]));

        $this->mapManager = new MapManager($this);
        $this->destinationManager = new DestinationManager($this);
        $this->trainCarManager = new TrainCarManager($this);
        $this->buildingManager = new BuildingManager($this);
        $this->legendaryCharacterManager = new LegendaryCharacterManager($this);

        $this->bga->notify->addDecorator(function(string $message, array $args) {
            if (isset($args['playerId']) && !isset($args['player_name']) && str_contains($message, '${player_name}')) {
                $args['player_name'] = $this->getPlayerNameById($args['playerId']);
            }
            
            return $args;
        });
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

        if ($this->getMap()->isLongestPathBonusActive($expansionOption)) {
            $this->playerStats->init('longestPathBonus', 0);
        }
        if ($this->getMap()->isGlobetrotterBonusActive($expansionOption)) {
            $this->playerStats->init('globetrotterBonus', 0);
        }

        // setup the initial game situation here

        $this->destinationManager->createDestinations();

        $this->trainCarManager->createTrainCars();
        // give 4 to each player
        $this->trainCarManager->giveInitialTrainCarCards(array_keys($players));

        $this->getMap()->setup($this);

        // Activate first player (which is in general a good idea :) )
        $this->activeNextPlayer();

        $legendaryCharacterActive = $this->legendaryCharacterManager->isActive();
        if ($legendaryCharacterActive) {
            $this->activePrevPlayer(); // start by the last one
        }

        return $legendaryCharacterActive ?
          ChooseLegendaryCharacter::class :
          DealInitialDestinations::class;

        /************ End of the game initialization *****/
    }

    /*
        getAllDatas: 
        
        Gather all informations about current game situation (visible by the current player).
        
        The method is called each time the game interface is displayed to a player, ie:
        _ when the game starts
        _ when a player refreshes the game page (F5)
    */
    protected function getAllDatas(int $currentPlayerId): array {
        $isEnd = $this->gamestate->getCurrentMainStateId() >= ST_END_SCORE;
        $gameStarted = $this->gamestate->getCurrentMainStateId() >= ST_PLAYER_CHOOSE_ACTION;

        $expansionOption = $this->getExpansionOption();
        $legendaryCharacterActive = $this->legendaryCharacterManager->isActive();
        //var_dump($this->map);

        $result = [
            'map' => [
                'code' => $this->getMap()->code,
                'cities' => $this->getMap()->cities,
                'routes' => $this->mapManager->getAllRoutes(),
                'destinations' => $this->getMap()->destinations,
                'bigCities' => $this->getMap()->getBigCities($expansionOption),
                'illustration' => $expansionOption,
                'preloadImages' => $this->getMap()->getPreloadImages($expansionOption),
                'locomotiveUsageRestriction' => $this->getMap()->locomotiveUsageRestriction,
                'maximumPlayerForDoubleRoutes' => $this->getMap()->maximumPlayerForDoubleRoutes,
                'maximumPlayerForTripleRoutes' => $this->getMap()->maximumPlayerForTripleRoutes,
                'differentLengthRoutesAreDoubleRoutes' => $this->getMap()->differentLengthRoutesAreDoubleRoutes,
                'multilingualPdfRulesUrl' => $this->getMap()->multilingualPdfRulesUrl,
                'rulesDifferences' => $this->getMap()->rulesDifferences,
                'width' => $this->getMap()->width,
                'height' => $this->getMap()->height,
                'stations' => $this->getMap()->stations,
                'cityMarkers' => $this->getMap()->cityMarkers,
                'pointsForGlobetrotter' => $this->getMap()->pointsForGlobetrotter,
                'pointsForMostConnectedCities' => $this->getMap()->pointsForMostConnectedCities,
                'ferryCards' => $this->getMap()->ferryCards,
                'useTechnologyCards' => $this->getMap()->useTechnologyCards,
                'useBonusCards' => $this->getMap()->useBonusCards,
                'useTrackBedPieces' => $this->getMap()->useTrackBedPieces,
            ],
        ];
    
        // Get information about players
        // Note: you can retrieve some extra field you added for "player" table in "dbmodel.sql" if you need it.
        $sql = "SELECT player_id id, player_score score, player_no playerNo FROM player ";
        $result['players'] = $this->getCollectionFromDb($sql);
  
        // Gather all information about current game situation (visible by player $currentPlayerId).

        $result['claimedRoutes'] = $this->getClaimedRoutes();
        $result['builtStations'] = $this->buildingManager->getPlacedStations();
        $result['placedCityMarkers'] = $this->buildingManager->getPlacedCityMarkers();
        $visibleTrainCards = $this->trainCarManager->getVisibleTrainCarCards();
        $spotsCards = [];
        foreach($visibleTrainCards as $visibleTrainCard) {
            $spotsCards[$visibleTrainCard->location_arg] = $visibleTrainCard;
        }
        $result['visibleTrainCards'] = $spotsCards;

        // private data : current player hidden informations
        $result['handTrainCars'] = $this->trainCarManager->getPlayerHand($currentPlayerId);
        $result['handDestinations'] = $this->destinationManager->getPlayerHand($currentPlayerId);
        $result['completedDestinations'] = $this->destinationManager->getCompletedDestinations($currentPlayerId);

        // share informations (for player panels)
        foreach ($result['players'] as $playerId => &$player) {
            $player['playerNo'] = intval($player['playerNo']);
            $player['trainCarsCount'] = $this->trainCarManager->getPlayerHandCount($playerId);
            $player['destinationsCount'] = $gameStarted || $playerId === $currentPlayerId ? $this->destinationManager->getPlayerHandCount($playerId) : null;
            $player['remainingTrainCarsCount'] = $this->getRemainingTrainCarsCount($playerId);
            $player['remainingCityMarkers'] = $this->buildingManager->getRemainingCityMarkers($playerId);
            $remainingStations = $this->buildingManager->getRemainingStations($playerId);
            if ($remainingStations !== null) {
                $player['remainingStations'] = $remainingStations;
            }
            if ($legendaryCharacterActive) {
                $player['legendaryCharacter'] = $this->legendaryCharacterManager->getPlayerCharacter($playerId);
                $player['legendaryCharacterState'] = $this->legendaryCharacterManager->getPlayerCharacterState($playerId);
            }

            if ($isEnd) {
                $player['completedDestinations'] = $this->destinationManager->getCompletedDestinations($playerId);
                $player['uncompletedDestinations'] = $this->destinationManager->getUncompletedDestinations($playerId);
                $player['longestPathLength'] = $this->mapManager->getLongestPath($playerId)->length;
                if ($this->getMap()->mandalaPoints !== null) {
                    $player['mandalaCount'] = count(array_filter(
                        $player['completedDestinations'],
                        fn($destination) => count($this->mapManager->getDistinctRoutes($playerId, $destination)) >= 2,
                    ));
                }
                if ($this->getMap()->pointsForMostConnectedCities !== null) {
                    $player['mostConnectedCities'] = $this->mapManager->getMostConnectedCities($playerId);
                }
            } else {
                $player['completedDestinations'] = [];
                $player['uncompletedDestinations'] = [];
            }
            $player['mapSpecificData'] = $this->getMap()->getPlayerMapSpecificData($this, $playerId);
        }

        // deck counters
        $result['trainCarDeckCount'] = $this->trainCarManager->getRemainingTrainCarCardsInDeck();
        $result['destinationDeckCount'] = $this->destinationManager->getRemainingDestinationCardsInDeck();
        $result['trainCarDeckMaxCount'] = intval($this->getUniqueValueFromDB("select count(*) from `traincar`"));
        $result['destinationDeckMaxCount'] = intval($this->getUniqueValueFromDB("select count(*) from `destination`"));          

        $result['legendaryCharactersExpansionActive'] = $legendaryCharacterActive;
        $result['isGlobetrotterBonusActive'] = $this->getMap()->isGlobetrotterBonusActive($expansionOption);
        $result['isLongestPathBonusActive'] = $this->getMap()->isLongestPathBonusActive($expansionOption);

        $result['mapSpecificData'] = $this->getMap()->getMapSpecificData($this);
        
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

    function removeTrainCars(int $playerId, int $number) {
        $this->DbQuery("UPDATE player SET `player_remaining_train_cars` = `player_remaining_train_cars` - $number WHERE player_id = $playerId");
    }

    function applyClaimRoute(int $playerId, int $routeId, int $color, int $extraCardCost = 0, ?array $distributionCards = null, bool $shifted = false, int $ferryCardsUsed = 0): void {
        $route = $this->mapManager->getCurrentStateRoutes()[$routeId];
        $cardCost = $this->mapManager->getRouteTrainCardCost($route, $playerId, $extraCardCost);
        $ferryCardsUsed = $route->ferryWaves > 0 ? $ferryCardsUsed : 0;

        $legendaryCharacter = null;
        $considerAllRoutesGray = false;
        $pairSetAsLocomotive = null;
        if ($this->legendaryCharacterManager->isActive()) {
            $legendaryCharacter = $this->legendaryCharacterManager->getPlayerCharacter($playerId);
            $legendaryCharacterState = $this->legendaryCharacterManager->getPlayerCharacterState($playerId);
            $considerAllRoutesGray = $legendaryCharacter === 5 && $legendaryCharacterState === 'using';
            $pairSetAsLocomotive = $this->legendaryCharacterManager->getCharacter3UsingColor($playerId);
            if ($considerAllRoutesGray) {
                $this->legendaryCharacterManager->setPlayerCharacterState($playerId, 'used');
            }
            $this->legendaryCharacterManager->onCharacter4RouteClaim($playerId, $routeId);
        }
        
        $remainingTrainCars = $this->getRemainingTrainCarsCount($playerId);
        $trainCarsHand = $this->trainCarManager->getPlayerHand($playerId);
        $availableFerryCards = $this->getMap()->ferryCards ? (int) $this->bga->globals->get("FERRY_CARD_{$playerId}", 0) : 0;
        $cardsToRemove = $this->mapManager->canPayForRoute($route, $trainCarsHand, $remainingTrainCars, $color, $extraCardCost, distributionCards: $distributionCards, considerAllRoutesGray: $considerAllRoutesGray, pairSetAsLocomotive: $pairSetAsLocomotive, ferryCards: $availableFerryCards, ferryCardsUsed: $ferryCardsUsed, playerId: $playerId);
        $claimWithBulletTrain = $route->bulletTrainSpaceIndex !== null && $this->bga->globals->get(REMAINING_BULLET_TRAINS) > 0;

        if ($legendaryCharacter === 1 && $legendaryCharacterState === 'using') {
            $this->legendaryCharacterManager->setPlayerCharacterState($playerId, 'used:'.$routeId);
        }

        $this->trainCarManager->trainCars->moveCards(array_map(fn($card) => $card->id, $cardsToRemove), 'discard');
        $ferryCardsCount = $ferryCardsUsed > 0
            ? $this->bga->globals->inc("FERRY_CARD_{$playerId}", -$ferryCardsUsed)
            : $availableFerryCards;

        // save claimed route
        $claimerId = $claimWithBulletTrain ? -1 : $playerId;
        $shiftIndex = $shifted ? $this->getUniqueIntValueFromDB("SELECT count(*) FROM claimed_routes WHERE route_id = $routeId") : 0;
        $this->DbQuery("INSERT INTO `claimed_routes` (`route_id`, `player_id`, `shift_index`) VALUES ($routeId, $claimerId, $shiftIndex)");

        $returnedTrackPiece = null;
        if ($this->getMap()->useTrackBedPieces) {
            $placedTrackPieces = $this->bga->globals->get('PLACED_TRACK_PIECES', []);
            if (isset($placedTrackPieces[$routeId])) {
                $pieceColor = $placedTrackPieces[$routeId];
                $remainingTrackPieces = $this->bga->globals->get('REMAINING_TRACK_PIECES', []);
                $remainingTrackPieces[$pieceColor][$route->number]++;
                unset($placedTrackPieces[$routeId]);
                $this->bga->globals->set('PLACED_TRACK_PIECES', $placedTrackPieces);
                $this->bga->globals->set('REMAINING_TRACK_PIECES', $remainingTrackPieces);
                $returnedTrackPiece = [
                    'color' => $pieceColor,
                    'length' => $route->number,
                    'remainingCount' => $remainingTrackPieces[$pieceColor][$route->number],
                ];
            }
        }

        // update score
        $points = 0;
        $routePointAwards = [$playerId => 0];
        $remainingBulletTrains = null;
        $bulletTrainPosition = null;
        if ($claimWithBulletTrain) {
            $bulletTrainPosition = $this->bga->globals->inc("BULLET_TRAIN_POSITION_{$playerId}", $cardCost);

            $remainingBulletTrains = $this->bga->globals->inc(REMAINING_BULLET_TRAINS, -1);
        } else {
            $points = $this->getMap()->routePoints[$route->number];
            if ($this->getMap()->useTechnologyCards) {
                $technologyCards = $this->bga->globals->get("TECHNOLOGY_CARDS_{$playerId}", []);
                $points += $this->getMap()->getAdditionalRoutePoints($route, $technologyCards);
            }
            $routePointAwards = $this->buildingManager->getRoutePointAwards($playerId, $route, $points);
            foreach ($routePointAwards as $scoringPlayerId => $awardedPoints) {
                $this->incScore($scoringPlayerId, $awardedPoints, $scoringPlayerId !== $playerId
                    ? clienttranslate('${player_name} gains ${delta} point(s) from city control on the route from ${from} to ${to}')
                    : null, [
                        'from' => $this->getCityName($route->from),
                        'to' => $this->getCityName($route->to),
                    ]);
            }
            $points = $routePointAwards[$playerId] ?? 0;
            $this->removeTrainCars($playerId, $route->number);
        }
        
        $message = $ferryCardsUsed > 0
            ? clienttranslate('${player_name} gains ${points} point(s) by claiming route from ${from} to ${to} using ${ferryCardsUsed} Ferry card(s) and these Train Car cards: ${colors}')
            : ($claimWithBulletTrain ?
            clienttranslate('${player_name} claims a bullet train route from ${from} to ${to} with ${number} train car(s) : ${colors}') :
            clienttranslate('${player_name} gains ${points} point(s) by claiming route from ${from} to ${to} with ${number} train car(s) : ${colors}'));
        $args = [
            'playerId' => $playerId,
            'player_name' => $this->getPlayerNameById($playerId),
            'points' => $points,
            'routeId' => $route->id,
            'from' => $this->getCityName($route->from),
            'to' => $this->getCityName($route->to),
            'number' => $cardCost,
            'removeCards' => $cardsToRemove,
            'colors' => array_map(fn($card) => $card->type, $cardsToRemove),
            'remainingTrainCars' => $this->getRemainingTrainCarsCount($playerId),
            'shifted' => $shifted,
            'shiftIndex' => $shiftIndex,
            'ferryCardsUsed' => $ferryCardsUsed,
            'ferryCardsCount' => $ferryCardsCount,
            'returnedTrackPiece' => $returnedTrackPiece,
        ];
        if ($shifted) {
            $args['shifted'] = true;
        }
        if ($claimWithBulletTrain) {
            $args['claimWithBulletTrain'] = true;
            $args['remainingBulletTrains'] = $remainingBulletTrains;
            $args['bulletTrainPosition'] = $bulletTrainPosition;
        }

        $this->notify->all('claimedRoute', $message, $args);

        $this->playerStats->inc('claimedRoutes', 1, $playerId, updateTableStat: true);
        $this->playerStats->inc('playedTrainCars', $route->number, $playerId, updateTableStat: true);
        foreach ($routePointAwards as $scoringPlayerId => $awardedPoints) {
            $this->playerStats->inc('pointsWithClaimedRoutes', $awardedPoints, $scoringPlayerId, updateTableStat: true);
        }

        $this->getMap()->onClaimRoute($this, $playerId, $route);

        if ($route->drawingBonus > 0) {
            $drawNumber = $this->trainCarManager->drawBonusTrainCarCardsFromDeck($playerId, $route->drawingBonus);
            $this->playerStats->inc('collectedTrainCarCards', $drawNumber, $playerId, updateTableStat: true);
            $this->playerStats->inc('collectedHiddenTrainCarCards', $drawNumber, $playerId, updateTableStat: true);
        }

        if ($claimWithBulletTrain) {
            // we may have completed destination for other players as well
            $playerIds = $this->getPlayersIds();
            foreach ($playerIds as $pId) {
                $this->destinationManager->checkCompletedDestinations($pId);
            }
        } else {
            $this->destinationManager->checkCompletedDestinations($playerId);
        }

        // in case there is less than 5 visible cards on the table, we refill with newly discarded cards
        $this->trainCarManager->checkVisibleTrainCarCards();
    }

    /** Offer a City Marker after each successful claim before continuing the turn. */
    function getStateAfterRouteClaim(int $routeId, string $nextState): string {
        $nextState = $this->getMap()->getStateAfterRouteClaim($this, $nextState);
        if ($this->getMap()->cityMarkers === null) {
            return $nextState;
        }
        $this->bga->globals->set('CITY_MARKER_PLACEMENT', ['routeId' => $routeId, 'nextState' => $nextState]);
        return \Bga\Games\TicketToRide\States\PlaceCityMarker::class;
    }

    function endTunnelAttempt(bool $storedTunnelAttempt): void {
        // put back tunnel cards
        $this->trainCarManager->trainCars->moveAllCardsInLocation('tunnel', 'discard');

        if ($storedTunnelAttempt) {
            $this->deleteGlobalVariable(TUNNEL_ATTEMPT);
        }
    }

    function getLogTo(Destination $destination): string {
        return is_array($destination->to) ? implode(' / ', array_map(fn($to) => $this->getCityName($to), $destination->to)) : $this->getCityName($destination->to);
    }

    function getCityName(int $id): string {
        return $this->getMap()->cities[$id]->name;
    }

    /**
     * @return int[]
     */
    function getPlayersIds(): array {
        return array_keys($this->loadPlayersBasicInfos());
    }

    function incScore(int $playerId, int $delta, ?string $message = null, array $messageArgs = []): void {
        $this->playerScore->inc($playerId, $delta, null);

        $this->notify->all('points', $message !== null ? $message : '', [
            'playerId' => $playerId,
            'player_name' => $this->getPlayerNameById($playerId),
            'points' => $this->playerScore->get($playerId),
            'delta' => $delta,
        ] + $messageArgs);
    }

    function getUniqueIntValueFromDB(string $sql): int {
        return intval($this->getUniqueValueFromDB($sql));
    }

    /**
     * Save (insert or update) any object/array as variable.
     */
    function setGlobalVariable(string $name, mixed $obj): void {
        $jsonObj = json_encode($obj);
        $this->DbQuery("INSERT INTO `global_variables`(`name`, `value`)  VALUES ('$name', '$jsonObj') ON DUPLICATE KEY UPDATE `value` = '$jsonObj'");
    }

    /**
     * Return a variable object/array.
     * To force object/array type, set $asArray to false/true.
     */
    function getGlobalVariable(string $name, ?bool $asArray = null): mixed {
        $json_obj = $this->getUniqueValueFromDB("SELECT `value` FROM `global_variables` where `name` = '$name'");
        if ($json_obj) {
            $object = json_decode($json_obj, $asArray);
            return $object;
        } else {
            return null;
        }
    }

    /**
     * Delete a variable object/array.
     */
    function deleteGlobalVariable(string $name): void {
        $this->DbQuery("DELETE FROM `global_variables` where `name` = '$name'");
    }

    function getExpansionOption(): int {
        $expansion = $this->getMap()->expansion;
        return $expansion !== null ? $this->tableOptions->get($expansion) : 0;
    }

    function getMapCode(): string { 
        if (Table::getBgaEnvironment() === 'studio') { return MAP_LIST[13]; }
        return MAP_LIST[match (__NAMESPACE__) {
            'Bga\\Games\\TicketToRide' => 1,
            'Bga\\Games\\TicketToRideEurope' => 2,
            default => (int)$this->getUniqueValueFromDB("SELECT `global_value` FROM `global` where `global_id` = ".MAP_OPTION),
        }];
    }

    function getMap(): Map {
        if (!isset($this->map)) {
            $mapCode = $this->getMapCode();

            require_once(__DIR__.'/../maps/'.$mapCode.'/map.php');

            $this->map = getMap();
            $this->map->setCode($mapCode);
        }
        return $this->map;
    }

    function getLowestTrainCarsCount(): int {
        return $this->getUniqueIntValueFromDB("SELECT min(`player_remaining_train_cars`) FROM player");
    }

    function getRemainingTrainCarsCount(int $playerId): int {
        return $this->getUniqueIntValueFromDB("SELECT `player_remaining_train_cars` FROM player WHERE player_id = $playerId");
    }

    /**
     * @return ClaimedRoute[]
     */
    function getClaimedRoutes(?int $playerId = null): array {
        $sql = "SELECT route_id, player_id, shift_index FROM claimed_routes ";
        if ($playerId !== null) {
            $sql .= "WHERE player_id = $playerId OR player_id = -1";
        }
        $sql .= " ORDER BY route_id, shift_index";
        $dbResults = $this->getObjectListFromDB($sql);
        return array_map(fn($dbResult) => new ClaimedRoute($dbResult), array_values($dbResults));
    }

    public function returnRightOfWay(int $playerId): void {
        $cards = $this->bga->globals->get("TECHNOLOGY_CARDS_{$playerId}", []);
        $cards = array_values(array_filter($cards, fn($type) => $type !== 10));
        $remaining = $this->bga->globals->get('REMAINING_TECHNOLOGY_CARDS', []);
        $remaining[10]++;
        $this->bga->globals->set("TECHNOLOGY_CARDS_{$playerId}", $cards);
        $this->bga->globals->set('REMAINING_TECHNOLOGY_CARDS', $remaining);
        $this->bga->globals->set('RIGHT_OF_WAY_PENDING', false);
        $this->notify->all('technologyCardReturned', clienttranslate('${player_name} returns Right of Way to the table'), [
            'playerId' => $playerId,
            'player_name' => $this->getPlayerNameById($playerId),
            'type' => 10,
            'remainingCount' => $remaining[10],
        ]);
    }

    public function thermocompressorAfterRouteClaim(int $playerId): bool {
        if (!$this->getMap()->useTechnologyCards) {
            return false;
        }
        $remaining = (int) $this->bga->globals->get('THERMOCOMPRESSOR_REMAINING', 0);
        if ($remaining === 0) {
            return false;
        }
        $remaining--;
        $this->bga->globals->set('THERMOCOMPRESSOR_REMAINING', $remaining);
        if ($remaining > 0 && count($this->mapManager->claimableRoutes(
            $playerId,
            $this->trainCarManager->getPlayerHand($playerId),
            $this->getRemainingTrainCarsCount($playerId),
        )) > 0) {
            return true;
        }
        $this->returnThermocompressor($playerId);
        return false;
    }

    public function returnThermocompressor(int $playerId): void {
        $cards = $this->bga->globals->get("TECHNOLOGY_CARDS_{$playerId}", []);
        $cards = array_values(array_filter($cards, fn($type) => $type !== 11));
        $remaining = $this->bga->globals->get('REMAINING_TECHNOLOGY_CARDS', []);
        $remaining[11]++;
        $this->bga->globals->set("TECHNOLOGY_CARDS_{$playerId}", $cards);
        $this->bga->globals->set('REMAINING_TECHNOLOGY_CARDS', $remaining);
        $this->bga->globals->set('THERMOCOMPRESSOR_REMAINING', 0);
        $this->notify->all('technologyCardReturned', clienttranslate('${player_name} returns Thermocompressor to the table'), [
            'playerId' => $playerId,
            'player_name' => $this->getPlayerNameById($playerId),
            'type' => 11,
            'remainingCount' => $remaining[11],
        ]);
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
        if (count($this->getObjectListFromDB("SHOW COLUMNS FROM claimed_routes LIKE 'shift_index'")) === 0) {
            $this->applyDbUpgradeToAllDB('ALTER TABLE DBPREFIX_claimed_routes ADD shift_index TINYINT unsigned NOT NULL DEFAULT 0');
        }
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
