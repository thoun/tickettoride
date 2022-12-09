<?php

/**
 * When a player asks to place a tunnel route, we store corresponding informations, the time needed to ask if he wants to pay the extra cards.
 */
class TunnelAttempt {
    public int $routeId;
    public int $color;
    public int $extraCards;
    public array $tunnelCards;

    public function __construct(int $routeId, int $color, int $extraCards, array $tunnelCards) {
        $this->routeId = $routeId;
        $this->color = $color;
        $this->extraCards = $extraCards;
        $this->tunnelCards = $tunnelCards;
    } 
}

?>