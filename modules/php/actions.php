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

        $this->destinationDeck->keepInitialCards($playerId, $ids);

        // TODO notif

        self::incStat($number, 'keptInitialDestinationCards', $playerId);
        
        $this->gamestate->setPlayerNonMultiactive($playerId, 'start');
    }
    
    public function chooseAdditionalDestinations(array $ids) {
        self::checkAction('chooseAdditionalDestinations'); 
        
        $playerId = intval(self::getActivePlayerId());

        $this->destinationDeck->keepAdditionalCards($playerId, $ids);

        // TODO notif

        self::incStat($number, 'keptAdditionalDestinationCards', $playerId);
        
        $this->gamestate->nextState('nextPlayer');
    }
  	
    public function drawDeckCards(int $number) {        
        self::checkAction('drawDeckCards'); 
        
        $playerId = intval(self::getActivePlayerId());

        $this->trainCarDeck->drawFromDeck($playerId, $number);

        // TODO notif

        self::incStat($number, 'collectedTrainCarCards');
        self::incStat($number, 'collectedTrainCarCards', $playerId);
        self::incStat($number, 'collectedHiddenTrainCarCards');
        self::incStat($number, 'collectedHiddenTrainCarCards', $playerId);

        $this->gamestate->nextState($number == 2 ? 'nextPlayer' : 'drawSecondCard'); 
    }
  	
    public function drawTableCard(int $id) {
        self::checkAction('drawTableCard'); 
        
        $playerId = intval(self::getActivePlayerId());

        $card = $this->trainCarDeck->drawFromTable($playerId, $id);

        // TODO notif

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

        $this->trainCarDeck->drawFromDeck($playerId, 1, true);

        // TODO notif

        self::incStat(1, 'collectedTrainCarCards');
        self::incStat(1, 'collectedTrainCarCards', $playerId);
        self::incStat(1, 'collectedHiddenTrainCarCards');
        self::incStat(1, 'collectedHiddenTrainCarCards', $playerId);

        $this->gamestate->nextState('nextPlayer'); 
    }
  	
    public function drawSecondTableCard() {
        self::checkAction('drawSecondTableCard'); 
        
        $playerId = intval(self::getActivePlayerId());

        $card = $this->trainCarDeck->drawFromTable($playerId, $id, true);

        // TODO notif

        self::incStat(1, 'collectedTrainCarCards');
        self::incStat(1, 'collectedTrainCarCards', $playerId);
        self::incStat(1, 'collectedVisibleTrainCarCards');
        self::incStat(1, 'collectedVisibleTrainCarCards', $playerId);
        if ($card->type == 0) {
            self::incStat(1, 'collectedVisibleLocomotives');
            self::incStat(1, 'collectedVisibleLocomotives', $playerId);
        }

        $this->gamestate->nextState('drawSecondCard'); 
    }
  	
    public function drawDestinations() {
        self::checkAction('drawDestinations');

        self::incStat(1, 'drawDestinationsAction');
        self::incStat(1, 'drawDestinationsAction', $playerId);

        $this->gamestate->nextState('drawDestinations'); 
    }
  	
    public function claimRoute(int $routeId) {
        self::checkAction('claimRoute');
        
        $playerId = intval(self::getActivePlayerId());

        $route = $this->ROUTES[$routeId];

        if ($this->game->getUniqueIntValueFromDB( "SELECT count(*) FROM `claimed_routes` WHERE `route_id` = $routeId") > 0) {
            throw new BgaUserException("Route is already claimed.");
        }

        if ($this->getRemainingTrainCarsCount($playerId) < $route->number) {
            throw new BgaUserException("Not enough train cars to claim the route.");
        }
        
        $colorAndLocomotiveCards = array_filter($trainCarsHand, function ($card) use ($routeColor) { return in_array($card->type, [0, $routeColor]); });
        
        if (count($colorAndLocomotiveCards) < $route->number) {
            throw new BgaUserException("Not enough cards to claim the route.");
        }

        usort($colorAndLocomotiveCards, function ($card1, $card2) {
            return $card1->type < $card2->type;
        });
        // TODO check color cards are first after usort

        $idsToRemove = array_slice(array_keys($colorAndLocomotiveCards), 0, $route->number);
        $this->trainCars->moveCards($idsToRemove, 'discard');

        // save claimed route
        $sql = "INSERT INTO `claimed_routes` (`route_id`, `player_id`) VALUES ($routeId, $playerId)";

        // update score
        $points = $this->ROUTE_POINTS[$route->number];
        $message = clienttranslate('${player_name} gains ${delta} points by claiming route from ${from} to ${to}');
        $this->incScore($playerId, $points, $message, $route);

        self::DbQuery("UPDATE player SET `player_remaining_train_cars` = `player_remaining_train_cars` - $route->number WHERE player_id = $player_id");
        // notif claimed route, notif removed cards, notif remaining_train_cars

        self::incStat(1, 'claimedRoutes');
        self::incStat(1, 'claimedRoutes', $playerId);
        self::incStat($route->number, 'playedTrainCars');
        self::incStat($route->number, 'playedTrainCars', $playerId);

        $this->checkCompletedDestinations($playerId);

        $this->gamestate->nextState('nextPlayer'); 
    }
}
