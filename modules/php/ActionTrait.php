<?php

namespace Bga\Games\TicketToRide;

use Bga\GameFramework\Actions\Types\IntArrayParam;

require_once(__DIR__.'/objects/tunnel-attempt.php');

trait ActionTrait {

    //////////////////////////////////////////////////////////////////////////////
    //////////// Player actions
    //////////// 
    
    /*
        Each time a player is doing some game action, one of the methods below is called.
        (note: each method below must match an input method in nicodemus.action.php)
    */
    
    public function actChooseInitialDestinations(#[IntArrayParam] array $destinationsIds) {
        $playerId = intval(self::getCurrentPlayerId());

        $this->keepInitialDestinationCards($playerId, $destinationsIds);

        self::incStat(count($destinationsIds), 'keptInitialDestinationCards', $playerId);
        
        $this->gamestate->setPlayerNonMultiactive($playerId, 'start');
        self::giveExtraTime($playerId);
    }
    
    public function actChooseAdditionalDestinations(#[IntArrayParam] array $destinationsIds) {
        
        $playerId = intval(self::getActivePlayerId());

        $this->keepAdditionalDestinationCards($playerId, $destinationsIds);

        // player may have already completed picked destinations
        $this->checkCompletedDestinations($playerId);

        self::incStat(count($destinationsIds), 'keptAdditionalDestinationCards', $playerId);
        
        $this->gamestate->nextState('nextPlayer');
    }
    
    public function actDrawDeckCards(int $number) {        
        $playerId = intval(self::getActivePlayerId());

        $drawNumber = $this->drawTrainCarCardsFromDeck($playerId, $number);

        self::incStat($drawNumber, 'collectedTrainCarCards');
        self::incStat($drawNumber, 'collectedTrainCarCards', $playerId);
        self::incStat($drawNumber, 'collectedHiddenTrainCarCards');
        self::incStat($drawNumber, 'collectedHiddenTrainCarCards', $playerId);

        $this->gamestate->nextState($drawNumber == 1 && $this->canTakeASecondCard(null) ? 'drawSecondCard' : 'nextPlayer');
    }
    
    public function actDrawTableCard(int $id) {           
        $playerId = intval(self::getActivePlayerId());

        $card = $this->drawTrainCarCardsFromTable($playerId, $id);

        self::incStat(1, 'collectedTrainCarCards');
        self::incStat(1, 'collectedTrainCarCards', $playerId);
        self::incStat(1, 'collectedVisibleTrainCarCards');
        self::incStat(1, 'collectedVisibleTrainCarCards', $playerId);
        if ($card->type == 0) {
            self::incStat(1, 'collectedVisibleLocomotives');
            self::incStat(1, 'collectedVisibleLocomotives', $playerId);
        }

        $this->gamestate->nextState($this->canTakeASecondCard($card->type) ? 'drawSecondCard' : 'nextPlayer');
    }
    
    public function actDrawSecondDeckCard() {
        $playerId = intval(self::getActivePlayerId());

        $this->drawTrainCarCardsFromDeck($playerId, 1, true);

        self::incStat(1, 'collectedTrainCarCards');
        self::incStat(1, 'collectedTrainCarCards', $playerId);
        self::incStat(1, 'collectedHiddenTrainCarCards');
        self::incStat(1, 'collectedHiddenTrainCarCards', $playerId);

        $this->gamestate->nextState('nextPlayer'); 
    }
    
    public function actDrawSecondTableCard(int $id) {
        $playerId = intval(self::getActivePlayerId());

        $card = $this->drawTrainCarCardsFromTable($playerId, $id, true);

        self::incStat(1, 'collectedTrainCarCards');
        self::incStat(1, 'collectedTrainCarCards', $playerId);
        self::incStat(1, 'collectedVisibleTrainCarCards');
        self::incStat(1, 'collectedVisibleTrainCarCards', $playerId);
        if ($card->type == 0) {
            self::incStat(1, 'collectedVisibleLocomotives');
            self::incStat(1, 'collectedVisibleLocomotives', $playerId);
        }

        $this->gamestate->nextState('nextPlayer'); 
    }
    
    public function actDrawDestinations() {
        $playerId = intval(self::getActivePlayerId());

        $remainingDestinationsCardsInDeck = $this->getRemainingDestinationCardsInDeck();
        if ($remainingDestinationsCardsInDeck == 0) {
            throw new \BgaUserException(self::_("You can't take new Destination cards because the deck is empty"));
        }

        $this->pickAdditionalDestinationCards($playerId);

        self::incStat(1, 'drawDestinationsAction');
        self::incStat(1, 'drawDestinationsAction', $playerId);

        $this->gamestate->nextState('drawDestinations'); 
    }
    
    public function actClaimRoute(int $routeId, int $color) {
        $playerId = intval(self::getActivePlayerId());

        $route = $this->getAllRoutes()[$routeId];

        $remainingTrainCars = $this->getRemainingTrainCarsCount($playerId);
        if ($remainingTrainCars < $route->number) {
            self::notifyPlayer($playerId, 'notEnoughTrainCars', '', []);
            return;
        }

        if ($this->getUniqueIntValueFromDB( "SELECT count(*) FROM `claimed_routes` WHERE `route_id` = $routeId") > 0) {
            throw new \BgaUserException("Route is already claimed.");
        }
        
        $trainCarsHand = $this->getTrainCarsFromDb($this->trainCars->getCardsInLocation('hand', $playerId));
        $colorAndLocomotiveCards = $this->canPayForRoute($route, $trainCarsHand, $remainingTrainCars, $color);
        
        if ($colorAndLocomotiveCards == null || count($colorAndLocomotiveCards) < $route->number) {
            throw new \BgaUserException("Not enough cards to claim the route.");
        }

        $possibleRoutes = $this->claimableRoutes($playerId, $trainCarsHand, $remainingTrainCars);
        if (!$this->array_some($possibleRoutes, fn($possibleRoute) => $possibleRoute->id == $routeId)) {
            throw new \BgaUserException("You can't claim this route");
        }

        if ($route->tunnel) {
            $remainingDeckCards = $this->getRemainingTrainCarCardsInDeck(true);
            if ($remainingDeckCards == 0) {
                self::notifyAllPlayers('log', clienttranslate('No train car card in deck or discard, tunnel is free'), []);
            } else {
                $pickedCardCount = min(3, $remainingDeckCards);
                $tunnelCards = $this->getTrainCarsFromDb($this->trainCars->pickCardsForLocation($pickedCardCount, 'deck', 'tunnel'));
                $extraCards = count(array_filter($tunnelCards, fn($card) => $card->type == 0 || $card->type == $color));

                self::notifyAllPlayers('log', clienttranslate('${player_name} tries to build a tunnel from ${from} to ${to} with color ${color}'), [
                    'playerId' => $playerId,
                    'player_name' => $this->getPlayerName($playerId),
                    'from' => $this->getCityName($route->from),
                    'to' => $this->getCityName($route->to),
                    'color' => $color,
                ]);
                
                // show the revealed cards and log
                self::notifyAllPlayers($extraCards > 0 ? 'log' : 'freeTunnel', clienttranslate('${extraCards} extra cards over the ${pickedCards} train car cards revealed from the deck are needed to claim the route'), [
                    'pickedCards' => $pickedCardCount,
                    'extraCards' => $extraCards,
                    'tunnelCards' => $tunnelCards,
                ]);
                
                if ($extraCards > 0) { // if the player can't afford, we still ask to hide the fact he can't
                    $this->setGlobalVariable(TUNNEL_ATTEMPT, new \TunnelAttempt($routeId, $color, $extraCards, $tunnelCards));
                    $this->gamestate->nextState('tunnel'); 
                    return;
                } else {
                    // put back tunnel cards
                    $this->endTunnelAttempt(false);
                }
            }
        }

        $this->applyClaimRoute($playerId, $routeId, $color, 0);
    }

    function applyClaimRoute(int $playerId, int $routeId, int $color, int $extraCardCost = 0) {
        $route = $this->getAllRoutes()[$routeId];
        $cardCost = $route->number + $extraCardCost;
        
        $remainingTrainCars = $this->getRemainingTrainCarsCount($playerId);
        $trainCarsHand = $this->getTrainCarsFromDb($this->trainCars->getCardsInLocation('hand', $playerId));
        $cardsToRemove = $this->canPayForRoute($route, $trainCarsHand, $remainingTrainCars, $color, $extraCardCost);

        $this->trainCars->moveCards(array_map(fn($card) => $card->id, $cardsToRemove), 'discard');

        // save claimed route
        self::DbQuery("INSERT INTO `claimed_routes` (`route_id`, `player_id`) VALUES ($routeId, $playerId)");

        // update score
        $points = $this->getMap()->routePoints[$route->number];
        $this->incScore($playerId, $points);

        self::DbQuery("UPDATE player SET `player_remaining_train_cars` = `player_remaining_train_cars` - $route->number WHERE player_id = $playerId");
        
        self::notifyAllPlayers('claimedRoute', clienttranslate('${player_name} gains ${points} point(s) by claiming route from ${from} to ${to} with ${number} train car(s) : ${colors}'), [
            'playerId' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
            'points' => $points,
            'route' => $route,
            'from' => $this->getCityName($route->from),
            'to' => $this->getCityName($route->to),
            'number' => $cardCost,
            'removeCards' => $cardsToRemove,
            'colors' => array_map(fn($card) => $card->type, $cardsToRemove),
            'remainingTrainCars' => $this->getRemainingTrainCarsCount($playerId),
        ]);

        self::incStat(1, 'claimedRoutes');
        self::incStat(1, 'claimedRoutes', $playerId);
        self::incStat($route->number, 'playedTrainCars');
        self::incStat($route->number, 'playedTrainCars', $playerId);
        self::incStat($points, 'pointsWithClaimedRoutes');
        self::incStat($points, 'pointsWithClaimedRoutes', $playerId);

        $this->checkCompletedDestinations($playerId);

        // in case there is less than 5 visible cards on the table, we refill with newly discarded cards
        $this->checkVisibleTrainCarCards();

        $this->gamestate->nextState('nextPlayer'); 
    }
    
    public function actPass() {
        $args = $this->argChooseAction();

        if (!$args['canPass']) {
            throw new \BgaUserException("You cannot pass");
        }

        $this->gamestate->nextState('nextPlayer'); 
    }
    
    public function actClaimTunnel() {
        $playerId = intval(self::getActivePlayerId());

        $tunnelAttempt = $this->getGlobalVariable(TUNNEL_ATTEMPT);

        $this->endTunnelAttempt(true);

        $this->applyClaimRoute($playerId, $tunnelAttempt->routeId, $tunnelAttempt->color, $tunnelAttempt->extraCards);
        // applyClaimRoute handles the call to nextState
    }
    
    public function actSkipTunnel() {
        $playerId = intval(self::getActivePlayerId());
        
        $this->endTunnelAttempt(true);

        self::notifyAllPlayers('log', clienttranslate('${player_name} skip tunnel claim'), [
            'playerId' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
        ]);

        $this->gamestate->nextState('nextPlayer'); 
    }

    function endTunnelAttempt(bool $storedTunnelAttempt) {
        // put back tunnel cards
        $this->trainCars->moveAllCardsInLocation('tunnel', 'discard');

        if ($storedTunnelAttempt) {
            $this->deleteGlobalVariable(TUNNEL_ATTEMPT);
        }
    }
}
