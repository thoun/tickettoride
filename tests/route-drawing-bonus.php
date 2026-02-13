<?php

namespace Bga\GameFramework {
    class UserException extends \RuntimeException {}
}

namespace Bga\GameFramework\Components {
    class Deck {
        public bool $autoreshuffle = false;
        public array $autoreshuffle_trigger = [];
        public array $locations;
        public function __construct(array $deck, array $discard) {
            $this->locations = ['deck' => $deck, 'discard' => $discard, 'table' => [99], 'hand' => []];
        }
        public function countCardInLocation(string $location): int { return count($this->locations[$location]); }
        public function pickCards(int $number, string $location, int $playerId): array {
            $cards = [];
            for ($i = 0; $i < $number; $i++) {
                if (!$this->locations[$location] && $this->autoreshuffle) {
                    $this->locations[$location] = $this->locations['discard'];
                    $this->locations['discard'] = [];
                    $trigger = $this->autoreshuffle_trigger;
                    $trigger['obj']->{$trigger['method']}();
                }
                $id = array_shift($this->locations[$location]);
                if ($id === null) { throw new \RuntimeException('Deck overdraw'); }
                $this->locations['hand'][] = $id;
                $cards[] = ['id' => $id, 'type' => $id % 9, 'location' => 'hand', 'location_arg' => $playerId];
            }
            return $cards;
        }
    }
}

namespace Bga\Games\TicketToRideEurope\ {
    class Game {
        public object $deckFactory;
        public object $notify;
        public function __construct(\Bga\GameFramework\Components\Deck $deck) {
            $this->deckFactory = new class($deck) {
                public function __construct(private object $deck) {}
                public function createDeck(string $table): object { return $this->deck; }
            };
            $this->notify = new class {
                public array $events = [];
                public function all(string $type, string $message, array $args): void {
                    $this->events[] = ['recipient' => 'all', 'type' => $type, 'args' => $args];
                }
                public function player(int $playerId, string $type, string $message, array $args): void {
                    $this->events[] = ['recipient' => $playerId, 'type' => $type, 'args' => $args];
                }
            };
        }
        public function getMap(): object { return (object)['useTechnologyCards' => false]; }
        public function getPlayerNameById(int $playerId): string { return 'Player '.$playerId; }
    }
}

namespace {
    function clienttranslate(string $text): string { return $text; }
    class BgaUserException extends \RuntimeException {}
    require_once __DIR__.'/../modules/php/Objects/TrainCar.php';
    require_once __DIR__.'/../modules/php/TrainCarManager.php';

    function check(bool $condition, string $message): void {
        if (!$condition) { throw new \RuntimeException($message); }
    }
    foreach ([
        [[1, 2, 3, 4], [], 1, [1]],
        [[1, 2, 3, 4], [], 2, [1, 2]],
        [[1, 2, 3, 4], [], 3, [1, 2, 3]],
        [[1], [2, 3], 3, [1, 2, 3]],
        [[1], [2], 3, [1, 2]],
        [[], [], 3, []],
    ] as [$deckCards, $discardCards, $bonus, $expected]) {
        $deck = new \Bga\GameFramework\Components\Deck($deckCards, $discardCards);
        $game = new \Bga\Games\TicketToRideEurope\Game($deck);
        $manager = new \Bga\Games\TicketToRideEurope\TrainCarManager($game);
        check($manager->drawBonusTrainCarCardsFromDeck(7, $bonus) === count($expected), 'Wrong bonus count');
        check($deck->locations['hand'] === $expected, 'Bonus must draw from the top of the deck');
        check($deck->locations['table'] === [99], 'Bonus must leave visible cards alone');
        $events = array_values(array_filter($game->notify->events, fn($event) => $event['type'] === 'trainCarPicked'));
        check(count($events) === ($expected ? 2 : 0), 'Wrong draw notification count');
        if ($expected) {
            [$public, $private] = $events;
            check($public['recipient'] === 'all' && $private['recipient'] === 7, 'Wrong notification recipients');
            check(!isset($public['args']['cards']) && !isset($public['args']['colors']), 'Public notification reveals cards');
            check(array_map(fn($card) => $card->id, $private['args']['cards']) === $expected, 'Private cards mismatch');
            foreach ($events as $event) {
                check($event['args']['origin'] === 0 && $event['args']['number'] === count($expected)
                    && $event['args']['count'] === count($expected), 'Wrong notification draw data');
            }
        }
    }

    $game = new \Bga\Games\TicketToRideEurope\Game(new \Bga\GameFramework\Components\Deck([1, 2, 3], []));
    $manager = new \Bga\Games\TicketToRideEurope\TrainCarManager($game);
    foreach ([[3, false], [2, true]] as [$number, $second]) {
        try {
            $manager->drawTrainCarCardsFromDeck(7, $number, $second);
            throw new \RuntimeException('Normal draw limit was bypassed');
        } catch (\Bga\GameFramework\UserException $exception) {}
    }
    check($manager->drawTrainCarCardsFromDeck(7, 2) === 2, 'Normal two-card draw failed');
    check($manager->drawTrainCarCardsFromDeck(7, 2) === 1, 'Normal scarce-deck fallback failed');
    echo "Route drawing bonus tests passed.\n";
}
