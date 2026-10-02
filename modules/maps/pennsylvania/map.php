<?php

use Bga\Games\TicketToRide\Game;
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
        $this->countriesEndPoints = [
            -1 => [1001, 1002], // Ontario's two separate ferry endpoints
        ];
    
        $this->shareStockPoints = [
            2 => [7, 4],
            3 => [8, 5],
            4 => [9, 5],
            5 => [10, 6, 3],
            6 => [12, 7, 3],
            7 => [15, 9, 5],
            8 => [16, 10, 5, 1],
            10 => [20, 14, 9, 5, 2],
            15 => [30, 21, 14, 9, 6],
        ];
        $this->shareStockCompanyNames = [
            2 => 'Buffalo, Rochester & Pittsburgh Railway',
            3 => 'Jersey Central Lane',
            4 => 'Western Maryland Railway',
            5 => 'New York Central System',
            6 => 'Lehigh Valley Railroad',
            7 => 'Reading Lines',
            8 => 'Erie Lackwanna Railway',
            10 => 'Baltimore & Ohio Railroad',
            15 => 'Pennsylvania Railroad',
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

    function setup(Game $game): void {
        $remainingStockShareCards = [];
        foreach (array_keys($this->shareStockPoints) as $type) {
            $indexes = [];
            for ($i = 1; $i <= $type; $i++) {
                $indexes[] = $i;
            }
            $remainingStockShareCards[$type] = $indexes;
        }
        $playerIds = $game->getPlayersIds();
        $game->bga->globals->set("REMAINING_STOCK_SHARE_CARDS", $remainingStockShareCards);
        foreach ($playerIds as $playerId) {
            $game->bga->globals->set("STOCK_SHARE_CARDS_{$playerId}", array_fill_keys(array_keys($this->shareStockPoints), []));
        }
        if (count($playerIds) === 2) {
            $game->bga->globals->set("STOCK_SHARE_CARDS_DUMMY", array_fill_keys(array_keys($this->shareStockPoints), []));
        }
    }

    function getMapSpecificData(Game $game): array {
        return [
            'remainingStockShareCards' => $game->bga->globals->get("REMAINING_STOCK_SHARE_CARDS"),
            'stockShareCardsDummy' => $game->bga->globals->get("STOCK_SHARE_CARDS_DUMMY"),
            'shareStockPoints' => $this->shareStockPoints,
        ];
    }

    function getPlayerMapSpecificData(Game $game, int $playerId): array {
        return [
            'stockShareCards' => $game->bga->globals->get("STOCK_SHARE_CARDS_{$playerId}"),
        ];
    }

    /**
     * @param array<int, array<int, int[]>> $cardsByPlayer Share numbers, grouped by player and railroad type.
     * @param array<int, int[]> $revealedDummyCards Dummy shares revealed for a two-player game.
     * @return array<int, int> Points earned by each player.
     */
    public function getStockShareScores(array $cardsByPlayer, array $revealedDummyCards = []): array {
        $scores = array_fill_keys(array_keys($cardsByPlayer), 0);

        foreach ($this->getStockShareRanks($cardsByPlayer, $revealedDummyCards) as $result) {
            $scores[$result['playerId']] += $result['points'];
        }

        return $scores;
    }

    /**
     * @param array<int, array<int, int[]>> $cardsByPlayer
     * @param array<int, int[]> $revealedDummyCards
     * @return array<int, array{playerId: int, type: int, rank: int, points: int}>
     */
    public function getStockShareRanks(array $cardsByPlayer, array $revealedDummyCards = []): array {
        $results = [];

        foreach ($this->shareStockPoints as $type => $rankPoints) {
            $owners = [];
            foreach ($cardsByPlayer as $playerId => $cardsByType) {
                $cards = $cardsByType[$type] ?? [];
                if ($cards !== []) {
                    $owners[] = [
                        'playerId' => $playerId,
                        'count' => count($cards),
                        'firstShare' => min($cards),
                    ];
                }
            }

            $dummyCards = $revealedDummyCards[$type] ?? [];
            if ($dummyCards !== []) {
                $owners[] = [
                    'playerId' => null,
                    'count' => count($dummyCards),
                    'firstShare' => min($dummyCards),
                ];
            }

            usort($owners, fn($a, $b) =>
                ($b['count'] <=> $a['count']) ?: ($a['firstShare'] <=> $b['firstShare'])
            );

            foreach ($owners as $rank => $owner) {
                if ($owner['playerId'] !== null) {
                    $results[] = [
                        'playerId' => $owner['playerId'],
                        'type' => $type,
                        'rank' => $rank + 1,
                        'points' => $rankPoints[$rank] ?? 0,
                    ];
                }
            }
        }

        return $results;
    }

    /** Reveal half of all dummy shares, rounded up, rather than half of each railroad. */
    public function revealDummyStockShareCards(array $dummyCards): array {
        $cards = [];
        foreach ($dummyCards as $type => $numbers) {
            foreach ($numbers as $number) {
                $cards[] = ['type' => $type, 'number' => $number];
            }
        }

        shuffle($cards);
        $revealed = array_fill_keys(array_keys($this->shareStockPoints), []);
        foreach (array_slice($cards, 0, intdiv(count($cards) + 1, 2)) as $card) {
            $revealed[$card['type']][] = $card['number'];
        }

        return $revealed;
    }
}

function getMap() {
    return new PennsylvaniaMap();
}
