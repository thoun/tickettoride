<?php

namespace Bga\Games\TicketToRide\States;

use Bga\GameFramework\States\GameState;
use Bga\GameFramework\StateType;
use Bga\GameFrameworkPrototype\Helpers\Arrays;
use Bga\Games\TicketToRide\Game;
use Bga\Games\TicketToRide\Objects\Destination;

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

    /**
     * @param Destination[] $uncompletedDestinations 
     * @return Destination[] the new list of uncompleted destinations
     */
    private function legendaryCharacterDiscardDestinations(int $playerId, array $uncompletedDestinations): array {
        if (!$this->game->legendaryCharacterManager->isActive() || $this->game->legendaryCharacterManager->getPlayerCharacter($playerId) !== 2 || empty($uncompletedDestinations)) {
            return $uncompletedDestinations;
        }

        $uncompletedDestinationWithPenalty = Arrays::map($uncompletedDestinations, fn($destination) => [
            'destination' => $destination,
            'penalty' => is_array($destination->to) ? min($destination->points) : $destination->points,
        ]);

        usort($uncompletedDestinationWithPenalty, function(array $left, array $right) {
            $penaltyComparison = $right['penalty'] <=> $left['penalty'];
            if ($penaltyComparison !== 0) {
                return $penaltyComparison;
            }

            return $left['destination']->id <=> $right['destination']->id;
        });

        $discardedDestinations = Arrays::map(array_slice($uncompletedDestinationWithPenalty, 0, 2), fn($entry) => $entry['destination']);
        
        foreach ($discardedDestinations as $destination) {
            $this->game->destinationManager->discardDestination($destination);
            $this->notify->all('discardDestination', /*TODOLC clienttranslate*/('${player_name} discards ${from} to ${to} incomplete destination with ${character_name}'), [
                'playerId' => $playerId,
                'destination' => $destination,
                'from' => $this->game->getCityName($destination->from),
                'to' => $this->game->getLogTo($destination),
                'character_name' => $this->game->legendaryCharacterManager->getCharacterName(2),
            ]);
        }

        return Arrays::filter($uncompletedDestinations, fn($destination) => !Arrays::some($discardedDestinations, fn($dd) => $destination->id === $dd->id));
    }

    function onEnteringState() {
        $expansionOption = $this->game->getExpansionOption();
        $isGlobetrotterBonusActive = $this->game->getMap()->isGlobetrotterBonusActive($expansionOption);
        $isLongestPathBonusActive = $this->game->getMap()->isLongestPathBonusActive($expansionOption);
        $isMostConnectedCitiesBonusActive = $this->game->getMap()->pointsForMostConnectedCities !== null;
        $mandalaPoints = $this->game->getMap()->mandalaPoints;

        $sql = "SELECT player_id id, player_score score FROM player ORDER BY player_no ASC";
        $players = $this->game->getCollectionFromDb($sql);

        // points gained during the game : claimed routes
        $totalScore = [];
        foreach ($players as $playerId => $playerDb) {
            $totalScore[$playerId] = intval($playerDb['score']);
        }

        $useStationResult = [];
        $playersRemainingStations = [];
        foreach ($players as $playerId => $playerDb) {
            $useStationResult[$playerId] = [0, [], [], []];
            $destinations = $this->game->destinationManager->getPlayerHand($playerId);
            $uncompletedDestinations = Arrays::filter($destinations, fn($destination) => !boolval($this->game->getUniqueValueFromDb("SELECT `completed` FROM `destination` WHERE `card_id` = $destination->id")));
            $uncompletedDestinations = $this->legendaryCharacterDiscardDestinations($playerId, $uncompletedDestinations);

            $playerStations = $this->game->buildingManager->getPlacedStations($playerId);
            $usedStations = count($playerStations);
            if ($usedStations > 0 && count($uncompletedDestinations) > 0) {
                $useStationResult[$playerId] = $this->game->buildingManager->useStations($playerId, $playerStations, $uncompletedDestinations);
            }
            if ($this->game->getMap()->stations !== null) {
                $playersRemainingStations[$playerId] = $this->game->getMap()->stations - $usedStations;
                $totalScore[$playerId] += 4 * $playersRemainingStations[$playerId];
            }
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

            $destinations = $this->game->destinationManager->getPlayerHand($playerId);

            foreach ($destinations as &$destination) {
                $completed = boolval($this->game->getUniqueValueFromDb("SELECT `completed` FROM `destination` WHERE `card_id` = $destination->id"));
                
                $points = 0;
                if (is_array($destination->to)) {
                    if ($completed) {
                        foreach ($destination->to as $index => $to) {
                            if ($destination->points[$index] > $points) {
                                $route = $this->game->mapManager->getShortestRoutesToLinkCitiesOrCountries($claimedRoutes, $destination->from, $to);
                                if ($route !== null) {
                                    $points = $destination->points[$index];
                                    $routeByCompletedDestination[$destination->id] = $route;
                                }
                            }
                        }
                    } else {
                        $points = -min($destination->points);
                    }
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
            $longestPath = $this->game->mapManager->getLongestPath($playerId);
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

        // Asian Explorer: largest continuous network of distinct cities
        $playersMostConnectedCities = [];
        $mostConnectedCitiesWinners = [];
        $bestMostConnectedCities = null;
        if ($isMostConnectedCitiesBonusActive) {
            foreach ($players as $playerId => $playerDb) {
                $playersMostConnectedCities[$playerId] = $this->game->mapManager->getMostConnectedCities($playerId);
            }
            $mostConnectedCitiesBySize = [];
            foreach ($playersMostConnectedCities as $playerId => $size) {
                $mostConnectedCitiesBySize[$size][] = $playerId;
            }
            $bestMostConnectedCities = max(array_keys($mostConnectedCitiesBySize));
            $mostConnectedCitiesWinners = $mostConnectedCitiesBySize[$bestMostConnectedCities];
            foreach ($mostConnectedCitiesWinners as $playerId) {
                $totalScore[$playerId] += $this->game->getMap()->pointsForMostConnectedCities;
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
                $completedDestinations = array_values(array_filter($destinations, fn($destination) => (array_key_exists($destination->id, $routeByCompletedDestination) ? $routeByCompletedDestination[$destination->id] : $this->game->mapManager->getDestinationRoutes($playerId, $destination)) != null));
                foreach ($completedDestinations as $destination) {
                    $distinctRoutes = $this->game->mapManager->getDistinctRoutes($playerId, $destination);
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
                $destinationRoutes = null;
                $destinationStations = null;
                $completed = boolval($this->game->getUniqueValueFromDb("SELECT `completed` FROM `destination` WHERE `card_id` = $destination->id"));
                if ($completed) {
                    $index = Arrays::findKey($useStationResult[$playerId][1], fn($d) => $d->id == $destination->id);
                    if ($index !== null) {
                        $destinationRoutes = $useStationResult[$playerId][2][$index];
                        $destinationStations = $useStationResult[$playerId][3][$index];
                    } else if (array_key_exists($destination->id, $routeByCompletedDestination)) {
                        $destinationRoutes = $routeByCompletedDestination[$destination->id];
                    } else {
                        $destinationRoutes = $this->game->mapManager->getDestinationRoutes($playerId, $destination);
                    }
                }
                $points = $pointsByDestination[$destination->id];

                $this->notify->all('scoreDestination', clienttranslate('${player_name} reveals ${from} to ${to} destination'), [
                    'playerId' => $playerId,
                    'player_name' => $this->game->getPlayerNameById($playerId),
                    'destination' => $destination,
                    'from' => $this->game->getCityName($destination->from),
                    'to' => $this->game->getLogTo($destination),
                    'destinationRoutes' => $destinationRoutes,
                    'destinationStations' => $destinationStations,
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

        // Asian Explorer
        if ($isMostConnectedCitiesBonusActive) {
            foreach ($players as $playerId => $playerDb) {
                $this->notify->all('mostConnectedCities', /* TODOMAPS clienttranslate*/('${player_name} connected ${cities} cities in their largest network'), [
                    'playerId' => $playerId,
                    'player_name' => $this->game->getPlayerNameById($playerId),
                    'length' => $playersMostConnectedCities[$playerId],
                    'cities' => $playersMostConnectedCities[$playerId],
                ]);
            }

            foreach ($mostConnectedCitiesWinners as $playerId) {
                $points = $this->game->getMap()->pointsForMostConnectedCities;
                $this->game->incScore($playerId, $points, /* TODOMAPS clienttranslate*/('${player_name} gains ${delta} points with Asian Explorer : ${cities} connected cities'), [
                    'points' => $points,
                    'cities' => $bestMostConnectedCities,
                ]);
                $this->notify->all('mostConnectedCitiesWinner', '', [
                    'playerId' => $playerId,
                    'length' => $bestMostConnectedCities,
                ]);
            }
        }

        if ($this->game->getMap()->stations !== null) {
            // stations
            foreach ($players as $playerId => $playerDb) {
                $remainingStations = $playersRemainingStations[$playerId];

                $this->bga->notify->all('remainingStations', '', [
                    'playerId' => $playerId,
                    'remainingStations' => $remainingStations,
                ]);

                $points = 4 * $remainingStations;
                $this->game->incScore($playerId, $points, clienttranslate('${player_name} gains ${delta} points with ${remainingStations} remaining station(s)'), [
                    'points' => $points,
                    'remainingStations' => $remainingStations,
                ]);

                $this->game->setStat($remainingStations, 'unusedStations', $playerId);
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
            if ($this->game->getMap()->stations !== null) {
                $scoreAux += 100 * ($playersRemainingStations[$playerId] ?? 0);
            }
            $this->bga->playerScoreAux->set((int)$playerId, $scoreAux, null);
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
                $points = $mandalaPoints[$completedMandalas];

                $this->notify->all('mandalaCount', '', [
                    'playerId' => $playerId,
                    'length' => count($playerMandalas),
                ]);
                
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
