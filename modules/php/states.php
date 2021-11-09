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

        self::incStat(1, 'turnsNumber');
        self::incStat(1, 'turnsNumber', $playerId);

        $lastTurn = intval(self::getGameStateValue(LAST_TURN));

        // check if it was last action from player who started last turn
        if ($lastTurn == $playerId) {
            $this->gamestate->nextState('endScore');
        } else {
            if ($lastTurn == 0) {
                // check if last turn is started    
                if ($this->getLowestTrainCarsCount() <= TRAIN_CARS_NUMBER_TO_START_LAST_TURN) {
                    self::setGameStateValue(LAST_TURN, $playerId);

                    self::notifyAllPlayers('lastTurn', clienttranslate('${player_name} has ${number} train cars or less, starting final turn !'), [
                        'playerId' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'number' => TRAIN_CARS_NUMBER_TO_START_LAST_TURN,
                    ]);
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

            $destinations = $this->getDestinationsFromDb($this->destinations->getCardsInLocation('hand', $playerId));

            foreach ($destinations as $destination) {
                $completed = $this->map->isDestinationCompleted($playerId, $destination);
                $points = $completed ? $destination->points : -$destination->points;
                
                $message = clienttranslate('${player_name} ${gainsloses} ${delta} points with ${from} to ${to} destination');
                $deltaPoints = $completed ? $destination->points : -$destination->points;
                $this->incScore($playerId, $deltaPoints, $message, [
                    'delta' => $destination->points,
                    'from' => $this->CITIES[$destination->from],
                    'to' => $this->CITIES[$destination->to],
                    'i18n' => ['gainsloses'],
                    'gainsloses' => $completed ? clienttranslate('gains') : clienttranslate('loses')
                ]);

                // TODO stats

                //self::incStat(1, 'uncompletedDestinations');
                //self::incStat(1, 'uncompletedDestinations', $playerId);
            }
        }

        // Longest continuous path 
        $playersLongestPaths = [];
        foreach ($players as $playerId => $playerDb) {
            $longestPath = $this->map->getLongestPath($playerId);
            $playersLongestPaths[$playerId] = $longestPath;

            /*$points = $this->getScoreLocations($player_id, intval($playerDb['pearls']));

            $playersPoints[$player_id] += $points;
            self::DbQuery("UPDATE player SET player_score = player_score + $points, player_score_locations = $points WHERE player_id = $player_id");
            // TODO self::DbQuery("UPDATE player SET player_score_locations = $points WHERE player_id = $player_id");

            self::notifyAllPlayers('scoreLocations', clienttranslate('${player_name} wins ${points} points with locations'), [
                'playerId' => $player_id,
                'player_name' => $playerDb['player_name'],
                'points' => $points,
            ]);*/
            
            self::setStat($longestPath, 'longestPath', $playerId);
        }

        // averageClaimedRouteLength stat = playedTrainCars / claimedRoutes
        self::setStat(self::getStat('playedTrainCars') / (float)self::getStat('claimedRoutes'), 'averageClaimedRouteLength');
        foreach ($players as $playerId => $playerDb) {
            self::setStat(self::getStat('playedTrainCars', $playerId) / (float)self::getStat('claimedRoutes', $playerId), 'averageClaimedRouteLength', $playerId);
        }

        $this->gamestate->nextState('endGame');
    }
}
