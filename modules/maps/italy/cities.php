<?php

use Bga\Games\TicketToRide\Objects\City;

/**
 * Cities in the map (by alphabetical order).
 */
function getCities() {
  return [
    1 => new City('Agrigento', 166, 1835),
    2 => new City('Ancona', 761, 848),
    3 => new City('Bari', 934, 1473),
    4 => new City('Bergamo', 562, 258),
    5 => new City('Bologna', 606, 565),
    6 => new City('Bolzano', 815, 238),
    7 => new City('Cagliari', 198, 1194),
    8 => new City('Catania', 364, 1912),
    9 => new City('Cosenza', 675, 1713),
    10 => new City('Firenze', 522, 663),
    11 => new City('Foggia', 797, 1304),
    12 => new City('Genova', 343, 402),
    13 => new City('Grosseto', 412, 816),
    14 => new City('Lecce', 1038, 1682),
    15 => new City('Messina', 501, 1841),
    16 => new City('Milano', 461, 258),
    17 => new City('Napoli', 578, 1319),
    18 => new City('Olbia', 237, 877),
    19 => new City('Palermo', 225, 1672),
    20 => new City('Parma', 517, 427),
    21 => new City('Perugia', 587, 838),
    22 => new City('Pescara', 745, 1081),
    23 => new City('Pisa', 433, 598),
    24 => new City('Ravenna', 699, 626),
    25 => new City('Roma', 481, 1041),
    26 => new City('Salerno', 630, 1399),
    27 => new City('Sassari', 78, 931),
    28 => new City('Siracusa', 342, 2003),
    29 => new City('Taranto', 914, 1587),
    30 => new City('Tarvisio', 1038, 400),
    31 => new City('Torino', 252, 199),
    32 => new City('Trieste', 997, 553),
    33 => new City('Venezia', 790, 476),
    34 => new City('Verona', 653, 394),

    // countries end point
    1001 => new City('Francia', 66, 170),
    2001 => new City('Monaco', 77, 379),
    3001 => new City('Svizzera', 400, 29),
    3002 => new City('Svizzera', 694, 122),
    4001 => new City('Austria', 1010, 271),
    5001 => new City('Croazia', 1075, 722),
    5002 => new City('Croazia', 1058, 848),
    5003 => new City('Croazia', 1071, 1005),
  ];
}
