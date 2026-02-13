<?php

namespace Bga\Games\TicketToRideEurope\Objects;

/**
 * A route is a path from one city to another.
 * For double routes, there is 2 instances of Route.
 *
 * from/to : cities ids
 * number : number of meeples to take the route
 * color (0 for gray, else see Color constants)
 */
class Route {
    public int $id;
    public int $number;

    public function __construct(
        public int $from,
        public int $to,
        public int $color,
        public array $spaces = [],
        public bool $tunnel = false,
        public int $locomotives = 0,
        public ?int $canPayWithAnySetOfCards = null,
    ) {
        $this->number = count($spaces);
    }
}
