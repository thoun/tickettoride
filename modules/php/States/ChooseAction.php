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
        $rightOfWayPending = $this->game->getMap()->useTechnologyCards && $this->bga->globals->get('RIGHT_OF_WAY_PENDING', false);
        $thermocompressorRemaining = $this->game->getMap()->useTechnologyCards ? (int) $this->bga->globals->get('THERMOCOMPRESSOR_REMAINING', 0) : 0;
        $legendaryCharacter = null;
        $legendaryCharacterState = null;
        $opponentRoutesInsteadOfFreeOnes = false;
        $considerAllRoutesGray = false;
        $pairSetAsLocomotive = null;
        $usingCharacter4 = false;

        if ($this->game->legendaryCharacterManager->isActive()) {
            $legendaryCharacter = $this->game->legendaryCharacterManager->getPlayerCharacter($activePlayerId);
            $legendaryCharacterState = $this->game->legendaryCharacterManager->getPlayerCharacterState($activePlayerId);

            $opponentRoutesInsteadOfFreeOnes = $legendaryCharacter === 1 && $legendaryCharacterState === 'using';
            $considerAllRoutesGray = $legendaryCharacter === 5 && $legendaryCharacterState === 'using';
            $pairSetAsLocomotive = $this->game->legendaryCharacterManager->getCharacter3UsingColor($activePlayerId);

            $usingCharacter4 = $legendaryCharacter === 4 && count($this->game->legendaryCharacterManager->getCharacter4UsingRouteIds($activePlayerId)) > 0;
        }
        $opponentRoutesInsteadOfFreeOnes = $opponentRoutesInsteadOfFreeOnes || $rightOfWayPending;

        $trainCarsHand = $this->game->trainCarManager->getPlayerHand($activePlayerId);
        $ferryCardsCount = $this->game->getMap()->ferryCards ? (int) $this->game->bga->globals->get("FERRY_CARD_{$activePlayerId}", 0) : 0;
        // we don't limit claimable routes to the number of remaining train cars, because the players don't understand why they can't claim the route
        // so instead they'll get an error when they try to claim the route, saying they don't have enough train cars left
        $remainingTrainCars = 99;
        $realRemainingTrainCars = $this->game->getRemainingTrainCarsCount($activePlayerId);

        $possibleRoutes = $this->game->mapManager->claimableRoutes($activePlayerId, $trainCarsHand, ($rightOfWayPending || $thermocompressorRemaining > 0) ? $realRemainingTrainCars : $remainingTrainCars, opponentRoutesInsteadOfFreeOnes: $opponentRoutesInsteadOfFreeOnes, considerAllRoutesGray: $considerAllRoutesGray, pairSetAsLocomotive: $pairSetAsLocomotive, ferryCards: $ferryCardsCount);
        if ($legendaryCharacter === 4) {
            $possibleRoutes = $this->game->legendaryCharacterManager->filterCharacter4Routes($activePlayerId, $possibleRoutes);
        }
        $maxHiddenCardsPerAction = 2;
        if ($this->game->getMap()->useTechnologyCards) {
            $technologyCards = $this->bga->globals->get("TECHNOLOGY_CARDS_{$activePlayerId}", []);
            $maxHiddenCardsPerAction = $this->game->getMap()->getMaximumHiddenTrainCardsPerAction($technologyCards);
        }
        $maxHiddenCardsPick = min($maxHiddenCardsPerAction, $this->game->trainCarManager->getRemainingTrainCarCardsInDeck(true));
        $maxDestinationsPick = min($this->game->getMap()->getAdditionalDestinationCardNumber($this->game->getExpansionOption()), $this->game->destinationManager->getRemainingDestinationCardsInDeck());

        $canClaimARoute = false;
        $costForRoute = [];
        foreach($possibleRoutes as $possibleRoute) {
            $possibleRouteColor = $considerAllRoutesGray ? 0 : $possibleRoute->color;
            $colorsToTest = $possibleRouteColor > 0 ? [0, $possibleRouteColor] : [0,1,2,3,4,5,6,7,8];
            // if all route spaces are locomotives, you can only pay it with locomotives
            if ($possibleRoute->locomotives === $possibleRoute->number) {
                $colorsToTest = [0];
            }
            $costByColor = [];
            foreach($colorsToTest as $colorToTest) {
                $costByColor[$colorToTest] = $this->game->mapManager->canPayForRoute($possibleRoute, $trainCarsHand, 99, $colorToTest, considerAllRoutesGray: $considerAllRoutesGray, pairSetAsLocomotive: $pairSetAsLocomotive, ferryCards: $ferryCardsCount, playerId: $activePlayerId);

                if (!$canClaimARoute && $costByColor[$colorToTest] !== null && ($possibleRoute->number + $possibleRoute->mountain) <= $realRemainingTrainCars) {
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

        $canDrawFerryCard = $this->game->getMap()->ferryCards && $ferryCardsCount < 2;
        $buyableTechnologyCards = [];
        $technologyCardCosts = $this->game->getMap()->technologyCardCosts;
        if ($this->game->getMap()->useTechnologyCards && !$this->bga->globals->get('TECHNOLOGY_CARD_BOUGHT_THIS_TURN', false)) {
            $remainingTechnologyCards = $this->bga->globals->get('REMAINING_TECHNOLOGY_CARDS', []);
            $ownedTechnologyCards = $this->bga->globals->get("TECHNOLOGY_CARDS_{$activePlayerId}", []);
            $setSize = in_array(6, $ownedTechnologyCards, true) ? 3 : 4;
            $locomotives = count(array_filter($trainCarsHand, fn($card) => $card->type === 0));
            $otherCards = count($trainCarsHand) - $locomotives;
            $deckReshuffled = $this->bga->globals->get('TRAIN_CAR_DECK_RESHUFFLED', false);
            foreach ($technologyCardCosts as $type => $cost) {
                if ($type === 10 && count($this->game->mapManager->claimableRoutes($activePlayerId, $trainCarsHand, $realRemainingTrainCars, opponentRoutesInsteadOfFreeOnes: true, ferryCards: $ferryCardsCount)) === 0) {
                    continue;
                }
                if ($type === 11 && count($this->game->mapManager->claimableRoutes($activePlayerId, $trainCarsHand, $realRemainingTrainCars, ferryCards: $ferryCardsCount)) === 0) {
                    continue;
                }
                if (($remainingTechnologyCards[$type] ?? 0) > 0
                    && !in_array($type, $ownedTechnologyCards, true)
                    && (!$deckReshuffled || !in_array($type, [13, 14], true))
                    && $locomotives + intdiv($otherCards, $setSize) >= $cost) {
                    $buyableTechnologyCards[] = $type;
                }
            }
        }
        $canPass = !$canClaimARoute && !$canBuildStation && $maxDestinationsPick == 0 && $canTakeTrainCarCards == 0 && !$canDrawFerryCard;
        if ($rightOfWayPending || $thermocompressorRemaining > 0) {
            $buyableTechnologyCards = [];
            $maxHiddenCardsPick = 0;
            $maxDestinationsPick = 0;
            $canTakeTrainCarCards = false;
            $canDrawFerryCard = false;
            $canBuildStation = false;
            $possibleStations = [];
            $canPass = false;
        }

        if ($usingCharacter4) {
            $maxDestinationsPick = 0;
            $maxHiddenCardsPick = 0;
            $canTakeTrainCarCards = false;
            $canDrawFerryCard = false;
            $canBuildStation = false;
            $possibleStations = [];
            $costForStation = [];
            $canPass = true;
            $buyableTechnologyCards = [];
        }

        $args = [
            'possibleRouteIds' => array_values(array_map(fn($route) => $route->id, $possibleRoutes)),
            'possibleStationIds' => $possibleStations === null
                ? null
                : array_values(array_map(fn($city) => $city->id, $possibleStations)),
            'costForRoute' => $costForRoute,
            'maxHiddenCardsPick' => $maxHiddenCardsPick,
            'maxDestinationsPick' => $maxDestinationsPick,
            'canTakeTrainCarCards' => $canTakeTrainCarCards,
            'canDrawFerryCard' => $canDrawFerryCard,
            'ferryCardsCount' => $ferryCardsCount,
            'canBuildStation' => $canBuildStation,
            'costForStation' => $costForStation,
            'canPass' => $canPass,
            'buyableTechnologyCards' => $buyableTechnologyCards,
            'technologyCardCosts' => $technologyCardCosts,
            'rightOfWayPending' => $rightOfWayPending,
            'thermocompressorRemaining' => $thermocompressorRemaining,
            '_private' => [
                $activePlayerId => [

                ]
            ]
        ];

        $hasCardSetPayments = Arrays::some($this->game->getMap()->routes, fn($route) =>
            ($route->canPayWithAnySetOfCards ?? 0) > 0 || ($route->canPayFerriesWithAnySetOfCards ?? 0) > 0
        );
        if ($this->game->getMap()->locomotiveUsageRestriction || $this->game->getMap()->ferryCards || $this->game->getMap()->useTechnologyCards || $hasCardSetPayments) {
            $args['_private'] = [
                $activePlayerId => [
                    'trainCarsHand' => $trainCarsHand,
                ],
            ];
        }

        if ($this->game->legendaryCharacterManager->isActive()) {
            $args['legendaryCharacter'] = $legendaryCharacter;
            $args['legendaryCharacterState'] = $legendaryCharacterState;
            if ($legendaryCharacter === 3) {
                $args['_private'][$activePlayerId]['legendaryCharacter3Colors'] = array_values(array_filter(
                    array_unique(array_map(fn($card) => $card->type, $trainCarsHand)),
                    fn($color) => $color > 0 && count(array_filter($trainCarsHand, fn($card) => $card->type === $color)) >= 2,
                ));
            }
        }

        return $args;
    }

    #[PossibleAction]
    public function actBuyTechnologyCard(int $type, #[IntArrayParam()] array $distribution, int $activePlayerId, array $args) {
        $this->assertNoImmediateTechnologyPending();
        if (in_array($type, [13, 14], true) && $this->bga->globals->get('TRAIN_CAR_DECK_RESHUFFLED', false)) {
            throw new UserException('This Technology card is no longer available after the Train Car deck was reshuffled.');
        }
        if (!in_array($type, $args['buyableTechnologyCards'] ?? [], true)) {
            throw new UserException('This Technology card is not available to buy.');
        }

        $hand = $this->game->trainCarManager->getPlayerHand($activePlayerId);
        $cardsById = [];
        foreach ($hand as $card) {
            $cardsById[$card->id] = $card;
        }
        if (count($distribution) !== count(array_unique($distribution))) {
            throw new UserException('A Train Car card cannot be used twice.');
        }
        $cardsToRemove = [];
        foreach ($distribution as $cardId) {
            if (!isset($cardsById[$cardId])) {
                throw new UserException('Selected Train Car card is not in your hand.');
            }
            $cardsToRemove[] = $cardsById[$cardId];
        }

        $locomotives = count(array_filter($cardsToRemove, fn($card) => $card->type === 0));
        $otherCards = count($cardsToRemove) - $locomotives;
        $ownedTechnologyCards = $this->bga->globals->get("TECHNOLOGY_CARDS_{$activePlayerId}", []);
        $setSize = in_array(6, $ownedTechnologyCards, true) ? 3 : 4;
        if ($otherCards % $setSize !== 0 || $locomotives + intdiv($otherCards, $setSize) !== $this->game->getMap()->technologyCardCosts[$type]) {
            throw new UserException('Selected cards do not pay the Technology cost.');
        }
        if ($type === 10 || $type === 11) {
            $remainingHand = array_values(array_filter($hand, fn($card) => !in_array($card->id, $distribution, true)));
            if (count($this->game->mapManager->claimableRoutes($activePlayerId, $remainingHand, $this->game->getRemainingTrainCarsCount($activePlayerId), opponentRoutesInsteadOfFreeOnes: $type === 10)) === 0) {
                throw new UserException($type === 10
                    ? 'Keep enough Train Car cards to claim an occupied route with Right of Way.'
                    : 'Keep enough Train Car cards to claim a route with Thermocompressor.');
            }
        }

        $remainingTechnologyCards = $this->bga->globals->get('REMAINING_TECHNOLOGY_CARDS', []);
        $remainingTechnologyCards[$type]--;
        $ownedTechnologyCards[] = $type;
        $this->game->trainCarManager->trainCars->moveCards($distribution, 'discard');
        $this->bga->globals->set('REMAINING_TECHNOLOGY_CARDS', $remainingTechnologyCards);
        $this->bga->globals->set("TECHNOLOGY_CARDS_{$activePlayerId}", $ownedTechnologyCards);
        $this->bga->globals->set('TECHNOLOGY_CARD_BOUGHT_THIS_TURN', true);
        if ($type === 10) {
            $this->bga->globals->set('RIGHT_OF_WAY_PENDING', true);
        } else if ($type === 11) {
            $this->bga->globals->set('THERMOCOMPRESSOR_REMAINING', 2);
        }

        $this->notify->all('technologyCardBought', clienttranslate('${player_name} buys a Technology card with these Train Car cards: ${colors}'), [
            'playerId' => $activePlayerId,
            'player_name' => $this->game->getPlayerNameById($activePlayerId),
            'type' => $type,
            'remainingCount' => $remainingTechnologyCards[$type],
            'removeCards' => $cardsToRemove,
            'colors' => array_map(fn($card) => $card->type, $cardsToRemove),
        ]);
        return self::class;
    }

    #[PossibleAction]
    public function actDrawDeckCards(int $number, int $activePlayerId) { 
        $this->assertNoImmediateTechnologyPending();
        $this->assertCharacter4DoesNotDraw($activePlayerId);
        $drawNumber = $this->game->trainCarManager->drawTrainCarCardsFromDeck($activePlayerId, $number);

        $this->game->incStat($drawNumber, 'collectedTrainCarCards');
        $this->game->incStat($drawNumber, 'collectedTrainCarCards', $activePlayerId);
        $this->game->incStat($drawNumber, 'collectedHiddenTrainCarCards');
        $this->game->incStat($drawNumber, 'collectedHiddenTrainCarCards', $activePlayerId);

       return $drawNumber == 1 && $this->game->trainCarManager->canTakeASecondCard(null) ? DrawSecondCard::class : NextPlayer::class;
    }

    #[PossibleAction]
    public function actDrawFerryCard(int $activePlayerId) {
        $this->assertNoImmediateTechnologyPending();
        $this->assertCharacter4DoesNotDraw($activePlayerId);
        if (!$this->game->getMap()->ferryCards) {
            throw new UserException("Ferry cards are not used on this map.");
        }

        $key = "FERRY_CARD_{$activePlayerId}";
        $ferryCardsCount = (int) $this->game->bga->globals->get($key, 0);
        if ($ferryCardsCount >= 2) {
            throw new UserException("You cannot have more than 2 Ferry cards.");
        }

        $ferryCardsCount = $this->game->bga->globals->inc($key, 1);
        $this->notify->all('ferryCardDrawn', clienttranslate('${player_name} draws a Ferry card'), [
            'playerId' => $activePlayerId,
            'player_name' => $this->game->getPlayerNameById($activePlayerId),
            'ferryCardsCount' => $ferryCardsCount,
        ]);

        return NextPlayer::class;
    }
    
    #[PossibleAction]
    public function actDrawTableCard(int $id, int $activePlayerId) { 
        $this->assertNoImmediateTechnologyPending();
        $this->assertCharacter4DoesNotDraw($activePlayerId);
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
        $this->assertNoImmediateTechnologyPending();
        $this->assertCharacter4DoesNotDraw($activePlayerId);
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
    public function actClaimRoute(int $routeId, int $color, #[IntArrayParam()] ?array $distribution, int $ferryCards, int $activePlayerId) {
        $rightOfWayPending = $this->game->getMap()->useTechnologyCards && $this->bga->globals->get('RIGHT_OF_WAY_PENDING', false);
        $route = $this->game->mapManager->getAllRoutes()[$routeId] ?? null;
        if (!isset($route)) {
            throw new UserException("Invalid route.");
        }

        $claimWithBulletTrain = $route->bulletTrainSpaceIndex !== null && $this->bga->globals->get(REMAINING_BULLET_TRAINS) > 0;
        $remainingTrainCars = $this->game->getRemainingTrainCarsCount($activePlayerId);
        if (!$claimWithBulletTrain && $remainingTrainCars < ($route->number + $route->mountain)) {
            $this->notify->player($activePlayerId, 'notEnoughTrainCars', '', []);
            return;
        }

        $legendaryCharacter = null;
        $legendaryCharacterState = null;
        $opponentRoutesInsteadOfFreeOnes = false;
        $considerAllRoutesGray = false;
        $pairSetAsLocomotive = null;

        if ($this->game->legendaryCharacterManager->isActive()) {
            $legendaryCharacter = $this->game->legendaryCharacterManager->getPlayerCharacter($activePlayerId);
            $legendaryCharacterState = $this->game->legendaryCharacterManager->getPlayerCharacterState($activePlayerId);
            $opponentRoutesInsteadOfFreeOnes = $legendaryCharacter === 1 && $legendaryCharacterState === 'using';
            $considerAllRoutesGray = $legendaryCharacter === 5 && $legendaryCharacterState === 'using';
            $pairSetAsLocomotive = $this->game->legendaryCharacterManager->getCharacter3UsingColor($activePlayerId);
        }
        $opponentRoutesInsteadOfFreeOnes = $opponentRoutesInsteadOfFreeOnes || $rightOfWayPending;

        $claimedRoutes = array_values(array_filter($this->game->getClaimedRoutes(), fn($claimedRoute) => $claimedRoute->routeId === $routeId));
        if ($opponentRoutesInsteadOfFreeOnes) {
            if (count($claimedRoutes) === 0) {
                throw new UserException("Route is not already claimed.");
            }
            if (Arrays::some($claimedRoutes, fn($claimedRoute) => $claimedRoute->playerId === $activePlayerId)) {
                throw new UserException("Route is already claimed by you.");
            }
        } else {
            if (count($claimedRoutes) > 0) {
                throw new UserException("Route is already claimed.");
            }
        }
        
        $trainCarsHand = $this->game->trainCarManager->getPlayerHand($activePlayerId);
        $distributionCards = $distribution !== null ? Arrays::filter($trainCarsHand, fn($card) => in_array($card->id, $distribution)) : null;
        $availableFerryCards = $this->game->getMap()->ferryCards ? (int) $this->game->bga->globals->get("FERRY_CARD_{$activePlayerId}", 0) : 0;
        if ($route->ferryWaves > 0 && $distribution === null) {
            throw new UserException("You must choose how to pay for this Ferry route.");
        }
        $colorAndLocomotiveCards = $this->game->mapManager->canPayForRoute($route, $trainCarsHand, $remainingTrainCars, $color, distributionCards: $distributionCards, considerAllRoutesGray: $considerAllRoutesGray, pairSetAsLocomotive: $pairSetAsLocomotive, ferryCards: $availableFerryCards, ferryCardsUsed: $ferryCards, playerId: $activePlayerId);
        
        if ($colorAndLocomotiveCards == null) {
            throw new UserException("Not enough cards to claim the route.");
        }

        $possibleRoutes = $this->game->mapManager->claimableRoutes($activePlayerId, $trainCarsHand, $remainingTrainCars, opponentRoutesInsteadOfFreeOnes: $opponentRoutesInsteadOfFreeOnes, considerAllRoutesGray: $considerAllRoutesGray, pairSetAsLocomotive: $pairSetAsLocomotive, ferryCards: $availableFerryCards);
        $possibleRoutes = $this->game->legendaryCharacterManager->filterCharacter4Routes($activePlayerId, $possibleRoutes);
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

        $this->game->applyClaimRoute($activePlayerId, $routeId, $color, 0, distributionCards: $distributionCards, shifted: $opponentRoutesInsteadOfFreeOnes, ferryCardsUsed: $ferryCards);
        if ($rightOfWayPending) {
            $this->game->returnRightOfWay($activePlayerId);
        }
        if ($this->game->thermocompressorAfterRouteClaim($activePlayerId)) {
            return self::class;
        }

        if ($legendaryCharacter === 4 && count($this->game->legendaryCharacterManager->getCharacter4UsingRouteIds($activePlayerId)) > 0) {
            if ($this->game->legendaryCharacterManager->character4CanClaimAnotherRoute($activePlayerId)) {
                return self::class;
            }

            $this->game->legendaryCharacterManager->onCharacter4Pass($activePlayerId);
        }

        if ($route->stockShares !== null) {
            $remainingStockShareCards = $this->game->bga->globals->get('REMAINING_STOCK_SHARE_CARDS', []);
            if (array_any($route->stockShares, fn($type) => !empty($remainingStockShareCards[$type]))) {
                $this->bga->globals->set('STOCK_SHARE_ROUTE', $routeId);
                return ChooseStockShare::class;
            }
        }

        return NextPlayer::class;
    }
  	
    /**
     * Build a station on a city with seleced color
     */
    #[PossibleAction]
    public function actBuildStation(int $cityId, int $color, int $activePlayerId) {
        $this->assertNoImmediateTechnologyPending();

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
    public function actPass(int $activePlayerId, array $args) {
        $this->assertNoImmediateTechnologyPending();
        if (!$args['canPass']) {
            throw new UserException("You cannot pass");
        }

        $this->game->legendaryCharacterManager->onCharacter4Pass($activePlayerId);

        return NextPlayer::class;
    }

    private function assertNoImmediateTechnologyPending(): void {
        if ($this->game->getMap()->useTechnologyCards && $this->bga->globals->get('RIGHT_OF_WAY_PENDING', false)) {
            throw new UserException('Claim an occupied route with Right of Way first.');
        }
        if ($this->game->getMap()->useTechnologyCards && $this->bga->globals->get('THERMOCOMPRESSOR_REMAINING', 0) > 0) {
            throw new UserException('Claim your routes with Thermocompressor first.');
        }
    }

    #[PossibleAction]
    public function actUseLegendaryCharacter(int $activePlayerId, array $args, ?int $color = null) {
        $this->assertNoImmediateTechnologyPending();
        $legendaryCharacter = $args['legendaryCharacter'];
        $legendaryCharacterState = $args['legendaryCharacterState'];
        if ($legendaryCharacterState !== null) {
            throw new UserException("Already activated");
        }

        switch ($legendaryCharacter) {
            case 1: 
                $this->game->legendaryCharacterManager->setPlayerCharacterState($activePlayerId, 'using');
                break;
            case 3:
                $trainCarsHand = $this->game->trainCarManager->getPlayerHand($activePlayerId);
                if ($color === null || $color === 0 || count(array_filter($trainCarsHand, fn($card) => $card->type === $color)) < 2) {
                    throw new UserException("You don't have two train cards of this color");
                }
                $this->game->legendaryCharacterManager->setPlayerCharacterState($activePlayerId, 'using:'.$color);
                break;
            case 5: 
                $this->game->legendaryCharacterManager->setPlayerCharacterState($activePlayerId, 'using');
                break;
            default: throw new UserException("Impossible to activate the Legendary character special rule");
        }

        return self::class;
    }

    #[PossibleAction]
    public function actCancelLegendaryCharacter(int $activePlayerId, array $args) {
        $this->assertNoImmediateTechnologyPending();
        $legendaryCharacterState = $args['legendaryCharacterState'];
        $isCharacter3Using = $args['legendaryCharacter'] === 3
            && is_string($legendaryCharacterState)
            && preg_match('/^using:[1-8]$/', $legendaryCharacterState) === 1;
        if ($legendaryCharacterState !== 'using' && !$isCharacter3Using) {
            throw new UserException("Not activated");
        }

        $this->game->legendaryCharacterManager->setPlayerCharacterState($activePlayerId, null);
        return self::class;
    }

    private function assertCharacter4DoesNotDraw(int $playerId): void {
        if (count($this->game->legendaryCharacterManager->getCharacter4UsingRouteIds($playerId)) > 0) {
            throw new UserException("You must claim another route or pass");
        }
    }

    function zombie(int $playerId, array $args) {
        try {
            if ($args['canPass']) {
                return $this->actPass($playerId, $args);
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

            $hiddenCards = $this->game->trainCarManager->getRemainingTrainCarCardsInDeck(true);
            if ($hiddenCards > 0) {
                return $this->actDrawDeckCards(min(2, $hiddenCards), $playerId);
            }
            $visibleCards = $this->game->trainCarManager->getVisibleTrainCarCards();
            if (count($visibleCards) > 0) {
                return $this->actDrawTableCard(reset($visibleCards)->id, $playerId);
            }
            // With no cards to draw, use a payable route even if it does not
            // help an existing ticket, or draw a new ticket when available.
            $hand = $this->game->trainCarManager->getPlayerHand($playerId);
            $remainingTrains = $this->game->getRemainingTrainCarsCount($playerId);
            foreach ($this->game->mapManager->claimableRoutes($playerId, $hand, $remainingTrains) as $route) {
                if ($route->ferryWaves > 0) { continue; }
                $color = $this->getZombieClaimColor($route, $hand, $remainingTrains, $playerId);
                if ($color !== null) {
                    return $this->actClaimRoute($route->id, $color, null, 0, $playerId);
                }
            }
            if ($this->game->destinationManager->getRemainingDestinationCardsInDeck() > 0) {
                return $this->actDrawDestinations($playerId);
            }
            return NextPlayer::class;
        } catch (\Throwable $e) { // safe catch : if the zombie cannot play, just pass
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
        $maximumDoubleRoutes = $this->game->getMap()->maximumPlayerForDoubleRoutes[$this->game->getPlayerCount()] ?? 2;
        $maximumTripleRoutes = $this->game->getMap()->maximumPlayerForTripleRoutes[$this->game->getPlayerCount()] ?? 3;

        $tracksByPair = [];
        foreach ($allRoutes as $route) {
            $tracksByPair[$this->getZombieRoutePairKey($route)][] = $route->id;
        }

        $playerClaimedRouteIds = [];
        $claimedOwnersByPair = [];
        $claimedTracksByPair = [];
        foreach ($claimedRoutes as $claimedRoute) {
            $playerClaimedRouteIds[$claimedRoute->routeId] = $claimedRoute->playerId;

            $route = $allRoutes[$claimedRoute->routeId];
            $pairKey = $this->getZombieRoutePairKey($route);
            if (!array_key_exists($pairKey, $claimedOwnersByPair)) {
                $claimedOwnersByPair[$pairKey] = [];
            }
            $claimedOwnersByPair[$pairKey][$claimedRoute->playerId] = true;
            $claimedTracksByPair[$pairKey][$claimedRoute->routeId] = true;
        }

        $adjacency = [];
        foreach ($allRoutes as $route) {
            $routeId = $route->id;
            if (array_key_exists($routeId, $playerClaimedRouteIds)) {
                if (!in_array($playerClaimedRouteIds[$routeId], [$playerId, -1])) {
                    continue;
                }
                $weight = 0;
            } else {
                $pairKey = $this->getZombieRoutePairKey($route);
                $pairOwners = $claimedOwnersByPair[$pairKey] ?? [];
                $maximumRoutes = count($tracksByPair[$pairKey]) > 2 ? $maximumTripleRoutes : (count($tracksByPair[$pairKey]) === 2 ? $maximumDoubleRoutes : 1);
                if (count($claimedTracksByPair[$pairKey] ?? []) >= $maximumRoutes || array_key_exists($playerId, $pairOwners)) {
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

                if ($possibleRoute->ferryWaves > 0) {
                    continue;
                }

                $color = $this->getZombieClaimColor($possibleRoute, $trainCarsHand, $remainingTrainCars, $playerId);
                if ($color !== null) {
                    return $this->actClaimRoute($possibleRoute->id, $color, null, 0, $playerId);
                }
            }
        }

        return null;
    }

    private function getZombieClaimColor(object $route, array $trainCarsHand, int $remainingTrainCars, int $playerId): ?int {
        $colorsToTest = $route->color > 0 ? [$route->color, 0] : [1,2,3,4,5,6,7,8,0];
        if ($route->locomotives === $route->number) {
            $colorsToTest = [0];
        }

        $bestColor = null;
        $bestLocomotiveCount = PHP_INT_MAX;
        foreach ($colorsToTest as $colorToTest) {
            $cost = $this->game->mapManager->canPayForRoute($route, $trainCarsHand, $remainingTrainCars, $colorToTest, playerId: $playerId);
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
        $key = min($route->from, $route->to).'-'.max($route->from, $route->to);
        if (!$this->game->getMap()->differentLengthRoutesAreDoubleRoutes) {
            $key .= '-'.$route->number;
        }
        return $key;
    }
}
