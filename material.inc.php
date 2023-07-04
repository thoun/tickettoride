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

require_once(__DIR__.'/modules/maps/'.MAP.'/constants.inc.php');
require_once(__DIR__.'/modules/maps/'.MAP.'/cities.php');
require_once(__DIR__.'/modules/maps/'.MAP.'/routes.php');
require_once(__DIR__.'/modules/maps/'.MAP.'/destinations.php');

$this->CITIES = getCities();

/**
 * Route on the map. 
 * For double routes, there is 2 instances of Route.
 * For cities (from/to), it's always low id to high id.
 */
$this->ROUTES = getRoutes();

/**
 * List of DestinationCard.
 */
$this->DESTINATIONS = getAllDestinations();

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