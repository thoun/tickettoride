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
        
        $playerId = intval(self::getActivePlayerId());

        $this->destinationDeck->keepInitialCards($playerId, $ids);

        // TODO notif
        
        $this->gamestate->nextState('nextPlayer');
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

        // TODO check claimed route
        // TODO save claimed route
        // TODO update score
        // TODO notif

        $this->gamestate->nextState('nextPlayer'); 
    }
}
