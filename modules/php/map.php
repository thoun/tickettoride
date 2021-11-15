<?php

require_once(__DIR__.'/objects/route.php');

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
        $claimedRoutesIds = array_map(function($claimedRoute) { return $claimedRoute->routeId; }, array_values($claimedRoutes));

        // remove routes already claimed
        $claimableRoutes = array_filter($allRoutes, function($route) use ($claimedRoutesIds) { return !in_array($route->id, $claimedRoutesIds); });

        // remove routes user can't pay
        $claimableRoutes = array_values(array_filter($claimableRoutes, function($unclaimedRoute) use ($trainCarsHand, $remainingTrainCars) {
            return $this->canPayForRoute($unclaimedRoute, $trainCarsHand, $remainingTrainCars) !== null;
        }));

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
     * Get the longest continuous path for a player.
     */
    public function getLongestPath(int $playerId) {
        $claimedRoutes = $this->getClaimedRoutes($playerId);
        $claimedRoutesIds = array_map(function($claimedRoute) { return $claimedRoute->routeId; }, array_values($claimedRoutes));

        $longestPath = 0;
        
        foreach ($claimedRoutes as $claimedRoute) {
            $longestPathFromRoute = $this->getLongestPathFromRouteId($claimedRoute->routeId, $claimedRoutesIds);

            if ($longestPathFromRoute > $longestPath) {
                $longestPath = $longestPathFromRoute;
            }
        }
        
        return $longestPath;
    }

    /**
     * Indicates if destination is completed (continuous path linking both cities).
     */
    public function isDestinationCompleted(int $playerId, object $destination) {
        $claimedRoutes = $this->getClaimedRoutes($playerId);
        $claimedRoutesIds = array_map(function($claimedRoute) { return $claimedRoute->routeId; }, array_values($claimedRoutes));

        $citiesConnectedToFrom = $this->getAccessibleCitiesFrom($destination->from, [$destination->from], $claimedRoutesIds);

        return in_array($destination->from, $citiesConnectedToFrom);
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
    public function canPayForRoute(object $route, array $trainCarsHand, int $remainingTrainCars) {
        if ($remainingTrainCars < $route->number) {
            return null; // not enough remaining meeples
        }

        $colorsToTest = $route->color > 0 ? [$route->color] : [1,2,3,4,5,6,7,8];
        $locomotiveCards = array_filter($trainCarsHand, function ($card) { return $card->type == 0; });
        
        // route is gray, check for each possible color
        foreach ($colorsToTest as $color) {
            $colorCards = array_filter($trainCarsHand, function ($card) use ($color) { return $card->type == $color; });
            if (count($colorCards) + count($locomotiveCards) >= $route->number) {
                // enough color card (including locomotives)
                return array_slice(array_merge($colorCards, $locomotiveCards), 0, $route->number); 
            }
        }
        return null;
    }

    private function getTwinRoutes(object $route) {
        $allRoutes = $this->getAllRoutes();

        $twinRoutes = array_values(array_filter($allRoutes, function($twinRoute) use ($route) {
            return $twinRoute->from == $route->from && $twinRoute->to == $route->to && $twinRoute->id != $route->id;
        }));

        return $twinRoutes;
    }

    private function getRoutesConnectedToCity(int $city) {
        $allRoutes = $this->getAllRoutes();

        $connectedRoutes = array_values(array_filter($allRoutes, function($route) use ($city) {
            return $route->from == $city && $route->to == $city;
        }));

        return $connectedRoutes;
    }

    private function getAccessibleCitiesFrom(int $from, array $visitedCitiesIds, array $playerClaimedRoutesIds) {
        $connectedRoutes = $this->getRoutesConnectedToCity($from);

        // we only check route to cities we haven't checked, to avoid infinite loop
        $claimedConnectedRouteToExplore = array_values(array_filter($connectedRoutes, function($route) use ($from, $visitedCitiesIds, $playerClaimedRoutesIds) {
            $cityOnOtherSideOfRoute = $route->from == $from ? $route->to : $route->from;
            return in_array($route->id, $playerClaimedRoutesIds) && !in_array($cityOnOtherSideOfRoute, $visitedCitiesIds);
        }));

        $connectedCities = array_map(function($route) use ($from) {
            $cityOnOtherSideOfRoute = $route->from == $from ? $route->to : $route->from;
            return $cityOnOtherSideOfRoute;
        }, $claimedConnectedRouteToExplore);

        $recursiveConnectedCities = $connectedCities; // copy
        $newVisitedCitiesIds = array_merge($visitedCitiesIds, $connectedCities);
        foreach ($connectedCities as $connectedCity) {
            $recursiveConnectedCities = array_merge(
                $recursiveConnectedCities, 
                $this->getAccessibleCitiesFrom($connectedCity, $newVisitedCitiesIds, $playerClaimedRoutesIds)
            );
        }

        return $recursiveConnectedCities;
    }

    private function getLongestPathFromRouteId(int $fromRouteId, array $claimedRoutesIds) {
        $fromRoute = $this->ROUTES[$fromRouteId];

        return $fromRoute->number + max(
            $this->getLongestPathFromCity($fromRoute->from, [$fromRoute->id], $claimedRoutesIds),
            $this->getLongestPathFromCity($fromRoute->to, [$fromRoute->id], $claimedRoutesIds)
        );
    }

    private function getLongestPathFromCity(int $from, array $visitedRoutesIds, array $playerClaimedRoutesIds) {
        $connectedRoutes = $this->getRoutesConnectedToCity($from);

        // we only check route we haven't checked, to avoid infinite loop
        $claimedConnectedRoutesToExplore = array_values(array_filter($connectedRoutes, function($route) use ($from, $visitedRoutesIds, $playerClaimedRoutesIds) {
            $cityOnOtherSideOfRoute = $route->from == $from ? $route->to : $route->from;
            return in_array($route->id, $playerClaimedRoutesIds) && !in_array($route->id, $visitedRoutesIds);
        }));

        $longestPath = 0;

        foreach ($claimedConnectedRoutesToExplore as $route) {
            $cityOnOtherSideOfRoute = $route->from == $from ? $route->to : $route->from;
            $longestPathFromRoute = $this->getLongestPathFromCity(
                $cityOnOtherSideOfRoute, 
                array_merge($visitedRoutesIds, [$claimedConnectedRoutesToExplore->id]),
                $playerClaimedRoutesIds
            );

            if ($longestPathFromRoute > $longestPath) {
                $longestPath = $longestPathFromRoute;
            }
        }

        return $longestPath;
    }
}