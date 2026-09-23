<?php

namespace Bga\Games\TicketToRide\Objects;

class City {
    public int $id;

    /**
     * @param string $name the name of the city, printed on the map
     * @param int $x the x position of the center of the city circle
     * @param int $y the y position of the center of the city circle
     * @param ?int $country the country the city is associated to (UK map)
     * @param ?int $region the region the city is associated to (Italy map)
     * @param int[]|null $extraCoordinates the coordinates of the second display of the city on the map (Japan map)
     */
    public function __construct(
        // generic parameters
        public string $name,
        public int $x,
        public int $y,
        // specific parameters
        public ?int $country = null,
        public ?int $region = null,
        public ?array $extraCoordinates = null,
    ) {
    }
}
