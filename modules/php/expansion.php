<?php

trait ExpansionTrait {
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
                foreach($this->DESTINATIONS[2] as $typeArg => $destination) {
                    $destinations[] = [ 'type' => 2, 'type_arg' => $typeArg, 'nbr' => 1];
                }
                break;
            case 2:
            case 3:
                foreach([2, 3] as $type) {
                    foreach($this->DESTINATIONS[$type] as $typeArg => $destination) {
                        if ($expansion != 3 || in_array($destination->from, BIG_CITIES) || in_array($destination->to, BIG_CITIES)) {
                            $destinations[] = [ 'type' => $type, 'type_arg' => $typeArg, 'nbr' => 1];
                        }
                    }
                }
                break;
            default:
                foreach($this->DESTINATIONS[1] as $typeArg => $destination) {
                    $destinations[] = [ 'type' => 1, 'type_arg' => $typeArg, 'nbr' => 1];
                }
                break;
        }

        return $destinations;

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
     * Return the number of destinations cards shown at the beginning.
     */
    function getInitialDestinationCardNumber() {
        switch ($this->getExpansionOption()) {
            case 2:
                return 5;
            case 3:
                return 4;
            default:
                return 3;
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
    
}