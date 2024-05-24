<?php

require_once(__DIR__.'/objects/destination.php');

trait DestinationDeckTrait {

    /**
     * Create destination cards.
     */
    public function createDestinations() {
        $destinations = $this->getDestinationToGenerate();

        foreach($destinations as $deck => $cards) {
            $this->destinations->createCards($cards, $deck);
            $this->destinations->shuffle($deck);
        }
    }
	
    /**
     * Pick destination cards for beginning choice.
     */
    public function pickInitialDestinationCards(int $playerId) {
        $pick = $this->getInitialDestinationPick();

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
		$this->keepDestinationCards($playerId, $ids, $this->getInitialDestinationMinimumKept(), UNUSED_INITIAL_DESTINATIONS_GO_TO_DECK_BOTTOM);
    }	
	
    /**
     * Pick destination cards for pick destination action.
     */
    public function pickAdditionalDestinationCards(int $playerId) {
		return $this->pickDestinationCards($playerId, $this->getAdditionalDestinationCardNumber());
    }	

    /**
     * Select kept destination cards for pick destination action. 
     * Unused destination cards are set back on the deck or discarded.
     */
    public function keepAdditionalDestinationCards(int $playerId, array $ids) {
		$this->keepDestinationCards($playerId, $ids, ADDITIONAL_DESTINATION_MINIMUM_KEPT, UNUSED_ADDITIONAL_DESTINATIONS_GO_TO_DECK_BOTTOM);
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
            throw new BgaUserException("You must keep at least $minimum cards.");
        }

        if (count($ids) > 0 && $this->getUniqueIntValueFromDB("SELECT count(*) FROM destination WHERE `card_location` != 'pick$playerId' AND `card_id` in (".implode(', ', $ids).")") > 0) {
            throw new BgaUserException("Selected cards are not available.");
        }

        $cards = $this->getDestinationsFromDb($this->destinations->getCards($ids));
        if (count(array_filter($cards, fn($card) => $card->type == 2)) > 1) {
            throw new BgaUserException($this->_("You cannot keep both long routes."));
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

        $this->notifyAllPlayers('destinationsPicked', clienttranslate('${player_name} keeps ${count} destinations'), [
            'playerId' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
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
}
