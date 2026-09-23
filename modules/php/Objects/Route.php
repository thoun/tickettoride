<?php

namespace Bga\Games\TicketToRide\Objects;

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

    /**
     * @param int $from id of one of the city linked to this route
     * @param int $to id of the other city linked to this route
     * @param int $color the color of the route (TRACKBED if not yet known)
     * @param RouteSpace[] $spaces the spaces of the route
     * @param bool $tunnel if the route is a tunnel (special black outline on each space)
     * @param int $locomotives number of locomotives required to take the route (drawn on route spaces)
     * @param ?int $canPayWithAnySetOfCards number of any cards you can use to replace a color card
     * @param int $mountain number of discarded train cars when taking the route (cross drawn on route spaces)
     * @param int[] $stockShares ids of the stock shares associated to this route
     * @param ?int $bulletTrainSpaceIndex index of the bullet train space (bullet train logo drawn on this route space)
     * @param ?int $ferryWaves number of waves on this route (wave logo drawn on route spaces)
     */
    public function __construct(
        // generic parameters
        public int $from,
        public int $to,
        public int $color,
        public array $spaces = [],
        public bool $tunnel = false,
        public int $locomotives = 0,
        // specific parameters
        public ?int $canPayWithAnySetOfCards = null,
        public int $mountain = 0,
        public array $stockShares = [],
        public ?int $bulletTrainSpaceIndex = null,
        public int $ferryWaves = 0,
    ) {
        $this->number = count($spaces);
    }
}
