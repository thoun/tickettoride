<?php

namespace Bga\Games\TicketToRideMaps\States;

use Bga\GameFramework\Actions\Types\IntArrayParam;
use Bga\GameFramework\States\GameState;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\StateType;
use Bga\GameFramework\UserException;
use Bga\GameFrameworkPrototype\Helpers\Arrays;
use Bga\Games\TicketToRideMaps\Game;
use Bga\Games\TicketToRideMaps\Objects\TunnelAttempt;
use Throwable;

class ChooseAction extends GameState {
    public function __construct(protected Game $game)
    {
        parent::__construct($game,
            id: ST_PLAYER_CHOOSE_ACTION,
            type: StateType::ACTIVE_PLAYER,
            name: 'chooseAction',
            transitions: [
                "drawSecondCard" => ST_PLAYER_DRAW_SECOND_CARD,
                "drawDestinations" => ST_PLAYER_CHOOSE_ADDITIONAL_DESTINATIONS,
                "tunnel" => ST_PLAYER_CONFIRM_TUNNEL,
            ]
        );
    }

    function getArgs(int $activePlayerId) {
        $trainCarsHand = $this->game->trainCarManager->getPlayerHand($activePlayerId);
        // we don't limit claimable routes to the number of remaining train cars, because the players don't understand why they can't claim the route
        // so instead they'll get an error when they try to claim the route, saying they don't have enough train cars left
        $remainingTrainCars = 99;
        $realRemainingTrainCars = $this->game->getRemainingTrainCarsCount($activePlayerId);

        $possibleRoutes = $this->game->mapManager->claimableRoutes($activePlayerId, $trainCarsHand, $remainingTrainCars);
        $maxHiddenCardsPick = min(2, $this->game->trainCarManager->getRemainingTrainCarCardsInDeck(true));
        $maxDestinationsPick = min($this->game->getMap()->getAdditionalDestinationCardNumber($this->game->getExpansionOption()), $this->game->destinationManager->getRemainingDestinationCardsInDeck());

        $canClaimARoute = false;
        $costForRoute = [];
        foreach($possibleRoutes as $possibleRoute) {
            $colorsToTest = $possibleRoute->color > 0 ? [0, $possibleRoute->color] : [0,1,2,3,4,5,6,7,8];
            // if all route spaces are locomotives, you can only pay it with locomotives
            if ($possibleRoute->locomotives === $possibleRoute->number) {
                $colorsToTest = [0];
            }
            $costByColor = [];
            foreach($colorsToTest as $colorToTest) {
                $costByColor[$colorToTest] = $this->game->mapManager->canPayForRoute($possibleRoute, $trainCarsHand, 99, $colorToTest);

                if (!$canClaimARoute && $costByColor[$colorToTest] != null && count($costByColor[$colorToTest]) <= $realRemainingTrainCars) {
                    $canClaimARoute = true;
                }
            }
            $costForRoute[$possibleRoute->id] = array_map(fn($cardCost) => $cardCost === null ? null : array_map(fn($card) => $card->type, $cardCost), $costByColor);
        }

        $canTakeTrainCarCards = $this->game->trainCarManager->getRemainingTrainCarCardsInDeck(true, true);
        $canBuildStation = false;
        $possibleStations = null;
        $costForStation = null;
        if ($this->game->getMap()->stations !== null) {
            $remainingStations = $this->game->buildingManager->getRemainingStations($activePlayerId);
            $canBuildStation = $remainingStations > 0 && $this->game->buildingManager->canPayForStation($trainCarsHand, 4 - $remainingStations) != null;
            $possibleStations = $canBuildStation ? $this->game->buildingManager->claimableStations() : [];
            $costForStation = [];
            if ($canBuildStation) {
                $colorsToTest = [0,1,2,3,4,5,6,7,8];
                $costByColor = [];
                foreach($colorsToTest as $colorToTest) {
                    $costByColor[$colorToTest] = $this->game->buildingManager->canPayForStation($trainCarsHand, 4 - $remainingStations, $colorToTest);
                }
                $costForStation = array_map(fn($cardCost) => $cardCost == null ? null : array_map(fn($card) => $card->type, $cardCost), $costByColor);
            }
        }

        $canPass = !$canClaimARoute && !$canBuildStation && $maxDestinationsPick == 0 && $canTakeTrainCarCards == 0;

        $args = [
            'possibleRoutes' => $possibleRoutes,
            'possibleStations' => $possibleStations,
            'costForRoute' => $costForRoute,
            'maxHiddenCardsPick' => $maxHiddenCardsPick,
            'maxDestinationsPick' => $maxDestinationsPick,
            'canTakeTrainCarCards' => $canTakeTrainCarCards,
            'canBuildStation' => $canBuildStation,
            'costForStation' => $costForStation,
            'canPass' => $canPass,
            '_private' => [
                $activePlayerId => [

                ]
            ]
        ];

        if ($this->game->getMap()->locomotiveUsageRestriction) {
            $args['_private'] = [
                $activePlayerId => [
                    'trainCarsHand' => $trainCarsHand,
                ],
            ];
        }

        return $args;
    }

    #[PossibleAction]
    public function actDrawDeckCards(int $number, int $activePlayerId) { 
        $drawNumber = $this->game->trainCarManager->drawTrainCarCardsFromDeck($activePlayerId, $number);

        $this->game->incStat($drawNumber, 'collectedTrainCarCards');
        $this->game->incStat($drawNumber, 'collectedTrainCarCards', $activePlayerId);
        $this->game->incStat($drawNumber, 'collectedHiddenTrainCarCards');
        $this->game->incStat($drawNumber, 'collectedHiddenTrainCarCards', $activePlayerId);

       return $drawNumber == 1 && $this->game->trainCarManager->canTakeASecondCard(null) ? DrawSecondCard::class : NextPlayer::class;
    }
    
    #[PossibleAction]
    public function actDrawTableCard(int $id, int $activePlayerId) { 
        $card = $this->game->trainCarManager->drawTrainCarCardsFromTable($activePlayerId, $id);

        $this->game->incStat(1, 'collectedTrainCarCards');
        $this->game->incStat(1, 'collectedTrainCarCards', $activePlayerId);
        $this->game->incStat(1, 'collectedVisibleTrainCarCards');
        $this->game->incStat(1, 'collectedVisibleTrainCarCards', $activePlayerId);
        if ($card->type == 0) {
            $this->game->incStat(1, 'collectedVisibleLocomotives');
            $this->game->incStat(1, 'collectedVisibleLocomotives', $activePlayerId);
        }

        return $this->game->trainCarManager->canTakeASecondCard($card->type) ? DrawSecondCard::class : NextPlayer::class;
    }
    
    #[PossibleAction]
    public function actDrawDestinations(int $activePlayerId) {
        $remainingDestinationsCardsInDeck = $this->game->destinationManager->getRemainingDestinationCardsInDeck();
        if ($remainingDestinationsCardsInDeck == 0) {
            throw new UserException(clienttranslate("You can't take new Destination cards because the deck is empty"));
        }

        $this->game->destinationManager->pickAdditionalDestinationCards($activePlayerId);

        $this->game->incStat(1, 'drawDestinationsAction');
        $this->game->incStat(1, 'drawDestinationsAction', $activePlayerId);

        return ChooseAdditionalDestinations::class;
    }
    
    #[PossibleAction]
    public function actClaimRoute(int $routeId, int $color, #[IntArrayParam()] ?array $distribution, int $activePlayerId) {
        $route = $this->game->mapManager->getAllRoutes()[$routeId];

        $remainingTrainCars = $this->game->getRemainingTrainCarsCount($activePlayerId);
        if ($remainingTrainCars < $route->number) {
            $this->notify->player($activePlayerId, 'notEnoughTrainCars', '', []);
            return;
        }

        if ($this->game->getUniqueIntValueFromDB( "SELECT count(*) FROM `claimed_routes` WHERE `route_id` = $routeId") > 0) {
            throw new UserException("Route is already claimed.");
        }
        
        $trainCarsHand = $this->game->trainCarManager->getPlayerHand($activePlayerId);
        $distributionCards = $distribution ? Arrays::filter($trainCarsHand, fn($card) => in_array($card->id, $distribution)) : null;
        $colorAndLocomotiveCards = $this->game->mapManager->canPayForRoute($route, $trainCarsHand, $remainingTrainCars, $color, distributionCards: $distributionCards);
        
        if ($colorAndLocomotiveCards == null || count($colorAndLocomotiveCards) < $route->number) {
            throw new UserException("Not enough cards to claim the route.");
        }

        $possibleRoutes = $this->game->mapManager->claimableRoutes($activePlayerId, $trainCarsHand, $remainingTrainCars);
        if (!Arrays::some($possibleRoutes, fn($possibleRoute) => $possibleRoute->id == $routeId)) {
            throw new UserException("You can't claim this route");
        }

        if ($route->tunnel) {
            $remainingDeckCards = $this->game->trainCarManager->getRemainingTrainCarCardsInDeck(true);
            if ($remainingDeckCards == 0) {
                $this->notify->all('log', clienttranslate('No train car card in deck or discard, tunnel is free'), []);
            } else {
                $pickedCardCount = min(3, $remainingDeckCards);
                $tunnelCards = $this->game->trainCarManager->pickCardsForTunnel($pickedCardCount);
                $extraCards = count(array_filter($tunnelCards, fn($card) => $card->type == 0 || $card->type == $color));

                $this->notify->all('log', clienttranslate('${player_name} tries to build a tunnel from ${from} to ${to} with color ${color}'), [
                    'playerId' => $activePlayerId,
                    'player_name' => $this->game->getPlayerNameById($activePlayerId),
                    'from' => $this->game->getCityName($route->from),
                    'to' => $this->game->getCityName($route->to),
                    'color' => $color,
                ]);
                
                // show the revealed cards and log
                $this->notify->all($extraCards > 0 ? 'log' : 'freeTunnel', clienttranslate('${extraCards} extra cards over the ${pickedCards} train car cards revealed from the deck are needed to claim the route'), [
                    'pickedCards' => $pickedCardCount,
                    'extraCards' => $extraCards,
                    'tunnelCards' => $tunnelCards,
                ]);
                
                if ($extraCards > 0) { // if the player can't afford, we still ask to hide the fact he can't
                    $this->game->setGlobalVariable(TUNNEL_ATTEMPT, new TunnelAttempt($routeId, $color, $extraCards, $tunnelCards, $distribution));
                    $this->gamestate->nextState('tunnel'); 
                    return;
                } else {
                    // put back tunnel cards
                    $this->game->endTunnelAttempt(false);
                }
            }
        }

        $this->game->applyClaimRoute($activePlayerId, $routeId, $color, 0, distributionCards: $distributionCards);

        return NextPlayer::class;
    }
  	
    /**
     * Build a station on a city with seleced color
     */
    #[PossibleAction]
    public function actBuildStation(int $cityId, int $color, int $activePlayerId) {

        $remainingStations = $this->game->buildingManager->getRemainingStations($activePlayerId);
        if ($remainingStations <= 0) {
            throw new UserException("No station remaining");
        }

        if ($this->game->getUniqueIntValueFromDB( "SELECT count(*) FROM `placed_buildings` WHERE `city_id` = $cityId") > 0) {
            throw new UserException("City is already claimed.");
        }
        
        $trainCarsHand = $this->game->trainCarManager->getPlayerHand($activePlayerId);
        $colorAndLocomotiveCards = $this->game->buildingManager->canPayForStation($trainCarsHand, 4 - $remainingStations, $color);
        
        if ($colorAndLocomotiveCards == null || count($colorAndLocomotiveCards) < 4 - $remainingStations) {
            throw new UserException("Not enough cards to build a station.");
        }

        $possibleStations = $this->game->buildingManager->claimableStations();
        if (!Arrays::some($possibleStations, fn($possibleStation) => $possibleStation->id == $cityId)) {
            throw new UserException("You can't claim this city");
        }

        $this->game->buildingManager->applyBuildStation($activePlayerId, $cityId, $color);

        return NextPlayer::class;
    }
    
    #[PossibleAction]
    public function actPass(array $args) {
        if (!$args['canPass']) {
            throw new UserException("You cannot pass");
        }

        return NextPlayer::class;
    }

    function zombie(int $playerId, array $args) {
        try {
            if ($args['canPass']) {
                return $this->actPass($args);
            }

            $helpfulRouteAction = $this->tryClaimHelpfulRouteForDestination($playerId);
            if ($helpfulRouteAction !== null) {
                return $helpfulRouteAction;
            }
            
            if ($this->game->getLowestTrainCarsCount() >= 8
                && $this->game->destinationManager->getRemainingDestinationCardsInDeck() > 0
                && $this->game->getUniqueIntValueFromDB("SELECT count(*) FROM `destination` WHERE `card_location` = 'hand' AND `card_location_arg` = $playerId AND `completed` = 0") == 0
            ) {
                return $this->actDrawDestinations($playerId);
            }

            return $this->actDrawDeckCards(2, $playerId);
        } catch (Throwable $e) { // safe catch : if the zombie cannot play, just pass
            return NextPlayer::class;
        }
    }

    private function tryClaimHelpfulRouteForDestination(int $playerId): ?string {
        $trainCarsHand = $this->game->trainCarManager->getPlayerHand($playerId);
        $remainingTrainCars = $this->game->getRemainingTrainCarsCount($playerId);
        $possibleRoutes = $this->game->mapManager->claimableRoutes($playerId, $trainCarsHand, $remainingTrainCars);
        if (count($possibleRoutes) === 0) {
            return null;
        }

        $allRoutes = $this->game->mapManager->getAllRoutes();
        $claimedRoutes = $this->game->getClaimedRoutes();
        $doubleRouteAllowed = $this->game->getPlayerCount() >= $this->game->getMap()->minimumPlayerForDoubleRoutes;

        $playerClaimedRouteIds = [];
        $claimedOwnersByPair = [];
        foreach ($claimedRoutes as $claimedRoute) {
            $playerClaimedRouteIds[$claimedRoute->routeId] = $claimedRoute->playerId;

            $route = $allRoutes[$claimedRoute->routeId];
            $pairKey = $this->getZombieRoutePairKey($route);
            if (!array_key_exists($pairKey, $claimedOwnersByPair)) {
                $claimedOwnersByPair[$pairKey] = [];
            }
            $claimedOwnersByPair[$pairKey][$claimedRoute->playerId] = true;
        }

        $adjacency = [];
        foreach ($allRoutes as $route) {
            $routeId = $route->id;
            if (array_key_exists($routeId, $playerClaimedRouteIds)) {
                if ($playerClaimedRouteIds[$routeId] !== $playerId) {
                    continue;
                }
                $weight = 0;
            } else {
                $pairOwners = $claimedOwnersByPair[$this->getZombieRoutePairKey($route)] ?? [];
                if ((!$doubleRouteAllowed && count($pairOwners) > 0) || array_key_exists($playerId, $pairOwners)) {
                    continue;
                }
                $weight = 1;
            }

            $adjacency[$route->from][] = [$route->to, $weight];
            $adjacency[$route->to][] = [$route->from, $weight];
        }

        $uncompletedDestinations = $this->game->destinationManager->getUncompletedDestinations($playerId);
        foreach ($uncompletedDestinations as $destination) {
            $fromCities = $this->getZombieDestinationCities($destination->from);
            $toCities = [];
            foreach (is_array($destination->to) ? $destination->to : [$destination->to] as $to) {
                foreach ($this->getZombieDestinationCities($to) as $city) {
                    $toCities[$city] = true;
                }
            }
            $toCities = array_keys($toCities);

            $distanceFrom = $this->getZombieMissingRouteDistances($fromCities, $adjacency);
            $distanceTo = $this->getZombieMissingRouteDistances($toCities, $adjacency);

            $bestMissingRoutes = null;
            foreach ($toCities as $toCity) {
                if (array_key_exists($toCity, $distanceFrom) && ($bestMissingRoutes === null || $distanceFrom[$toCity] < $bestMissingRoutes)) {
                    $bestMissingRoutes = $distanceFrom[$toCity];
                }
            }
            if ($bestMissingRoutes === null || $bestMissingRoutes === 0) {
                continue;
            }

            foreach ($possibleRoutes as $possibleRoute) {
                $helpsDestination =
                    (array_key_exists($possibleRoute->from, $distanceFrom)
                        && array_key_exists($possibleRoute->to, $distanceTo)
                        && $distanceFrom[$possibleRoute->from] + 1 + $distanceTo[$possibleRoute->to] === $bestMissingRoutes)
                    || (array_key_exists($possibleRoute->to, $distanceFrom)
                        && array_key_exists($possibleRoute->from, $distanceTo)
                        && $distanceFrom[$possibleRoute->to] + 1 + $distanceTo[$possibleRoute->from] === $bestMissingRoutes);

                if (!$helpsDestination) {
                    continue;
                }

                $color = $this->getZombieClaimColor($possibleRoute, $trainCarsHand, $remainingTrainCars);
                if ($color !== null) {
                    return $this->actClaimRoute($possibleRoute->id, $color, null, $playerId);
                }
            }
        }

        return null;
    }

    private function getZombieClaimColor(object $route, array $trainCarsHand, int $remainingTrainCars): ?int {
        $colorsToTest = $route->color > 0 ? [$route->color, 0] : [1,2,3,4,5,6,7,8,0];
        if ($route->locomotives === $route->number) {
            $colorsToTest = [0];
        }

        $bestColor = null;
        $bestLocomotiveCount = PHP_INT_MAX;
        foreach ($colorsToTest as $colorToTest) {
            $cost = $this->game->mapManager->canPayForRoute($route, $trainCarsHand, $remainingTrainCars, $colorToTest);
            if ($cost === null) {
                continue;
            }

            $locomotiveCount = Arrays::count($cost, fn($card) => $card->type == 0);
            if ($bestColor === null || $locomotiveCount < $bestLocomotiveCount) {
                $bestColor = $colorToTest;
                $bestLocomotiveCount = $locomotiveCount;
            }
        }

        return $bestColor;
    }

    private function getZombieDestinationCities(int $cityOrCountry): array {
        if ($cityOrCountry < 0) {
            return $this->game->getMap()->countriesEndPoints[$cityOrCountry];
        }

        return [$cityOrCountry];
    }

    private function getZombieMissingRouteDistances(array $startCities, array $adjacency): array {
        $distances = [];
        $queue = new \SplPriorityQueue();
        $queue->setExtractFlags(\SplPriorityQueue::EXTR_BOTH);

        foreach ($startCities as $startCity) {
            if (array_key_exists($startCity, $distances)) {
                continue;
            }

            $distances[$startCity] = 0;
            $queue->insert($startCity, 0);
        }

        while (!$queue->isEmpty()) {
            $current = $queue->extract();
            $city = $current['data'];
            $distance = -$current['priority'];

            if ($distance > $distances[$city]) {
                continue;
            }

            foreach ($adjacency[$city] ?? [] as [$nextCity, $weight]) {
                $nextDistance = $distance + $weight;
                if (!array_key_exists($nextCity, $distances) || $nextDistance < $distances[$nextCity]) {
                    $distances[$nextCity] = $nextDistance;
                    $queue->insert($nextCity, -$nextDistance);
                }
            }
        }

        return $distances;
    }

    private function getZombieRoutePairKey(object $route): string {
        return min($route->from, $route->to).'-'.max($route->from, $route->to);
    }
}
