<?php
declare(strict_types=1);

namespace Bga\Games\TicketToRide;

use Bga\GameFrameworkPrototype\Helpers\Arrays;

class BuildingManager {
    protected \Bga\GameFramework\Bga $bga;

    function __construct(
        protected Game $game,
    ) {
        $this->bga = $game->bga;
    }


    /**
     * Return the number of remaining stations for a player.
     */
    function getRemainingStations(int $playerId): ?int {
        $defaultStations = $this->game->getMap()->stations;
        if ($defaultStations === null) {
            return null;
        }
        return $defaultStations - count($this->getPlacedStations($playerId));
    }

    /**
     * Return placed buildings on cities.
     * 
     * @return PlacedBuilding[]
     */
    function getPlacedBuildings(?int $playerId = null, ?int $buildingType = null): array {
        $sql = "SELECT `city_id`, `player_id`, `building_type` FROM `placed_buildings` ";
        if ($playerId !== null) {
            $sql .= "WHERE `player_id` = $playerId ";
        }
        if ($buildingType !== null) {
            $sql .= ($playerId !== null ? 'AND' : 'WHERE')." `building_type` = $buildingType ";
        }
        $dbResults = $this->game->getCollectionFromDB($sql);
        return Arrays::map(array_values($dbResults), fn($dbResult) => new \PlacedBuilding($dbResult));
    }

    /**
     * Return placed stations on cities.
     * 
     * @return PlacedBuilding[]
     */
    function getPlacedStations(?int $playerId = null): array {
        if ($this->game->getMap()->stations === null) {
            return [];
        }
        return $this->getPlacedBuildings($playerId, STATION);
    }

    /**
     * Return the cities where a station can be placed.
     * 
     * @return City[]
     */
    function claimableStations(): array {
        $cities = $this->game->getMap()->cities;
        $placedStations = $this->getPlacedStations(null);
        $availableCities = [];
        foreach ($cities as $cityId => $city) {
            if (!Arrays::some($placedStations, fn($placedStation) => $placedStation->cityId == $cityId)) {
                $city->id = $cityId;
                $availableCities[] = $city;
            }
        }

        return $availableCities;
    }

    /**
     * Return if the player can pay for a station, given the cost (placed stations + 1) and a selected color
     * 
     * @param TrainCar[] $trainCarsHand
     * @return int[]|null
     */
    function canPayForStation(array $trainCarsHand, int $cardCost, ?int $color = null): ?array {
        $colorsToTest = $color !== null ? [$color] : [0, 1,2,3,4,5,6,7,8];
        $locomotiveCards = array_filter($trainCarsHand, fn($card) => $card->type == 0);

        foreach ($colorsToTest as $color) {
            $colorCards = $color == 0 ? [] : array_filter($trainCarsHand, fn($card) => $card->type == $color);

            $colorCardCount = min($cardCost, count($colorCards));
            $locomotiveCardsCount = min($cardCost - $colorCardCount, count($locomotiveCards));

            if ($colorCardCount + $locomotiveCardsCount >= $cardCost) {
                return array_merge(
                    // first color cards
                    array_slice($colorCards, 0, $colorCardCount),
                    // then remaining locomotives
                    array_slice($locomotiveCards, 0, $locomotiveCardsCount)
                );
            }
        }

        return null;

    }

    function applyBuildStation(int $playerId, int $cityId, int $color): void {
        $cardCost = 4 - $this->getRemainingStations($playerId);
        
        $trainCarsHand = $this->game->trainCarManager->getPlayerHand($playerId);
        $cardsToRemove = $this->canPayForStation($trainCarsHand, $cardCost, $color);

        $this->game->trainCarManager->trainCars->moveCards(array_map(fn($card) => $card->id, $cardsToRemove), 'discard');

        // save built station
        $this->game->DbQuery("INSERT INTO `placed_buildings` (`city_id`, `player_id`, `building_type`) VALUES ($cityId, $playerId, ".STATION.")");

        $this->bga->notify->all('builtStation', clienttranslate('${player_name} builds a Train Station on ${city_name} with ${number} train car(s) : ${colors}'), [
            'playerId' => $playerId,
            'player_name' => $this->game->getPlayerNameById($playerId),
            'city' => $this->game->getMap()->cities[$cityId],
            'city_name' => $this->game->getCityName($cityId),
            'number' => $cardCost,
            'removeCards' => $cardsToRemove,
            'colors' => array_map(fn($card) => $card->type, $cardsToRemove),
        ]);

        $this->bga->playerStats->inc('builtStations', 1, $playerId, updateTableStat: true);
    }

    /**
     * Find the best use for stations, at the end of a game, to complete
     * 
     * @param PlacedBuilding[] $stations
     * @param Destination[] $uncompletedDestinations
     */
    function useStations(int $playerId, array $stations, array $uncompletedDestinations): array {
        $allRoutes = $this->game->getClaimedRoutes();
        $playerRoutes = array_values(array_filter($allRoutes, fn($route) => $route->playerId == $playerId));

        $mapRoutes = $this->game->getMap()->routes;

        $potentialRoutesPerStation = [];
        foreach ($stations as $station) {
            $potentialRoutesPerStation[$station->cityId] = array_values(array_filter($allRoutes, fn($route) => 
                $route->playerId != $playerId && in_array($station->cityId, [$mapRoutes[$route->routeId]->from, $mapRoutes[$route->routeId]->to])
            ));
        }
        $citiesIds = array_keys($potentialRoutesPerStation);
        $combinations = $this->getArrayCombinations(array_values(array_filter($potentialRoutesPerStation, fn($routesForStation) => count($routesForStation) > 0)));

        // [points of completed objectives, array of completed destinations, array of completed destinations routes, array of completed destinations used stations]
        $bestCombinationResult = [0, [], [], []];
        foreach ($combinations as $combination) {
            $combinationResult = $this->getCombinationResult($uncompletedDestinations, $playerRoutes, $combination, $citiesIds);
            if ($combinationResult[0] > $bestCombinationResult[0]) {
                $bestCombinationResult = $combinationResult;
            }
        }
        
        foreach ($bestCombinationResult[1] as $index => $destination) {
            $this->game->destinationManager->markCompletedDestination($playerId, $destination, $bestCombinationResult[2][$index], $bestCombinationResult[3][$index]);
        }

        return $bestCombinationResult;
    }

    /**
     * Return the result of a combination of possible station usage.
     */
    function getCombinationResult(array $uncompletedDestinations, array $playerRoutes, array $opponentRoutes, array $stations) {
        $routes = array_merge($playerRoutes, $opponentRoutes);
        $opponentRoutesIds = Arrays::map($opponentRoutes, fn($opponentRoute) => $opponentRoute->routeId);

        $points = 0;
        $completedDestinations = [];
        $completedDestinationsRoutes = [];
        $completedDestinationsStations = [];

        foreach ($uncompletedDestinations as $destination) {
            $withRoutes = $this->game->getShortestRoutesToLinkCitiesOrCountries($routes, $destination->from, $destination->to);
            if ($withRoutes !== null) {
                $completedDestinations[] = $destination;
                $completedDestinationsRoutes[] = $withRoutes;
                $destinationStations = [];
                foreach ($withRoutes as $route) {
                    $index = array_search($route->id, $opponentRoutesIds);
                    if ($index !== false) {
                        $destinationStations[] = $stations[$index];
                    }
                }
                $completedDestinationsStations[] = $destinationStations;
                $points += $destination->points;
            }
        }

        return [$points, $completedDestinations, $completedDestinationsRoutes, $completedDestinationsStations];
    }

    /**
     * Get the possible array combinations, to find all possibilities of stations usage.
     */
    function getArrayCombinations($arrays, $currentIndex = 0, $currentCombination = []) {
        if ($currentIndex == count($arrays)) {
            return [$currentCombination];
        }
    
        $result = [];
    
        foreach ($arrays[$currentIndex] as $value) {
            $nextCombination = array_merge($currentCombination, [$value]);
            $result = array_merge($result, $this->getArrayCombinations($arrays, $currentIndex + 1, $nextCombination));
        }
    
        return $result;
    }
}
