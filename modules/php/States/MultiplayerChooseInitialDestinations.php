<?php

namespace Bga\Games\TicketToRide\States;

use Bga\GameFramework\States\GameState;
use Bga\GameFramework\StateType;
use Bga\Games\TicketToRide\Game;

class MultiplayerChooseInitialDestinations extends GameState {
    public function __construct(protected Game $game)
    {
        parent::__construct($game,
            id: ST_MULTIPLAYER_CHOOSE_INITIAL_DESTINATIONS,
            type: StateType::MULTIPLE_ACTIVE_PLAYER,
            name: 'multiChooseInitialDestinations',
            description: clienttranslate('Other players must choose destination tickets'),

            initialPrivate: PrivateChooseInitialDestinations::class,
        );
    }

    function onEnteringState() {
        $this->gamestate->setAllPlayersMultiactive();
        $this->gamestate->initializePrivateStateForAllActivePlayers(); 
    }

    function zombie(int $playerId) {
    }
}