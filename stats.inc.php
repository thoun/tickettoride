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
 * stats.inc.php
 *
 * TicketToRide game statistics description
 *
 */

/*
    In this file, you are describing game statistics, that will be displayed at the end of the
    game.
    
    !! After modifying this file, you must use "Reload  statistics configuration" in BGA Studio backoffice
    ("Control Panel" / "Manage Game" / "Your Game")
    
    There are 2 types of statistics:
    _ table statistics, that are not associated to a specific player (ie: 1 value for each game).
    _ player statistics, that are associated to each players (ie: 1 value for each player in the game).

    Statistics types can be "int" for integer, "float" for floating point values, and "bool" for boolean
    
    Once you defined your statistics there, you can start using "initStat", "setStat" and "incStat" method
    in your game logic, using statistics names defined below.
    
    !! It is not a good idea to modify this file when a game is running !!

    If your game is already public on BGA, please read the following before any change:
    http://en.doc.boardgamearena.com/Post-release_phase#Changes_that_breaks_the_games_in_progress
    
    Notes:
    * Statistic index is the reference used in setStat/incStat/initStat PHP method
    * Statistic index must contains alphanumerical characters and no space. Example: 'turn_played'
    * Statistics IDs must be >=10
    * Two table statistics can't share the same ID, two player statistics can't share the same ID
    * A table statistic can have the same ID than a player statistics
    * Statistics ID is the reference used by BGA website. If you change the ID, you lost all historical statistic data. Do NOT re-use an ID of a deleted statistic
    * Statistic name is the English description of the statistic as shown to players
    
*/

$commonStats = [
    // 10+ : other
    "turnsNumber" => [
        "id" => 10,
        "name" => totranslate("Number of turns"),
        "type" => "int"
    ], 

    // 20+ : train car cards
    "collectedTrainCarCards" => [
        "id" => 20,
        "name" => totranslate("Collected train car cards"),
        "type" => "int"
    ],
    "collectedHiddenTrainCarCards" => [
        "id" => 21,
        "name" => totranslate("Collected train car cards (hidden)"),
        "type" => "int"
    ],
    "collectedVisibleTrainCarCards" => [
        "id" => 22,
        "name" => totranslate("Collected train car cards (visible)"),
        "type" => "int"
    ],
    "collectedVisibleLocomotives" => [
        "id" => 23,
        "name" => totranslate("Collected visible locomotives"),
        "type" => "int"
    ],

    // 30+ : destination cards
    "drawDestinationsAction" => [
        "id" => 30,
        "name" => totranslate("Draw destination action"),
        "type" => "int"
    ],
    "completedDestinations" => [
        "id" => 31,
        "name" => totranslate("Completed destinations"),
        "type" => "int"
    ],
    "uncompletedDestinations" => [
        "id" => 32,
        "name" => totranslate("Uncompleted destinations"),
        "type" => "int"
    ],

    // 40+ : train cars (meeples)
    "claimedRoutes" => [
        "id" => 40,
        "name" => totranslate("Claimed routes"),
        "type" => "int"
    ],
    "playedTrainCars" => [
        "id" => 41,
        "name" => totranslate("Played train cars"),
        "type" => "int"
    ],
    "averageClaimedRouteLength" => [ // playedTrainCars / claimedRoutes
        "id" => 42,
        "name" => totranslate("Average claimed route length"),
        "type" => "float"
    ],
];

$stats_type = [

    // Statistics global to table
    "table" => $commonStats,
    
    // Statistics existing for each player
    "player" => $commonStats + [   
        // 30+ : destination cards     
        "keptInitialDestinationCards" => [
            "id" => 33,
            "name" => totranslate("Kept initial destination cards"),
            "type" => "int"
        ],
        "keptAdditionalDestinationCards" => [
            "id" => 34,
            "name" => totranslate("Kept additional destination cards"),
            "type" => "int"
        ],

        // 40+ : train cars (meeples)
        "longestPath" => [
            "id" => 43,
            "name" => totranslate("Longest continuous path"),
            "type" => "int"
        ],
    ],

];