<?php

trait ActionTrait {

    //////////////////////////////////////////////////////////////////////////////
    //////////// Player actions
    //////////// 
    
    /*
        Each time a player is doing some game action, one of the methods below is called.
        (note: each method below must match an input method in nicodemus.action.php)
    */
    
    public function chooseInitialDestinations(array $ids) {
        self::checkAction('chooseInitialDestinations'); 
        
        $playerId = intval(self::getCurrentPlayerId());

        $this->keepInitialDestinationCards($playerId, $ids);

        self::incStat(count($ids), 'keptInitialDestinationCards', $playerId);
        
        $this->gamestate->setPlayerNonMultiactive($playerId, 'start');
    }
    
    public function chooseAdditionalDestinations(array $ids) {
        self::checkAction('chooseAdditionalDestinations'); 
        
        $playerId = intval(self::getActivePlayerId());

        $this->keepAdditionalDestinationCards($playerId, $ids);

        self::incStat(count($ids), 'keptAdditionalDestinationCards', $playerId);
        
        $this->gamestate->nextState('nextPlayer');
    }
  	
    public function drawDeckCards(int $number) {        
        self::checkAction('drawDeckCards'); 
        
        $playerId = intval(self::getActivePlayerId());

        $this->drawTrainCarCardsFromDeck($playerId, $number);

        self::incStat($number, 'collectedTrainCarCards');
        self::incStat($number, 'collectedTrainCarCards', $playerId);
        self::incStat($number, 'collectedHiddenTrainCarCards');
        self::incStat($number, 'collectedHiddenTrainCarCards', $playerId);

        $this->gamestate->nextState($number == 2 ? 'nextPlayer' : 'drawSecondCard'); 
    }
  	
    public function drawTableCard(int $id) {
        self::checkAction('drawTableCard'); 
        
        $playerId = intval(self::getActivePlayerId());

        $card = $this->drawTrainCarCardsFromTable($playerId, $id);

        self::incStat(1, 'collectedTrainCarCards');
        self::incStat(1, 'collectedTrainCarCards', $playerId);
        self::incStat(1, 'collectedVisibleTrainCarCards');
        self::incStat(1, 'collectedVisibleTrainCarCards', $playerId);
        if ($card->type == 0) {
            self::incStat(1, 'collectedVisibleLocomotives');
            self::incStat(1, 'collectedVisibleLocomotives', $playerId);
        }

        $this->gamestate->nextState($card->type == 0 ? 'nextPlayer' : 'drawSecondCard'); 
    }
  	
    public function drawSecondDeckCard() {
        self::checkAction('drawSecondDeckCard'); 
        
        $playerId = intval(self::getActivePlayerId());

        $this->drawTrainCarCardsFromDeck($playerId, 1, true);

        self::incStat(1, 'collectedTrainCarCards');
        self::incStat(1, 'collectedTrainCarCards', $playerId);
        self::incStat(1, 'collectedHiddenTrainCarCards');
        self::incStat(1, 'collectedHiddenTrainCarCards', $playerId);

        $this->gamestate->nextState('nextPlayer'); 
    }
  	
    public function drawSecondTableCard(int $id) {
        self::checkAction('drawSecondTableCard'); 
        
        $playerId = intval(self::getActivePlayerId());

        $card = $this->drawTrainCarCardsFromTable($playerId, $id, true);

        self::incStat(1, 'collectedTrainCarCards');
        self::incStat(1, 'collectedTrainCarCards', $playerId);
        self::incStat(1, 'collectedVisibleTrainCarCards');
        self::incStat(1, 'collectedVisibleTrainCarCards', $playerId);
        if ($card->type == 0) {
            self::incStat(1, 'collectedVisibleLocomotives');
            self::incStat(1, 'collectedVisibleLocomotives', $playerId);
        }

        $this->gamestate->nextState('nextPlayer'); 
    }
  	
    public function drawDestinations() {
        self::checkAction('drawDestinations');
        
        $playerId = intval(self::getActivePlayerId());

        $this->pickAdditionalDestinationCards($playerId);

        self::incStat(1, 'drawDestinationsAction');
        self::incStat(1, 'drawDestinationsAction', $playerId);

        $this->gamestate->nextState('drawDestinations'); 
    }
  	
    public function claimRoute(int $routeId, int $color) {
        self::checkAction('claimRoute');
        
        $playerId = intval(self::getActivePlayerId());

        $route = $this->ROUTES[$routeId];

        if ($this->getUniqueIntValueFromDB( "SELECT count(*) FROM `claimed_routes` WHERE `route_id` = $routeId") > 0) {
            throw new BgaUserException("Route is already claimed.");
        }

        $remainingTrainCars = $this->getRemainingTrainCarsCount($playerId);
        if ($remainingTrainCars < $route->number) {
            throw new BgaUserException("Not enough train cars to claim the route.");
        }
        
        $trainCarsHand = $this->getTrainCarsFromDb($this->trainCars->getCardsInLocation('hand', $playerId));
        $colorAndLocomotiveCards = $this->canPayForRoute($route, $trainCarsHand, $remainingTrainCars, $color);
        
        if ($colorAndLocomotiveCards == null || count($colorAndLocomotiveCards) < $route->number) {
            throw new BgaUserException("Not enough cards to claim the route.");
        }

        usort($colorAndLocomotiveCards, function ($card1, $card2) {
            return $card1->type < $card2->type;
        });

        $cardsToRemove = array_slice($colorAndLocomotiveCards, 0, $route->number);
        $this->trainCars->moveCards(array_map(function ($card) { return $card->id; }, $cardsToRemove), 'discard');

        // save claimed route
        self::DbQuery("INSERT INTO `claimed_routes` (`route_id`, `player_id`) VALUES ($routeId, $playerId)");

        // update score
        $points = $this->ROUTE_POINTS[$route->number];
        $this->incScore($playerId, $points);

        self::DbQuery("UPDATE player SET `player_remaining_train_cars` = `player_remaining_train_cars` - $route->number WHERE player_id = $playerId");

        self::notifyAllPlayers('claimedRoute', clienttranslate('${player_name} gains ${points} point(s) by claiming route from ${from} to ${to} with ${number} train car(s)'), [
            'playerId' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
            'points' => $points,
            'route' => $route,
            'from' => $this->CITIES[$route->from],
            'to' => $this->CITIES[$route->to],
            'number' => $route->number,
            'removeCards' => $cardsToRemove,
        ]);

        self::incStat(1, 'claimedRoutes');
        self::incStat(1, 'claimedRoutes', $playerId);
        self::incStat($route->number, 'playedTrainCars');
        self::incStat($route->number, 'playedTrainCars', $playerId);

        $this->checkCompletedDestinations($playerId);

        $this->gamestate->nextState('nextPlayer'); 
    }
}
