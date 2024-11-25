<?php

require_once(__DIR__.'/../../php/objects/map.php');

require_once(__DIR__.'/cities.php');
require_once(__DIR__.'/routes.php');
require_once(__DIR__.'/destinations.php');

class EuropeMap extends Map {
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

        $this->unusedInitialDestinationsGoToDeckBottom = false; // Indicates if unpicked destinations cards go back to the bottom of the deck.
        $this->pointsForGlobetrotter = null; // points for maximum completed destinations (null means disabled)

        $this->hasExpansion = true; // 0 => base game, 1 => extended, 2 => mega game, 3 => big cities

        $this->bigCities = [
            2, // Angora',
            3, // Athina',
            5, // Berlin',
            23, // London',
            24, // Madrid',
            26, // Moskva',
            30, // Paris',
            33, // Roma',
            44, // Wien',
        ];
    }
    
    /**
     * Return the number of destinations cards shown at the beginning, for each deck.
     */
    function getInitialDestinationPick(int $expansionValue): array {
        switch ($expansionValue) {
            case 2:
                return [
                    'deckbig' => 2, 
                    'deck' => 5,
                ];
            case 3:
                return [
                    'deckbig' => 0, 
                    'deck' => 5,
                ];
            default:
                return [
                    'deckbig' => 1, 
                    'deck' => 3,
                ];
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
            case 3:
                return 4;
            default:
                return 3;
        }
    }

    function getBigCities(int $expansionValue): array {
        return $expansionValue == 3 ?
        [
            new BigCity(1421, 1061, 72), // Angora
            new BigCity(1182, 1034, 68), // Athina
            new BigCity(785, 330, 60), // Berlin
            new BigCity(263, 336, 69), // London
            new BigCity(64, 987, 73), // Madrid
            new BigCity(1570, 272, 75), // Moskva
            new BigCity(335, 582, 48), // Paris
            new BigCity(733, 903, 54), // Roma
            new BigCity(878, 564, 45), // Wien
        ]
        : [];
    }

    function getPreloadImages(int $expansionValue): array {
        switch ($expansionValue) {
            case 1:
            case 2:
                return ['destinations-1-1.jpg', 'destinations-2-1.jpg'];
            case 3:
                return ['destinations-1-1.jpg'];
            default:
                return ['destinations-1-0.jpg', 'destinations-2-0.jpg'];
        }
    }
    
    /**
     * List the destination tickets that will be used for the game.
     */
    function getDestinationToGenerate(int $expansionValue): array {
        $destinationsBig = [];
        $destinations = [];
        $expansion = $expansionValue;

        switch ($expansion) {
            case 1:
                $baseBig = getBaseBigDestinations();
                $big = get1912BigDestinations(); 

                $bigIdsFromBaseIds = array_keys(array_filter($big, fn($destination) => $this->array_some($baseBig, fn($baseDestination) => 
                    ($destination->from === $baseDestination->from && $destination->to === $baseDestination->to) || 
                    ($destination->from === $baseDestination->to && $destination->to === $baseDestination->from)
                )));


                $baseSmall = getBaseSmallDestinations();
                $small = get1912SmallDestinations(); 

                $smallIdsFromBaseIds = array_keys(array_filter($small, fn($destination) => $this->array_some($baseSmall, fn($baseDestination) => 
                    ($destination->from === $baseDestination->from && $destination->to === $baseDestination->to) || 
                    ($destination->from === $baseDestination->to && $destination->to === $baseDestination->from)
                )));

                $smallIdsExpanded = array_merge($smallIdsFromBaseIds, [
                    102, // Amsterdam	Venezia
                    119, // Bucuresti	Erzurum
                    118, // Bruxelles	Stockholm
                    121, // Cadiz	Frankfurt
                    122, // Danzig	Budapest
                    123, // Dieppe	Kobenhavn
                    124, // Dieppe	Marseille
                    125, // Edinburgh	Essen
                    131, // Lisboa	Cadiz
                    151, // Munchen	Petrograd
                    152, // Munchen	Sarajevo
                    154, // Pamplona	Palermo
                    165, // Riga	Kharkov
                    173, // Sochi	Smyrna
                    174, // Sofia	Kyiv
                    176, // Stockholm	Wilno
                    178, // Venezia	Warszawa
                    179, // Warszawa	Budapest
                    180, // Warszawa	Sevastopol
                ]);

                foreach ($bigIdsFromBaseIds as $typeArg) {
                    $destinationsBig[] = [ 'type' => 2, 'type_arg' => $typeArg, 'nbr' => 1];
                }
                foreach($smallIdsExpanded as $typeArg) {
                    $destinations[] = [ 'type' => 1, 'type_arg' => $typeArg, 'nbr' => 1];
                }
                break;
            case 2:
                $big = get1912BigDestinations();
                $small = get1912SmallDestinations(); 
                foreach ($big as $typeArg => $destination) {
                    $destinationsBig[] = [ 'type' => 2, 'type_arg' => $typeArg, 'nbr' => 1];
                }
                foreach ($small as $typeArg => $destination) {
                    $destinations[] = [ 'type' => 1, 'type_arg' => $typeArg, 'nbr' => 1];
                }
                break;
            case 3:
                $small = get1912SmallDestinations(); 
                foreach($small as $typeArg => $destination) {
                    if (in_array($destination->from, $this->bigCities) || in_array($destination->to, $this->bigCities)) {
                        $destinations[] = [ 'type' => 1, 'type_arg' => $typeArg, 'nbr' => 1];
                    }
                }
                break;
            default:
                $big = getBaseBigDestinations();
                $small = getBaseSmallDestinations();
                foreach ($big as $typeArg => $destination) {
                    $destinationsBig[] = [ 'type' => 2, 'type_arg' => $typeArg, 'nbr' => 1];
                }
                foreach ($small as $typeArg => $destination) {
                    $destinations[] = [ 'type' => 1, 'type_arg' => $typeArg, 'nbr' => 1];
                }
                break;
        }

        return [
            'deckbig' => $destinationsBig,
            'deck' => $destinations, 
        ];
    }

    function array_some(array $array, callable $fn) {
        foreach ($array as $value) {
            if($fn($value)) {
                return true;
            }
        }
        return false;
    }
}

function getMap() {
    return new EuropeMap();
}

?>