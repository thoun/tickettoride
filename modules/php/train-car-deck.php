<?php

require_once(__DIR__.'/objects/train-car.php');

class TrainCarDeck {
    private /*object*/ $game;
    private /*int*/ $initialCardsInHand;
    private /*bool*/ $visibleLocomotiveAsTwoCards;
    private /*bool*/ $resetVisibleCardsAtThreeLocomotives;
    private /*int*/ $tableCardsNumber = 5;

    function __construct(object &$game, int $initialCardsInHand = 4, bool $visibleLocomotiveAsTwoCards = true, bool $resetVisibleCardsAtThreeLocomotives = true) {
        $this->game = $game;
        $this->trainCars = $game->trainCars;
        $this->initialCardsInHand = $initialCardsInHand;
        $this->visibleLocomotiveAsTwoCards = $visibleLocomotiveAsTwoCards;
        $this->resetVisibleCardsAtThreeLocomotives = $resetVisibleCardsAtThreeLocomotives;

        $this->trainCars->init("traincar"); 
        $this->trainCars->autoreshuffle = true;
	}

    public function createTrainCars() {
        for ($color = 0; $color <= 8; $color++) {
            $trainCars[] = [ 'type' => $color, 'type_arg' => null, 'nbr' => ($color == 0 ? 14 : 12)];
        }
        $this->trainCars->createCards($trainCars, 'deck');
        $this->trainCars->shuffle('deck');

        $this->placeNewCardsOnTable();
        $this->checkTooMuchLocomotives();
    }

    public function giveInitialCards(array $playersIds) {
		foreach ($playersIds as $playerId) {
            $this->trainCars->pickCards($this->initialCardsInHand, 'deck', $playerId);
        }
    }

    public function getVisibleCards() {
        $cards = $this->game->getTrainCarsFromDb($this->trainCars->getCardsInLocation('table'));
        return $cards;
    }

    public function drawFromDeck(int $playerId, int $number, bool $isSecondCard = false) {
        if ($number != 1 && $number != 2) {
            throw new BgaSystemException("You must take one or two cards.");
        }
        
        if ($number == 2 && $isSecondCard) {
            throw new BgaSystemException("You must take one card.");
        }

        $this->trainCars->pickCards($number, 'deck', $playerId);
    }

    public function drawFromTable(int $playerId, int $id, bool $isSecondCard = false) { // return card
        $card = $this->game->getTrainCarFromDb($this->trainCars->getCard($id));

        if ($card->location != 'table') {
            throw new BgaSystemException("You can't take this visible card.");
        }

        if ($isSecondCard && $card->type == 0 && $visibleLocomotiveAsTwoCards) {
            throw new BgaSystemException("You can't take a locomotive as a second card.");
        }

        $spot = $card->location_arg;

        $this->trainCars->moveCard($id, 'hand', $playerId);

        $this->trainCars->pickCardForLocation('deck', 'table', $spot);

        $this->checkTooMuchLocomotives();

        return $card;
    }

    private function checkTooMuchLocomotives() {
        if (!$this->resetVisibleCardsAtThreeLocomotives) {
            return;
        }

        $cards = $this->getVisibleCards();
        $locomotives = count(array_filter($cards, function ($card) { return $card->type == 0; }));
        if ($locomotives >= 3) {
            $this->trainCars->moveAllCardsInLocation('table', 'discard');
            $this->placeNewCardsOnTable();
            $this->checkTooMuchLocomotives();
        }
    }

    private function placeNewCardsOnTable() {
        $cards = [];
        
        for ($i=1; $i<=5; $i++) {
            $cards[] = $this->getCardFromDb($this->trainCars->pickCardForLocation('deck', 'table', $i));
        }

        return $cards;
    }
}
