<?php

namespace Bga\Games\TicketToRideMaps {
    class Game {
        public array $claims = [];
        public function __construct(private object $map, private int $playerCount) {}
        public function getMap(): object { return $this->map; }
        public function getPlayerCount(): int { return $this->playerCount; }
        public function getClaimedRoutes(?int $playerId = null): array { return $this->claims; }
    }
}
namespace {
    require_once __DIR__.'/../modules/php/constants.inc.php';
    require_once __DIR__.'/../modules/php/framework-prototype/Helpers/Arrays.php';
    require_once __DIR__.'/../modules/php/Objects/Map.php';
    require_once __DIR__.'/../modules/php/Objects/Route.php';
    require_once __DIR__.'/../modules/php/Objects/RouteSpace.php';
    require_once __DIR__.'/../modules/php/MapManager.php';

    $route = fn($from, $to) => new \Bga\Games\TicketToRideMaps\Objects\Route($from, $to, RED,
        [new \Bga\Games\TicketToRideMaps\Objects\RouteSpace(0, 0, 0)]);
    $claim = fn($id, $owner) => (object)['routeId' => $id, 'playerId' => $owner];
    $hand = [(object)['id' => 1, 'type' => RED]];
    foreach ([[2 => 1, 3 => 1, 4 => 2, 5 => 2], [2 => 1, 3 => 2, 4 => 2, 5 => 2]] as $doubleLimits) {
      foreach ([[2 => 1, 3 => 1, 4 => 3, 5 => 3], [2 => 1, 3 => 2, 4 => 3, 5 => 3]] as $limits) {
        foreach ($limits as $players => $maximum) {
            $map = new \Bga\Games\TicketToRideMaps\Objects\Map([], [
                1 => $route(1, 2), 2 => $route(1, 2), 3 => $route(1, 2),
                4 => $route(3, 4), 5 => $route(3, 4), 6 => $route(5, 6),
            ], []);
            $map->maximumPlayerForTripleRoutes = $limits;
            $map->maximumPlayerForDoubleRoutes = $doubleLimits;
            $game = new \Bga\Games\TicketToRideMaps\Game($map, $players);
            $manager = new \Bga\Games\TicketToRideMaps\MapManager($game);
            $available = fn($playerId = 99) => array_map(fn($route) => $route->id, $manager->claimableRoutes($playerId, $hand, 40));
            for ($taken = 0; $taken <= 3; $taken++) {
                $game->claims = [];
                for ($id = 1; $id <= $taken; $id++) { $game->claims[] = $claim($id, $id); }
                $actual = array_values(array_intersect($available(), [1, 2, 3]));
                $expected = $taken < $maximum ? range($taken + 1, 3) : [];
                if ($actual !== $expected) { throw new \RuntimeException("Triple limit failed for $players players after $taken claims"); }
                if ($taken && array_intersect($available(1), [1, 2, 3])) {
                    throw new \RuntimeException('A player cannot own two parallel tracks');
                }
            }
            $game->claims = [$claim(1, 1), $claim(1, 2)];
            if (in_array(2, $available(), true) !== ($maximum > 1)) {
                throw new \RuntimeException('Multiple owners of a track must count as one claimed track');
            }
            $game->claims = [$claim(4, 1)];
            if (in_array(5, $available(), true) !== ($doubleLimits[$players] > 1) || in_array(5, $available(1), true)) {
                throw new \RuntimeException('Double-route restrictions changed');
            }
            if (!in_array(6, $available(), true)) { throw new \RuntimeException('Single route became unavailable'); }
        }
      }
    }
    echo "Parallel route limits passed.\n";
}
