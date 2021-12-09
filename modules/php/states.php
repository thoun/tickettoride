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
            $this->pickInitialDestinationCards($playerId);
        }
        
        $this->gamestate->nextState('');
    }

    function stChooseInitialDestinations() { 
        $this->gamestate->setAllPlayersMultiactive();
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
                $completed = $this->isDestinationCompleted($playerId, $destination);
                $points = $completed ? $destination->points : -$destination->points;
                
                $message = clienttranslate('${player_name} ${gainsloses} ${delta} points with ${from} to ${to} destination');
                $this->incScore($playerId, $points, $message, [
                    'delta' => $destination->points,
                    'from' => $this->CITIES[$destination->from],
                    'to' => $this->CITIES[$destination->to],
                    'i18n' => ['gainsloses'],
                    'gainsloses' => $completed ? clienttranslate('gains') : clienttranslate('loses')
                ]);

                if (!$completed) {
                    self::incStat(1, 'uncompletedDestinations');
                    self::incStat(1, 'uncompletedDestinations', $playerId);
                }
            }
        }

        // Longest continuous path 
        if (POINTS_FOR_LONGEST_PATH !== null) {
            $playersLongestPaths = [];
            foreach ($players as $playerId => $playerDb) {
                $longestPath = $this->getLongestPath($playerId);
                $playersLongestPaths[$playerId] = $longestPath;

                self::notifyAllPlayers('longestPath', clienttranslate('${player_name} longest continuous path is ${length} train-cars long'), [
                    'playerId' => $playerId,
                    'player_name' => $this->getPlayerName($playerId),
                    'length' => $longestPath,
                ]);

                self::setStat($longestPath, 'longestPath', $playerId);
            }

            $longestPathBySize = [];
            foreach ($playersLongestPaths as $playerId => $longestPath) {
                $longestPathBySize[$longestPath] = array_key_exists($longestPath, $longestPathBySize) ?
                    array_merge($longestPathBySize[$longestPath], [$playerId]):
                    [$playerId];
            }
            $bestLongestPath = array_key_last($longestPathBySize);
            $longestPathWinners = $longestPathBySize[$bestLongestPath];   
            self::setStat($bestLongestPath, 'longestPath');
            foreach ($longestPathWinners as $playerId) {
                $points = POINTS_FOR_LONGEST_PATH;
                $this->incScore($playerId, $points, clienttranslate('${player_name} gains ${points} points with longest continuous path : ${trainCars} train cars'), [
                    'points' => $points,
                    'trainCars' => $bestLongestPath,
                ]);

                if (!$completed) {
                    self::incStat(1, 'uncompletedDestinations');
                    self::incStat(1, 'uncompletedDestinations', $playerId);
                }
            }
        }

        // Globetrotter
        if (POINTS_FOR_GLOBETROTTER !== null) {
        }

        // averageClaimedRouteLength stat = playedTrainCars / claimedRoutes
        self::setStat(self::getStat('playedTrainCars') / (float)self::getStat('claimedRoutes'), 'averageClaimedRouteLength');
        foreach ($players as $playerId => $playerDb) {
            self::setStat(self::getStat('playedTrainCars', $playerId) / (float)self::getStat('claimedRoutes', $playerId), 'averageClaimedRouteLength', $playerId);
        }

        $this->gamestate->nextState('endGame');
    }
}
