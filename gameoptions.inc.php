<?php

/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * TicketToRide implementation : © <Your name here> <Your email address here>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * gameoptions.inc.php
 *
 * TicketToRide game options description
 * 
 * In this file, you can define your game options (= game variants).
 *   
 * Note: If your game has no variant, you don't have to modify this file.
 *
 * Note²: All options defined in this file should have a corresponding "game state labels"
 *        with the same ID (see "initGameStateLabels" in tickettoride.game.php)
 *
 * !! It is not a good idea to modify this file when a game is running !!
 *
 */

$game_options = [    
    /*TODO1910 101 => [ // 0 => base game, 1 => 1910, 2 => mega game, 3 => big cities
        'name' => totranslate('1910 Expansion'),
        'values' => [
            0 => [
                'name' => totranslate('Disabled'),
            ],
            1 => [
                'name' => totranslate('1910'),
                'description' => totranslate('New tickets. Globetrotter - Most Completed Tickets bonus card instead of Longest Route Bonus card'),
                'tmdisplay' => totranslate('1910 Expansion : 1910'),
            ],
            2 => [
                'name' => totranslate('The Mega Game'),
                'description' => totranslate('All tickets, both bonus cards, more tickets when choosing'),
                'tmdisplay' => totranslate('1910 Expansion : The Mega Game'),
            ],
            3 => [
                'name' => totranslate('The Big Cities'),
                'description' => totranslate('Tickets with Big Cities'),
                'tmdisplay' => totranslate('1910 Expansion : The Big Cities'),
            ],
        ],
        'default' => 0,
    ],*/

    110 => [
        'name' => totranslate('Turn order'),
        'values' => [
            1 => [
                'name' => totranslate('Turn order determined after selecting tickets'),
                'description' => totranslate('Turn order is not known when selecting tickets'),
            ],
            2 => [
                'name' => totranslate('Turn order determined before selecting tickets'),
                'description' => totranslate('Turn order is visible when selecting tickets'),
                'tmdisplay' => totranslate('Turn order determined before selecting tickets'),
            ],
        ],
        'default' => 1,
    ],
];

$game_preferences = [
    206 => [
        'name' => totranslate('Ask for confirmation when taking new destinations'),
        'needReload' => false,
        'values' => [
            1 => [ 'name' => totranslate('Enabled')],
            2 => [ 'name' => totranslate('Disabled')],
        ],
        'default' => 1
    ],

    201 => [
        'name' => totranslate('Show buttons for hidden cards selection'),
        'needReload' => false,
        'values' => [
            1 => [ 'name' => totranslate('Enabled')],
            2 => [ 'name' => totranslate('Disabled')],
        ],
        'default' => 2
    ],

    209 => [
        'name' => totranslate('Ask wanted color for double routes'),
        'needReload' => false,
        'values' => [
            1 => [ 'name' => totranslate('Enabled')],
            2 => [ 'name' => totranslate('Disabled')],
        ],
        'default' => 1
    ],

    202 => [
        'name' => totranslate('Confirm route claim'),
        'needReload' => false,
        'values' => [
            1 => [ 'name' => totranslate('Enabled')],
            2 => [ 'name' => totranslate('Enabled for touch device only')],
            3 => [ 'name' => totranslate('Disabled')],
        ],
        'default' => 2
    ],

    207 => [
        'name' => totranslate('Countdown timer for Confirm button'),
        'needReload' => false,
        'values' => [
            1 => ['name' => totranslate('Enabled')],
            2 => ['name' => totranslate('Disabled')],
        ],
        'default' => 1
    ],

    203 => [
        'name' => totranslate('Train car outline'),
        'needReload' => false,
        'values' => [
            1 => [ 'name' => totranslate('Enabled')],
            2 => [ 'name' => totranslate('Automatic')],
            3 => [ 'name' => totranslate('Disabled')],
        ],
        'default' => 2
    ],

    204 => [
        'name' => totranslate('Show color-blind indications'),
        'needReload' => false,
        'values' => [
            1 => [ 'name' => totranslate('Enabled')],
            2 => [ 'name' => totranslate('Disabled')],
        ],
        'default' => 2
    ],
    
    205 => [
        'name' => totranslate('Deck placement'),
        'needReload' => false,
        'values' => [
            1 => [ 'name' => totranslate('Train cards top, Destination tickets bottom')],
            2 => [ 'name' => totranslate('Destination tickets top, Train cards bottom')],
        ],
        'default' => 1
    ],

    // 206 and 207 already used
    
    208 => [
        'name' => totranslate('End of game animations'),
        'needReload' => true,
        'values' => [
            1 => [ 'name' => totranslate('Enabled')],
            2 => [ 'name' => totranslate('Disabled')],
        ],
        'default' => 1
    ],

    // 209 already used
];
