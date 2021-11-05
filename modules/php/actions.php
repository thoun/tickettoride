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
        
        $this->gamestate->setPlayerNonMultiactive($playerId, 'start');
    }
    
    public function chooseAdditionalDestinations(array $ids) {
        self::checkAction('chooseAdditionalDestinations'); 
        
        $playerId = intval(self::getActivePlayerId());

        $this->destinationDeck->keepAdditionalCards($playerId, $ids);

        // TODO notif
        
        $this->gamestate->nextState('nextPlayer');
    }
  	
    public function drawDeckCards(int $number) {        
        self::checkAction('drawDeckCards'); 
        
        $playerId = intval(self::getActivePlayerId());

        $this->trainCarsDeck->drawFromDeck($playerId, $number);

        // TODO notif

        $this->gamestate->nextState($number == 2 ? 'nextPlayer' : 'drawSecondCard'); 
    }
  	
    public function drawTableCard(int $id) {
        self::checkAction('drawTableCard'); 
        
        $playerId = intval(self::getActivePlayerId());

        $this->trainCarsDeck->drawFromTable($playerId, $id);

        // TODO notif

        $this->gamestate->nextState($card->type == 0 ? 'nextPlayer' : 'drawSecondCard'); 
    }
  	
    public function drawSecondDeckCard() {
        self::checkAction('drawSecondDeckCard'); 
        
        $playerId = intval(self::getActivePlayerId());

        $this->trainCarsDeck->drawFromDeck($playerId, 1, true);

        // TODO notif

        $this->gamestate->nextState('nextPlayer'); 
    }
  	
    public function drawSecondTableCard() {
        self::checkAction('drawSecondTableCard'); 
        
        $playerId = intval(self::getActivePlayerId());

        $this->trainCarsDeck->drawFromTable($playerId, $id, true);

        // TODO notif

        $this->gamestate->nextState('drawSecondCard'); 
    }
  	
    public function drawDestinations() {
        self::checkAction('drawDestinations');

        $this->gamestate->nextState('drawDestinations'); 
    }
  	
    public function claimRoute(int $routeId) {
        self::checkAction('claimRoute');
        
        $playerId = intval(self::getActivePlayerId());

        $route = $this->ROUTES[$routeId];

        if ($this->game->getUniqueIntValueFromDB( "SELECT count(*) FROM `claimed_routes` WHERE `route_id` = $routeId") > 0) {
            throw new BgaSystemException("Route is already claimed.");
        }

        if ($this->getRemainingTrainCarsCount($playerId) < $route->number) {
            throw new BgaSystemException("Not enough train cars to claim the route.");
        }
        
        $colorAndLocomotiveCards = array_filter($trainCarsHand, function ($card) use ($routeColor) { return in_array($card->type, [0, $routeColor]); });
        
        if (count($colorAndLocomotiveCards) < $route->number) {
            throw new BgaSystemException("Not enough cards to claim the route.");
        }

        usort($colorAndLocomotiveCards, function ($card1, $card2) {
            return $card1->type < $card2->type;
        });
        // TODO check color cards are first after usort

        $idsToRemove = array_slice(array_keys($colorAndLocomotiveCards), 0, $route->number);
        $this->trainCars->moveCards($idsToRemove, 'discard');

        // save claimed route
        $sql = "INSERT INTO `claimed_routes` (`route_id`, `player_id`) VALUES ($routeId, $playerId)";
        
        // TODO notif claimed route, notif removed cards

        // update score
        $message = clienttranslate('${player_name} gains ${delta} points by claiming ${number} train-cars route from ${from} to ${to}');
        $this->incScore($playerId, $route->points, $message, $route);

        $this->gamestate->nextState('nextPlayer'); 
    }
}
