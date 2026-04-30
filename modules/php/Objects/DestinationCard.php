<?php

namespace Bga\Games\TicketToRide\Objects;

/**
 * A DestinationCard is the graphic representation of a card (informations on it : from, to and points).
 */
class DestinationCard {
    public function __construct(
        public int $from,
        public int|array $to,
        public int|array $points
    ) {
    }
}
