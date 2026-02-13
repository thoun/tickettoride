<?php

namespace Bga\Games\TicketToRideEurope\ {
    class Game {
        public object $bga;
        public function __construct() {
            $this->bga = (object)['globals' => new class {
                private array $values = [];
                public function set(string $key, mixed $value): void { $this->values[$key] = $value; }
                public function get(string $key, mixed $default = null): mixed { return $this->values[$key] ?? $default; }
            }];
        }
    }
}

namespace {
    function clienttranslate(string $text): string { return $text; }
    require_once __DIR__.'/../modules/php/constants.inc.php';
    require_once __DIR__.'/../modules/php/Objects/City.php';
    require_once __DIR__.'/../modules/php/Objects/RouteSpace.php';
    require_once __DIR__.'/../modules/php/Objects/Route.php';
    require_once __DIR__.'/../modules/php/Objects/DestinationCard.php';
    require_once __DIR__.'/../modules/php/Objects/Map.php';
    require_once __DIR__.'/../modules/maps/northernlights/map.php';

    $map = new \NorthernLightsMap();
    for ($i = 0; $i < 100; $i++) {
        $game = new \Bga\Games\TicketToRideEurope\Game();
        $map->setup($game);
        $selection = $map->getMapSpecificData($game)['bonusCards'];
        if (count($selection) !== 4 || count(array_unique($selection)) !== 4
            || array_diff($selection, range(0, 10))) {
            throw new \RuntimeException('Setup must select four distinct bonus cards from A-K.');
        }
        // Recreating the map on refresh must keep the saved selection.
        if ((new \NorthernLightsMap())->getMapSpecificData($game)['bonusCards'] !== $selection) {
            throw new \RuntimeException('Bonus selection changed on refresh.');
        }
    }
    if (!in_array('bonus-cards.webp', $map->getPreloadImages(0), true)) {
        throw new \RuntimeException('Bonus sprite must be preloaded.');
    }
    echo "Northern Lights bonus setup passed.\n";
}
