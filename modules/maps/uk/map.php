<?php

use Bga\Games\TicketToRide\Game;
use Bga\Games\TicketToRide\Objects\Map;
use Bga\Games\TicketToRide\Objects\Route;

require_once(__DIR__.'/cities.php');
require_once(__DIR__.'/routes.php');
require_once(__DIR__.'/destinations.php');

class UkMap extends Map {
    private const TECHNOLOGY_CARD_COUNTS = [4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 1, 1, 2, 1, 1, 1];

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
        $this->useTechnologyCards = true;
        $this->technologyCardCosts = [1, 1, 1, 1, 2, 2, 2, 2, 2, 4, 4, 1, 2, 2, 2, 3];

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

    public function canClaimRouteWithTechnology(Route $route, array $technologyCards): bool {
        // The transatlantic route is always available, regardless of its length and Locomotive spaces.
        if ($route->from === 41 && $route->to === 2001) {
            return true;
        }

        $countryTechnologies = [
            UK_COUNTRY_WALES => 0,
            UK_COUNTRY_SCOTLAND => 2,
            UK_COUNTRY_IRELAND => 1,
            UK_COUNTRY_FRANCE => 1,
        ];
        foreach ([$route->from, $route->to] as $cityId) {
            $country = $this->cities[$cityId]->country;
            $technology = $countryTechnologies[$country] ?? (in_array($cityId, [1001, 1002], true) ? 1 : null);
            if ($technology !== null && !in_array($technology, $technologyCards, true)) {
                return false;
            }
        }

        if ($route->number === 3 && !in_array(3, $technologyCards, true)) {
            return false;
        }
        if ($route->number >= 4 && !in_array(4, $technologyCards, true)) {
            return false;
        }
        if ($route->locomotives > 0 && !in_array(5, $technologyCards, true)) {
            return false;
        }

        return true;
    }

    public function getLocomotiveSubstitutionSize(array $technologyCards): ?int {
        return in_array(6, $technologyCards, true) ? 3 : 4;
    }

    public function getAdditionalRoutePoints(Route $route, array $technologyCards): int {
        return (in_array(7, $technologyCards, true) ? 1 : 0)
            + ($route->locomotives > 0 && in_array(8, $technologyCards, true) ? 2 : 0);
    }

    public function getCompletedTicketBonus(int $completedTickets, array $technologyCards): int {
        return in_array(9, $technologyCards, true) ? 2 * $completedTickets : 0;
    }

    public function getMaximumHiddenTrainCardsPerAction(array $technologyCards): int {
        return in_array(12, $technologyCards, true) ? 3 : 2;
    }

    public function getEndGameTechnologyBonuses(array $technologyCards, int $completedTickets, int $mostCompletedTickets, int $longestPath, int $longestPathInGame): array {
        $bonuses = [];
        if (in_array(13, $technologyCards, true)) {
            $bonuses[13] = $completedTickets === $mostCompletedTickets ? 20 : -20;
        }
        if (in_array(14, $technologyCards, true)) {
            $bonuses[14] = $longestPath === $longestPathInGame ? 15 : -15;
        }
        return $bonuses;
    }

    function getInitialDestinationPick(int $expansionValue): array {
        return ['deck' => 5];
    }

    function getInitialDestinationMinimumKept(int $expansionValue): int {
        return 3;
    }

    function getPreloadImages(int $expansionValue): array {
        return ['destinations-1-0.jpg', 'train-cards.jpg', 'technology-cards.webp'];
    }

    function getDestinationToGenerate(int $expansionValue): array {
        $destinations = [];
        foreach (getBaseDestinations() as $typeArg => $destination) {
            $destinations[] = ['type' => 1, 'type_arg' => $typeArg, 'nbr' => 1];
        }
        return ['deck' => $destinations];
    }

    function setup(Game $game): void {
        $advancedTechnologies = $game->bga->tableOptions->get(151) === 1;
        $technologyCardCounts = $advancedTechnologies
            ? self::TECHNOLOGY_CARD_COUNTS
            : array_slice(self::TECHNOLOGY_CARD_COUNTS, 0, 11);
        $game->bga->globals->set('REMAINING_TECHNOLOGY_CARDS', $technologyCardCounts);
        $game->bga->globals->set('TECHNOLOGY_CARD_BOUGHT_THIS_TURN', false);
        $game->bga->globals->set('THERMOCOMPRESSOR_REMAINING', 0);
        $game->bga->globals->set('TRAIN_CAR_DECK_RESHUFFLED', false);
        foreach ($game->getPlayersIds() as $playerId) {
            $game->bga->globals->set("TECHNOLOGY_CARDS_{$playerId}", []);
        }

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

    function getMapSpecificData(Game $game): array {
        return [
            'remainingTechnologyCards' => $game->bga->globals->get('REMAINING_TECHNOLOGY_CARDS', self::TECHNOLOGY_CARD_COUNTS),
        ];
    }

    function getPlayerMapSpecificData(Game $game, int $playerId): array {
        return [
            'technologyCards' => $game->bga->globals->get("TECHNOLOGY_CARDS_{$playerId}", []),
        ];
    }
}

function getMap() {
    return new UkMap();
}
