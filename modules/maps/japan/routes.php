<?php

use Bga\Games\TicketToRide\Objects\Route;
use Bga\Games\TicketToRide\Objects\RouteSpace;

/**
 * Route on the map.
 * For double routes, there is 2 instances of Route.
 * For cities (from/to), it's always low id to high id.
 * Inset routes use the main city ids for Kokura (17) and Tokyo (43).
 */
function getRoutes() {
  return [
    1 => new Route(1, 2, RED, [
        new RouteSpace(1778, 329, -35),
        new RouteSpace(1827, 294, -35),
        new RouteSpace(1875, 260, -35),
    ]),
    2 => new Route(1, 26, GRAY, [
        new RouteSpace(1777, 386, 23),
        new RouteSpace(1831, 413, 30),
    ], bulletTrainSpaceIndex: 1),
    3 => new Route(1, 31, ORANGE, [
        new RouteSpace(1682, 401, -39),
        new RouteSpace(1636, 438, -39),
        new RouteSpace(1590, 476, -39),
        new RouteSpace(1544, 513, -39),
        new RouteSpace(1498, 550, -39),
    ]),
    4 => new Route(1, 38, BLUE, [
        new RouteSpace(1718, 423, 100),
        new RouteSpace(1702, 480, 110),
    ]),
    5 => new Route(2, 7, WHITE, [
        new RouteSpace(1881, 189, 77),
        new RouteSpace(1887, 131, -67),
        new RouteSpace(1930, 88, -22),
    ]),
    6 => new Route(2, 7, GRAY, [
        new RouteSpace(1937, 183, -68),
        new RouteSpace(1961, 132, -68),
    ], bulletTrainSpaceIndex: 0),
    7 => new Route(2, 23, YELLOW, [
        new RouteSpace(1965, 239, 15),
        new RouteSpace(2006, 278, 74),
        new RouteSpace(2014, 339, 95),
        new RouteSpace(2003, 399, 107),
        new RouteSpace(1984, 457, 108),
    ]),
    8 => new Route(2, 26, GRAY, [
        new RouteSpace(1949, 264, 25),
        new RouteSpace(1981, 315, 90),
        new RouteSpace(1959, 373, -49),
        new RouteSpace(1916, 416, -43),
    ], bulletTrainSpaceIndex: 2),
    9 => new Route(3, 15, ORANGE, [
        new RouteSpace(1302, 175, 48),
    ]),
    10 => new Route(3, 15, BLACK, [
        new RouteSpace(1287, 190, 48),
    ]),
    11 => new Route(3, 25, BLUE, [
        new RouteSpace(1338, 282, 91),
        new RouteSpace(1335, 341, 100),
        new RouteSpace(1322, 403, 105),
    ]),
    12 => new Route(3, 40, YELLOW, [
        new RouteSpace(1278, 247, -36),
        new RouteSpace(1230, 282, -36),
    ]),
    13 => new Route(3, 40, GREEN, [
        new RouteSpace(1292, 265, -36),
        new RouteSpace(1244, 300, -36),
    ]),
    14 => new Route(4, 12, GREEN, [
        new RouteSpace(1617, 760, 98),
        new RouteSpace(1614, 820, 90),
    ]),
    15 => new Route(4, 31, RED, [
        new RouteSpace(1487, 630, 58),
        new RouteSpace(1518, 681, 56),
        new RouteSpace(1571, 707, 2),
    ]),
    16 => new Route(4, 36, GRAY, [
        new RouteSpace(1674, 692, -14),
    ], bulletTrainSpaceIndex: 0),
    17 => new Route(4, 38, GRAY, [
        new RouteSpace(1645, 572, -42),
        new RouteSpace(1600, 611, -42),
        new RouteSpace(1598, 661, 58),
    ], bulletTrainSpaceIndex: 1),
    18 => new Route(4, 47, GRAY, [
        new RouteSpace(1586, 755, -49),
        new RouteSpace(1546, 800, -49),
        new RouteSpace(1506, 846, -49),
    ], bulletTrainSpaceIndex: 1),
    19 => new Route(5, 25, YELLOW, [
        new RouteSpace(1129, 444, 15),
        new RouteSpace(1187, 457, 8),
        new RouteSpace(1247, 462, 0),
    ]),
    20 => new Route(5, 40, BLUE, [
        new RouteSpace(1127, 399, -28),
        new RouteSpace(1178, 371, -28),
    ]),
    21 => new Route(5, 43, GRAY, [
        new RouteSpace(1067, 383, 67),
    ], bulletTrainSpaceIndex: 0),
    22 => new Route(6, 17, GRAY, [
        new RouteSpace(470, 132, 3),
    ], bulletTrainSpaceIndex: 0),
    23 => new Route(6, 18, GRAY, [
        new RouteSpace(407, 173, -64),
        new RouteSpace(378, 228, -64),
    ], bulletTrainSpaceIndex: 1),
    24 => new Route(6, 28, WHITE, [
        new RouteSpace(305, 150, -21),
        new RouteSpace(364, 131, -11),
    ]),
    25 => new Route(6, 28, ORANGE, [
        new RouteSpace(314, 171, -21),
        new RouteSpace(368, 152, -11),
    ]),
    26 => new Route(8, 29, GRAY, [
        new RouteSpace(993, 1033, 7),
        new RouteSpace(944, 1000, 67),
        new RouteSpace(936, 941, 97),
    ], bulletTrainSpaceIndex: 1),
    27 => new Route(8, 32, GRAY, [
        new RouteSpace(1091, 1039, 0),
        new RouteSpace(1148, 1040, 0),
        new RouteSpace(1205, 1040, 0),
    ], bulletTrainSpaceIndex: 1),
    28 => new Route(9, 17, GRAY, [
        new RouteSpace(70, 665, 17),
        new RouteSpace(127, 678, 9),
        new RouteSpace(186, 686, 3),
        new RouteSpace(245, 687, 0),
    ], bulletTrainSpaceIndex: 1),
    29 => new Route(9, 20, RED, [
        new RouteSpace(261, 640, 61),
    ]),
    30 => new Route(9, 20, WHITE, [
        new RouteSpace(278, 629, 61),
    ]),
    31 => new Route(9, 22, PINK, [
        new RouteSpace(277, 730, 105),
    ]),
    32 => new Route(9, 22, BLACK, [
        new RouteSpace(298, 736, 105),
    ]),
    33 => new Route(9, 34, GRAY, [
        new RouteSpace(352, 694, 14),
        new RouteSpace(407, 708, 14),
        new RouteSpace(463, 722, 14),
    ], bulletTrainSpaceIndex: 1),
    34 => new Route(10, 39, ORANGE, [
        new RouteSpace(857, 167, -46),
        new RouteSpace(816, 214, -53),
        new RouteSpace(780, 264, -58),
    ]),
    35 => new Route(10, 39, GREEN, [
        new RouteSpace(874, 183, -46),
        new RouteSpace(835, 227, -54),
        new RouteSpace(800, 277, -58),
    ]),
    36 => new Route(10, 46, YELLOW, [
        new RouteSpace(955, 149, 11),
        new RouteSpace(1012, 160, 11),
        new RouteSpace(1070, 171, 11),
    ]),
    37 => new Route(10, 46, BLACK, [
        new RouteSpace(950, 169, 11),
        new RouteSpace(1007, 180, 11),
        new RouteSpace(1066, 191, 11),
    ]),
    38 => new Route(10, 48, RED, [
        new RouteSpace(917, 209, 80),
        new RouteSpace(926, 269, 87),
    ]),
    39 => new Route(11, 19, ORANGE, [
        new RouteSpace(821, 944, 63),
        new RouteSpace(800, 887, 78),
    ]),
    40 => new Route(11, 29, WHITE, [
        new RouteSpace(899, 909, -18),
        new RouteSpace(862, 950, -79),
    ]),
    41 => new Route(11, 35, YELLOW, [
        new RouteSpace(712, 908, 52),
        new RouteSpace(755, 951, 40),
        new RouteSpace(807, 986, 27),
    ]),
    42 => new Route(12, 30, WHITE, [
        new RouteSpace(1590, 916, -52),
        new RouteSpace(1550, 963, -46),
        new RouteSpace(1504, 1003, -38),
    ]),
    43 => new Route(12, 36, BLUE, [
        new RouteSpace(1711, 727, 107),
        new RouteSpace(1687, 781, 119),
        new RouteSpace(1656, 832, 126),
    ]),
    44 => new Route(13, 18, GRAY, [
        new RouteSpace(309, 288, -39),
        new RouteSpace(265, 326, -43),
        new RouteSpace(222, 369, -49),
    ], bulletTrainSpaceIndex: 1),
    45 => new Route(13, 24, YELLOW, [
        new RouteSpace(238, 434, 25),
        new RouteSpace(291, 459, 25),
    ]),
    46 => new Route(14, 27, GRAY, [
        new RouteSpace(1083, 610, -24),
        new RouteSpace(1140, 610, 17),
        new RouteSpace(1196, 637, 39),
        new RouteSpace(1226, 689, 79),
    ], bulletTrainSpaceIndex: 1),
    47 => new Route(14, 29, ORANGE, [
        new RouteSpace(1025, 683, 110),
        new RouteSpace(1004, 737, 110),
        new RouteSpace(984, 789, 110),
        new RouteSpace(964, 846, 110),
    ]),
    48 => new Route(14, 45, WHITE, [
        new RouteSpace(995, 661, -35),
        new RouteSpace(947, 696, -35),
        new RouteSpace(899, 730, -35),
    ]),
    49 => new Route(15, 46, WHITE, [
        new RouteSpace(1149, 164, -20),
        new RouteSpace(1209, 149, -10),
    ]),
    50 => new Route(15, 46, RED, [
        new RouteSpace(1157, 186, -20),
        new RouteSpace(1213, 171, -10),
    ]),
    51 => new Route(16, 22, GREEN, [
        new RouteSpace(290, 832, 64),
        new RouteSpace(320, 884, 59),
    ]),
    52 => new Route(16, 41, ORANGE, [
        new RouteSpace(400, 898, -32),
        new RouteSpace(447, 864, -37),
    ]),
    53 => new Route(17, 20, GREEN, [
        new RouteSpace(63, 597, -25),
        new RouteSpace(123, 578, -12),
        new RouteSpace(189, 569, 0),
    ]),
    54 => new Route(17, 20, YELLOW, [
        new RouteSpace(72, 619, -25),
        new RouteSpace(129, 600, -12),
        new RouteSpace(189, 591, 0),
    ]),
    55 => new Route(17, 22, GRAY, [
        new RouteSpace(59, 695, 38),
        new RouteSpace(109, 730, 30),
        new RouteSpace(160, 754, 19),
        new RouteSpace(216, 771, 8),
    ]),
    56 => new Route(17, 33, PINK, [
        new RouteSpace(505, 186, 92),
        new RouteSpace(503, 243, 92),
    ]),
    57 => new Route(17, 33, ORANGE, [
        new RouteSpace(527, 186, 92),
        new RouteSpace(525, 243, 92),
    ]),
    58 => new Route(18, 24, PINK, [
        new RouteSpace(348, 313, 94),
        new RouteSpace(343, 370, 94),
        new RouteSpace(339, 427, 94),
    ]),
    59 => new Route(18, 28, GREEN, [
        new RouteSpace(315, 218, 40),
    ]),
    60 => new Route(18, 28, BLACK, [
        new RouteSpace(301, 235, 40),
    ]),
    61 => new Route(18, 33, RED, [
        new RouteSpace(402, 272, 10),
        new RouteSpace(461, 279, 2),
    ]),
    62 => new Route(18, 33, BLUE, [
        new RouteSpace(398, 294, 10),
        new RouteSpace(459, 301, 2),
    ]),
    63 => new Route(19, 29, GRAY, [
        new RouteSpace(838, 857, 18),
        new RouteSpace(895, 873, 17),
    ], bulletTrainSpaceIndex: 0),
    64 => new Route(19, 35, GRAY, [
        new RouteSpace(744, 855, -14),
    ], bulletTrainSpaceIndex: 0),
    65 => new Route(19, 44, GREEN, [
        new RouteSpace(651, 677, 56),
        new RouteSpace(685, 726, 56),
        new RouteSpace(719, 776, 56),
        new RouteSpace(753, 825, 56),
    ]),
    66 => new Route(19, 45, BLACK, [
        new RouteSpace(826, 797, -46),
    ]),
    67 => new Route(20, 44, BLACK, [
        new RouteSpace(289, 574, 0),
        new RouteSpace(348, 576, 3),
        new RouteSpace(406, 582, 7),
        new RouteSpace(464, 590, 8),
        new RouteSpace(521, 601, 13),
        new RouteSpace(579, 616, 15),
    ]),
    68 => new Route(21, 27, YELLOW, [
        new RouteSpace(1195, 765, -36),
    ]),
    69 => new Route(21, 29, RED, [
        new RouteSpace(1103, 824, -29),
        new RouteSpace(1052, 852, -23),
        new RouteSpace(996, 874, -18),
    ]),
    70 => new Route(21, 29, BLUE, [
        new RouteSpace(1117, 842, -29),
        new RouteSpace(1061, 873, -23),
        new RouteSpace(1005, 894, -18),
    ]),
    71 => new Route(21, 32, GREEN, [
        new RouteSpace(1174, 847, 69),
        new RouteSpace(1194, 904, 69),
        new RouteSpace(1214, 961, 69),
        new RouteSpace(1235, 1016, 69),
    ]),
    72 => new Route(21, 43, BLACK, [
        new RouteSpace(1203, 831, 49),
        new RouteSpace(1241, 877, 49),
        new RouteSpace(1280, 923, 49),
        new RouteSpace(1319, 969, 49),
    ]),
    73 => new Route(22, 41, BLUE, [
        new RouteSpace(320, 791, 12),
        new RouteSpace(378, 804, 12),
        new RouteSpace(436, 816, 12),
    ]),
    74 => new Route(23, 26, BLACK, [
        new RouteSpace(1921, 472, 30),
    ]),
    75 => new Route(23, 36, PINK, [
        new RouteSpace(1954, 552, 101),
        new RouteSpace(1932, 610, 119),
        new RouteSpace(1889, 655, 146),
        new RouteSpace(1835, 681, 163),
        new RouteSpace(1775, 691, 2),
    ]),
    76 => new Route(24, 33, WHITE, [
        new RouteSpace(386, 472, -12),
        new RouteSpace(442, 442, -39),
        new RouteSpace(482, 395, -62),
        new RouteSpace(502, 340, -79),
    ]),
    77 => new Route(25, 40, RED, [
        new RouteSpace(1244, 360, 56),
        new RouteSpace(1277, 410, 56),
    ]),
    78 => new Route(25, 40, PINK, [
        new RouteSpace(1226, 373, 56),
        new RouteSpace(1258, 421, 56),
    ]),
    79 => new Route(26, 36, GRAY, [
        new RouteSpace(1860, 494, -65),
        new RouteSpace(1834, 548, -62),
        new RouteSpace(1799, 598, -53),
        new RouteSpace(1761, 645, -48),
    ], bulletTrainSpaceIndex: 2),
    80 => new Route(26, 38, GREEN, [
        new RouteSpace(1831, 471, -32),
        new RouteSpace(1779, 501, -24),
        new RouteSpace(1725, 523, -18),
    ]),
    81 => new Route(27, 31, PINK, [
        new RouteSpace(1420, 614, -32),
        new RouteSpace(1369, 646, -32),
        new RouteSpace(1318, 678, -32),
        new RouteSpace(1267, 710, -32),
    ]),
    82 => new Route(27, 42, GRAY, [
        new RouteSpace(1235, 787, 93),
        new RouteSpace(1272, 830, 9),
    ], bulletTrainSpaceIndex: 0),
    83 => new Route(29, 45, PINK, [
        new RouteSpace(893, 793, 60),
        new RouteSpace(922, 844, 60),
    ]),
    84 => new Route(30, 43, PINK, [
        new RouteSpace(1406, 1006, 18),
    ]),
    85 => new Route(30, 43, ORANGE, [
        new RouteSpace(1399, 1027, 18),
    ]),
    86 => new Route(30, 47, YELLOW, [
        new RouteSpace(1475, 921, 91),
        new RouteSpace(1470, 981, 100),
    ]),
    87 => new Route(31, 38, BLACK, [
        new RouteSpace(1519, 583, -10),
        new RouteSpace(1576, 568, -19),
        new RouteSpace(1632, 547, -24),
    ]),
    88 => new Route(31, 42, GRAY, [
        new RouteSpace(1438, 643, -69),
        new RouteSpace(1415, 699, -68),
        new RouteSpace(1385, 750, -55),
        new RouteSpace(1350, 798, -52),
    ], bulletTrainSpaceIndex: 2),
    89 => new Route(32, 43, GRAY, [
        new RouteSpace(1307, 1017, -25),
    ], bulletTrainSpaceIndex: 0),
    90 => new Route(34, 35, GRAY, [
        new RouteSpace(553, 765, 27),
        new RouteSpace(605, 796, 33),
        new RouteSpace(652, 831, 41),
    ], bulletTrainSpaceIndex: 1),
    91 => new Route(34, 41, RED, [
        new RouteSpace(486, 784, 107),
    ]),
    92 => new Route(34, 41, YELLOW, [
        new RouteSpace(507, 790, 107),
    ]),
    93 => new Route(34, 44, WHITE, [
        new RouteSpace(548, 705, -47),
        new RouteSpace(589, 662, -47),
    ]),
    94 => new Route(35, 41, PINK, [
        new RouteSpace(644, 872, 1),
        new RouteSpace(586, 867, 7),
        new RouteSpace(529, 856, 12),
    ]),
    95 => new Route(35, 44, RED, [
        new RouteSpace(633, 700, 75),
        new RouteSpace(652, 756, 68),
        new RouteSpace(677, 811, 61),
    ]),
    96 => new Route(36, 38, WHITE, [
        new RouteSpace(1693, 574, 77),
        new RouteSpace(1709, 633, 77),
    ]),
    97 => new Route(37, 39, BLACK, [
        new RouteSpace(785, 383, 64),
        new RouteSpace(816, 435, 58),
    ]),
    98 => new Route(37, 39, BLUE, [
        new RouteSpace(807, 374, 66),
        new RouteSpace(833, 421, 58),
    ]),
    99 => new Route(37, 43, ORANGE, [
        new RouteSpace(907, 449, -33),
        new RouteSpace(955, 414, -40),
        new RouteSpace(1003, 373, -45),
    ]),
    100 => new Route(37, 43, RED, [
        new RouteSpace(925, 467, -35),
        new RouteSpace(972, 430, -39),
        new RouteSpace(1016, 388, -44),
    ]),
    101 => new Route(37, 48, GREEN, [
        new RouteSpace(886, 433, -70),
        new RouteSpace(908, 378, -64),
    ]),
    102 => new Route(39, 48, PINK, [
        new RouteSpace(823, 312, 1),
        new RouteSpace(881, 313, 1),
    ]),
    103 => new Route(39, 48, WHITE, [
        new RouteSpace(823, 334, 1),
        new RouteSpace(880, 335, 1),
    ]),
    104 => new Route(40, 43, WHITE, [
        new RouteSpace(1094, 324, -2),
        new RouteSpace(1152, 322, -2),
    ]),
    105 => new Route(40, 43, BLACK, [
        new RouteSpace(1095, 346, -2),
        new RouteSpace(1153, 344, -2),
    ]),
    106 => new Route(40, 46, ORANGE, [
        new RouteSpace(1139, 236, 54),
        new RouteSpace(1173, 285, 54),
    ]),
    107 => new Route(42, 43, GRAY, [
        new RouteSpace(1328, 888, 79),
        new RouteSpace(1343, 945, 77),
    ], bulletTrainSpaceIndex: 0),
    108 => new Route(42, 47, BLUE, [
        new RouteSpace(1367, 841, 7),
        new RouteSpace(1427, 854, 14),
    ]),
    109 => new Route(43, 46, GREEN, [
        new RouteSpace(1044, 284, -59),
        new RouteSpace(1075, 233, -59),
    ]),
    110 => new Route(43, 46, PINK, [
        new RouteSpace(1063, 296, -59),
        new RouteSpace(1096, 243, -59),
    ]),
    111 => new Route(43, 47, GRAY, [
        new RouteSpace(1394, 949, -49),
        new RouteSpace(1433, 905, -49),
    ], bulletTrainSpaceIndex: 0),
    112 => new Route(43, 48, BLUE, [
        new RouteSpace(978, 319, 1),
    ]),
    113 => new Route(43, 48, YELLOW, [
        new RouteSpace(978, 340, 1),
    ]),
    114 => new Route(44, 45, BLUE, [
        new RouteSpace(676, 641, 21),
        new RouteSpace(732, 664, 27),
        new RouteSpace(782, 691, 32),
        new RouteSpace(830, 723, 34),
    ]),
  ];
}
