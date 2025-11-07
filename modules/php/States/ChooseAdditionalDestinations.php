<?php

namespace Bga\Games\TicketToRideMaps\States;

use Bga\GameFramework\Actions\Types\IntArrayParam;
use Bga\GameFramework\States\GameState;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\StateType;
use Bga\GameFrameworkPrototype\Helpers\Arrays;
use Bga\Games\TicketToRideMaps\Game;

class ChooseAdditionalDestinations extends GameState {
    public function __construct(protected Game $game)
    {
        parent::__construct($game,
            id: ST_PLAYER_CHOOSE_ADDITIONAL_DESTINATIONS,
            type: StateType::ACTIVE_PLAYER,
            name: 'chooseAdditionalDestinations',
            description: clienttranslate('${actplayer} must choose destination tickets'),
            descriptionMyTurn: clienttranslate('${you} must choose destination tickets (minimum ${minimum})'),
        );
    }

    function getArgs(int $activePlayerId) {
        $destinations = $this->game->destinationManager->getPickedDestinationCards($activePlayerId);

        return [
            'minimum' => $this->game->getMap()->additionalDestinationMinimumKept,
            '_private' => [          // Using "_private" keyword, all data inside this array will be made private
                'active' => [       // Using "active" keyword inside "_private", you select active player(s)
                    'destinations' => $destinations,   // will be send only to active player(s)
                ]
            ],
        ];
    }

    #[PossibleAction]
    public function actChooseAdditionalDestinations(#[IntArrayParam] array $destinationsIds, int $activePlayerId) {
        $this->game->destinationManager->keepAdditionalDestinationCards($activePlayerId, $destinationsIds);

        // player may have already completed picked destinations
        $this->game->destinationManager->checkCompletedDestinations($activePlayerId);

        $this->game->incStat(count($destinationsIds), 'keptAdditionalDestinationCards', $activePlayerId);
        
        return ST_NEXT_PLAYER;
    }

    function zombie(int $playerId, array $args) {
        $destinations = $args['_private']['active']['destinations'];
        shuffle($destinations);
        $kept = array_slice($destinations, 0, $args['minimum']);
        return $this->actChooseAdditionalDestinations(Arrays::map($kept, fn($card) => $card->id), $playerId);
    }
}