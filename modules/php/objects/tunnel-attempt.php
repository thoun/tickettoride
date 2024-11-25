<?php

/**
 * When a player asks to place a tunnel route, we store corresponding informations, the time needed to ask if he wants to pay the extra cards.
 */
class TunnelAttempt {
    public function __construct(
        public int $routeId,
        public int $color,
        public int $extraCards,
        public array $tunnelCards,
    ) {
    } 
}

?>