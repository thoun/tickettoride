<?php

require_once(__DIR__.'/../../php/objects/route.php');

/**
 * Route on the map. 
 * For double routes, there is 2 instances of Route.
 * For cities (from/to), it's always low id to high id.
 */
function getRoutes() {
  return [
    1 => new Route(21, 34, WHITE, [
      new RouteSpace(780, 313, -10),
      new RouteSpace(845, 300, -10),
      new RouteSpace(909, 288, -10),
    ]),
    2 => new Route(28, 34, BLACK, [
      new RouteSpace(1023, 267, -8),
      new RouteSpace(1089, 259, -8),
      new RouteSpace(1154, 251, -8),
      new RouteSpace(1218, 243, -8),
    ]),
    3 => new Route(1, 34, YELLOW, [
      new RouteSpace(908, 245, 23),
    ]),
    4 => new Route(31, 34, BLUE, [
      new RouteSpace(987, 209, -52),
    ]),
    5 => new Route(31, 34, PINK, [
      new RouteSpace(1006, 225, -52),
    ]),
    6 => new Route(24, 31, WHITE, [
      new RouteSpace(993, 136, 68),
    ]),
    7 => new Route(24, 31, BLACK, [
      new RouteSpace(1017, 124, 68),
    ]),
    8 => new Route(13, 31, YELLOW, [
      new RouteSpace(1097, 161, -13),
      new RouteSpace(1162, 145, -13),
    ]),
    9 => new Route(28, 31, RED, [
      new RouteSpace(1087, 209, 24),
      new RouteSpace(1151, 220, -4),
      new RouteSpace(1216, 216, -3),
    ]),
    10 => new Route(13, 28, GREEN, [
      new RouteSpace(1252, 173, 63),
    ]),
    11 => new Route(13, 24, PINK, [
      new RouteSpace(1037, 77, 1),
      new RouteSpace(1102, 83, 9),
      new RouteSpace(1165, 102, 23),
    ]),
    12 => new Route(24, 34, ORANGE, [
      new RouteSpace(939, 90, 149),
      new RouteSpace(908, 147, 91),
      new RouteSpace(928, 211, 54),
    ]),
    13 => new Route(1, 21, PINK, [
      new RouteSpace(816, 249, 151),
      new RouteSpace(760, 282, 151),
    ]),
    14 => new Route(21, 27, BLUE, [
      new RouteSpace(655, 332, -5),
    ]),
    15 => new Route(22, 34, BLUE, [
      new RouteSpace(1023, 298, 7),
      new RouteSpace(1074, 338, 68),
    ]),
    16 => new Route(22, 28, ORANGE, [
      new RouteSpace(1148, 364, -20),
      new RouteSpace(1207, 335, -34),
      new RouteSpace(1250, 285, -65),
    ]),
    17 => new Route(33, 34, RED, [
      new RouteSpace(952, 334, 89),
    ]),
    18 => new Route(33, 34, GREEN, [
      new RouteSpace(976, 334, 89),
    ]),
    19 => new Route(25, 33, WHITE, [
      new RouteSpace(978, 440, 72),
    ]),
    20 => new Route(25, 33, BLACK, [
      new RouteSpace(1002, 435, 72),
    ]),
    21 => new Route(22, 25, PINK, [
      new RouteSpace(1050, 440, 121),
    ]),
    22 => new Route(18, 25, BLUE, [
      new RouteSpace(941, 483, 18),
    ]),
    23 => new Route(18, 33, ORANGE, [
      new RouteSpace(908, 408, -41),
    ]),
    24 => new Route(18, 33, YELLOW, [
      new RouteSpace(924, 427, -41),
    ]),
    25 => new Route(18, 21, GREEN, [
      new RouteSpace(730, 365, 79),
      new RouteSpace(766, 422, 39),
      new RouteSpace(829, 443, -2),
    ]),
    26 => new Route(4, 18, GRAY, [
      new RouteSpace(624, 516, 5),
      new RouteSpace(690, 514, -9),
      new RouteSpace(756, 500, -15),
      new RouteSpace(818, 478, -23),
    ]),
    27 => new Route(4, 18, GRAY, [
      new RouteSpace(629, 542, 5),
      new RouteSpace(695, 539, -9),
      new RouteSpace(760, 527, -15),
      new RouteSpace(826, 503, -23),
    ]),
    28 => new Route(4, 27, BLACK, [
      new RouteSpace(579, 458, -82),
      new RouteSpace(587, 394, -82),
    ]),
    29 => new Route(4, 20, RED, [
      new RouteSpace(432, 500, 8),
      new RouteSpace(499, 509, 8),
    ]),
    30 => new Route(20, 27, GREEN, [
      new RouteSpace(375, 445, -75),
      new RouteSpace(408, 387, -40),
      new RouteSpace(467, 353, -17),
      new RouteSpace(532, 348, 7),
    ]),
    31 => new Route(20, 32, BLACK, [
      new RouteSpace(316, 508, 164),
      new RouteSpace(277, 556, 93),
    ]),
    32 => new Route(4, 10, ORANGE, [
      new RouteSpace(511, 557, -43),
    ]),
    33 => new Route(4, 10, YELLOW, [
      new RouteSpace(526, 575, -43),
    ]),
    34 => new Route(4, 12, BLUE, [
      new RouteSpace(581, 580, 69),
      new RouteSpace(617, 634, 44),
      new RouteSpace(675, 667, 10),
    ]),
    35 => new Route(10, 15, RED, [
      new RouteSpace(320, 700, -35),
      new RouteSpace(373, 661, -35),
      new RouteSpace(425, 624, -35),
    ]),
    36 => new Route(10, 15, PINK, [
      new RouteSpace(334, 719, -35),
      new RouteSpace(387, 682, -35),
      new RouteSpace(439, 645, -35),
    ]),
    37 => new Route(12, 18, PINK, [
      new RouteSpace(780, 670, -7),
      new RouteSpace(839, 641, -44),
      new RouteSpace(876, 585, -69),
      new RouteSpace(885, 520, -93),
    ]),
    38 => new Route(15, 20, GRAY, [
      new RouteSpace(291, 673, -57),
      new RouteSpace(336, 624, -39),
      new RouteSpace(393, 588, -27),
      new RouteSpace(390, 541, -117),
    ]),
    39 => new Route(11, 32, GRAY, [
      new RouteSpace(43, 864, -95),
      new RouteSpace(41, 800, -88),
      new RouteSpace(52, 736, -64),
      new RouteSpace(89, 679, -49),
      new RouteSpace(143, 639, -25),
      new RouteSpace(208, 620, -10),
    ]),
    40 => new Route(11, 15, BLUE, [
      new RouteSpace(75, 872, -77),
      new RouteSpace(95, 807, -67),
      new RouteSpace(159, 750, -17),
      new RouteSpace(224, 735, -8),
    ]),
    41 => new Route(11, 15, WHITE, [
      new RouteSpace(97, 883, -77),
      new RouteSpace(118, 820, -67),
      new RouteSpace(166, 774, -17),
      new RouteSpace(230, 759, -8),
    ]),
    42 => new Route(11, 1002, YELLOW, [
      new RouteSpace(53, 981, 100),
    ]),
    43 => new Route(24, 2002, YELLOW, [
      new RouteSpace(1027, 40, -29),
    ]),
    44 => new Route(13, 2003, ORANGE, [
      new RouteSpace(1194, 68, 61),
    ]),
    45 => new Route(13, 2004, WHITE, [
      new RouteSpace(1261, 78, -42),
    ]),
    46 => new Route(28, 2005, GRAY, [
      new RouteSpace(1324, 194, -32),
      new RouteSpace(1378, 160, -31),
    ]),
    47 => new Route(14, 32, YELLOW, [
      new RouteSpace(221, 565, -134),
      new RouteSpace(205, 505, -78),
      new RouteSpace(241, 449, -38),
    ], true),
    48 => new Route(9, 14, WHITE, [
      new RouteSpace(320, 375, -50),
      new RouteSpace(372, 330, -30),
      new RouteSpace(433, 304, -20),
    ], true),
    49 => new Route(9, 27, PINK, [
      new RouteSpace(540, 315, 20),
    ], true),
    50 => new Route(2, 9, YELLOW, [
      new RouteSpace(532, 265, -28),
      new RouteSpace(585, 221, -51),
    ], true),
    51 => new Route(2, 21, ORANGE, [
      new RouteSpace(641, 227, 65),
      new RouteSpace(681, 283, 43),
    ], true),
    52 => new Route(1, 2, RED, [
      new RouteSpace(676, 164, -9),
      new RouteSpace(742, 166, 15),
      new RouteSpace(806, 189, 26),
    ], true),
    53 => new Route(15, 19, ORANGE, [
      new RouteSpace(326, 769, 25),
      new RouteSpace(378, 810, 53),
      new RouteSpace(403, 869, 86),
      new RouteSpace(401, 938, 98),
    ], true),
    54 => new Route(19, 26, GREEN, [
      new RouteSpace(449, 970, -32),
      new RouteSpace(501, 928, -42),
    ], true),
    55 => new Route(5, 26, BLACK, [
      new RouteSpace(588, 902, 6),
      new RouteSpace(656, 902, -6),
      new RouteSpace(721, 881, -28),
    ], true),
    56 => new Route(5, 12, WHITE, [
      new RouteSpace(724, 732, 90),
      new RouteSpace(738, 797, 67),
    ], true),
    57 => new Route(5, 30, RED, [
      new RouteSpace(802, 798, -34),
      new RouteSpace(857, 762, -34),
      new RouteSpace(912, 727, -34),
      new RouteSpace(968, 690, -34),
    ], true),
    58 => new Route(5, 16, GRAY, [
      new RouteSpace(881, 807, -21),
      new RouteSpace(820, 830, -21),
      new RouteSpace(945, 783, -22),
      new RouteSpace(992, 786, 65),
      new RouteSpace(1019, 845, 65),
      new RouteSpace(1047, 906, 65),
    ], true),
    59 => new Route(16, 17, PINK, [
      new RouteSpace(1110, 992, 43),
    ], true),
    60 => new Route(3, 16, BLACK, [
      new RouteSpace(1126, 942, -14),
    ], true),
    61 => new Route(3, 17, RED, [
      new RouteSpace(1165, 979, -78),
    ], true),
    62 => new Route(3, 17, YELLOW, [
      new RouteSpace(1193, 984, -78),
    ], true),
    63 => new Route(3, 30, GRAY, [
      new RouteSpace(1047, 710, 58),
      new RouteSpace(1085, 766, 58),
      new RouteSpace(1122, 820, 58),
      new RouteSpace(1157, 874, 58),
    ], true),
    64 => new Route(3, 30, GRAY, [
      new RouteSpace(1026, 725, 58),
      new RouteSpace(1062, 780, 58),
      new RouteSpace(1097, 834, 58),
      new RouteSpace(1134, 889, 57),
    ], true),
    65 => new Route(25, 30, YELLOW, [
      new RouteSpace(1014, 545, 93),
      new RouteSpace(1016, 611, 85),
    ], true),
    66 => new Route(25, 30, GREEN, [
      new RouteSpace(988, 545, 93),
      new RouteSpace(989, 610, 85),
    ], true),
    67 => new Route(7, 30, GRAY, [
      new RouteSpace(1083, 682, 28),
      new RouteSpace(1144, 708, 17),
      new RouteSpace(1209, 707, -15),
      new RouteSpace(1266, 675, -43),
      new RouteSpace(1310, 627, -51),
    ], true),
    68 => new Route(6, 7, GRAY, [
      new RouteSpace(1369, 631, 55),
      new RouteSpace(1409, 686, 55),
      new RouteSpace(1444, 737, 55),
      new RouteSpace(1484, 791, 55),
      new RouteSpace(1522, 844, 55),
    ], true),
    69 => new Route(7, 23, WHITE, [
      new RouteSpace(1319, 528, -117),
    ], true),
    70 => new Route(22, 23, YELLOW, [
      new RouteSpace(1116, 428, 56),
      new RouteSpace(1166, 468, 24),
      new RouteSpace(1229, 480, -5),
    ], true),
    71 => new Route(23, 29, ORANGE, [
      new RouteSpace(1326, 435, -54),
    ], true),
    72 => new Route(28, 29, BLUE, [
      new RouteSpace(1297, 285, 63),
      new RouteSpace(1326, 344, 63),
    ], true),
    73 => new Route(8, 23, BLACK, [
      new RouteSpace(1355, 482, 5),
      new RouteSpace(1416, 500, 27),
      new RouteSpace(1471, 535, 37),
    ], true),
    74 => new Route(7, 8, PINK, [
      new RouteSpace(1401, 581, 0),
      new RouteSpace(1464, 581, 1),
    ], true),
    75 => new Route(6, 8, BLUE, [
      new RouteSpace(1529, 636, 79),
      new RouteSpace(1540, 700, 80),
      new RouteSpace(1552, 765, 80),
      new RouteSpace(1563, 828, 82),
    ], true),
    76 => new Route(14, 1003, GREEN, [
      new RouteSpace(172, 361, 25),
      new RouteSpace(231, 391, 27),
    ], true),
    77 => new Route(9, 1004, BLACK, [
      new RouteSpace(499, 167, 97),
      new RouteSpace(492, 232, 98),
    ], true),
    78 => new Route(2, 2001, BLUE, [
      new RouteSpace(624, 122, 99),
    ], true),
    79 => new Route(28, 3001, GRAY, [
      new RouteSpace(1335, 253, -161),
      new RouteSpace(1396, 275, -161),
      new RouteSpace(1456, 296, -161),
      new RouteSpace(1518, 318, -161),
    ], true),
    80 => new Route(29, 3002, RED, [
      new RouteSpace(1419, 383, -9),
    ], true),
    81 => new Route(8, 3003, GRAY, [
      new RouteSpace(1571, 560, -20),
      new RouteSpace(1632, 539, -18),
      new RouteSpace(1695, 517, -19),
    ], true),
    82 => new Route(8, 4001, GRAY, [
      new RouteSpace(1572, 602, -171),
      new RouteSpace(1637, 612, -171),
      new RouteSpace(1701, 622, -171),
    ], true),
    83 => new Route(6, 4002, GREEN, [
      new RouteSpace(1596, 930, -120),
      new RouteSpace(1628, 987, -121),
    ], true),
    84 => new Route(17, 4003, WHITE, [
      new RouteSpace(1213, 1094, -191),
      new RouteSpace(1164, 1083, -101),
    ], true),
    85 => new Route(16, 4004, ORANGE, [
      new RouteSpace(1056, 1006, -76),
      new RouteSpace(1039, 1069, -76),
    ], true),
    86 => new Route(5, 4005, GREEN, [
      new RouteSpace(877, 1004, -125),
      new RouteSpace(840, 948, -124),
      new RouteSpace(805, 896, -124),
    ], true),
    87 => new Route(19, 1001, GRAY, [
      new RouteSpace(317, 1081, -49),
      new RouteSpace(360, 1032, -48),
    ], true),
    88 => new Route(14, 20, ORANGE, [
      new RouteSpace(323, 457, 35),
    ], true),
    
  ];
}

/*
console.log(``.replace(/RouteSpace\((\d+), (\d+)/g, (_, x, y) => `RouteSpace(${Number(x) + 37}, ${Number(y) + 18}`))
*/
