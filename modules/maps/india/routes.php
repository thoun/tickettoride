<?php

require_once(__DIR__.'/../../php/objects/route.php');

/**
 * Route on the map. 
 * For double routes, there is 2 instances of Route.
 * For cities (from/to), it's always low id to high id.
 */
function getRoutes() {
  return [
    1 => new Route(1, 36, RED, [
      new RouteSpace(459, 698, 93),
    ]),
    2 => new Route(1, 36, PINK, [
      new RouteSpace(481, 699, 93),
    ]),
    3 => new Route(1, 13, ORANGE, [
      new RouteSpace(460, 528, 84),
      new RouteSpace(465, 589, 84),
    ]),
    4 => new Route(1, 19, BLUE, [
      new RouteSpace(420, 620, 15),
    ]),
    5 => new Route(1, 19, WHITE, [
      new RouteSpace(415, 640, 13),
    ]),
    6 => new Route(1, 23, GREEN, [
      new RouteSpace(523, 647, 2),
      new RouteSpace(584, 649, 1),
    ]),
    7 => new Route(1, 26, GRAY, [
      new RouteSpace(506, 601, -50),
      new RouteSpace(558, 566, -17),
    ]),

    // TODO using the cities described in modules/maps/india/cities.php and the image img/india/map.jpg showing the link between cities as rectangles representing a RouteSpace, can you complete this list for routes linked to Ahmadabad, as it is done above for routes linked to Agra ?
  ];
}

/*
console.log(``.replace(/RouteSpace\((\d+), (\d+)/g, (_, x, y) => `RouteSpace(${Number(x) + 37}, ${Number(y) + 18}`))
*/
