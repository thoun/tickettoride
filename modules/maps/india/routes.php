<?php

require_once(__DIR__.'/../../php/objects/route.php');

/**
 * Route on the map. 
 * For double routes, there is 2 instances of Route.
 * For cities (from/to), it's always low id to high id.
 */
function getRoutes() {
  return [
    1 => new Route(1, 7, PINK, [
      new RouteSpace(479, 698, -87)
    ]),
    2 => new Route(1, 7, RED, [
      new RouteSpace(460, 697, -87)
    ]),
    3 => new Route(1, 8, WHITE, [
      new RouteSpace(530, 675, 2)
    ]),
    4 => new Route(1, 13, ORANGE, [
      new RouteSpace(467, 601, 87),
      new RouteSpace(464, 573, 84),
      new RouteSpace(461, 543, 86),
      new RouteSpace(458, 516, -87)
    ]),
    5 => new Route(1, 19, BLUE, [
      new RouteSpace(420, 619, 15)
    ]),
    6 => new Route(1, 19, WHITE, [
      new RouteSpace(415, 637, 15)
    ]),
    7 => new Route(1, 23, GREEN, [
      new RouteSpace(507, 648, -1),
      new RouteSpace(537, 648, 4),
      new RouteSpace(581, 648, 1)
    ]),
    8 => new Route(1, 36, WHITE, [
      new RouteSpace(397, 693, 3)
    ]),
    9 => new Route(2, 9, WHITE, [
      new RouteSpace(232, 816, 73),
      new RouteSpace(234, 879, -77),
      new RouteSpace(232, 941, 82)
    ]),
    10 => new Route(2, 21, PINK, [
      new RouteSpace(211, 707, -85),
      new RouteSpace(216, 647, -85),
      new RouteSpace(221, 589, -85)
    ]),
    11 => new Route(2, 21, BLACK, [
      new RouteSpace(231, 707, -85),
      new RouteSpace(236, 647, -85),
      new RouteSpace(241, 589, -85)
    ]),
    12 => new Route(2, 24, YELLOW, [
      new RouteSpace(265, 776, 15),
      new RouteSpace(317, 811, 49),
    ]),
    13 => new Route(2, 36, GREEN, [
      new RouteSpace(265, 738, -30),
    ]),
    14 => new Route(3, 4, ORANGE, [
      new RouteSpace(519, 361, 69),
      new RouteSpace(539, 381, 69),
    ]),
    15 => new Route(3, 6, WHITE, [
      new RouteSpace(409, 292, -1),
      new RouteSpace(350, 293, -1)
    ]),
    16 => new Route(3, 6, YELLOW, [
      new RouteSpace(425, 310, 1),
      new RouteSpace(394, 311, 0),
    ]),
    17 => new Route(3, 13, GRAY, [
      new RouteSpace(454, 356, -84),
      new RouteSpace(448, 415, -84)
    ]),
    18 => new Route(3, 13, GRAY, [
      new RouteSpace(469, 356, -84),
      new RouteSpace(469, 415, -84)
    ]),
    19 => new Route(3, 25, BLACK, [
      new RouteSpace(424, 268, 35),
      new RouteSpace(378, 236, 35),
      new RouteSpace(332, 203, 35),
      new RouteSpace(286, 171, 35)
    ]),
    // TODO visually check routes from here
    20 => new Route(4, 13, WHITE, [
      new RouteSpace(509, 461, -13)
    ]),
    21 => new Route(4, 26, GRAY, [
      new RouteSpace(589, 500, 65)
    ]),
    22 => new Route(4, 31, GREEN, [
      new RouteSpace(677, 491, 30),
      new RouteSpace(717, 518, 38),
      new RouteSpace(739, 537, 40)
    ]),
    23 => new Route(5, 16, BLUE, [
      new RouteSpace(537, 1126, -23),
      new RouteSpace(482, 1150, -23)
    ]),
    24 => new Route(5, 16, PINK, [
      new RouteSpace(467, 1135, -14)
    ]),
    25 => new Route(5, 17, GREEN, [
      new RouteSpace(572, 1049, 86),
      new RouteSpace(566, 990, 85)
    ]),
    26 => new Route(5, 17, YELLOW, [
      new RouteSpace(590, 1046, 84),
      new RouteSpace(585, 987, 84)
    ]),
    27 => new Route(5, 27, RED, [
      new RouteSpace(591, 1158, 84)
    ]),
    28 => new Route(5, 39, ORANGE, [
      new RouteSpace(714, 1057, -21)
    ]),
    29 => new Route(6, 13, BLUE, [
      new RouteSpace(323, 353, 60),
      new RouteSpace(361, 402, 44),
      new RouteSpace(408, 442, 38)
    ]),
    30 => new Route(6, 21, GREEN, [
      new RouteSpace(279, 362, -76),
      new RouteSpace(269, 403, -75),
      new RouteSpace(262, 433, -79),
      new RouteSpace(255, 463, -83),
      new RouteSpace(248, 490, -84)
    ]),
    31 => new Route(6, 25, GRAY, [
      new RouteSpace(265, 253, 72),
      new RouteSpace(247, 197, 71)
    ]),
    32 => new Route(6, 37, PINK, [
      new RouteSpace(263, 334, -51),
      new RouteSpace(243, 357, -51),
      new RouteSpace(225, 379, -52),
      new RouteSpace(206, 402, -53)
    ]),
    33 => new Route(7, 8, GRAY, [
      new RouteSpace(523, 738, 0),
      new RouteSpace(554, 747, -15),
      new RouteSpace(583, 756, 0)
    ]),
    34 => new Route(7, 8, PINK, [
      new RouteSpace(524, 773, 0)
    ]),
    35 => new Route(7, 24, BLUE, [
      new RouteSpace(399, 834, -17)
    ]),
    36 => new Route(8, 23, PINK, [
      new RouteSpace(654, 698, -85)
    ]),
    37 => new Route(8, 24, ORANGE, [
      new RouteSpace(531, 807, -10),
      new RouteSpace(503, 812, -13)
    ]),
    38 => new Route(8, 35, RED, [
      new RouteSpace(639, 803, -80)
    ]),
    39 => new Route(9, 29, PINK, [
      new RouteSpace(304, 999, -5)
    ]),
    40 => new Route(9, 30, GREEN, [
      new RouteSpace(245, 1054, 81)
    ]),
    41 => new Route(9, 33, BLUE, [
      new RouteSpace(293, 1026, 31)
    ]),
    42 => new Route(9, 33, PINK, [
      new RouteSpace(281, 1063, 5)
    ]),
    43 => new Route(9, 33, WHITE, [
      new RouteSpace(290, 1051, 5)
    ]),
    44 => new Route(9, 38, PINK, [
      new RouteSpace(320, 1021, -7)
    ]),
    45 => new Route(10, 14, GRAY, [
      new RouteSpace(948, 732, -71),
      new RouteSpace(930, 724, -70),
      new RouteSpace(951, 669, -70),
      new RouteSpace(969, 674, -72)
    ]),
    46 => new Route(10, 31, PINK, [
      new RouteSpace(884, 721, 53),
      new RouteSpace(847, 670, 53),
      new RouteSpace(854, 646, 27)
    ]),
    47 => new Route(10, 31, YELLOW, [
      new RouteSpace(869, 745, 34)
    ]),
    48 => new Route(10, 35, BLUE, [
      new RouteSpace(798, 809, -15),
      new RouteSpace(738, 824, -14)
    ]),
    49 => new Route(10, 39, RED, [
      new RouteSpace(879, 879, -64),
      new RouteSpace(866, 905, -64),
      new RouteSpace(848, 933, -51),
      new RouteSpace(830, 956, -49)
    ]),
    50 => new Route(11, 15, BLUE, [
      new RouteSpace(406, 1386, 5),
      new RouteSpace(466, 1391, 5),
      new RouteSpace(525, 1397, 4)
    ]),
    51 => new Route(11, 15, RED, [
      new RouteSpace(408, 1368, 5),
      new RouteSpace(467, 1373, 5),
      new RouteSpace(527, 1378, 5)
    ]),
    52 => new Route(11, 16, PINK, [
      new RouteSpace(360, 1299, -67)
    ]),
    53 => new Route(11, 27, PINK, [
      new RouteSpace(437, 1291, 0),
      new RouteSpace(498, 1279, -24)
    ]),
    54 => new Route(11, 28, WHITE, [
      new RouteSpace(345, 1318, 77)
    ]),
    55 => new Route(11, 28, YELLOW, [
      new RouteSpace(327, 1323, 76)
    ]),
    56 => new Route(11, 34, GRAY, [
      new RouteSpace(356, 1433, 83),
      new RouteSpace(367, 1492, 48)
    ]),
    57 => new Route(12, 14, PINK, [
      new RouteSpace(1000, 674, 57)
    ]),
    58 => new Route(12, 14, WHITE, [
      new RouteSpace(1052, 704, 57),
      new RouteSpace(1012, 657, 44)
    ]),
    59 => new Route(12, 20, GRAY, [
      new RouteSpace(1094, 694, -80),
      new RouteSpace(1101, 633, 89),
      new RouteSpace(1099, 573, 87)
    ]),
    60 => new Route(13, 19, PINK, [
      new RouteSpace(420, 499, -38),
      new RouteSpace(397, 517, -40),
      new RouteSpace(374, 560, -76)
    ]),
    61 => new Route(13, 26, YELLOW, [
      new RouteSpace(506, 500, 25),
      new RouteSpace(559, 525, 25)
    ]),
    62 => new Route(14, 20, PINK, [
      new RouteSpace(1072, 574, -37)
    ]),
    63 => new Route(14, 31, BLUE, [
      new RouteSpace(916, 607, 1),
      new RouteSpace(857, 606, 1)
    ]),
    64 => new Route(14, 31, RED, [
      new RouteSpace(916, 626, 1)
    ]),
    65 => new Route(15, 27, GREEN, [
      new RouteSpace(598, 1332, -86),
      new RouteSpace(602, 1273, -85)
    ]),
    66 => new Route(15, 27, ORANGE, [
      new RouteSpace(566, 1396, 86),
      new RouteSpace(580, 1345, -87),
      new RouteSpace(581, 1318, -77)
    ]),
    67 => new Route(16, 27, PINK, [
      new RouteSpace(483, 1203, 13)
    ]),
    68 => new Route(16, 27, WHITE, [
      new RouteSpace(483, 1188, 14),
      new RouteSpace(543, 1201, 11)
    ]),
    69 => new Route(16, 30, PINK, [
      new RouteSpace(355, 1126, 27)
    ]),
    70 => new Route(16, 30, RED, [
      new RouteSpace(370, 1154, 18),
      new RouteSpace(311, 1135, 17)
    ]),
    71 => new Route(16, 38, PINK, [
      new RouteSpace(446, 1118, -81)
    ]),
    72 => new Route(17, 29, PINK, [
      new RouteSpace(515, 945, -15),
      new RouteSpace(456, 958, -8),
      new RouteSpace(393, 959, 6)
    ]),
    73 => new Route(17, 29, WHITE, [
      new RouteSpace(420, 930, 1)
    ]),
    74 => new Route(17, 39, WHITE, [
      new RouteSpace(617, 938, 1),
      new RouteSpace(658, 1012, 3)
    ]),
    75 => new Route(18, 25, RED, [
      new RouteSpace(152, 293, -62),
      new RouteSpace(173, 254, -61),
      new RouteSpace(187, 229, -62),
      new RouteSpace(209, 188, -62)
    ]),
    76 => new Route(18, 32, ORANGE, [
      new RouteSpace(98, 302, 77),
      new RouteSpace(92, 273, 77),
      new RouteSpace(99, 151, -66)
    ]),
    77 => new Route(18, 32, YELLOW, [
      new RouteSpace(116, 301, 75),
      new RouteSpace(110, 271, 76),
      new RouteSpace(106, 239, 84),
      new RouteSpace(108, 209, 90),
      new RouteSpace(111, 178, -84),
      new RouteSpace(119, 148, -78)
    ]),
    78 => new Route(18, 37, BLUE, [
      new RouteSpace(157, 383, 59)
    ]),
    79 => new Route(18, 37, GREEN, [
      new RouteSpace(134, 381, 59)
    ]),
    80 => new Route(19, 21, WHITE, [
      new RouteSpace(297, 615, 1)
    ]),
    81 => new Route(19, 36, YELLOW, [
      new RouteSpace(335, 645, -66),
      new RouteSpace(321, 672, -64)
    ]),
    82 => new Route(21, 22, ORANGE, [
      new RouteSpace(150, 583, -18),
      new RouteSpace(76, 603, -8)
    ]),
    83 => new Route(23, 26, BLUE, [
      new RouteSpace(623, 602, 71)
    ]),
    84 => new Route(23, 31, GRAY, [
      new RouteSpace(696, 648, -10),
      new RouteSpace(693, 630, -9),
      new RouteSpace(751, 620, -10),
      new RouteSpace(754, 638, -10)
    ]),
    85 => new Route(23, 31, PINK, [
      new RouteSpace(684, 612, 1)
    ]),
    86 => new Route(24, 29, RED, [
      new RouteSpace(346, 905, -85)
    ]),
    87 => new Route(25, 32, GREEN, [
      new RouteSpace(181, 133, 14)
    ]),
    88 => new Route(25, 32, PINK, [
      new RouteSpace(185, 114, 14)
    ]),
    89 => new Route(26, 31, WHITE, [
      new RouteSpace(680, 551, 2)
    ]),
    90 => new Route(27, 39, YELLOW, [
      new RouteSpace(637, 1188, -38),
      new RouteSpace(661, 1171, -35),
      new RouteSpace(684, 1148, -53),
      new RouteSpace(703, 1124, -55),
      new RouteSpace(719, 1100, -61),
      new RouteSpace(735, 1074, -57)
    ]),
    91 => new Route(28, 38, PINK, [
      new RouteSpace(402, 1140, -43)
    ]),
    92 => new Route(29, 33, GRAY, [
      new RouteSpace(341, 1005, -89)
    ]),
    93 => new Route(30, 38, GRAY, [
      new RouteSpace(320, 1102, -13),
      new RouteSpace(383, 1090, -12)
    ]),
    94 => new Route(33, 38, ORANGE, [
      new RouteSpace(380, 1054, -1),
      new RouteSpace(406, 1056, 7)
    ]),
    95 => new Route(35, 39, GREEN, [
      new RouteSpace(651, 876, 53),
      new RouteSpace(669, 899, 53),
      new RouteSpace(686, 922, 49),
      new RouteSpace(704, 947, 55),
      new RouteSpace(722, 970, 55),
      new RouteSpace(739, 994, 54)
    ]),
    96 => new Route(35, 39, WHITE, [
      new RouteSpace(644, 898, 53),
      new RouteSpace(680, 946, 53),
      new RouteSpace(716, 993, 53)
    ])
  ];
}

/*
console.log(``.replace(/RouteSpace\((\d+), (\d+)/g, (_, x, y) => `RouteSpace(${Number(x) + 37}, ${Number(y) + 18}`))
*/
