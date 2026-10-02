<?php

use Bga\Games\TicketToRide\Objects\Route;
use Bga\Games\TicketToRide\Objects\RouteSpace;

/**
 * Routes on the Northern Lights map.
 * Each track of a double or triple route has its own Route instance.
 * City endpoints are ordered from the lower id to the higher id.
 * Coordinates use the original 1158 x 1744 map image.
 */
function getRoutes() {
  $routes = [
    // Ålborg – Århus
    1 => new Route(1, 3, RED, [
      new RouteSpace(452, 1622, 67),
    ]),
    2 => new Route(1, 3, BLACK, [
      new RouteSpace(474, 1613, 67),
    ]),

    // Ålborg – Göteborg
    3 => new Route(1, 8, GRAY, [
      new RouteSpace(476, 1516, -51),
    ], locomotives: 1),
    4 => new Route(1, 8, GRAY, [
      new RouteSpace(494, 1530, -51),
    ], locomotives: 1),

    // Ålborg – Kristiansand
    5 => new Route(1, 21, GRAY, [
      new RouteSpace(388, 1534, 38),
    ], locomotives: 1),
    6 => new Route(1, 21, GRAY, [
      new RouteSpace(373, 1553, 37),
    ], locomotives: 1),

    // Ålborg – Oslo
    7 => new Route(1, 31, GRAY, [
      new RouteSpace(430, 1525, 81),
      new RouteSpace(420, 1464, 81),
      new RouteSpace(410, 1403, 81),
    ], locomotives: 1),

    // Åndalsnes – Bergen
    8 => new Route(2, 5, GRAY, [
      new RouteSpace(162, 1091, -27),
      new RouteSpace(106, 1141, -57),
      new RouteSpace(79, 1209, -80),
      new RouteSpace(84, 1283, 72),
    ], locomotives: 2, drawingBonus: 2),
    9 => new Route(2, 5, GRAY, [
      new RouteSpace(172, 1112, -27),
      new RouteSpace(125, 1154, -56),
      new RouteSpace(102, 1212, -81),
      new RouteSpace(107, 1275, 72),
    ], locomotives: 2, drawingBonus: 2),

    // Åndalsnes – Lillehammer
    10 => new Route(2, 26, RED, [
      new RouteSpace(257, 1114, 52),
      new RouteSpace(291, 1158, 52),
    ]),
    11 => new Route(2, 26, YELLOW, [
      new RouteSpace(234, 1124, 53),
      new RouteSpace(272, 1173, 52),
    ]),

    // Åndalsnes – Trondheim
    12 => new Route(2, 43, GRAY, [
      new RouteSpace(244, 1030, -49),
    ], locomotives: 1, drawingBonus: 1),
    13 => new Route(2, 43, GRAY, [
      new RouteSpace(262, 1045, -49),
    ], locomotives: 1, drawingBonus: 1),

    // Århus – København
    14 => new Route(3, 19, GRAY, [
      new RouteSpace(540, 1650, 0),
    ], locomotives: 1),
    15 => new Route(3, 19, GRAY, [
      new RouteSpace(541, 1671, 0),
    ], locomotives: 1),

    // Apatity – Kemijärvi
    16 => new Route(4, 15, PINK, [
      new RouteSpace(873, 269, -39),
      new RouteSpace(829, 305, -39),
    ]),

    // Apatity – Kirkenes
    17 => new Route(4, 16, BLACK, [
      new RouteSpace(866, 198, 18),
      new RouteSpace(812, 182, 17),
      new RouteSpace(753, 165, 17),
    ]),

    // Apatity – Kostomuksha
    18 => new Route(4, 20, GREEN, [
      new RouteSpace(929, 290, 82),
      new RouteSpace(936, 346, 83),
      new RouteSpace(944, 407, 83),
    ]),

    // Apatity – Murmansk
    19 => new Route(4, 28, BLUE, [
      new RouteSpace(935, 172, -70),
      new RouteSpace(910, 120, 26),
    ]),

    // Apatity – Unnamed Russian city
    20 => new Route(4, 48, RED, [
      new RouteSpace(972, 236, 22),
      new RouteSpace(1024, 257, 22),
    ]),

    // Bergen – Lillehammer
    21 => new Route(5, 26, BLUE, [
      new RouteSpace(172, 1289, -23),
      new RouteSpace(229, 1265, -23),
      new RouteSpace(281, 1244, -23),
    ]),

    // Bergen – Oslo
    22 => new Route(5, 31, PINK, [
      new RouteSpace(190, 1320, 1),
      new RouteSpace(246, 1320, -1),
      new RouteSpace(307, 1321, 1),
    ]),
    23 => new Route(5, 31, RED, [
      new RouteSpace(184, 1345, 1),
      new RouteSpace(248, 1345, 0),
      new RouteSpace(307, 1345, 1),
    ]),

    // Bergen – Stavanger
    24 => new Route(5, 36, GRAY, [
      new RouteSpace(116, 1388, 84),
      new RouteSpace(134, 1449, 65),
    ], locomotives: 1, drawingBonus: 1),
    25 => new Route(5, 36, GRAY, [
      new RouteSpace(139, 1382, 82),
      new RouteSpace(157, 1442, 64),
    ], locomotives: 1, drawingBonus: 1),

    // Boden – Kiruna
    26 => new Route(6, 17, BLACK, [
      new RouteSpace(540, 496, 24),
      new RouteSpace(488, 473, 24),
    ]),
    27 => new Route(6, 17, PINK, [
      new RouteSpace(530, 518, 24),
      new RouteSpace(479, 496, 24),
    ]),

    // Boden – Rovaniemi
    28 => new Route(6, 35, ORANGE, [
      new RouteSpace(640, 483, -38),
    ]),

    // Boden – Tornio
    29 => new Route(6, 41, YELLOW, [
      new RouteSpace(641, 540, 0),
    ]),

    // Boden – Umeå
    30 => new Route(6, 45, WHITE, [
      new RouteSpace(593, 589, 80),
      new RouteSpace(604, 651, 80),
      new RouteSpace(614, 711, 80),
    ]),
    31 => new Route(6, 45, BLUE, [
      new RouteSpace(618, 591, 80),
      new RouteSpace(628, 647, 80),
      new RouteSpace(638, 707, 80),
    ]),

    // Boden – Unnamed Swedish city (south)
    32 => new Route(6, 49, GREEN, [
      new RouteSpace(545, 587, -56),
      new RouteSpace(510, 639, -56),
      new RouteSpace(484, 678, -56),
    ]),

    // Gdańsk – Karlskrona
    33 => new Route(7, 13, GRAY, [
      new RouteSpace(869, 1615, 28),
      new RouteSpace(809, 1597, 7),
    ], locomotives: 1),

    // Gdańsk – Klaipėda
    34 => new Route(7, 18, ORANGE, [
      new RouteSpace(936, 1619, -57),
      new RouteSpace(966, 1572, -57),
    ]),
    35 => new Route(7, 18, YELLOW, [
      new RouteSpace(953, 1637, -57),
      new RouteSpace(987, 1585, -57),
    ]),

    // Gdańsk – København
    36 => new Route(7, 19, GRAY, [
      new RouteSpace(855, 1648, 0),
      new RouteSpace(794, 1648, 0),
      new RouteSpace(732, 1649, 0),
      new RouteSpace(671, 1648, 0),
    ], locomotives: 1),
    37 => new Route(7, 19, GRAY, [
      new RouteSpace(855, 1671, 0),
      new RouteSpace(794, 1671, 0),
      new RouteSpace(732, 1671, 0),
      new RouteSpace(671, 1671, 0),
    ], locomotives: 1),

    // Göteborg – Karlstad
    38 => new Route(8, 14, BLUE, [
      new RouteSpace(540, 1413, -78),
      new RouteSpace(552, 1358, -79),
    ]),

    // Göteborg – København
    39 => new Route(8, 19, GRAY, [
      new RouteSpace(543, 1540, 71),
      new RouteSpace(564, 1598, 71),
    ], locomotives: 1),
    40 => new Route(8, 19, GRAY, [
      new RouteSpace(565, 1532, 70),
      new RouteSpace(586, 1590, 72),
    ], locomotives: 1),

    // Göteborg – Norrköping
    41 => new Route(8, 30, ORANGE, [
      new RouteSpace(573, 1440, -39),
      new RouteSpace(616, 1404, -36),
      new RouteSpace(665, 1368, -36),
    ]),
    42 => new Route(8, 30, WHITE, [
      new RouteSpace(584, 1462, -38),
      new RouteSpace(632, 1424, -38),
      new RouteSpace(685, 1382, -38),
    ]),

    // Göteborg – Oslo
    43 => new Route(8, 31, BLACK, [
      new RouteSpace(500, 1430, 56),
      new RouteSpace(468, 1382, 56),
    ]),
    44 => new Route(8, 31, BLUE, [
      new RouteSpace(480, 1443, 56),
      new RouteSpace(445, 1391, 57),
    ]),

    // Helsinki – Imatra
    45 => new Route(9, 11, GREEN, [
      new RouteSpace(1009, 999, -50),
      new RouteSpace(1042, 952, -60),
      new RouteSpace(1071, 890, -70),
    ]),

    // Helsinki – Lahti
    46 => new Route(9, 24, PINK, [
      new RouteSpace(944, 983, 88),
    ]),
    47 => new Route(9, 24, ORANGE, [
      new RouteSpace(968, 982, 88),
    ]),

    // Helsinki – Stockholm
    48 => new Route(9, 37, GRAY, [
      new RouteSpace(920, 1074, -51),
      new RouteSpace(877, 1120, -41),
      new RouteSpace(829, 1157, -30),
      new RouteSpace(771, 1185, -21),
    ], locomotives: 1),
    49 => new Route(9, 37, GRAY, [
      new RouteSpace(938, 1089, -52),
      new RouteSpace(892, 1137, -41),
      new RouteSpace(839, 1176, -32),
      new RouteSpace(782, 1206, -20),
    ], locomotives: 1),

    // Helsinki – Tallinn
    50 => new Route(9, 39, GRAY, [
      new RouteSpace(981, 1099, 81),
    ], locomotives: 1),
    51 => new Route(9, 39, GRAY, [
      new RouteSpace(1005, 1096, 84),
    ], locomotives: 1),

    // Helsinki – Tampere
    52 => new Route(9, 40, BLUE, [
      new RouteSpace(900, 981, 46),
    ]),

    // Helsinki – Turku
    53 => new Route(9, 44, WHITE, [
      new RouteSpace(898, 1032, -1),
    ]),

    // Honningsvåg – Kirkenes
    54 => new Route(10, 16, GRAY, [
      new RouteSpace(572, 113, 40),
      new RouteSpace(626, 145, 21),
    ], locomotives: 1),

    // Honningsvåg – Murmansk
    55 => new Route(10, 28, GRAY, [
      new RouteSpace(611, 74, 0),
      new RouteSpace(672, 74, 0),
      new RouteSpace(734, 72, 0),
      new RouteSpace(795, 72, 0),
    ], locomotives: 2, drawingBonus: 2),
    56 => new Route(10, 28, GRAY, [
      new RouteSpace(611, 97, 0),
      new RouteSpace(672, 97, 0),
      new RouteSpace(734, 95, 0),
      new RouteSpace(795, 95, 0),
    ], locomotives: 2, drawingBonus: 2),

    // Honningsvåg – Tromsø
    57 => new Route(10, 42, GRAY, [
      new RouteSpace(495, 77, -18),
      new RouteSpace(433, 112, -39),
      new RouteSpace(387, 165, -58),
      new RouteSpace(361, 231, -79),
    ], locomotives: 2, drawingBonus: 2),
    58 => new Route(10, 42, GRAY, [
      new RouteSpace(503, 99, -19),
      new RouteSpace(448, 129, -39),
      new RouteSpace(406, 177, -59),
      new RouteSpace(389, 244, -78),
    ], locomotives: 2, drawingBonus: 2),

    // Honningsvåg – Unnamed Swedish city (north)
    59 => new Route(10, 50, GREEN, [
      new RouteSpace(527, 141, -78),
      new RouteSpace(519, 209, -88),
      new RouteSpace(523, 266, 82),
    ]),

    // Imatra – Kuopio
    60 => new Route(11, 22, BLACK, [
      new RouteSpace(1054, 795, 59),
      new RouteSpace(1021, 742, 59),
    ]),

    // Imatra – Lahti
    61 => new Route(11, 24, RED, [
      new RouteSpace(1042, 869, -44),
      new RouteSpace(1002, 908, -45),
    ]),

    // Imatra – Lieksa
    62 => new Route(11, 25, WHITE, [
      new RouteSpace(1085, 767, -90),
      new RouteSpace(1076, 705, 78),
      new RouteSpace(1056, 644, 65),
    ]),

    // Kajaani – Kostomuksha
    63 => new Route(12, 20, BLUE, [
      new RouteSpace(937, 531, -70),
    ]),

    // Kajaani – Kuopio
    64 => new Route(12, 22, RED, [
      new RouteSpace(940, 648, 57),
    ]),

    // Kajaani – Lieksa
    65 => new Route(12, 25, YELLOW, [
      new RouteSpace(963, 592, 4),
    ]),

    // Kajaani – Oulu
    66 => new Route(12, 32, WHITE, [
      new RouteSpace(864, 593, -6),
      new RouteSpace(802, 599, -6),
    ]),

    // Karlskrona – Klaipėda
    67 => new Route(13, 18, GRAY, [
      new RouteSpace(812, 1564, -9),
      new RouteSpace(873, 1554, -9),
      new RouteSpace(933, 1544, -10),
    ], locomotives: 1),

    // Karlskrona – København
    68 => new Route(13, 19, GRAY, [
      new RouteSpace(700, 1577, -10),
      new RouteSpace(641, 1598, -29),
    ], locomotives: 1),
    69 => new Route(13, 19, GRAY, [
      new RouteSpace(707, 1599, -10),
      new RouteSpace(648, 1620, -30),
    ], locomotives: 1),

    // Karlskrona – Norrköping
    70 => new Route(13, 30, RED, [
      new RouteSpace(739, 1526, 82),
      new RouteSpace(729, 1466, 81),
      new RouteSpace(720, 1410, 81),
    ]),
    71 => new Route(13, 30, GREEN, [
      new RouteSpace(763, 1523, 82),
      new RouteSpace(754, 1461, 82),
      new RouteSpace(743, 1400, 81),
    ]),

    // Karlskrona – Visby
    72 => new Route(13, 47, GRAY, [
      new RouteSpace(800, 1514, -70),
      new RouteSpace(821, 1456, -70),
    ], locomotives: 1),

    // Karlstad – Lillehammer
    73 => new Route(14, 26, BLACK, [
      new RouteSpace(493, 1257, 17),
      new RouteSpace(435, 1239, 17),
      new RouteSpace(381, 1222, 17),
    ]),

    // Karlstad – Norrköping
    74 => new Route(14, 30, BLACK, [
      new RouteSpace(651, 1316, 22),
    ]),

    // Karlstad – Oslo
    75 => new Route(14, 31, WHITE, [
      new RouteSpace(487, 1296, -14),
    ]),
    76 => new Route(14, 31, GREEN, [
      new RouteSpace(497, 1317, -14),
    ]),
    77 => new Route(14, 31, ORANGE, [
      new RouteSpace(497, 1342, -14),
    ]),

    // Karlstad – Östersund
    78 => new Route(14, 33, YELLOW, [
      new RouteSpace(516, 1225, 49),
      new RouteSpace(480, 1173, 61),
      new RouteSpace(451, 1118, 65),
      new RouteSpace(429, 1066, 69),
      new RouteSpace(410, 1000, 79),
    ]),

    // Karlstad – Stockholm
    79 => new Route(14, 37, GREEN, [
      new RouteSpace(620, 1229, -24),
    ]),
    80 => new Route(14, 37, RED, [
      new RouteSpace(629, 1251, -25),
    ]),
    81 => new Route(14, 37, BLUE, [
      new RouteSpace(644, 1270, -24),
    ]),

    // Karlstad – Sundsvall
    82 => new Route(14, 38, PINK, [
      new RouteSpace(557, 1209, 82),
      new RouteSpace(550, 1147, 86),
      new RouteSpace(548, 1092, 89),
      new RouteSpace(554, 1029, -79),
    ]),

    // Kemijärvi – Kirkenes
    83 => new Route(15, 16, WHITE, [
      new RouteSpace(769, 292, 82),
      new RouteSpace(755, 231, 62),
      new RouteSpace(716, 182, 42),
    ]),

    // Kemijärvi – Kostomuksha
    84 => new Route(15, 20, ORANGE, [
      new RouteSpace(818, 375, 37),
      new RouteSpace(864, 410, 36),
      new RouteSpace(913, 446, 36),
    ]),

    // Kemijärvi – Rovaniemi
    85 => new Route(15, 35, BLACK, [
      new RouteSpace(737, 399, -54),
    ]),

    // Kemijärvi – Unnamed Swedish city (north)
    86 => new Route(15, 50, RED, [
      new RouteSpace(719, 346, 4),
      new RouteSpace(658, 342, 4),
      new RouteSpace(602, 339, 3),
    ]),

    // Kirkenes – Murmansk
    87 => new Route(16, 28, GRAY, [
      new RouteSpace(736, 129, -12),
      new RouteSpace(800, 123, 0),
    ], locomotives: 1),

    // Kirkenes – Unnamed Swedish city (north)
    88 => new Route(16, 50, YELLOW, [
      new RouteSpace(645, 198, -53),
      new RouteSpace(609, 247, -53),
      new RouteSpace(575, 292, -53),
    ]),

    // Kiruna – Narvik
    89 => new Route(17, 29, YELLOW, [
      new RouteSpace(374, 425, 19),
    ]),
    90 => new Route(17, 29, BLUE, [
      new RouteSpace(368, 449, 19),
    ]),

    // Kiruna – Unnamed Swedish city (south)
    91 => new Route(17, 49, RED, [
      new RouteSpace(431, 525, 86),
      new RouteSpace(435, 587, 86),
      new RouteSpace(439, 648, 86),
    ]),

    // Kiruna – Unnamed Swedish city (north)
    92 => new Route(17, 50, WHITE, [
      new RouteSpace(462, 418, -45),
      new RouteSpace(502, 378, -45),
    ]),

    // Klaipėda – Riga
    93 => new Route(18, 34, BLACK, [
      new RouteSpace(1018, 1480, -64),
      new RouteSpace(1042, 1430, -64),
    ]),
    94 => new Route(18, 34, GREEN, [
      new RouteSpace(1039, 1492, -64),
      new RouteSpace(1067, 1435, -64),
    ]),

    // Klaipėda – Visby
    95 => new Route(18, 47, GRAY, [
      new RouteSpace(929, 1497, 44),
      new RouteSpace(884, 1455, 44),
    ], locomotives: 1),
    96 => new Route(18, 47, GRAY, [
      new RouteSpace(945, 1480, 43),
      new RouteSpace(899, 1437, 43),
    ], locomotives: 1),

    // Kostomuksha – Lieksa
    97 => new Route(20, 25, RED, [
      new RouteSpace(989, 535, 61),
    ]),

    // Kostomuksha – Unnamed Russian city
    98 => new Route(20, 48, YELLOW, [
      new RouteSpace(984, 425, -64),
      new RouteSpace(1009, 375, -63),
      new RouteSpace(1038, 320, -62),
    ]),

    // Kristiansand – Oslo
    99 => new Route(21, 31, WHITE, [
      new RouteSpace(351, 1426, -71),
    ]),
    100 => new Route(21, 31, YELLOW, [
      new RouteSpace(374, 1433, -71),
    ]),

    // Kristiansand – Stavanger
    101 => new Route(21, 36, GRAY, [
      new RouteSpace(294, 1494, -9),
      new RouteSpace(231, 1493, 10),
    ], locomotives: 1, drawingBonus: 1),
    102 => new Route(21, 36, GRAY, [
      new RouteSpace(294, 1517, -9),
      new RouteSpace(231, 1516, 10),
    ], locomotives: 1, drawingBonus: 1),

    // Kuopio – Lahti
    103 => new Route(22, 24, GREEN, [
      new RouteSpace(963, 763, -82),
      new RouteSpace(954, 824, -82),
      new RouteSpace(946, 880, -82),
    ]),
    104 => new Route(22, 24, WHITE, [
      new RouteSpace(988, 761, -83),
      new RouteSpace(979, 823, -82),
      new RouteSpace(970, 884, -82),
    ]),

    // Kuopio – Lieksa
    105 => new Route(22, 25, ORANGE, [
      new RouteSpace(988, 648, -74),
    ]),
    106 => new Route(22, 25, PINK, [
      new RouteSpace(1011, 655, -73),
    ]),

    // Kuopio – Oulu
    107 => new Route(22, 32, BLUE, [
      new RouteSpace(917, 683, 23),
      new RouteSpace(865, 661, 22),
      new RouteSpace(809, 637, 23),
    ]),

    // Kuopio – Vaasa
    108 => new Route(22, 46, YELLOW, [
      new RouteSpace(925, 730, -26),
      new RouteSpace(870, 757, -26),
      new RouteSpace(815, 783, -26),
      new RouteSpace(764, 808, -26),
    ]),

    // Kuressaare – Riga
    109 => new Route(23, 34, WHITE, [
      new RouteSpace(1011, 1315, 26),
    ]),
    110 => new Route(23, 34, RED, [
      new RouteSpace(1002, 1337, 25),
    ]),

    // Kuressaare – Stockholm
    111 => new Route(23, 37, GRAY, [
      new RouteSpace(904, 1286, -4),
      new RouteSpace(843, 1285, 5),
      new RouteSpace(780, 1274, 16),
    ], locomotives: 1),

    // Kuressaare – Tallinn
    112 => new Route(23, 39, GREEN, [
      new RouteSpace(964, 1223, -80),
    ]),
    113 => new Route(23, 39, PINK, [
      new RouteSpace(988, 1226, -80),
    ]),

    // Kuressaare – Visby
    114 => new Route(23, 47, GRAY, [
      new RouteSpace(936, 1312, -39),
      new RouteSpace(886, 1352, -38),
    ], locomotives: 1),

    // Lahti – Tampere
    115 => new Route(24, 40, BLACK, [
      new RouteSpace(902, 934, -1),
    ]),

    // Lieksa – Unnamed Russian city
    116 => new Route(25, 48, PINK, [
      new RouteSpace(1038, 527, -82),
      new RouteSpace(1046, 472, -82),
      new RouteSpace(1055, 410, -81),
      new RouteSpace(1064, 349, -81),
    ]),

    // Lillehammer – Oslo
    117 => new Route(26, 31, WHITE, [
      new RouteSpace(366, 1259, 59),
    ]),
    118 => new Route(26, 31, GREEN, [
      new RouteSpace(345, 1271, 59),
    ]),

    // Lillehammer – Trondheim
    119 => new Route(26, 43, PINK, [
      new RouteSpace(334, 1162, -77),
      new RouteSpace(336, 1098, 79),
      new RouteSpace(309, 1041, 52),
    ]),
    120 => new Route(26, 43, ORANGE, [
      new RouteSpace(360, 1166, -77),
      new RouteSpace(360, 1094, 79),
      new RouteSpace(328, 1026, 52),
    ]),

    // Mo I Rana – Narvik
    121 => new Route(27, 29, GRAY, [
      new RouteSpace(231, 638, 62),
      new RouteSpace(213, 566, -90),
      new RouteSpace(227, 494, -67),
      new RouteSpace(273, 435, -38),
    ], locomotives: 2, drawingBonus: 2),
    122 => new Route(27, 29, GRAY, [
      new RouteSpace(252, 626, 62),
      new RouteSpace(236, 566, -90),
      new RouteSpace(248, 503, -66),
      new RouteSpace(288, 454, -38),
    ], locomotives: 2, drawingBonus: 2),

    // Mo I Rana – Trondheim
    123 => new Route(27, 43, GRAY, [
      new RouteSpace(236, 701, -62),
      new RouteSpace(215, 768, -82),
      new RouteSpace(212, 834, 89),
      new RouteSpace(221, 900, 78),
      new RouteSpace(247, 966, 58),
    ], locomotives: 2, drawingBonus: 3),
    124 => new Route(27, 43, GRAY, [
      new RouteSpace(256, 711, -62),
      new RouteSpace(238, 771, -82),
      new RouteSpace(236, 835, 89),
      new RouteSpace(244, 895, 79),
      new RouteSpace(268, 953, 58),
    ], locomotives: 2, drawingBonus: 3),

    // Mo I Rana – Unnamed Swedish city (south)
    125 => new Route(27, 49, PINK, [
      new RouteSpace(333, 681, 17),
      new RouteSpace(392, 699, 17),
    ]),

    // Murmansk – Unnamed Russian city
    126 => new Route(28, 48, ORANGE, [
      new RouteSpace(928, 100, 24),
      new RouteSpace(984, 129, 31),
      new RouteSpace(1031, 169, 50),
      new RouteSpace(1063, 223, 71),
    ]),

    // Narvik – Tromsø
    127 => new Route(29, 42, GRAY, [
      new RouteSpace(287, 372, 84),
      new RouteSpace(322, 307, -49),
    ], locomotives: 1, drawingBonus: 1),
    128 => new Route(29, 42, GRAY, [
      new RouteSpace(310, 374, 85),
      new RouteSpace(340, 322, -49),
    ], locomotives: 1, drawingBonus: 1),

    // Norrköping – Stockholm
    129 => new Route(30, 37, YELLOW, [
      new RouteSpace(701, 1294, 84),
    ]),
    130 => new Route(30, 37, PINK, [
      new RouteSpace(725, 1292, 84),
    ]),

    // Norrköping – Visby
    131 => new Route(30, 47, GRAY, [
      new RouteSpace(791, 1358, 19),
    ], locomotives: 1),
    132 => new Route(30, 47, GRAY, [
      new RouteSpace(784, 1380, 19),
    ], locomotives: 1),

    // Oulu – Rovaniemi
    133 => new Route(32, 35, YELLOW, [
      new RouteSpace(751, 543, 67),
      new RouteSpace(728, 491, 67),
    ]),

    // Oulu – Tornio
    134 => new Route(32, 41, RED, [
      new RouteSpace(724, 569, 49),
    ]),

    // Oulu – Vaasa
    135 => new Route(32, 46, PINK, [
      new RouteSpace(750, 659, -81),
      new RouteSpace(740, 720, -81),
      new RouteSpace(729, 775, -80),
    ]),

    // Östersund – Sundsvall
    136 => new Route(33, 38, BLUE, [
      new RouteSpace(459, 952, 6),
      new RouteSpace(515, 957, 5),
    ]),
    137 => new Route(33, 38, GREEN, [
      new RouteSpace(457, 976, 5),
      new RouteSpace(513, 982, 6),
    ]),

    // Östersund – Trondheim
    138 => new Route(33, 43, WHITE, [
      new RouteSpace(341, 963, -20),
    ]),
    139 => new Route(33, 43, RED, [
      new RouteSpace(347, 985, -22),
    ]),

    // Östersund – Unnamed Swedish city (south)
    140 => new Route(33, 49, BLACK, [
      new RouteSpace(415, 892, -80),
      new RouteSpace(425, 836, -80),
      new RouteSpace(435, 776, -80),
    ]),

    // Riga – Tallinn
    141 => new Route(34, 39, BLUE, [
      new RouteSpace(1058, 1314, 81),
      new RouteSpace(1042, 1254, 71),
      new RouteSpace(1019, 1203, 60),
    ]),
    142 => new Route(34, 39, ORANGE, [
      new RouteSpace(1082, 1310, 81),
      new RouteSpace(1065, 1246, 70),
      new RouteSpace(1037, 1185, 60),
    ]),

    // Riga – Visby
    143 => new Route(34, 47, GRAY, [
      new RouteSpace(1021, 1374, -8),
      new RouteSpace(960, 1382, -7),
      new RouteSpace(900, 1389, -7),
    ], locomotives: 1),

    // Rovaniemi – Tornio
    144 => new Route(35, 41, PINK, [
      new RouteSpace(691, 485, -87),
    ]),

    // Rovaniemi – Unnamed Swedish city (north)
    145 => new Route(35, 50, BLUE, [
      new RouteSpace(645, 405, 30),
      new RouteSpace(596, 377, 28),
    ]),

    // Stockholm – Sundsvall
    146 => new Route(37, 38, BLACK, [
      new RouteSpace(654, 1136, 62),
      new RouteSpace(625, 1082, 61),
      new RouteSpace(598, 1032, 63),
    ]),
    147 => new Route(37, 38, WHITE, [
      new RouteSpace(676, 1126, 62),
      new RouteSpace(646, 1071, 62),
      new RouteSpace(616, 1015, 62),
    ]),

    // Stockholm – Tallinn
    148 => new Route(37, 39, GRAY, [
      new RouteSpace(782, 1239, -4),
      new RouteSpace(843, 1229, -14),
      new RouteSpace(902, 1208, -25),
      new RouteSpace(957, 1178, -33),
    ], locomotives: 1),

    // Stockholm – Turku
    149 => new Route(37, 44, GRAY, [
      new RouteSpace(758, 1118, -50),
      new RouteSpace(797, 1071, -50),
    ], locomotives: 1),
    150 => new Route(37, 44, GRAY, [
      new RouteSpace(775, 1133, -50),
      new RouteSpace(815, 1086, -50),
    ], locomotives: 1),

    // Sundsvall – Umeå
    151 => new Route(38, 45, ORANGE, [
      new RouteSpace(564, 928, -86),
      new RouteSpace(574, 863, -76),
      new RouteSpace(598, 795, -66),
    ]),
    152 => new Route(38, 45, RED, [
      new RouteSpace(589, 924, -86),
      new RouteSpace(599, 863, -75),
      new RouteSpace(620, 805, -66),
    ]),

    // Sundsvall – Vaasa
    153 => new Route(38, 46, GRAY, [
      new RouteSpace(639, 962, -14),
      new RouteSpace(692, 930, -47),
      new RouteSpace(715, 872, -90),
    ], locomotives: 1),

    // Tampere – Turku
    154 => new Route(40, 44, YELLOW, [
      new RouteSpace(833, 985, 88),
    ]),
    155 => new Route(40, 44, GREEN, [
      new RouteSpace(857, 985, 89),
    ]),

    // Tampere – Vaasa
    156 => new Route(40, 46, BLACK, [
      new RouteSpace(820, 891, 42),
      new RouteSpace(776, 851, 41),
    ]),
    157 => new Route(40, 46, ORANGE, [
      new RouteSpace(804, 910, 42),
      new RouteSpace(763, 872, 41),
    ]),

    // Tromsø – Unnamed Swedish city (north)
    158 => new Route(42, 50, ORANGE, [
      new RouteSpace(427, 297, 14),
      new RouteSpace(481, 312, 16),
    ]),

    // Umeå – Vaasa
    159 => new Route(45, 46, GRAY, [
      new RouteSpace(683, 787, 48),
    ], locomotives: 1),
    160 => new Route(45, 46, GRAY, [
      new RouteSpace(666, 804, 48),
    ], locomotives: 1),

    // Umeå – Unnamed Swedish city (south)
    161 => new Route(45, 49, YELLOW, [
      new RouteSpace(568, 745, 13),
      new RouteSpace(510, 731, 14),
    ]),
  ];

  // Each ferry locomotive symbol may instead be paid with two matching cards.
  foreach ($routes as $route) {
    if ($route->locomotives > 0) {
      $route->canPayFerriesWithAnySetOfCards = 2;
    }
  }

  return $routes;
}
