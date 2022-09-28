<?php

trait DebugUtilTrait {

//////////////////////////////////////////////////////////////////////////////
//////////// Utility functions
////////////

    function debugSetup() {
        if ($this->getBgaEnvironment() != 'studio') { 
            return;
        }

        //$this->debugSetDestinationInHand(7, 2343492);

        //$this->debugClaimAllRoutes(2343492, 0.2);
        //$this->debugClaimAllRoutes(2343493, 0.2);
        //$this->debugSetLastTurn();

        //$this->debugSetRemainingTrainCarDeck(1);

        //$this->gamestate->changeActivePlayer(2343492);
    }

    function debugSetDestinationInHand($cardType, $playerId) {
        $card = $this->getDestinationFromDb(array_values($this->destinations->getCardsOfType(1, $cardType))[0]);
        $this->destinations->moveCard($card->id, 'hand', $playerId);
        return $card;
    }

    function debugEmptyDeck() {
        $this->trainCars->moveAllCardsInLocation('deck', 'void');
    }
    
    function debugAlmostEmptyDeck() {
        $moveNumber = $this->getRemainingTrainCarCardsInDeck(true) - 1;
        $this->trainCars->pickCardsForLocation($moveNumber, 'deck', 'void');
    }

    function debugClaimRoute($playerId, $routeId) {
        self::DbQuery("INSERT INTO `claimed_routes` (`route_id`, `player_id`) VALUES ($routeId, $playerId)");

        $route = $this->ROUTES[$routeId];
        $points = $this->ROUTE_POINTS[$route->number];

        self::notifyAllPlayers('claimedRoute', clienttranslate('${player_name} gains ${points} point(s) by claiming route from ${from} to ${to} with ${number} train car(s) : ${colors}'), [
            'playerId' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
            'points' => $points,
            'route' => $route,
            'from' => $this->CITIES[$route->from],
            'to' => $this->CITIES[$route->to],
            'number' => $route->number,
            'removeCards' => [],
            'colors' => [],
        ]);
    }

    function debugClaimAllRoutes($playerId, $ratio = 0.1) {
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

    // select all 3 destinations for each player
    function debugStart() {
        $playersIds = $this->getPlayersIds();
        foreach ($playersIds as $playerId) {
            $destinations = $this->getPickedDestinationCards($playerId);
            $ids = array_map(fn($card) => $card->id, $destinations);
            $this->keepInitialDestinationCards($playerId, $ids);
        }

        $this->gamestate->jumpToState(ST_PLAYER_CHOOSE_ACTION);
    }

    function debug($debugData) {
        if ($this->getBgaEnvironment() != 'studio') { 
            return;
        }die('debug data : '.json_encode($debugData));
    }
}
