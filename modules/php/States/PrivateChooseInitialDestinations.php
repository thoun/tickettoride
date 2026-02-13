<?php

namespace Bga\Games\TicketToRideEurope\States;

use Bga\GameFramework\Actions\Types\IntArrayParam;
use Bga\GameFramework\States\GameState;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\StateType;
use Bga\GameFrameworkPrototype\Helpers\Arrays;
use Bga\Games\TicketToRideEurope\Game;

class PrivateChooseInitialDestinations extends GameState {
    public function __construct(protected Game $game)
    {
        parent::__construct($game,
            id: ST_PRIVATE_CHOOSE_INITIAL_DESTINATIONS,
            type: StateType::PRIVATE,
            name: 'privateChooseInitialDestinations',
            descriptionMyTurn:  clienttranslate('${you} must choose destination tickets (minimum ${minimum})'),
        );
    }

    function getArgs(int $playerId) {
        return [
            'minimum' => $this->game->getMap()->getInitialDestinationMinimumKept($this->game->getExpansionOption()),
            'destinations' => $this->game->destinationManager->getPickedDestinationCards($playerId),
        ];
    }

    #[PossibleAction]    
    public function actChooseInitialDestinations(#[IntArrayParam] array $destinationsIds, int $currentPlayerId) {
        $this->game->destinationManager->keepInitialDestinationCards($currentPlayerId, $destinationsIds);

        $this->game->incStat(count($destinationsIds), 'keptInitialDestinationCards', $currentPlayerId);
        
        $this->gamestate->setPlayerNonMultiactive($currentPlayerId, ChooseAction::class);
        $this->game->giveExtraTime($currentPlayerId);
    }

    function zombie(int $playerId, array $args) {
        $destinations = $args['destinations'];
        shuffle($destinations);
        $kept = array_slice($destinations, 0, $args['minimum']);
        return $this->actChooseInitialDestinations(Arrays::map($kept, fn($card) => $card->id), $playerId);
    }
}