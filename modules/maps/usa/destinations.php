<?php

require_once(__DIR__.'/../../php/objects/destination.php');

function getBaseDestinations() {
  return [
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
    ];
}

function get1910Destinations() {
  return [
    101 => new DestinationCard(3, 18, 14), // Calgary	Nashville	14
    102 => new DestinationCard(5, 1, 5), // Chicago	Atlanta 5
    103 => new DestinationCard(5, 2, 5), // Chicago	Boston	5
    104 => new DestinationCard(5, 20, 5), // Chicago	New-York	5
    105 => new DestinationCard(7, 27, 6), // Denver	Saint Louis	6
    106 => new DestinationCard(8, 6, 7), // Duluth	Dallas	7
    107 => new DestinationCard(11, 35, 10), // Houston Washington	10
    108 => new DestinationCard(12, 2, 11), // Kansas City	Boston	11
    109 => new DestinationCard(13, 16, 21), // Las Vegas	Miami 21
    110 => new DestinationCard(13, 20, 19), // Las Vegas	New-York 19
    111 => new DestinationCard(15, 1, 15), // Los Angeles	Atlanta	15
    112 => new DestinationCard(15, 3, 12), // Los Angeles	Calgary	12
    113 => new DestinationCard(15, 21, 9), // Los Angeles	Oklahoma City	12
    114 => new DestinationCard(17, 6, 13), // Montréal	Dallas	13
    115 => new DestinationCard(17, 26, 7), // Montréal	Raleigh	7
    116 => new DestinationCard(18, 20, 6), // Nashville  New York	6
    117 => new DestinationCard(20, 16, 10), // New York	Miami	10
    118 => new DestinationCard(22, 19, 8), // Omaha	New Orleans	8
    119 => new DestinationCard(23, 2, 19), // Phoenix	Boston	19
    120 => new DestinationCard(24, 19, 8), // Pittsburgh	New Orleans	8
    121 => new DestinationCard(25, 11, 16), // Portland	Houston	16
    122 => new DestinationCard(25, 24, 19), // Portland	Pittsburgh	19
    123 => new DestinationCard(28, 5, 11), // Salt Lake City	Chicago	11
    124 => new DestinationCard(28, 12, 7), // Salt Lake City	Kansas City	7
    125 => new DestinationCard(30, 29, 17), // San Francisco	Sault St. Marie	17
    126 => new DestinationCard(30, 35, 21), // San Francisco	Washington	21
    127 => new DestinationCard(29, 16, 12), // Sault St. Marie	Miami	12
    128 => new DestinationCard(32, 13, 10), // Seattle	Las Vegas	10
    129 => new DestinationCard(32, 21, 14), // Seattle	Oklahoma City	14
    130 => new DestinationCard(27, 16, 8), // Saint Louis	Miami	8
    131 => new DestinationCard(33, 4, 6), // Toronto	Charleston	6
    132 => new DestinationCard(34, 7, 11), // Vancouver	Denver	11
    133 => new DestinationCard(34, 8, 13), // Vancouver	Duluth	13
    134 => new DestinationCard(35, 1, 4), // Washington	Atlanta 4
    135 => new DestinationCard(36, 31, 10), // Winnipeg	Santa Fe	10
  ];
}

function getMegaDestinations() {
  return [
    201 => new DestinationCard(2, 16, 12), // Boston	Miami	12
    202 => new DestinationCard(2, 35, 4), // Boston	Washington	4
    203 => new DestinationCard(3, 23, 13), // Calgary	Phoenix	13
    204 => new DestinationCard(3, 28, 7), // Calgary	Salt Lake City	7
    205 => new DestinationCard(5, 19, 7), // Chicago	New Orleans	7
    206 => new DestinationCard(5, 31, 9), // Chicago	Santa Fe	9
    207 => new DestinationCard(6, 20, 11), // Dallas	New York	11
    208 => new DestinationCard(7, 9, 4), // Denver	El Paso	4
    209 => new DestinationCard(7, 24, 11), // Denver	Pittsburgh	11
    210 => new DestinationCard(8, 9, 10), // Duluth	El Paso	10
    211 => new DestinationCard(8, 11, 8), // Duluth	Houston	8
    212 => new DestinationCard(10, 15, 8), // Helena	Los Angeles	8
    213 => new DestinationCard(12, 11, 5), // Kansas City	Houston	5
    214 => new DestinationCard(15, 5, 16), // Los Angeles Chicago	16
    215 => new DestinationCard(15, 16, 19), // Los Angeles	Miami	19
    216 => new DestinationCard(15, 20, 20), // Los Angeles	New York	20
    217 => new DestinationCard(17, 1, 9), // Montréal	Atlanta	9
    218 => new DestinationCard(17, 5, 7), // Montréal	Chicago	7
    219 => new DestinationCard(17, 19, 13), // Montréal  New Orleans	13
    220 => new DestinationCard(20, 1, 6), // New York	Atlanta	6
    221 => new DestinationCard(25, 18, 17), // Portland	Nashville	17
    222 => new DestinationCard(25, 23, 11), // Portland	Phoenix	11
    223 => new DestinationCard(30, 1, 17), // San Francisco	Atlanta	17
    224 => new DestinationCard(29, 18, 8), // Sault St. Marie	Nashville	8
    225 => new DestinationCard(29, 21, 8), // Sault St. Marie	Oklahoma City	8
    226 => new DestinationCard(32, 15, 9), // Seattle	Los Angeles	9
    227 => new DestinationCard(32, 20, 20), // Seattle	New York	20
    228 => new DestinationCard(33, 16, 10), // Toronto	Miami	10
    229 => new DestinationCard(34, 17, 20), // Vancouver	Montréal	20
    230 => new DestinationCard(34, 25, 2), // Vancouver	Portland	2
    231 => new DestinationCard(34, 31, 13), // Vancouver	Santa Fe	13
    232 => new DestinationCard(36, 11, 12), // Winnipeg	Houston	12
    233 => new DestinationCard(36, 14, 11), // Winnipeg	Little Rock	11
    234 => new DestinationCard(36, 22, 6), // Winnipeg	Omaha	6
  ];
}

function getAllDestinations() {
  return [
    1 => getBaseDestinations() + get1910Destinations() + getMegaDestinations(),
  ];
}
