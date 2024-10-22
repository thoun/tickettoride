<?php

require_once(__DIR__.'/objects/building.php');

trait StationTrait {

    /**
     * Return the number of remaining stations for a player.
     */
    function getRemainingStations(int $playerId) {
        return 3 - count($this->getPlacedStations($playerId));
    }

    /**
     * Return placed buildings on cities.
     */
    function getPlacedBuildings(?int $playerId = null, ?int $buildingType = null) {
        $sql = "SELECT `city_id`, `player_id`, `building_type` FROM `placed_buildings` ";
        if ($playerId !== null) {
            $sql .= "WHERE `player_id` = $playerId ";
        }
        if ($buildingType !== null) {
            $sql .= ($playerId !== null ? 'AND' : 'WHERE')." `building_type` = $buildingType ";
        }
        $dbResults = self::getCollectionFromDB($sql);
        return array_map(fn($dbResult) => new PlacedBuilding($dbResult), array_values($dbResults));
    }

    /**
     * Return placed stations on cities.
     */
    function getPlacedStations(?int $playerId = null) {
        return $this->getPlacedBuildings($playerId, STATION);
    }

    /**
     * Return the cities where a station can be placed.
     */
    function claimableStations() {
        $cities = $this->CITIES;
        $placedStations = $this->getPlacedStations(null);
        $availableCities = [];
        foreach ($cities as $cityId => $city) {
            if (!$this->array_some($placedStations, fn($placedStation) => $placedStation->cityId == $cityId)) {
                $city->id = $cityId;
                $availableCities[] = $city;
            }
        }

        return $availableCities;
    }

    /**
     * Return if the player can pay for a station, given the cost (placed stations + 1) and a selected color
     */
    function canPayForStation(array $trainCarsHand, int $cardCost, ?int $color = null) {
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
  	
    /**
     * Build a station on a city with seleced color
     */
    public function buildStation(int $cityId, int $color) {
        self::checkAction('buildStation');
        
        $this->actBuildStation($cityId, $color);
    }
  	
    /**
     * Build a station on a city with seleced color
     */
    public function actBuildStation(int $cityId, int $color) {
        
        $playerId = intval(self::getActivePlayerId());

        $remainingStations = $this->getRemainingStations($playerId);
        if ($remainingStations <= 0) {
            throw new BgaUserException("No station remaining");
        }

        if ($this->getUniqueIntValueFromDB( "SELECT count(*) FROM `placed_buildings` WHERE `city_id` = $cityId") > 0) {
            throw new BgaUserException("City is already claimed.");
        }
        
        $trainCarsHand = $this->getTrainCarsFromDb($this->trainCars->getCardsInLocation('hand', $playerId));
        $colorAndLocomotiveCards = $this->canPayForStation($trainCarsHand, 4 - $remainingStations, $color);
        
        if ($colorAndLocomotiveCards == null || count($colorAndLocomotiveCards) < 4 - $remainingStations) {
            throw new BgaUserException("Not enough cards to build a station.");
        }

        $possibleStations = $this->claimableStations();
        if (!$this->array_some($possibleStations, fn($possibleStation) => $possibleStation->id == $cityId)) {
            throw new BgaUserException("You can't claim this city");
        }

        $this->applyBuildStation($playerId, $cityId, $color);
    }

    function applyBuildStation(int $playerId, int $cityId, int $color) {
        $cardCost = 4 - $this->getRemainingStations($playerId);
        
        $trainCarsHand = $this->getTrainCarsFromDb($this->trainCars->getCardsInLocation('hand', $playerId));
        $cardsToRemove = $this->canPayForStation($trainCarsHand, $cardCost, $color);

        $this->trainCars->moveCards(array_map(fn($card) => $card->id, $cardsToRemove), 'discard');

        // save built station
        self::DbQuery("INSERT INTO `placed_buildings` (`city_id`, `player_id`, `building_type`) VALUES ($cityId, $playerId, ".STATION.")");

        self::notifyAllPlayers('builtStation', clienttranslate('${player_name} builds a Train Station on ${city_name} with ${number} train car(s) : ${colors}'), [
            'playerId' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
            'city' => $this->CITIES[$cityId],
            'city_name' => $this->getCityName($cityId),
            'number' => $cardCost,
            'removeCards' => $cardsToRemove,
            'colors' => array_map(fn($card) => $card->type, $cardsToRemove),
        ]);

        self::incStat(1, 'builtStations');
        self::incStat(1, 'builtStations', $playerId);

        $this->gamestate->nextState('nextPlayer'); 
    }

    /**
     * Find the best use for stations, at the end of a game, to complete
     */
    function useStations(int $playerId, array $stations, array $uncompletedDestinations) {
        $allRoutes = $this->getClaimedRoutes();
        $playerRoutes = array_map(fn($route) => $route->routeId, array_values(array_filter($allRoutes, fn($route) => $route->playerId == $playerId)));

        $potentialRoutesPerStation = [];
        foreach ($stations as $station) {
            $potentialRoutesPerStation[$station->cityId] = array_map(fn($route) => $route->routeId, array_values(array_filter($allRoutes, fn($route) => 
                $route->playerId != $playerId && in_array($station->cityId, [$this->ROUTES[$route->routeId]->from, $this->ROUTES[$route->routeId]->to])
            )));
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
            $this->markCompletedDestination($playerId, $destination, $bestCombinationResult[2][$index], $bestCombinationResult[3][$index]);
        }

        return $bestCombinationResult;
    }

    /**
     * Return the result of a combination of possible station usage.
     */
    function getCombinationResult(array $uncompletedDestinations, array $playerRoutes, array $opponentRoutes, array $stations) {
        $routeIds = array_merge($playerRoutes, $opponentRoutes);

        $points = 0;
        $completedDestinations = [];
        $completedDestinationsRoutes = [];
        $completedDestinationsStations = [];

        foreach ($uncompletedDestinations as $destination) {
            $withRoutes = $this->getDestinationRoutesWithRoutesIds($destination, $routeIds);
            if ($withRoutes !== null) {
                $completedDestinations[] = $destination;
                $completedDestinationsRoutes[] = $withRoutes;
                $destinationStations = [];
                foreach ($withRoutes as $route) {
                    $index = array_search($route->id, $opponentRoutes);
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
