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

    function endTunnelAttempt(bool $storedTunnelAttempt) {
        // put back tunnel cards
        $this->trainCars->moveAllCardsInLocation('tunnel', 'discard');

        if ($storedTunnelAttempt) {
            $this->deleteGlobalVariable(TUNNEL_ATTEMPT);
        }
    }
}
