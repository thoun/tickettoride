<?php

namespace Bga\Games\TicketToRide\Objects;

class City {
    public int $id;

    public function __construct(
        public string $name,
        public int $x,
        public int $y,
    ) {
    }
}
