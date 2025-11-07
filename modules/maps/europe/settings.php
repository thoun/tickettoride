<?php

trait SettingsTrait {
    function getExpansionOption() {
        return 0; //intval($this->getGameStateValue(EXPANSION1912));
    }
    
    /**
     * List the destination tickets that will be used for the game.
     */
    function getDestinationToGenerate() {
        $destinationsBig = [];
        $destinations = [];
        
        $big = getBigDestinations();
        $small = getSmallDestinations();
        foreach ($big as $typeArg => $destination) {
            $destinationsBig[] = [ 'type' => 2, 'type_arg' => $typeArg, 'nbr' => 1];
        }
        foreach ($small as $typeArg => $destination) {
            $destinations[] = [ 'type' => 1, 'type_arg' => $typeArg, 'nbr' => 1];
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
        return [
            'deckbig' => 1, 
            'deck' => 3,
        ];
    }
    
    /**
     * Return the minimum number of destinations cards to keep at the beginning.
     */
    function getInitialDestinationMinimumKept() {
       return 2;
    }
    
    /**
     * Return the number of destinations cards shown at pick destination action.
     */
    function getAdditionalDestinationCardNumber() {
        return 3;
    }

    function getBigCities() {
        return [];
    }

    function getPreloadImages() {
        return ['destinations-1-0.jpg', 'destinations-2-0.jpg'];
    }
    
}