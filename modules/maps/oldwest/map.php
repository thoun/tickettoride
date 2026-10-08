<?php

use Bga\Games\TicketToRide\Objects\Map;
use Bga\Games\TicketToRide\Objects\Route;
use Bga\Games\TicketToRide\Game;
use Bga\Games\TicketToRide\States\MoveAlvin;

require_once(__DIR__.'/cities.php');
require_once(__DIR__.'/routes.php');
require_once(__DIR__.'/destinations.php');

/**
 * Rules reference: 06b-OldWest-EN-2019-1.pdf, page 2.
 *
 * TODO: Support 6 players in the game configuration (Old West allows 2-6). => or only allow 5 in BGA ? we only have 5 colors of wagons sprites.
 * *
 * TODO: Verify the destination data against the physical tickets: the rules
 * list 50 tickets, but destinations.php currently defines 51. => ask the publisher
 */
class OldWestMap extends Map {
    private const ROSWELL = 30;

    public function __construct() {
        parent::__construct(
            getCities(),
            getRoutes(),
            getAllDestinations(),
        );

        $this->trainCarsPerPlayer = 40;
        $this->cityMarkers = 3;
        $this->unusedInitialDestinationsGoToDeckBottom = true;
        $this->unusedAdditionalDestinationsGoToDeckBottom = true;
        $this->additionalDestinationMinimumKept = 1;
        $this->pointsForLongestPath = null;
        $this->pointsForGlobetrotter = 15;
        $this->maximumPlayerForDoubleRoutes = [2 => 1, 3 => 1, 4 => 2, 5 => 2, 6 => 2];
        $this->maximumPlayerForTripleRoutes = [2 => 1, 3 => 1, 4 => 3, 5 => 3, 6 => 3];

        $this->rulesDifferences = [
            /*TODOclienttranslate('Old West is designed for 2 to 6 players. Each player starts with 40 trains.'), => confirm 6th color with publisher */
            clienttranslate('Deal 5 Destination Tickets at the start and keep at least 3. Shuffle all rejected initial tickets together and place them under the deck. Later, draw 4 tickets and keep at least 1; return unchosen tickets to the bottom of the deck.'),
            clienttranslate('Double and triple routes are fully available with 4 or more players. With 2 or 3 players, only one track can be claimed. A player cannot claim more than one track between the same cities.'),
            clienttranslate('After choosing tickets, players choose a starting city in reverse turn order by placing one of their 3 City Markers. Every claimed route must extend their network from that city.'),
            clienttranslate('After claiming a route, you may place a City Marker in either uncontrolled endpoint by paying 2 cards of the same color. Locomotives may replace either or both cards. City Markers cannot be moved.'),
            clienttranslate('Controlled cities determine who scores a claimed route. If both endpoints are controlled, both controllers score the route points; a player controlling both scores twice.'),
            clienttranslate('Ferries require a Locomotive card for each Locomotive symbol on the route, plus matching cards for the remaining spaces.'),
            clienttranslate('There is no longest path bonus. All players tied for the most completed tickets receive the 15-point Globetrotter bonus.'),
        ];
    }

    function isAlvinVariantActive(Game $game): bool {
        return $game->bga->tableOptions->get(152) === 1;
    }

    function setup(Game $game): void {
        if ($this->isAlvinVariantActive($game)) {
            $game->bga->globals->set('ALVIN', ['cityId' => self::ROSWELL, 'playerId' => null]);
        }
    }

    function getMapSpecificData(Game $game): array {
        return ['alvin' => $this->isAlvinVariantActive($game)
            ? $game->bga->globals->get('ALVIN', ['cityId' => self::ROSWELL, 'playerId' => null])
            : null];
    }

    function getForbiddenCityMarkerCityIds(Game $game): array {
        return $this->isAlvinVariantActive($game) ? [self::ROSWELL] : [];
    }

    function onClaimRoute(Game $game, int $playerId, Route $route): void {
        $alvin = $this->getMapSpecificData($game)['alvin'];
        if ($alvin === null || $alvin['playerId'] === $playerId
            || ($route->from !== $alvin['cityId'] && $route->to !== $alvin['cityId'])) {
            return;
        }
        $alvin['playerId'] = $playerId;
        $game->bga->globals->set('ALVIN', $alvin);
        $game->bga->globals->set('ALVIN_MOVE_PENDING', $playerId);
        $game->incScore($playerId, 10);
        $game->bga->notify->all('alvinUpdated', clienttranslate('${player_name} captures Alvin in ${city_name} and gains 10 points'), [
            'playerId' => $playerId,
            'player_name' => $game->getPlayerNameById($playerId),
            'city_name' => $game->getCityName($alvin['cityId']),
            'alvin' => $alvin,
        ]);
    }

    function getStateAfterRouteClaim(Game $game, string $nextState): string {
        if ($this->isAlvinVariantActive($game) && $game->bga->globals->get('ALVIN_MOVE_PENDING', null) !== null) {
            $game->bga->globals->set('ALVIN_MOVE_NEXT_STATE', $nextState);
            return MoveAlvin::class;
        }
        return $nextState;
    }

    function getEndGameBonuses(Game $game): array {
        $alvin = $this->getMapSpecificData($game)['alvin'];
        return $alvin === null || $alvin['playerId'] === null ? [] : [[
            'playerId' => $alvin['playerId'],
            'points' => 10,
            'message' => clienttranslate('${player_name} gains ${delta} points for controlling Alvin at game end'),
            'args' => [],
        ]];
    }

    function getInitialDestinationPick(int $expansionValue): array {
        return ['deck' => 5];
    }

    function getInitialDestinationMinimumKept(int $expansionValue): int {
        return 3;
    }

    function getAdditionalDestinationCardNumber(int $expansionValue): int {
        return 4;
    }

    function getDestinationToGenerate(int $expansionValue): array {
        $destinations = [];
        foreach (getBaseDestinations() as $typeArg => $destination) {
            $destinations[] = ['type' => 1, 'type_arg' => $typeArg, 'nbr' => 1];
        }

        return ['deck' => $destinations];
    }
}

function getMap() {
    return new OldWestMap();
}
