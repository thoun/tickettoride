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
            $cards = array_values(array_filter($cards, fn($card) => $card->type != 0));
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

        $remainingTrainCarCardsInDeck = $this->getRemainingTrainCarCardsInDeck(true);

        if ($number == 2 && $remainingTrainCarCardsInDeck == 1) {
            $number = 1;
        }

        if ($number > $remainingTrainCarCardsInDeck) {
            throw new BgaUserException(self::_("You can't take train car cards because the deck is empty"));
        }

        $cards = $this->getTrainCarsFromDb($this->trainCars->pickCards($number, 'deck', $playerId));

        $this->notifyAllPlayers('trainCarPicked', clienttranslate('${player_name} takes ${count} hidden train car card(s)'), [
            'playerId' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
            'number' => $number,
            'count' => $number,
            'remainingTrainCarsInDeck' => $this->getRemainingTrainCarCardsInDeck(),
            'origin' => 0, // 0 means hidden
        ]);

        $this->notifyPlayer($playerId, 'trainCarPicked', clienttranslate('You take hidden train car card(s) ${colors}'), [
            'playerId' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
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
            throw new BgaUserException("You can't take this visible card.");
        }

        if ($isSecondCard && $card->type == 0 && VISIBLE_LOCOMOTIVES_COUNTS_AS_TWO_CARDS) {
            throw new BgaUserException("You can't take a locomotive as a second card.");
        }

        $spot = $card->location_arg;

        $this->trainCars->moveCard($id, 'hand', $playerId);

        $this->notifyAllPlayers('trainCarPicked', clienttranslate('${player_name} takes ${color}'), [
            'playerId' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
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
        if (RESET_VISIBLE_CARDS_WITH_LOCOMOTIVES === null) {
            return;
        }

        $cards = $this->getVisibleTrainCarCards();
        $locomotives = count(array_filter($cards, fn($card) => $card->type == 0));
        if ($locomotives >= RESET_VISIBLE_CARDS_WITH_LOCOMOTIVES && $this->getRemainingTrainCarCardsInDeck(true) > 0) {
            if ($attempts >= 3) {
                $this->notifyAllPlayers('log', clienttranslate('Three locomotives have been revealed multiples times in a row, they will stay visible'), []);
            } else {
                $this->trainCars->moveAllCardsInLocation('table', 'discard');
                $this->placeNewTrainCarCardsOnTable();

                $this->checkTooMuchLocomotives($attempts + 1);
            }
        }
    }

    /**
     * replace all visible cards
     */
    private function placeNewTrainCarCardsOnTable() {
        $cards = [];
        $spots = [];
        
        for ($i=1; $i<=5; $i++) {
            $newCard = $this->getTrainCarFromDb($this->trainCars->pickCardForLocation('deck', 'table', $i));
            if ($newCard !== null) {
                $cards[] = $newCard;
            }
            $spots[$i] = $newCard;
        }

        $this->notifyAllPlayers('highlightVisibleLocomotives', clienttranslate('Three locomotives have been revealed, visible train cards are replaced'), []);

        if (count($cards) > 0) {
            $this->notifyAllPlayers('newCardsOnTable', '', [
                'cards' => $cards,
                'spotsCards' => $spots,
                'remainingTrainCarsInDeck' => $this->getRemainingTrainCarCardsInDeck(),
                'locomotiveRefill' => true,
            ]);
        }

        $this->incStat(1, 'visibleCardsReplaced');

        return $cards;
    }

    /**
     * replace a visible card
     */
    private function placeNewTrainCarCardOnTable(int $spot) {
        $card = $this->getTrainCarFromDb($this->trainCars->pickCardForLocation('deck', 'table', $spot));

        $this->notifyAllPlayers('newCardsOnTable', '', [
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
                $this->notifyAllPlayers('newCardsOnTable', '', [
                    'spotsCards' => $spots,
                    'remainingTrainCarsInDeck' => $this->getRemainingTrainCarCardsInDeck(),
                    'locomotiveRefill' => false,
                ]);

                $this->checkTooMuchLocomotives();
            }
        }
    }

    private function canTakeASecondCard(/*int | null*/ $firstCardType) { // null if unknown/hidden
        if ($firstCardType === 0) {
            // if the player chose a locomotive
            return false;
        }

        $remainingTrainCarCardsInDeck = $this->getRemainingTrainCarCardsInDeck(true);
        if ($remainingTrainCarCardsInDeck == 0) {
            // if there is no hidden card and all remaining visible cards are locomotives, it's impossible to take a second card
            $tableCards = $this->getVisibleTrainCarCards();
            return !$this->array_every($tableCards, fn($tableCard) => $tableCard->type == 0); 
        }

        return true;
    }

    public function trainCarDeckAutoReshuffle() {
        $this->notifyAllPlayers('log', clienttranslate('The train car deck has been reshuffled'), []);
    }
}
