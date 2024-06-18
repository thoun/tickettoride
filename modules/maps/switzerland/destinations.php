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

function getAllDestinations() {
  return [
    1 => getBaseDestinations(),
  ];
}
