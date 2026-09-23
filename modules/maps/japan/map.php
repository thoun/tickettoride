<?php

use Bga\Games\TicketToRide\Game;
use Bga\Games\TicketToRide\Objects\Map;

require_once(__DIR__.'/cities.php');
require_once(__DIR__.'/routes.php');
require_once(__DIR__.'/destinations.php');

class JapanMap extends Map {
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

        $this->trainCarsPerPlayer = 20; // trains car tokens per player at the beginning of the game
        $this->unusedInitialDestinationsGoToDeckBottom = true; // Indicates if unpicked destinations cards go back to the bottom of the deck.
        $this->unusedAdditionalDestinationsGoToDeckBottom = true; // Indicates if unpicked destinations cards go back to the bottom of the deck.
        $this->pointsForLongestPath = null; // points for maximum longest countinuous path (null means disabled)
        $this->pointsForGlobetrotter = null; // points for maximum completed destinations (null means disabled)
        $this->minimumPlayerForDoubleRoutes = 4; // 4 means 2-3 players cant use double routes

        $this->multilingualPdfRulesUrl = 'https://cdn.svc.asmodee.net/production-daysofwonder/uploads/2024/07/720132-T2RMC7-Rules_Japan_en.pdf';
        $this->rulesDifferences = [
            // TODO
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

    function setup(Game $game): void {
        $game->bga->globals->set(REMAINING_BULLET_TRAINS, 16);

        foreach ($game->getPlayersIds() as $playerId) {
            $game->bga->globals->set("BULLET_TRAIN_POSITION_{$playerId}", 0);
        }
    }

    function getMapSpecificData(Game $game): array {
        return [
            'remainingBulletTrains' => $game->bga->globals->get(REMAINING_BULLET_TRAINS, 0),
        ];
    }

    function getPlayerMapSpecificData(Game $game, int $playerId): array {
        return [
            'bulletTrainPosition' => $game->bga->globals->get("BULLET_TRAIN_POSITION_{$playerId}", 0),
        ];
    }

    function isLastTurn(Game $game): bool {
        return $game->getLowestTrainCarsCount() <= 2 && $game->bga->globals->get(REMAINING_BULLET_TRAINS) <= 2; // 2 means 0, 1, or 2 will start last turn
    }
}

function getMap() {
    return new JapanMap();
}

?>
