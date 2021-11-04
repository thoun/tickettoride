<?php

trait StateTrait {

//////////////////////////////////////////////////////////////////////////////
//////////// Game state actions
////////////

    /*
        Here, you can create methods defined as "game state actions" (see "action" property in states.inc.php).
        The action method of state X is called everytime the current game state is set to X.
    */

    function stChooseInitialDestinations() {  
        $playerId = self::getActivePlayerId();

        if ($this->everyPlayerHasDestinations()) {
            $this->gamestate->nextState('start');
        } else {            
            $this->destinationDeck->pickInitialCards();
        }
    }

    function stChooseInitialDestinationsNextPlayer() {
        $playerId = self::activeNextPlayer();
        self::giveExtraTime($playerId);

        if ($this->everyPlayerHasDestinations()) {
            $this->gamestate->nextState('start');
        } else {
            $this->gamestate->nextState('nextPlayer');
        }
    }

    function stChooseAdditionalDestinations() {  
        $playerId = self::getActivePlayerId();

        $this->destinationDeck->pickAdditionialCards();
    }
}
