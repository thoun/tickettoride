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
        
        // TODO in nextPlayer check if all remaining players have at least one destination
        $this->gamestate->nextState('nextPlayer');
    }
}
