<?php

namespace Bga\Games\TicketToRide\Objects;

use Bga\Games\TicketToRide\Game;

class Map {
    public const LOCOMOTIVE_TUNNEL = 0b01;
    public const LOCOMOTIVE_FERRY = 0b10;

    public string $code;
    public int $width;
    public int $height;
    public ?int $expansion = null;
    public ?array $bigCities = null;
    public array $countriesEndPoints = [];

    public int $numberOfLocomotiveCards = 14;
    public int $numberOfColoredCards = 12;
    public int $initialTrainCarCardsInHand = 4; // Number of train car cards in hand, for each player, at the beginning of the game.
    public bool $visibleLocomotivesCountsAsTwoCards = true; // Says if it is possible to take only one visible locomotive.
    public int $locomotiveUsageRestriction = 0; // Combination of LOCOMOTIVE_TUNNEL / LOCOMOTIVE_FERRY for joker usage. (0 means no restriction)
    public ?int $resetVisibleCardsWithLocomotives = 3; // Resets visible cards when 3 locomotives are visible (null means disabled)
    
    public int $trainCarsPerPlayer = 45; // trains car tokens per player at the beginning of the game
    public int $additionalDestinationMinimumKept = 1; // Minimum number of destinations cards to keep at pick destination action.
    public bool $unusedInitialDestinationsGoToDeckBottom = true; // Indicates if unpicked destinations cards go back to the bottom of the deck.
    public bool $unusedAdditionalDestinationsGoToDeckBottom = true; // Indicates if unpicked destinations cards go back to the bottom of the deck.
    public ?int $pointsForLongestPath = 10; // points for maximum longest countinuous path (null means disabled)
    public ?int $pointsForGlobetrotter = 15; // points for maximum completed destinations (null means disabled)
    public ?int $pointsForMostConnectedCities = null; // points for most connected cities (null means disabled)
    public int $minimumPlayerForDoubleRoutes = 4; // 4 means 2-3 players cant use double routes
    public int $minimumPlayerForTripleRoutes = 4; // 4 means 2-3 players cant use double routes
    public bool $differentLengthRoutesAreDoubleRoutes = true; // Whether parallel routes of different lengths count as a double route.
    public ?string $multilingualPdfRulesUrl = null; // PDF rules URL to display when it's not the base game
    public ?array $rulesDifferences = null; // text summary of rules differences to display when it's not the base game
    public ?int $stations = null;
    public ?array $mandalaPoints = null;
    public ?array $bulletTrainBonusPoints = null;
    public ?array $regionBonusPoints = null;
    public array $completeRegionsCountingDouble = [];
    public bool $ferryCards = false;

    /**
     * @param City[] $cities
     * @param Route[] $routes
     * @param DestinationCard[] $destinations
     */
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

    public function setCode(string $code): void {
        $this->code = $code;

        $dimensions = getimagesize(__DIR__.'/../../../img/'.$code.'/map.webp');
        if ($dimensions === false) {
            throw new \RuntimeException("Unable to read dimensions for map '$code'");
        }

        [$this->width, $this->height] = $dimensions;
    }

    public function areRoutesDouble(object $route, object $otherRoute): bool {
        return $route->id !== $otherRoute->id
            && $route->from === $otherRoute->from
            && $route->to === $otherRoute->to
            && ($this->differentLengthRoutesAreDoubleRoutes || $route->number === $otherRoute->number);
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
     * Return each player's Bullet Train bonus, based on their position on the
     * progression track. Tied players share a rank and still occupy all their
     * positions (for example: 1st, tied 2nd, tied 2nd, 4th).
     *
     * @param array<int, int> $positions position by player id
     * @return array<int, int> bonus points by player id
     */
    function getBulletTrainBonuses(array $positions): array {
        return [];
    }

    /**
     * Return the Regions Bonus for all distinct networks belonging to a player.
     */
    function getRegionsBonus(Game $game, int $playerId): int {
        return $this->getRegionsBonusForNetworks($game->mapManager->getConnectedNetworks($playerId));
    }

    /**
     * Return the total number of Regions counted across a player's distinct networks.
     */
    function getRegionsCount(Game $game, int $playerId): int {
        return array_sum($this->getRegionsCountsForNetworks($game->mapManager->getConnectedNetworks($playerId)));
    }

    /**
     * @param \Bga\Games\TicketToRide\ConnectedNetwork[] $networks
     */
    function getRegionsBonusForNetworks(array $networks): int {
        if ($this->regionBonusPoints === null) {
            return 0;
        }

        $bonus = 0;
        $maximumScoredRegions = max(array_keys($this->regionBonusPoints));
        foreach ($this->getRegionsCountsForNetworks($networks) as $regionCount) {
            $bonus += $this->regionBonusPoints[min($regionCount, $maximumScoredRegions)] ?? 0;
        }

        return $bonus;
    }

    /**
     * @param \Bga\Games\TicketToRide\ConnectedNetwork[] $networks
     * @return int[] region count by distinct network
     */
    private function getRegionsCountsForNetworks(array $networks): array {
        $citiesByRegion = [];
        foreach ($this->cities as $cityId => $city) {
            if ($city->region !== null) {
                $citiesByRegion[$city->region][] = $cityId;
            }
        }

        $regionCounts = [];
        foreach ($networks as $network) {
            $networkCities = array_fill_keys($network->cities, true);
            $connectedRegions = [];
            foreach ($network->cities as $cityId) {
                $region = $this->cities[$cityId]->region ?? null;
                if ($region !== null) {
                    $connectedRegions[$region] = true;
                }
            }

            $regionCount = count($connectedRegions);
            foreach ($this->completeRegionsCountingDouble as $region) {
                if (isset($connectedRegions[$region])
                    && count(array_filter($citiesByRegion[$region], fn($cityId) => !isset($networkCities[$cityId]))) === 0) {
                    $regionCount++;
                }
            }

            $regionCounts[] = $regionCount;
        }

        return $regionCounts;
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

    function setup(Game $game): void {}

    function getMapSpecificData(Game $game): array {
        return [];
    }

    function getPlayerMapSpecificData(Game $game, int $playerId): array {
        return [];
    }
    
    function onClaimRoute(Game $game, int $playerId, Route $route): void {}

    function isLastTurn(Game $game): bool {
        return $game->getLowestTrainCarsCount() <= 2; // 2 means 0, 1, or 2 will start last turn
    }
}
