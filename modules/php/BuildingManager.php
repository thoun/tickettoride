<?php
declare(strict_types=1);

namespace Bga\Games\TicketToRide;

use Bga\GameFrameworkPrototype\Helpers\Arrays;
use Bga\GameFramework\UserException;
use Bga\Games\TicketToRide\Objects\City;
use Bga\Games\TicketToRide\Objects\Destination;
use Bga\Games\TicketToRide\Objects\PlacedBuilding;
use Bga\Games\TicketToRide\Objects\Route;
use Bga\Games\TicketToRide\Objects\TrainCar;

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

    function getPlacedCityMarkers(?int $playerId = null): array {
        return $this->game->getMap()->cityMarkers === null ? [] : $this->getPlacedBuildings($playerId, CITY_MARKER);
    }

    function getRemainingCityMarkers(int $playerId): ?int {
        $markers = $this->game->getMap()->cityMarkers;
        return $markers === null ? null : $markers - count($this->getPlacedCityMarkers($playerId));
    }

    /** Uncontrolled endpoints where a player may place a marker after claiming this route. */
    function getCityMarkerPlacementCityIds(int $playerId, int $routeId): array {
        if (($this->getRemainingCityMarkers($playerId) ?? 0) <= 0
            || !Arrays::some($this->game->getClaimedRoutes(), fn($route) => $route->routeId === $routeId && $route->playerId === $playerId)) {
            return [];
        }
        $route = $this->game->mapManager->getAllRoutes()[$routeId];
        return array_values(array_intersect([$route->from, $route->to], $this->getUncontrolledCityIds()));
    }

    function placeCityMarker(int $playerId, int $routeId, int $cityId, int $color): void {
        if (!in_array($cityId, $this->getCityMarkerPlacementCityIds($playerId, $routeId), true)) {
            throw new UserException('You cannot place a City Marker in this city.');
        }
        if ($color < 0 || $color > 8) {
            throw new UserException('Invalid card color.');
        }
        $cardsToRemove = $this->canPayForStation($this->game->trainCarManager->getPlayerHand($playerId), 2, $color);
        if ($cardsToRemove === null) {
            throw new UserException('Not enough cards to place a City Marker.');
        }

        $this->game->trainCarManager->trainCars->moveCards(array_map(fn($card) => $card->id, $cardsToRemove), 'discard');
        $this->game->DbQuery("INSERT INTO `placed_buildings` (`city_id`, `player_id`, `building_type`) VALUES ($cityId, $playerId, ".CITY_MARKER.")");
        $this->bga->notify->all('cityMarkerPlaced', clienttranslate('${player_name} places a City Marker in ${city_name} with these Train Car cards: ${colors}'), [
            'playerId' => $playerId,
            'player_name' => $this->game->getPlayerNameById($playerId),
            'cityId' => $cityId,
            'city_name' => $this->game->getCityName($cityId),
            'remainingCityMarkers' => $this->getRemainingCityMarkers($playerId),
            'removeCards' => $cardsToRemove,
            'colors' => array_map(fn($card) => $card->type, $cardsToRemove),
        ]);
        $this->game->trainCarManager->checkVisibleTrainCarCards();
    }

    /** @return array<int, int> Route points awarded to each player according to city control. */
    function getRoutePointAwards(int $playerId, Route $route, int $points): array {
        $awards = [];
        foreach ($this->getPlacedCityMarkers() as $marker) {
            if ($marker->cityId === $route->from || $marker->cityId === $route->to) {
                $awards[$marker->playerId] = ($awards[$marker->playerId] ?? 0) + $points;
            }
        }

        return empty($awards) ? [$playerId => $points] : $awards;
    }

    /** City ids that are not controlled by any player. */
    function getUncontrolledCityIds(): array {
        $controlled = array_map(fn($marker) => $marker->cityId, $this->getPlacedCityMarkers());
        return array_values(array_diff(array_keys($this->game->getMap()->cities), $controlled,
            $this->game->getMap()->getForbiddenCityMarkerCityIds($this->game)));
    }

    function placeStartingCityMarker(int $playerId, int $cityId): void {
        if ($this->game->getMap()->cityMarkers === null || !empty($this->getPlacedCityMarkers($playerId))) {
            throw new UserException('You cannot choose a starting city.');
        }
        if (!in_array($cityId, $this->getUncontrolledCityIds(), true)) {
            throw new UserException('This city is not available.');
        }

        $this->game->DbQuery("INSERT INTO `placed_buildings` (`city_id`, `player_id`, `building_type`) VALUES ($cityId, $playerId, ".CITY_MARKER.")");
        $this->bga->notify->all('cityMarkerPlaced', clienttranslate('${player_name} chooses ${city_name} as their starting city'), [
            'playerId' => $playerId,
            'player_name' => $this->game->getPlayerNameById($playerId),
            'cityId' => $cityId,
            'city_name' => $this->game->getCityName($cityId),
            'remainingCityMarkers' => $this->getRemainingCityMarkers($playerId),
        ]);
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
        return Arrays::map(array_values($dbResults), fn($dbResult) => new PlacedBuilding($dbResult));
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
     * @return TrainCar[]|null
     */
    function canPayForStation(array $trainCarsHand, int $cardCost, ?int $color = null): ?array {
        $colorsToTest = $color !== null ? [$color] : [0, 1,2,3,4,5,6,7,8];
        $locomotiveCards = array_filter($trainCarsHand, fn($card) => $card->type == 0);

        foreach ($colorsToTest as $colorToTest) {
            $colorCards = $colorToTest == 0 ? [] : array_filter($trainCarsHand, fn($card) => $card->type == $colorToTest);

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
            'cityId' => $cityId,
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
        $playerRoutes = array_values(array_filter($allRoutes, fn($route) => $route->playerId == $playerId || $route->playerId === -1));

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
            $withRoutes = $this->game->mapManager->getShortestRoutesToLinkCitiesOrCountries($routes, $destination->from, $destination->to);
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
    function getArrayCombinations(array $arrays, int $currentIndex = 0, array $currentCombination = []) {
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
