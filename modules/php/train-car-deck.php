<?php

require_once(__DIR__.'/objects/train-car.php');

trait TrainCarDeckTrait {

    /**
     * Create cards, place 5 on table, and check revealed cards are valid.
     */
    public function createTrainCars() {
        for ($color = 0; $color <= 8; $color++) {
            $trainCars[] = [ 'type' => $color, 'type_arg' => null, 'nbr' => ($color == 0 ? NUMBER_OF_LOCOMOTIVE_CARDS : NUMBER_OF_COLORED_CARDS)];
        }
        $this->trainCars->createCards($trainCars, 'deck');
        $this->trainCars->shuffle('deck');

        $this->placeNewTrainCarCardsOnTable();
        $this->checkTooMuchLocomotives();
    }

    /**
     * Give initial cards to each player.
     */
    public function giveInitialTrainCarCards(array $playersIds) {
		foreach ($playersIds as $playerId) {
            $this->trainCars->pickCards(INITIAL_TRAIN_CAR_CARDS_IN_HAND, 'deck', $playerId);
        }
    }

    /**
     * List visible cards.
     * Optional filter can return only card play can draw.
     */
    public function getVisibleTrainCarCards(bool $limitToSelectableOnSecondPick = false) {
        $cards = $this->getTrainCarsFromDb($this->trainCars->getCardsInLocation('table'));

        if ($limitToSelectableOnSecondPick && VISIBLE_LOCOMOTIVES_COUNTS_AS_TWO_CARDS) {
            $cards = array_values(array_filter($cards, function ($card) { return $card->type != 0; }));
        }

        return $cards;
    }

    /**
     * Draw 1 or 2 hidden cards, to player hand.
     */
    public function drawTrainCarCardsFromDeck(int $playerId, int $number, bool $isSecondCard = false) {
        if ($number != 1 && $number != 2) {
            throw new BgaUserException("You must take one or two cards.");
        }
        
        if ($number == 2 && $isSecondCard) {
            throw new BgaUserException("You must take one card.");
        }

        $cards = $this->getTrainCarsFromDb($this->trainCars->pickCards($number, 'deck', $playerId));

        $this->notifyAllPlayers('trainCarPicked', clienttranslate('${player_name} takes ${number} hidden train car card(s)'), [
            'playerId' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
            'number' => $number,
            'remainingTrainCarsInDeck' => $this->getRemainingTrainCarCardsInDeck(),
            '_private' => [
                $playerId => [
                    'cards' => $cards,
                ],
            ],
        ]);
    }

    /**
     * Draw 1 visible card, to player hand.
     */
    public function drawTrainCarCardsFromTable(int $playerId, int $id, bool $isSecondCard = false) { // return card
        $card = $this->getTrainCarFromDb($this->trainCars->getCard($id));

        if ($card->location != 'table') {
            throw new BgaUserException("You can't take this visible card.");
        }

        if ($isSecondCard && $card->type == 0 && VISIBLE_LOCOMOTIVES_COUNTS_AS_TWO_CARDS) {
            throw new BgaUserException("You can't take a locomotive as a second card.");
        }

        $spot = $card->location_arg;

        $this->trainCars->moveCard($id, 'hand', $playerId);

        $this->placeNewTrainCarCardOnTable($spot);

        $this->notifyAllPlayers('trainCarPicked', clienttranslate('${player_name} takes a visible train car card'), [
            'playerId' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
            'number' => 1,
            'remainingTrainCarsInDeck' => $this->getRemainingTrainCarCardsInDeck(),
            'cards' => $this->getTrainCarsFromDb($this->trainCars->getCards([$id])),
        ]);

        $this->checkTooMuchLocomotives();

        return $card;
    }

    /**
     * get remaining cards in deck (can include discarded ones, to know how many cards player can pick).
     */
    public function getRemainingTrainCarCardsInDeck(bool $includeDiscard = false) {
        $remaining = intval($this->trainCars->countCardInLocation('deck'));

        if ($includeDiscard) {
            $remaining += intval($this->trainCars->countCardInLocation('discard'));
        }

        return $remaining;
    }

    /**
     * reset visible cards if there is 3 or more locomotives
     */
    private function checkTooMuchLocomotives() {
        if (RESET_VISIBLE_CARDS_WITH_LOCOMOTIVES === null) {
            return;
        }

        $cards = $this->getVisibleTrainCarCards();
        $locomotives = count(array_filter($cards, function ($card) { return $card->type == 0; }));
        if ($locomotives >= RESET_VISIBLE_CARDS_WITH_LOCOMOTIVES) {
            $this->trainCars->moveAllCardsInLocation('table', 'discard');
            $this->placeNewTrainCarCardsOnTable();

            $this->checkTooMuchLocomotives();
        }
    }

    /**
     * replace all visible cards
     */
    private function placeNewTrainCarCardsOnTable() {
        $cards = [];
        
        for ($i=1; $i<=5; $i++) {
            $cards[] = $this->getTrainCarFromDb($this->trainCars->pickCardForLocation('deck', 'table', $i));
        }

        $this->notifyAllPlayers('newCardsOnTable', clienttranslate('Three locomotives have been revealed, visible train cards are replaced'), [
            'cards' => $cards
        ]);

        $this->incStat(1, 'visibleCardsReplaced');

        return $cards;
    }

    /**
     * replace a visible card
     */
    private function placeNewTrainCarCardOnTable(int $spot) {
        $card = $this->getTrainCarFromDb($this->trainCars->pickCardForLocation('deck', 'table', $spot));

        $this->notifyAllPlayers('newCardsOnTable', '', [
            'cards' => [$card]
        ]);

        return [$card];
    }
}
