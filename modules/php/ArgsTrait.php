<?php

namespace Bga\Games\TicketToRide;

trait ArgsTrait {
    
//////////////////////////////////////////////////////////////////////////////
//////////// Game state arguments
////////////

    /*
        Here, you can create methods defined as "game state arguments" (see "args" property in states.inc.php).
        These methods function is to return some additional information that is specific to the current
        game state.
    */

    function argChooseInitialDestinationsOld() {
        $playersIds = $this->getPlayersIds();
        
        $private = [];

        foreach($playersIds as $playerId) {
            $private[$playerId] = [
                'destinations' => $this->getPickedDestinationCards($playerId),
            ];
        }

        return [
            'minimum' => $this->getMap()->getInitialDestinationMinimumKept($this->getExpansionOption()),
            '_private' => $private,
        ];
        
    }
}