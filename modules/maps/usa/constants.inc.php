<?php

const EXPANSION1910 = 'EXPANSION1910';        

const INIT_GAME_STATE_LABELS = [
    EXPANSION1910 => 101, // 0 => base game, 1 => 1910, 2 => mega game, 3 => big cities
];

const INITIAL_TRAIN_CAR_CARDS_IN_HAND = 4; // Number of train car cards in hand, for each player, at the beginning of the game.
const VISIBLE_LOCOMOTIVES_COUNTS_AS_TWO_CARDS = true; // Says if it is possible to take only one visible locomotive. // TODO MAPS
const CAN_ONLY_USE_LOCOMOTIVES_IN_TUNNELS = false; // Says locomotives are reserved to tunnels. // TODO MAPS
const RESET_VISIBLE_CARDS_WITH_LOCOMOTIVES = 3; // Resets visible cards when 3 locomotives are visible (null means disabled)
const TRAIN_CARS_NUMBER_TO_START_LAST_TURN = 2; // 2 means 0, 1, or 2 will start last turn
const TRAIN_CARS_PER_PLAYER = 45;
const ADDITIONAL_DESTINATION_MINIMUM_KEPT = 1; // Minimum number of destinations cards to keep at pick destination action.
const UNUSED_INITIAL_DESTINATIONS_GO_TO_DECK_BOTTOM = true; // Indicates if unpicked destinations cards go back to the bottom of the deck.
const UNUSED_ADDITIONAL_DESTINATIONS_GO_TO_DECK_BOTTOM = true; // Indicates if unpicked destinations cards go back to the bottom of the deck.
const POINTS_FOR_LONGEST_PATH = 10; // points for maximum longest countinuous path (null means disabled)
const POINTS_FOR_GLOBETROTTER = 15; // points for maximum completed destinations (null means disabled)
const MINIMUM_PLAYER_FOR_DOUBLE_ROUTES = 4; // 4 means 2-3 players cant use double routes
const NUMBER_OF_LOCOMOTIVE_CARDS = 14;
const NUMBER_OF_COLORED_CARDS = 12;

const BIG_CITIES = [
    5, // Chicago',
    6, // Dallas',
    11, // Houston',
    15, // Los Angeles',
    16, // Miami',
    20, // New York',
    32, // Seattle',
];

function getMapOptions() {
    return [    
        101 => [ // 0 => base game, 1 => 1910, 2 => mega game, 3 => big cities
            'name' => totranslate('1910 Expansion'),
            'values' => [
                0 => [
                    'name' => totranslate('Disabled'),
                ],
                1 => [
                    'name' => totranslate('1910'),
                    'description' => totranslate('New tickets. Globetrotter - Most Completed Tickets bonus card instead of Longest Route Bonus card'),
                    'tmdisplay' => totranslate('1910 Expansion : 1910'),
                    'nobeginner' => true,
                ],
                2 => [
                    'name' => totranslate('The Mega Game'),
                    'description' => totranslate('All tickets, both bonus cards, more tickets when choosing'),
                    'tmdisplay' => totranslate('1910 Expansion : The Mega Game'),
                    'nobeginner' => true,
                ],
                3 => [
                    'name' => totranslate('The Big Cities'),
                    'description' => totranslate('Tickets with Big Cities'),
                    'tmdisplay' => totranslate('1910 Expansion : The Big Cities'),
                    'nobeginner' => true,
                ],
            ],
            'default' => 0,
        ],
    ];
} 