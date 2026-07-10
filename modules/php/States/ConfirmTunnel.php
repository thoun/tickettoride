<?php

namespace Bga\Games\TicketToRide\States;

use Bga\GameFramework\Actions\Types\IntArrayParam;
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

        $route = $this->game->mapManager->getAllRoutes()[$tunnelAttempt->routeId];
        $remainingTrainCars = $this->game->getRemainingTrainCarsCount($activePlayerId);        
        $trainCarsHand = $this->game->trainCarManager->getPlayerHand($activePlayerId);
        $legendaryCharacter = $this->game->legendaryCharacterManager->getPlayerCharacter($activePlayerId);
        $legendaryCharacterState = $this->game->legendaryCharacterManager->getPlayerCharacterState($activePlayerId);
        $considerAllRoutesGray = $legendaryCharacter === 5 && $legendaryCharacterState === 'using';
        $pairSetAsLocomotive = $this->game->legendaryCharacterManager->getCharacter3UsingColor($activePlayerId);
        $tunnelCost = $this->game->mapManager->canPayForRoute($route, $trainCarsHand, $remainingTrainCars, $tunnelAttempt->color, $tunnelAttempt->extraCards, pairSetAsLocomotive: $pairSetAsLocomotive, considerAllRoutesGray: $considerAllRoutesGray);
        $canPay = $tunnelCost != null;

        $extraCards = null;
        if ($canPay) {
            $routeCost = $this->game->mapManager->canPayForRoute($route, $trainCarsHand, $remainingTrainCars, $tunnelAttempt->color, pairSetAsLocomotive: $pairSetAsLocomotive, considerAllRoutesGray: $considerAllRoutesGray);
            $extraCards = array_values(array_filter($tunnelCost, fn($tunnelCard) => !Arrays::some($routeCost, fn($routeCard) => $routeCard->id == $tunnelCard->id)));
        }

        $args = [
            'playerId' => $activePlayerId,
            'tunnelAttempt' => $tunnelAttempt,
            'canPay' => $canPay,
            'colors' => $extraCards == null ? '' : array_map(fn($card) => $card->type, $extraCards), // for title bar
            'extraCards' => $tunnelAttempt->extraCards, // for title bar
        ];

        if ($canPay && isset($tunnelAttempt->distribution)) {
            $args['_private'] = [
                $activePlayerId => [
                    'trainCarsHand' => $trainCarsHand,
                ],
            ];
        }

        return $args;
    }

    #[PossibleAction]    
    public function actClaimTunnel(#[IntArrayParam()] ?array $distribution, int $activePlayerId) {
        $tunnelAttempt = $this->game->getGlobalVariable(TUNNEL_ATTEMPT);

        $this->game->endTunnelAttempt(true);

        $distributionCards = $distribution ? Arrays::filter($this->game->trainCarManager->getPlayerHand($activePlayerId), fn($card) => in_array($card->id, $distribution)) : null;

        $shifted = $this->game->legendaryCharacterManager->getPlayerCharacter($activePlayerId) === 1
            && $this->game->legendaryCharacterManager->getPlayerCharacterState($activePlayerId) === 'using';
        $this->game->applyClaimRoute($activePlayerId, $tunnelAttempt->routeId, $tunnelAttempt->color, $tunnelAttempt->extraCards, distributionCards: $distributionCards, shifted: $shifted);

        if ($this->game->legendaryCharacterManager->getPlayerCharacter($activePlayerId) === 4 && count($this->game->legendaryCharacterManager->getCharacter4UsingRouteIds($activePlayerId)) > 0) {
            if ($this->game->legendaryCharacterManager->character4CanClaimAnotherRoute($activePlayerId)) {
                return ChooseAction::class;
            }

            $this->game->legendaryCharacterManager->onCharacter4Pass($activePlayerId);
        }

        return NextPlayer::class;
    }

    #[PossibleAction]
    public function actSkipTunnel(int $activePlayerId) {
        $this->game->endTunnelAttempt(true);

        if ($this->game->legendaryCharacterManager->getPlayerCharacter($activePlayerId) === 1
            && $this->game->legendaryCharacterManager->getPlayerCharacterState($activePlayerId) === 'using') {
            $this->game->legendaryCharacterManager->setPlayerCharacterState($activePlayerId, null);
        }

        $this->notify->all('log', clienttranslate('${player_name} skip tunnel claim'), [
            'playerId' => $activePlayerId,
            'player_name' => $this->game->getPlayerNameById($activePlayerId),
        ]);

        return ST_NEXT_PLAYER;
    }

    function zombie(int $playerId, array $args) {
        return $args['canPay'] ? $this->actClaimTunnel(null, $playerId) : $this->actSkipTunnel($playerId);
    }
}
