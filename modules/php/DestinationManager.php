<?php
declare(strict_types=1);

namespace Bga\Games\TicketToRide;

use Bga\GameFramework\Components\Deck;

class DestinationManager {

    public Deck $destinations;

    function __construct(
        protected Game $game,
    ) {
        $this->destinations = $this->game->deckFactory->createDeck('destination');
    }

    /**
     * Transforms a Destination Db object to Destination class.
     */    
    /*private but used for DebugUtilTrait*/ function getDestinationFromDb(?array $dbObject): \Destination {
        if (!$dbObject || !array_key_exists('id', $dbObject)) {
            throw new \BgaSystemException("Destination doesn't exists ".json_encode($dbObject));
        }
        return new \Destination($dbObject, $this->game->getMap()->destinations);
    }

    /**
     * Transforms a Destination Db object array to Destination class array.
     * 
     * @return \Destination[] 
     */    
    private function getDestinationsFromDb(array $dbObjects): array {
        return array_map(fn($dbObject) => $this->getDestinationFromDb($dbObject), array_values($dbObjects));
    }

    /**
     * Create destination cards.
     */
    public function createDestinations(): void {
        $expansionOption = $this->game->getExpansionOption();
        $destinations = $this->game->getMap()->getDestinationToGenerate($expansionOption);
        //debug($this->getMap()->code, $expansionOption, $destinations);

        foreach($destinations as $deck => $cards) {
            $this->destinations->createCards($cards, $deck);
            $this->destinations->shuffle($deck);
        }
    }
	
    /**
     * Pick destination cards for beginning choice.
     * 
     * @return \Destination[]
     */
    public function pickInitialDestinationCards(int $playerId): array {
        $expansionOption = $this->game->getExpansionOption();
        $pick = $this->game->getMap()->getInitialDestinationPick($expansionOption);

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
    public function keepInitialDestinationCards(int $playerId, array $ids): void {
		$this->keepDestinationCards($playerId, $ids, $this->game->getMap()->getInitialDestinationMinimumKept($this->game->getExpansionOption()), $this->game->getMap()->unusedInitialDestinationsGoToDeckBottom);
    }	
	
    /**
     * Pick destination cards for pick destination action.
     * 
     * @return \Destination[]
     */
    public function pickAdditionalDestinationCards(int $playerId): array {
		return $this->pickDestinationCards($playerId, $this->game->getMap()->getAdditionalDestinationCardNumber($this->game->getExpansionOption()));
    }	

    /**
     * Select kept destination cards for pick destination action. 
     * Unused destination cards are set back on the deck or discarded.
     */
    public function keepAdditionalDestinationCards(int $playerId, array $ids): void {
		$this->keepDestinationCards($playerId, $ids, $this->game->getMap()->additionalDestinationMinimumKept, $this->game->getMap()->unusedAdditionalDestinationsGoToDeckBottom);
    }

    /**
     * Get destination picked cards (cards player can choose).
     * 
     * @return \Destination[]
     */
    public function getPickedDestinationCards(int $playerId): array {
        $cards = $this->getDestinationsFromDb($this->destinations->getCardsInLocation("pick$playerId"));
        return $cards;
    }

    /**
     * get remaining destination cards in deck.
     */
    public function getRemainingDestinationCardsInDeck(): int {
        $remaining = intval($this->destinations->countCardInLocation('deck'));

        if ($remaining == 0) {
            $remaining = intval($this->destinations->countCardInLocation('discard'));
        }

        return $remaining;
    }

    /**
     * place a number of destinations cards to pick$playerId.
     * 
     * @return \Destination[]
     */
    private function pickDestinationCards($playerId, int $number, string $from = 'deck'): array {
        $cards = $this->getDestinationsFromDb($this->destinations->pickCardsForLocation($number, $from, "pick$playerId"));
        return $cards;
    }

    /**
     * move selected cards to player hand, and empty pick$playerId.
     */
    private function keepDestinationCards(int $playerId, array $ids, int $minimum, bool $toDeckBottom): void {
        if (count($ids) < $minimum) {
            throw new \BgaUserException("You must keep at least $minimum cards.");
        }

        if (count($ids) > 0 && $this->game->getUniqueIntValueFromDB("SELECT count(*) FROM destination WHERE `card_location` != 'pick$playerId' AND `card_id` in (".implode(', ', $ids).")") > 0) {
            throw new \BgaUserException("Selected cards are not available.");
        }

        $this->destinations->moveCards($ids, 'hand', $playerId);

        $remainingCardsInPick = intval($this->destinations->countCardInLocation("pick$playerId"));
        if ($remainingCardsInPick > 0) {
            if ($toDeckBottom) {
                $this->destinations->shuffle("pick$playerId");
                // we put remaining cards in pick at the bottom of the deck
                $this->game->DbQuery("UPDATE destination SET `card_location_arg` = card_location_arg + $remainingCardsInPick WHERE `card_location` = 'deck'");
                $this->destinations->moveAllCardsInLocationKeepOrder("pick$playerId", 'deck');
            } else {
                // we discard remaining cards in pick
                $this->destinations->moveAllCardsInLocation("pick$playerId", 'void');
            }
        }

        $this->game->notify->all('destinationsPicked', clienttranslate('${player_name} keeps ${count} destinations'), [
            'playerId' => $playerId,
            'player_name' => $this->game->getPlayerNameById($playerId),
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
    
    public function getPlayerHandCount(int $playerId): int {
        return intval($this->destinations->countCardInLocation('hand', $playerId));
    }
    
    /**
     * @return \Destination[]
     */
    public function getPlayerHand(int $playerId): array {
        return $this->getDestinationsFromDb($this->destinations->getCardsInLocation('hand', $playerId));
    }
    
    /**
     * @return \Destination[]
     */
    public function getCompletedDestinations(int $playerId): array {
        return $this->getDestinationsFromDb($this->destinations->getCards($this->getCompletedDestinationsIds($playerId)));
    }
    
    /**
     * @return \Destination[]
     */
    public function getUncompletedDestinations(int $playerId): array {
        return $this->getDestinationsFromDb($this->destinations->getCards($this->getUncompletedDestinationsIds($playerId)));
    }

    /**
     * @return int[]
     */
    public function getCompletedDestinationsIds(int $playerId): array {
        $sql = "SELECT `card_id` FROM `destination` WHERE `card_location` = 'hand' AND `card_location_arg` = $playerId AND  `completed` = 1";
        $dbResults = $this->game->getCollectionFromDB($sql);
        return array_map(fn($dbResult) => intval($dbResult['card_id']), array_values($dbResults));
    }

    /**
     * @return int[]
     */
    private function getUncompletedDestinationsIds(int $playerId): array {
        $sql = "SELECT `card_id` FROM `destination` WHERE `card_location` = 'hand' AND `card_location_arg` = $playerId AND  `completed` = 0";
        $dbResults = $this->game->getCollectionFromDB($sql);
        return array_map(fn($dbResult) => intval($dbResult['card_id']), array_values($dbResults));
    }

    function checkCompletedDestinations(int $playerId) {
        $handDestinations = $this->getPlayerHand($playerId);
        $alreadyCompleted = $this->getCompletedDestinationsIds($playerId);

        foreach($handDestinations as $destination) {
            if (!in_array($destination->id, $alreadyCompleted)) {
                $destinationRoutes = $this->game->getDestinationRoutes($playerId, $destination);
                if ($destinationRoutes != null) {
                    $this->game->DbQuery("UPDATE `destination` SET `completed` = 1 where `card_id` = $destination->id");

                    $this->game->notify->player($playerId, 'destinationCompleted', clienttranslate('${you} completed a new destination : ${from} - ${to}'), [
                        'playerId' => $playerId,
                        'player_name' => $this->game->getPlayerNameById($playerId),
                        'destination' => $destination,
                        'from' => $this->game->getCityName($destination->from),
                        'to' => $this->game->getLogTo($destination),
                        'you' => clienttranslate('You'),
                        'i18n' => ['you'],
                        'destinationRoutes' => $destinationRoutes,
                    ]);

                    $this->game->playerStats->inc('completedDestinations', 1, $playerId, updateTableStat: true);
                }
            }
        }
    }
}
