<?php

namespace Bga\Games\TicketToRide\Objects;

class ClaimedRoute {
    public int $routeId;
    public int $playerId;

    public function __construct(array $db) {
        $this->routeId = intval($db['route_id']);
        $this->playerId = intval($db['player_id']);
    }
}
