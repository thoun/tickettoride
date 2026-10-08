<?php

namespace Bga\GameFramework\States {
    class GameState { public function __construct(object $game, mixed ...$options) {} }
}
namespace Bga\GameFramework {
    class StateType { const ACTIVE_PLAYER = 1; }
}
namespace Bga\Games\TicketToRide {
    class Game {
        public object $bga;
        public MapManager $mapManager;
        public object $trainCarManager;
        public object $destinationManager;
        public array $claims = [];
        public function __construct(private object $map) {
            $this->bga = (object)['globals' => new class {
                public array $pieces = [];
                public function get(string $key, mixed $default = null): mixed {
                    return $key === 'PLACED_TRACK_PIECES' ? $this->pieces : $default;
                }
            }];
            $this->mapManager = new MapManager($this);
            $this->trainCarManager = new class {
                public function getPlayerHand(int $playerId): array { return [(object)['id' => 1, 'type' => RED]]; }
            };
            $this->destinationManager = new class {
                public function getUncompletedDestinations(int $playerId): array {
                    return [(object)['from' => 1, 'to' => 3]];
                }
            };
        }
        public function getMap(): object { return $this->map; }
        public function getPlayerCount(): int { return 4; }
        public function getClaimedRoutes(): array { return $this->claims; }
        public function getRemainingTrainCarsCount(int $playerId): int { return 40; }
    }
}
namespace {
    function clienttranslate(string $text): string { return $text; }
    require_once __DIR__.'/../modules/php/constants.inc.php';
    require_once __DIR__.'/../modules/php/framework-prototype/Helpers/Arrays.php';
    require_once __DIR__.'/../modules/php/Objects/Map.php';
    require_once __DIR__.'/../modules/php/Objects/Route.php';
    require_once __DIR__.'/../modules/php/Objects/RouteSpace.php';
    require_once __DIR__.'/../modules/php/MapManager.php';
    require_once __DIR__.'/../modules/php/States/ChooseAction.php';

    use Bga\Games\TicketToRide\Game;
    use Bga\Games\TicketToRide\Objects\Map;
    use Bga\Games\TicketToRide\Objects\Route;
    use Bga\Games\TicketToRide\Objects\RouteSpace;
    use Bga\Games\TicketToRide\States\ChooseAction;

    class ZombieChooseAction extends ChooseAction {
        public ?array $claim = null;
        public function actClaimRoute(int $routeId, int $color, ?array $distribution, int $ferryCards, int $activePlayerId) {
            $this->claim = [$routeId, $color];
            return 'claimed';
        }
    }
    function check(bool $valid, string $message): void {
        if (!$valid) { throw new \RuntimeException($message); }
    }
    $route = fn($from, $to) => new Route($from, $to, TRACKBED, [new RouteSpace(0, 0, 0)]);
    $map = new Map([], [1 => $route(1, 3), 2 => $route(1, 2), 3 => $route(2, 3)], []);
    $map->useTrackBedPieces = true;
    $game = new Game($map);
    $state = new ZombieChooseAction($game);
    $helpful = new \ReflectionMethod(ChooseAction::class, 'tryClaimHelpfulRouteForDestination');

    check($helpful->invoke($state, 1) === null && $state->claim === null, 'Empty trackbeds must not be claimed');
    $game->bga->globals->pieces = [2 => RED, 3 => RED];
    check($helpful->invoke($state, 1) === 'claimed' && $state->claim === [2, RED], 'Built route should be chosen despite a shorter empty trackbed');
    check($map->routes[2]->color === TRACKBED, 'Placed colors must not mutate the base map');

    $game->claims = [(object)['routeId' => 2, 'playerId' => 1]];
    $game->bga->globals->pieces = [3 => RED];
    check($helpful->invoke($state, 1) === 'claimed' && $state->claim === [3, RED], 'Claimed routes must remain connected after returning their Track Piece');

    $game->claims = [];
    $game->bga->globals->pieces = [2 => BLUE, 3 => BLUE];
    check($helpful->invoke($state, 1) === null, 'Zombie must pay the placed Track Piece color');

    $claimColor = new \ReflectionMethod(ChooseAction::class, 'getZombieClaimColor');
    check($claimColor->invoke($state, $map->routes[1], $game->trainCarManager->getPlayerHand(1), 40, 1) === null, 'Zombie claim color must reject trackbeds');
    echo "France zombie route selection passed.\n";
}
