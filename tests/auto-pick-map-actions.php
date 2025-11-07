<?php

namespace Bga\GameFramework {
    class Table {
        public object $bga;
        public object $notify;
        public function getPlayerNameById(int $id): string { return "Player $id"; }
    }
    class UserException extends \RuntimeException {}
    class StateType { const ACTIVE_PLAYER = 1; }
}
namespace Bga\GameFramework\Components\Counters {
    class PlayerCounter {
        public int $notifications = 0;
        public function __construct(public array $values) {}
        public function getMax(): int { return max($this->values); }
        public function setAll(int $value): void {
            $this->values = array_fill_keys(array_keys($this->values), $value);
            $this->notifications++;
        }
    }
}
namespace Bga\GameFramework\States {
    class GameState {
        public object $bga;
        public object $notify;
        public function __construct(object $game, mixed ...$options) {
            $this->bga = $game->bga;
            $this->notify = $game->notify;
        }
    }
}
namespace {
    function clienttranslate(string $text): string { return $text; }
    require_once __DIR__.'/../modules/php/DebugUtilTrait.php';
    require_once __DIR__.'/../modules/php/Game.php';
    require_once __DIR__.'/../modules/php/Objects/Map.php';
    require_once __DIR__.'/../modules/php/States/PlaceTrackPiece.php';

    use Bga\Games\TicketToRideMaps\Game;
    use Bga\Games\TicketToRideMaps\Objects\Map;
    use Bga\Games\TicketToRideMaps\States\PlaceTrackPiece;
    use Bga\GameFramework\Components\Counters\PlayerCounter;

    function check(bool $condition, string $message): void {
        if (!$condition) { throw new \RuntimeException($message); }
    }
    class TestTrackPiece extends PlaceTrackPiece {
        public function getArgs(): array {
            return ['possibleRouteIds' => [7], 'remainingTrackPieces' => [RED => [2 => 1]]];
        }
    }
    $game = (new \ReflectionClass(Game::class))->newInstanceWithoutConstructor();
    $game->autoPickTrainCarCards = new PlayerCounter([42 => 20, 43 => 0, 44 => 30]);
    $game->resetAutoPick();
    check($game->autoPickTrainCarCards->values === [42 => 0, 43 => 0, 44 => 0], 'Reset must disable every player');
    check($game->autoPickTrainCarCards->notifications === 1, 'Reset must send one shared notification');
    $game->resetAutoPick();
    check($game->autoPickTrainCarCards->notifications === 1, 'Already disabled counters must not send another notification');

    $game->autoPickTrainCarCards = new PlayerCounter([42 => 20, 43 => 30]);
    $game->bga = (object)['globals' => new class {
        public array $values = [];
        public function get(string $key, mixed $default = null): mixed { return $this->values[$key] ?? $default; }
        public function set(string $key, mixed $value): void { $this->values[$key] = $value; }
    }];
    $game->notify = new class {
        public function all(string $name, string $message, array $args): void {}
    };
    $game->map = new Map(
        [1 => (object)['name' => 'A'], 2 => (object)['name' => 'B']],
        [7 => (object)['id' => 7, 'from' => 1, 'to' => 2, 'number' => 2, 'color' => TRACKBED]],
        [],
    );
    $game->mapManager = new \Bga\Games\TicketToRideMaps\MapManager($game);
    $state = new TestTrackPiece($game);
    foreach ([[8, RED], [7, BLUE]] as [$routeId, $color]) {
        try {
            $state->actPlaceTrackPiece($routeId, $color, 42);
            throw new \RuntimeException('Invalid Track Piece placement accepted');
        } catch (\Bga\GameFramework\UserException) {}
        check($game->autoPickTrainCarCards->notifications === 0, 'Rejected placements must preserve auto-pick');
    }
    $state->actPlaceTrackPiece(7, RED, 42);
    check($game->bga->globals->values['PLACED_TRACK_PIECES'] === [7 => RED], 'Track Piece was not placed');
    check($game->autoPickTrainCarCards->values === [42 => 0, 43 => 0], 'Track Piece placement must disable everyone');
    check($game->autoPickTrainCarCards->notifications === 1, 'Track Piece placement must notify the reset');
    echo "Auto-pick map actions passed.\n";
}
