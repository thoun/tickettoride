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
        $destinations = $this->destinationDeck->getPickedCards();

        return [
           'destinations' => $destinations,
        ];
    }

    function argChooseAdditionalDestinations() {
        $destinations = $this->destinationDeck->getPickedCards();

        return [
           'destinations' => $destinations,
        ];
    }

    function argChooseAction() {
        $possibleRoutes = []; // TODO 
        $maxHiddenCardsPick = min(2, $this->trainCarDeck->getRemainingCardsInDeck(true));
        $availableDestinations = $this->destinationDeck->getRemainingCardsInDeck() > 0;

        return [
            'possibleRoutes' => $possibleRoutes,
            'maxHiddenCardsPick' => $maxHiddenCardsPick,
            'availableDestinations' => $availableDestinations,
        ];
    }

    function argDrawSecondCard() {
        $maxHiddenCardsPick = min(1, $this->trainCarDeck->getRemainingCardsInDeck(true));
        $availableVisibleCards = $this->trainCarDeck->getVisibleCards(true);

        return [
            'maxHiddenCardsPick' => $maxHiddenCardsPick,
            'availableVisibleCards' => $availableVisibleCards,
        ];

    }
}