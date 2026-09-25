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
                5 => 10,
                6 => 15,
                7 => 18,
            ]
        );

        $this->trainCarsPerPlayer = 45; // trains car tokens per player at the beginning of the game
        $this->unusedInitialDestinationsGoToDeckBottom = true; // Indicates if unpicked destinations cards go back to the bottom of the deck.
        $this->unusedAdditionalDestinationsGoToDeckBottom = true; // Indicates if unpicked destinations cards go back to the bottom of the deck.
        $this->pointsForLongestPath = null; // points for maximum longest continuous path (null means disabled)
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
            clienttranslate('Game start: Deal 5 tickets and keep at least 3.'),
            clienttranslate('A player can draw a Ferry card as a turn action. They cannot have more than 2 Ferry cards in hand.'),
            clienttranslate('To claim a space with a Wave Symbol, player must use a Locomotive card or a Ferry card. A Ferry card can be used for up to 2 spaces with a Wave Symbol whereas Locomotive cards are only ever worth one space each. Ferry Cards cannot be used on regular Routes or on Ferry Route spaces without a Wave Symbol.'),
            clienttranslate('Regions bonus: player count the number of Regions that they connected together and scores points according to the chart. If a player has two (or more) distinct networks, these networks are scored separately. The 3 special Regions (Sardegna, Sicilia and Puglia) count as 2 Regions instead of one toward the bonus if all their cities are part of the same network.'),
            clienttranslate('There is no longest path bonus and no Globetrotter bonus.'),
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
    
    function getPlayerMapSpecificData(Game $game, int $playerId): array {
        return [
            'regionsCount' => $this->getRegionsCount($game, $playerId),
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
