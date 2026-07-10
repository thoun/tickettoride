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

    /**
     * Character 4 keeps the routes claimed during its optional extra-route
     * sequence in the state itself: using:routeId[:routeId...].
     *
     * @return int[]
     */
    public function getCharacter4UsingRouteIds(int $playerId): array {
        if ($this->getPlayerCharacter($playerId) !== 4) {
            return [];
        }

        $state = $this->getPlayerCharacterState($playerId);
        if (!is_string($state) || !str_starts_with($state, 'using:')) {
            return [];
        }

        $routeIds = array_slice(explode(':', $state), 1);
        return array_values(array_filter(array_map('intval', $routeIds), fn($routeId) => $routeId > 0));
    }

    public function onCharacter4RouteClaim(int $playerId, int $routeId): void {
        if ($this->getPlayerCharacter($playerId) !== 4) {
            return;
        }

        $state = $this->getPlayerCharacterState($playerId);
        if ($state !== null && (!is_string($state) || !str_starts_with($state, 'using:'))) {
            return;
        }

        $routeIds = $this->getCharacter4UsingRouteIds($playerId);
        $routeIds[] = $routeId;
        $this->setPlayerCharacterState($playerId, 'using:'.implode(':', $routeIds));
    }

    public function onCharacter4Pass(int $playerId): void {
        $routeIds = $this->getCharacter4UsingRouteIds($playerId);
        if (count($routeIds) === 1) {
            // The first route was a normal action; the power was not used.
            $this->setPlayerCharacterState($playerId, null);
        } elseif (count($routeIds) > 1) {
            $this->setPlayerCharacterState($playerId, 'used');
        }
    }

    public function filterCharacter4Routes(int $playerId, array $routes): array {
        $routeIds = $this->getCharacter4UsingRouteIds($playerId);
        if (count($routeIds) === 0) {
            return $routes;
        }

        $allRoutes = $this->game->mapManager->getAllRoutes();
        $cities = [];
        $claimedRouteSpaces = 0;
        foreach ($routeIds as $routeId) {
            if (isset($allRoutes[$routeId])) {
                $cities[] = $allRoutes[$routeId]->from;
                $cities[] = $allRoutes[$routeId]->to;
                $claimedRouteSpaces += $allRoutes[$routeId]->number;
            }
        }

        return array_values(array_filter($routes, fn($route) =>
            ($route->number + $claimedRouteSpaces <= 7)
            && (in_array($route->from, $cities) || in_array($route->to, $cities))
        ));
    }

    public function character4CanClaimAnotherRoute(int $playerId): bool {
        if (count($this->getCharacter4UsingRouteIds($playerId)) === 0) {
            return false;
        }

        $trainCarsHand = $this->game->trainCarManager->getPlayerHand($playerId);
        $remainingTrainCars = $this->game->getRemainingTrainCarsCount($playerId);
        $possibleRoutes = $this->game->mapManager->claimableRoutes($playerId, $trainCarsHand, $remainingTrainCars);

        return count($this->filterCharacter4Routes($playerId, $possibleRoutes)) > 0;
    }

    public function onEndTurn(): void {
        if (!$this->isActive()) {
            return;
        }

        // character 3 can be played every turn, all others are single use
        $this->game->DbQuery("UPDATE `player` SET `player_legendary_character_state` = null WHERE `player_legendary_character` = 3");
    }
}
