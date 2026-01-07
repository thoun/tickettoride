<?php

namespace Bga\Games\TicketToRide;

use Bga\GameFrameworkPrototype\Helpers\Arrays;

require_once(__DIR__.'/objects/route.php');

class ConnectedCity {
    public int $city;
    public array $routes;
  
    public function __construct(int $city, array $routes) {
        $this->city = $city;
        $this->routes = $routes;
    } 
}

class LongestPath {
    public int $length;
    public array $routes;
  
    public function __construct(int $length, array $routes) {
        $this->length = $length;
        $this->routes = $routes;
    } 
}

trait MapTrait {

    /**
     * List routes a player can claim. Player can claim only if :
     * - he got enough train cars & train car cards
     * - it is not already claimed
     * - player count allows it (if double route)
     */
    public function claimableRoutes(int $playerId, array $trainCarsHand, int $remainingTrainCars) {
        $allRoutes = $this->getAllRoutes();
        $claimedRoutes = $this->getClaimedRoutes();
        $claimedRoutesIds = array_map(fn($claimedRoute) => $claimedRoute->routeId, array_values($claimedRoutes));

        // remove routes already claimed
        $claimableRoutes = array_filter($allRoutes, fn($route) => !in_array($route->id, $claimedRoutesIds));

        // remove routes user can't pay
        $claimableRoutes = array_values(array_filter($claimableRoutes, fn($unclaimedRoute) => 
           $this->canPayForRoute($unclaimedRoute, $trainCarsHand, $remainingTrainCars) !== null
        ));

        $doubleRouteAllowed = $this->isDoubleRouteAllowed();
        // remove double routes if low player count, or if player already got the other route
        $claimableRoutes = array_values(array_filter($claimableRoutes, function($unclaimedRoute) use ($playerId, $claimedRoutes, $doubleRouteAllowed) {
            $twinRoutes = $this->getTwinRoutes($unclaimedRoute);
            foreach($twinRoutes as $twinRoute) {
                // we check if twin route is claimed
                $twinRouteClaimedBy = null;
                foreach($claimedRoutes as $claimedRoute) {
                    if ($claimedRoute->routeId == $twinRoute->id) {
                        $twinRouteClaimedBy = $claimedRoute->playerId;
                        break;
                    }
                }

                if ($twinRouteClaimedBy !== null) {
                    // twin route is claimed by someone
                    // if double routes are not allowed, or player already got twin route, he can claim route
                    if (!$doubleRouteAllowed || $twinRouteClaimedBy == $playerId) {
                        return false;
                    }
                }
            }
            return true;
        }));
        
        return $claimableRoutes;
    }

    /**
     * Get the longest continuous path for a player. Returns a LongestPath object.
     */
    public function getLongestPath(int $playerId) {
        $claimedRoutes = $this->getClaimedRoutes($playerId);
        $claimedRoutesIds = array_map(fn($claimedRoute) => $claimedRoute->routeId, array_values($claimedRoutes));

        $longestPath = new LongestPath(0, []);
        
        foreach ($claimedRoutes as $claimedRoute) {
            $longestPathFromRoute = $this->getLongestPathFromRouteId($claimedRoute->routeId, $claimedRoutesIds);

            if ($longestPathFromRoute->length > $longestPath->length) {
                $longestPath = $longestPathFromRoute;
            }
        }
        
        return $longestPath;
    }

    /**
     * Indicates if destination is completed (continuous path linking both cities).
     */
    public function getDestinationRoutes(int $playerId, object $destination) {
        $claimedRoutes = $this->getClaimedRoutes($playerId);

        if (is_array($destination->to)) {
            // multiple destination possibilities, test from the top-most points to the bottom-most points
            foreach ($destination->to as $to) {
                $routes = $this->getShortestRoutesToLinkCitiesOrCountries($claimedRoutes, $destination->from, $to);
                if ($routes !== null) {
                    return $routes;
                }
            }
        } else {
            // simple case, 1 destination
            return $this->getShortestRoutesToLinkCitiesOrCountries($claimedRoutes, $destination->from, $destination->to);
        }
    }

    public function getShortestRoutesToLinkCitiesOrCountries(array $claimedRoutes, int $from, int $to) {
        $froms = [];
        $tos = [];

        if ($from < 0) {
            $froms = array_merge($froms, $this->getMap()->countriesEndPoints[$from]);
        } else {
            $froms[] = $from;
        }

        if ($to < 0) {
            $tos = array_merge($tos, $this->getMap()->countriesEndPoints[$to]);
        } else {
            $tos[] = $to;
        }

        foreach ($froms as $from) {
            foreach ($tos as $to) {
                $routes = $this->getShortestRoutesToLinkCities($claimedRoutes, $from, $to);
                if ($routes !== null) {
                    return $routes;
                }
            }
        }
    }

    private function getShortestRoutesToLinkCities(array $claimedRoutes, int $from, int $to): array | null {
        $claimedRoutesIds = array_map(fn($claimedRoute) => $claimedRoute->routeId, array_values($claimedRoutes));

        $citiesConnectedToFrom = $this->getAccessibleCitiesFrom(new ConnectedCity($from, []), [$from], $claimedRoutesIds);

        $validConnections = array_values(array_filter($citiesConnectedToFrom, fn($connectedCity) => $connectedCity->city == $to));
        $count = count($validConnections);

        if ($count == 0) {
            return null;
        } else if ($count == 1) {
            return $validConnections[0]->routes;
        } else {
            $shortest = $validConnections[0];

            foreach($validConnections as $validConnection) {
                if (count($validConnection->routes) < count($shortest->routes)) {
                    $shortest = $validConnection;
                }
            }
            
            return $shortest->routes;
        }
    }

     /**
     * @return ConnectedCity[]
     */
    public function getDistinctRoutes(int $playerId, object $destination): array {
        $claimedRoutes = $this->getClaimedRoutes($playerId);

        $claimedRoutesIds = array_map(fn($claimedRoute) => $claimedRoute->routeId, array_values($claimedRoutes));

        $citiesConnectedToFrom = $this->getAccessibleCitiesFromDistinctRoutes(new ConnectedCity($destination->from, []), [], $claimedRoutesIds);

        $validConnections = array_values(array_filter($citiesConnectedToFrom, fn($connectedCity) => $connectedCity->city == $destination->to));

        if (count($validConnections) < 2) {
            return $validConnections;
        }

        foreach ($validConnections as $index1 => $validConnection1) {
            foreach ($validConnections as $index2 => $validConnection2) {
                if ($index1 != $index2) {
                    $allDifferent = array_all($validConnection1->routes, fn($route1) => array_all($validConnection2->routes, fn($route2) => $route1->id != $route2->id));
                    if ($allDifferent) {
                        return [$validConnection1, $validConnection2];
                    }
                }
            }
        }

        return [$validConnections[0]];
    }

    public function getAllRoutes() {
        $allRoutes = $this->getMap()->routes;
        array_walk($allRoutes, function(&$route, $id) { $route->id = $id; });
        return $allRoutes;
    }
    
    private function isDoubleRouteAllowed() {
        return $this->getPlayerCount() >= $this->getMap()->minimumPlayerForDoubleRoutes;
    }

    /**
     * Indicates if the player got enough train cars (meeples) left, and enough Train car cards (of route color + locomotive).
     * If player cannot pay, returns null.
     * If player can pay return cards to pay for the route.
     */
    public function canPayForRoute(object $route, array $trainCarsHand, int $remainingTrainCars, ?int $color = null, int $extraCardsCost = 0, ?array $distributionCards = null): ?array {
        $cardCost = $route->number + $extraCardsCost;

        if ($remainingTrainCars < $route->number) {
            return null; // not enough remaining meeples
        }

        if ($color != null && $color > 0 && $route->color > 0 && $color != $route->color) {
            return null; // we try to pay with a color that doesn't match the route color
        }

        $colorsToTest = [1,2,3,4,5,6,7,8];
        if ($color > 0) {
            $colorsToTest = [$color];
        } else if ($route->color > 0) {
            $colorsToTest = [$route->color];
        }
        // if all route spaces are locomotives, you can only pay it with locomotives
        if ($route->locomotives === $route->number) {
            $colorsToTest = [0];
        }

        $locomotiveCards = array_filter($trainCarsHand, fn($card) => $card->type == 0);

        $locomotiveRestriction = $this->getMap()->locomotiveUsageRestriction;
        $canUseRestrictedLocomotiveAsJoker = 
            $locomotiveRestriction === 0
            || (($locomotiveRestriction & \Map::LOCOMOTIVE_TUNNEL) !== 0 && $route->tunnel)
            || (($locomotiveRestriction & \Map::LOCOMOTIVE_FERRY) !== 0 && $route->locomotives > 0);
        $forbidLocomotiveAsJoker = !$canUseRestrictedLocomotiveAsJoker;

        if ($distributionCards) {
            $locomotiveCount = $forbidLocomotiveAsJoker ? 0 : Arrays::count($distributionCards, fn($card) => $card->type == 0);
            $colorCount = $color === 0 ? 0 : Arrays::count($distributionCards, fn($card) => $card->type == $color);
            $setCount = 0;
            if ($route->canPayWithAnySetOfCards) {
                $setCardsCount = Arrays::count($distributionCards, fn($card) => !in_array($card->type, $forbidLocomotiveAsJoker ? [$color] : [0, $color]));
                $setCount = (int)floor($setCardsCount / $route->canPayWithAnySetOfCards);
            }
            // check if valid
            if (($locomotiveCount + $setCount) < $route->locomotives) {
                return null;
            } 
            if (($locomotiveCount + $colorCount + $setCount) === ($route->number + $extraCardsCost)) {
                return $distributionCards;
            } else {
                return null;
            }
        }

        // after distribution, so we don't block if locomotives are replaced by set of cards
        if (!$route->canPayWithAnySetOfCards && count($locomotiveCards) < $route->locomotives) {
            return null;
        }
        
        if ($color === 0) {
            // the user wants to pay with locomotives
            $possibleSets = $route->canPayWithAnySetOfCards ? (int)floor(Arrays::count($trainCarsHand, fn($card) => $card->type != 0) / $route->canPayWithAnySetOfCards) : 0;
            if ((count($locomotiveCards) + $possibleSets) >= $cardCost) {
                if ($forbidLocomotiveAsJoker) {
                    return null;
                }
                // enough locomotive cards
                return array_slice($locomotiveCards, 0, $cardCost); 
            }
        } else {
            // route is gray, check for each possible color
            foreach ($colorsToTest as $color) {
                $colorCards = array_filter($trainCarsHand, fn($card) => $card->type == $color);

                $locomotiveCardsCount = 0;
                $colorCardCount = 0;
                if ($color === 0) { // pay with locomotives only
                    $colorCards = [];
                    $locomotiveCardsCount = $cardCost;
                } else {
                    $colorCards = array_slice($colorCards, 0, $cardCost - $route->locomotives);
                    // first we set required locomotives
                    $locomotiveCardsCount = $route->locomotives;
                    // then we add as much color card as needed
                    if ($locomotiveCardsCount < $cardCost) {
                        $colorCardCount = min($cardCost - $locomotiveCardsCount, count($colorCards));
                    }
                    // we complete with locomotives if needed
                    if ($locomotiveCardsCount + $colorCardCount < $cardCost && !$forbidLocomotiveAsJoker) {
                        $locomotiveCardsCount += min($cardCost - ($locomotiveCardsCount + $colorCardCount), count($locomotiveCards) - $locomotiveCardsCount);
                    }
                }

                $singleCardsUsed = array_merge(
                    array_slice($colorCards, 0, count($colorCards)),
                    array_slice($locomotiveCards, 0, $locomotiveCardsCount),
                );
                $possibleSets = $route->canPayWithAnySetOfCards ? (int)floor(Arrays::count($trainCarsHand, fn($card) => !Arrays::some($singleCardsUsed, fn($sc) => $sc->id == $card->id)) / $route->canPayWithAnySetOfCards) : 0;

                if (
                    (count($singleCardsUsed) + $possibleSets) >= $cardCost
                    && $route->locomotives <= (count($locomotiveCards) + $possibleSets)
                ) {
                    return array_merge(
                        // first required locomotives
                        array_slice($locomotiveCards, 0, $route->locomotives),
                        // then color cards
                        array_slice($colorCards, 0, $colorCardCount),
                        // then remaining locomotives
                        array_slice($locomotiveCards, $route->locomotives, $locomotiveCardsCount - $route->locomotives)
                    );
                }
            }
        }

        return null;
    }

    private function getTwinRoutes(object $route) {
        $allRoutes = $this->getAllRoutes();

        $twinRoutes = array_values(array_filter($allRoutes, fn($twinRoute) =>
            $twinRoute->from == $route->from && $twinRoute->to == $route->to && $twinRoute->id != $route->id
        ));

        return $twinRoutes;
    }

    private function getRoutesConnectedToCity(int $city) {
        $allRoutes = $this->getAllRoutes();

        $connectedRoutes = array_values(array_filter($allRoutes, fn($route) =>
            $route->from == $city || $route->to == $city
        ));

        return $connectedRoutes;
    }

    /**
     * @return ConnectedCity[]
     */
    private function getAccessibleCitiesFrom(object $connectedCity, array $visitedCitiesIds, array $playerClaimedRoutesIds): array {
        $connectedRoutes = $this->getRoutesConnectedToCity($connectedCity->city);

        // we only check route to cities we haven't checked, to avoid infinite loop
        $claimedConnectedRouteToExplore = array_values(array_filter($connectedRoutes, function($route) use ($connectedCity, $visitedCitiesIds, $playerClaimedRoutesIds) {
            $cityOnOtherSideOfRoute = $route->from == $connectedCity->city ? $route->to : $route->from;
            return in_array($route->id, $playerClaimedRoutesIds) && !in_array($cityOnOtherSideOfRoute, $visitedCitiesIds);
        }));

        $connectedCities = array_map(function($route) use ($connectedCity) {
            $cityOnOtherSideOfRoute = $route->from == $connectedCity->city ? $route->to : $route->from;
            return new ConnectedCity($cityOnOtherSideOfRoute, array_merge($connectedCity->routes, [$route]));
        }, $claimedConnectedRouteToExplore);

        $recursiveConnectedCities = $connectedCities; // copy
        $newVisitedCitiesIds = array_merge($visitedCitiesIds, array_map(fn ($cc) => $cc->city, $connectedCities));
        foreach ($connectedCities as $connectedCity) {
            $recursiveConnectedCities = array_merge(
                $recursiveConnectedCities, 
                $this->getAccessibleCitiesFrom($connectedCity, $newVisitedCitiesIds, $playerClaimedRoutesIds)
            );
        }

        return $recursiveConnectedCities;
    }

    /**
     * Similar to getAccessibleCitiesFrom but allows revisiting cities as long as a route is never reused.
     *
     * @return ConnectedCity[]
     */
    private function getAccessibleCitiesFromDistinctRoutes(object $connectedCity, array $visitedRoutesIds, array $playerClaimedRoutesIds): array {
        $connectedRoutes = $this->getRoutesConnectedToCity($connectedCity->city);

        $claimedConnectedRoutesToExplore = array_values(array_filter($connectedRoutes, function($route) use ($playerClaimedRoutesIds, $visitedRoutesIds) {
            return in_array($route->id, $playerClaimedRoutesIds) && !in_array($route->id, $visitedRoutesIds);
        }));

        $connectedCities = [];

        foreach ($claimedConnectedRoutesToExplore as $route) {
            $cityOnOtherSideOfRoute = $route->from == $connectedCity->city ? $route->to : $route->from;
            $nextConnectedCity = new ConnectedCity($cityOnOtherSideOfRoute, array_merge($connectedCity->routes, [$route]));
            $connectedCities[] = $nextConnectedCity;

            $newVisitedRoutesIds = $visitedRoutesIds;
            $newVisitedRoutesIds[] = $route->id;
            foreach ($this->getAccessibleCitiesFromDistinctRoutes($nextConnectedCity, $newVisitedRoutesIds, $playerClaimedRoutesIds) as $recursiveCity) {
                $connectedCities[] = $recursiveCity;
            }
        }

        return $connectedCities;
    }

    //  Returns a LongestPath object.
    private function getLongestPathFromRouteId(int $fromRouteId, array $claimedRoutesIds) {
        $fromRoute = $this->getAllRoutes()[$fromRouteId];

        $pathFrom = $this->getLongestPathFromCity($fromRoute->from, [$fromRoute->id], $claimedRoutesIds);
        $pathTo = $this->getLongestPathFromCity($fromRoute->to, [$fromRoute->id], $claimedRoutesIds);

        $longestPath = $pathFrom->length > $pathTo->length ? $pathFrom : $pathTo;

        // we add fromRoute
        return new LongestPath($longestPath->length + $fromRoute->number, array_merge(
            $longestPath->routes,
            [$fromRoute]
        ));
    }

    private function getLongestPathFromCity(int $from, array $visitedRoutesIds, array $playerClaimedRoutesIds) {
        $connectedRoutes = $this->getRoutesConnectedToCity($from);

        // we only check route we haven't checked, to avoid infinite loop
        $claimedConnectedRoutesToExplore = array_values(array_filter($connectedRoutes, fn($route) =>
            in_array($route->id, $playerClaimedRoutesIds) && !in_array($route->id, $visitedRoutesIds)
        ));

        $longestPath = new LongestPath(0, []);

        foreach ($claimedConnectedRoutesToExplore as $route) {
            $cityOnOtherSideOfRoute = $route->from == $from ? $route->to : $route->from;
            $longestPathFromRouteBefore = $this->getLongestPathFromCity(
                $cityOnOtherSideOfRoute, 
                array_merge($visitedRoutesIds, [$route->id]),
                $playerClaimedRoutesIds
            );
            $longestPathFromRoute = new LongestPath(
                $longestPathFromRouteBefore->length + $route->number, 
                array_merge($longestPathFromRouteBefore->routes, [$route])
            );

            if ($longestPathFromRoute->length > $longestPath->length) {
                $longestPath = $longestPathFromRoute;
            }
        }

        return $longestPath;
    }
}
