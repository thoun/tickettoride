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

require_once(__DIR__.'/modules/php/objects/destination.php');

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

$this->DESTINATIONS = [
  1 => [
    1 => new DestinationCard(2, 16, 12), // Boston	Miami	12
    2 => new DestinationCard(3, 23, 13), // Calgary	Phoenix	13
    3 => new DestinationCard(3, 28, 7), // Calgary	Salt Lake City	7
    4 => new DestinationCard(5, 19, 7), // Chicago	New Orleans	7
    5 => new DestinationCard(5, 31, 9), // Chicago	Santa Fe	9
    1 => new DestinationCard(6, 20, 11), // Dallas	New York	11
    1 => new DestinationCard(7, 9, 4), // Denver	El Paso	4
    1 => new DestinationCard(7, 24, 11), // Denver	Pittsburgh	11
    1 => new DestinationCard(8, 9, 10), // Duluth	El Paso	10
    1 => new DestinationCard(8, 11, 8), // Duluth	Houston	8
    1 => new DestinationCard(10, 15, 8), // Helena	Los Angeles	8
    1 => new DestinationCard(12, 11, 5), // Kansas City	Houston	5
    1 => new DestinationCard(15, 5, 16), // Los Angeles	Chicago	16
    1 => new DestinationCard(15, 16, 20), // Los Angeles	Miami	20
    1 => new DestinationCard(15, 20, 21), // Los Angeles	New York	21
    1 => new DestinationCard(17, 1, 9), // Montréal	Atlanta	9
    1 => new DestinationCard(17, 19, 13), // Montréal	New Orleans	13
    1 => new DestinationCard(20, 1, 6), // New York	Atlanta	6
    1 => new DestinationCard(25, 18, 17), // Portland	Nashville	17
    1 => new DestinationCard(25, 23, 11), // Portland	Phoenix	11
    1 => new DestinationCard(30, 1, 17), // San Francisco	Atlanta	17
    1 => new DestinationCard(29, 18, 8), // Sault St. Marie	Nashville	8
    1 => new DestinationCard(29, 21, 9), // Sault St. Marie	Oklahoma City	9
    1 => new DestinationCard(32, 15, 9), // Seattle	Los Angeles	9
    1 => new DestinationCard(32, 20, 22), // Seattle	New York	22
    1 => new DestinationCard(33, 16, 10), // Toronto	Miami	10
    1 => new DestinationCard(34, 17, 20), // Vancouver	Montréal	20
    1 => new DestinationCard(34, 31, 13), // Vancouver	Santa Fe	13
    1 => new DestinationCard(36, 11, 12), // Winnipeg	Houston	12
    1 => new DestinationCard(36, 14, 11), // Winnipeg	Little Rock	11
  ]
];
