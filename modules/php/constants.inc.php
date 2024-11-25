<?php

/*
 * Table options
 */

const MAP_OPTION = 102;
const EXPANSION_OPTION = 101;
const SHOW_TURN_ORDER_OPTION = 110;

/* 
 * Colors 
 */
const GRAY = 0;
const PINK = 1;
const WHITE = 2;
const BLUE = 3;
const YELLOW = 4;
const ORANGE = 5;
const BLACK = 6;
const RED = 7;
const GREEN = 8;

/*
 * State constants
 */
const ST_BGA_GAME_SETUP = 1;

const ST_DEAL_INITIAL_DESTINATIONS = 10;
const ST_MULTIPLAYER_CHOOSE_INITIAL_DESTINATIONS = 21;
const ST_PRIVATE_CHOOSE_INITIAL_DESTINATIONS = 22;

const ST_PLAYER_CHOOSE_ACTION = 30;
const ST_PLAYER_DRAW_SECOND_CARD = 31;
const ST_PLAYER_CHOOSE_ADDITIONAL_DESTINATIONS = 32;
const ST_PLAYER_CONFIRM_TUNNEL = 33;

const ST_NEXT_PLAYER = 80;

const ST_END_SCORE = 98;

const ST_END_GAME = 99;
const END_SCORE = 100;

/*
 * Variables (numbers)
 */

const LAST_TURN = 'LAST_TURN';

/*
 * Global variables (objects)
 */

const TUNNEL_ATTEMPT = 'TUNNEL_ATTEMPT';

?>
