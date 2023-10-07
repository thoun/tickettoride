<?php

trait SettingsTrait {
    function getExpansionOption() {
        return intval($this->getGameStateValue(EXPANSION1912));
    }
    
    /**
     * List the destination tickets that will be used for the game.
     */
    function getDestinationToGenerate() {
        $destinationsBig = [];
        $destinations = [];
        $expansion = $this->getExpansionOption();

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
                    if (in_array($destination->from, BIG_CITIES) || in_array($destination->to, BIG_CITIES)) {
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
    
    /**
     * Return if Globetrotter bonus card is used for the game.
     */
    function isGlobetrotterBonusActive() {
        return false;
    }
    
    /**
     * Return if Longest Path bonus card is used for the game.
     */
    function isLongestPathBonusActive() {
        return true;
    }
    
    /**
     * Return the number of destinations cards shown at the beginning, for each deck.
     */
    function getInitialDestinationPick() {
        switch ($this->getExpansionOption()) {
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
    function getInitialDestinationMinimumKept() {
        switch ($this->getExpansionOption()) {
            case 2:
                return 3;
            default:
                return 2;
        }
    }
    
    /**
     * Return the number of destinations cards shown at pick destination action.
     */
    function getAdditionalDestinationCardNumber() {
        switch ($this->getExpansionOption()) {
            case 3:
                return 4;
            default:
                return 3;
        }
    }

    function getBigCities() {
        return $this->getExpansionOption() == 3 ?
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

    function getPreloadImages() {
        switch ($this->getExpansionOption()) {
            case 1:
            case 2:
                return ['destinations-1-1.jpg', 'destinations-2-1.jpg'];
            case 3:
                return ['destinations-1-1.jpg'];
            default:
                return ['destinations-1-0.jpg', 'destinations-2-0.jpg'];
        }
    }
    
}
