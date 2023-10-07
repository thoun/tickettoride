<?php

class PlacedBuilding {
    public int $cityId;
    public int $playerId;
    public int $buildingType;

    public function __construct(array $db) {
        $this->cityId = intval($db['city_id']);
        $this->playerId = intval($db['player_id']);
        $this->buildingType = intval($db['building_type']);
    } 
}
?>