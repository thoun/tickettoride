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

        //$this->gamestate->changeActivePlayer(2343492);
    }

    function debugSetDestinationInHand($cardType, $playerId) {
        $card = $this->getDestinationFromDb(array_values($this->destinations->getCardsOfType(1, $cardType))[0]);
        $this->destinations->moveCard($card->id, 'hand', $playerId);
        return $card;
    }

    // SELECT card_location, count(*)  FROM `traincar` GROUP BY card_location
    function debugEmptyHand() {
        $this->trainCars->moveAllCardsInLocation('hand', 'void');
    }
    function debugEmptyDeck() {
        $this->trainCars->moveAllCardsInLocation('deck', 'void');
    }
    function debugEmptyDiscard() {
        $this->trainCars->moveAllCardsInLocation('discard', 'void');
    }
    
    function debugAlmostEmptyDeck() {
        $moveNumber = $this->getRemainingTrainCarCardsInDeck(true) - 1;
        $this->trainCars->pickCardsForLocation($moveNumber, 'deck', 'void');
    }

    function debugNoAvailableTrainCardCards() {
        $this->trainCars->moveAllCardsInLocation('table', 'void');
        $this->trainCars->moveAllCardsInLocation('deck', 'void');
        $this->trainCars->moveAllCardsInLocation('discard', 'void');
    }

    function debugEmptyDestinationDeck() {
        $this->destinations->moveAllCardsInLocation('deck', 'void');
    }
    
    function debugAlmostEmptyDestinationDeck() {
        $moveNumber = $this->getRemainingDestinationCardsInDeck() - 1;
        $this->destinations->pickCardsForLocation($moveNumber, 'deck', 'discard');
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

    function debugClaimRoutes($playerId, $routesIds) {
        foreach($routesIds as $routeId) {
            $this->debugClaimRoute($playerId, $routeId);
        }
    }

    function debugSetRemainingTrainCarDeck(int $remaining) {
        $moveNumber = $this->getRemainingTrainCarCardsInDeck() - $remaining;
        $this->trainCars->pickCardsForLocation($moveNumber, 'deck', 'discard');
    }

    function debugSetLastTurn() {
        self::DbQuery("UPDATE player SET `player_remaining_train_cars` = ".TRAIN_CARS_NUMBER_TO_START_LAST_TURN);
    }
    function debugNoTrainCar() {
        self::DbQuery("UPDATE player SET `player_remaining_train_cars` = 0");
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

    public function debugReplacePlayersIds() {
        if ($this->getBgaEnvironment() != 'studio') { 
            return;
        } 

		// These are the id's from the BGAtable I need to debug.
        // SELECT JSON_ARRAYAGG(`player_id`) FROM `player`
		$ids = [90574255, 93146640];

		// Id of the first player in BGA Studio
		$sid = 2343492;
		
		foreach ($ids as $id) {
			// basic tables
			$this->DbQuery("UPDATE player SET player_id=$sid WHERE player_id = $id" );
			$this->DbQuery("UPDATE global SET global_value=$sid WHERE global_value = $id" );
			$this->DbQuery("UPDATE stats SET stats_player_id=$sid WHERE stats_player_id = $id" );

			// 'other' game specific tables. example:
			// tables specific to your schema that use player_ids
			$this->DbQuery("UPDATE traincar SET card_location_arg=$sid WHERE card_location_arg = $id" );
			$this->DbQuery("UPDATE destination SET card_location_arg=$sid WHERE card_location_arg = $id" );
			$this->DbQuery("UPDATE claimed_routes SET player_id=$sid WHERE player_id = $id" );
            
			++$sid;
		}
	}

    function debug($debugData) {
        if ($this->getBgaEnvironment() != 'studio') { 
            return;
        }die('debug data : '.json_encode($debugData));
    }
}
