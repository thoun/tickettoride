<?php

trait DebugUtilTrait {

//////////////////////////////////////////////////////////////////////////////
//////////// Utility functions
////////////

    function debugSetup() {
        if ($this->getBgaEnvironment() != 'studio') { 
            return;
        }

        $this->debugSetDestinationInHand(7, 2343492);

        $this->debugClaimAllRoutes(2343492, 0.3);
        $this->debugSetLastTurn();

        //$this->debugSetRemainingTrainCarDeck(1);

        //$this->gamestate->changeActivePlayer(2343492);
    }

    function debugSetDestinationInHand($cardType, $playerId) {
        $card = $this->getDestinationFromDb(array_values($this->destinations->getCardsOfType(1, $cardType))[0]);
        $this->destinations->moveCard($card->id, 'hand', $playerId);
        return $card;
    }

    function debugClaimRoute($playerId, $routeId) {
        self::DbQuery("INSERT INTO `claimed_routes` (`route_id`, `player_id`) VALUES ($routeId, $playerId)");
    }

    function debugClaimAllRoutes($playerId, $ratio = 1) {
        foreach($this->ROUTES as $id => $route) {
            if ((bga_rand(0, count($this->ROUTES)-1) / (float)count($this->ROUTES)) < $ratio) {
                $this->debugClaimRoute($playerId, $id);
            }
        }
    }

    function debugSetRemainingTrainCarDeck(int $remaining) {
        $moveNumber = $this->getRemainingTrainCarCardsInDeck() - $remaining;
        $this->trainCars->pickCardsForLocation($moveNumber, 'deck', 'discard');
    }

    function debugSetLastTurn() {
        self::DbQuery("UPDATE player SET `player_remaining_train_cars` = ".TRAIN_CARS_NUMBER_TO_START_LAST_TURN);
    }

    function debug($debugData) {
        if ($this->getBgaEnvironment() != 'studio') { 
            return;
        }die('debug data : '.json_encode($debugData));
    }
}
