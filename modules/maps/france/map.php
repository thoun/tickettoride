<?php

use Bga\Games\TicketToRideMaps\Objects\Map;

require_once(__DIR__.'/cities.php');
require_once(__DIR__.'/routes.php');
require_once(__DIR__.'/destinations.php');

/**
 * Rules reference: 06a-France-EN-2019-2.pdf, printed pages 2-3.
 * *
 * TODO: Add a shared supply of the 64 Track Pieces, sorted by length. Track
 * Piece quantities by length and color must be checked against the components;
 * the rules booklet does not give their distribution.
 *
 * TODO: After drawing Train Car cards under the normal rules (including taking
 * a face-up Locomotive), require the player to build one route by placing an
 * available Track Piece on an unbuilt Track Bed of the same length. Its color
 * determines the route's payment color. Building does not claim the route:
 * any player may claim it on a later turn.
 *
 * TODO: Record crossing routes and forbid placing a Track Piece over or under
 * another Track Piece or through a claimed route. Building a crossing route
 * permanently blocks the crossed routes, even after its Track Piece is returned
 * to the supply (for example, Marseille-Grenoble blocks Avignon-Briançon and
 * Avignon-Nice).
 *
 * TODO: Apply parallel-route limits when building, as well as when claiming.
 * With 2 or 3 players, only one track of a double or triple route may be built.
 * With 4 or 5 players, build each track separately, one per card-drawing turn;
 * the same player may build several tracks on different turns, but may still
 * claim only one track between the same cities.
 *
 * TODO: Prevent claiming TRACKBED routes until they have been built, and use
 * the placed Track Piece's color for payment. One-space colored routes and
 * printed grey routes (including ferries) are already built and can be claimed
 * immediately. Return a built route's Track Piece to the shared supply when
 * anyone claims it, retaining its crossing restrictions.
 */
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
            /* TODO clienttranslate('France is designed for 2 to 5 players. Each player starts with 40 trains and 8 Train Car cards.'),
            clienttranslate('Deal 5 Destination Tickets at the start and keep at least 3. Shuffle all rejected initial tickets together and place them under the deck. Later, draw 4 tickets and keep at least 1; return unchosen tickets to the bottom of the deck.'),
            clienttranslate('After drawing Train Car cards, you must build one route: place an available Track Piece on a Track Bed of the same length. The Track Piece determines the route color. Any player may later claim the built route.'),
            clienttranslate('A Track Piece cannot cross another Track Piece or a claimed route. Building a crossing route permanently cuts off the crossed routes, even after the built route is claimed.'),
            clienttranslate('With 2 or 3 players, only one track of each double or triple route may be built or claimed. With 4 or 5 players, each track must be built separately. A player may build several parallel tracks on different turns, but cannot claim more than one.'),
            clienttranslate('Only built routes can be claimed. One-space routes and printed grey routes are already built. When a route is claimed, its Track Piece returns to the supply.'),
            clienttranslate('Routes leading to neighboring countries or Corsica are separate dead ends. Routes leading to the same zone do not connect to each other.'),
            clienttranslate('Ferries require a Locomotive card for each Locomotive symbol on the route, plus matching cards for the remaining spaces.'),
            clienttranslate('The Longest Continuous Path bonus is worth 10 points and the Globetrotter bonus for the most completed tickets is worth 15 points. All players tied for either bonus receive its points.'),
            */
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
}

function getMap() {
    return new FranceMap();
}
