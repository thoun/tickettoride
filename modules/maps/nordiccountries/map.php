<?php

require_once(__DIR__.'/../../php/objects/map.php');

require_once(__DIR__.'/cities.php');
require_once(__DIR__.'/routes.php');
require_once(__DIR__.'/destinations.php');

class NordicCountriesMap extends Map {
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
                //8 => 21,
                9 => 27,
            ]
        );

        $this->visibleLocomotivesCountsAsTwoCards = false; // Says if it is possible to take only one visible locomotive.
        $this->locomotiveUsageRestriction = Map::LOCOMOTIVE_TUNNEL | Map::LOCOMOTIVE_FERRY; // Locomotive jokers only usable on tunnels or ferries.
        $this->trainCarsPerPlayer = 40; // trains car tokens per player at the beginning of the game
        $this->unusedInitialDestinationsGoToDeckBottom = false; // Indicates if unpicked destinations cards go back to the bottom of the deck.
        $this->unusedAdditionalDestinationsGoToDeckBottom = false; // Indicates if unpicked destinations cards go back to the bottom of the deck.
        $this->pointsForLongestPath = null; // points for maximum longest countinuous path (null means disabled)
        $this->pointsForGlobetrotter = 10; // points for maximum completed destinations (null means disabled)
        $this->minimumPlayerForDoubleRoutes = 3; // 4 means 2-3 players cant use double routes

        $this->multilingualPdfRulesUrl = 'https://cdn.svc.asmodee.net/staging-daysofwonder/uploads/2024/07/7208-T2RNC-Rules-EN.pdf';
        $this->rulesDifferences = [
            clienttranslate('All players start with 40 trains instead of 45 trains.'),
            clienttranslate('Locomotives count as a simple card, so you can take 2 visible locomotives in one turn'),
            clienttranslate('Locomotives can only be used for tunnels and ferries'),
            clienttranslate('You can only play 3 players maximum. Double routes are only available at 3 players.'),
            clienttranslate('Game start: Deal 5 tickets and keep at least 2.'),
            clienttranslate('All unselected tickets will be discarded from the deck.'),
            clienttranslate('You can spend any set of 3 cards to replace a locomotive on ferries.'),
            clienttranslate('You can spend any set of 4 cards to replace a color card on the Murmansk-Lieksa route.'),
        ];

        $this->vertical = true;
    }
    
    /**
     * Return the number of destinations cards shown at the beginning, for each deck.
     */
    function getInitialDestinationPick(int $expansionValue): array {
        return ['deck' => 5];
    }

    function getPreloadImages(int $expansionValue): array {
        return ['destinations-1-0.jpg', 'train-cards.jpg'];
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
    
    /**
     * Return if Globetrotter bonus card is used for the game.
     */
    function isGlobetrotterBonusActive(int $expansionValue): bool {
        return true;
    }
    
    /**
     * Return if Longest Path bonus card is used for the game.
     */
    function isLongestPathBonusActive(int $expansionValue): bool {
        return false;
    }
}

function getMap() {
    return new NordicCountriesMap();
}

?>
