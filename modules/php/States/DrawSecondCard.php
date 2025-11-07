<?php

namespace Bga\Games\TicketToRideMaps\States;

use Bga\GameFramework\States\GameState;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\StateType;
use Bga\Games\TicketToRideMaps\Game;

class DrawSecondCard extends GameState {
    public function __construct(protected Game $game)
    {
        parent::__construct($game,
            id: ST_PLAYER_DRAW_SECOND_CARD,
            type: StateType::ACTIVE_PLAYER,
            name: 'drawSecondCard',
            description: clienttranslate('${actplayer} must draw a train car card'),
            descriptionMyTurn: clienttranslate('${you} must draw a train car card'),
        );
    }

    function getArgs() {
        $maxHiddenCardsPick = min(1, $this->game->trainCarManager->getRemainingTrainCarCardsInDeck(true));
        $availableVisibleCards = $this->game->trainCarManager->getVisibleTrainCarCards(true);

        return [
            'maxHiddenCardsPick' => $maxHiddenCardsPick,
            'availableVisibleCards' => $availableVisibleCards,
        ];
    }

    #[PossibleAction]
    public function actDrawSecondDeckCard(int $activePlayerId) {
        $this->game->trainCarManager->drawTrainCarCardsFromDeck($activePlayerId, 1, true);

        $this->game->incStat(1, 'collectedTrainCarCards');
        $this->game->incStat(1, 'collectedTrainCarCards', $activePlayerId);
        $this->game->incStat(1, 'collectedHiddenTrainCarCards');
        $this->game->incStat(1, 'collectedHiddenTrainCarCards', $activePlayerId);

       return ST_NEXT_PLAYER;
    }
    
    #[PossibleAction]
    public function actDrawSecondTableCard(int $id, int $activePlayerId) {
        $card = $this->game->trainCarManager->drawTrainCarCardsFromTable($activePlayerId, $id, true);

        $this->game->incStat(1, 'collectedTrainCarCards');
        $this->game->incStat(1, 'collectedTrainCarCards', $activePlayerId);
        $this->game->incStat(1, 'collectedVisibleTrainCarCards');
        $this->game->incStat(1, 'collectedVisibleTrainCarCards', $activePlayerId);
        if ($card->type == 0) {
            $this->game->incStat(1, 'collectedVisibleLocomotives');
            $this->game->incStat(1, 'collectedVisibleLocomotives', $activePlayerId);
        }

        return ST_NEXT_PLAYER; 
    }

    function zombie(int $playerId) {
        return $this->actDrawSecondDeckCard($playerId);
    }
}