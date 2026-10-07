<?php

namespace Bga\GameFramework\States {
    class GameState {
        protected object $notify;
        protected object $bga;
        public function __construct(object $game, mixed ...$options) {
            $this->notify = $game->notify;
            $this->bga = $game->bga;
        }
    }
}
namespace Bga\GameFramework {
    class StateType { const GAME = 1; }
    class NotificationMessage {
        public function __construct(public string $message = '', public array $args = []) {}
    }
}
namespace Bga\Games\TicketToRide {
    class Game {
        public object $bga;
        public object $notify;
        public object $destinationManager;
        public object $buildingManager;
        public object $legendaryCharacterManager;
        public object $mapManager;
        public object $trainCarManager;
        public array $scores = [1 => 100, 2 => 100];
        public function __construct(private object $map) {
            $this->notify = new class {
                public array $events = [];
                public function all(string $type, string $message, array $args): void {
                    $this->events[] = compact('type', 'message', 'args');
                }
            };
            $this->bga = (object)[
                'globals' => new class {
                    public array $selectedBonusCards = [0, 2, 4, 9];
                    public function get(string $key, mixed $default = null): mixed {
                        return $key === 'SELECTED_BONUS_CARDS' ? $this->selectedBonusCards : $default;
                    }
                },
                'playerScoreAux' => new class {
                    public array $values = [];
                    public function set(int $playerId, int $value, mixed $message): void { $this->values[$playerId] = $value; }
                },
                'playerStats' => new class {
                    public function set(string $name, int $value, int $playerId): void {}
                },
            ];
            $this->destinationManager = new class {
                public function getPlayerHand(int $playerId): array { return []; }
            };
            $this->buildingManager = new class {
                public function getPlacedStations(int $playerId): array { return []; }
            };
            $this->legendaryCharacterManager = new class {
                public function isActive(): bool { return false; }
            };
            $this->mapManager = new class($map) {
                public function __construct(private object $map) {}
                public function getLongestPath(int $playerId): object {
                    return (object)['length' => $playerId === 1 ? 7 : 11, 'routes' => []];
                }
                public function getAllRoutes(): array { return $this->map->routes; }
            };
            $this->trainCarManager = new class {
                public function getPlayerHand(int $playerId): array {
                    return array_map(fn($type) => (object)['type' => $type], $playerId === 1 ? [0, 3, 3] : [0]);
                }
            };
        }
        public function getMap(): object { return $this->map; }
        public function getPlayerNameById(int $playerId): string { return 'Player '.$playerId; }
        public function getExpansionOption(): int { return 0; }
        public function getCollectionFromDb(string $sql): array {
            return array_map(fn($score) => ['score' => $score], $this->scores);
        }
        public function getClaimedRoutes(int $playerId): array {
            // Shared claims must not count towards individual bonus cards.
            $claims = [(object)['routeId' => 5, 'playerId' => -1]];
            if ($playerId === 1) { $claims[] = (object)['routeId' => 3, 'playerId' => 1]; }
            return $claims;
        }
        public function getRemainingTrainCarsCount(int $playerId): int { return 12; }
        public function getStat(string $name, ?int $playerId = null): int { return 0; }
        public function setStat(mixed ...$args): void {}
        public function incScore(int $playerId, int $delta, ?string $message = null, array $args = []): void {
            $this->scores[$playerId] += $delta;
            $this->notify->all('points', $message ?? '', [
                'playerId' => $playerId, 'player_name' => 'Player '.$playerId,
                'points' => $this->scores[$playerId], 'delta' => $delta,
            ] + $args);
        }
    }
}
namespace {
    function clienttranslate(string $text): string { return $text; }
    require_once __DIR__.'/../modules/php/constants.inc.php';
    require_once __DIR__.'/../modules/php/framework-prototype/Helpers/Arrays.php';
    require_once __DIR__.'/../modules/php/Objects/City.php';
    require_once __DIR__.'/../modules/php/Objects/RouteSpace.php';
    require_once __DIR__.'/../modules/php/Objects/Route.php';
    require_once __DIR__.'/../modules/php/Objects/DestinationCard.php';
    require_once __DIR__.'/../modules/php/Objects/Map.php';
    require_once __DIR__.'/../modules/maps/northernlights/map.php';
    require_once __DIR__.'/../modules/php/States/EndScore.php';

    function check(bool $condition, string $message): void {
        if (!$condition) { throw new \RuntimeException($message); }
    }
    $map = new \NorthernLightsMap();
    $game = new \Bga\Games\TicketToRide\Game($map);
    $scoreBonus = function(array $selectedCards, array $players) use ($map, $game): array {
        $game->bga->globals->selectedBonusCards = $selectedCards;
        return $map->getBonusCardScores($game, $players);
    };
    $base = ['hand' => [], 'routes' => [], 'completedDestinations' => [], 'remainingTrains' => 0, 'longestPathLength' => 0];
    $ticket = fn($from, $to, $points) => new \Bga\Games\TicketToRide\Objects\DestinationCard($from, $to, $points);
    $route = function($from, $to, $length, $ferry = false) {
        return new \Bga\Games\TicketToRide\Objects\Route($from, $to, GRAY,
            array_fill(0, $length, new \Bga\Games\TicketToRide\Objects\RouteSpace(0, 0, 0)), locomotives: $ferry ? 1 : 0);
    };
    $examples = [
        0 => ['hand' => [(object)['type' => 0], (object)['type' => 3], (object)['type' => 3]]],
        1 => ['completedDestinations' => [$ticket(37, 19, 5)]],
        2 => ['remainingTrains' => 1],
        3 => ['routes' => [$route(1, 3, 1)]],
        4 => ['longestPathLength' => 1],
        5 => ['completedDestinations' => [$ticket(1, 3, 5)]],
        6 => ['routes' => [$route(1, 8, 1, true)]],
        7 => ['completedDestinations' => [$ticket(29, 4, 7)]],
        8 => ['routes' => [$route(29, 4, 3)]],
        9 => ['routes' => [$route(1, 8, 1, true)]],
        10 => ['routes' => [$route(5, 31, 3)]],
    ];
    $points = [5, 7, 7, 10, 10, 10, 12, 12, 12, 12, 7];
    foreach ($examples as $type => $example) {
        $results = $scoreBonus([$type], [1 => array_replace($base, $example), 2 => $base]);
        check(count($results) === 1 && $results[0]['playerId'] === 1 && $results[0]['points'] === $points[$type]
            && $results[0]['message'] instanceof \Bga\GameFramework\NotificationMessage
            && $results[0]['message']->args['number'] > 0
            && $results[0]['message']->args['bonusCardType'] === $type, "Incorrect scoring for bonus $type");
        $tied = $scoreBonus([$type], [1 => array_replace($base, $example), 2 => array_replace($base, $example)]);
        check(array_column($tied, 'points', 'playerId') === [1 => $points[$type], 2 => $points[$type]], "Tie must award full points for bonus $type");
    }
    // Odd colored sets count as floor(n/2); Locomotives count individually.
    $hand = fn($types) => array_map(fn($type) => (object)['type' => $type], $types);
    $results = $scoreBonus([0], [
        1 => array_replace($base, ['hand' => $hand([0, 0, 3, 3, 3, 4])]),
        2 => array_replace($base, ['hand' => $hand([1, 1, 1, 1, 2, 2])]),
    ]);
    check(array_column($results, 'points', 'playerId') === [1 => 5, 2 => 5]
        && $results[0]['message']->args['number'] === 3, 'Locomotive equivalents must count correctly');
    // Count a ticket/route once even when both ends qualify. Boden and Tornio are south of the line.
    foreach ([1, 7, 8, 10] as $type) {
        $oneEnd = [1 => ['completedDestinations' => [$ticket(37, 7, 8)]],
            7 => ['completedDestinations' => [$ticket(29, 6, 8)]],
            8 => ['routes' => [$route(29, 6, 3)]], 10 => ['routes' => [$route(5, 8, 3)]]][$type];
        $results = $scoreBonus([$type], [1 => array_replace($base, $examples[$type]), 2 => array_replace($base, $oneEnd)]);
        check(array_column($results, 'points', 'playerId') === [1 => $points[$type], 2 => $points[$type]], "Both qualifying endpoints counted twice for $type");
    }
    $results = $scoreBonus([7, 8], [
        1 => array_replace($base, ['completedDestinations' => [$ticket(6, 41, 5)], 'routes' => [$route(6, 41, 1)]]),
        2 => array_replace($base, ['completedDestinations' => [$ticket(35, 41, 5)], 'routes' => [$route(35, 41, 1)]]),
    ]);
    check(array_column($results, 'points', 'playerId') === [2 => 12] && count($results) === 2, 'Arctic boundary classification is incorrect');
    check($scoreBonus([], [1 => $base]) === [], 'Unselected cards must not score');

    $game = new \Bga\Games\TicketToRide\Game($map);
    $state = new \Bga\Games\TicketToRide\States\EndScore($game);
    check($state->onEnteringState() === ST_END_GAME, 'End scoring failed to finish');
    check($game->scores === [1 => 124, 2 => 117], 'Selected bonuses not added exactly once');
    check($game->notify->events[0]['type'] === 'bestScore' && $game->notify->events[0]['args']['bestScore'] === 124, 'Best score must include bonuses before score notifications');
    $events = array_values(array_filter($game->notify->events, fn($event) => $event['type'] === 'points'));
    check(count($events) === 5, 'Only players awarded a selected bonus must be notified');
    foreach ($events as $event) {
        check(str_contains($event['message'], '${number}')
            && isset($event['args']['bonus_name'], $event['args']['player_name'])
            && $event['args']['i18n'] === ['bonus_name']
            && $event['args']['delta'] > 0
            && $event['args']['number'] > 0, 'Wrong bonus notification');
    }
    $ferryEvents = array_values(array_filter($events, fn($event) => $event['args']['bonusCardType'] === 9));
    check(count($ferryEvents) === 1 && $ferryEvents[0]['args']['number'] === 1
        && $ferryEvents[0]['message'] === '${player_name} gains ${delta} points with ${bonus_name} bonus card by claiming ${number} ferry routes', 'Ferry Master notification must include claimed ferry routes');
    check($game->bga->playerScoreAux->values === [1 => 3, 2 => 2], 'Tie-breaker must count awarded cards instead of path length');
    $quantitiesByType = [0 => [1 => 2, 2 => 1], 2 => [1 => 12, 2 => 12], 4 => [1 => 7, 2 => 11], 9 => [1 => 1, 2 => 0]];
    $tables = array_values(array_filter($game->notify->events, fn($event) => $event['type'] === 'log'));
    check(count($tables) === 4, 'Each selected bonus card must have exactly one quantity table');
    foreach ($game->notify->events as $index => $event) {
        if ($event['type'] !== 'log') { continue; }
        $type = $event['args']['bonusCardType'];
        check($event['args']['quantities'] === $quantitiesByType[$type], 'Table must include every player and their quantity');
        foreach ($quantitiesByType[$type] as $playerId => $quantity) {
            check(str_contains($event['message'], "<tr><td>Player {$playerId}</td><td>{$quantity}</td></tr>"), 'Missing player quantity table row');
        }
        $winnerCount = $type === 2 ? 2 : 1;
        for ($offset = 1; $offset <= $winnerCount; $offset++) {
            $previous = $game->notify->events[$index - $offset];
            check($previous['type'] === 'points' && $previous['args']['bonusCardType'] === $type, 'Table must immediately follow all winners of its card');
        }
    }
    $map->useBonusCards = false;
    $game = new \Bga\Games\TicketToRide\Game($map);
    (new \Bga\Games\TicketToRide\States\EndScore($game))->onEnteringState();
    check($game->scores === [1 => 100, 2 => 100] && $game->bga->playerScoreAux->values === [1 => 7, 2 => 11], 'Disabled bonus cards changed ordinary scoring');
    echo "Northern Lights bonus scoring passed.\n";
}
