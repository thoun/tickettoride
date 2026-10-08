<?php

namespace Bga\Games\TicketToRide\States;

use Bga\GameFramework\States\GameState;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\StateType;
use Bga\GameFramework\UserException;
use Bga\Games\TicketToRide\Game;

class MoveAlvin extends GameState {
    public function __construct(protected Game $game) {
        parent::__construct($game,
            id: ST_PLAYER_MOVE_ALVIN,
            type: StateType::ACTIVE_PLAYER,
            name: 'MoveAlvin',
            description: clienttranslate('${actplayer} must move Alvin to a city they control'),
            descriptionMyTurn: clienttranslate('${you} must choose a city you control for Alvin'),
        );
    }

    function getArgs(int $activePlayerId): array {
        return ['possibleCityIds' => array_map(fn($marker) => $marker->cityId,
            $this->game->buildingManager->getPlacedCityMarkers($activePlayerId))];
    }

    function onEnteringState(int $activePlayerId) {
        $cities = $this->getArgs($activePlayerId)['possibleCityIds'];
        if (count($cities) === 1) {
            return $this->actMoveAlvin($cities[0], $activePlayerId);
        }
    }

    #[PossibleAction]
    public function actMoveAlvin(int $cityId, int $activePlayerId) {
        $alvin = $this->game->getMap()->getMapSpecificData($this->game)['alvin'] ?? null;
        if ($alvin === null || $alvin['playerId'] !== $activePlayerId
            || $this->bga->globals->get('ALVIN_MOVE_PENDING', null) !== $activePlayerId
            || !in_array($cityId, $this->getArgs($activePlayerId)['possibleCityIds'], true)) {
            throw new UserException('You must move Alvin to a city you control.');
        }
        $alvin['cityId'] = $cityId;
        $this->bga->globals->set('ALVIN', $alvin);
        $this->bga->globals->set('ALVIN_MOVE_PENDING', null);
        $nextState = $this->bga->globals->get('ALVIN_MOVE_NEXT_STATE', NextPlayer::class);
        $this->bga->globals->set('ALVIN_MOVE_NEXT_STATE', null);
        $this->notify->all('alvinUpdated', clienttranslate('${player_name} moves Alvin to ${city_name}'), [
            'playerId' => $activePlayerId,
            'player_name' => $this->game->getPlayerNameById($activePlayerId),
            'city_name' => $this->game->getCityName($cityId),
            'alvin' => $alvin,
        ]);
        return $nextState;
    }

    function zombie(int $playerId, array $args) {
        return $this->actMoveAlvin($this->getRandomZombieChoice($args['possibleCityIds']), $playerId);
    }
}
