<?php

trait ExpansionTrait {
    
    /**
     * List the destination tickets that will be used for the game.
     */
    function getDestinationToGenerate() {
        $destinations = [];

        if (ACTIVATE1910) {
            foreach($this->DESTINATIONS[2] as $typeArg => $destination) {
                $destinations[] = [ 'type' => 2, 'type_arg' => $typeArg, 'nbr' => 1];
            }
        } else {
            foreach($this->DESTINATIONS[1] as $typeArg => $destination) {
                $destinations[] = [ 'type' => 1, 'type_arg' => $typeArg, 'nbr' => 1];
            }
        }

        return $destinations;

    }
    
    /**
     * Return if Globetrotter bonus card is used for the game.
     */
    function isGlobetrotterBonusActive() {
        return ACTIVATE1910;
    }
    
    /**
     * Return if Longest Path bonus card is used for the game.
     */
    function isLongestPathBonusActive() {
        return !ACTIVATE1910;
    }
}