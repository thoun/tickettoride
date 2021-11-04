<?php

require_once(__DIR__.'/objects/route.php');

class Map {
    /** Access to main game functions. */
    private /*object*/ $game;

    function __construct(object &$game) {
        $this->game = $game;
	}

    /**
     * List routes a player can claim.
     */
    public function claimableRoutes(array $trainCarsHand, int $remainingTrainCars) {
        $routes = [];
        $unclaimedRoutes = $this->getUnclaimedRoutes();

        foreach ($unclaimedRoutes as $unclaimedRoute) {
            if ($this->canClaimRoute($unclaimedRoute, $trainCarsHand, $remainingTrainCars)) {
                $routes[] = $unclaimedRoute;
            }
        }
        
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
        // TODO 
        return true;
    }
    
    private function getUnclaimedRoutes() {
        $allRoutes = $this->game->ROUTES;
        $claimedRoutesIds = $this->game->getClaimedRoutesIds();

        return array_filter($allRoutes, function($id) use ($claimedRoutesIds) { return !in_array($id, $claimedRoutesIds); }, ARRAY_FILTER_USE_KEY);
    }

    private function canClaimRoute(object $route, array $trainCarsHand, int $remainingTrainCars) {
        if ($remainingTrainCars < $route->number) {
            return false; // not enough remaining meeples
        }

        $routeColor = $route->color;        
        $colorAndLocomotiveCards = array_filter($trainCarsHand, function ($card) use ($routeColor) { return in_array($card->color, [0, $routeColor]); });
        return count($colorAndLocomotiveCards) >= $route->number; // enough color card (including locomotives)
    }
}