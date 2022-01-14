<?php

/* 
 * Game version 
 */
define('INITIAL_TRAIN_CAR_CARDS_IN_HAND', 4); // Number of train car cards in hand, for each player, at the beginning of the game.
define('VISIBLE_LOCOMOTIVES_COUNTS_AS_TWO_CARDS', true); // Says if it is possible to take only one visible locomotive.
define('RESET_VISIBLE_CARDS_WITH_LOCOMOTIVES', 3); // Resets visible cards when 3 locomotives are visible (null means disabled)
define('TRAIN_CARS_NUMBER_TO_START_LAST_TURN', 2); // 2 means 0, 1, or 2 will start last turn
define('TRAIN_CARS_PER_PLAYER', 45);
define('INITIAL_DESTINATION_CARD_PICK', 3); // Number of destinations cards shown at the beginning.
define('INITIAL_DESTINATION_MINIMUM_KEPT', 2); // Minimum number of destinations cards to keep at the beginning.
define('ADDITIONAL_DESTINATION_CARD_PICK', 3); // Number of destinations cards shown at pick destination action.
define('ADDITIONAL_DESTINATION_MINIMUM_KEPT', 1); // Minimum number of destinations cards to keep at pick destination action.
define('UNUSED_DESTINATIONS_GO_TO_DECK_BOTTOM', true); // Indicates if unpicked destinations cards go back to the bottom of the deck.
define('POINTS_FOR_LONGEST_PATH', 10); // points for maximum longest countinuous path (null means disabled)
define('POINTS_FOR_GLOBETROTTER', null); // points for maximum completed destinations (null means disabled)
define('MINIMUM_PLAYER_FOR_DOUBLE_ROUTES', 4); // 4 means 2-3 players cant use double routes
define('NUMBER_OF_LOCOMOTIVE_CARDS', 14);
define('NUMBER_OF_COLORED_CARDS', 12);

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
define('ST_MULTIPLAYER_CHOOSE_INITIAL_DESTINATIONS', 20);

define('ST_PLAYER_CHOOSE_ACTION', 30);
define('ST_PLAYER_DRAW_SECOND_CARD', 31);
define('ST_PLAYER_CHOOSE_ADDITIONAL_DESTINATIONS', 32);

define('ST_NEXT_PLAYER', 80);

define('ST_END_SCORE', 98);

define('ST_END_GAME', 99);
define('END_SCORE', 100);

/*
 * Options
 */

//define('UNDO', 'Undo');

/*
 * Variables
 */

define('LAST_TURN', 'LAST_TURN');

?>
