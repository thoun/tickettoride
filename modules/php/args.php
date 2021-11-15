<?php

trait ArgsTrait {
    
//////////////////////////////////////////////////////////////////////////////
//////////// Game state arguments
////////////

    /*
        Here, you can create methods defined as "game state arguments" (see "args" property in states.inc.php).
        These methods function is to return some additional information that is specific to the current
        game state.
    */

    function argChooseInitialDestinations() {
        $playersIds = $this->getPlayersIds();
        
        $private = [];

        foreach($playersIds as $playerId) {
            $private[$playerId] = [
                'destinations' => $this->pickInitialDestinationCards($playerId),
            ];
        }

        $destinations = $this->getPickedDestinationCards($playerId);

        return [
            'minimum' => 2,
            '_private' => $private,
        ];
        
    }

    function argChooseAdditionalDestinations() {
        $playerId = intval(self::getActivePlayerId());

        $destinations = $this->getPickedDestinationCards($playerId);

        return [
            'minimum' => 1,
            '_private' => [          // Using "_private" keyword, all data inside this array will be made private
                'active' => [       // Using "active" keyword inside "_private", you select active player(s)
                    'destinations' => $destinations,   // will be send only to active player(s)
                ]
            ],
        ];
    }

    function argChooseAction() {        
        $playerId = intval(self::getActivePlayerId());

        $trainCarsHand = $this->getTrainCarsFromDb($this->trainCars->getCardsInLocation('hand', $playerId));
        $remainingTrainCars = $this->getRemainingTrainCarsCount($playerId);

        $possibleRoutes = $this->claimableRoutes($playerId, $trainCarsHand, $remainingTrainCars);
        $maxHiddenCardsPick = min(2, $this->getRemainingTrainCarCardsInDeck(true));
        $maxDestinationsPick = min(ADDITIONAL_DESTINATION_CARD_PICK, $this->getRemainingDestinationCardsInDeck());

        return [
            'possibleRoutes' => $possibleRoutes,
            'maxHiddenCardsPick' => $maxHiddenCardsPick,
            'maxDestinationsPick' => $maxDestinationsPick,
        ];
    }

    function argDrawSecondCard() {
        $maxHiddenCardsPick = min(1, $this->getRemainingTrainCarCardsInDeck(true));
        $availableVisibleCards = $this->getVisibleTrainCarCards(true);

        return [
            'maxHiddenCardsPick' => $maxHiddenCardsPick,
            'availableVisibleCards' => $availableVisibleCards,
        ];

    }
}