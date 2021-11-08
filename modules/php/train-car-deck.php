<?php

require_once(__DIR__.'/objects/train-car.php');

class TrainCarDeck {
    /** Access to main game functions. */
    private /*object*/ $game;
    /** Number of train car cards in hand, for each player, at the beginning of the game. */
    private /*int*/ $initialCardsInHand;
    /** Says if it is possible to take only one visible locomotive. */
    private /*bool*/ $visibleLocomotiveAsTwoCards;
    /** Resets visible cards when 3 locomotives are visible. */
    private /*bool*/ $resetVisibleCardsAtThreeLocomotives;
    /** Number of visible cards. */
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

    /**
     * Create cards, place 5 on table, and check revealed cards are valid.
     */
    public function createTrainCars() {
        for ($color = 0; $color <= 8; $color++) {
            $trainCars[] = [ 'type' => $color, 'type_arg' => null, 'nbr' => ($color == 0 ? NUMBER_OF_LOCOMOTIVE_CARDS : NUMBER_OF_COLORED_CARDS)];
        }
        $this->trainCars->createCards($trainCars, 'deck');
        $this->trainCars->shuffle('deck');

        $this->placeNewCardsOnTable();
        $this->checkTooMuchLocomotives();
    }

    /**
     * Give initial cards to each player.
     */
    public function giveInitialCards(array $playersIds) {
		foreach ($playersIds as $playerId) {
            $this->trainCars->pickCards($this->initialCardsInHand, 'deck', $playerId);
        }
    }

    /**
     * List visible cards.
     * Optional filter can return only card play can draw.
     */
    public function getVisibleCards(bool $limitToSelectableOnSecondPick = false) {
        $cards = $this->game->getTrainCarsFromDb($this->trainCars->getCardsInLocation('table'));

        if ($limitToSelectableOnSecondPick && $this->visibleLocomotiveAsTwoCards) {
            $cards = array_values(array_filter($cards, function ($card) { return $card->type != 0; }));
        }

        return $cards;
    }

    /**
     * Draw 1 or 2 hidden cards, to player hand.
     */
    public function drawFromDeck(int $playerId, int $number, bool $isSecondCard = false) {
        if ($number != 1 && $number != 2) {
            throw new BgaUserException("You must take one or two cards.");
        }
        
        if ($number == 2 && $isSecondCard) {
            throw new BgaUserException("You must take one card.");
        }

        $cards = $this->game->getTrainCarsFromDb($this->trainCars->pickCards($number, 'deck', $playerId));

        /* TODO $this->game->notifyAllPlayers('trainCarPicked', clienttranslate('${player_name} takes ${number} hidden train car card(s)'), [
            'playerId' => $playerId,
            'player_name' => $this->game->getPlayerName($playerId),
            'number' => $number,
            'remainingTrainCarsInDeck' => $this->getRemainingCardsInDeck(),
            '_private' => [
                $playerId => [
                    'cards' => $cards,
                ],
            ],
        ]);*/
    }

    /**
     * Draw 1 visible card, to player hand.
     */
    public function drawFromTable(int $playerId, int $id, bool $isSecondCard = false) { // return card
        $card = $this->game->getTrainCarFromDb($this->trainCars->getCard($id));

        if ($card->location != 'table') {
            throw new BgaUserException("You can't take this visible card.");
        }

        if ($isSecondCard && $card->type == 0 && $visibleLocomotiveAsTwoCards) {
            throw new BgaUserException("You can't take a locomotive as a second card.");
        }

        $spot = $card->location_arg;

        $this->trainCars->moveCard($id, 'hand', $playerId);

        $this->placeNewCardOnTable($spot);

        /* $this->game->notifyAllPlayers('trainCarPicked', clienttranslate('${player_name} takes a visible train car card'), [
            'playerId' => $playerId,
            'player_name' => $this->game->getPlayerName($playerId),
            'number' => 1,
            'remainingTrainCarsInDeck' => $this->getRemainingCardsInDeck(),
            '_private' => [
                $playerId => [
                    'cards' => $this->game->getTrainCarsFromDb($this->trainCars->getCards([$id])),
                ],
            ],
        ]);*/

        $this->checkTooMuchLocomotives();

        return $card;
    }

    /**
     * get remaining cards in deck (can include discarded ones, to know how many cards player can pick).
     */
    public function getRemainingCardsInDeck(bool $includeDiscard = false) {
        $remaining = intval($this->trainCars->countCardInLocation('deck'));

        if ($includeDiscard) {
            $remaining += intval($this->trainCars->countCardInLocation('discard'));
        }

        return $remaining;
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
            $cards[] = $this->game->getTrainCarFromDb($this->trainCars->pickCardForLocation('deck', 'table', $i));
        }

        $this->game->notifyAllPlayers('newCardsOnTable', clienttranslate('Three locomotives have been revealed, visible train cards are replaced'), [
            'cards' => $cards
        ]);

        $this->game->incStat(1, 'visibleCardsReplaced');

        return $cards;
    }

    private function placeNewCardOnTable(int $spot) {
        $card = $this->game->getTrainCarFromDb($this->trainCars->pickCardForLocation('deck', 'table', $spot));

        $this->game->notifyAllPlayers('newCardsOnTable', '', [
            'cards' => [$card]
        ]);

        return $cards;
    }
}
