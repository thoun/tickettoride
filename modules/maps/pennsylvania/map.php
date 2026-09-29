<?php

use Bga\Games\TicketToRide\Objects\Map;

require_once(__DIR__.'/cities.php');
require_once(__DIR__.'/routes.php');
require_once(__DIR__.'/destinations.php');

class PennsylvaniaMap extends Map {
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
        );

        $this->trainCarsPerPlayer = 45;
        $this->unusedInitialDestinationsGoToDeckBottom = true;
        $this->unusedAdditionalDestinationsGoToDeckBottom = true;
        $this->pointsForLongestPath = null;
        $this->pointsForGlobetrotter = 15;
        $this->minimumPlayerForDoubleRoutes = 4;
        $this->countriesEndPoints = [
            -1 => [1001, 1002], // Ontario's two separate ferry endpoints
        ];

        $this->rulesDifferences = [
            clienttranslate('Deal 5 Destination Tickets at the start and keep at least 3. Later, draw 4 and keep at least 1. Return unchosen tickets to the bottom of the deck.'),
            clienttranslate('The two ferry routes to Ontario require the Locomotive cards shown on their spaces and do not connect to each other.'),
            clienttranslate('When claiming a route, you may take the top numbered Stock Share from one of the railroads shown on that route. Shares score according to railroad majorities at the end of the game.'),
            clienttranslate('In a 2-player game, every Stock Share taken also gives the dummy player a share. Reveal half of its shares, rounded up, before scoring majorities.'),
            clienttranslate('There is no longest path bonus. The player or players with the most completed tickets receive the 15-point Globetrotter bonus.'),
        ];
    }

    function getInitialDestinationPick(int $expansionValue): array {
        return ['deck' => 5];
    }

    function getInitialDestinationMinimumKept(int $expansionValue): int {
        return 3;
    }

    function getAdditionalDestinationCardNumber(int $expansionValue): int {
        return 4;
    }

    function getPreloadImages(int $expansionValue): array {
        return ['destinations-1-0.jpg'];
    }

    function getDestinationToGenerate(int $expansionValue): array {
        $destinations = [];
        foreach (getBaseDestinations() as $typeArg => $destination) {
            $destinations[] = ['type' => 1, 'type_arg' => $typeArg, 'nbr' => 1];
        }
        return ['deck' => $destinations];
    }
}

function getMap() {
    return new PennsylvaniaMap();
}
