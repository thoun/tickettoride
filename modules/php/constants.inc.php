<?php

const MAP = 'usa';

/* 
 * Colors 
 */
define('GRAY', 0);
define('PINK', 1);
define('WHITE', 2);
define('BLUE', 3);
define('YELLOW', 4);
define('ORANGE', 5);
define('BLACK', 6);
define('RED', 7);
define('GREEN', 8);

/*
 * State constants
 */
define('ST_BGA_GAME_SETUP', 1);

define('ST_DEAL_INITIAL_DESTINATIONS', 10);
define('ST_MULTIPLAYER_CHOOSE_INITIAL_DESTINATIONS', 21);
define('ST_PRIVATE_CHOOSE_INITIAL_DESTINATIONS', 22);

define('ST_PLAYER_CHOOSE_ACTION', 30);
define('ST_PLAYER_DRAW_SECOND_CARD', 31);
define('ST_PLAYER_CHOOSE_ADDITIONAL_DESTINATIONS', 32);
define('ST_PLAYER_CONFIRM_TUNNEL', 33);

define('ST_NEXT_PLAYER', 80);

define('ST_END_SCORE', 98);

define('ST_END_GAME', 99);
define('END_SCORE', 100);

/*
 * Options
 */
define('SHOW_TURN_ORDER', 'SHOW_TURN_ORDER');

/*
 * Variables (numbers)
 */

define('LAST_TURN', 'LAST_TURN');

/*
 * Global variables (objects)
 */

define('TUNNEL_ATTEMPT', 'TUNNEL_ATTEMPT');

?>
