<?php

/**
 * A DestinationCard is the graphic representation of a card (informations on it : from, to and points).
 */
class DestinationCard {
    public int $from;
    public int $to;
    public int $points;
  
    public function __construct(int $from, int $to, int $points) {
        $this->from = $from;
        $this->to = $to;
        $this->points = $points;
    } 
}

/**
 * A Destination is a physical card. It contains informations from matching DestinationCard, with technical informations like id and location.
 * Location : deck or hand
 * Location arg : order (in deck), playerId (in hand)
 * Type : 1 for simple destination
 * Type arg : the destination type (DestinationCard id)
 */
class Destination extends DestinationCard {
    public int $id;
    public string $location;
    public int $location_arg;
    public int $type;
    public int $type_arg;

    public function __construct($dbCard, $DESTINATIONS) {
        $this->id = intval($dbCard['id']);
        $this->location = $dbCard['location'];
        $this->location_arg = intval($dbCard['location_arg']);
        $this->type = intval($dbCard['type']);
        $this->type_arg = intval($dbCard['type_arg']);

        $destinationCard = $DESTINATIONS[$this->type][$this->type_arg];
        $this->from = $destinationCard->from;
        $this->to = $destinationCard->to;
        $this->points = $destinationCard->points;
    } 
}
?>