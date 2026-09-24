<?php

use Bga\Games\TicketToRide\Game;
use Bga\Games\TicketToRide\Objects\Map;

require_once(__DIR__.'/cities.php');
require_once(__DIR__.'/routes.php');
require_once(__DIR__.'/destinations.php');

class ItalyMap extends Map {
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

        $this->trainCarsPerPlayer = 45; // trains car tokens per player at the beginning of the game
        $this->unusedInitialDestinationsGoToDeckBottom = true; // Indicates if unpicked destinations cards go back to the bottom of the deck.
        $this->unusedAdditionalDestinationsGoToDeckBottom = true; // Indicates if unpicked destinations cards go back to the bottom of the deck.
        $this->pointsForLongestPath = null; // points for maximum longest countinuous path (null means disabled)
        $this->pointsForGlobetrotter = null; // points for maximum completed destinations (null means disabled)
        $this->minimumPlayerForDoubleRoutes = 4; // 4 means 2-3 players cant use double routes
        $this->ferryCards = true;

        $this->regionBonusPoints = [
            5 => 1,
            6 => 2,
            7 => 4,
            8 => 7,
            9 => 11,
            10 => 16,
            11 => 22,
            12 => 29,
            13 => 37,
            14 => 46,
            15 => 56,
        ];
        $this->completeRegionsCountingDouble = [
            ITALY_REGION_SARDEGNA,
            ITALY_REGION_SICILIA,
            ITALY_REGION_PUGLIA,
        ];

        $this->countriesEndPoints = [
            -1 => [1001],
            -2 => [2001],
            -3 => [3001, 3002],
            -4 => [4001],
            -5 => [5001, 5002, 5003],
        ];

        $this->multilingualPdfRulesUrl = 'https://cdn.svc.asmodee.net/production-daysofwonder/uploads/2024/07/720132-T2RMC7-Rules_Italy_en.pdf';
        $this->rulesDifferences = [
            // TODO
        ];
    }
    
    /**
     * Return the number of destinations cards shown at the beginning, for each deck.
     */
    function getInitialDestinationPick(int $expansionValue): array {
        return ['deck' => 5];
    }

    /**
     * Return the minimum number of destinations cards to keep at the beginning.
     */
    function getInitialDestinationMinimumKept(int $expansionValue): int {
        return 3;
    }

    /**
     * Return the number of destinations cards shown at pick destination action.
     */
    function getAdditionalDestinationCardNumber(int $expansionValue): int {
        return 4;
    }

    function getPreloadImages(int $expansionValue): array {
        return ['destinations-1-0.jpg'];
    }
    
    function getPlayerMapSpecificData(\Bga\Games\TicketToRide\Game $game, int $playerId): array {
        return [
            'regionsBonus' => $this->getRegionsBonus($game, $playerId),
            'ferryCards' => $game->bga->globals->get("FERRY_CARD_{$playerId}", 0),
        ];
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
     * Return if Longest Path bonus card is used for the game.
     */
    function isLongestPathBonusActive(int $expansionValue): bool {
        return false;
    }

    function setup(Game $game): void {
        foreach ($game->getPlayersIds() as $playerId) {
            $game->bga->globals->set("FERRY_CARD_{$playerId}", 0);
        }
    }
}

function getMap() {
    return new ItalyMap();
}

?>
