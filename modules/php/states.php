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
        $sql = "SELECT player_id id, player_score score FROM player ORDER BY player_no ASC";
        $players = self::getCollectionFromDb($sql);

        // points gained during the game : claimed routes
        $totalScore = [];
        foreach ($players as $playerId => $playerDb) {
            $totalScore[$playerId] = intval($playerDb['score']);
        }

        // completed/failed destinations 
        $destinationsResults = [];
        foreach ($players as $playerId => $playerDb) {
            $destinationsResults[$playerId] = [];

            $destinations = $this->getDestinationsFromDb($this->destinations->getCardsInLocation('hand', $playerId));

            foreach ($destinations as $destination) {
                $destination->completed = boolval(self::getUniqueValueFromDb("SELECT `completed` FROM `destination` WHERE `card_id` = $destination->id"));
                $totalScore[$playerId] += $destination->completed ? $destination->points : -$destination->points;
            }
        }

        // Longest continuous path 
        $playersLongestPaths = [];
        $longestPathWinners = [];
        $bestLongestPath = null;
        if (POINTS_FOR_LONGEST_PATH !== null) {
            foreach ($players as $playerId => $playerDb) {
                $longestPath = $this->getLongestPath($playerId);
                $playersLongestPaths[$playerId] = $longestPath;
            }

            $longestPathBySize = [];
            foreach ($playersLongestPaths as $playerId => $longestPath) {
                $longestPathBySize[$longestPath] = array_key_exists($longestPath, $longestPathBySize) ?
                    array_merge($longestPathBySize[$longestPath], [$playerId]):
                    [$playerId];
            }
            $bestLongestPath = array_key_last($longestPathBySize);
            $longestPathWinners = $longestPathBySize[$bestLongestPath];  
            
            foreach ($longestPathWinners as $playerId) {
                $totalScore[$playerId] += POINTS_FOR_LONGEST_PATH;
            }
        }

        // Globetrotter
        if (POINTS_FOR_GLOBETROTTER !== null) {
            // we send notifications here for Globetrotter when the map supports it
        }

        // we need to send bestScore before all score notifs, because train animations will show score ratio over best score
        $bestScore = max($totalScore);
        self::notifyAllPlayers('bestScore', '', [
            'bestScore' => $bestScore,
        ]);

        // now we can send score notifications

        // completed/failed destinations 
        foreach ($destinationsResults as $playerId => $destinations) {

            foreach ($destinations as $destination) {
                $points = $destination->completed ? $destination->points : -$destination->points;
                
                $message = clienttranslate('${player_name} ${gainsloses} ${delta} points with ${from} to ${to} destination');
                $this->incScore($playerId, $points, $message, [
                    'delta' => $destination->points,
                    'from' => $this->CITIES[$destination->from],
                    'to' => $this->CITIES[$destination->to],
                    'i18n' => ['gainsloses'],
                    'gainsloses' => $destination->completed ? clienttranslate('gains') : clienttranslate('loses'),
                ]);

                if (!$destination->completed) {
                    self::incStat(1, 'uncompletedDestinations');
                    self::incStat(1, 'uncompletedDestinations', $playerId);
                }
            }
        }

        // Longest continuous path 
        if (POINTS_FOR_LONGEST_PATH !== null) {
            foreach ($players as $playerId => $playerDb) {
                $longestPath = $playersLongestPaths[$playerId];

                self::notifyAllPlayers('longestPath', clienttranslate('${player_name} longest continuous path is ${length} train-cars long'), [
                    'playerId' => $playerId,
                    'player_name' => $this->getPlayerName($playerId),
                    'length' => $longestPath,
                ]);

                self::setStat($longestPath, 'longestPath', $playerId);
            }
             
            self::setStat($bestLongestPath, 'longestPath');
            foreach ($longestPathWinners as $playerId) {
                $points = POINTS_FOR_LONGEST_PATH;
                $this->incScore($playerId, $points, clienttranslate('${player_name} gains ${delta} points with longest continuous path : ${trainCars} train cars'), [
                    'points' => $points,
                    'trainCars' => $bestLongestPath,
                ]);
            }
        }

        // Globetrotter
        if (POINTS_FOR_GLOBETROTTER !== null) {
            // we send notifications here for Globetrotter when the map supports it
        }

        $claimedRoutes = intval(self::getStat('claimedRoutes'));
        if ($claimedRoutes > 0) {
            self::setStat(self::getStat('playedTrainCars') / (float)$claimedRoutes, 'averageClaimedRouteLength');
        } else {
            self::setStat(0, 'averageClaimedRouteLength'); 
        }
        foreach ($players as $playerId => $playerDb) {
            $claimedRoutes = intval(self::getStat('claimedRoutes', $playerId));
            if ($claimedRoutes > 0) {
                self::setStat(self::getStat('playedTrainCars', $playerId) / (float)$claimedRoutes, 'averageClaimedRouteLength', $playerId);
            } else {
                self::setStat(0, 'averageClaimedRouteLength', $playerId);
            }
        }

        foreach ($players as $playerId => $playerDb) {
            $points = $totalScore[$playerId];
            
            self::notifyAllPlayers('scoreTotal', clienttranslate('${player_name} has ${points} points in total'), [
                'playerId' => $playerId,
                'player_name' => $this->getPlayerName($playerId),
                'points' => $points,
            ]);
        }

        $this->gamestate->nextState('endGame');
    }
}
