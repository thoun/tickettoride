<?php

namespace Bga\Games\TicketToRide\States;

use Bga\GameFramework\States\GameState;
use Bga\GameFramework\StateType;
use Bga\Games\TicketToRide\Game;

use function Bga\Games\TicketToRide\debug;

class EndScore extends GameState {
    public function __construct(protected Game $game)
    {
        parent::__construct($game,
            id: ST_END_SCORE,
            type: StateType::GAME,
            name: 'endScore',
        );
    }

    function onEnteringState() {
        $expansionOption = $this->game->getExpansionOption();
        $isGlobetrotterBonusActive = $this->game->getMap()->isGlobetrotterBonusActive($expansionOption);
        $isLongestPathBonusActive = $this->game->getMap()->isLongestPathBonusActive($expansionOption);
        $mandalaPoints = $this->game->getMap()->mandalaPoints;

        $sql = "SELECT player_id id, player_score score FROM player ORDER BY player_no ASC";
        $players = $this->game->getCollectionFromDb($sql);

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
            $claimedRoutes = $this->game->getClaimedRoutes($playerId);

            $destinations = $this->game->getDestinationsFromDb($this->game->destinations->getCardsInLocation('hand', $playerId));

            foreach ($destinations as &$destination) {
                $completed = boolval($this->game->getUniqueValueFromDb("SELECT `completed` FROM `destination` WHERE `card_id` = $destination->id"));
                
                $points = 0;
                if (is_array($destination->to)) {
                    if ($completed) {
                        foreach ($destination->to as $index => $to) {
                            if ($destination->points[$index] > $points) {
                                $route = $this->game->getShortestRoutesToLinkCitiesOrCountries($claimedRoutes, $destination->from, $to);
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
            $longestPath = $this->game->getLongestPath($playerId);
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
                $totalScore[$playerId] += $this->game->getMap()->pointsForLongestPath;
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
                $totalScore[$playerId] += $this->game->getMap()->pointsForGlobetrotter;
            }
        }

        $mandalaResults = [];
        $mandalaRoutes = [];
        // Mandala
        if ($mandalaPoints !== null) {
            foreach ($destinationsResults as $playerId => $destinations) {
                $playerMandalas = [];
                $completedDestinations = array_values(array_filter($destinations, fn($destination) => (array_key_exists($destination->id, $routeByCompletedDestination) ? $routeByCompletedDestination[$destination->id] : $this->game->getDestinationRoutes($playerId, $destination)) != null));
                foreach ($completedDestinations as $destination) {
                    $distinctRoutes = $this->game->getDistinctRoutes($playerId, $destination);
                    $distinctRoutesCount = count($distinctRoutes);
                    if ($distinctRoutesCount >= 2) {
                        $playerMandalas[] = $destination;
                        $routes = [];
                        foreach($distinctRoutes as $distinctRoute) {
                            $routes = array_merge($routes, $distinctRoute->routes);
                        }
                        $mandalaRoutes[$destination->id] = $routes;
                    }
                }
                $mandalaResults[$playerId] = $playerMandalas;
                $completedMandalas = min(
                    max(array_keys($mandalaPoints)),
                    count($playerMandalas),
                );
                $totalScore[$playerId] += $mandalaPoints[$completedMandalas];
            }
        }

        // we need to send bestScore before all score notifs, because train animations will show score ratio over best score
        $bestScore = max($totalScore);
        $this->notify->all('bestScore', '', [
            'bestScore' => $bestScore,
        ]);

        // now we can send score notifications

        // completed/failed destinations 
        foreach ($destinationsResults as $playerId => $destinations) {

            foreach ($destinations as $destination) {
                $destinationRoutes = array_key_exists($destination->id, $routeByCompletedDestination) ? $routeByCompletedDestination[$destination->id] : $this->game->getDestinationRoutes($playerId, $destination);
                $completed = $destinationRoutes != null;
                $points = $pointsByDestination[$destination->id];

                $this->notify->all('scoreDestination', clienttranslate('${player_name} reveals ${from} to ${to} destination'), [
                    'playerId' => $playerId,
                    'player_name' => $this->game->getPlayerNameById($playerId),
                    'destination' => $destination,
                    'from' => $this->game->getCityName($destination->from),
                    'to' => $this->game->getLogTo($destination),
                    'destinationRoutes' => $destinationRoutes,
                ]);
                
                $message = clienttranslate('${player_name} ${gainsloses} ${absdelta} points with ${from} to ${to} destination');
                $this->game->incScore($playerId, $points, $message, [
                    'delta' => $points,
                    'absdelta' => abs($points),
                    'from' => $this->game->getCityName($destination->from),
                    'to' => $this->game->getLogTo($destination),
                    'i18n' => ['gainsloses'],
                    'gainsloses' => $completed ? clienttranslate('gains') : clienttranslate('loses'),
                ]);

                $this->game->incStat($points, 'pointsWithDestinations');
                $this->game->incStat($points, 'pointsWithDestinations', $playerId);
                if ($completed) {      
                    // stats for completed destinations are set as soon as they are completed              
                    $this->game->incStat($points, 'pointsWithCompletedDestinations');
                    $this->game->incStat($points, 'pointsWithCompletedDestinations', $playerId);
                } else {
                    // stats for uncompleted destinations can only be set at the end
                    $this->game->incStat(1, 'uncompletedDestinations');
                    $this->game->incStat(1, 'uncompletedDestinations', $playerId);
                    $this->game->incStat($points, 'pointsLostWithUncompletedDestinations');
                    $this->game->incStat($points, 'pointsLostWithUncompletedDestinations', $playerId);
                }
            }
        }

        // Globetrotter
        if ($isGlobetrotterBonusActive) {
            foreach ($globetrotterWinners as $playerId) {
                $points = $this->game->getMap()->pointsForGlobetrotter;
                $this->game->incScore($playerId, $points, clienttranslate('${player_name} gains ${delta} points with Globetrotter : ${destinations} completed destinations'), [
                    'points' => $points,
                    'destinations' => $bestCompletedDestinationsCount,
                ]);

                $this->notify->all('globetrotterWinner', '', [
                    'playerId' => $playerId,
                    'length' => $bestCompletedDestinationsCount,
                ]);

                $this->game->setStat(1, 'globetrotterBonus', $playerId);
            }
        }

        // Longest continuous path 
        if ($isLongestPathBonusActive) {
            foreach ($players as $playerId => $playerDb) {
                $longestPath = $playersLongestPaths[$playerId];

                $this->notify->all('longestPath', clienttranslate('${player_name} longest continuous path is ${length} train-cars long'), [
                    'playerId' => $playerId,
                    'player_name' => $this->game->getPlayerNameById($playerId),
                    'length' => $longestPath->length,
                    'routes' => $longestPath->routes,
                ]);

                $this->game->setStat($longestPath->length, 'longestPath', $playerId);
            }
             
            $this->game->setStat($bestLongestPath, 'longestPath');
            foreach ($longestPathWinners as $playerId) {
                $points = $this->game->getMap()->pointsForLongestPath;
                $this->game->incScore($playerId, $points, clienttranslate('${player_name} gains ${delta} points with longest continuous path : ${trainCars} train cars'), [
                    'points' => $points,
                    'trainCars' => $bestLongestPath,
                ]);

                $this->notify->all('longestPathWinner', '', [
                    'playerId' => $playerId,
                    'length' => $bestLongestPath,
                ]);

                $this->game->setStat(1, 'longestPathBonus', $playerId);
            }
        }

        $claimedRoutes = intval($this->game->getStat('claimedRoutes'));
        if ($claimedRoutes > 0) {
            $this->game->setStat($this->game->getStat('playedTrainCars') / (float)$claimedRoutes, 'averageClaimedRouteLength');
        } else {
            $this->game->setStat(0, 'averageClaimedRouteLength'); 
        }
        foreach ($players as $playerId => $playerDb) {
            $claimedRoutes = intval($this->game->getStat('claimedRoutes', $playerId));
            if ($claimedRoutes > 0) {
                $this->game->setStat($this->game->getStat('playedTrainCars', $playerId) / (float)$claimedRoutes, 'averageClaimedRouteLength', $playerId);
            } else {
                $this->game->setStat(0, 'averageClaimedRouteLength', $playerId);
            }

            $scoreAux = 1000 * $completedDestinationsCount[$playerId] + $playersLongestPaths[$playerId]->length;
            $this->game->DbQuery("UPDATE player SET `player_score_aux` = $scoreAux where `player_id` = $playerId");
        }
        // Mandala
        if ($mandalaPoints !== null) {
            foreach ($mandalaResults as $playerId => $playerMandalas) {

                foreach ($playerMandalas as $destination) {
                    $this->notify->all('scoreDestinationGrandTour', clienttranslate('${player_name} gets a Grand Tour bonus (Mandala) from ${from} to ${to}'), [
                        'playerId' => $playerId,
                        'player_name' => $this->game->getPlayerNameById($playerId),
                        'destination' => $destination,
                        'from' => $this->game->getCityName($destination->from),
                        'to' => $this->game->getLogTo($destination),
                        'routes' => $mandalaRoutes[$destination->id],
                    ]);
                }

                $completedMandalas = min(
                    max(array_keys($mandalaPoints)),
                    count($playerMandalas),
                );
                $points =  $mandalaPoints[$completedMandalas];                
                
                $message = clienttranslate('${player_name} gains ${delta} points for ${number} completed Grand Tour(s) (Mandala)');
                $this->game->incScore($playerId, $points, $message, [
                    'delta' => $points,
                    'number' => count($playerMandalas),
                ]);
            }
        }

        // highlight winner(s)
        foreach ($totalScore as $playerId => $playerScore) {
            if ($playerScore == $bestScore) {
                $this->notify->all('highlightWinnerScore', '', [
                    'playerId' => $playerId,
                ]);
            }
        }

        return ST_END_GAME;
    }
}