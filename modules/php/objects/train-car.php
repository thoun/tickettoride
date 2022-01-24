<?php

/**
 * A TrainCar is a physical card : a locomotive or a color card.
 * Location : deck, table or hand
 * Location arg : order (in deck or table), playerId (in hand)
 * Type : color (0 for locomotive, else see Color constants)
 * Type arg : unused
 */
class TrainCar {
    public int $id;
    public string $location;
    public int $location_arg;
    public int $type;
    //public int $type_arg;

    public function __construct($dbCard) {
        $this->id = intval($dbCard['id']);
        $this->location = $dbCard['location'];
        $this->location_arg = intval($dbCard['location_arg']);
        $this->type = intval($dbCard['type']);
        //$this->type_arg = intval($dbCard['type_arg']);
    } 
}
?>