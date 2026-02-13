<?php

use Bga\Games\TicketToRideEurope\Game;
use Bga\Games\TicketToRideEurope\Objects\ClaimedRoute;

define("APP_GAMEMODULE_PATH", "../misc/"); // include path to stubs, which defines "table.game.php" and other classes
require_once ('../tickettoride.game.php');

class TicketToRideTestDestinationCompleted extends Game { // this is your game class defined in ggg.game.php
    private ?string $forcedMapCode = null;

    function __construct() {
        // parent::__construct();
        include '../material.inc.php';// this is how this normally included, from constructor
    }

    function getMapCode(): string {
        return $this->forcedMapCode ?? parent::getMapCode();
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
        } else if ($playerId === 2) {
            $routes = [
                86, //25 to 28, 
                50, //10 to 28, 
                61, //13 to 28, 
                60, //13 to 15, 
                37, //7 to 28, 
                32, //7 to 12,
                35, //7 to 23
            ];
            return array_map(function($route) use ($playerId) {
                return new ClaimedRoute(['route_id' => $route, 'player_id' => $playerId]);
            }, $routes);
        } else if ($playerId === 3) {
            $routes = [
                48, //9 to 31, 
                99, //7 to 31
            ];
            return array_map(function($route) use ($playerId) {
                return new ClaimedRoute(['route_id' => $route, 'player_id' => $playerId]);
            }, $routes);
        } else if ($playerId === 4) {
            $routes = [
                50, //10 to 28, 
                100, //3 to 28
            ];
            return array_map(function($route) use ($playerId) {
                return new ClaimedRoute(['route_id' => $route, 'player_id' => $playerId]);
            }, $routes);
        } else if ($playerId === 5) {
            $routes = [
                29, // Bern to Neuchatel
                88, // Neuchatel to La Chaux-de-Fonds
                76, // La Chaux-de-Fonds to France
                26, // Bern to Luzern
                25, // Luzern to Olten
                1, // Olten to Zurich
                2, // St Gallen to Zurich
                79, // St Gallen to Osterreich
            ];
            return array_map(function($route) use ($playerId) {
                return new ClaimedRoute(['route_id' => $route, 'player_id' => $playerId]);
            }, $routes);
        }
        return [];
    }

    // class tests
    function testDestinationCompletedNo() {

        $result = $this->mapManager->getDestinationRoutes(1, $this->getMap()->destinations[1][20]);

        $equal = $result == null;

        if ($equal) {
            echo "Test1: PASSED\n";
        } else {
            echo "Test1: FAILED\n";
            echo "Expected: null, value: not null\n";
        }
    }

    function testDestinationCompletedYes() {

        $result = $this->mapManager->getDestinationRoutes(2, $this->getMap()->destinations[1][20]);

        $equal = $result != null;

        if ($equal) {
            echo "Test2: PASSED\n";
        } else {
            echo "Test2: FAILED\n";
            echo "Expected: not null, value: null\n";
        }
    }

    function testDestinationCompletedYes2() {
        $result = $this->mapManager->getDestinationRoutes(3, $this->getMap()->destinations[1][7]);

        $equal = $result != null;

        if ($equal) {
            echo "Test3: PASSED\n";
        } else {
            echo "Test3: FAILED\n";
            echo "Expected: not null, value: null\n";
        }
    }

    function testDestinationCompletedYes3() {
        $result = $this->mapManager->getDestinationRoutes(4, $this->getMap()->destinations[1][3]);

        $equal = $result != null;

        if ($equal) {
            echo "Test4: PASSED\n";
        } else {
            echo "Test4: FAILED\n";
            echo "Expected: not null, value: null\n";
        }
    }

    function testMultiToDestinationUsesHighestScoringTo() {
        $this->forcedMapCode = 'switzerland';
        unset($this->map);

        $destination = (object)[
            'from' => 4,
            'to' => [-1, -3],
            'points' => [5, 11],
        ];

        $result = $this->mapManager->getDestinationRoutes(5, $destination);
        $lastRoute = $result[count($result) - 1] ?? null;
        $equal = $lastRoute !== null && $lastRoute->id == 79;

        if ($equal) {
            echo "Test5: PASSED\n";
        } else {
            echo "Test5: FAILED\n";
            echo "Expected route ending at Osterreich, value: ".($lastRoute === null ? 'null' : $lastRoute->id)."\n";
        }
    }

    function testAll() {
        $this->testDestinationCompletedNo();
        $this->testDestinationCompletedYes();
        $this->testDestinationCompletedYes2();
        $this->testDestinationCompletedYes3();
        $this->testMultiToDestinationUsesHighestScoringTo();
    }
}

$test1 = new TicketToRideTestDestinationCompleted();
$test1->testAll();
