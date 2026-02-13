<?php

namespace Bga\Games\TicketToRideEurope\States;

use Bga\GameFramework\States\GameState;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\StateType;
use Bga\Games\TicketToRideEurope\Game;

class ChooseStartingCity extends GameState {
    public function __construct(protected Game $game) {
        parent::__construct($game,
            id: ST_PLAYER_CHOOSE_STARTING_CITY,
            type: StateType::ACTIVE_PLAYER,
            description: clienttranslate('${actplayer} must choose a starting city'),
            descriptionMyTurn: clienttranslate('${you} must choose a starting city'),
        );
    }

    function getArgs(): array {
        return ['possibleCityIds' => $this->game->buildingManager->getUncontrolledCityIds()];
    }

    #[PossibleAction]
    public function actChooseStartingCity(int $cityId, int $activePlayerId) {
        $this->game->buildingManager->placeStartingCityMarker($activePlayerId, $cityId);

        foreach ($this->game->getPlayersIds() as $playerId) {
            if (empty($this->game->buildingManager->getPlacedCityMarkers($playerId))) {
                $previousPlayerId = (int) $this->game->activePrevPlayer();
                $this->game->giveExtraTime($previousPlayerId);
                return self::class;
            }
        }

        // Reverse selection ends on the first player, who takes the first turn.
        return ChooseAction::class;
    }

    function zombie(int $playerId, array $args) {
        return $this->actChooseStartingCity($this->getRandomZombieChoice($args['possibleCityIds']), $playerId);
    }
}
