<?php

class Map {
    public string $code;
    public ?int $expansion = null;
    public ?array $bigCities = null;

    public int $numberOfLocomotiveCards = 14;
    public int $numberOfColoredCards = 12;
    public int $initialTrainCarCardsInHand = 4; // Number of train car cards in hand, for each player, at the beginning of the game.
    public bool $visibleLocomotivesCountsAsTwoCards = true; // Says if it is possible to take only one visible locomotive.
    public bool $canOnlyUseLocomotivesInTunnels = false; // Says locomotives are reserved to tunnels.
    public ?int $resetVisibleCardsWithLocomotives = 3; // Resets visible cards when 3 locomotives are visible (null means disabled)
    public int $trainCarsNumberToStartLastTurn = 2; // 2 means 0, 1, or 2 will start last turn
    public int $trainCarsPerPlayer = 45; // trains car tokens per player at the beginning of the game
    public int $additionalDestinationMinimumKept = 1; // Minimum number of destinations cards to keep at pick destination action.
    public bool $unusedInitialDestinationsGoToDeckBottom = true; // Indicates if unpicked destinations cards go back to the bottom of the deck.
    public bool $unusedAdditionalDestinationsGoToDeckBottom = true; // Indicates if unpicked destinations cards go back to the bottom of the deck.
    public ?int $pointsForLongestPath = 10; // points for maximum longest countinuous path (null means disabled)
    public ?int $pointsForGlobetrotter = 15; // points for maximum completed destinations (null means disabled)
    public int $minimumPlayerForDoubleRoutes = 4; // 4 means 2-3 players cant use double routes

    public function __construct(
        public array $cities,
        public array $routes,
        public array $destinations,
        /**
        * Points scored for claimed routes.
        */
        public array $routePoints =  [
           1 => 1,
           2 => 2,
           3 => 4,
           4 => 7,
           5 => 10,
           6 => 15,
           8 => 21,
        ],
    ) {
    } 
    
    /**
     * Return if Globetrotter bonus card is used for the game.
     */
    function isGlobetrotterBonusActive(int $expansionValue): bool {
        return false;
    }
    
    /**
     * Return if Longest Path bonus card is used for the game.
     */
    function isLongestPathBonusActive(int $expansionValue): bool {
        return true;
    }
    
    /**
     * Return the number of destinations cards shown at the beginning, for each deck.
     */
    function getInitialDestinationPick(int $expansionValue): array {
        return ['deck' => 3];
    }
    
    /**
     * Return the minimum number of destinations cards to keep at the beginning.
     */
    function getInitialDestinationMinimumKept(int $expansionValue): int {
        return 2;
    }
    
    /**
     * Return the number of destinations cards shown at pick destination action.
     */
    function getAdditionalDestinationCardNumber(int $expansionValue): int {
        return 3;
    }

    function getBigCities(int $expansionValue): array {
        return [];
    }

    function getPreloadImages(int $expansionValue): array {
        return ['destinations-1-0.jpg'];
    }
    
    /**
     * List the destination tickets that will be used for the game.
     */
    function getDestinationToGenerate(int $expansionValue): array {
        $destinations = [];

        return [
            'deck' => $destinations
        ];
    }
}
?>