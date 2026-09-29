<?php

use Bga\Games\TicketToRide\Game;
use Bga\Games\TicketToRide\Objects\Map;

require_once(__DIR__.'/cities.php');
require_once(__DIR__.'/routes.php');
require_once(__DIR__.'/destinations.php');

class UkMap extends Map {
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
             * Additional points scored for route lengths not in the default table.
             */
            [
                10 => 40,
            ]
        );

        $this->trainCarsPerPlayer = 35;
        $this->numberOfLocomotiveCards = 20; // The UK deck has 6 more than the base deck.
        $this->resetVisibleCardsWithLocomotives = null;
        $this->unusedInitialDestinationsGoToDeckBottom = true;
        $this->unusedAdditionalDestinationsGoToDeckBottom = true;
        $this->pointsForLongestPath = null;
        $this->pointsForGlobetrotter = null;
        $this->minimumPlayerForDoubleRoutes = 3;
        $this->countriesEndPoints = [
            -1 => [1001, 1002], // France
        ];

        $this->rulesDifferences = [
            clienttranslate('You can only play 4 players maximum. Double routes are only available at 3 or 4 players.'),
            clienttranslate('Each player starts with 35 trains, 4 Train Car cards, and 1 Locomotive. The UK deck has 6 extra Locomotives; 3 face-up Locomotives are not replaced.'),
            clienttranslate('Game start: Deal 5 tickets and keep at least 3.'),
            clienttranslate('Any 4 Train Car cards can substitute for a Locomotive, or any 3 with Booster technology.'),
            clienttranslate('At the start, players may only claim 1- or 2-space routes in England. Technology cards unlock longer routes, ferries, and routes into Wales, Scotland, Ireland, and France.'),
            clienttranslate('Before a regular turn action, a player may buy one Technology card using Locomotives. The Southampton–New York route may be claimed without Technology.'),
            clienttranslate('There is no longest path bonus and no Globetrotter bonus.'),
        ];
    }

    function getInitialDestinationPick(int $expansionValue): array {
        return ['deck' => 5];
    }

    function getInitialDestinationMinimumKept(int $expansionValue): int {
        return 3;
    }

    function getPreloadImages(int $expansionValue): array {
        return ['destinations-1-0.jpg', 'train-cards.jpg'];
    }

    function getDestinationToGenerate(int $expansionValue): array {
        $destinations = [];
        foreach (getBaseDestinations() as $typeArg => $destination) {
            $destinations[] = ['type' => 1, 'type_arg' => $typeArg, 'nbr' => 1];
        }
        return ['deck' => $destinations];
    }

    function setup(Game $game): void {
        // Game setup has already dealt the four random Train Car cards.
        $deck = $game->trainCarManager->trainCars;
        foreach ($game->getPlayersIds() as $playerId) {
            $locomotives = $deck->getCardsOfTypeInLocation(0, null, 'deck');
            $locomotive = reset($locomotives);
            if ($locomotive === false) {
                throw new \RuntimeException('No Locomotive left for the UK starting hand');
            }
            $deck->moveCard($locomotive['id'], 'hand', $playerId);
        }
    }
}

function getMap() {
    return new UkMap();
}
