<?php

require_once(__DIR__.'/../../php/objects/destination.php');


function getSmallDestinations() {
  return [
    1 => new DestinationCard(1, 29, 7), // Amsterdam Pampona 7
    2 => new DestinationCard(1, 45, 12), // Amsterdam Wilno 12
    3 => new DestinationCard(2, 19, 10), // Angora  Kharov 10
    4 => new DestinationCard(3, 2, 5), // Athina Angora  5
    5 => new DestinationCard(3, 45, 11), // Athina	Wilno 11
    6 => new DestinationCard(4, 8, 8), // Barcelona Bruxelles 8
    7 => new DestinationCard(4, 27, 8), // Barcelona  Munchen 8
    8 => new DestinationCard(5, 9, 8), // Berlin  Bucuresti 8
    9 => new DestinationCard(5, 26, 12), // Berlin Moska 12
    10 => new DestinationCard(5, 33, 9), // Berlin  Roma 9
    11 => new DestinationCard(6, 25, 7), // Brest  Marseille 7
    12 => new DestinationCard(6, 42, 8), // Brest  Venezia 8
    13 => new DestinationCard(8, 13, 9), // Bruxelles  Danzig  9
    14 => new DestinationCard(10, 40, 5), // Budapest Sofia 5
    15 => new DestinationCard(15, 30, 7), // Edinburgh  Paris 7
    16 => new DestinationCard(17, 21, 10), // Essen  Kiev  10
    17 => new DestinationCard(18, 20, 5), // Frankfurt  Kobenhagvn  5
    18 => new DestinationCard(18, 37, 13), // Frankfurt  Smolensk  13
    19 => new DestinationCard(21, 31, 6), // Kiev Petrograd 6
    20 => new DestinationCard(21, 39, 8), // Kiev	Sochi 8
    21 => new DestinationCard(23, 5, 7), // London  Berlin  7
    22 => new DestinationCard(23, 44, 10), // London  Wien  10
    23 => new DestinationCard(24, 14, 8), // Madrid Dieppe  8
    24 => new DestinationCard(24, 47, 8), // Madrid Zurich  8
    25 => new DestinationCard(25, 17, 8), // Marseille Essen 8
    26 => new DestinationCard(28, 12, 8), // Palermo Constantinople  8
    27 => new DestinationCard(30, 44, 8), // Paris Wien  8
    28 => new DestinationCard(30, 46, 7), // Paris Zabrag  7
    29 => new DestinationCard(32, 9, 10), // Riga  Bucuresti 10
    30 => new DestinationCard(33, 38, 8), // Roma Smyrna  8
    31 => new DestinationCard(34, 16, 5), // Rostov  Erzhurum  5
    32 => new DestinationCard(35, 36, 8), // Sarajevo Sevastopol  8
    33 => new DestinationCard(37, 34, 8), // Smolensk Rostov  8
    34 => new DestinationCard(40, 38, 5), // Sofia Smyrna  5
    35 => new DestinationCard(41, 44, 11), // Stokolm Wien  11
    36 => new DestinationCard(42, 12, 10), // Venezia Constantinople  10
    37 => new DestinationCard(43, 37, 6), // Warzawa Smolensk  6
    38 => new DestinationCard(46, 7, 6), // Zagrab Brindisi  6
    39 => new DestinationCard(47, 7, 6), // Zurich Brindisi  6
    40 => new DestinationCard(47, 10, 6), // Zurich Budapest 6
  ];
}

function getBigDestinations() {
  return [
    1 => new DestinationCard(6, 31, 20), // Brest	Petrograd	20
    2 => new DestinationCard(11, 41, 21), // Cadiz	Sotcholm	21
    3 => new DestinationCard(15, 3, 21), // Edinburgh	Athina	21
    4 => new DestinationCard(20, 16, 21), // Kobenhavn	Erzurum	21
    5 => new DestinationCard(22, 13, 20), // Lisboa Danzig	20
    6 => new DestinationCard(28, 26, 20), // Palermo Moskva	20
  ];
}

function getAllDestinations() {
  return [
    1 => getSmallDestinations(),
    2 => getBigDestinations(),
  ];
}
