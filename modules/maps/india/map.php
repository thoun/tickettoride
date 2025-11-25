<?php

require_once(__DIR__.'/../../php/objects/map.php');

require_once(__DIR__.'/cities.php');
require_once(__DIR__.'/routes.php');
require_once(__DIR__.'/destinations.php');

class IndiaMap extends Map {
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
                //5 => 10,
                6 => 15,
                8 => 21,
            ]
        );

        $this->visibleLocomotivesCountsAsTwoCards = true; // Says if it is possible to take only one visible locomotive.
        $this->trainCarsPerPlayer = 45; // trains car tokens per player at the beginning of the game
        $this->unusedInitialDestinationsGoToDeckBottom = true; // Indicates if unpicked destinations cards go back to the bottom of the deck.
        $this->unusedAdditionalDestinationsGoToDeckBottom = true; // Indicates if unpicked destinations cards go back to the bottom of the deck.
        $this->pointsForGlobetrotter = null; // points for maximum completed destinations (null means disabled)
        $this->minimumPlayerForDoubleRoutes = 4; // 4 means 2-3 players cant use double routes

        $this->multilingualPdfRulesUrl = 'https://cdn.svc.asmodee.net/production-asmodeees/uploads/2023/06/Reglas_TTR_India-1.pdf';
        $this->rulesDifferences = [
            clienttranslate('You can only play 4 players maximum. Double routes are only available at 4 players.'),
            clienttranslate('Ferries: To claim a Ferry route, a player must play a Locomotive card for each Locomotive symbol on the route.'),
            clienttranslate('Grand Tour bonus (Mandala): Any Ticket whose 2 Destination Cities are linked via at least 2 distinct continuous paths of its owner’s plastic trains qualifies for a Grand Tour bonus.'),
        ];

        $this->vertical = true;
        
        $this->mandalaPoints = [
            0 => 0,
            1 => 5,
            2 => 10,
            3 => 20,
            4 => 30,
            5 => 40,
        ];
    }
    
    /**
     * Return the number of destinations cards shown at the beginning, for each deck.
     */
    function getInitialDestinationPick(int $expansionValue): array {
        return ['deck' => 4];
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
            $destinations[] = [ 'type' => 1, 'type_arg' => $typeArg, 'nbr' => 1];
        }

        return [
            'deck' => $destinations
        ];
    }
}

function getMap() {
    return new IndiaMap();
}

?>
