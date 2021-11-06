<?php

require_once(__DIR__.'/objects/destination.php');

class DestinationDeck {
    /** Access to main game functions. */
    private /*object*/ $game;
    /** Number of destinations cards shown at the beginning. */
    private /*int*/ $initialCardPick;
    /** Minimum number of destinations cards to keep at the beginning. */
    private /*int*/ $minimumInitialCardKept;
    /** Number of destinations cards shown at pick destination action. */
    private /*int*/ $additionalCardPick;
    /** Minimum number of destinations cards to keep at pick destination action. */
    private /*int*/ $minimumAdditionalCardKept;
    /** Indicates if unpicked destinations cards go back to the bottom of the deck. */
    private /*bool*/ $unusedGoToDeckBottom;

    function __construct(object &$game, int $initialCardPick = 3, int $minimumInitialCardKept = 2, int $additionalCardPick = 3, int $minimumAdditionalCardKept = 1, bool $unusedGoToDeckBottom = true) {
        $this->game = $game;
        $this->destinations = $game->destinations;
        $this->initialCardPick = $initialCardPick;
        $this->minimumInitialCardKept = $minimumInitialCardKept;
        $this->additionalCardPick = $additionalCardPick;
        $this->minimumAdditionalCardKept = $minimumAdditionalCardKept;
        $this->unusedGoToDeckBottom = $unusedGoToDeckBottom;

        $this->destinations->init("destination");  
	}

    /**
     * Create cards.
     */
    public function createDestinations() {
        foreach($this->game->DESTINATIONS as $type => $typeDestinations) {
            foreach($typeDestinations as $typeArg => $destination) {
                $destinations[] = [ 'type' => 1, 'type_arg' => $typeArg, 'nbr' => 1];
            }
        }
        $this->destinations->createCards($destinations, 'deck');
        $this->destinations->shuffle('deck');
    }

    /**
     * Return the number of card to pick when the player draw new destinations.
     */
    public function getAdditionalCardPick() {
        return $this->additionalCardPick;
    }
	
    /**
     * Pick cards for beginning choice.
     */
    public function pickInitialCards(int $playerId) {
		return $this->pickCards($playerId, $this->initialCardPick);
    }	

    /**
     * Select kept cards for beginning choice. 
     * Unused cards are set back on the deck or discarded.
     */
    public function keepInitialCards(int $playerId, array $ids) {
		$this->keepCards($playerId, $ids, $this->minimumInitialCardKept);
    }	
	
    /**
     * Pick cards for pick destination action.
     */
    public function pickAdditionalCards(int $playerId) {
		return $this->pickCards($playerId, $this->additionalCardPick);
    }	

    /**
     * Select kept cards for pick destination action. 
     * Unused cards are set back on the deck or discarded.
     */
    public function keepAdditionalCards(int $playerId, array $ids) {
		$this->keepCards($playerId, $ids, $this->minimumAdditionalCardKept);
    }

    /**
     * Get picked cards (cards player can choose).
     */
    public function getPickedCards(int $playerId) {
        $cards = $this->game->getDestinationsFromDb($this->destinations->getCardsInLocation("pick$playerId"));
        return $cards;
    }

    /**
     * get remaining cards in deck.
     */
    public function getRemainingCardsInDeck() {
        $remaining = intval($this->destinations->countCardInLocation('deck'));
        return $remaining;
    }

    private function pickCards($playerId, int $number) {
        $cards = $this->game->getDestinationsFromDb($this->destinations->pickCardsForLocation($number, 'deck', "pick$playerId"));
        return $cards;
    }

    private function keepCards(int $playerId, array $ids, int $minimum) {
        if (count($ids) < $minimum) {
            throw new BgaSystemException("You must keep at least $minimum cards.");
        }

        if (count($ids) > 0 && $this->game->getUniqueIntValueFromDB("SELECT count(*) FROM destination WHERE `card_location` != 'pick$playerId' AND `card_id` in (".implode(', ', $ids).")") > 0) {
            throw new BgaSystemException("Selected cards are not available.");
        }

        $this->destinations->moveCards($ids, 'hand', $playerId);

        $remainingCardsInPick = intval($this->destinations->countCardInLocation("pick$playerId"));
        if ($remainingCardsInPick > 0) {
            if ($this->unusedGoToDeckBottom) {
                $this->destinations->shuffle("pick$playerId");
                // we put remaining cards in pick at the bottom of the deck
                $this->game->DbQuery("UPDATE destination SET `card_location_arg` = card_location_arg + $remainingCardsInPick WHERE `card_location` = 'deck'");
                $this->destinations->moveAllCardsInLocationKeepOrder("pick$playerId", 'deck');
            } else {
                // we discard remaining cards in pick
                $this->destinations->moveAllCardsInLocationKeepOrder("pick$playerId", 'discard');
            }
        }
    }
}
