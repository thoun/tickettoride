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

    function argChooseInitialDestinationsOld() {
        $playersIds = $this->getPlayersIds();
        
        $private = [];

        foreach($playersIds as $playerId) {
            $private[$playerId] = [
                'destinations' => $this->getPickedDestinationCards($playerId),
            ];
        }

        return [
            'minimum' => 2,
            '_private' => $private,
        ];
        
    }

    function argPrivateChooseInitialDestinations(int $playerId) {
        return [
            'minimum' => 2,
            'destinations' => $this->getPickedDestinationCards($playerId),
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
        // we don't limit claimable routes to the number of remaining train cars, because the players don't understand why they can't claim the route
        // so instead they'll get an error when they try to claim the route, saying they don't have enough train cars left
        $remainingTrainCars = 99; //$this->getRemainingTrainCarsCount($playerId);

        $possibleRoutes = $this->claimableRoutes($playerId, $trainCarsHand, $remainingTrainCars);
        $maxHiddenCardsPick = min(2, $this->getRemainingTrainCarCardsInDeck(true));
        $maxDestinationsPick = min(ADDITIONAL_DESTINATION_CARD_PICK, $this->getRemainingDestinationCardsInDeck());

        $costForRoute = [];
        foreach($possibleRoutes as $possibleRoute) {
            $colorsToTest = $possibleRoute->color > 0 ? [0, $possibleRoute->color] : [0,1,2,3,4,5,6,7,8];
            $costByColor = [];
            foreach($colorsToTest as $colorToTest) {
                $costByColor[$colorToTest] = $this->canPayForRoute($possibleRoute, $trainCarsHand, $remainingTrainCars, $colorToTest);
            }
            $costForRoute[$possibleRoute->id] = array_map(fn($cardCost) => $cardCost == null ? null : array_map(fn($card) => $card->type, $cardCost), $costByColor);
        }

        return [
            'possibleRoutes' => $possibleRoutes,
            'costForRoute' => $costForRoute,
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