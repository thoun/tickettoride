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
    use Bga\Games\TicketToRideEurope\Objects\Route;

    require_once __DIR__.'/../modules/php/constants.inc.php';
    require_once __DIR__.'/../modules/php/framework-prototype/Helpers/Arrays.php';
    require_once __DIR__.'/../modules/php/Objects/Map.php';
    require_once __DIR__.'/../modules/php/Objects/Route.php';
    require_once __DIR__.'/../modules/php/MapManager.php';

    $manager = new MapManager(new Game(new Map([], [], [])));
    $assertPayment = function(string $scenario, Route $route, array $types, int $color, ?int $pairColor, array $expectedIds) use ($manager): void {
        $hand = array_map(fn($index, $type) => (object)['id' => $index + 1, 'type' => $type], array_keys($types), $types);
        $payment = $manager->canPayForRoute($route, $hand, 40, $color, pairSetAsLocomotive: $pairColor);
        $ids = array_map(fn($card) => $card->id, $payment ?? []);
        sort($ids);
        sort($expectedIds);
        if ($ids !== $expectedIds) {
            throw new \RuntimeException("Unexpected payment for $scenario: ".json_encode($ids));
        }
    };

    $redRoute = new Route(1, 2, RED, [null, null, null]);
    // Bug #248099: preserve the real locomotive when Mina's blue pair covers the missing card.
    $assertPayment('colored route', $redRoute, [RED, RED, 0, BLUE, BLUE], RED, BLUE, [1, 2, 4, 5]);
    // Bug #244854: use the selected pair and only one of the four real locomotives.
    $grayRoute = new Route(1, 2, 0, array_fill(0, 6, null));
    $assertPayment('gray route', $grayRoute, [GREEN, GREEN, GREEN, GREEN, 0, 0, 0, 0, RED, RED], GREEN, RED, [1, 2, 3, 4, 5, 9, 10]);
    $ferryRoute = new Route(1, 2, RED, [null, null, null], locomotives: 2);
    $assertPayment('required locomotives', $ferryRoute, [RED, 0, 0, BLUE, BLUE], RED, BLUE, [1, 2, 4, 5]);
    $assertPayment('locomotives only', $redRoute, [0, 0, 0, BLUE, BLUE], 0, BLUE, [1, 2, 4, 5]);
    // Activating Mina should not spend the pair when ordinary colored cards cover the whole route.
    $assertPayment('no locomotive needed', $redRoute, [RED, RED, RED, 0, BLUE, BLUE], RED, BLUE, [1, 2, 3]);
    $assertPayment('Mina inactive', $redRoute, [RED, RED, 0, BLUE, BLUE], RED, null, [1, 2, 3]);

    echo "Mina Harker payments passed.\n";
}
