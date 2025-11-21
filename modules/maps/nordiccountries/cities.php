<?php

require_once(__DIR__.'/../../php/objects/city.php');

/**
 * Cities in the map (by alphabetical order).
 */
function getCities() {
  return [
    1 => new City('Ålborg', 429, 1564),
    2 => new City('Århus', 476, 1650),
    3 => new City('Åndalsnes', 200, 1071),
    4 => new City('Bergen', 104, 1321),
    5 => new City('Boden', 585, 536),
    6 => new City('Göteborg', 512, 1468),
    7 => new City('Helsinki', 939, 1027),
    8 => new City('Honningsvåg', 527, 52),
    9 => new City('Imatra', 1058, 824),
    10 => new City('Kajaani', 895, 570),
    11 => new City('Karlskrona', 740, 1567),
    12 => new City('Kirkenes', 657, 139),
    13 => new City('Kiruna', 397, 433),
    14 => new City('København', 580, 1641),
    15 => new City('Kristiansand', 326, 1475),
    16 => new City('Kuopio', 956, 708),
    17 => new City('Lahti', 938, 923),
    18 => new City('Lillehammer', 317, 1187),
    19 => new City('Lieksa', 984, 616),
    20 => new City('Mo I Rana', 280, 642),
    21 => new City('Murmansk', 839, 93),
    22 => new City('Narvik', 305, 405),
    23 => new City('Norrköping', 703, 1337),
    24 => new City('Oslo', 392, 1331),
    25 => new City('Oulu', 741, 594),
    26 => new City('Örebro', 543, 1271),
    27 => new City('Östersund', 418, 931),
    28 => new City('Rovaniemi', 688, 425),
    29 => new City('Stavanger', 171, 1462),
    30 => new City('Stockholm', 711, 1219),
    31 => new City('Sundsvall', 558, 961),
    32 => new City('Tampere', 839, 924),
    33 => new City('Trondheim', 278, 990),
    34 => new City('Tromsø', 351, 258),
    35 => new City('Tallinn', 967, 1136),
    36 => new City('Turku', 837, 1024),
    37 => new City('Tornio', 676, 521),
    38 => new City('Vaasa', 694, 812),
    39 => new City('Umeå', 620, 749),
  ];
}
