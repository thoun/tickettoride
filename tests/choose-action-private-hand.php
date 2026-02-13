<?php

namespace Bga\GameFramework\States {
    class GameState {
        public function __construct(object $game, mixed ...$options) {}
    }
}
namespace Bga\GameFramework {
    class StateType { const ACTIVE_PLAYER = 1; }
}
namespace Bga\Games\TicketToRideEurope\ {
    class Game {
        public object $legendaryCharacterManager;
        public object $trainCarManager;
        public object $destinationManager;
        public object $mapManager;
        public function __construct(private object $map, public array $hand) {
            $this->legendaryCharacterManager = new class {
                public function isActive(): bool { return false; }
            };
            $this->trainCarManager = new class($hand) {
                public function __construct(private array $hand) {}
                public function getPlayerHand(int $playerId): array { return $this->hand; }
                public function getRemainingTrainCarCardsInDeck(bool ...$options): int { return 10; }
            };
            $this->destinationManager = new class {
                public function getRemainingDestinationCardsInDeck(): int { return 10; }
            };
            $this->mapManager = new class {
                public function claimableRoutes(mixed ...$options): array { return []; }
            };
        }
        public function getMap(): object { return $this->map; }
        public function getRemainingTrainCarsCount(int $playerId): int { return 40; }
        public function getExpansionOption(): int { return 0; }
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
    require_once __DIR__.'/../modules/php/States/ChooseAction.php';

    $hand = [(object)['id' => 1, 'type' => RED], (object)['id' => 2, 'type' => RED]];
    $game = new \Bga\Games\TicketToRideEurope\Game(new \NorthernLightsMap(), $hand);
    $args = (new \Bga\Games\TicketToRideEurope\States\ChooseAction($game))->getArgs(42);
    if (($args['_private'][42]['trainCarsHand'] ?? null) !== $hand || array_keys($args['_private']) !== [42]
        || array_key_exists('trainCarsHand', $args)) {
        throw new \RuntimeException('Northern Lights must provide the popin hand only to the active player.');
    }
    $game = new \Bga\Games\TicketToRideEurope\Game(new \Bga\Games\TicketToRideEurope\Objects\Map([], [], []), $hand);
    $args = (new \Bga\Games\TicketToRideEurope\States\ChooseAction($game))->getArgs(42);
    if (isset($args['_private'][42]['trainCarsHand'])) {
        throw new \RuntimeException('Maps without custom payment rules changed their state payload.');
    }
    echo "Choose Action private hand passed.\n";
}
