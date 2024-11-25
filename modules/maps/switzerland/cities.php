<?php

require_once(__DIR__.'/../../php/objects/city.php');

const COUNTRIES_END_POINTS = [
  -1 => [ 1001, 1002, 1003, 1004 ],
  -2 => [ 2001, 2002, 2003, 2004, 2005 ],
  -3 => [ 3001, 3002, 3003 ],
  -4 => [ 4001, 4002, 4003, 4004, 4005 ],
];

/**
 * Cities in the map (by alphabetical order).
 */
function getCities() {
  return [
    // countries
    -4 => new City('Italia', 1440, 960),
    -3 => new City('Österreich', 1540, 400),
    -2 => new City('Deutschland', 1470, 60),
    -1 => new City('France', 250, 150),

    // cities
    1 => new City('Baden', 859, 216),
    2 => new City('Basel', 620, 178),
    3 => new City('Bellinzona', 1182, 928),
    4 => new City('Bern', 564, 519),
    5 => new City('Brig', 768, 848),
    6 => new City('Brusio', 1567, 884),
    7 => new City('Chur', 1342, 581),
    8 => new City('Davos', 1520, 579),
    9 => new City('Delémont', 485, 289),
    10 => new City('Fribourg', 477, 603),
    11 => new City('Geneva', 63, 928),
    12 => new City('Interlaken', 729, 678),
    13 => new City('Kreuzligen', 1223, 122),
    14 => new City('La Chaux-de-Fonds', 285, 416),
    15 => new City('Lausanne', 280, 737),
    16 => new City('Locarno', 1070, 955),
    17 => new City('Lugano', 1159, 1034),
    18 => new City('Luzern', 881, 462),
    19 => new City('Martigny', 398, 993),
    20 => new City('Neuchâtel', 367, 494),
    21 => new City('Olten', 720, 314),
    22 => new City('Pfäffikon', 1086, 385),
    23 => new City('Sargans', 1292, 480),
    24 => new City('Schaffhausen', 985, 71),
    25 => new City('Schwyz', 1008, 490),
    26 => new City('Sion', 538, 891),
    27 => new City('Solothurn', 594, 337),
    28 => new City('St. Gallen', 1278, 228),
    29 => new City('Vaduz', 1360, 389),
    30 => new City('Wassen', 1019, 667),
    31 => new City('Winterthur', 1038, 178),
    32 => new City('Yverdon', 258, 608),
    33 => new City('Zug', 966, 390),
    34 => new City('Zürich', 964, 270),

    // countries end point
    1001 => new City('France', 264, 1100),
    1002 => new City('France', 44, 1041),
    1003 => new City('France', 114, 335),
    1004 => new City('France', 505, 103),
    2001 => new City('Deutschland', 634, 63),
    2002 => new City('Deutschland', 1082, 26),
    2003 => new City('Deutschland', 1155, 26),
    2004 => new City('Deutschland', 1301, 38),
    2005 => new City('Deutschland', 1428, 128),
    3001 => new City('Österreich', 1575, 336),
    3002 => new City('Österreich', 1479, 373),
    3003 => new City('Österreich', 1719, 470),
    4001 => new City('Italia', 1719, 663),
    4002 => new City('Italia', 1655, 1041),
    4003 => new City('Italia', 1275, 1085),
    4004 => new City('Italia', 995, 1100),
    4005 => new City('Italia', 911, 1056),
  ];
}
                                 