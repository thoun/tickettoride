<?php

class RouteSpace {
    public int $x;
    public int $y;
    public int $angle;
    public bool $top = false;

    public function __construct(int $x, int $y, int $angle, bool $top = false) {
        $this->x = $x;
        $this->y = $y;
        $this->angle = $angle;
        $this->top = $top;
    }
}

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
    public int $from;
    public int $to;
    public int $number;
    public int $color;
    public bool $tunnel;
    public int $locomotives;
    public array $spaces;

    public function __construct(int $from, int $to, int $color, array $spaces = [], bool $tunnel = false, int $locomotives = 0) {
        $this->from = $from;
        $this->to = $to;
        $this->number = count($spaces);
        $this->color = $color;
        $this->spaces = $spaces;
        $this->tunnel = $tunnel;
        $this->locomotives = $locomotives;
    } 
}

class ClaimedRoute {
    public int $routeId;
    public int $playerId;

    public function __construct(array $db) {
        $this->routeId = intval($db['route_id']);
        $this->playerId = intval($db['player_id']);
    } 
}
?>