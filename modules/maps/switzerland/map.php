<?php

require_once(__DIR__.'/../../php/objects/map.php');

require_once(__DIR__.'/cities.php');
require_once(__DIR__.'/routes.php');
require_once(__DIR__.'/destinations.php');

class SwitzerlandMap extends Map {
    public function __construct() {
        parent::__construct(
            getCities(),
            /**
             * Route on the map. 
             * For double routes, there is 2 instances of Route.
             * For cities (from/to), it's always low id to high id.
             */
            getRoutes(),
            /**
             * List of DestinationCard.
             */
            getAllDestinations(),
            /**
             * Points scored for claimed routes.
             */
            [
                1 => 1,
                2 => 2,
                3 => 4,
                4 => 7,
                5 => 10,
                6 => 15,
                8 => 21,
            ]
        );

        $this->visibleLocomotivesCountsAsTwoCards = false; // Says if it is possible to take only one visible locomotive.
        $this->canOnlyUseLocomotivesInTunnels = true; // Says locomotives are reserved to tunnels.
        $this->trainCarsPerPlayer = 40; // trains car tokens per player at the beginning of the game
        $this->unusedInitialDestinationsGoToDeckBottom = false; // Indicates if unpicked destinations cards go back to the bottom of the deck.
        $this->unusedAdditionalDestinationsGoToDeckBottom = false; // Indicates if unpicked destinations cards go back to the bottom of the deck.
        $this->pointsForGlobetrotter = null; // points for maximum completed destinations (null means disabled)
        $this->minimumPlayerForDoubleRoutes = 3; // 4 means 2-3 players cant use double routes

        $this->countriesEndPoints = [
            -1 => [ 1001, 1002, 1003, 1004 ],
            -2 => [ 2001, 2002, 2003, 2004, 2005 ],
            -3 => [ 3001, 3002, 3003 ],
            -4 => [ 4001, 4002, 4003, 4004, 4005 ],
        ];
    }
    
    /**
     * Return the number of destinations cards shown at the beginning, for each deck.
     */
    function getInitialDestinationPick(int $expansionValue): array {
        return ['deck' => 5];
    }

    function getPreloadImages(int $expansionValue): array {
        return ['destinations-1-0.jpg'];
    }
    
    /**
     * List the destination tickets that will be used for the game.
     */
    function getDestinationToGenerate(int $expansionValue): array {
        $destinations = [];

        $base = getBaseDestinations();
        foreach($base as $typeArg => $destination) {
            $destinations[] = [ 'type' => 1, 'type_arg' => $typeArg, 'nbr' => $typeArg <= 4 ? 2 : 1];
        }

        return [
            'deck' => $destinations
        ];
    }
}

function getMap() {
    return new SwitzerlandMap();
}

?>