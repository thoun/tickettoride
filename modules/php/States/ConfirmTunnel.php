<?php

namespace Bga\Games\TicketToRide\States;

use Bga\GameFramework\States\GameState;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\StateType;
use Bga\GameFrameworkPrototype\Helpers\Arrays;
use Bga\Games\TicketToRide\Game;

class ConfirmTunnel extends GameState {
    public function __construct(protected Game $game)
    {
        parent::__construct($game,
            id: ST_PLAYER_CONFIRM_TUNNEL,
            type: StateType::ACTIVE_PLAYER,
            name: 'confirmTunnel',
            description: clienttranslate('${actplayer} must confirm tunnel claim using ${extraCards} extra card(s) ${colors}'),
            descriptionMyTurn: clienttranslate('${you} must confirm tunnel claim using ${extraCards} extra card(s) ${colors}'),
            
            transitions: [
                "nextPlayer" => ST_NEXT_PLAYER,
            ],
        );
    }

    function getArgs(int $activePlayerId) {
        $tunnelAttempt = $this->game->getGlobalVariable(TUNNEL_ATTEMPT);

        $route = $this->game->getAllRoutes()[$tunnelAttempt->routeId];
        $remainingTrainCars = $this->game->getRemainingTrainCarsCount($activePlayerId);        
        $trainCarsHand = $this->game->trainCarManager->getPlayerHand($activePlayerId);
        $tunnelCost = $this->game->canPayForRoute($route, $trainCarsHand, $remainingTrainCars, $tunnelAttempt->color, $tunnelAttempt->extraCards);
        $canPay = $tunnelCost != null;

        $extraCards = null;
        if ($canPay) {
            $routeCost = $this->game->canPayForRoute($route, $trainCarsHand, $remainingTrainCars, $tunnelAttempt->color);
            $extraCards = array_values(array_filter($tunnelCost, fn($tunnelCard) => !Arrays::some($routeCost, fn($routeCard) => $routeCard->id == $tunnelCard->id)));
        }

        return [
            'playerId' => $activePlayerId,
            'tunnelAttempt' => $tunnelAttempt,
            'canPay' => $canPay,
            'colors' => $extraCards == null ? '' : array_map(fn($card) => $card->type, $extraCards), // for title bar
            'extraCards' => $tunnelAttempt->extraCards, // for title bar
        ];
    }

    #[PossibleAction]    
    public function actClaimTunnel(int $activePlayerId) {
        $tunnelAttempt = $this->game->getGlobalVariable(TUNNEL_ATTEMPT);

        $this->game->endTunnelAttempt(true);

        $this->game->applyClaimRoute($activePlayerId, $tunnelAttempt->routeId, $tunnelAttempt->color, $tunnelAttempt->extraCards);
        // applyClaimRoute handles the call to nextState
    }

    #[PossibleAction]
    public function actSkipTunnel(int $activePlayerId) {
        $this->game->endTunnelAttempt(true);

        $this->notify->all('log', clienttranslate('${player_name} skip tunnel claim'), [
            'playerId' => $activePlayerId,
            'player_name' => $this->game->getPlayerNameById($activePlayerId),
        ]);

        return ST_NEXT_PLAYER;
    }

    function zombie(int $playerId, array $args) {
        return $args['canPay'] ? $this->actClaimTunnel($playerId) : $this->actSkipTunnel($playerId);
    }
}