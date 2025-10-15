<?php

declare(strict_types=1);

namespace Bga\Games\TicketToRide\States;

use Bga\GameFramework\StateType;
use Bga\Games\TicketToRide\Game;

class NextPlayer extends \Bga\GameFramework\States\GameState
{

    function __construct(
        protected Game $game,
    ) {
        parent::__construct($game,
            id: ST_NEXT_PLAYER,
            type: StateType::GAME,
            updateGameProgression: true,
        );
    }

    function onEnteringState(int $activePlayerId) {
        $this->game->incStat(1, 'turnsNumber');
        $this->game->incStat(1, 'turnsNumber', $activePlayerId);

        $lastTurn = intval($this->game->getGameStateValue(LAST_TURN));

        // check if it was last action from player who started last turn
        if ($lastTurn == $activePlayerId) {
            return \ST_END_SCORE;
        }
        
        if ($lastTurn == 0) {
            // check if last turn is started    
            if ($this->game->getLowestTrainCarsCount() <= $this->game->getMap()->trainCarsNumberToStartLastTurn) {
                $this->game->setGameStateValue(LAST_TURN, $activePlayerId);

                $this->notify->all('lastTurn', clienttranslate('${player_name} has ${number} train cars or less, starting final turn !'), [
                    'playerId' => $activePlayerId,
                    'player_name' => $this->game->getPlayerNameById($activePlayerId),
                    'number' => $this->game->getMap()->trainCarsNumberToStartLastTurn,
                ]);
            }
        }

        $playerId = $this->game->activeNextPlayer();
        $this->game->giveExtraTime($playerId);
        return \ST_PLAYER_CHOOSE_ACTION;
    }
}
