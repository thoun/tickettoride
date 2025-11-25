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
        $this->locomotiveUsageRestriction = Map::LOCOMOTIVE_TUNNEL; // Locomotive jokers only usable on tunnels.
        $this->trainCarsPerPlayer = 40; // trains car tokens per player at the beginning of the game
        $this->unusedInitialDestinationsGoToDeckBottom = false; // Indicates if unpicked destinations cards go back to the bottom of the deck.
        $this->unusedAdditionalDestinationsGoToDeckBottom = false; // Indicates if unpicked destinations cards go back to the bottom of the deck.
        $this->pointsForGlobetrotter = null; // points for maximum completed destinations (null means disabled)
        $this->minimumPlayerForDoubleRoutes = 3; // 4 means 2-3 players cant use double routes

        $this->multilingualPdfRulesUrl = 'https://cdn.svc.asmodee.net/production-daysofwonder/uploads/2023/09/720114-T2RMC2-Rules_switzerland-ML-2017.pdf';
        $this->rulesDifferences = [
            clienttranslate('All players start with 40 trains instead of 45 trains.'),
            clienttranslate('Locomotives count as a simple card, so you can take 2 visible locomotives in one turn'),
            clienttranslate('Locomotives can only be used for tunnels'),
            clienttranslate('Some tickets link to multiple destinations. If you complete at least one, you\'ll score the highest completed one. If you don\'t complete any, you will lose the lowest.'),
            clienttranslate('You can only play 3 players maximum. Double routes are only available at 3 players.'),
            clienttranslate('Game start: Deal 5 tickets and keep at least 2.'),
            clienttranslate('All unselected tickets will be discarded from the deck.'),
        ];

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
