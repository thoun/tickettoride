<?php
define("APP_GAMEMODULE_PATH", "../misc/"); // include path to stubs, which defines "table.game.php" and other classes
require_once ('../tickettorideeurope.game.php');

class TicketToRideTestStations extends TicketToRideEurope { // this is your game class defined in ggg.game.php
    function __construct() {
        // parent::__construct();
        include '../material.inc.php';// this is how this normally included, from constructor
    }

    function getClaimedRoutes($playerId = null) {
        if ($playerId === 1) {
            $routes = [
                38, //budapest-sarajevo,
            ];
            return array_map(fn($route) => new ClaimedRoute(['route_id' => $route, 'player_id' => $playerId]), $routes);
        } else if ($playerId === 2) {
            $routes = [
                94, //sarajevo-sofia
            ];
            return array_map(fn($route) => new ClaimedRoute(['route_id' => $route, 'player_id' => $playerId]), $routes);
        } else if ($playerId === null) {
            return array_merge(
                $this->getClaimedRoutes(1),
                $this->getClaimedRoutes(2),
            );
        }
        return [];
    }

    // class tests
    function testStationCloseSide() {
        // sarajevo
        $station = new PlacedBuilding(['city_id' => 35 /* sarajevo*/, 'player_id' => 1, 'building_type' => 1]);  
        // budapest sofia
        $destination = new Destination(['id' => 14, 'location' => 'hand', 'location_arg' => 1, 'type' => 1, 'type_arg' => 14], $this->DESTINATIONS);

        $result = $this->useStations(1, [$station], [$destination]);
        // [
        //    points of completed objectives, 
        //    array of completed destinations, 
        //    array of completed destinations routes, 
        //    array of completed destinations used stations
        // ]
        $equal = 
            $result[0] == 5 &&
            count($result[1]) == 1 && $result[1][0]->id == $destination->id &&
            count($result[2]) == 1 && $result[2][0][0]->id == 38 &&
            count($result[3]) == 1 && $result[3][0][0] == 35;
        ;

        if ($equal) {
            echo "Test1: PASSED\n";
        } else {
            echo "Test1: FAILED\n";
            echo "Expected: 5, value: ".json_encode($result, JSON_PRETTY_PRINT)."\n";
        }
    }
    function testStationFarSide() {
        // sofia
        $station = new PlacedBuilding(['city_id' => 40 /* sofia*/, 'player_id' => 1, 'building_type' => 1]);  
        // budapest sofia
        $destination = new Destination(['id' => 14, 'location' => 'hand', 'location_arg' => 1, 'type' => 1, 'type_arg' => 14], $this->DESTINATIONS);

        $result = $this->useStations(1, [$station], [$destination]);
        // [
        //    points of completed objectives, 
        //    array of completed destinations, 
        //    array of completed destinations routes, 
        //    array of completed destinations used stations
        // ]
        $equal = 
            $result[0] == 5 &&
            count($result[1]) == 1 && $result[1][0]->id == $destination->id &&
            count($result[2]) == 1 && $result[2][0][0]->id == 38 &&
            count($result[3]) == 1 && $result[3][0][0] == 40;
        ;

        if ($equal) {
            echo "Test2: PASSED\n";
        } else {
            echo "Test2: FAILED\n";
            echo "Expected: 5, value: ".json_encode($result, JSON_PRETTY_PRINT)."\n";
        }
    }

    function testStationBoth() {
        // sofia
        $stations = [
            new PlacedBuilding(['city_id' => 35 /* sarajevo*/, 'player_id' => 1, 'building_type' => 1]),
            new PlacedBuilding(['city_id' => 40 /* sofia*/, 'player_id' => 1, 'building_type' => 1]),
        ];
        // budapest sofia
        $destination = new Destination(['id' => 14, 'location' => 'hand', 'location_arg' => 1, 'type' => 1, 'type_arg' => 14], $this->DESTINATIONS);

        $result = $this->useStations(1, $stations, [$destination]);
        // [
        //    points of completed objectives, 
        //    array of completed destinations, 
        //    array of completed destinations routes, 
        //    array of completed destinations used stations
        // ]
        $equal = 
            $result[0] == 5 &&
            count($result[1]) == 1 && $result[1][0]->id == $destination->id &&
            count($result[2]) == 1 && $result[2][0][0]->id == 38 &&
            count($result[3]) == 1 && in_array($result[3][0][0], [35, 40]);
        ;

        if ($equal) {
            echo "Test3: PASSED\n";
        } else {
            echo "Test3: FAILED\n";
            echo "Expected: 5, value: ".json_encode($result, JSON_PRETTY_PRINT)."\n";
        }
    }

    function testStationUseless() {
        // sofia
        $stations = [
            new PlacedBuilding(['city_id' => 35 /* sarajevo*/, 'player_id' => 1, 'building_type' => 1]),
            new PlacedBuilding(['city_id' => 41 /* stockolhm*/, 'player_id' => 1, 'building_type' => 1]),
        ];
        // budapest sofia
        $destination = new Destination(['id' => 14, 'location' => 'hand', 'location_arg' => 1, 'type' => 1, 'type_arg' => 14], $this->DESTINATIONS);

        $result = $this->useStations(1, $stations, [$destination]);
        // [
        //    points of completed objectives, 
        //    array of completed destinations, 
        //    array of completed destinations routes, 
        //    array of completed destinations used stations
        // ]
        $equal = 
            $result[0] == 5 &&
            count($result[1]) == 1 && $result[1][0]->id == $destination->id &&
            count($result[2]) == 1 && $result[2][0][0]->id == 38 &&
            count($result[3]) == 1 && $result[3][0][0] == 35;
        ;

        if ($equal) {
            echo "Test4: PASSED\n";
        } else {
            echo "Test4: FAILED\n";
            echo "Expected: 5, value: ".json_encode($result, JSON_PRETTY_PRINT)."\n";
        }
    }

    function testAll() {
        $this->testStationCloseSide();
        $this->testStationFarSide();
        $this->testStationBoth();
        $this->testStationUseless();
    }
}

$test1 = new TicketToRideTestStations();
$test1->testAll();