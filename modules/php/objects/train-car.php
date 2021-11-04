<?php

class TrainCar {
    public $id;
    public $location;
    public $location_arg;
    public $type; // color: 0 for locomotive, ... TODO
    public $type_arg;

    public function __construct($dbCard) {
        $this->id = intval($dbCard['id']);
        $this->location = $dbCard['location'];
        $this->location_arg = intval($dbCard['location_arg']);
        $this->type = intval($dbCard['type']);
        $this->type_arg = intval($dbCard['type_arg']);
    } 
}
?>