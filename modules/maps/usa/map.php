<?php

require_once(__DIR__.'/../../php/objects/map.php');

require_once(__DIR__.'/cities.php');
require_once(__DIR__.'/routes.php');
require_once(__DIR__.'/destinations.php');
require_once(__DIR__.'/settings.php');

class UsaMap extends Map {
    public function __construct() {
        parent::__construct(
            getCities(),
            /**
             * Route on the map. 
             * For double routes, there is 2 instances of Route.
             * For cities (from/to), it's always low id to high id.
             */
            getRoutes(),
            /**
             * List of DestinationCard.
             */
            getAllDestinations(),
            /**
             * Points scored for claimed routes.
             */
            [
                1 => 1,
                2 => 2,
                3 => 4,
                4 => 7,
                5 => 10,
                6 => 15,
                8 => 21,
            ]
        );

        $this->hasExpansion = true; // 0 => base game, 1 => 1910, 2 => mega game, 3 => big cities

        $this->bigCities = [
            5, // Chicago',
            6, // Dallas',
            11, // Houston',
            15, // Los Angeles',
            16, // Miami',
            20, // New York',
            32, // Seattle',
        ];
    }
    
    /**
     * Return if Globetrotter bonus card is used for the game.
     */
    function isGlobetrotterBonusActive(int $expansionValue): bool {
        return in_array($expansionValue, [1, 2]);
    }
    
    /**
     * Return if Longest Path bonus card is used for the game.
     */
    function isLongestPathBonusActive(int $expansionValue): bool {
        return in_array($expansionValue, [0, 2]);
    }
    
    /**
     * Return the number of destinations cards shown at the beginning, for each deck.
     */
    function getInitialDestinationPick(int $expansionValue): array {
        switch ($expansionValue) {
            case 2:
                return ['deck' => 5];
            case 3:
                return ['deck' => 4];
            default:
                return ['deck' => 3];
        }
    }
    
    /**
     * Return the minimum number of destinations cards to keep at the beginning.
     */
    function getInitialDestinationMinimumKept(int $expansionValue): int {
        switch ($expansionValue) {
            case 2:
                return 3;
            default:
                return 2;
        }
    }
    
    /**
     * Return the number of destinations cards shown at pick destination action.
     */
    function getAdditionalDestinationCardNumber(int $expansionValue): int {
        switch ($expansionValue) {
            case 2:
                return 4;
            case 3:
                return 4;
            default:
                return 3;
        }
    }

    function getBigCities(int $expansionValue): array {
        return $expansionValue == 3 ?
        [
            new BigCity(1226, 479, 70), // Chicago
            new BigCity(1007, 903, 64), // Dallas
            new BigCity(1046, 1022, 79), // Houston
            new BigCity(86, 904, 107), // Los Angeles
            new BigCity(1633, 1066, 62), // Miami
            new BigCity(1642, 359, 93), // New York
            new BigCity(38, 234, 69), // Seattle
        ]
        : [];
    }

    function getPreloadImages(int $expansionValue): array {
        return $expansionValue > 0 ? ['destinations-1-1.jpg', 'destinations-1-2.jpg'] : ['destinations-1-0.jpg'];
    }
    
    /**
     * List the destination tickets that will be used for the game.
     */
    function getDestinationToGenerate(int $expansionValue): array {
        $destinations = [];

        switch ($expansionValue) {
            case 1:
                $expansion1910 = get1910Destinations();
                foreach($expansion1910 as $typeArg => $destination) {
                    $destinations[] = [ 'type' => 1, 'type_arg' => $typeArg, 'nbr' => 1];
                }
                break;
            case 2:
            case 3:
                $allExpansionDestinations = get1910Destinations() + getMegaDestinations();
                foreach($allExpansionDestinations as $typeArg => $destination) {
                    if ($expansionValue != 3 || in_array($destination->from, $this->bigCities) || in_array($destination->to, $this->bigCities)) {
                        $destinations[] = [ 'type' => 1, 'type_arg' => $typeArg, 'nbr' => 1];
                    }
                }
                break;
            default:
            $base = getBaseDestinations();
                foreach($base as $typeArg => $destination) {
                    $destinations[] = [ 'type' => 1, 'type_arg' => $typeArg, 'nbr' => 1];
                }
                break;
        }

        return [
            'deck' => $destinations
        ];
    }
}

function getMap() {
    return new UsaMap();
}

?>