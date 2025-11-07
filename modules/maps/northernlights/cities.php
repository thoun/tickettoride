<?php

use Bga\Games\TicketToRideMaps\Objects\City;

const NORTHERNLIGHTS_COUNTRY_DENMARK = 1;
const NORTHERNLIGHTS_COUNTRY_ESTONIA = 2;
const NORTHERNLIGHTS_COUNTRY_FINLAND = 3;
const NORTHERNLIGHTS_COUNTRY_LATVIA = 4;
const NORTHERNLIGHTS_COUNTRY_LITHUANIA = 5;
const NORTHERNLIGHTS_COUNTRY_NORWAY = 6;
const NORTHERNLIGHTS_COUNTRY_POLAND = 7;
const NORTHERNLIGHTS_COUNTRY_RUSSIA = 8;
const NORTHERNLIGHTS_COUNTRY_SWEDEN = 9;

/**
 * Cities on the map, with coordinates at the center of each flag marker.
 * Named cities are listed alphabetically; unnamed markers are listed last.
 */
function getCities() {
  return [
    1 => new City('Ålborg', 443, 1574, country: NORTHERNLIGHTS_COUNTRY_DENMARK),
    2 => new City('Åndalsnes', 213, 1076, country: NORTHERNLIGHTS_COUNTRY_NORWAY),
    3 => new City('Århus', 487, 1664, country: NORTHERNLIGHTS_COUNTRY_DENMARK),
    4 => new City('Apatity', 917, 226, country: NORTHERNLIGHTS_COUNTRY_RUSSIA),
    5 => new City('Bergen', 115, 1330, country: NORTHERNLIGHTS_COUNTRY_NORWAY),
    6 => new City('Boden', 588, 532, country: NORTHERNLIGHTS_COUNTRY_SWEDEN),
    7 => new City('Gdańsk', 908, 1668, country: NORTHERNLIGHTS_COUNTRY_POLAND),
    8 => new City('Göteborg', 527, 1482, country: NORTHERNLIGHTS_COUNTRY_SWEDEN),
    9 => new City('Helsinki', 963, 1041, country: NORTHERNLIGHTS_COUNTRY_FINLAND),
    10 => new City('Honningsvåg', 550, 67, country: NORTHERNLIGHTS_COUNTRY_NORWAY),
    11 => new City('Imatra', 1095, 837, country: NORTHERNLIGHTS_COUNTRY_FINLAND),
    12 => new City('Kajaani', 916, 589, country: NORTHERNLIGHTS_COUNTRY_FINLAND),
    13 => new City('Karlskrona', 758, 1579, country: NORTHERNLIGHTS_COUNTRY_SWEDEN),
    14 => new City('Karlstad', 567, 1278, country: NORTHERNLIGHTS_COUNTRY_SWEDEN),
    15 => new City('Kemijärvi', 774, 348, country: NORTHERNLIGHTS_COUNTRY_FINLAND),
    16 => new City('Kirkenes', 679, 144, country: NORTHERNLIGHTS_COUNTRY_NORWAY),
    17 => new City('Kiruna', 424, 462, country: NORTHERNLIGHTS_COUNTRY_SWEDEN),
    18 => new City('Klaipėda', 1004, 1533, country: NORTHERNLIGHTS_COUNTRY_LITHUANIA),
    19 => new City('København', 598, 1658, country: NORTHERNLIGHTS_COUNTRY_DENMARK),
    20 => new City('Kostomuksha', 961, 480, country: NORTHERNLIGHTS_COUNTRY_RUSSIA),
    21 => new City('Kristiansand', 348, 1493, country: NORTHERNLIGHTS_COUNTRY_NORWAY),
    22 => new City('Kuopio', 980, 708, country: NORTHERNLIGHTS_COUNTRY_FINLAND),
    23 => new City('Kuressaare', 969, 1283, country: NORTHERNLIGHTS_COUNTRY_ESTONIA),
    24 => new City('Lahti', 956, 936, country: NORTHERNLIGHTS_COUNTRY_FINLAND),
    25 => new City('Lieksa', 1025, 594, country: NORTHERNLIGHTS_COUNTRY_FINLAND),
    26 => new City('Lillehammer', 328, 1215, country: NORTHERNLIGHTS_COUNTRY_NORWAY),
    27 => new City('Mo I Rana', 285, 666, country: NORTHERNLIGHTS_COUNTRY_NORWAY),
    28 => new City('Murmansk', 856, 95, country: NORTHERNLIGHTS_COUNTRY_RUSSIA),
    29 => new City('Narvik', 327, 422, country: NORTHERNLIGHTS_COUNTRY_NORWAY),
    30 => new City('Norrköping', 726, 1349, country: NORTHERNLIGHTS_COUNTRY_SWEDEN),
    31 => new City('Oslo', 406, 1330, country: NORTHERNLIGHTS_COUNTRY_NORWAY),
    32 => new City('Oulu', 758, 609, country: NORTHERNLIGHTS_COUNTRY_FINLAND),
    33 => new City('Östersund', 400, 950, country: NORTHERNLIGHTS_COUNTRY_SWEDEN),
    34 => new City('Riga', 1084, 1376, country: NORTHERNLIGHTS_COUNTRY_LATVIA),
    35 => new City('Rovaniemi', 696, 430, country: NORTHERNLIGHTS_COUNTRY_FINLAND),
    36 => new City('Stavanger', 174, 1494, country: NORTHERNLIGHTS_COUNTRY_NORWAY),
    37 => new City('Stockholm', 700, 1217, country: NORTHERNLIGHTS_COUNTRY_SWEDEN),
    38 => new City('Sundsvall', 574, 978, country: NORTHERNLIGHTS_COUNTRY_SWEDEN),
    39 => new City('Tallinn', 1003, 1149, country: NORTHERNLIGHTS_COUNTRY_ESTONIA),
    40 => new City('Tampere', 853, 936, country: NORTHERNLIGHTS_COUNTRY_FINLAND),
    41 => new City('Tornio', 693, 536, country: NORTHERNLIGHTS_COUNTRY_FINLAND),
    42 => new City('Tromsø', 373, 285, country: NORTHERNLIGHTS_COUNTRY_NORWAY),
    43 => new City('Trondheim', 290, 998, country: NORTHERNLIGHTS_COUNTRY_NORWAY),
    44 => new City('Turku', 844, 1041, country: NORTHERNLIGHTS_COUNTRY_FINLAND),
    45 => new City('Umeå', 639, 760, country: NORTHERNLIGHTS_COUNTRY_SWEDEN),
    46 => new City('Vaasa', 715, 825, country: NORTHERNLIGHTS_COUNTRY_FINLAND),
    47 => new City('Visby', 842, 1401, country: NORTHERNLIGHTS_COUNTRY_SWEDEN),   
    48 => new City('Unnamed Russian city', 1079, 282, country: NORTHERNLIGHTS_COUNTRY_RUSSIA),
    49 => new City('Unnamed Swedish city', 449, 717, country: NORTHERNLIGHTS_COUNTRY_SWEDEN),
    50 => new City('Unnamed Swedish city', 541, 335, country: NORTHERNLIGHTS_COUNTRY_SWEDEN),
  ];
}
