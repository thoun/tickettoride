<?php
define("APP_GAMEMODULE_PATH", "../misc/"); // include path to stubs, which defines "table.game.php" and other classes
require_once ('../tickettoridemaps.game.php');

class TicketToRideMapsTestLongestPath extends TicketToRideMaps { // this is your game class defined in ggg.game.php
    function __construct() {
        // parent::__construct();
        include '../material.inc.php';// this is how this normally included, from constructor
    }

    function getClaimedRoutes($playerId = null) {
        $routes = [];
        if ($playerId === 1) {
            $routes = [
                86, //25 to 28, 
                50, //10 to 28, 
                61, //13 to 28, 
                60, //13 to 15, 
                37, //7 to 28, 
                32, //7 to 12,
            ];
        } else if ($playerId === 2) {
            $routes = [
                12, // 3 => 'Calgary', to 32 => 'Seattle',
                90, // 32 => 'Seattle' to 25 => 'Portland',
                88, // 25 => 'Portland' to 30 => 'San Francisco',,
                68, // 30 => 'San Francisco' to 15 => 'Los Angeles',
                45, // 15 => 'Los Angeles' to 9 => 'El Paso',
                44, // 9 => 'El Paso' to 11 => 'Houston',
                53, // 11 => 'Houston' to 19 => 'New Orleans',
                4, // 19 => 'New Orleans' to 1 => 'Atlanta',
                1, // 1 => 'Atlanta' to 4 => 'Charleston',
                3, // 1 => 'Atlanta' to 18 => 'Nashville',
                75, // 18 => 'Nashville' to 27 => 'Saint Louis',
                21, // 27 => 'Saint Louis' to 5 => 'Chicago',
                20, // 5 => 'Chicago' to 24 => 'Pittsburgh',
                77, // 24 => 'Pittsburgh' to 20 => 'New York',
            ];
        }
        return array_map(fn($route) => new ClaimedRoute(['route_id' => $route, 'player_id' => $playerId]), $routes);
    }

    // class tests
    function testLongestPath() {

        $longestPath = $this->getLongestPath(1);

        $expected = 13;
        $equal = $longestPath->length == $expected;

        if ($equal) {
            echo "Test1: PASSED\n";
        } else {
            echo "Test1: FAILED\n";
            echo "Expected: $expected, value: $longestPath->length\n";
        }
    }

    function testLongestPathWithFork() {

        $longestPath = $this->getLongestPath(2);

        $expected = 41;
        $equal = $longestPath->length == $expected;

        if ($equal) {
            echo "Test2: PASSED\n";
        } else {
            echo "Test2: FAILED\n";
            echo "Expected: $expected, value: $longestPath->length\n";
        }
    }

    function testAll() {
        $this->testLongestPath();
        $this->testLongestPathWithFork();
    }
}

$test1 = new TicketToRideMapsTestLongestPath();
$test1->testAll();