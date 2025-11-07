<?php

use Bga\Games\TicketToRideMaps\Objects\Map;

require_once(__DIR__.'/cities.php');
require_once(__DIR__.'/routes.php');
require_once(__DIR__.'/destinations.php');

/**
 * Rules reference: 06b-OldWest-EN-2019-1.pdf, page 2.
 *
 * TODO: Support 6 players in the game configuration (Old West allows 2-6). => or only allow 5 in BGA ? we only have 5 colors of wagons sprites.
 * *
 * TODO: Give each player 3 City Markers. After all players choose their initial
 * tickets, choose starting cities in reverse turn order, beginning with the
 * last player. Each player places one marker for free in an uncontrolled city.
 *
 * TODO: Restrict route claims to the player's network: the first route must
 * touch their starting city, and every later route must touch that city or a
 * city already connected to it by their own claimed routes.
 *
 * TODO: After claiming a route, optionally place one remaining City Marker at
 * either endpoint by paying 2 cards of the same color. Locomotives may replace
 * one or both cards. A city can have only one marker; markers cannot be moved,
 * and a player can control at most 3 cities, including their starting city.
 *
 * TODO: Award route points according to city control. If neither endpoint is
 * controlled, the claimant scores normally. If only one endpoint is controlled,
 * its controller scores instead (including when that controller is the claimant).
 * If both endpoints are controlled, each endpoint's controller scores the full
 * route value; a player controlling both receives it twice. The claimant gets
 * no separate award when another player controls an endpoint.
 *
 * TODO: Add the optional Alvin the Alien variant. Alvin starts in Roswell
 * (city 30), where no player may start or place a City Marker. The first player
 * claiming a route into Roswell captures Alvin, scores 10 points, and moves him
 * to any city they control, including their starting city. Whenever another
 * player claims a route into Alvin's current city, that player captures him,
 * scores 10 points, and moves him to a city they control. Alvin's controller
 * at game end receives an additional 10 points. The normal Alvin & Dexter
 * expansion is incompatible with Old West.
 *
 * TODO: Verify the destination data against the physical tickets: the rules
 * list 50 tickets, but destinations.php currently defines 51. => ask the publisher
 */
class OldWestMap extends Map {
    public function __construct() {
        parent::__construct(
            getCities(),
            getRoutes(),
            getAllDestinations(),
        );

        $this->trainCarsPerPlayer = 40;
        $this->unusedInitialDestinationsGoToDeckBottom = true;
        $this->unusedAdditionalDestinationsGoToDeckBottom = true;
        $this->additionalDestinationMinimumKept = 1;
        $this->pointsForLongestPath = null;
        $this->pointsForGlobetrotter = 15;
        $this->maximumPlayerForDoubleRoutes = [2 => 1, 3 => 1, 4 => 2, 5 => 2, 6 => 2];
        $this->maximumPlayerForTripleRoutes = [2 => 1, 3 => 1, 4 => 3, 5 => 3, 6 => 3];

        $this->rulesDifferences = [
            /*TODOclienttranslate('Old West is designed for 2 to 6 players. Each player starts with 40 trains.'),
            clienttranslate('Deal 5 Destination Tickets at the start and keep at least 3. Shuffle all rejected initial tickets together and place them under the deck. Later, draw 4 tickets and keep at least 1; return unchosen tickets to the bottom of the deck.'),
            clienttranslate('Double and triple routes are fully available with 4 or more players. With 2 or 3 players, only one track can be claimed. A player cannot claim more than one track between the same cities.'),
            clienttranslate('After choosing tickets, players choose a starting city in reverse turn order by placing one of their 3 City Markers. Every claimed route must extend their network from that city.'),
            clienttranslate('After claiming a route, you may place a City Marker in either uncontrolled endpoint by paying 2 cards of the same color. Locomotives may replace either or both cards. City Markers cannot be moved.'),
            clienttranslate('Controlled cities determine who scores a claimed route. If both endpoints are controlled, both controllers score the route points; a player controlling both scores twice.'),
            clienttranslate('Ferries require a Locomotive card for each Locomotive symbol on the route, plus matching cards for the remaining spaces.'),
            clienttranslate('There is no longest path bonus. All players tied for the most completed tickets receive the 15-point Globetrotter bonus.'),*/
        ];
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
