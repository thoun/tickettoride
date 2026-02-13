<?php

use Bga\Games\TicketToRideEurope\Game;
use Bga\Games\TicketToRideEurope\Objects\Map;

require_once(__DIR__.'/cities.php');
require_once(__DIR__.'/routes.php');
require_once(__DIR__.'/destinations.php');

class FranceMap extends Map {
    public function __construct() {
        parent::__construct(
            getCities(),
            getRoutes(),
            getAllDestinations(),
        );

        $this->trainCarsPerPlayer = 40;
        $this->initialTrainCarCardsInHand = 8;
        $this->unusedInitialDestinationsGoToDeckBottom = true;
        $this->unusedAdditionalDestinationsGoToDeckBottom = true;
        $this->additionalDestinationMinimumKept = 1;
        $this->pointsForLongestPath = 10;
        $this->pointsForGlobetrotter = 15;
        $this->maximumPlayerForDoubleRoutes = [2 => 1, 3 => 1, 4 => 2, 5 => 2];
        $this->maximumPlayerForTripleRoutes = [2 => 1, 3 => 1, 4 => 3, 5 => 3];
        $this->useTrackBedPieces = true;
        // Crossings checked against img/france/map.webp. Each pair blocks both ways.
        $this->blockedTrackBedRoutes = [
            12 => [90],       // Angers-Rennes / Le Mans-Lorient
            15 => [83],       // Avignon-Briançon / Grenoble-Marseille
            24 => [56, 83],   // Avignon-Nice / Briançon-Marseille, Grenoble-Marseille
            44 => [71],       // Bourges-Brive-la-Gaillarde / Clermont-Ferrand-Limoges
            53 => [102],      // Brest-Rennes / Lorient-Saint-Malo
            56 => [24],
            69 => [146],      // Cherbourg-Le Mans / Rouen-Saint-Malo
            71 => [44],
            83 => [15, 24],
            88 => [146],      // Le Havre-Le Mans / Rouen-Saint-Malo
            90 => [12, 149],  // Le Mans-Lorient / Angers-Rennes, Nantes-Rennes
            102 => [53],
            146 => [69, 88],
            149 => [90],
            150 => [151],     // Besançon-Nancy / Dijon-Mulhouse
            151 => [150],
        ];

        // Zone tickets accept any listed endpoint; each is a separate dead end.
        $this->countriesEndPoints = [
            -1 => [1001, 1002, 1003], // Belgique
            -2 => [2001, 2002, 2003], // Allemagne
            -3 => [3001, 3002, 3003], // Suisse
            -4 => [4001, 4002, 4003], // Italie
            -5 => [5001, 5002, 5003, 5004], // Espagne
            -6 => [6001, 6002, 6003], // Corse
        ];

        $this->rulesDifferences = [
            clienttranslate('Each player starts with 40 trains and 8 Train Car cards.'),
            clienttranslate('Deal 5 Destination Tickets at the start and keep at least 3. Shuffle all rejected initial tickets together and place them under the deck. Later, draw 4 tickets and keep at least 1; return unchosen tickets to the bottom of the deck.'),
            clienttranslate('After drawing Train Car cards, you must build one route: place an available Track Piece on a Track Bed of the same length. The Track Piece determines the route color. Any player may later claim the built route.'),
            clienttranslate('A Track Piece cannot cross another Track Piece or a claimed route. Building a crossing route permanently cuts off the crossed routes, even after the built route is claimed.'),
            clienttranslate('With 2 or 3 players, only one track of each double or triple route may be built or claimed. With 4 or 5 players, each track must be built separately. A player may build several parallel tracks on different turns, but cannot claim more than one.'),
            clienttranslate('Only built routes can be claimed. One-space routes and printed grey routes are already built. When a route is claimed, its Track Piece returns to the supply.'),
            clienttranslate('Routes leading to neighboring countries or Corsica are separate dead ends. Routes leading to the same zone do not connect to each other.'),
            clienttranslate('Ferries require a Locomotive card for each Locomotive symbol on the route, plus matching cards for the remaining spaces.'),
            clienttranslate('The Longest Continuous Path bonus is worth 10 points and the Globetrotter bonus for the most completed tickets is worth 15 points. All players tied for either bonus receive its points.'),
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

    function getDestinationToGenerate(int $expansionValue): array {
        $destinations = [];
        foreach (getBaseDestinations() as $typeArg => $destination) {
            $destinations[] = ['type' => 1, 'type_arg' => $typeArg, 'nbr' => 1];
        }

        return ['deck' => $destinations];
    }

    function setup(Game $game): void {
        $remainingTrackPieces = [];
        foreach ([PINK, WHITE, BLUE, YELLOW, ORANGE, BLACK, RED, GREEN] as $color) {
            $remainingTrackPieces[$color] = [2 => 3, 3 => 3, 4 => 1, 5 => 1];
        }
        $game->bga->globals->set('REMAINING_TRACK_PIECES', $remainingTrackPieces);
        $game->bga->globals->set('PLACED_TRACK_PIECES', []);
    }

    function getMapSpecificData(Game $game): array {
        return [
            'remainingTrackPieces' => $game->bga->globals->get('REMAINING_TRACK_PIECES', []),
            'placedTrackPieces' => $game->bga->globals->get('PLACED_TRACK_PIECES', []),
        ];
    }

    function getPreloadImages(int $expansionValue): array {
        return array_merge(parent::getPreloadImages($expansionValue), [
            'track-pieces-1.webp',
            'track-pieces-2.webp',
            'track-pieces-3.webp',
            'track-pieces-4.webp',
            'track-pieces-5.webp',
        ]);
    }
}

function getMap() {
    return new FranceMap();
}
