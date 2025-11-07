<?php

trait SettingsTrait {
    function getExpansionOption() {
        return intval($this->getGameStateValue(EXPANSION1910));
    }
    
    /**
     * List the destination tickets that will be used for the game.
     */
    function getDestinationToGenerate() {
        $destinations = [];
        $expansion = $this->getExpansionOption();

        switch ($expansion) {
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
                    if ($expansion != 3 || in_array($destination->from, BIG_CITIES) || in_array($destination->to, BIG_CITIES)) {
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
    
    /**
     * Return if Globetrotter bonus card is used for the game.
     */
    function isGlobetrotterBonusActive() {
        return in_array($this->getExpansionOption(), [1, 2]);
    }
    
    /**
     * Return if Longest Path bonus card is used for the game.
     */
    function isLongestPathBonusActive() {
        return in_array($this->getExpansionOption(), [0, 2]);
    }
    
    /**
     * Return the number of destinations cards shown at the beginning, for each deck.
     */
    function getInitialDestinationPick() {
        switch ($this->getExpansionOption()) {
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
            case 2:
                return 4;
            case 3:
                return 4;
            default:
                return 3;
        }
    }

    function getBigCities() {
        return $this->getExpansionOption() == 3 ?
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

    function getPreloadImages() {
        return $this->getExpansionOption() > 0 ? ['destinations-1-1.jpg', 'destinations-1-2.jpg'] : ['destinations-1-0.jpg'];
    }
    
}