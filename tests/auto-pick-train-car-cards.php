<?php

namespace Bga\GameFramework\States {
    class GameState {
        public function __construct(object $game, mixed ...$options) {}
    }
}
namespace Bga\GameFramework {
    class StateType { const ACTIVE_PLAYER = 1; }
}
namespace Bga\Games\TicketToRide {
    class Game {
        public object $autoPickTrainCarCards;
        public object $trainCarManager;
        public object $legendaryCharacterManager;
        public array $stats = [];
        public function __construct(int $target, int $hand, int $deck, public int $maxDraw = 2, public bool $trackPieces = false) {
            $this->autoPickTrainCarCards = new class($target) {
                public function __construct(public int $value) {}
                public function get(int $id): int { return $this->value; }
                public function set(int $id, int $value): void { $this->value = $value; }
            };
            $this->trainCarManager = new class($hand, $deck) {
                public array $draws = [];
                public function __construct(public int $hand, public int $deck) {}
                public function getPlayerHandCount(int $id): int { return $this->hand; }
                public function getRemainingTrainCarCardsInDeck(bool $includeDiscard): int { return $this->deck; }
                public function drawTrainCarCardsFromDeck(int $id, int $number): int {
                    if ($number > $this->deck) { throw new \RuntimeException('Deck overdraw'); }
                    $this->draws[] = $number;
                    $this->hand += $number;
                    $this->deck -= $number;
                    return $number;
                }
            };
            $this->legendaryCharacterManager = new class {
                public function getCharacter4UsingRouteIds(int $id): array { return []; }
            };
        }
        public function getMap(): object {
            return (object)['useTechnologyCards' => false, 'useTrackBedPieces' => $this->trackPieces];
        }
        public function incStat(int $number, string $name, ?int $id = null): void {
            $key = $name.':'.($id ?? 'table');
            $this->stats[$key] = ($this->stats[$key] ?? 0) + $number;
        }
    }
}
namespace {
    require_once __DIR__.'/../modules/php/constants.inc.php';
    require_once __DIR__.'/../modules/php/States/ChooseAction.php';

    class AutoPickChooseAction extends \Bga\Games\TicketToRide\States\ChooseAction {
        public function getArgs(int $activePlayerId): array {
            return ['maxHiddenCardsPick' => min($this->game->maxDraw, $this->game->trainCarManager->deck)];
        }
    }

    foreach ([
        // target, hand, deck, legal draw limit, track pieces, expected draws, remaining target
        [0, 4, 20, 2, false, [], 0],
        [8, 4, 20, 2, false, [2], 8],
        [5, 4, 20, 2, false, [2], 0],
        [4, 4, 20, 2, false, [], 0],
        [8, 4, 1, 2, false, [], 0],
        [8, 4, 0, 2, false, [], 0],
        [8, 4, 3, 2, false, [2], 0],
        [8, 4, 20, 0, false, [], 0],
        [8, 4, 20, 3, false, [3], 8],
        [8, 4, 20, 2, true, [2], 8],
    ] as [$target, $hand, $deck, $maximum, $trackPieces, $draws, $remainingTarget]) {
        $game = new \Bga\Games\TicketToRide\Game($target, $hand, $deck, $maximum, $trackPieces);
        $next = (new AutoPickChooseAction($game))->onEnteringState(42);
        $expectedNext = !$draws ? null : ($trackPieces
            ? \Bga\Games\TicketToRide\States\PlaceTrackPiece::class
            : \Bga\Games\TicketToRide\States\NextPlayer::class);
        if ($game->trainCarManager->draws !== $draws || $game->autoPickTrainCarCards->value !== $remainingTarget || $next !== $expectedNext) {
            throw new \RuntimeException('Unexpected auto-pick result for '.json_encode([$target, $hand, $deck, $maximum, $trackPieces]));
        }
        if ($draws && ($game->stats['collectedHiddenTrainCarCards:42'] ?? 0) !== array_sum($draws)) {
            throw new \RuntimeException('Auto-pick must update normal draw statistics.');
        }
    }
    echo "Auto-pick train car cards passed.\n";
}
