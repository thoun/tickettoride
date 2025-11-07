<?php

namespace Bga\Games\TicketToRideMaps\Objects;

class RouteSpace {
    public function __construct(
        public int $x,
        public int $y,
        public int $angle,
        public bool $top = false,
    ) {
    }
}
