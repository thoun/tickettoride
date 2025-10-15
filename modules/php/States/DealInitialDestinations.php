<?php

namespace Bga\Games\TicketToRide\States;

use Bga\GameFramework\Actions\Types\IntArrayParam;
use Bga\GameFramework\States\GameState;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\StateType;
use Bga\GameFrameworkPrototype\Helpers\Arrays;
use Bga\Games\TicketToRide\Game;

class DealInitialDestinations extends GameState {
    public function __construct(protected Game $game)
    {
        parent::__construct($game,
            id: ST_DEAL_INITIAL_DESTINATIONS,
            type: StateType::GAME,
        );
    }

    function onEnteringState() {
        $playersIds = $this->game->getPlayersIds();

        foreach($playersIds as $playerId) {
            $this->game->pickInitialDestinationCards($playerId);
        }
        
        return MultiplayerChooseInitialDestinations::class;
    }
}