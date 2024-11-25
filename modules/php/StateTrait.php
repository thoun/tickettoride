<?php

namespace Bga\Games\TicketToRide;

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

    function stChooseInitialDestinationsOld() { 
        $this->gamestate->setAllPlayersMultiactive();
    }

    function stChooseInitialDestinations() { 
        $this->gamestate->setAllPlayersMultiactive();
        $this->gamestate->initializePrivateStateForAllActivePlayers(); 
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
                if ($this->getLowestTrainCarsCount() <= $this->getMap()->trainCarsNumberToStartLastTurn) {
                    self::setGameStateValue(LAST_TURN, $playerId);

                    self::notifyAllPlayers('lastTurn', clienttranslate('${player_name} has ${number} train cars or less, starting final turn !'), [
                        'playerId' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'number' => $this->getMap()->trainCarsNumberToStartLastTurn,
                    ]);
                }
            }

            $playerId = self::activeNextPlayer();
            self::giveExtraTime($playerId);
            $this->gamestate->nextState('nextPlayer');
        }
    }

    function stEndScore() {
        $expansionOption = $this->getExpansionOption();
        $isGlobetrotterBonusActive = $this->getMap()->isGlobetrotterBonusActive($expansionOption);
        $isLongestPathBonusActive = $this->getMap()->isLongestPathBonusActive($expansionOption);

        $sql = "SELECT player_id id, player_score score FROM player ORDER BY player_no ASC";
        $players = self::getCollectionFromDb($sql);

        // points gained during the game : claimed routes
        $totalScore = [];
        foreach ($players as $playerId => $playerDb) {
            $totalScore[$playerId] = intval($playerDb['score']);
        }

        // completed/failed destinations 
        $destinationsResults = [];
        $completedDestinationsCount = [];

        $routeByCompletedDestination = [];
        $pointsByDestination = [];

        foreach ($players as $playerId => $playerDb) {
            $completedDestinationsCount[$playerId] = 0;
            $uncompletedDestinations = [];
            $completedDestinations = [];
            $claimedRoutes = $this->getClaimedRoutes($playerId);

            $destinations = $this->getDestinationsFromDb($this->destinations->getCardsInLocation('hand', $playerId));

            foreach ($destinations as &$destination) {
                $completed = boolval(self::getUniqueValueFromDb("SELECT `completed` FROM `destination` WHERE `card_id` = $destination->id"));
                
                $points = 0;
                if (is_array($destination->to)) {
                    if ($completed) {
                        foreach ($destination->to as $index => $to) {
                            if ($destination->points[$index] > $points) {
                                $route = $this->getShortestRoutesToLinkCitiesOrCountries($claimedRoutes, $destination->from, $to);
                                if ($route !== null) {
                                    $points = $destination->points[$index];
                                    $routeByCompletedDestination[$destination->id] = $route;
                                }
                            }
                        }
                    } else {
                        $points = -min($destination->points);
                    }
                    $totalScore[$playerId] += $points;
                } else {
                    $points = $completed ? $destination->points : -$destination->points;
                }

                $totalScore[$playerId] += $points;
                $pointsByDestination[$destination->id] = $points;

                if ($completed) {
                    $completedDestinationsCount[$playerId]++;
                    $completedDestinations[] = $destination;
                } else {                    
                    $uncompletedDestinations[] = $destination;
                }
            }

            // first we will reveal uncomplete destinations, then complete destinations
            $destinationsResults[$playerId] = array_merge($uncompletedDestinations, $completedDestinations);
        }

        // Longest continuous path 
        $playersLongestPaths = [];
        $longestPathWinners = [];
        $bestLongestPath = null;

        foreach ($players as $playerId => $playerDb) {
            $longestPath = $this->getLongestPath($playerId);
            $playersLongestPaths[$playerId] = $longestPath;
        }

        if ($isLongestPathBonusActive) {
            $longestPathBySize = [];
            foreach ($playersLongestPaths as $playerId => $longestPath) {
                $longestPathBySize[$longestPath->length] = array_key_exists($longestPath->length, $longestPathBySize) ?
                    array_merge($longestPathBySize[$longestPath->length], [$playerId]):
                    [$playerId];
            }
            $bestLongestPath = max(array_keys($longestPathBySize));
            $longestPathWinners = $longestPathBySize[$bestLongestPath];  
            
            foreach ($longestPathWinners as $playerId) {
                $totalScore[$playerId] += $this->getMap()->pointsForLongestPath;
            }
        }

        // Globetrotter
        $globetrotterWinners = [];
        $bestCompletedDestinationsCount = null;
        if ($isGlobetrotterBonusActive) {
            $completedDestinationsBySize = [];
            foreach ($completedDestinationsCount as $playerId => $size) {
                $completedDestinationsBySize[$size] = array_key_exists($size, $completedDestinationsBySize) ?
                    array_merge($completedDestinationsBySize[$size], [$playerId]):
                    [$playerId];
            }
            $bestCompletedDestinationsCount = max(array_keys($completedDestinationsBySize));
            $globetrotterWinners = $completedDestinationsBySize[$bestCompletedDestinationsCount];  
            
            foreach ($globetrotterWinners as $playerId) {
                $totalScore[$playerId] += $this->getMap()->pointsForGlobetrotter;
            }
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
                $destinationRoutes = array_key_exists($destination->id, $routeByCompletedDestination) ? $routeByCompletedDestination[$destination->id] : $this->getDestinationRoutes($playerId, $destination);
                $completed = $destinationRoutes != null;
                $points = $pointsByDestination[$destination->id];

                self::notifyAllPlayers('scoreDestination', clienttranslate('${player_name} reveals ${from} to ${to} destination'), [
                    'playerId' => $playerId,
                    'player_name' => $this->getPlayerName($playerId),
                    'destination' => $destination,
                    'from' => $this->getCityName($destination->from),
                    'to' => $this->getLogTo($destination),
                    'destinationRoutes' => $destinationRoutes,
                ]);
                
                $message = clienttranslate('${player_name} ${gainsloses} ${absdelta} points with ${from} to ${to} destination');
                $this->incScore($playerId, $points, $message, [
                    'delta' => $points,
                    'absdelta' => abs($points),
                    'from' => $this->getCityName($destination->from),
                    'to' => $this->getLogTo($destination),
                    'i18n' => ['gainsloses'],
                    'gainsloses' => $completed ? clienttranslate('gains') : clienttranslate('loses'),
                ]);

                self::incStat($points, 'pointsWithDestinations');
                self::incStat($points, 'pointsWithDestinations', $playerId);
                if ($completed) {      
                    // stats for completed destinations are set as soon as they are completed              
                    self::incStat($points, 'pointsWithCompletedDestinations');
                    self::incStat($points, 'pointsWithCompletedDestinations', $playerId);
                } else {
                    // stats for uncompleted destinations can only be set at the end
                    self::incStat(1, 'uncompletedDestinations');
                    self::incStat(1, 'uncompletedDestinations', $playerId);
                    self::incStat($points, 'pointsLostWithUncompletedDestinations');
                    self::incStat($points, 'pointsLostWithUncompletedDestinations', $playerId);
                }
            }
        }

        // Globetrotter
        if ($isGlobetrotterBonusActive) {
            foreach ($globetrotterWinners as $playerId) {
                $points = $this->getMap()->pointsForGlobetrotter;
                $this->incScore($playerId, $points, clienttranslate('${player_name} gains ${delta} points with Globetrotter : ${destinations} completed destinations'), [
                    'points' => $points,
                    'destinations' => $bestCompletedDestinationsCount,
                ]);

                self::notifyAllPlayers('globetrotterWinner', '', [
                    'playerId' => $playerId,
                    'length' => $bestCompletedDestinationsCount,
                ]);

                $this->setStat(1, 'globetrotterBonus', $playerId);
            }
        }

        // Longest continuous path 
        if ($isLongestPathBonusActive) {
            foreach ($players as $playerId => $playerDb) {
                $longestPath = $playersLongestPaths[$playerId];

                self::notifyAllPlayers('longestPath', clienttranslate('${player_name} longest continuous path is ${length} train-cars long'), [
                    'playerId' => $playerId,
                    'player_name' => $this->getPlayerName($playerId),
                    'length' => $longestPath->length,
                    'routes' => $longestPath->routes,
                ]);

                self::setStat($longestPath->length, 'longestPath', $playerId);
            }
             
            self::setStat($bestLongestPath, 'longestPath');
            foreach ($longestPathWinners as $playerId) {
                $points = $this->getMap()->pointsForLongestPath;
                $this->incScore($playerId, $points, clienttranslate('${player_name} gains ${delta} points with longest continuous path : ${trainCars} train cars'), [
                    'points' => $points,
                    'trainCars' => $bestLongestPath,
                ]);

                self::notifyAllPlayers('longestPathWinner', '', [
                    'playerId' => $playerId,
                    'length' => $bestLongestPath,
                ]);

                $this->setStat(1, 'longestPathBonus', $playerId);
            }
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

            $scoreAux = 1000 * $completedDestinationsCount[$playerId] + $playersLongestPaths[$playerId]->length;
            self::DbQuery("UPDATE player SET `player_score_aux` = $scoreAux where `player_id` = $playerId");
        }

        // highlight winner(s)
        foreach ($totalScore as $playerId => $playerScore) {
            if ($playerScore == $bestScore) {
                self::notifyAllPlayers('highlightWinnerScore', '', [
                    'playerId' => $playerId,
                ]);
            }
        }

        $this->gamestate->nextState('endGame');
    }
}
