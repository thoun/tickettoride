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
 * material.inc.php
 *
 * TicketToRide game material description
 *
 * Here, you can describe the material of your game with PHP variables.
 *   
 * This file is loaded in your game logic class constructor, ie these variables
 * are available everywhere in your game logic code.
 *
 */

require_once(__DIR__.'/modules/php/objects/route.php');
require_once(__DIR__.'/modules/php/objects/destination.php');

/**
 * Cities in the map (by alphabetical order).
 */
$this->CITIES = [
  1 => 'Atlanta',
  2 => 'Boston',
  3 => 'Calgary',
  4 => 'Charleston',
  5 => 'Chicago',
  6 => 'Dallas',
  7 => 'Denver',
  8 => 'Duluth',
  9 => 'El Paso',
  10 => 'Helena',
  11 => 'Houston',
  12 => 'Kansas City',
  13 => 'Las Vegas',
  14 => 'Little Rock',
  15 => 'Los Angeles',
  16 => 'Miami',
  17 => 'Montréal',
  18 => 'Nashville',
  19 => 'New Orleans',
  20 => 'New York',
  21 => 'Oklahoma City',
  22 => 'Omaha',
  23 => 'Phoenix',
  24 => 'Pittsburgh',
  25 => 'Portland',
  26 => 'Raleigh',
  27 => 'Saint Louis',
  28 => 'Salt Lake City',
  29 => 'Sault St. Marie',
  30 => 'San Francisco',
  31 => 'Santa Fe',
  32 => 'Seattle',
  33 => 'Toronto',
  34 => 'Vancouver',
  35 => 'Washington',
  36 => 'Winnipeg',
];

/**
 * Route on the map. 
 * For double routes, there is 2 instances of Route.
 * For cities (from/to), it's always low id to high id.
 */
$this->ROUTES = [
  1 => new Route(1, 4, 2, GRAY),
  2 => new Route(1, 16, 5, BLUE),
  3 => new Route(1, 18, 1, GRAY),
  4 => new Route(1, 19, 4, YELLOW),
  5 => new Route(1, 19, 4, ORANGE),
  6 => new Route(1, 26, 2, GRAY),
  7 => new Route(1, 26, 2, GRAY),
  8 => new Route(2, 17, 2, GRAY),
  9 => new Route(2, 17, 2, GRAY),
  10 => new Route(2, 20, 2, YELLOW),
  11 => new Route(2, 20, 2, RED),

  100 => new Route(3, 10, 4, GRAY),
  
  12 => new Route(3, 32, 4, GRAY),
  13 => new Route(3, 34, 3, GRAY),
  14 => new Route(3, 36, 6, WHITE),
  15 => new Route(4, 16, 4, PINK),
  16 => new Route(4, 26, 2, GRAY),
  17 => new Route(5, 8, 3, RED),
  18 => new Route(5, 22, 4, BLUE),
  19 => new Route(5, 24, 3, ORANGE),
  20 => new Route(5, 24, 3, BLACK),
  21 => new Route(5, 27, 2, GREEN),
  22 => new Route(5, 27, 2, WHITE),
  23 => new Route(5, 33, 4, WHITE),
  24 => new Route(6, 9, 4, RED),
  25 => new Route(6, 11, 1, GRAY),
  26 => new Route(6, 11, 1, GRAY),
  27 => new Route(6, 14, 2, GRAY),
  28 => new Route(6, 21, 2, GRAY),
  29 => new Route(6, 21, 2, GRAY),
  30 => new Route(7, 10, 4, GREEN),
  31 => new Route(7, 12, 4, BLACK),
  32 => new Route(7, 12, 4, ORANGE),
  33 => new Route(7, 21, 4, RED),
  34 => new Route(7, 22, 4, PINK),
  35 => new Route(7, 23, 5, WHITE),
  36 => new Route(7, 28, 3, RED),
  37 => new Route(7, 28, 3, YELLOW),

  99 => new Route(7, 31, 2, GRAY),

  38 => new Route(8, 10, 6, ORANGE),
  39 => new Route(8, 22, 2, GRAY),
  40 => new Route(8, 22, 2, GRAY),
  41 => new Route(8, 29, 3, GRAY),
  42 => new Route(8, 33, 6, PINK),
  43 => new Route(8, 36, 4, BLACK),
  44 => new Route(9, 11, 6, GREEN),
  45 => new Route(9, 15, 6, BLACK),
  46 => new Route(9, 21, 5, YELLOW),
  47 => new Route(9, 23, 3, GRAY),
  48 => new Route(9, 31, 2, GRAY),
  49 => new Route(10, 22, 5, RED),
  50 => new Route(10, 28, 3, PINK),
  51 => new Route(10, 32, 6, YELLOW),
  52 => new Route(10, 36, 4, BLUE),
  53 => new Route(11, 19, 2, GRAY),
  54 => new Route(12, 21, 2, GRAY),
  55 => new Route(12, 21, 2, GRAY),
  56 => new Route(12, 22, 1, GRAY),
  57 => new Route(12, 22, 1, GRAY),
  58 => new Route(12, 27, 2, BLUE),
  59 => new Route(12, 27, 2, PINK),
  60 => new Route(13, 15, 2, GRAY),
  61 => new Route(13, 28, 3, ORANGE),
  62 => new Route(14, 18, 3, WHITE),
  63 => new Route(14, 19, 3, GREEN),
  64 => new Route(14, 21, 2, GRAY),
  65 => new Route(14, 27, 2, GRAY),
  66 => new Route(15, 23, 3, GRAY),
  67 => new Route(15, 30, 3, YELLOW),
  68 => new Route(15, 30, 3, PINK),
  69 => new Route(16, 19, 6, RED),
  70 => new Route(17, 20, 3, BLUE),
  71 => new Route(17, 29, 5, BLACK),
  72 => new Route(17, 33, 3, GRAY),
  73 => new Route(18, 24, 4, YELLOW),
  74 => new Route(18, 26, 3, BLACK),
  75 => new Route(18, 27, 2, GRAY),
  76 => new Route(20, 24, 2, WHITE),
  77 => new Route(20, 24, 2, GREEN),
  78 => new Route(20, 35, 2, ORANGE),
  79 => new Route(20, 35, 2, BLACK),
  80 => new Route(21, 31, 3, BLUE),
  81 => new Route(23, 31, 3, GRAY),
  82 => new Route(24, 26, 2, GRAY),
  83 => new Route(24, 27, 5, GREEN),
  84 => new Route(24, 33, 2, GRAY),
  85 => new Route(24, 35, 2, GRAY),
  86 => new Route(25, 28, 6, BLUE),
  87 => new Route(25, 30, 5, GREEN),
  88 => new Route(25, 30, 5, PINK),
  89 => new Route(25, 32, 1, GRAY),
  90 => new Route(25, 32, 1, GRAY),
  91 => new Route(26, 35, 2, GRAY),
  92 => new Route(26, 35, 2, GRAY),
  93 => new Route(28, 30, 5, ORANGE),
  94 => new Route(28, 30, 5, WHITE),
  95 => new Route(29, 33, 2, GRAY),
  96 => new Route(29, 36, 6, GRAY),
  97 => new Route(32, 34, 1, GRAY),
  98 => new Route(32, 34, 1, GRAY),
];

/**
 * List of DestinationCard.
 */
$this->DESTINATIONS = [
  1 => [
    1 => new DestinationCard(2, 16, 12), // Boston	Miami	12
    2 => new DestinationCard(3, 23, 13), // Calgary	Phoenix	13
    3 => new DestinationCard(3, 28, 7), // Calgary	Salt Lake City	7
    4 => new DestinationCard(5, 19, 7), // Chicago	New Orleans	7
    5 => new DestinationCard(5, 31, 9), // Chicago	Santa Fe	9
    6 => new DestinationCard(6, 20, 11), // Dallas	New York	11
    7 => new DestinationCard(7, 9, 4), // Denver	El Paso	4
    8 => new DestinationCard(7, 24, 11), // Denver	Pittsburgh	11
    9 => new DestinationCard(8, 9, 10), // Duluth	El Paso	10
    10 => new DestinationCard(8, 11, 8), // Duluth	Houston	8
    11 => new DestinationCard(10, 15, 8), // Helena	Los Angeles	8
    12 => new DestinationCard(12, 11, 5), // Kansas City	Houston	5
    13 => new DestinationCard(15, 5, 16), // Los Angeles	Chicago	16
    14 => new DestinationCard(15, 16, 20), // Los Angeles	Miami	20
    15 => new DestinationCard(15, 20, 21), // Los Angeles	New York	21
    16 => new DestinationCard(17, 1, 9), // Montréal	Atlanta	9
    17 => new DestinationCard(17, 19, 13), // Montréal	New Orleans	13
    18 => new DestinationCard(20, 1, 6), // New York	Atlanta	6
    19 => new DestinationCard(25, 18, 17), // Portland	Nashville	17
    20 => new DestinationCard(25, 23, 11), // Portland	Phoenix	11
    21 => new DestinationCard(30, 1, 17), // San Francisco	Atlanta	17
    22 => new DestinationCard(29, 18, 8), // Sault St. Marie	Nashville	8
    23 => new DestinationCard(29, 21, 9), // Sault St. Marie	Oklahoma City	9
    24 => new DestinationCard(32, 15, 9), // Seattle	Los Angeles	9
    25 => new DestinationCard(32, 20, 22), // Seattle	New York	22
    26 => new DestinationCard(33, 16, 10), // Toronto	Miami	10
    27 => new DestinationCard(34, 17, 20), // Vancouver	Montréal	20
    28 => new DestinationCard(34, 31, 13), // Vancouver	Santa Fe	13
    29 => new DestinationCard(36, 11, 12), // Winnipeg	Houston	12
    30 => new DestinationCard(36, 14, 11), // Winnipeg	Little Rock	11
  ]
];

/**
 * Points scored for claimed routes.
 */
$this->ROUTE_POINTS = [
  1 => 1,
  2 => 2,
  3 => 4,
  4 => 7,
  5 => 10,
  6 => 15,
];