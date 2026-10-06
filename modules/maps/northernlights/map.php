<?php

use Bga\Games\TicketToRide\Game;
use Bga\Games\TicketToRide\Objects\Map;
use Bga\GameFramework\NotificationMessage;

require_once(__DIR__.'/cities.php');
require_once(__DIR__.'/routes.php');
require_once(__DIR__.'/destinations.php');

class NorthernLightsMap extends Map {
    // Cities north of the Arctic Circle line printed on the board.
    private const ARCTIC_CITIES = [4, 10, 15, 16, 17, 28, 29, 35, 42, 48, 50];
    private const CAPITAL_CITIES = [9, 19, 31, 37];

    public function __construct() {
        parent::__construct(
            getCities(),
            /**
             * Routes on the map. Parallel routes have separate instances.
             * For cities (from/to), it is always low id to high id.
             */
            getRoutes(),
            getAllDestinations(),
        );

        $this->trainCarsPerPlayer = 40;
        $this->numberOfLocomotiveCards = 18;
        $this->numberOfColoredCards = 12;
        $this->initialTrainCarCardsInHand = 4;
        $this->visibleLocomotivesCountsAsTwoCards = true;
        $this->locomotiveUsageRestriction = 0;
        $this->resetVisibleCardsWithLocomotives = null;
        $this->unusedInitialDestinationsGoToDeckBottom = true;
        $this->unusedAdditionalDestinationsGoToDeckBottom = true;
        $this->pointsForLongestPath = null;
        $this->pointsForGlobetrotter = null;
        $this->maximumPlayerForTripleRoutes = [2 => 1, 3 => 2, 4 => 3, 5 => 3];
        $this->useBonusCards = true;

        $this->rulesDifferences = [
            clienttranslate('All players start with 40 trains instead of 45 trains.'),
            clienttranslate('The Train Car deck contains 18 Locomotives and 12 cards of each color. Face-up cards are not replaced when 3 or more Locomotives are visible.'),
            clienttranslate('Double routes are only fully available in 4 and 5-player games. For triple routes, only 1 route can be claimed at 2 players, 2 routes at 3 players, and all 3 routes at 4 or 5 players. A player cannot claim more than one route between the same cities.'),
            clienttranslate('After claiming a route with a +X drawing bonus, draw X Train Car cards from the top of the deck.'),
            clienttranslate('At the start, randomly select 4 of the 11 bonus cards. Only these cards score at the end of the game. All players tied for a bonus receive its points.'),
        ];
    }

    /**
     * Return the number of destination cards shown at the beginning.
     */
    function getInitialDestinationPick(int $expansionValue): array {
        return ['deck' => 4];
    }

    /**
     * List the destination tickets that will be used for the game.
     */
    function getDestinationToGenerate(int $expansionValue): array {
        $destinations = [];
        foreach (getBaseDestinations() as $typeArg => $destination) {
            $destinations[] = ['type' => 1, 'type_arg' => $typeArg, 'nbr' => 1];
        }

        return ['deck' => $destinations];
    }

    function getPreloadImages(int $expansionValue): array {
        return ['destinations-1-0.jpg', 'bonus-cards.webp'];
    }

    function setup(Game $game): void {
        // Sprite indices 0-10 correspond to bonus cards A-K.
        $bonusCards = range(0, 10);
        shuffle($bonusCards);
        $game->bga->globals->set('SELECTED_BONUS_CARDS', array_slice($bonusCards, 0, 4));
    }

    function getMapSpecificData(Game $game): array {
        return ['bonusCards' => $game->bga->globals->get('SELECTED_BONUS_CARDS', [])];
    }

    public function getBonusCardScores(Game $game, array $players): array {
        $names = [
            clienttranslate('Call of the wild'), clienttranslate('Capital investment'),
            clienttranslate('Cost efficiency'), clienttranslate('Small steps strategist'),
            clienttranslate('Nordic Express'), clienttranslate('Local network'),
            clienttranslate('International tycoon'), clienttranslate('Polar Express'),
            clienttranslate('Snowplow award'), clienttranslate('Ferry Master'),
            clienttranslate('The wild west'),
        ];
        $points = [5, 7, 7, 10, 10, 10, 12, 12, 12, 12, 7];
        $messages = [
            clienttranslate('${player_name} gains ${delta} points with ${bonus_name} bonus card by holding ${number} locomotive equivalents'),
            clienttranslate('${player_name} gains ${delta} points with ${bonus_name} bonus card by completing ${number} destinations involving a capital'),
            clienttranslate('${player_name} gains ${delta} points with ${bonus_name} bonus card with ${number} train cars remaining'),
            clienttranslate('${player_name} gains ${delta} points with ${bonus_name} bonus card by claiming ${number} single-space routes'),
            clienttranslate('${player_name} gains ${delta} points with ${bonus_name} bonus card with a continuous path of ${number} train cars'),
            clienttranslate('${player_name} gains ${delta} points with ${bonus_name} bonus card by completing ${number} destinations worth 5 points or fewer'),
            clienttranslate('${player_name} gains ${delta} points with ${bonus_name} bonus card by reaching ${number} countries'),
            clienttranslate('${player_name} gains ${delta} points with ${bonus_name} bonus card by completing ${number} destinations involving an Arctic city'),
            clienttranslate('${player_name} gains ${delta} points with ${bonus_name} bonus card by claiming ${number} routes connected to an Arctic city'),
            clienttranslate('${player_name} gains ${delta} points with ${bonus_name} bonus card by claiming ${number} ferry routes'),
            clienttranslate('${player_name} gains ${delta} points with ${bonus_name} bonus card by claiming ${number} routes involving Norway'),
        ];
        $values = [];
        foreach ($players as $playerId => $player) {
            $colors = array_count_values(array_map(fn($card) => $card->type, $player['hand']));
            $locomotives = $colors[0] ?? 0;
            unset($colors[0]);
            $locomotives += array_sum(array_map(fn($count) => intdiv($count, 2), $colors));

            $capitalTickets = $smallTickets = $arcticTickets = 0;
            foreach ($player['completedDestinations'] as $ticket) {
                $ends = array_merge([$ticket->from], (array)$ticket->to);
                $capitalTickets += (int)(bool)array_intersect($ends, self::CAPITAL_CITIES);
                $arcticTickets += (int)(bool)array_intersect($ends, self::ARCTIC_CITIES);
                $smallTickets += (int)($ticket->points <= 5);
            }

            $shortRoutes = $arcticRoutes = $ferries = $norwegianRoutes = 0;
            $countries = [];
            foreach ($player['routes'] as $route) {
                $ends = [$route->from, $route->to];
                $shortRoutes += (int)($route->number === 1);
                $arcticRoutes += (int)(bool)array_intersect($ends, self::ARCTIC_CITIES);
                $ferries += (int)($route->locomotives > 0);
                $routeCountries = array_map(fn($cityId) => $this->cities[$cityId]->country, $ends);
                $norwegianRoutes += (int)in_array(NORTHERNLIGHTS_COUNTRY_NORWAY, $routeCountries, true);
                foreach ($routeCountries as $country) {
                    $countries[$country] = true;
                }
            }
            $values[$playerId] = [
                $locomotives, $capitalTickets, $player['remainingTrains'], $shortRoutes,
                $player['longestPathLength'], $smallTickets, count($countries),
                $arcticTickets, $arcticRoutes, $ferries, $norwegianRoutes,
            ];
        }

        $results = [];
        foreach ($game->bga->globals->get('SELECTED_BONUS_CARDS', []) as $type) {
            $best = max(array_column($values, $type));
            foreach ($values as $playerId => $metrics) {
                if ($metrics[$type] !== $best) {
                    continue;
                }
                $results[] = [
                    'playerId' => $playerId,
                    'points' => $points[$type],
                    'message' => new NotificationMessage($messages[$type], [
                        'bonusCardType' => $type,
                        'bonus_name' => $names[$type],
                        'number' => $best,
                        'i18n' => ['bonus_name'],
                    ]),
                ];
            }
        }
        return $results;
    }
}

function getMap() {
    return new NorthernLightsMap();
}
