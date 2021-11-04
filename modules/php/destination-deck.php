<?php

require_once(__DIR__.'/objects/destination.php');

class DestinationDeck {
    private /*object*/ $game;
    private /*int*/ $initialCardPick;
    private /*int*/ $minimumInitialCardKept;
    private /*int*/ $additionalCardPick;
    private /*int*/ $minimumAdditionalCardKept;
    private /*bool*/ $unusedGoToDeckBottom;

    function __construct(object &$game, /* TODO pass selff:new as parameter ? */ int $initialCardPick = 3, int $minimumInitialCardKept = 2, int $additionalCardPick = 3, int $minimumAdditionalCardKept = 1, bool $unusedGoToDeckBottom = true) {
        $this->game = $game;
        $this->initialCardPick = $initialCardPick;
        $this->minimumInitialCardKept = $minimumInitialCardKept;
        $this->additionalCardPick = $additionalCardPick;
        $this->minimumAdditionalCardKept = $minimumAdditionalCardKept;
        $this->unusedGoToDeckBottom = $unusedGoToDeckBottom;

        $this->game->destinations->init("destination");  
	}

    public function createDestinations() {
        foreach($this->game->DESTINATIONS as $type => $typeDestinations) {
            foreach($typeDestinations as $typeArg => $destination) {
                $destinations[] = [ 'type' => 1, 'type_arg' => $typeArg, 'nbr' => 1];
            }
        }
        $this->game->destinations->createCards($destinations, 'deck');
        $this->game->destinations->shuffle('deck');
    }
	
    public function pickInitialCards() {
		$this->pickCards($this->initialCardPick);
    }	

    public function keepInitialCards(int $playerId, array $ids) {
		$this->keepCards($playerId, $ids, $this->minimumInitialCardKept);
    }	
	
    public function pickAdditionalCards() {
		$this->pickCards($this->additionalCardPick);
    }	

    public function keepAdditionalCards(int $playerId, array $ids) {
		$this->keepCards($playerId, $ids, $this->minimumAdditionalCardKept);
    }

    public function getPickedCards() {
        return $this->getDestinationsFromDb($this->game->destinations->getCardsInLocation('pick'));
    }

    private function pickCards(int $number) {
        $cards = $this->getDestinationsFromDb($this->game->destinations->pickCardsForLocation($number,'deck', 'pick'));
        return $cards;
    }

    private function keepCards(int $playerId, array $ids, int $minimum) {
        if (count($ids) < $minimum) {
            throw new BgaSystemException("You must keep at least $minimum cards.");
        }

        if (count($ids) > 0 && intval(self::getUniqueValueFromDB( "SELECT count(*) FROM destination WHERE `card_location` != 'pick' AND `card_id` in (".implode(', ', $ids).")")) > 0) {
            throw new BgaSystemException("Selected cards are not available.");
        }

        $cards = $this->getDestinationsFromDb($this->game->destinations->moveCards($ids, 'hand', $playerId));

        $remainingCardsInPick = intval($this->game->destinations->countCardInLocation('pick'));
        if ($remainingCardsInPick > 0) {
            if ($this->unusedGoToDeckBottom) {
                $this->game->destinations->shuffle('pick');
                // we put remaining cards in pick at the bottom of the deck
                self::DbQuery("UPDATE destination SET `card_location_arg` = card_location_arg + $remainingCardsInPick");
                $this->game->destinations->moveAllCardsInLocationKeepOrder('pick', 'deck');
            } else {
                $this->game->destinations->moveAllCardsInLocationKeepOrder('pick', 'discard');
            }
        }

        return $cards;
    }

    //////////////////////////////////////////////////////////////////////////////
    //////////// Utility functions
    ////////////
    // TODO Duplicate with Utils, chose where to keep it (probably here)
    
    function getDestinationFromDb($dbObject) {
        if (!$dbObject || !array_key_exists('id', $dbObject)) {
            throw new BgaSystemException("Destination doesn't exists ".json_encode($dbObject));
        }
        return new Destination($dbObject, $this->game->DESTINATIONS);
    }

    function getDestinationsFromDb(array $dbObjects) {
        return array_map(function($dbObject) { return $this->getDestinationFromDb($dbObject); }, array_values($dbObjects));
    }
}
