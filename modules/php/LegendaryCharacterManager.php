<?php
declare(strict_types=1);

namespace Bga\Games\TicketToRide;

use Bga\GameFramework\Helpers\Json;
use Bga\GameFrameworkPrototype\Helpers\Arrays;

class LegendaryCharacterManager {

    function __construct(
        protected Game $game,
    ) {
        $this->game->bga->notify->addDecorator(function(string $message, array $args) {
            if (isset($args['character']) && !isset($args['character_name']) && str_contains($message, '${character_name}')) {
                $args['character_name'] = $this->getCharacterName($args['character']);
            }
            
            return $args;
        });
    }

    public function isActive(): bool {
        //return $this->game->tableOptions->get(LEGENDARY_CHARACTERS_EXPANSION_OPTION) === 1; // TODOLC
        return Game::getBgaEnvironment() === 'studio';
    }

    public function getAllCharacters(): array {
        return [1,2,3,4,5];
    }

    public function getCharacterName(int $character): string {
        return match($character) {
            1 => 'Phileas FOGG',
            2 => 'Irene ADLER',
            3 => 'Mina HARKER',
            4 => 'Captain NEMO',
            5 => 'Arsène LUPIN',
        };
    }

    public function getRemainingCharacters(): array {
        $setCharacters = Arrays::map($this->game->getObjectListFromDB("SELECT `player_legendary_character` FROM `player` WHERE `player_legendary_character` IS NOT NULL", true), fn($dbVal) => (int)$dbVal);
        return Arrays::diff(
            $this->getAllCharacters(),
            $setCharacters,
        );
    }

    public function allPlayersHaveCharacter(): bool {
        return $this->game->getUniqueIntValueFromDB("SELECT COUNT(*) FROM `player` WHERE `player_legendary_character` IS NULL") === 0;
    }

    public function getPlayerCharacter(int $playerId): ?int {
        $dbVal = $this->game->getUniqueValueFromDB("SELECT `player_legendary_character` FROM `player` WHERE `player_id` = $playerId");
        return $dbVal ? (int)$dbVal : null;
    }

    public function getPlayerCharacterState(int $playerId): mixed {
        $dbVal = $this->game->getUniqueValueFromDB("SELECT `player_legendary_character_state` FROM `player` WHERE `player_id` = $playerId");
        return $dbVal !== null ? Json::decode($dbVal) : null;
    }

    public function setCharacter(int $playerId, int $character): void {
        $this->game->DbQuery("UPDATE `player` SET `player_legendary_character` = $character WHERE `player_id` = $playerId");

        $this->game->notify->all('chooseCharacter', /*TODOLC clienttranslate*/('${player_name} chooses ${character_name}'), [
            'playerId' => $playerId,
            'character' => $character,
        ]);
    }

    public function setPlayerCharacterState(int $playerId, mixed $state): void {
        $stateJson = $state !== null ? Json::encode($state) : null;
        $this->game->DbQuery("UPDATE `player` SET `player_legendary_character_state` = ".($stateJson === null ? 'NULL' : "'".$this->game->escapeStringForDB($stateJson)."'")." WHERE `player_id` = $playerId");
    }

    public function onEndTurn(): void {
        if (!$this->isActive()) {
            return;
        }

        // character 3 can be played every turn, all others are single use
        $this->game->DbQuery("UPDATE `player` SET `player_legendary_character_state` = null WHERE `player_legendary_character` = 3");
    }
}
