<?php

namespace Bga\Games\TicketToRide\States;

use Bga\GameFramework\Actions\Types\IntArrayParam;
use Bga\GameFramework\States\GameState;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\StateType;
use Bga\GameFramework\UserException;
use Bga\GameFrameworkPrototype\Helpers\Arrays;
use Bga\Games\TicketToRide\Game;

class ChooseLegendaryCharacter extends GameState {
    public function __construct(protected Game $game)
    {
        parent::__construct($game,
            id: ST_PLAYER_CHOOSE_LEGENDARY_CHARACTER,
            type: StateType::ACTIVE_PLAYER,
            description: /* TODOLC clienttranslate*/('${actplayer} must choose a Legendary character'),
            descriptionMyTurn: /* TODOLC clienttranslate*/('${you} must choose a Legendary character'),
        );
    }

    function getArgs() {
        $remainingCharacters = $this->game->legendaryCharacterManager->getRemainingCharacters();

        return [
            'remainingCharacters' => $remainingCharacters,
        ];
    }

    #[PossibleAction]    
    public function actChooseCharacter(int $character, int $activePlayerId, array $args) {
        if (!in_array($character, $args['remainingCharacters'])) {
            throw new UserException("Invalid choice");
        }
        
        $this->game->legendaryCharacterManager->setCharacter($activePlayerId, $character);

        if ($this->game->legendaryCharacterManager->allPlayersHaveCharacter()) {
          return DealInitialDestinations::class;
        }

        $playerId = (int)$this->game->activePrevPlayer();
        $this->game->giveExtraTime($playerId);

        return ChooseLegendaryCharacter::class;
    }

    function zombie(int $playerId, array $args) {
        $choice = $this->getRandomZombieChoice($args['remainingCharacters']);
        return $this->actChooseCharacter($choice, $playerId, $args);
    }
}