<?php

require_once(__DIR__.'/../../php/objects/route.php');

/**
 * Route on the map. 
 * For double routes, there is 2 instances of Route.
 * For cities (from/to), it's always low id to high id.
 */
function getRoutes() {
  return [
    1 => new Route(1, 4, GRAY, [
      new RouteSpace(1413, 729, 3),
      new RouteSpace(1479, 731, 3),
    ]),
    2 => new Route(1, 16, BLUE, [
      new RouteSpace(1385, 763, 51),
      new RouteSpace(1427, 814, 51),
      new RouteSpace(1468, 865, 51),
      new RouteSpace(1508, 915, 51),
      new RouteSpace(1549, 965, 51, true),
  ]),
    3 => new Route(1, 18, GRAY, [
      new RouteSpace(1306, 673, 35),
  ]),
    4 => new Route(1, 19, YELLOW, [
      new RouteSpace(1198, 889, 291),
      new RouteSpace(1227, 831, 303),
      new RouteSpace(1266, 779, 310),
      new RouteSpace(1312, 732, 319),
  ]),
    5 => new Route(1, 19, ORANGE, [
      new RouteSpace(1214, 906, 291),
      new RouteSpace(1245, 847, 303),
      new RouteSpace(1284, 795, 310),
      new RouteSpace(1329, 749, 319),
  ]),
    6 => new Route(1, 26, GRAY, [
      new RouteSpace(1385, 669, -41),
      new RouteSpace(1434, 627, -41),
  ]),
    7 => new Route(1, 26, GRAY, [
      new RouteSpace(1449, 643, -41),
      new RouteSpace(1400, 687, -41),
  ]),
    8 => new Route(2, 17, GRAY, [
      new RouteSpace(1583, 98, 40),
      new RouteSpace(1633, 139, 40),
  ]),
    9 => new Route(2, 17, GRAY, [
      new RouteSpace(1568, 115, 40),
      new RouteSpace(1618, 156, 40),
  ]),
    10 => new Route(2, 20, YELLOW, [
      new RouteSpace(1630, 220, 122),
      new RouteSpace(1596, 275, 122),
  ]),
    11 => new Route(2, 20, RED, [
      new RouteSpace(1649, 232, 122),
      new RouteSpace(1615, 287, 122),
  ]),

    100 => new Route(3, 10, GRAY, [
      new RouteSpace(377, 127, 50),
      new RouteSpace(420, 178, 50),
      new RouteSpace(461, 227, 50),
      new RouteSpace(503, 277, 50),
  ]),
    
    12 => new Route(3, 32, GRAY, [
      new RouteSpace(154, 211, 0),
      new RouteSpace(220, 207, -7),
      new RouteSpace(281, 183, -37),
      new RouteSpace(324, 131, -63),
  ]),
    13 => new Route(3, 34, GRAY, [
      new RouteSpace(159, 103, -6),
      new RouteSpace(224, 95, -6),
      new RouteSpace(290, 88, -6),
  ]),
    14 => new Route(3, 36, WHITE, [
      new RouteSpace(391, 66, -23),
      new RouteSpace(453, 47, -11),
      new RouteSpace(518, 37, -2),
      new RouteSpace(585, 38, 4),
      new RouteSpace(650, 51, 15),
      new RouteSpace(710, 73, 25),
  ]),
    15 => new Route(4, 16, PINK, [
      new RouteSpace(1530, 768, 87),
      new RouteSpace(1535, 834, 82),
      new RouteSpace(1551, 899, 73),
      new RouteSpace(1577, 958, 59),
  ]),
    16 => new Route(4, 26, GRAY, [
      new RouteSpace(1514, 634, 35),
      new RouteSpace(1544, 678, -65),
  ]),
    17 => new Route(5, 8, RED, [
      new RouteSpace(994, 344, 28),
      new RouteSpace(1054, 371, 21),
      new RouteSpace(1117, 390, 14),
  ]),
    18 => new Route(5, 22, BLUE, [
      new RouteSpace(945, 461, -34, true),
      new RouteSpace(998, 425, -34),
      new RouteSpace(1062, 410, 8),
      new RouteSpace(1124, 419, 8),
  ]),
    19 => new Route(5, 24, ORANGE, [
      new RouteSpace(1221, 389, -14),
      new RouteSpace(1285, 375, -9),
      new RouteSpace(1351, 372, 3),
  ]),
    20 => new Route(5, 24, BLACK, [
      new RouteSpace(1229, 412, -14),
      new RouteSpace(1294, 398, -9),
      new RouteSpace(1358, 395, 3),
  ]),
    21 => new Route(5, 27, GREEN, [
      new RouteSpace(1101, 519, -57),
      new RouteSpace(1135, 466, -57),
  ]),
    22 => new Route(5, 27, WHITE, [
      new RouteSpace(1121, 529, -57),
      new RouteSpace(1155, 475, -57),
  ]),
    23 => new Route(5, 33, WHITE, [
      new RouteSpace(1186, 371, -48),
      new RouteSpace(1236, 328, -35),
      new RouteSpace(1296, 299, -17),
      new RouteSpace(1354, 269, -39, true),
  ]),
    24 => new Route(6, 9, RED, [
      new RouteSpace(691, 933, 351),
      new RouteSpace(755, 922, 351),
      new RouteSpace(819, 913, 351),
      new RouteSpace(884, 904, 351),
  ]),
    25 => new Route(6, 11, GRAY, [
      new RouteSpace(964, 930, 49, true),
  ]),
    26 => new Route(6, 11, GRAY, [
      new RouteSpace(982, 915, 49),
  ]),
    27 => new Route(6, 14, GRAY, [
      new RouteSpace(986, 830, -55),
      new RouteSpace(1024, 777, -55),
  ]),
    28 => new Route(6, 21, GRAY, [
      new RouteSpace(911, 777, -97),
      new RouteSpace(919, 841, -97),
  ]),
    29 => new Route(6, 21, GRAY, [
      new RouteSpace(933, 775, -97),
      new RouteSpace(941, 839, -97),
  ]),
    30 => new Route(7, 10, GREEN, [
      new RouteSpace(545, 369, 67),
      new RouteSpace(568, 428, 67),
      new RouteSpace(593, 488, 67),
      new RouteSpace(617, 549, 67),
  ]),
    31 => new Route(7, 12, BLACK, [
      new RouteSpace(692, 605, 5),
      new RouteSpace(758, 606, -5),
      new RouteSpace(822, 596, -11),
      new RouteSpace(886, 576, -23),
  ]),
    32 => new Route(7, 12, ORANGE, [
      new RouteSpace(692, 629, 5),
      new RouteSpace(758, 630, -5),
      new RouteSpace(822, 620, -11),
      new RouteSpace(886, 600, -23),
  ]),
    33 => new Route(7, 21, RED, [
      new RouteSpace(662, 658, 224),
      new RouteSpace(717, 696, 205),
      new RouteSpace(779, 714, 189),
      new RouteSpace(844, 720, 184),
  ]),
    34 => new Route(7, 22, PINK, [
      new RouteSpace(668, 566, -38),
      new RouteSpace(723, 532, -26),
      new RouteSpace(784, 508, -16),
      new RouteSpace(848, 493, -12),
  ]),
    35 => new Route(7, 23, WHITE, [
      new RouteSpace(411, 818, -68),
      new RouteSpace(439, 762, -61),
      new RouteSpace(473, 706, -55),
      new RouteSpace(516, 658, -39),
      new RouteSpace(574, 624, -20),
  ]),
    36 => new Route(7, 28, RED, [
      new RouteSpace(445, 543, 11),
      new RouteSpace(510, 556, 11),
      new RouteSpace(571, 568, 11),
  ]),
    37 => new Route(7, 28, YELLOW, [
      new RouteSpace(441, 565, 11),
      new RouteSpace(506, 578, 11),
      new RouteSpace(567, 590, 11),
  ]),

    99 => new Route(7, 31, GRAY, [
      new RouteSpace(619, 657, 273),
      new RouteSpace(616, 721, 273),
  ]),

    38 => new Route(8, 10, ORANGE, [
      new RouteSpace(576, 320, -1),
      new RouteSpace(642, 320, -1),
      new RouteSpace(708, 319, -1),
      new RouteSpace(773, 318, -1),
      new RouteSpace(839, 317, -1),
      new RouteSpace(905, 316, -1),
  ]),
    39 => new Route(8, 22, GRAY, [
      new RouteSpace(925, 363, -68),
      new RouteSpace(901, 423, -68),
  ]),
    40 => new Route(8, 22, GRAY, [
      new RouteSpace(945, 370, -68),
      new RouteSpace(921, 431, -68),
  ]),
    41 => new Route(8, 29, GRAY, [
      new RouteSpace(1016, 269, -23),
      new RouteSpace(1076, 245, -23),
      new RouteSpace(1136, 219, -23),
  ]),
    42 => new Route(8, 33, PINK, [
      new RouteSpace(1004, 303, -8),
      new RouteSpace(1068, 292, -8),
      new RouteSpace(1133, 280, -8),
      new RouteSpace(1198, 270, -8),
      new RouteSpace(1261, 259, -8),
      new RouteSpace(1327, 247, -8),
  ]),
    43 => new Route(8, 36, BLACK, [
      new RouteSpace(784, 140, 44),
      new RouteSpace(832, 185, 44),
      new RouteSpace(878, 230, 44),
      new RouteSpace(925, 276, 44),
  ]),
    44 => new Route(9, 11, GREEN, [
      new RouteSpace(647, 962, 30),
      new RouteSpace(707, 989, 18),
      new RouteSpace(770, 1004, 8),
      new RouteSpace(836, 1010, 2),
      new RouteSpace(902, 1006, -10),
      new RouteSpace(964, 989, -19),
  ]),
    45 => new Route(9, 15, BLACK, [
      new RouteSpace(221, 886, 36),
      new RouteSpace(277, 919, 24),
      new RouteSpace(340, 941, 15),
      new RouteSpace(405, 954, 9),
      new RouteSpace(470, 956, -4),
      new RouteSpace(534, 946, -14, true),
  ]),
    46 => new Route(9, 21, YELLOW, [
      new RouteSpace(656, 913, 342),
      new RouteSpace(716, 889, 334),
      new RouteSpace(775, 856, 326),
      new RouteSpace(825, 814, 315),
      new RouteSpace(867, 764, 305),
  ]),
    47 => new Route(9, 23, GRAY, [
      new RouteSpace(562, 917, 16),
      new RouteSpace(499, 899, 16),
      new RouteSpace(437, 881, 16),
  ]),
    48 => new Route(9, 31, GRAY, [
      new RouteSpace(609, 883, 273),
      new RouteSpace(612, 816, 273),
  ]),
    49 => new Route(10, 22, RED, [
      new RouteSpace(593, 357, 22),
      new RouteSpace(653, 381, 22),
      new RouteSpace(714, 406, 22),
      new RouteSpace(776, 431, 22),
      new RouteSpace(835, 456, 22),
  ]),
    50 => new Route(10, 28, PINK, [
      new RouteSpace(493, 371, -59),
      new RouteSpace(461, 426, -59),
      new RouteSpace(426, 483, -59),
  ]),
    51 => new Route(10, 32, YELLOW, [
      new RouteSpace(149, 245, 12),
      new RouteSpace(213, 260, 12),
      new RouteSpace(276, 274, 12),
      new RouteSpace(340, 289, 12),
      new RouteSpace(403, 303, 12),
      new RouteSpace(467, 317, 12),
  ]),
    52 => new Route(10, 36, BLUE, [
      new RouteSpace(569, 277, -47),
      new RouteSpace(615, 230, -47),
      new RouteSpace(661, 185, -47),
      new RouteSpace(706, 138, -47),
  ]),
    53 => new Route(11, 19, GRAY, [
      new RouteSpace(1126, 943, 352),
      new RouteSpace(1062, 953, 352),
  ]),
    54 => new Route(12, 21, GRAY, [
      new RouteSpace(924, 618, -74),
      new RouteSpace(906, 680, -74),
  ]),
    55 => new Route(12, 21, GRAY, [
      new RouteSpace(945, 624, -74),
      new RouteSpace(928, 686, -74),
  ]),
    56 => new Route(12, 22, GRAY, [
      new RouteSpace(913, 531, -115, true),
  ]),
    57 => new Route(12, 22, GRAY, [
      new RouteSpace(933, 521, -115),
  ]),
    58 => new Route(12, 27, BLUE, [
      new RouteSpace(984, 558, -1),
      new RouteSpace(1049, 556, -1),
  ]),
    59 => new Route(12, 27, PINK, [
      new RouteSpace(984, 581, -1),
      new RouteSpace(1049, 579, -1),
  ]),
    60 => new Route(13, 15, GRAY, [
      new RouteSpace(191, 796, -65),
      new RouteSpace(242, 754, -12),
  ]),
    61 => new Route(13, 28, ORANGE, [
      new RouteSpace(334, 719, -45),
      new RouteSpace(370, 665, -67),
      new RouteSpace(388, 601, -81),
  ]),
    62 => new Route(14, 18, WHITE, [
      new RouteSpace(1111, 731, -4),
      new RouteSpace(1177, 714, -24),
      new RouteSpace(1233, 678, -41),
  ]),
    63 => new Route(14, 19, GREEN, [
      new RouteSpace(1090, 779, 63),
      new RouteSpace(1119, 836, 63),
      new RouteSpace(1151, 894, 63),
  ]),
    64 => new Route(14, 21, GRAY, [
      new RouteSpace(954, 730, -2),
      new RouteSpace(1017, 728, -2),
  ]),
    65 => new Route(14, 27, GRAY, [
      new RouteSpace(1087, 621, -75),
      new RouteSpace(1072, 685, -75),
  ]),
    66 => new Route(15, 23, GRAY, [
      new RouteSpace(220, 837, -8),
      new RouteSpace(285, 834, 1),
      new RouteSpace(350, 841, 13),
  ]),
    67 => new Route(15, 30, YELLOW, [
      new RouteSpace(47, 717, -113, true),
      new RouteSpace(78, 776, -125, true),
      new RouteSpace(120, 826, -134, true),
  ]),
    68 => new Route(15, 30, PINK, [
      new RouteSpace(66, 705, -113),
      new RouteSpace(98, 762, -125),
      new RouteSpace(139, 812, -134),
  ]),
    69 => new Route(16, 19, RED, [
      new RouteSpace(1537, 993, 49),
      new RouteSpace(1492, 946, 40),
      new RouteSpace(1437, 910, 28),
      new RouteSpace(1374, 894, 0),
      new RouteSpace(1308, 902, 345),
      new RouteSpace(1248, 929, 330),
  ]),
    70 => new Route(17, 20, BLUE, [
      new RouteSpace(1528, 134, 80),
      new RouteSpace(1538, 199, 80),
      new RouteSpace(1549, 262, 80),
  ]),
    71 => new Route(17, 29, BLACK, [
      new RouteSpace(1227, 155, -40),
      new RouteSpace(1281, 119, -28),
      new RouteSpace(1340, 93, -19),
      new RouteSpace(1405, 75, -13),
      new RouteSpace(1470, 68, 0),
  ]),
    72 => new Route(17, 33, GRAY, [
      new RouteSpace(1389, 184, -59),
      new RouteSpace(1431, 133, -42),
      new RouteSpace(1487, 97, -23),
  ]),
    73 => new Route(18, 24, YELLOW, [
      new RouteSpace(1259, 603, -62),
      new RouteSpace(1295, 547, -51),
      new RouteSpace(1344, 502, -33),
      new RouteSpace(1391, 458, -56),
  ]),
    74 => new Route(18, 26, BLACK, [
      new RouteSpace(1305, 613, -32),
      new RouteSpace(1365, 588, -13),
      new RouteSpace(1431, 582, 4),
  ]),
    75 => new Route(18, 27, GRAY, [
      new RouteSpace(1141, 613, 17),
      new RouteSpace(1203, 633, 17),
  ]),
    76 => new Route(20, 24, WHITE, [
      new RouteSpace(1455, 356, -31),
      new RouteSpace(1510, 322, -31),
  ]),
    77 => new Route(20, 24, GREEN, [
      new RouteSpace(1467, 375, -31),
      new RouteSpace(1522, 342, -31),
  ]),
    78 => new Route(20, 35, ORANGE, [
      new RouteSpace(1568, 366, 87),
      new RouteSpace(1571, 432, 87),
  ]),
    79 => new Route(20, 35, BLACK, [
      new RouteSpace(1590, 364, 87),
      new RouteSpace(1593, 430, 87),
  ]),
    80 => new Route(21, 31, BLUE, [
      new RouteSpace(670, 765, -7),
      new RouteSpace(734, 759, -7),
      new RouteSpace(800, 751, -7),
  ]),
    81 => new Route(23, 31, GRAY, [
      new RouteSpace(449, 839, -23),
      new RouteSpace(511, 812, -23),
      new RouteSpace(569, 785, -23),
  ]),
    82 => new Route(24, 26, GRAY, [
      new RouteSpace(1437, 467, 77),
      new RouteSpace(1452, 532, 77),
  ]),
    83 => new Route(24, 27, GREEN, [
      new RouteSpace(1141, 564, -30),
      new RouteSpace(1198, 531, -30),
      new RouteSpace(1254, 498, -30),
      new RouteSpace(1311, 466, -30),
      new RouteSpace(1368, 434, -30),
  ]),
    84 => new Route(24, 33, GRAY, [
      new RouteSpace(1399, 277, -93),
      new RouteSpace(1403, 344, -93),
  ]),
    85 => new Route(24, 35, GRAY, [
      new RouteSpace(1470, 428, 28),
      new RouteSpace(1528, 459, 28),
  ]),
    86 => new Route(25, 28, BLUE, [
      new RouteSpace(111, 315, 12),
      new RouteSpace(173, 333, 20),
      new RouteSpace(234, 363, 33),
      new RouteSpace(287, 401, 40),
      new RouteSpace(334, 446, 49),
      new RouteSpace(371, 499, 61),
  ]),
    87 => new Route(25, 30, GREEN, [
      new RouteSpace(28, 351, 111),
      new RouteSpace(8, 414, 102),
      new RouteSpace(1, 479, 93, true),
      new RouteSpace(2, 544, 86, true),
      new RouteSpace(14, 610, 73, true),
  ]),
    88 => new Route(25, 30, PINK, [
      new RouteSpace(51, 354, 111),
      new RouteSpace(31, 417, 102),
      new RouteSpace(24, 482, 93),
      new RouteSpace(25, 547, 86),
      new RouteSpace(37, 613, 73),
  ]),
    89 => new Route(25, 32, GRAY, [
      new RouteSpace(67, 254, 112),
  ]),
    90 => new Route(25, 32, GRAY, [
      new RouteSpace(88, 262, 112),
  ]),
    91 => new Route(26, 35, GRAY, [
      new RouteSpace(1502, 561, -50),
      new RouteSpace(1545, 511, -50),
  ]),
    92 => new Route(26, 35, GRAY, [
      new RouteSpace(1519, 575, -50),
      new RouteSpace(1561, 525, -50),
  ]),
    93 => new Route(28, 30, ORANGE, [
      new RouteSpace(92, 633, -18),
      new RouteSpace(153, 613, -18),
      new RouteSpace(214, 593, -18),
      new RouteSpace(274, 572, -18),
      new RouteSpace(336, 553, -18),
  ]),
    94 => new Route(28, 30, WHITE, [
      new RouteSpace(99, 654, -18),
      new RouteSpace(160, 634, -18),
      new RouteSpace(221, 614, -18),
      new RouteSpace(282, 593, -18),
      new RouteSpace(343, 573, -18),
  ]),
    95 => new Route(29, 33, GRAY, [
      new RouteSpace(1245, 201, 11),
      new RouteSpace(1309, 215, 11),
  ]),
    96 => new Route(29, 36, GRAY, [
      new RouteSpace(816, 105, 12),
      new RouteSpace(880, 118, 12),
      new RouteSpace(944, 131, 12),
      new RouteSpace(1007, 145, 12),
      new RouteSpace(1070, 159, 12),
      new RouteSpace(1134, 171, 12),
  ]),
    97 => new Route(32, 34, GRAY, [
      new RouteSpace(89, 165, 89),
  ]),
    98 => new Route(32, 34, GRAY, [
      new RouteSpace(112, 165, 89),
  ]),
  ];
}
