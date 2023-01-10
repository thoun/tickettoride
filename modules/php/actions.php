<?php

require_once(__DIR__.'/objects/tunnel-attempt.php');

trait ActionTrait {

    //////////////////////////////////////////////////////////////////////////////
    //////////// Player actions
    //////////// 
    
    /*
        Each time a player is doing some game action, one of the methods below is called.
        (note: each method below must match an input method in nicodemus.action.php)
    */
    
    public function chooseInitialDestinations(array $ids) {
        self::checkAction('chooseInitialDestinations'); 
        
        $playerId = intval(self::getCurrentPlayerId());

        $this->keepInitialDestinationCards($playerId, $ids);

        self::incStat(count($ids), 'keptInitialDestinationCards', $playerId);
        
        $this->gamestate->setPlayerNonMultiactive($playerId, 'start');
        self::giveExtraTime($playerId);
    }
    
    public function chooseAdditionalDestinations(array $ids) {
        self::checkAction('chooseAdditionalDestinations'); 
        
        $playerId = intval(self::getActivePlayerId());

        $this->keepAdditionalDestinationCards($playerId, $ids);

        // player may have already completed picked destinations
        $this->checkCompletedDestinations($playerId);

        self::incStat(count($ids), 'keptAdditionalDestinationCards', $playerId);
        
        $this->gamestate->nextState('nextPlayer');
    }
  	
    public function drawDeckCards(int $number) {        
        self::checkAction('drawDeckCards'); 
        
        $playerId = intval(self::getActivePlayerId());

        $drawNumber = $this->drawTrainCarCardsFromDeck($playerId, $number);

        self::incStat($drawNumber, 'collectedTrainCarCards');
        self::incStat($drawNumber, 'collectedTrainCarCards', $playerId);
        self::incStat($drawNumber, 'collectedHiddenTrainCarCards');
        self::incStat($drawNumber, 'collectedHiddenTrainCarCards', $playerId);

        $this->gamestate->nextState($number == 1 && $this->canTakeASecondCard(null) ? 'drawSecondCard' : 'nextPlayer');
    }
  	
    public function drawTableCard(int $id) {
        self::checkAction('drawTableCard'); 
        
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
  	
    public function drawSecondDeckCard() {
        self::checkAction('drawSecondDeckCard'); 
        
        $playerId = intval(self::getActivePlayerId());

        $this->drawTrainCarCardsFromDeck($playerId, 1, true);

        self::incStat(1, 'collectedTrainCarCards');
        self::incStat(1, 'collectedTrainCarCards', $playerId);
        self::incStat(1, 'collectedHiddenTrainCarCards');
        self::incStat(1, 'collectedHiddenTrainCarCards', $playerId);

        $this->gamestate->nextState('nextPlayer'); 
    }
  	
    public function drawSecondTableCard(int $id) {
        self::checkAction('drawSecondTableCard'); 
        
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
  	
    public function drawDestinations() {
        self::checkAction('drawDestinations');
        
        $playerId = intval(self::getActivePlayerId());

        $remainingDestinationsCardsInDeck = $this->getRemainingDestinationCardsInDeck();
        if ($remainingDestinationsCardsInDeck == 0) {
            throw new BgaUserException(self::_("You can't take new Destination cards because the deck is empty"));
        }

        $this->pickAdditionalDestinationCards($playerId);

        self::incStat(1, 'drawDestinationsAction');
        self::incStat(1, 'drawDestinationsAction', $playerId);

        $this->gamestate->nextState('drawDestinations'); 
    }
  	
    public function claimRoute(int $routeId, int $color) {
        self::checkAction('claimRoute');
        
        $playerId = intval(self::getActivePlayerId());

        $route = $this->ROUTES[$routeId];

        $remainingTrainCars = $this->getRemainingTrainCarsCount($playerId);
        if ($remainingTrainCars < $route->number) {
            self::notifyPlayer($playerId, 'notEnoughTrainCars', '', []);
            return;
        }

        if ($this->getUniqueIntValueFromDB( "SELECT count(*) FROM `claimed_routes` WHERE `route_id` = $routeId") > 0) {
            throw new BgaUserException("Route is already claimed.");
        }
        
        $trainCarsHand = $this->getTrainCarsFromDb($this->trainCars->getCardsInLocation('hand', $playerId));
        $colorAndLocomotiveCards = $this->canPayForRoute($route, $trainCarsHand, $remainingTrainCars, $color);
        
        if ($colorAndLocomotiveCards == null || count($colorAndLocomotiveCards) < $route->number) {
            throw new BgaUserException("Not enough cards to claim the route.");
        }

        $possibleRoutes = $this->claimableRoutes($playerId, $trainCarsHand, $remainingTrainCars);
        if (!$this->array_some($possibleRoutes, fn($possibleRoute) => $possibleRoute->id == $routeId)) {
            throw new BgaUserException("You can't claim this route");
        }

        if ($route->tunnel) {
            $remainingDeckCards = $this->getRemainingTrainCarCardsInDeck(true);
            if ($remainingDeckCards == 0) {
                self::notifyAllPlayers('log', /* TODO MAPS clienttranslate*/('No train car card in deck or discard, tunnel is free'), []);
            } else {
                $pickedCardCount = min(3, $remainingDeckCards);
                $tunnelCards = $this->getTrainCarsFromDb($this->trainCars->pickCardsForLocation($pickedCardCount, 'deck', 'tunnel'));
                $extraCards = count(array_filter($tunnelCards, fn($card) => $card->type == 0 || $card->type == $color));

                
                // show the revealed cards and log
                self::notifyAllPlayers($extraCards > 0 ? 'log' : 'freeTunnel', /* TODO MAPS clienttranslate*/('${extraCards} extra cards over the ${pickedCards} train car cards revealed from the deck are needed to claim the route'), [
                    'pickedCards' => $pickedCardCount,
                    'extraCards' => $extraCards,
                    'tunnelCards' => $tunnelCards,
                ]);
                
                if ($extraCards > 0) { // TODO TOCHECK if the player can't afford, do we still ask to hide the fact he can't ?
                    $this->setGlobalVariable(TUNNEL_ATTEMPT, new TunnelAttempt($routeId, $color, $extraCards, $tunnelCards));
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
        $route = $this->ROUTES[$routeId];
        $cardCost = $route->number + $extraCardCost;
        
        $remainingTrainCars = $this->getRemainingTrainCarsCount($playerId);
        $trainCarsHand = $this->getTrainCarsFromDb($this->trainCars->getCardsInLocation('hand', $playerId));
        $cardsToRemove = $this->canPayForRoute($route, $trainCarsHand, $remainingTrainCars, $color, $extraCardCost);

        $this->trainCars->moveCards(array_map(fn($card) => $card->id, $cardsToRemove), 'discard');

        // save claimed route
        self::DbQuery("INSERT INTO `claimed_routes` (`route_id`, `player_id`) VALUES ($routeId, $playerId)");

        // update score
        $points = $this->ROUTE_POINTS[$route->number];
        $this->incScore($playerId, $points);

        self::DbQuery("UPDATE player SET `player_remaining_train_cars` = `player_remaining_train_cars` - $route->number WHERE player_id = $playerId");
        
        self::notifyAllPlayers('claimedRoute', clienttranslate('${player_name} gains ${points} point(s) by claiming route from ${from} to ${to} with ${number} train car(s) : ${colors}'), [
            'playerId' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
            'points' => $points,
            'route' => $route,
            'from' => $this->CITIES[$route->from],
            'to' => $this->CITIES[$route->to],
            'number' => $cardCost,
            'removeCards' => $cardsToRemove,
            'colors' => array_map(fn($card) => $card->type, $cardsToRemove),
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

    function pass() {
        self::checkAction('pass');
        
        $args = $this->argChooseAction();

        if (!$args['canPass']) {
            throw new BgaUserException("You cannot pass");
        }

        $this->gamestate->nextState('nextPlayer'); 
    }
  	
    public function claimTunnel() {
        self::checkAction('claimTunnel');
        
        $playerId = intval(self::getActivePlayerId());

        $tunnelAttempt = $this->getGlobalVariable(TUNNEL_ATTEMPT);

        $this->endTunnelAttempt(true);

        $this->applyClaimRoute($playerId, $tunnelAttempt->routeId, $tunnelAttempt->color, $tunnelAttempt->extraCards);
        // applyClaimRoute handles the call to nextState
    }
  	
    public function skipTunnel() {
        self::checkAction('skipTunnel');
        
        $playerId = intval(self::getActivePlayerId());
        
        $this->endTunnelAttempt(true);

        self::notifyAllPlayers('log', /* TODO MAPS clienttranslate*/('${player_name} skip tunnel claim'), [
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
