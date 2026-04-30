<?php

namespace Bga\Games\TicketToRide\Objects;

class BigCity {
    public function __construct(
        public int $x,
        public int $y,
        public int $width,
    ) {
    }
}
