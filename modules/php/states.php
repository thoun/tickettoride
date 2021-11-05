<?php

trait StateTrait {

//////////////////////////////////////////////////////////////////////////////
//////////// Game state actions
////////////

    /*
        Here, you can create methods defined as "game state actions" (see "action" property in states.inc.php).
        The action method of state X is called everytime the current game state is set to X.
    */

    function stDealInitialDestinations() {
        $playersIds = $this->getPlayersIds();

        foreach($playersIds as $playerId) {
            $this->destinationDeck->pickInitialCards($playerId);
        }
        
        $this->gamestate->nextState('');
    }

    function stChooseInitialDestinations() { 
        $this->gamestate->setAllPlayersMultiactive();
    }

    function stChooseAdditionalDestinations() {  
        $playerId = self::getActivePlayerId();

        $this->destinationDeck->pickAdditionalCards($playerId);
    }

    function stNextPlayer() {
        $playerId = self::getActivePlayerId();

        $lastTurn = intval(self::getGameStateValue(LAST_TURN));

        // check if it was last action from player who started last turn
        if ($lastTurn == $playerId) {
            $this->gamestate->nextState('endScore');
        } else {
            if ($lastTurn == 0) {
                // check if last turn is started    
                if ($this->getLowestTrainCarsCount() <= TRAIN_CARS_NUMBER_TO_START_LAST_TURN) {
                    self::setGameStateValue(LAST_TURN, $playerId);
                }
            }

            $playerId = self::activeNextPlayer();
            self::giveExtraTime($playerId);
            $this->gamestate->nextState('nextPlayer');
        }
    }

    function stEndScore() {
        $sql = "SELECT player_id id, player_name FROM player ORDER BY player_no ASC";
        $players = self::getCollectionFromDb($sql);

        // completed/failed destinations 
        foreach ($players as $playerId => $playerDb) {

            $destinations = $this->getDestinationsFromDb($this->destinations->getCardsInLocation('hand', $currentPlayerId));

            foreach ($destinations as $destination) {
                $completed = $this->map->isDestinationCompleted($playerId, $destination);
                $points = $completed ? $destination->points : -$destination->points;
                
                $message = clienttranslate('${player_name} ${gainsloses} ${delta} points with ${from} to ${to} destination');
                $this->incScore($playerId, $route->points, $message, [
                    'delta' => $completed ? $route->points : -$route->points,
                    'from' => $route->from,
                    'to' => $route->to,
                    'i18n' => ['gainsloses'],
                    'gainsloses' => $completed ? clienttranslate('gains') : clienttranslate('loses')
                ]);

                // TODO stats self::setStat($points, 'lords_points', $player_id);
            }
        }

        // Longest continuous path 
        $playersLongestPaths = [];
        foreach ($players as $playerId => $playerDb) {
            $playersLongestPaths[$playerId] = $this->map->getLongestPath($playerId);

            /*$points = $this->getScoreLocations($player_id, intval($playerDb['pearls']));

            $playersPoints[$player_id] += $points;
            self::DbQuery("UPDATE player SET player_score = player_score + $points, player_score_locations = $points WHERE player_id = $player_id");
            // TODO self::DbQuery("UPDATE player SET player_score_locations = $points WHERE player_id = $player_id");

            self::notifyAllPlayers('scoreLocations', clienttranslate('${player_name} wins ${points} points with locations'), [
                'playerId' => $player_id,
                'player_name' => $playerDb['player_name'],
                'points' => $points,
            ]);
            
            // TODO statsself::setStat($points, 'locations_points', $player_id);*/
        }

        // TODO TOCHECK count max completed destination ?

        $this->gamestate->nextState('endGame');
    }
}
