<?php

namespace Bga\Games\TicketToRide {
    class Game {
        public function __construct(private object $map) {}
        public function getMap(): object { return $this->map; }
    }
}
namespace {
    function clienttranslate(string $text): string { return $text; }
    require_once __DIR__.'/../modules/php/constants.inc.php';
    require_once __DIR__.'/../modules/php/framework-prototype/Helpers/Arrays.php';
    require_once __DIR__.'/../modules/php/Objects/City.php';
    require_once __DIR__.'/../modules/php/Objects/Route.php';
    require_once __DIR__.'/../modules/php/Objects/RouteSpace.php';
    require_once __DIR__.'/../modules/php/Objects/DestinationCard.php';
    require_once __DIR__.'/../modules/php/Objects/Map.php';
    require_once __DIR__.'/../modules/php/MapManager.php';
    require_once __DIR__.'/../modules/maps/northernlights/map.php';

    $map = new \NorthernLightsMap();
    foreach ($map->routes as $route) {
        if (($route->canPayFerriesWithAnySetOfCards === 2) !== ($route->locomotives > 0)) {
            throw new \RuntimeException('Matching pairs must be enabled exactly on ferry routes.');
        }
        if ($route->canPayWithAnySetOfCards !== null) {
            throw new \RuntimeException('Pairs must not replace ordinary route spaces.');
        }
    }
    $manager = new \Bga\Games\TicketToRide\MapManager(new \Bga\Games\TicketToRide\Game($map));
    $cases = [
        [3, 1, RED, [RED, RED, BLUE, BLUE], true],
        [3, 1, RED, [RED, RED, BLUE, GREEN], false],
        [3, 1, RED, [RED, RED, 0], true],
        [3, 1, RED, [RED, RED, RED, RED], true],
        [3, 1, RED, [RED, BLUE, BLUE], false],
        [3, 1, RED, [RED, RED, BLUE, BLUE, GREEN], false],
        [3, 1, RED, [BLUE, BLUE, GREEN, GREEN, YELLOW, YELLOW], false],
        [4, 2, RED, [RED, RED, BLUE, BLUE, GREEN, GREEN], true],
        [4, 2, RED, [RED, RED, 0, BLUE, BLUE], true],
        [4, 2, RED, [RED, RED, BLUE, GREEN, YELLOW, YELLOW], false],
        [4, 2, RED, [0, 0, RED, RED, RED, RED], true],
        [1, 1, 0, [BLUE, BLUE], true],
        [1, 1, 0, [BLUE, GREEN], false],
        [2, 2, 0, [BLUE, BLUE, GREEN, GREEN], true],
        [3, 1, 0, [0, 0, BLUE, BLUE], true],
        [3, 1, 0, [RED, RED, BLUE, BLUE], false],
    ];
    foreach ($cases as [$length, $symbols, $color, $types, $valid]) {
        $route = new \Bga\Games\TicketToRide\Objects\Route(1, 2, GRAY,
            array_fill(0, $length, new \Bga\Games\TicketToRide\Objects\RouteSpace(0, 0, 0)),
            locomotives: $symbols, canPayFerriesWithAnySetOfCards: 2);
        $hand = array_map(fn($id, $type) => (object)['id' => $id + 1, 'type' => $type], array_keys($types), $types);
        $payment = $manager->canPayForRoute($route, $hand, 40, $color, distributionCards: $hand);
        if (($payment !== null) !== $valid) {
            throw new \RuntimeException('Wrong custom payment: '.json_encode([$length, $symbols, $color, $types]));
        }
        if ($valid) {
            $automatic = $manager->canPayForRoute($route, $hand, 40, $color);
            if ($automatic === null || count(array_unique(array_column($automatic, 'id'))) !== count($automatic)) {
                throw new \RuntimeException('Automatic payment failed or reused cards.');
            }
        }
        if ($manager->canPayForRoute($route, $hand, $length - 1, $color) !== null) {
            throw new \RuntimeException('Ferry substitution bypassed the train-token requirement.');
        }
    }
    echo "Northern Lights ferry payments passed.\n";
}
