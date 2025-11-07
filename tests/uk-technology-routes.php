<?php

use Bga\Games\TicketToRideMaps\Objects\Map;

function clienttranslate(string $text): string { return $text; }

require_once __DIR__.'/../modules/php/constants.inc.php';
require_once __DIR__.'/../modules/php/Objects/City.php';
require_once __DIR__.'/../modules/php/Objects/RouteSpace.php';
require_once __DIR__.'/../modules/php/Objects/Route.php';
require_once __DIR__.'/../modules/php/Objects/DestinationCard.php';
require_once __DIR__.'/../modules/php/Objects/Map.php';
require_once __DIR__.'/../modules/maps/uk/map.php';

$map = new UkMap();
$routes = $map->routes;
$assert = static function(int $routeId, array $owned, bool $expected) use ($map, $routes): void {
    if ($map->canClaimRouteWithTechnology($routes[$routeId], $owned) !== $expected) {
        throw new RuntimeException("Unexpected technology access for UK route {$routeId}");
    }
};

$assert(11, [], true);             // One-space England route.
$assert(6, [], false);             // Wales Concession.
$assert(6, [0], true);
$assert(3, [2], false);            // Scotland and Mechanical Stoker are both needed.
$assert(3, [2, 3], true);
$assert(2, [2, 4], false);         // Boiler does not unlock a ferry.
$assert(2, [2, 4, 5], true);
$assert(120, [1, 3], false);       // France requires Propellers too.
$assert(120, [1, 3, 5], true);
$assert(122, [], true);            // Southampton-New York exception.

if (!(new Map([], [], []))->canClaimRouteWithTechnology($routes[6], [])) {
    throw new RuntimeException('Maps without technology cards must retain their route access.');
}

echo "UK technology route access passed.\n";
