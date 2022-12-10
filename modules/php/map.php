<?php

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
        $claimedRoutesIds = array_map(fn($claimedRoute) => $claimedRoute->routeId, array_values($claimedRoutes));

        $citiesConnectedToFrom = $this->getAccessibleCitiesFrom(new ConnectedCity($destination->from, []), [$destination->from], $claimedRoutesIds);

        $validConnections = array_values(array_filter($citiesConnectedToFrom, fn($connectedCity) => $connectedCity->city == $destination->to));
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

    public function getAllRoutes() {
        $allRoutes = $this->ROUTES;
        array_walk($allRoutes, function(&$route, $id) { $route->id = $id; });
        return $allRoutes;
    }
    
    private function isDoubleRouteAllowed() {
        return $this->getPlayerCount() >= MINIMUM_PLAYER_FOR_DOUBLE_ROUTES;
    }

    /**
     * Indicates if the player got enough train cars (meeples) left, and enough Train car cards (of route color + locomotive).
     * If player cannot pay, returns null.
     * If player can pay return cards to pay for the route.
     */
    public function canPayForRoute(object $route, array $trainCarsHand, int $remainingTrainCars, /*int|null*/ $color = null, int $extraCardsCost = 0) {
        $cardCost = $route->number + $extraCardsCost;

        if ($remainingTrainCars < $route->number) {
            return null; // not enough remaining meeples
        }

        if ($color != null && $color > 0 && $route->color > 0 && $color != $route->color) {
            return null;
        }

        $colorsToTest = [1,2,3,4,5,6,7,8];
        if ($color > 0) {
            $colorsToTest = [$color];
        } else if ($route->color > 0) {
            $colorsToTest = [$route->color];
        }
        $locomotiveCards = array_filter($trainCarsHand, fn($card) => $card->type == 0);

        if (count($locomotiveCards) < $route->locomotives) {
            return null;
        }
        
        if ($color === 0) {
            // the user wants to pay with locomotives
            if (count($locomotiveCards) >= $cardCost) {
                // enough locomotive cards
                return array_slice($locomotiveCards, 0, $cardCost); 
            }
        } else {        
            // route is gray, check for each possible color
            foreach ($colorsToTest as $color) {
                $colorCards = array_filter($trainCarsHand, fn($card) => $card->type == $color);

                // first we set required locomotives
                $locomotiveCardsCount = $route->locomotives;
                $colorCardCount = 0;
                // then we add as much color card as needed
                if ($locomotiveCardsCount < $cardCost) {
                    $colorCardCount = min($cardCost - $locomotiveCardsCount, count($colorCards));
                }
                // we complete with locomotives if needed
                if ($locomotiveCardsCount + $colorCardCount < $cardCost) {
                    $locomotiveCardsCount += min($cardCost - ($locomotiveCardsCount + $colorCardCount), count($locomotiveCards) - $locomotiveCardsCount);
                }

                if (
                    ($locomotiveCardsCount + $colorCardCount) >= $cardCost 
                    && $locomotiveCardsCount <= count($locomotiveCards) 
                    && $colorCardCount <= count($colorCards)
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

    // return an array of ConnectedCity
    private function getAccessibleCitiesFrom(object $connectedCity, array $visitedCitiesIds, array $playerClaimedRoutesIds) {
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

    //  Returns a LongestPath object.
    private function getLongestPathFromRouteId(int $fromRouteId, array $claimedRoutesIds) {
        $fromRoute = $this->ROUTES[$fromRouteId];
        $fromRoute->id = $fromRouteId;

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