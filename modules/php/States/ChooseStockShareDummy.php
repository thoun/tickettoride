<?php

namespace Bga\Games\TicketToRideMaps\States;

use Bga\GameFramework\States\PossibleAction;
use Bga\Games\TicketToRideMaps\Game;

class ChooseStockShareDummy extends ChooseStockShare {
    public function __construct(Game $game) {
        parent::__construct($game, true);
    }

    #[PossibleAction]
    public function actChooseStockShare(int $type, int $activePlayerId) {
        $this->takeStockShare($type, 'STOCK_SHARE_CARDS_DUMMY', null);

        return NextPlayer::class;
    }
}
