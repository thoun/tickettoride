<?php

use Bga\Games\TicketToRide\Game;
use Bga\Games\TicketToRide\Objects\Map;

require_once(__DIR__.'/cities.php');
require_once(__DIR__.'/routes.php');
require_once(__DIR__.'/destinations.php');

class JapanMap extends Map {
    public function __construct() {
        parent::__construct(
            getCities(),
            /**
             * Route on the map. 
             * For double routes, there is 2 instances of Route.
             * For cities (from/to), it's always low id to high id.
             */
            getRoutes(),
            /**
             * List of DestinationCard.
             */
            getAllDestinations(),
            /**
             * Points scored for claimed routes.
             */
            [
                1 => 1,
                2 => 2,
                3 => 4,
                4 => 7,
                5 => 10,
                6 => 15,
            ]
        );

        $this->trainCarsPerPlayer = 20; // trains car tokens per player at the beginning of the game
        $this->unusedInitialDestinationsGoToDeckBottom = true; // Indicates if unpicked destinations cards go back to the bottom of the deck.
        $this->unusedAdditionalDestinationsGoToDeckBottom = true; // Indicates if unpicked destinations cards go back to the bottom of the deck.
        $this->pointsForLongestPath = null; // points for maximum longest countinuous path (null means disabled)
        $this->pointsForGlobetrotter = null; // points for maximum completed destinations (null means disabled)
        $this->minimumPlayerForDoubleRoutes = 4; // 4 means 2-3 players cant use double routes
        $this->differentLengthRoutesAreDoubleRoutes = false;

        $this->bulletTrainBonusPoints = [
            2 => [1 => 10, 2 => -10],
            3 => [1 => 15, 2 => 5, 3 => -10],
            4 => [1 => 20, 2 => 10, 3 => 0, 4 => -10],
            5 => [1 => 25, 2 => 15, 3 => 5, 4 => -5, 5 => -10],
        ];

        $this->multilingualPdfRulesUrl = 'https://cdn.svc.asmodee.net/production-daysofwonder/uploads/2024/07/720132-T2RMC7-Rules_Japan_en.pdf';
        $this->rulesDifferences = [
            clienttranslate('All players start with 20 trains instead of 45 trains, and the game has 16 white Bullet Train miniatures.'),
            clienttranslate('When claiming a Bullet Train route, place 1 Bullet Train miniature on it. It will not give you points, by make you progress on the Progression Marker of the Bullet Train Track that will give points at the end of the game.'),
            clienttranslate('To complete Destination Tickets players can use any mix of routes with wagons in their color or claimed Bullet Train Routes whether or not they claimed them themselves.'),
            clienttranslate('If there is no Bullet Train miniature remaining, the Bullet Train routes become standard gray routes.'),
            clienttranslate('The Aomori-Hakodate routes are of different length and are not considered a double route.'),
            clienttranslate('Final turn is triggered when a player has 2 train cars or less AND there are 2 Bullet Trains miniatures or less.'),
            clienttranslate('There is no longest path bonus and no Globetrotter bonus.'),
        ];
    }
    
    /**
     * Return the number of destinations cards shown at the beginning, for each deck.
     */
    function getInitialDestinationPick(int $expansionValue): array {
        return ['deck' => 4];
    }

    function getPreloadImages(int $expansionValue): array {
        return ['destinations-1-0.jpg'];
    }
    
    /**
     * List the destination tickets that will be used for the game.
     */
    function getDestinationToGenerate(int $expansionValue): array {
        $destinations = [];

        $base = getBaseDestinations();
        foreach($base as $typeArg => $destination) {
            $destinations[] = [ 'type' => 1, 'type_arg' => $typeArg, 'nbr' => 1];
        }

        return [
            'deck' => $destinations
        ];
    }
    
    /**
     * Return if Longest Path bonus card is used for the game.
     */
    function isLongestPathBonusActive(int $expansionValue): bool {
        return false;
    }

    function setup(Game $game): void {
        $game->bga->globals->set(REMAINING_BULLET_TRAINS, 16);

        foreach ($game->getPlayersIds() as $playerId) {
            $game->bga->globals->set("BULLET_TRAIN_POSITION_{$playerId}", 0);
        }
    }

    function getMapSpecificData(Game $game): array {
        return [
            'remainingBulletTrains' => $game->bga->globals->get(REMAINING_BULLET_TRAINS, 0),
        ];
    }

    function getPlayerMapSpecificData(Game $game, int $playerId): array {
        return [
            'bulletTrainPosition' => $game->bga->globals->get("BULLET_TRAIN_POSITION_{$playerId}", 0),
        ];
    }

    function isLastTurn(Game $game): bool {
        return $game->getLowestTrainCarsCount() <= 2 && $game->bga->globals->get(REMAINING_BULLET_TRAINS) <= 2; // 2 means 0, 1, or 2 will start last turn
    }

    /**
     * Return each player's Bullet Train bonus, based on their position on the
     * progression track. Tied players share a rank and still occupy all their
     * positions (for example: 1st, tied 2nd, tied 2nd, 4th).
     *
     * @param array<int, int> $positions position by player id
     * @return array<int, int> bonus points by player id
     */
    function getBulletTrainBonuses(array $positions): array {
        if ($this->bulletTrainBonusPoints === null) {
            return [];
        }

        $pointsByRank = $this->bulletTrainBonusPoints[count($positions)];
        $bonuses = [];
        foreach ($positions as $playerId => $position) {
            if ($position === 0) {
                $bonuses[$playerId] = -20;
                continue;
            }

            $rank = 1 + count(array_filter($positions, fn($otherPosition) => $otherPosition > $position));
            $bonuses[$playerId] = $pointsByRank[$rank];
        }

        return $bonuses;
    }
}

function getMap() {
    return new JapanMap();
}

?>
