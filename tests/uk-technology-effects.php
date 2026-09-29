<?php

namespace Bga\GameFramework\Components {
    class Deck {
        public int $deckCount = 0;
        public function countCardInLocation(string $location): int { return $this->deckCount; }
    }
}

namespace Bga\Games\TicketToRide {
    use Bga\Games\TicketToRide\Objects\Map;

    class Game {
        public object $bga;
        public object $notify;
        public object $trainCarManager;

        public function __construct(private Map $map, array $technologyCards, bool $advancedTechnologies = false) {
            $this->bga = (object)[
                'tableOptions' => new class($advancedTechnologies) {
                    public function __construct(private bool $advancedTechnologies) {}
                    public function get(int $option): int { return $this->advancedTechnologies ? 1 : 0; }
                },
                'globals' => new class($technologyCards) {
                private array $values;
                public function __construct(array $cards) {
                    $this->values = [
                        'TECHNOLOGY_CARDS_1' => $cards,
                        'TRAIN_CAR_DECK_RESHUFFLED' => false,
                        'REMAINING_TECHNOLOGY_CARDS' => [12 => 2, 13 => 1, 14 => 1],
                    ];
                }

                public function get(string $key, mixed $default = null): mixed {
                    return $this->values[$key] ?? $default;
                }
                public function set(string $key, mixed $value): void { $this->values[$key] = $value; }
                },
            ];
            $this->trainCarManager = (object)['trainCars' => new \Bga\GameFramework\Components\Deck()];
            $this->notify = new class {
                public array $types = [];
                public function all(string $type, string $message, array $args): void { $this->types[] = $type; }
            };
        }

        public function getMap(): Map { return $this->map; }
        public function getPlayersIds(): array { return []; }
    }
}

namespace {
    use Bga\Games\TicketToRide\Game;
    use Bga\Games\TicketToRide\MapManager;
    use Bga\Games\TicketToRide\Objects\Map;
    use Bga\Games\TicketToRide\Objects\Route;
    use Bga\Games\TicketToRide\Objects\RouteSpace;

    function clienttranslate(string $text): string { return $text; }

    require_once __DIR__.'/../modules/php/constants.inc.php';
    require_once __DIR__.'/../modules/php/framework-prototype/Helpers/Arrays.php';
    require_once __DIR__.'/../modules/php/Objects/City.php';
    require_once __DIR__.'/../modules/php/Objects/RouteSpace.php';
    require_once __DIR__.'/../modules/php/Objects/Route.php';
    require_once __DIR__.'/../modules/php/Objects/DestinationCard.php';
    require_once __DIR__.'/../modules/php/Objects/Map.php';
    require_once __DIR__.'/../modules/php/MapManager.php';
    require_once __DIR__.'/../modules/php/TrainCarManager.php';
    require_once __DIR__.'/../modules/maps/uk/map.php';

    $uk = new \UkMap();
    foreach ([false, true] as $advancedTechnologies) {
        $setupGame = new Game($uk, [], $advancedTechnologies);
        $uk->setup($setupGame);
        $counts = $setupGame->bga->globals->get('REMAINING_TECHNOLOGY_CARDS');
        if (count($counts) !== ($advancedTechnologies ? 16 : 11)
            || $counts[10] !== 1
            || array_key_exists(11, $counts) !== $advancedTechnologies
            || $setupGame->bga->globals->get('THERMOCOMPRESSOR_REMAINING') !== 0) {
            throw new \RuntimeException('UK setup created the wrong Technology card types.');
        }
        if (!$advancedTechnologies) {
            $setupGame->trainCarManager->trainCars->deckCount = 1;
            $setupManager = (new \ReflectionClass(\Bga\Games\TicketToRide\TrainCarManager::class))->newInstanceWithoutConstructor();
            (new \ReflectionProperty($setupManager, 'game'))->setValue($setupManager, $setupGame);
            $setupManager->trainCars = $setupGame->trainCarManager->trainCars;
            $setupManager->trainCarDeckAutoReshuffle();
            $remaining = $setupGame->bga->globals->get('REMAINING_TECHNOLOGY_CARDS');
            if (count($remaining) !== 11 || in_array('technologyCardsExpired', $setupGame->notify->types, true)) {
                throw new \RuntimeException('Deck reshuffling recreated unavailable Advanced Technologies.');
            }
        }
    }
    $route = new Route(1, 2, RED, [new RouteSpace(0, 0, 0), new RouteSpace(0, 0, 0)], locomotives: 1);
    $hand = static fn(array $types): array => array_map(
        static fn(int $index, int $type): object => (object)['id' => $index + 1, 'type' => $type],
        array_keys($types), $types,
    );
    $payment = static function(Map $map, array $owned, array $types) use ($route, $hand): ?array {
        return (new MapManager(new Game($map, $owned)))->canPayForRoute($route, $hand($types), 35, RED, playerId: 1);
    };
    $assertPayment = static function(?array $cards, ?int $expected): void {
        if ($expected === null ? $cards !== null : count($cards ?? []) !== $expected) {
            throw new \RuntimeException('Unexpected UK locomotive substitution payment.');
        }
    };

    $assertPayment($payment($uk, [], [RED, BLUE, GREEN, YELLOW]), null);
    $assertPayment($payment($uk, [], [RED, BLUE, GREEN, YELLOW, ORANGE]), 5);
    $assertPayment($payment($uk, [6], [RED, BLUE, GREEN, YELLOW]), 4);
    $assertPayment($payment(new Map([], [], []), [6], [RED, BLUE, GREEN, YELLOW]), null);
    $assertPayment($payment($uk, [], [0]), null);
    $assertPayment($payment($uk, [15], [0]), 1);
    $assertPayment($payment($uk, [15], [RED]), null);
    $assertPayment($payment($uk, [15], [RED, BLUE, GREEN, YELLOW]), 4);

    $dieselManager = new MapManager(new Game($uk, [15]));
    $ferryCards = $hand([0, RED]);
    if ($dieselManager->getRouteTrainCardCost($route, 1) !== 1
        || $dieselManager->getRouteTrainCardCost($route, 1, 1) !== 2
        || $dieselManager->canPayForRoute($route, $ferryCards, 35, RED, distributionCards: [$ferryCards[1]], playerId: 1) !== null
        || count($dieselManager->canPayForRoute($route, $ferryCards, 35, RED, distributionCards: [$ferryCards[0]], playerId: 1) ?? []) !== 1
        || count($dieselManager->canPayForRoute($route, $ferryCards, 35, RED, extraCardsCost: 1, playerId: 1) ?? []) !== 2) {
        throw new \RuntimeException('Diesel Power must discount one card without waiving a Ferry Locomotive or tunnel extra cost.');
    }

    $allLocomotives = new Route(1, 2, RED, [new RouteSpace(0, 0, 0), new RouteSpace(0, 0, 0)], locomotives: 2);
    if ($dieselManager->getRouteTrainCardCost($allLocomotives, 1) !== 2
        || $dieselManager->canPayForRoute($allLocomotives, $hand([0]), 35, 0, playerId: 1) !== null) {
        throw new \RuntimeException('Diesel Power cannot waive a required Ferry Locomotive.');
    }

    $plainTwoSpace = new Route(1, 2, RED, [new RouteSpace(0, 0, 0), new RouteSpace(0, 0, 0)]);
    $assertPayment((new MapManager(new Game($uk, [])))->canPayForRoute($plainTwoSpace, $hand([RED]), 35, RED, playerId: 1), null);
    $assertPayment($dieselManager->canPayForRoute($plainTwoSpace, $hand([RED]), 35, RED, playerId: 1), 1);
    $assertPayment((new MapManager(new Game(new Map([], [], []), [15])))->canPayForRoute($plainTwoSpace, $hand([RED]), 35, RED, playerId: 1), null);

    $selected = $hand([RED, BLUE, GREEN, YELLOW, ORANGE]);
    $manager = new MapManager(new Game($uk, [6]));
    if ($manager->canPayForRoute($route, $selected, 35, RED, distributionCards: $selected, playerId: 1) !== null) {
        throw new \RuntimeException('Extra cards must not be accepted in a Booster substitute set.');
    }

    $plainRoute = new Route(1, 2, RED, [new RouteSpace(0, 0, 0)]);
    if ($dieselManager->getRouteTrainCardCost($plainRoute, 1) !== 1
        || $dieselManager->canPayForRoute($plainRoute, [], 35, RED, playerId: 1) !== null) {
        throw new \RuntimeException('Diesel Power cannot make a one-space route free.');
    }

    if ($uk->getAdditionalRoutePoints($route, []) !== 0
        || $uk->getAdditionalRoutePoints($route, [7]) !== 1
        || $uk->getAdditionalRoutePoints($route, [8]) !== 2
        || $uk->getAdditionalRoutePoints($route, [7, 8]) !== 3
        || $uk->getAdditionalRoutePoints($plainRoute, [7, 8]) !== 1
        || $uk->getCompletedTicketBonus(3, [9]) !== 6
        || $uk->getCompletedTicketBonus(3, []) !== 0
        || $uk->getMaximumHiddenTrainCardsPerAction([]) !== 2
        || $uk->getMaximumHiddenTrainCardsPerAction([12]) !== 3
        || $uk->getEndGameTechnologyBonuses([13, 14], 4, 4, 8, 8) !== [13 => 20, 14 => 15]
        || $uk->getEndGameTechnologyBonuses([13, 14], 3, 4, 7, 8) !== [13 => -20, 14 => -15]
        || (new Map([], [], []))->getEndGameTechnologyBonuses([13, 14], 4, 4, 8, 8) !== []) {
        throw new \RuntimeException('Unexpected UK Technology scoring.');
    }

    $game = new Game($uk, []);
    $manager = (new \ReflectionClass(\Bga\Games\TicketToRide\TrainCarManager::class))->newInstanceWithoutConstructor();
    (new \ReflectionProperty($manager, 'game'))->setValue($manager, $game);
    $manager->trainCars = new \Bga\GameFramework\Components\Deck();
    $manager->trainCarDeckAutoReshuffle();
    if ($game->bga->globals->get('TRAIN_CAR_DECK_RESHUFFLED')) {
        throw new \RuntimeException('An empty discard pile must not expire Technology cards.');
    }
    $manager->trainCars->deckCount = 1;
    $manager->trainCarDeckAutoReshuffle();
    $manager->trainCarDeckAutoReshuffle();
    $remaining = $game->bga->globals->get('REMAINING_TECHNOLOGY_CARDS');
    if (!$game->bga->globals->get('TRAIN_CAR_DECK_RESHUFFLED')
        || $remaining[12] !== 2 || $remaining[13] !== 0 || $remaining[14] !== 0
        || count(array_filter($game->notify->types, fn($type) => $type === 'technologyCardsExpired')) !== 1) {
        throw new \RuntimeException('Advanced Technologies must expire exactly once on the first real reshuffle.');
    }

    echo "UK technology effects passed.\n";
}
