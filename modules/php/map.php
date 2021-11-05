<?php

require_once(__DIR__.'/objects/route.php');

class Map {
    /** Access to main game functions. */
    private /*object*/ $game;
    private /*int*/ $playerCount;
    private /*int*/ $minimumPlayerForDoubleRoutes;

    function __construct(object &$game, int $playerCount, int $minimumPlayerForDoubleRoutes = 4) {
        $this->game = $game;
        $this->playerCount = $playerCount;
        $this->minimumPlayerForDoubleRoutes = $minimumPlayerForDoubleRoutes;
	}

    /**
     * List routes a player can claim.
     */
    public function claimableRoutes(int $playerId, array $trainCarsHand, int $remainingTrainCars) {
        $allRoutes = $this->getAllRoutes();
        $claimedRoutes = $this->game->getClaimedRoutes();
        $claimedRoutesIds = array_map(function($dbResult) { return intval($dbResult['route_id']); }, array_values($claimedRoutes));

        // remove routes already claimed
        $claimableRoutes = array_filter($allRoutes, function($route) use ($claimedRoutesIds) { return !in_array($route->id, $claimedRoutesIds); });

        // remove routes user can't pay
        $claimableRoutes = array_values(array_filter($claimableRoutes, function($unclaimedRoute) use ($trainCarsHand, $remainingTrainCars) {
            return $this->canPayForRoute($unclaimedRoute, $trainCarsHand, $remainingTrainCars);
        }));

        $doubleRouteAllowed = $this->isDoubleRouteAllowed();
        // remove double routes if low player count, or if player already got the other route
        $claimableRoutes = array_values(array_filter($claimableRoutes, function($unclaimedRoute) use ($playerId, $claimedRoutes, $doubleRouteAllowed) {
            $twinRoutes = $this-getTwinRoutes($unclaimedRoute);
            foreach($twinRoutes as $twinRoute) {
                // we check if twin route is claimed
                $twinRouteClaimedBy = null;
                foreach($claimedRoutes as $claimedRoute) {
                    if (intval($claimedRoute['route_id']) == $twinRoute->id) {
                        $twinRouteClaimedBy = intval($claimedRoute['player_id']);
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
        
        return $routes;
    }

    /**
     * Get the longest continuous path for a player.
     */
    public function getLongestPath(int $playerId) {
        // TODO
        return 0;
    }

    /**
     * Indicates if destination is completed (continuous path linking both cities).
     */
    public function isDestinationCompleted(int $playerId, object $destination) {
        $claimedRoutes = $this->game->getClaimedRoutes($playerId);
        $claimedRoutesIds = array_map(function($dbResult) { return intval($dbResult['route_id']); }, array_values($claimedRoutes));

        $citiesConnectedToFrom = $this->getAccessibleCitiesFrom($destination->from, [$destination->from], $claimedRoutesIds);

        return in_array($destination->from, $citiesConnectedToFrom);
    }

    public function getAllRoutes() {
        $allRoutes = $this->game->ROUTES;
        array_walk($allRoutes, function(&$route, $id) { $route->id = $id; });
        return $allRoutes;
    }
    
    private function isDoubleRouteAllowed() {
        return $this->playerCount >= $this->minimumPlayerForDoubleRoutes;
    }

    private function canPayForRoute(object $route, array $trainCarsHand, int $remainingTrainCars) {
        if ($remainingTrainCars < $route->number) {
            return false; // not enough remaining meeples
        }

        $routeColor = $route->color;        
        $colorAndLocomotiveCards = array_filter($trainCarsHand, function ($card) use ($routeColor) { return in_array($card->color, [0, $routeColor]); });
        return count($colorAndLocomotiveCards) >= $route->number; // enough color card (including locomotives)
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
}