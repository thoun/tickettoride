<?php

use Bga\Games\TicketToRide\Game;
use Bga\Games\TicketToRide\Objects\Map;
use Bga\Games\TicketToRide\Objects\Route;

require_once(__DIR__.'/cities.php');
require_once(__DIR__.'/routes.php');
require_once(__DIR__.'/destinations.php');

/*
 * TODOMAPS 
 */
class LegendaryAsiaMap extends Map {
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
             * List of DestinationCard decks.
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

        $this->trainCarsPerPlayer = 45;
        $this->unusedInitialDestinationsGoToDeckBottom = false;
        $this->pointsForLongestPath = null;
        $this->pointsForGlobetrotter = null;
        $this->pointsForMostConnectedCities = 10; // TODOMAPS implement and show on the front side
        $this->minimumPlayerForDoubleRoutes = 4;

        $this->rulesDifferences = [
            /*TODOMAPS
            clienttranslate('Legendary Asia is for 2 to 5 players. Double routes are only available in 4- and 5-player games.'),
            clienttranslate('At the start of the game, each player receives 1 Long Route ticket and 3 regular tickets, and must keep at least 2 tickets. All unchosen initial tickets are discarded.'),
            clienttranslate('Ferries require a Locomotive card for each Locomotive symbol on the route.'),
            clienttranslate('A gray route next to a colored double route may be claimed with cards of any one color.'),
            clienttranslate('Mountain routes require discarding 1 Train Car for each X. Each discarded Train Car scores 2 points, and you must have enough Train Cars to place and discard.'),
            clienttranslate('The 10-point Asian Explorer bonus goes to the player or players with the largest continuous network of connected cities. Ties are broken by completed Destination Tickets, then by Mountain routes traveled.'),
            */
        ];
    }

    /**
     * Return the number of destination cards shown at the beginning, for each deck.
     */
    function getInitialDestinationPick(int $expansionValue): array {
        return [
            'deckbig' => 1,
            'deck' => 3,
        ];
    }

    function getPreloadImages(int $expansionValue): array {
        return ['destinations-1-0.jpg', 'destinations-2-0.jpg'];
    }

    function isLongestPathBonusActive(int $expansionValue): bool {
        return false;
    }

    /**
     * List the destination tickets that will be used for the game.
     */
    function getDestinationToGenerate(int $expansionValue): array {
        $smallDestinations = [];
        foreach (getBaseSmallDestinations() as $typeArg => $destination) {
            $smallDestinations[] = [ 'type' => 1, 'type_arg' => $typeArg, 'nbr' => 1 ];
        }

        $bigDestinations = [];
        foreach (getBaseBigDestinations() as $typeArg => $destination) {
            $bigDestinations[] = [ 'type' => 2, 'type_arg' => $typeArg, 'nbr' => 1 ];
        }

        return [
            'deck' => $smallDestinations,
            'deckbig' => $bigDestinations,
        ];
    }

    function setup(Game $game): void {
        foreach ($game->getPlayersIds() as $playerId) {
            $game->bga->globals->set("MOUNTAIN_TRAINS_{$playerId}", 0);
        }
    }

    function getPlayerMapSpecificData(Game $game, int $playerId): array {
        return [
            'mountainTrains' => $game->bga->globals->get("MOUNTAIN_TRAINS_{$playerId}", 0),
        ];
    }
    
    function onClaimRoute(Game $game, int $playerId, Route $route): void {
        if ($route->mountain <= 0) {
            return;
        }
        $game->removeTrainCars($playerId, $route->mountain);
        $playerMountainCars = $game->bga->globals->inc("MOUNTAIN_TRAINS_{$playerId}", $route->mountain);
        $points = $route->mountain * 2;

        $game->bga->notify->all('addMountainTrains', /*TODOMAPS clienttranslate*/('${player_name} discards ${number} train cars when claiming mountain route from ${from} to ${to} and gains ${points} points'), [
            'playerId' => $playerId,
            'player_name' => $game->getPlayerNameById($playerId),
            'points' => $points,
            'route' => $route,
            'from' => $game->getCityName($route->from),
            'to' => $game->getCityName($route->to),
            'number' => $route->mountain,
            'mountainCars' => $playerMountainCars,
            'remainingTrainCars' => $game->getRemainingTrainCarsCount($playerId),
        ]);
        $game->incScore($playerId, $points);
    }
}

function getMap() {
    return new LegendaryAsiaMap();
}

?>
