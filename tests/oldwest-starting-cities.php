<?php

namespace Bga\GameFramework {
    class UserException extends \Exception {}
    class StateType { const ACTIVE_PLAYER = 1; const PRIVATE = 2; }
    class Bga {
        public object $notify;
        public function __construct() {
            $this->notify = new class {
                public array $notifications = [];
                public function all(string $name, string $message, array $args): void {
                    $this->notifications[] = [$name, $args];
                }
            };
        }
    }
}
namespace Bga\GameFramework\States {
    class GameState {
        public function __construct(object $game, mixed ...$options) {}
        protected function getRandomZombieChoice(array $choices): mixed { return $choices[0]; }
    }
}
namespace Bga\Games\TicketToRide {
    class Game {
        public \Bga\GameFramework\Bga $bga;
        public BuildingManager $buildingManager;
        public object $destinationManager;
        public object $notify;
        public array $buildings = [];
        public int $activeIndex = 0;
        public array $extraTime = [];
        public function __construct(private object $map, private array $players) {
            $this->bga = new \Bga\GameFramework\Bga();
            $this->notify = $this->bga->notify;
            $this->buildingManager = new BuildingManager($this);
            $this->destinationManager = new class {
                public function getPlayerHandCount(int $id): int { return 3; }
                public function getRemainingDestinationCardsInDeck(): int { return 20; }
            };
        }
        public function getMap(): object { return $this->map; }
        public function getPlayersIds(): array { return $this->players; }
        public function activePrevPlayer(): int {
            $this->activeIndex = ($this->activeIndex + count($this->players) - 1) % count($this->players);
            return $this->players[$this->activeIndex];
        }
        public function giveExtraTime(int $id): void { $this->extraTime[] = $id; }
        public function getPlayerNameById(int $id): string { return "Player $id"; }
        public function getCityName(int $id): string { return $this->map->cities[$id]->name; }
        public function getCollectionFromDB(string $sql): array {
            return array_filter($this->buildings, function($row) use ($sql) {
                foreach (['player_id', 'building_type'] as $field) {
                    if (preg_match('/`'.$field.'` = (\d+)/', $sql, $match) && $row[$field] !== (int) $match[1]) {
                        return false;
                    }
                }
                return true;
            });
        }
        public function DbQuery(string $sql): void {
            if (!preg_match('/VALUES \((\d+), (\d+), (\d+)\)/', $sql, $match)) {
                throw new \RuntimeException('Unexpected database mutation: '.$sql);
            }
            $this->buildings[] = ['city_id' => (int)$match[1], 'player_id' => (int)$match[2], 'building_type' => (int)$match[3]];
        }
    }
}
namespace {
    function clienttranslate(string $text): string { return $text; }
    require_once __DIR__.'/../modules/php/constants.inc.php';
    require_once __DIR__.'/../modules/php/framework-prototype/Helpers/Arrays.php';
    foreach (['Map', 'City', 'Route', 'RouteSpace', 'DestinationCard', 'PlacedBuilding'] as $class) {
        require_once __DIR__.'/../modules/php/Objects/'.$class.'.php';
    }
    require_once __DIR__.'/../modules/maps/oldwest/map.php';
    require_once __DIR__.'/../modules/php/BuildingManager.php';
    require_once __DIR__.'/../modules/php/States/PrivateChooseInitialDestinations.php';
    require_once __DIR__.'/../modules/php/States/ChooseStartingCity.php';

    use Bga\Games\TicketToRide\Game;
    use Bga\Games\TicketToRide\States\ChooseAction;
    use Bga\Games\TicketToRide\States\ChooseStartingCity;
    use Bga\Games\TicketToRide\States\PrivateChooseInitialDestinations;

    function check(bool $condition, string $message): void {
        if (!$condition) { throw new \RuntimeException($message); }
    }
    function rejects(callable $action): void {
        try { $action(); } catch (\Bga\GameFramework\UserException $e) { return; }
        throw new \RuntimeException('Invalid city placement was accepted');
    }

    foreach ([2, 3, 5, 6] as $count) {
        // Deliberately unsorted ids: selection must follow turn order, not id order.
        $players = array_slice([81, 17, 63, 24, 95, 32], 0, $count);
        $game = new Game(new \OldWestMap(), $players);
        foreach ($players as $player) {
            check($game->buildingManager->getRemainingCityMarkers($player) === 3, 'Players need 3 markers');
        }
        check((new PrivateChooseInitialDestinations($game))->endChooseInitialDestination() === ChooseStartingCity::class, 'City selection must follow ticket selection');
        $state = new ChooseStartingCity($game);
        $order = array_reverse($players);
        foreach ($order as $index => $player) {
            check($players[$game->activeIndex] === $player, 'Incorrect reverse selection order');
            rejects(fn() => $state->actChooseStartingCity(999, $player));
            if ($index > 0) { rejects(fn() => $state->actChooseStartingCity(1, $player)); }
            $city = $index + 1;
            check(in_array($city, $state->getArgs()['possibleCityIds'], true), 'Free city not selectable');
            $next = $index === $count - 1
                ? $state->zombie($player, $state->getArgs())
                : $state->actChooseStartingCity($city, $player);
            check($next === ($index === $count - 1 ? ChooseAction::class : ChooseStartingCity::class), 'Wrong next state');
            check($game->buildingManager->getRemainingCityMarkers($player) === 2, 'Placement must use one marker');
            check(!in_array($city, $state->getArgs()['possibleCityIds'], true), 'Controlled city remains available');
            rejects(fn() => $game->buildingManager->placeStartingCityMarker($player, 40));
            $notification = end($game->bga->notify->notifications);
            check($notification === ['cityMarkerPlaced', [
                'playerId' => $player, 'player_name' => "Player $player", 'cityId' => $city,
                'city_name' => $game->getCityName($city), 'remainingCityMarkers' => 2,
            ]], 'Placement notification must update every client');
        }
        check($game->activeIndex === 0, 'First player must take the first normal turn');
        $reloaded = new \Bga\Games\TicketToRide\BuildingManager($game);
        check(count($reloaded->getPlacedCityMarkers()) === $count, 'Markers must survive reload');
        check($reloaded->getPlacedStations() === [], 'City markers must not act as stations');
        foreach ($game->buildings as $row) { check($row['building_type'] === CITY_MARKER, 'Incorrect building type'); }
    }

    $baseGame = new Game(new \Bga\Games\TicketToRide\Objects\Map([], [], []), [81, 17]);
    check((new PrivateChooseInitialDestinations($baseGame))->endChooseInitialDestination() === ChooseAction::class, 'Other maps must skip city selection');
    check($baseGame->activeIndex === 0 && $baseGame->buildingManager->getRemainingCityMarkers(81) === null, 'Other maps must keep their setup');
    rejects(fn() => $baseGame->buildingManager->placeStartingCityMarker(81, 1));
    echo "Old West starting cities passed.\n";
}
