<?php
define("APP_GAMEMODULE_PATH", "../misc/"); // include path to stubs, which defines "table.game.php" and other classes
require_once ('../tickettoride.game.php');

class TicketToRideTestLongestPath extends TicketToRide { // this is your game class defined in ggg.game.php
    function __construct() {
        // parent::__construct();
        include '../material.inc.php';// this is how this normally included, from constructor
    }

    function getClaimedRoutes($playerId = null) {
        if ($playerId === 1) {
            $routes = [
                86, //25 to 28, 
                50, //10 to 28, 
                61, //13 to 28, 
                60, //13 to 15, 
                37, //7 to 28, 
                32, //7 to 12,
            ];
            return array_map(function($route) use ($playerId) {
                return new ClaimedRoute(['route_id' => $route, 'player_id' => $playerId]);
            }, $routes);
        }
        return [];
    }

    // class tests
    function testLongestPath() {

        $longestPath = $this->getLongestPath(1);

        $expected = 13;
        $equal = $longestPath == $expected;

        if ($equal) {
            echo "Test1: PASSED\n";
        } else {
            echo "Test1: FAILED\n";
            echo "Expected: $expected, value: $longestPath\n";
        }
    }

    function testAll() {
        $this->testLongestPath();
    }
}

$test1 = new TicketToRideTestLongestPath();
$test1->testAll();