<?php

namespace Bga\Games\TicketToRide\States;

use Bga\GameFramework\States\GameState;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\StateType;
use Bga\GameFramework\UserException;
use Bga\Games\TicketToRide\Game;

class PlaceCityMarker extends GameState {
    public function __construct(protected Game $game) {
        parent::__construct($game,
            id: ST_PLAYER_PLACE_CITY_MARKER,
            type: StateType::ACTIVE_PLAYER,
            name: 'PlaceCityMarker',
            description: clienttranslate('${actplayer} may place a City Marker'),
            descriptionMyTurn: clienttranslate('${you} may place a City Marker'),
        );
    }

    function getArgs(int $activePlayerId): array {
        $placement = $this->bga->globals->get('CITY_MARKER_PLACEMENT', null);
        $costByColor = [];
        $hand = $this->game->trainCarManager->getPlayerHand($activePlayerId);
        foreach (range(0, 8) as $color) {
            $cards = $this->game->buildingManager->canPayForStation($hand, 2, $color);
            if ($cards !== null) {
                $costByColor[$color] = array_map(fn($card) => $card->type, $cards);
            }
        }
        return [
            'possibleCityIds' => $placement === null ? []
                : $this->game->buildingManager->getCityMarkerPlacementCityIds($activePlayerId, $placement['routeId']),
            '_private' => [$activePlayerId => ['costByColor' => $costByColor]],
            '_merge_private' => true,
        ];
    }

    function onEnteringState(int $activePlayerId) {
        $args = $this->getArgs($activePlayerId);
        if (empty($args['possibleCityIds']) || empty($args['_private'][$activePlayerId]['costByColor'])) {
            return $this->finish();
        }
    }

    #[PossibleAction]
    public function actPlaceCityMarker(int $cityId, int $color, int $activePlayerId) {
        $placement = $this->bga->globals->get('CITY_MARKER_PLACEMENT', null);
        if ($placement === null) {
            throw new UserException('You cannot place a City Marker now.');
        }
        $this->game->buildingManager->placeCityMarker($activePlayerId, $placement['routeId'], $cityId, $color);
        return $this->finish();
    }

    #[PossibleAction]
    public function actPassCityMarker() {
        return $this->finish();
    }

    private function finish(): string {
        $placement = $this->bga->globals->get('CITY_MARKER_PLACEMENT', null);
        $this->bga->globals->set('CITY_MARKER_PLACEMENT', null);
        return $placement['nextState'] ?? NextPlayer::class;
    }

    function zombie(int $playerId, array $args) {
        return $this->actPassCityMarker();
    }
}
