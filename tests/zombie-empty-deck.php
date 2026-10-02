<?php

namespace Bga\GameFramework\States {
    class GameState { public function __construct(object $game, mixed ...$options) {} }
}
namespace Bga\GameFramework {
    class StateType { const ACTIVE_PLAYER = 1; }
}
namespace Bga\Games\TicketToRide {
    class Game {
        public object $trainCarManager;
        public object $legendaryCharacterManager;
        public object $mapManager;
        public object $destinationManager;
        public function __construct(int $hidden, array $visible, int $destinations = 0) {
            $this->trainCarManager = new class($hidden, $visible) {
                public array $draws = [];
                public function __construct(public int $hidden, public array $visible) {}
                public function getPlayerHand(int $playerId): array { return []; }
                public function getRemainingTrainCarCardsInDeck(bool ...$options): int { return $this->hidden; }
                public function getVisibleTrainCarCards(bool $second = false): array {
                    return array_values(array_filter($this->visible, fn($card) => !$second || $card->type !== 0));
                }
                public function drawTrainCarCardsFromDeck(int $playerId, int $count, bool $second = false): int {
                    if ($count > $this->hidden) { throw new \RuntimeException('Attempted empty-deck draw'); }
                    $this->hidden -= $count;
                    $this->draws[] = ['deck', $count];
                    return $count;
                }
                public function drawTrainCarCardsFromTable(int $playerId, int $id, bool $second = false): object {
                    foreach ($this->getVisibleTrainCarCards($second) as $card) {
                        if ($card->id === $id) {
                            $this->visible = array_values(array_filter($this->visible, fn($other) => $other->id !== $id));
                            $this->draws[] = ['table', $id];
                            return $card;
                        }
                    }
                    throw new \RuntimeException('Illegal visible draw');
                }
                public function canTakeASecondCard(?int $type): bool {
                    return $type !== 0 && ($this->hidden > 0 || count($this->getVisibleTrainCarCards(true)) > 0);
                }
            };
            $this->legendaryCharacterManager = new class {
                public function getCharacter4UsingRouteIds(int $playerId): array { return []; }
            };
            $this->mapManager = new class { public function claimableRoutes(mixed ...$options): array { return []; } };
            $this->destinationManager = new class($destinations) {
                public bool $picked = false;
                public function __construct(private int $remaining) {}
                public function getRemainingDestinationCardsInDeck(): int { return $this->remaining; }
                public function pickAdditionalDestinationCards(int $playerId): void { $this->picked = true; }
            };
        }
        public function getMap(): object { return (object)['useTechnologyCards' => false]; }
        public function getRemainingTrainCarsCount(int $playerId): int { return 6; }
        public function getLowestTrainCarsCount(): int { return 6; }
        public function incStat(mixed ...$options): void {}
    }
}
namespace {
    function clienttranslate(string $text): string { return $text; }
    require_once __DIR__.'/../modules/php/constants.inc.php';
    require_once __DIR__.'/../modules/php/States/ChooseAction.php';
    require_once __DIR__.'/../modules/php/States/DrawSecondCard.php';
    use Bga\Games\TicketToRide\Game;
    use Bga\Games\TicketToRide\States\ChooseAction;
    use Bga\Games\TicketToRide\States\DrawSecondCard;
    use Bga\Games\TicketToRide\States\NextPlayer;

    function check(bool $valid, string $message): void { if (!$valid) { throw new \RuntimeException($message); } }
    $red = (object)['id' => 10, 'type' => RED];
    $loco = (object)['id' => 11, 'type' => 0];
    $game = new Game(0, [$loco, $red]);
    check((new DrawSecondCard($game))->zombie(1) === ST_NEXT_PLAYER, 'Second-card fallback did not advance');
    check($game->trainCarManager->draws === [['table', 10]], 'Second-card fallback must exclude face-up Locomotives');
    $game = new Game(0, [$loco]);
    check((new DrawSecondCard($game))->zombie(1) === NextPlayer::class && $game->trainCarManager->draws === [], 'No legal second card should finish the turn');
    $game = new Game(1, []);
    (new DrawSecondCard($game))->zombie(1);
    check($game->trainCarManager->draws === [['deck', 1]], 'Available hidden second card should be drawn');
    $game = new Game(0, [$loco]);
    check((new ChooseAction($game))->zombie(1, ['canPass' => false]) === NextPlayer::class, 'Visible Locomotive should finish the draw turn');
    check($game->trainCarManager->draws === [['table', 11]], 'First draw may take a face-up Locomotive');
    $game = new Game(1, [$red]);
    check((new ChooseAction($game))->zombie(1, ['canPass' => false]) === DrawSecondCard::class, 'One hidden card should lead to a legal visible second draw');
    (new DrawSecondCard($game))->zombie(1);
    check($game->trainCarManager->draws === [['deck', 1], ['table', 10]], 'Exhausting the deck mid-turn should fall back to visible cards');
    $game = new Game(0, [], 1);
    (new ChooseAction($game))->zombie(1, ['canPass' => false]);
    check($game->destinationManager->picked, 'With no train cards, draw an available ticket');
    echo "Zombie empty-deck handling passed.\n";
}
