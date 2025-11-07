<?php

namespace Bga\Games\TicketToRideMaps\Objects;

class ClaimedRoute {
    public int $routeId;
    public int $playerId;
    public int $shiftIndex;

    public function __construct(array $db) {
        $this->routeId = intval($db['route_id']);
        $this->playerId = intval($db['player_id']);
        $this->shiftIndex = intval($db['shift_index'] ?? 0);
    }
}
