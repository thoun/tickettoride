<?php

namespace Bga\Games\TicketToRide\States;

use Bga\GameFramework\States\GameState;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\StateType;
use Bga\GameFrameworkPrototype\Helpers\Arrays;
use Bga\Games\TicketToRide\Game;

class ChooseAction extends GameState {
    public function __construct(protected Game $game)
    {
        parent::__construct($game,
            id: ST_PLAYER_CHOOSE_ACTION,
            type: StateType::ACTIVE_PLAYER,
            name: 'chooseAction',
            description: clienttranslate('${actplayer} must draw train car cards, claim a route or draw destination tickets'),
            descriptionMyTurn: clienttranslate('${you} must draw train car cards, claim a route or draw destination tickets'),
            transitions: [
                "drawSecondCard" => ST_PLAYER_DRAW_SECOND_CARD,
                "drawDestinations" => ST_PLAYER_CHOOSE_ADDITIONAL_DESTINATIONS,
                "tunnel" => ST_PLAYER_CONFIRM_TUNNEL,
                "nextPlayer" => ST_NEXT_PLAYER,
            ]
        );
    }

    function getArgs(int $activePlayerId) {
        $trainCarsHand = $this->game->getTrainCarsFromDb($this->game->trainCars->getCardsInLocation('hand', $activePlayerId));
        // we don't limit claimable routes to the number of remaining train cars, because the players don't understand why they can't claim the route
        // so instead they'll get an error when they try to claim the route, saying they don't have enough train cars left
        $remainingTrainCars = 99;
        $realRemainingTrainCars = $this->game->getRemainingTrainCarsCount($activePlayerId);

        $possibleRoutes = $this->game->claimableRoutes($activePlayerId, $trainCarsHand, $remainingTrainCars);
        $maxHiddenCardsPick = min(2, $this->game->getRemainingTrainCarCardsInDeck(true));
        $maxDestinationsPick = min($this->game->getMap()->getAdditionalDestinationCardNumber($this->game->getExpansionOption()), $this->game->destinationManager->getRemainingDestinationCardsInDeck());

        $canClaimARoute = false;
        $costForRoute = [];
        foreach($possibleRoutes as $possibleRoute) {
            $colorsToTest = $possibleRoute->color > 0 ? [0, $possibleRoute->color] : [0,1,2,3,4,5,6,7,8];
            $costByColor = [];
            foreach($colorsToTest as $colorToTest) {
                $costByColor[$colorToTest] = $this->game->canPayForRoute($possibleRoute, $trainCarsHand, 99, $colorToTest);

                if (!$canClaimARoute && $costByColor[$colorToTest] != null && count($costByColor[$colorToTest]) <= $realRemainingTrainCars) {
                    $canClaimARoute = true;
                }
            }
            $costForRoute[$possibleRoute->id] = array_map(fn($cardCost) => $cardCost == null ? null : array_map(fn($card) => $card->type, $cardCost), $costByColor);
        }

        $canTakeTrainCarCards = $this->game->getRemainingTrainCarCardsInDeck(true, true);

        $canPass = !$canClaimARoute && $maxDestinationsPick == 0 && $canTakeTrainCarCards == 0;

        return [
            'possibleRoutes' => $possibleRoutes,
            'costForRoute' => $costForRoute,
            'maxHiddenCardsPick' => $maxHiddenCardsPick,
            'maxDestinationsPick' => $maxDestinationsPick,
            'canTakeTrainCarCards' => $canTakeTrainCarCards,
            'canPass' => $canPass,
        ];
    }

    #[PossibleAction]
    public function actDrawDeckCards(int $number, int $activePlayerId) { 
        $drawNumber = $this->game->drawTrainCarCardsFromDeck($activePlayerId, $number);

        $this->game->incStat($drawNumber, 'collectedTrainCarCards');
        $this->game->incStat($drawNumber, 'collectedTrainCarCards', $activePlayerId);
        $this->game->incStat($drawNumber, 'collectedHiddenTrainCarCards');
        $this->game->incStat($drawNumber, 'collectedHiddenTrainCarCards', $activePlayerId);

       return $drawNumber == 1 && $this->game->canTakeASecondCard(null) ? DrawSecondCard::class : ST_NEXT_PLAYER;
    }
    
    #[PossibleAction]
    public function actDrawTableCard(int $id, int $activePlayerId) { 
        $card = $this->game->drawTrainCarCardsFromTable($activePlayerId, $id);

        $this->game->incStat(1, 'collectedTrainCarCards');
        $this->game->incStat(1, 'collectedTrainCarCards', $activePlayerId);
        $this->game->incStat(1, 'collectedVisibleTrainCarCards');
        $this->game->incStat(1, 'collectedVisibleTrainCarCards', $activePlayerId);
        if ($card->type == 0) {
            $this->game->incStat(1, 'collectedVisibleLocomotives');
            $this->game->incStat(1, 'collectedVisibleLocomotives', $activePlayerId);
        }

        return $this->game->canTakeASecondCard($card->type) ? DrawSecondCard::class : ST_NEXT_PLAYER;
    }
    
    #[PossibleAction]
    public function actDrawDestinations(int $activePlayerId) {
        $remainingDestinationsCardsInDeck = $this->game->destinationManager->getRemainingDestinationCardsInDeck();
        if ($remainingDestinationsCardsInDeck == 0) {
            throw new \BgaUserException(clienttranslate("You can't take new Destination cards because the deck is empty"));
        }

        $this->game->destinationManager->pickAdditionalDestinationCards($activePlayerId);

        $this->game->incStat(1, 'drawDestinationsAction');
        $this->game->incStat(1, 'drawDestinationsAction', $activePlayerId);

        return ChooseAdditionalDestinations::class;
    }
    
    #[PossibleAction]
    public function actClaimRoute(int $routeId, int $color, int $activePlayerId) {
        $route = $this->game->getAllRoutes()[$routeId];

        $remainingTrainCars = $this->game->getRemainingTrainCarsCount($activePlayerId);
        if ($remainingTrainCars < $route->number) {
            $this->notify->player($activePlayerId, 'notEnoughTrainCars', '', []);
            return;
        }

        if ($this->game->getUniqueIntValueFromDB( "SELECT count(*) FROM `claimed_routes` WHERE `route_id` = $routeId") > 0) {
            throw new \BgaUserException("Route is already claimed.");
        }
        
        $trainCarsHand = $this->game->getTrainCarsFromDb($this->game->trainCars->getCardsInLocation('hand', $activePlayerId));
        $colorAndLocomotiveCards = $this->game->canPayForRoute($route, $trainCarsHand, $remainingTrainCars, $color);
        
        if ($colorAndLocomotiveCards == null || count($colorAndLocomotiveCards) < $route->number) {
            throw new \BgaUserException("Not enough cards to claim the route.");
        }

        $possibleRoutes = $this->game->claimableRoutes($activePlayerId, $trainCarsHand, $remainingTrainCars);
        if (!Arrays::some($possibleRoutes, fn($possibleRoute) => $possibleRoute->id == $routeId)) {
            throw new \BgaUserException("You can't claim this route");
        }

        if ($route->tunnel) {
            $remainingDeckCards = $this->game->getRemainingTrainCarCardsInDeck(true);
            if ($remainingDeckCards == 0) {
                $this->notify->all('log', clienttranslate('No train car card in deck or discard, tunnel is free'), []);
            } else {
                $pickedCardCount = min(3, $remainingDeckCards);
                $tunnelCards = $this->game->getTrainCarsFromDb($this->game->trainCars->pickCardsForLocation($pickedCardCount, 'deck', 'tunnel'));
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
                    $this->game->setGlobalVariable(TUNNEL_ATTEMPT, new \TunnelAttempt($routeId, $color, $extraCards, $tunnelCards));
                    $this->gamestate->nextState('tunnel'); 
                    return;
                } else {
                    // put back tunnel cards
                    $this->game->endTunnelAttempt(false);
                }
            }
        }

        $this->game->applyClaimRoute($activePlayerId, $routeId, $color, 0);
    }
    
    #[PossibleAction]
    public function actPass(array $args) {
        if (!$args['canPass']) {
            throw new \BgaUserException("You cannot pass");
        }

        return ST_NEXT_PLAYER;
    }

    function zombie(int $playerId, array $args) {
        if ($args['canPass']) {
            return $this->actPass($args);
        }

        // TODO check if it can build a route that will help complete a ticket (and return)
        // TODO if all tickets are completed and there is at least 8 train cars for each player, draw new tickets (and return)

        return $this->actDrawDeckCards(2, $playerId);
    }
}