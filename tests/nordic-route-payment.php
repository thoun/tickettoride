<?php

namespace Bga\Games\TicketToRideEurope\ {
    use Bga\Games\TicketToRideEurope\Objects\Map;

    class Game {
        public function __construct(private Map $map) {}

        public function getMap(): Map {
            return $this->map;
        }
    }
}

namespace {
    use Bga\Games\TicketToRideEurope\Game;
    use Bga\Games\TicketToRideEurope\MapManager;
    use Bga\Games\TicketToRideEurope\Objects\Map;

    require_once __DIR__.'/../modules/php/constants.inc.php';
    require_once __DIR__.'/../modules/php/framework-prototype/Helpers/Arrays.php';
    require_once __DIR__.'/../modules/php/Objects/Map.php';
    require_once __DIR__.'/../modules/php/Objects/Route.php';
    require_once __DIR__.'/../modules/php/Objects/RouteSpace.php';
    require_once __DIR__.'/../modules/php/MapManager.php';
    require_once __DIR__.'/../modules/maps/nordiccountries/routes.php';

    $map = new Map([], [], []);
    $map->locomotiveUsageRestriction = Map::LOCOMOTIVE_TUNNEL | Map::LOCOMOTIVE_FERRY;
    $manager = new MapManager(new Game($map));
    $routes = getRoutes();

    $hand = function(array $types): array {
        return array_map(fn($index, $type) => (object)['id' => $index + 1, 'type' => $type], array_keys($types), $types);
    };
    $assertPayment = function(int $routeId, array $types, ?int $color, ?int $expectedCards) use ($manager, $routes, $hand): void {
        $cards = $manager->canPayForRoute($routes[$routeId], $hand($types), 40, $color);
        if ($expectedCards === null ? $cards !== null : count($cards ?? []) !== $expectedCards) {
            throw new \RuntimeException("Unexpected payment for Nordic route $routeId");
        }
        if ($cards !== null && count(array_unique(array_map(fn($card) => $card->id, $cards))) !== count($cards)) {
            throw new \RuntimeException("A card was charged twice for Nordic route $routeId");
        }
    };

    // Stockholm-Helsinki: two orange cards plus two three-card locomotive substitutes.
    $assertPayment(23, [ORANGE, ORANGE, BLUE, BLUE, RED, RED, GREEN, GREEN], ORANGE, 8);
    // Sundsvall-Vaasa: one blue card plus two three-card substitutes.
    $assertPayment(78, [BLUE, ORANGE, ORANGE, RED, RED, GREEN, GREEN], BLUE, 7);
    $assertPayment(78, [BLUE, ORANGE, ORANGE, RED, RED, GREEN], BLUE, null);
    // A locomotive and two blue cards need no substitution.
    $assertPayment(78, [BLUE, BLUE, 0], BLUE, 3);
    // One substitution alongside two blue cards spends five physical cards.
    $assertPayment(78, [BLUE, BLUE, ORANGE, RED, GREEN], BLUE, 5);
    // The locomotive-only choice must also charge its substitute set.
    $assertPayment(5, [ORANGE, RED, BLUE], 0, 3);
    $assertPayment(5, [ORANGE, RED], 0, null);
    // A regular route cannot use spare colors as free substitutes.
    $assertPayment(77, [PINK, PINK, ORANGE, RED, GREEN], PINK, null);
    $assertPayment(77, [PINK, PINK, PINK, ORANGE], PINK, 3);

    echo "Nordic route payments passed.\n";
}
