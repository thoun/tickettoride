<?php

use Bga\Games\TicketToRide\Objects\Route;
use Bga\Games\TicketToRide\Objects\RouteSpace;

/**
 * Route on the map.
 * For double routes, there is 2 instances of Route.
 * For cities (from/to), it's always low id to high id.
 */
function getRoutes() {
  return [
    1 => new Route(1, 8, YELLOW, [
      new RouteSpace(215, 1859, 25),
      new RouteSpace(267, 1883, 25),
      new RouteSpace(320, 1907, 25),
    ]),
    2 => new Route(1, 19, ORANGE, [
      new RouteSpace(176, 1727, 106),
      new RouteSpace(161, 1784, 106),
    ]),
    3 => new Route(1, 19, PINK, [
      new RouteSpace(197, 1733, 106),
      new RouteSpace(182, 1790, 106),
    ]),
    4 => new Route(1, 28, BLACK, [
      new RouteSpace(211, 1883, 38),
      new RouteSpace(256, 1919, 38),
      new RouteSpace(302, 1955, 38),
    ]),
    5 => new Route(1, 28, GREEN, [
      new RouteSpace(197, 1900, 38),
      new RouteSpace(243, 1936, 38),
      new RouteSpace(289, 1972, 38),
    ]),
    6 => new Route(2, 21, YELLOW, [
      new RouteSpace(647, 849, 4),
      new RouteSpace(706, 852, 4),
    ]),
    7 => new Route(2, 22, RED, [
      new RouteSpace(747, 907, 96),
      new RouteSpace(741, 965, 96),
      new RouteSpace(735, 1024, 96),
    ]),
    8 => new Route(2, 22, WHITE, [
      new RouteSpace(768, 909, 96),
      new RouteSpace(762, 967, 96),
      new RouteSpace(756, 1026, 96),
    ]),
    9 => new Route(2, 24, ORANGE, [
      new RouteSpace(703, 683, 87),
      new RouteSpace(709, 742, 82),
      new RouteSpace(723, 800, 72),
    ]),
    10 => new Route(2, 24, GREEN, [
      new RouteSpace(724, 682, 87),
      new RouteSpace(730, 739, 82),
      new RouteSpace(743, 793, 72),
    ]),
    11 => new Route(2, 32, GRAY, [
      new RouteSpace(813, 801, -48),
      new RouteSpace(854, 757, -47),
      new RouteSpace(893, 711, -51),
      new RouteSpace(928, 662, -61),
      new RouteSpace(956, 609, -65),
    ], ferryWaves: 3),
    12 => new Route(2, 33, GRAY, [
      new RouteSpace(804, 551, 88),
      new RouteSpace(804, 611, 91),
      new RouteSpace(800, 671, 95),
      new RouteSpace(792, 730, 100),
      new RouteSpace(780, 789, 104),
    ], ferryWaves: 3),
    13 => new Route(2, 5002, GRAY, [
      new RouteSpace(816, 860, 10),
      new RouteSpace(875, 871, 7),
      new RouteSpace(935, 877, 3),
      new RouteSpace(995, 878, -2),
    ], ferryWaves: 2),
    14 => new Route(3, 11, ORANGE, [
      new RouteSpace(832, 1341, 51),
      new RouteSpace(869, 1387, 51),
      new RouteSpace(906, 1433, 51),
    ]),
    15 => new Route(3, 11, RED, [
      new RouteSpace(816, 1355, 51),
      new RouteSpace(853, 1401, 51),
      new RouteSpace(889, 1447, 51),
    ]),
    16 => new Route(3, 14, WHITE, [
      new RouteSpace(978, 1509, 55),
      new RouteSpace(1009, 1559, 63),
      new RouteSpace(1036, 1612, 69),
    ]),
    17 => new Route(3, 14, GREEN, [
      new RouteSpace(960, 1521, 55),
      new RouteSpace(990, 1569, 63),
      new RouteSpace(1016, 1620, 69),
    ]),
    18 => new Route(3, 29, PINK, [
      new RouteSpace(897, 1527, 91),
    ]),
    19 => new Route(3, 29, YELLOW, [
      new RouteSpace(919, 1527, 91),
    ]),
    20 => new Route(3, 5003, GRAY, [
      new RouteSpace(1022, 1057, -69),
      new RouteSpace(1003, 1113, -73),
      new RouteSpace(986, 1170, -74),
      new RouteSpace(970, 1228, -77),
      new RouteSpace(957, 1287, -80),
      new RouteSpace(947, 1346, -85),
      new RouteSpace(942, 1406, -85),
    ], ferryWaves: 4),
    21 => new Route(4, 6, RED, [
      new RouteSpace(629, 254, -4),
      new RouteSpace(688, 249, -4),
      new RouteSpace(745, 245, -4),
    ]),
    22 => new Route(4, 16, ORANGE, [
      new RouteSpace(511, 244, 2),
    ]),
    23 => new Route(4, 16, GREEN, [
      new RouteSpace(511, 265, 2),
    ]),
    24 => new Route(4, 20, PINK, [
      new RouteSpace(536, 319, 98),
      new RouteSpace(529, 376, 98),
    ]),
    25 => new Route(4, 34, BLUE, [
      new RouteSpace(598, 298, 52),
      new RouteSpace(632, 344, 52),
    ]),
    26 => new Route(4, 34, BLACK, [
      new RouteSpace(581, 311, 52),
      new RouteSpace(615, 357, 52),
    ]),
    27 => new Route(4, 3002, WHITE, [
      new RouteSpace(598, 218, -59),
      new RouteSpace(628, 169, -59),
    ]),
    28 => new Route(5, 10, BLACK, [
      new RouteSpace(565, 613, -48),
    ]),
    29 => new Route(5, 20, RED, [
      new RouteSpace(551, 469, 58),
      new RouteSpace(582, 519, 58),
    ]),
    30 => new Route(5, 20, GREEN, [
      new RouteSpace(533, 480, 58),
      new RouteSpace(564, 530, 58),
    ]),
    31 => new Route(5, 24, BLUE, [
      new RouteSpace(655, 584, 35),
    ]),
    32 => new Route(5, 24, WHITE, [
      new RouteSpace(643, 602, 35),
    ]),
    33 => new Route(5, 34, ORANGE, [
      new RouteSpace(635, 451, 99),
      new RouteSpace(626, 508, 99),
    ]),
    34 => new Route(5, 34, YELLOW, [
      new RouteSpace(656, 454, 99),
      new RouteSpace(647, 512, 99),
    ]),
    35 => new Route(6, 30, PINK, [
      new RouteSpace(847, 276, 36),
      new RouteSpace(895, 310, 36),
      new RouteSpace(943, 344, 36),
      new RouteSpace(990, 378, 36),
    ]),
    36 => new Route(6, 33, YELLOW, [
      new RouteSpace(813, 302, 93),
      new RouteSpace(809, 360, 93),
      new RouteSpace(806, 418, 93),
    ]),
    37 => new Route(6, 34, GREEN, [
      new RouteSpace(777, 271, -40),
      new RouteSpace(732, 310, -40),
      new RouteSpace(687, 349, -40),
    ]),
    38 => new Route(6, 3002, BLUE, [
      new RouteSpace(765, 184, 49),
    ]),
    39 => new Route(6, 4001, BLACK, [
      new RouteSpace(865, 242, 9),
      new RouteSpace(923, 251, 9),
    ]),
    40 => new Route(7, 17, GRAY, [
      new RouteSpace(247, 1228, 25),
      new RouteSpace(301, 1251, 22),
      new RouteSpace(356, 1271, 18),
      new RouteSpace(412, 1288, 16),
      new RouteSpace(468, 1304, 13),
      new RouteSpace(526, 1316, 10),
    ], ferryWaves: 3),
    41 => new Route(7, 18, BLUE, [
      new RouteSpace(220, 957, 95),
      new RouteSpace(215, 1015, 95),
      new RouteSpace(210, 1073, 95),
      new RouteSpace(205, 1131, 95),
    ]),
    42 => new Route(7, 18, PINK, [
      new RouteSpace(242, 959, 95),
      new RouteSpace(237, 1017, 95),
      new RouteSpace(232, 1075, 95),
      new RouteSpace(227, 1133, 95),
    ]),
    43 => new Route(7, 19, GRAY, [
      new RouteSpace(186, 1261, 95),
      new RouteSpace(181, 1320, 92),
      new RouteSpace(180, 1379, 90),
      new RouteSpace(180, 1439, 88),
      new RouteSpace(183, 1498, 86),
      new RouteSpace(189, 1557, 83),
      new RouteSpace(197, 1616, 82),
    ], ferryWaves: 4),
    44 => new Route(7, 19, GRAY, [
      new RouteSpace(208, 1263, 95),
      new RouteSpace(203, 1321, 92),
      new RouteSpace(202, 1379, 90),
      new RouteSpace(202, 1438, 88),
      new RouteSpace(205, 1497, 86),
      new RouteSpace(211, 1554, 83),
      new RouteSpace(219, 1613, 82),
    ], ferryWaves: 4),
    45 => new Route(7, 25, GRAY, [
      new RouteSpace(270, 1173, -25),
      new RouteSpace(323, 1146, -29),
      new RouteSpace(374, 1116, -32),
      new RouteSpace(423, 1080, -38),
    ], ferryWaves: 2),
    46 => new Route(7, 27, BLACK, [
      new RouteSpace(88, 987, 68),
      new RouteSpace(110, 1041, 68),
      new RouteSpace(131, 1096, 68),
      new RouteSpace(153, 1150, 68),
    ]),
    47 => new Route(7, 27, YELLOW, [
      new RouteSpace(108, 979, 68),
      new RouteSpace(129, 1033, 68),
      new RouteSpace(151, 1088, 68),
      new RouteSpace(173, 1142, 68),
    ]),
    48 => new Route(8, 15, PINK, [
      new RouteSpace(455, 1864, -27),
      new RouteSpace(402, 1891, -27),
    ]),
    49 => new Route(8, 19, WHITE, [
      new RouteSpace(247, 1736, 57),
      new RouteSpace(279, 1785, 57),
      new RouteSpace(311, 1835, 57),
      new RouteSpace(343, 1884, 57),
    ]),
    50 => new Route(8, 28, ORANGE, [
      new RouteSpace(354, 1955, 97),
    ]),
    51 => new Route(9, 15, GRAY, [
      new RouteSpace(628, 1735, -32),
      new RouteSpace(579, 1771, -39),
      new RouteSpace(535, 1811, -44),
    ], ferryWaves: 1),
    52 => new Route(9, 15, GRAY, [
      new RouteSpace(640, 1754, -32),
      new RouteSpace(592, 1788, -39),
      new RouteSpace(550, 1827, -44),
    ], ferryWaves: 1),
    53 => new Route(9, 26, PINK, [
      new RouteSpace(634, 1478, 85),
      new RouteSpace(639, 1536, 85),
      new RouteSpace(645, 1595, 85),
      new RouteSpace(651, 1654, 85),
    ]),
    54 => new Route(9, 26, GREEN, [
      new RouteSpace(655, 1476, 85),
      new RouteSpace(661, 1534, 85),
      new RouteSpace(666, 1593, 85),
      new RouteSpace(672, 1652, 85),
    ]),
    55 => new Route(9, 29, ORANGE, [
      new RouteSpace(710, 1684, -27),
      new RouteSpace(763, 1657, -27),
      new RouteSpace(815, 1631, -27),
      new RouteSpace(867, 1604, -27),
    ]),
    56 => new Route(9, 29, BLACK, [
      new RouteSpace(720, 1704, -27),
      new RouteSpace(772, 1677, -27),
      new RouteSpace(824, 1651, -27),
      new RouteSpace(877, 1624, -27),
    ]),
    57 => new Route(10, 13, BLUE, [
      new RouteSpace(488, 712, -56),
      new RouteSpace(455, 761, -56),
    ]),
    58 => new Route(10, 20, ORANGE, [
      new RouteSpace(504, 489, 86),
      new RouteSpace(508, 547, 86),
      new RouteSpace(513, 605, 86),
    ]),
    59 => new Route(10, 21, WHITE, [
      new RouteSpace(543, 726, 72),
      new RouteSpace(562, 782, 72),
    ]),
    60 => new Route(10, 23, PINK, [
      new RouteSpace(476, 623, 39),
    ]),
    61 => new Route(10, 23, RED, [
      new RouteSpace(463, 640, 39),
    ]),
    62 => new Route(10, 24, YELLOW, [
      new RouteSpace(577, 655, -9),
      new RouteSpace(635, 646, -9),
    ]),
    63 => new Route(11, 17, GREEN, [
      new RouteSpace(630, 1315, -2),
      new RouteSpace(689, 1313, -2),
      new RouteSpace(748, 1311, -2),
    ]),
    64 => new Route(11, 22, YELLOW, [
      new RouteSpace(747, 1149, 78),
      new RouteSpace(759, 1206, 78),
      new RouteSpace(771, 1264, 78),
    ]),
    65 => new Route(11, 22, BLUE, [
      new RouteSpace(768, 1144, 78),
      new RouteSpace(780, 1202, 78),
      new RouteSpace(792, 1259, 78),
    ]),
    66 => new Route(11, 26, BLACK, [
      new RouteSpace(745, 1353, -25),
      new RouteSpace(692, 1378, -25),
    ]),
    67 => new Route(12, 16, RED, [
      new RouteSpace(378, 336, -50),
      new RouteSpace(415, 291, -50),
    ]),
    68 => new Route(12, 16, PINK, [
      new RouteSpace(395, 349, -50),
      new RouteSpace(432, 304, -50),
    ]),
    69 => new Route(12, 18, GRAY, [
      new RouteSpace(295, 471, 98),
      new RouteSpace(287, 529, 98),
      new RouteSpace(278, 587, 98),
      new RouteSpace(270, 645, 98),
      new RouteSpace(261, 703, 98),
      new RouteSpace(252, 761, 98),
      new RouteSpace(244, 819, 98),
    ], ferryWaves: 4),
    70 => new Route(12, 18, GRAY, [
      new RouteSpace(316, 474, 98),
      new RouteSpace(308, 532, 98),
      new RouteSpace(299, 590, 98),
      new RouteSpace(291, 648, 98),
      new RouteSpace(282, 706, 98),
      new RouteSpace(273, 764, 98),
      new RouteSpace(265, 822, 98),
    ], ferryWaves: 4),
    71 => new Route(12, 20, WHITE, [
      new RouteSpace(414, 398, 12),
      new RouteSpace(471, 411, 12),
    ]),
    72 => new Route(12, 20, BLACK, [
      new RouteSpace(409, 419, 12),
      new RouteSpace(466, 432, 12),
    ]),
    73 => new Route(12, 23, YELLOW, [
      new RouteSpace(351, 459, 69),
      new RouteSpace(373, 514, 69),
      new RouteSpace(394, 568, 69),
    ]),
    74 => new Route(12, 23, GREEN, [
      new RouteSpace(371, 451, 69),
      new RouteSpace(392, 506, 69),
      new RouteSpace(414, 560, 69),
    ]),
    75 => new Route(12, 31, ORANGE, [
      new RouteSpace(270, 252, 71),
      new RouteSpace(289, 308, 71),
      new RouteSpace(309, 363, 71),
    ]),
    76 => new Route(12, 31, BLUE, [
      new RouteSpace(290, 245, 71),
      new RouteSpace(310, 301, 71),
      new RouteSpace(330, 357, 71),
    ]),
    77 => new Route(12, 2001, GRAY, [
      new RouteSpace(163, 395, 1),
      new RouteSpace(223, 397, 2),
      new RouteSpace(282, 401, 5),
    ], ferryWaves: 1),
    78 => new Route(13, 21, RED, [
      new RouteSpace(473, 827, 8),
      new RouteSpace(531, 836, 8),
    ]),
    79 => new Route(13, 23, WHITE, [
      new RouteSpace(393, 645, 95),
      new RouteSpace(389, 705, 91),
      new RouteSpace(392, 765, 84),
    ]),
    80 => new Route(13, 23, ORANGE, [
      new RouteSpace(414, 647, 95),
      new RouteSpace(410, 705, 91),
      new RouteSpace(413, 763, 84),
    ]),
    81 => new Route(13, 25, GREEN, [
      new RouteSpace(419, 870, 69),
      new RouteSpace(440, 925, 69),
      new RouteSpace(461, 980, 69),
    ]),
    82 => new Route(13, 25, BLACK, [
      new RouteSpace(439, 863, 69),
      new RouteSpace(461, 918, 69),
      new RouteSpace(482, 973, 69),
    ]),
    83 => new Route(14, 29, RED, [
      new RouteSpace(938, 1632, 26),
      new RouteSpace(990, 1658, 26),
    ]),
    84 => new Route(14, 29, BLUE, [
      new RouteSpace(928, 1652, 26),
      new RouteSpace(981, 1677, 26),
    ]),
    85 => new Route(15, 19, BLUE, [
      new RouteSpace(288, 1697, 27),
      new RouteSpace(340, 1724, 27),
      new RouteSpace(392, 1751, 27),
      new RouteSpace(444, 1778, 27),
    ]),
    86 => new Route(15, 19, RED, [
      new RouteSpace(278, 1716, 27),
      new RouteSpace(330, 1743, 27),
      new RouteSpace(382, 1770, 27),
      new RouteSpace(434, 1797, 27),
    ]),
    87 => new Route(15, 26, GRAY, [
      new RouteSpace(596, 1447, -67),
      new RouteSpace(573, 1502, -69),
      new RouteSpace(553, 1558, -72),
      new RouteSpace(535, 1615, -74),
      new RouteSpace(519, 1673, -76),
      new RouteSpace(506, 1732, -79),
      new RouteSpace(496, 1791, -82),
    ], ferryWaves: 4),
    88 => new Route(15, 28, GRAY, [
      new RouteSpace(480, 1890, -50),
      new RouteSpace(441, 1935, -45),
      new RouteSpace(397, 1975, -37),
    ], ferryWaves: 1),
    89 => new Route(15, 28, GRAY, [
      new RouteSpace(497, 1904, -50),
      new RouteSpace(456, 1951, -45),
      new RouteSpace(410, 1992, -37),
    ], ferryWaves: 1),
    90 => new Route(16, 20, YELLOW, [
      new RouteSpace(482, 312, 82),
      new RouteSpace(491, 370, 82),
    ]),
    91 => new Route(16, 31, BLACK, [
      new RouteSpace(310, 187, 16),
      new RouteSpace(367, 203, 16),
      new RouteSpace(423, 220, 16),
    ]),
    92 => new Route(16, 31, WHITE, [
      new RouteSpace(305, 207, 16),
      new RouteSpace(361, 223, 16),
      new RouteSpace(418, 240, 16),
    ]),
    93 => new Route(16, 3001, BLUE, [
      new RouteSpace(450, 85, 85),
      new RouteSpace(456, 143, 85),
      new RouteSpace(463, 201, 85),
    ]),
    94 => new Route(17, 19, GRAY, [
      new RouteSpace(521, 1366, -37),
      new RouteSpace(474, 1403, -40),
      new RouteSpace(429, 1443, -43),
      new RouteSpace(387, 1485, -46),
      new RouteSpace(347, 1529, -48),
      new RouteSpace(308, 1574, -50),
      new RouteSpace(270, 1620, -51),
    ], ferryWaves: 4),
    95 => new Route(17, 19, GRAY, [
      new RouteSpace(535, 1383, -37),
      new RouteSpace(488, 1420, -40),
      new RouteSpace(444, 1459, -43),
      new RouteSpace(403, 1500, -46),
      new RouteSpace(363, 1544, -48),
      new RouteSpace(325, 1588, -50),
      new RouteSpace(287, 1634, -51),
    ], ferryWaves: 4),
    96 => new Route(17, 22, PINK, [
      new RouteSpace(704, 1117, 118),
      new RouteSpace(676, 1169, 118),
      new RouteSpace(648, 1221, 118),
      new RouteSpace(620, 1273, 118),
    ]),
    97 => new Route(17, 25, YELLOW, [
      new RouteSpace(486, 1103, 72),
      new RouteSpace(504, 1159, 72),
      new RouteSpace(523, 1214, 72),
      new RouteSpace(541, 1270, 72),
    ]),
    98 => new Route(17, 25, WHITE, [
      new RouteSpace(507, 1097, 72),
      new RouteSpace(525, 1152, 72),
      new RouteSpace(544, 1207, 72),
      new RouteSpace(562, 1263, 72),
    ]),
    99 => new Route(17, 25, RED, [
      new RouteSpace(528, 1090, 72),
      new RouteSpace(546, 1145, 72),
      new RouteSpace(565, 1201, 72),
      new RouteSpace(583, 1256, 72),
    ]),
    100 => new Route(17, 26, ORANGE, [
      new RouteSpace(591, 1372, 53),
    ]),
    101 => new Route(17, 26, BLUE, [
      new RouteSpace(608, 1360, 53),
    ]),
    102 => new Route(18, 25, GRAY, [
      new RouteSpace(279, 901, 26),
      new RouteSpace(332, 930, 31),
      new RouteSpace(382, 964, 36),
      new RouteSpace(429, 1002, 40),
    ], ferryWaves: 2),
    103 => new Route(18, 25, GRAY, [
      new RouteSpace(270, 920, 26),
      new RouteSpace(321, 949, 31),
      new RouteSpace(370, 981, 36),
      new RouteSpace(415, 1018, 40),
    ], ferryWaves: 2),
    104 => new Route(18, 27, ORANGE, [
      new RouteSpace(132, 906, -16),
      new RouteSpace(189, 890, -16),
    ]),
    105 => new Route(18, 27, RED, [
      new RouteSpace(138, 926, -16),
      new RouteSpace(195, 910, -16),
    ]),
    106 => new Route(20, 23, BLUE, [
      new RouteSpace(471, 489, 105),
      new RouteSpace(456, 545, 105),
    ]),
    107 => new Route(21, 22, BLACK, [
      new RouteSpace(620, 890, 61),
      new RouteSpace(650, 941, 61),
      new RouteSpace(679, 992, 61),
      new RouteSpace(707, 1044, 61),
    ]),
    108 => new Route(21, 24, PINK, [
      new RouteSpace(664, 686, -64),
      new RouteSpace(639, 739, -64),
      new RouteSpace(613, 792, -64),
    ]),
    109 => new Route(21, 25, BLUE, [
      new RouteSpace(569, 890, 111),
      new RouteSpace(547, 945, 116),
      new RouteSpace(518, 997, 123),
    ]),
    110 => new Route(22, 25, ORANGE, [
      new RouteSpace(549, 1049, 8),
      new RouteSpace(608, 1059, 8),
      new RouteSpace(666, 1068, 8),
    ]),
    111 => new Route(24, 33, RED, [
      new RouteSpace(748, 524, -56),
      new RouteSpace(715, 573, -56),
    ]),
    112 => new Route(24, 33, BLACK, [
      new RouteSpace(766, 536, -56),
      new RouteSpace(733, 585, -56),
    ]),
    113 => new Route(26, 29, WHITE, [
      new RouteSpace(672, 1426, 34),
      new RouteSpace(721, 1459, 34),
      new RouteSpace(768, 1492, 34),
      new RouteSpace(817, 1525, 34),
      new RouteSpace(865, 1558, 34),
    ]),
    114 => new Route(30, 32, BLACK, [
      new RouteSpace(1018, 445, -70),
      new RouteSpace(997, 499, -70),
    ]),
    115 => new Route(30, 33, GREEN, [
      new RouteSpace(854, 438, -14),
      new RouteSpace(910, 425, -14),
      new RouteSpace(967, 411, -14),
    ]),
    116 => new Route(30, 33, BLUE, [
      new RouteSpace(859, 459, -14),
      new RouteSpace(916, 445, -14),
      new RouteSpace(972, 431, -14),
    ]),
    117 => new Route(30, 4001, RED, [
      new RouteSpace(1027, 346, 94),
    ]),
    118 => new Route(30, 4001, YELLOW, [
      new RouteSpace(1048, 348, 94),
    ]),
    119 => new Route(31, 1001, YELLOW, [
      new RouteSpace(142, 171, 8),
      new RouteSpace(201, 180, 8),
    ]),
    120 => new Route(31, 1001, PINK, [
      new RouteSpace(138, 191, 8),
      new RouteSpace(198, 200, 8),
    ]),
    121 => new Route(31, 2001, GREEN, [
      new RouteSpace(221, 244, -53),
      new RouteSpace(185, 292, -53),
      new RouteSpace(149, 340, -53),
    ]),
    122 => new Route(31, 3001, RED, [
      new RouteSpace(273, 156, -60),
      new RouteSpace(304, 104, -60),
      new RouteSpace(334, 52, -60),
    ]),
    123 => new Route(32, 33, ORANGE, [
      new RouteSpace(839, 493, 17),
      new RouteSpace(893, 510, 17),
      new RouteSpace(949, 527, 17),
    ]),
    124 => new Route(32, 33, GRAY, [
      new RouteSpace(832, 514, 17),
      new RouteSpace(887, 530, 17),
      new RouteSpace(942, 547, 17),
    ], ferryWaves: 1),
    125 => new Route(32, 5001, YELLOW, [
      new RouteSpace(998, 605, 77),
      new RouteSpace(1011, 663, 77),
    ]),
    126 => new Route(32, 5001, PINK, [
      new RouteSpace(1019, 600, 77),
      new RouteSpace(1032, 658, 77),
    ]),
    127 => new Route(33, 34, WHITE, [
      new RouteSpace(705, 410, 33),
      new RouteSpace(755, 442, 33),
    ]),
    128 => new Route(33, 34, PINK, [
      new RouteSpace(694, 428, 33),
      new RouteSpace(744, 460, 33),
    ]),
  ];
}
