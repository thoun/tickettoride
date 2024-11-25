<?php

require_once(__DIR__.'/../../php/objects/destination.php');


function getBaseSmallDestinations() {
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

function get1912SmallDestinations() {
  return [
    101 => new DestinationCard(1, 29, 7), // Amsterdam Pampona 7
    102 => new DestinationCard(1, 42, 6), // Amsterdam Venezia 6
    103 => new DestinationCard(1, 45, 12), // Amsterdam Wilno 12
    104 => new DestinationCard(2, 19, 10), // Angora  Kharov 10
    105 => new DestinationCard(3, 2, 5), // Athina Angora  5
    106 => new DestinationCard(3, 45, 11), // Athina	Wilno 11
    107 => new DestinationCard(4, 8, 8), // Barcelona Bruxelles 8
    108 => new DestinationCard(4, 27, 8), // Barcelona  Munchen 8
    109 => new DestinationCard(5, 2, 13), // Berlin  Angora 13
    110 => new DestinationCard(5, 3, 11), // Berlin  Athina 11
    111 => new DestinationCard(5, 9, 8), // Berlin  Bucuresti 8
    112 => new DestinationCard(5, 26, 12), // Berlin Moska 12
    113 => new DestinationCard(5, 33, 9), // Berlin Roma 9
    114 => new DestinationCard(5, 44, 3), // Berlin Wien 3
    115 => new DestinationCard(6, 25, 7), // Brest  Marseille 7
    116 => new DestinationCard(6, 42, 8), // Brest  Venezia 8
    117 => new DestinationCard(8, 13, 9), // Bruxelles  Danzig  9
    118 => new DestinationCard(8, 41, 10), // Bruxelles  Stockholm  10
    119 => new DestinationCard(9, 16, 7), // Bucuresti Erzurum 7
    120 => new DestinationCard(10, 40, 5), // Budapest Sofia 5
    121 => new DestinationCard(11, 18, 13), // Cadiz Frankfurt 13
    122 => new DestinationCard(13, 10, 7), // Danzig Budapest  7
    123 => new DestinationCard(14, 20, 9), // Dieppe Kobenhagvn  9
    124 => new DestinationCard(14, 25, 5), // Dieppe Marseille  5
    125 => new DestinationCard(15, 17, 9), // Edinburgh  Essen 9
    126 => new DestinationCard(17, 21, 10), // Essen  Kiev  10
    127 => new DestinationCard(18, 20, 5), // Frankfurt  Kobenhagvn  5
    128 => new DestinationCard(18, 37, 13), // Frankfurt  Smolensk  13
    129 => new DestinationCard(21, 31, 6), // Kiev Petrograd 6
    130 => new DestinationCard(21, 39, 8), // Kiev	Sochi 8
    131 => new DestinationCard(22, 11, 2), // Lisboa Cadiz 2
    132 => new DestinationCard(23, 2, 20), // London  Angora  20
    133 => new DestinationCard(23, 3, 16), // London  Athina  16
    134 => new DestinationCard(23, 5, 7), // London  Berlin  7
    135 => new DestinationCard(23, 24, 10), // London  Madrid  10
    136 => new DestinationCard(23, 26, 19), // London  Moskva  19
    137 => new DestinationCard(23, 30, 3), // London  Paris  3
    138 => new DestinationCard(23, 33, 10), // London  Roma  10
    139 => new DestinationCard(23, 44, 10), // London  Wien  10
    140 => new DestinationCard(24, 2, 21), // Madrid Angora  21
    141 => new DestinationCard(24, 3, 16), // Madrid Athina 16
    142 => new DestinationCard(24, 5, 13), // Madrid Berlin 13
    143 => new DestinationCard(24, 14, 8), // Madrid Dieppe  8
    144 => new DestinationCard(24, 26, 25), // Madrid Moskau  25
    145 => new DestinationCard(24, 33, 10), // Madrid Roma  10
    146 => new DestinationCard(24, 44, 13), // Madrid Wien  13
    147 => new DestinationCard(24, 47, 8), // Madrid Zurich  8
    148 => new DestinationCard(25, 17, 8), // Marseille Essen 8
    149 => new DestinationCard(26, 2, 14), // Moskva Angora 14
    150 => new DestinationCard(26, 3, 14), // Moskva Athina 14
    151 => new DestinationCard(27, 31, 14), // Munchen Petrograd 14
    152 => new DestinationCard(27, 35, 7), // Munchen Sarajevo  7
    153 => new DestinationCard(28, 12, 8), // Palermo Constantinople  8
    154 => new DestinationCard(29, 28, 12), // Pampluna Palermo 12
    155 => new DestinationCard(30, 2, 13), // Paris Angora 13
    156 => new DestinationCard(30, 3, 13), // Paris Athina 13
    157 => new DestinationCard(30, 5, 7), // Paris Berlin 7
    158 => new DestinationCard(30, 15, 7), // Paris Edinburgh 7
    159 => new DestinationCard(30, 24, 7), // Paris Madrid 7
    160 => new DestinationCard(30, 26, 18), // Paris Moskva 18
    161 => new DestinationCard(30, 33, 10), // Paris Roma 10
    162 => new DestinationCard(30, 44, 8), // Paris Wien  8
    163 => new DestinationCard(30, 46, 7), // Paris Zabrag  7
    164 => new DestinationCard(32, 9, 10), // Riga  Bucuresti 10
    165 => new DestinationCard(32, 19, 10), // Riga  Kharov 10
    166 => new DestinationCard(33, 2, 11), // Roma Angora 11
    167 => new DestinationCard(33, 3, 6), // Roma Athena 6
    168 => new DestinationCard(33, 26, 17), // Roma Moskva 17
    169 => new DestinationCard(33, 38, 8), // Roma Smyrna  8
    170 => new DestinationCard(34, 16, 5), // Rostov  Erzhurum  5
    171 => new DestinationCard(35, 36, 8), // Sarajevo Sevastopol  8
    172 => new DestinationCard(37, 34, 8), // Smolensk Rostov  8
    173 => new DestinationCard(39, 38, 9), // Sochi Smyrna  9
    174 => new DestinationCard(40, 21, 6), // Sofia Kiev  6
    175 => new DestinationCard(40, 38, 5), // Sofia Smyrna  5
    176 => new DestinationCard(41, 45, 12), // Stockholm Wilno  12
    177 => new DestinationCard(42, 12, 10), // Venezia Constantinople  10
    178 => new DestinationCard(42, 43, 8), // Venezia Warzawa 8
    179 => new DestinationCard(43, 10, 5), // Warzawa Budapest  5
    180 => new DestinationCard(43, 36, 12), // Warzawa Sevastopol  12
    181 => new DestinationCard(43, 37, 6), // Warzawa Smolensk  6
    182 => new DestinationCard(44, 2, 10), // Wien Angora 10
    183 => new DestinationCard(44, 3, 8), // Wien Athena 8
    184 => new DestinationCard(44, 26, 12), // Wien Moskva 12
    185 => new DestinationCard(44, 33, 6), // Wien Roma 6
    186 => new DestinationCard(44, 41, 11), // Wien Stockholm 11
    187 => new DestinationCard(46, 7, 6), // Zagrab Brindisi  6
    188 => new DestinationCard(47, 7, 6), // Zurich Brindisi  6
    189 => new DestinationCard(47, 10, 6), // Zurich Budapest 6
  ];
}

function getBaseBigDestinations() {
  return [
    1 => new DestinationCard(6, 31, 20), // Brest	Petrograd	20
    2 => new DestinationCard(11, 41, 21), // Cadiz	Sotcholm	21
    3 => new DestinationCard(15, 3, 21), // Edinburgh	Athina	21
    4 => new DestinationCard(20, 16, 21), // Kobenhavn	Erzurum	21
    5 => new DestinationCard(22, 13, 20), // Lisboa Danzig	20
    6 => new DestinationCard(28, 26, 20), // Palermo Moskva	20
  ];
}

function get1912BigDestinations() {
  return [
    101 => new DestinationCard(1, 34, 19), // Amsterdam Rostov  19
    102 => new DestinationCard(6, 31, 20), // Brest	Petrograd	20
    103 => new DestinationCard(11, 41, 21), // Cadiz	Sotcholm	21
    104 => new DestinationCard(15, 3, 21), // Edinburgh	Athina	21
    105 => new DestinationCard(17, 2, 16), // Essen  Angora  16
    106 => new DestinationCard(20, 16, 21), // Kobenhavn	Erzurum	21
    107 => new DestinationCard(22, 13, 20), // Lisboa Danzig	20
    108 => new DestinationCard(23, 39, 20), // London  Sochi 20
    109 => new DestinationCard(28, 26, 20), // Palermo Moskva	20
    110 => new DestinationCard(29, 21, 18), // Pamplona Kyiv  18
    111 => new DestinationCard(30, 36, 17), // Paris  Sevastopol  17
    112 => new DestinationCard(32, 7, 17), // Riga Brindisi  17
  ];
}

function getAllDestinations() {
  return [
    1 => getBaseSmallDestinations() + get1912SmallDestinations(),
    2 => getBaseBigDestinations() + get1912BigDestinations(),
  ];
}
