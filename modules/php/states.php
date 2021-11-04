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

    function stNextPlayer() {
        $playerId = self::getActivePlayerId();

        $lastTurn = intval(self::getGameStateValue(LAST_TURN));

        if ($lastTurn == $playerId) {
            $this->gamestate->nextState('endScore');
        } else {
            if ($lastTurn == 0) {
                // check if last turn is started
                $minTrainCars = intval(self::getUniqueValueFromDB("SELECT min(`player_remaining_train_cars`) FROM player"));
    
                if ($minTrainCars <= TRAIN_CARS_NUMBER_TO_START_LAST_TURN) {
                    self::setGameStateValue(LAST_TURN, $playerId);
                }
            }

            $playerId = self::activeNextPlayer();
            self::giveExtraTime($playerId);
            $this->gamestate->nextState('nextPlayer');
        }
    }

    function stEndScore() {
        // TODO count completed/failed destinations
        // TODO count longestRoad
        // TODO count max completed ?
        // TODO update score
    }
}
