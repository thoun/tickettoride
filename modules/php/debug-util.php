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

        //$this->debugClaimAllRoutes(2343492, 1);
        //$this->debugClaimAllRoutes(2343492, 0.3);
        //$this->debugClaimAllRoutes(2343493, 0.2);
        //$this->debugSetLastTurn();
        //$this->debugClaimRoutes(2343492, [94, 37, 32, 59, 22, 20, 77, 11, 79, 68, 55]);

        //$this->debugSetRemainingTrainCarDeck(1);

        //self::DbQuery("update `traincar` set card_location='hand', card_location_arg=2343492 WHERE `card_type` LIKE '0'");

        //$this->gamestate->changeActivePlayer(2343492);
    }

    function debug_allLocos(int $playerId) {
        self::DbQuery("update `traincar` set card_location = 'hand', card_location_arg = $playerId WHERE `card_type` LIKE '0'");
    }

    function debug_SetDestinationInHand(int $cardType, int $playerId) {
        $card = $this->getDestinationFromDb(array_values($this->destinations->getCardsOfType(1, $cardType))[0]);
        $this->destinations->moveCard($card->id, 'hand', $playerId);
        return $card;
    }

    // SELECT card_location, count(*)  FROM `traincar` GROUP BY card_location
    function debug_EmptyHand() {
        $this->trainCars->moveAllCardsInLocation('hand', 'void');
    }
    function debug_EmptyDeck() {
        $this->trainCars->moveAllCardsInLocation('deck', 'void');
    }
    function debug_EmptyDiscard() {
        $this->trainCars->moveAllCardsInLocation('discard', 'void');
    }
    
    function debug_AlmostEmptyDeck() {
        $moveNumber = $this->getRemainingTrainCarCardsInDeck(true) - 1;
        $this->trainCars->pickCardsForLocation($moveNumber, 'deck', 'void');
    }

    function debugNoAvailableTrainCardCards() {
        $this->trainCars->moveAllCardsInLocation('table', 'void');
        $this->trainCars->moveAllCardsInLocation('deck', 'void');
        $this->trainCars->moveAllCardsInLocation('discard', 'void');
    }

    function debug_EmptyDestinationDeck() {
        $this->destinations->moveAllCardsInLocation('deck', 'void');
    }
    
    function debug_AlmostEmptyDestinationDeck() {
        $moveNumber = $this->getRemainingDestinationCardsInDeck() - 1;
        $this->destinations->pickCardsForLocation($moveNumber, 'deck', 'discard');
    }

    function debug_ClaimRoute(int $playerId, int $routeId) {
        self::DbQuery("INSERT INTO `claimed_routes` (`route_id`, `player_id`) VALUES ($routeId, $playerId)");

        $route = $this->map->routes[$routeId];
        $points = $this->map->routePoints[$route->number];

        self::notifyAllPlayers('claimedRoute', clienttranslate('${player_name} gains ${points} point(s) by claiming route from ${from} to ${to} with ${number} train car(s) : ${colors}'), [
            'playerId' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
            'points' => $points,
            'route' => $route,
            'from' => $this->getCityName($route->from),
            'to' => $this->getCityName($route->to),
            'number' => $route->number,
            'removeCards' => [],
            'colors' => [],
            'remainingTrainCars' => $this->getRemainingTrainCarsCount($playerId),
        ]);
    }

    function debug_ClaimAllRoutes($playerId, $ratio = 0.1) {
        foreach($this->map->routes as $id => $route) {
            if ((bga_rand(0, count($this->map->routes)-1) / (float)count($this->map->routes)) < $ratio) {
                $this->debugClaimRoute($playerId, $id);
            }
        }
    }

    function debug_ClaimRoutes($playerId, $routesIds) {
        foreach($routesIds as $routeId) {
            $this->debugClaimRoute($playerId, $routeId);
        }
    }

    function debug_SetRemainingTrainCarDeck(int $remaining) {
        $moveNumber = $this->getRemainingTrainCarCardsInDeck() - $remaining;
        $this->trainCars->pickCardsForLocation($moveNumber, 'deck', 'discard');
    }

    function debug_SetLastTurn() {
        self::DbQuery("UPDATE player SET `player_remaining_train_cars` = ".$this->map->trainCarsNumberToStartLastTurn);
    }
    function debug_NoTrainCar() {
        self::DbQuery("UPDATE player SET `player_remaining_train_cars` = 0");
    }

    // select all 3 destinations for each player
    function debug_Start() {
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
