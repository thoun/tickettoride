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
      new RouteSpace(1450, 747, 3),
      new RouteSpace(1516, 749, 3),
    ]),
    2 => new Route(1, 16, BLUE, [
      new RouteSpace(1422, 781, 51),
      new RouteSpace(1464, 832, 51),
      new RouteSpace(1505, 883, 51),
      new RouteSpace(1545, 933, 51),
      new RouteSpace(1586, 983, 51, true),
  ]),
    3 => new Route(1, 18, GRAY, [
      new RouteSpace(1343, 691, 35),
  ]),
    4 => new Route(1, 19, YELLOW, [
      new RouteSpace(1235, 907, 291),
      new RouteSpace(1264, 849, 303),
      new RouteSpace(1303, 797, 310),
      new RouteSpace(1349, 750, 319),
  ]),
    5 => new Route(1, 19, ORANGE, [
      new RouteSpace(1251, 924, 291),
      new RouteSpace(1282, 865, 303),
      new RouteSpace(1321, 813, 310),
      new RouteSpace(1366, 767, 319),
  ]),
    6 => new Route(1, 26, GRAY, [
      new RouteSpace(1422, 687, -41),
      new RouteSpace(1471, 645, -41),
  ]),
    7 => new Route(1, 26, GRAY, [
      new RouteSpace(1486, 661, -41),
      new RouteSpace(1437, 705, -41),
  ]),
    8 => new Route(2, 17, GRAY, [
      new RouteSpace(1620, 116, 40),
      new RouteSpace(1670, 157, 40),
  ]),
    9 => new Route(2, 17, GRAY, [
      new RouteSpace(1605, 133, 40),
      new RouteSpace(1655, 174, 40),
  ]),
    10 => new Route(2, 20, YELLOW, [
      new RouteSpace(1667, 238, 122),
      new RouteSpace(1633, 293, 122),
  ]),
    11 => new Route(2, 20, RED, [
      new RouteSpace(1686, 250, 122),
      new RouteSpace(1652, 305, 122),
  ]),

    100 => new Route(3, 10, GRAY, [
      new RouteSpace(414, 145, 50),
      new RouteSpace(457, 196, 50),
      new RouteSpace(498, 245, 50),
      new RouteSpace(540, 295, 50),
  ]),
    
    12 => new Route(3, 32, GRAY, [
      new RouteSpace(191, 229, 0),
      new RouteSpace(257, 225, -7),
      new RouteSpace(318, 201, -37),
      new RouteSpace(361, 149, -63),
  ]),
    13 => new Route(3, 34, GRAY, [
      new RouteSpace(196, 121, -6),
      new RouteSpace(261, 113, -6),
      new RouteSpace(327, 106, -6),
  ]),
    14 => new Route(3, 36, WHITE, [
      new RouteSpace(428, 84, -23),
      new RouteSpace(490, 65, -11),
      new RouteSpace(555, 55, -2),
      new RouteSpace(622, 56, 4),
      new RouteSpace(687, 69, 15),
      new RouteSpace(747, 91, 25),
  ]),
    15 => new Route(4, 16, PINK, [
      new RouteSpace(1567, 786, 87),
      new RouteSpace(1572, 852, 82),
      new RouteSpace(1588, 917, 73),
      new RouteSpace(1614, 976, 59),
  ]),
    16 => new Route(4, 26, GRAY, [
      new RouteSpace(1551, 652, 35),
      new RouteSpace(1581, 696, -65),
  ]),
    17 => new Route(5, 8, RED, [
      new RouteSpace(1031, 362, 28),
      new RouteSpace(1091, 389, 21),
      new RouteSpace(1154, 408, 14),
  ]),
    18 => new Route(5, 22, BLUE, [
      new RouteSpace(982, 479, -34, true),
      new RouteSpace(1035, 443, -34),
      new RouteSpace(1099, 428, 8),
      new RouteSpace(1161, 437, 8),
  ]),
    19 => new Route(5, 24, ORANGE, [
      new RouteSpace(1258, 407, -14),
      new RouteSpace(1322, 393, -9),
      new RouteSpace(1388, 390, 3),
  ]),
    20 => new Route(5, 24, BLACK, [
      new RouteSpace(1266, 430, -14),
      new RouteSpace(1331, 416, -9),
      new RouteSpace(1395, 413, 3),
  ]),
    21 => new Route(5, 27, GREEN, [
      new RouteSpace(1138, 537, -57),
      new RouteSpace(1172, 484, -57),
  ]),
    22 => new Route(5, 27, WHITE, [
      new RouteSpace(1158, 547, -57),
      new RouteSpace(1192, 493, -57),
  ]),
    23 => new Route(5, 33, WHITE, [
      new RouteSpace(1223, 389, -48),
      new RouteSpace(1273, 346, -35),
      new RouteSpace(1333, 317, -17),
      new RouteSpace(1391, 287, -39, true),
  ]),
    24 => new Route(6, 9, RED, [
      new RouteSpace(728, 951, 351),
      new RouteSpace(792, 940, 351),
      new RouteSpace(856, 931, 351),
      new RouteSpace(921, 922, 351),
  ]),
    25 => new Route(6, 11, GRAY, [
      new RouteSpace(1001, 948, 49, true),
  ]),
    26 => new Route(6, 11, GRAY, [
      new RouteSpace(1019, 933, 49),
  ]),
    27 => new Route(6, 14, GRAY, [
      new RouteSpace(1023, 848, -55),
      new RouteSpace(1061, 795, -55),
  ]),
    28 => new Route(6, 21, GRAY, [
      new RouteSpace(948, 795, -97),
      new RouteSpace(956, 859, -97),
  ]),
    29 => new Route(6, 21, GRAY, [
      new RouteSpace(970, 793, -97),
      new RouteSpace(978, 857, -97),
  ]),
    30 => new Route(7, 10, GREEN, [
      new RouteSpace(582, 387, 67),
      new RouteSpace(605, 446, 67),
      new RouteSpace(630, 506, 67),
      new RouteSpace(654, 567, 67),
  ]),
    31 => new Route(7, 12, BLACK, [
      new RouteSpace(729, 623, 5),
      new RouteSpace(795, 624, -5),
      new RouteSpace(859, 614, -11),
      new RouteSpace(923, 594, -23),
  ]),
    32 => new Route(7, 12, ORANGE, [
      new RouteSpace(729, 647, 5),
      new RouteSpace(795, 648, -5),
      new RouteSpace(859, 638, -11),
      new RouteSpace(923, 618, -23),
  ]),
    33 => new Route(7, 21, RED, [
      new RouteSpace(699, 676, 224),
      new RouteSpace(754, 714, 205),
      new RouteSpace(816, 732, 189),
      new RouteSpace(881, 738, 184),
  ]),
    34 => new Route(7, 22, PINK, [
      new RouteSpace(705, 584, -38),
      new RouteSpace(760, 550, -26),
      new RouteSpace(821, 526, -16),
      new RouteSpace(885, 511, -12),
  ]),
    35 => new Route(7, 23, WHITE, [
      new RouteSpace(448, 836, -68),
      new RouteSpace(476, 780, -61),
      new RouteSpace(510, 724, -55),
      new RouteSpace(553, 676, -39),
      new RouteSpace(611, 642, -20),
  ]),
    36 => new Route(7, 28, RED, [
      new RouteSpace(482, 561, 11),
      new RouteSpace(547, 574, 11),
      new RouteSpace(608, 586, 11),
  ]),
    37 => new Route(7, 28, YELLOW, [
      new RouteSpace(478, 583, 11),
      new RouteSpace(543, 596, 11),
      new RouteSpace(604, 608, 11),
  ]),

    99 => new Route(7, 31, GRAY, [
      new RouteSpace(656, 675, 273),
      new RouteSpace(653, 739, 273),
  ]),

    38 => new Route(8, 10, ORANGE, [
      new RouteSpace(613, 338, -1),
      new RouteSpace(679, 338, -1),
      new RouteSpace(745, 337, -1),
      new RouteSpace(810, 336, -1),
      new RouteSpace(876, 335, -1),
      new RouteSpace(942, 334, -1),
  ]),
    39 => new Route(8, 22, GRAY, [
      new RouteSpace(962, 381, -68),
      new RouteSpace(938, 441, -68),
  ]),
    40 => new Route(8, 22, GRAY, [
      new RouteSpace(982, 388, -68),
      new RouteSpace(958, 449, -68),
  ]),
    41 => new Route(8, 29, GRAY, [
      new RouteSpace(1053, 287, -23),
      new RouteSpace(1113, 263, -23),
      new RouteSpace(1173, 237, -23),
  ]),
    42 => new Route(8, 33, PINK, [
      new RouteSpace(1041, 321, -8),
      new RouteSpace(1105, 310, -8),
      new RouteSpace(1170, 298, -8),
      new RouteSpace(1235, 288, -8),
      new RouteSpace(1298, 277, -8),
      new RouteSpace(1364, 265, -8),
  ]),
    43 => new Route(8, 36, BLACK, [
      new RouteSpace(821, 158, 44),
      new RouteSpace(869, 203, 44),
      new RouteSpace(915, 248, 44),
      new RouteSpace(962, 294, 44),
  ]),
    44 => new Route(9, 11, GREEN, [
      new RouteSpace(684, 980, 30),
      new RouteSpace(744, 1007, 18),
      new RouteSpace(807, 1022, 8),
      new RouteSpace(873, 1028, 2),
      new RouteSpace(939, 1024, -10),
      new RouteSpace(1001, 1007, -19),
  ]),
    45 => new Route(9, 15, BLACK, [
      new RouteSpace(258, 904, 36),
      new RouteSpace(314, 937, 24),
      new RouteSpace(377, 959, 15),
      new RouteSpace(442, 972, 9),
      new RouteSpace(507, 974, -4),
      new RouteSpace(571, 964, -14, true),
  ]),
    46 => new Route(9, 21, YELLOW, [
      new RouteSpace(693, 931, 342),
      new RouteSpace(753, 907, 334),
      new RouteSpace(812, 874, 326),
      new RouteSpace(862, 832, 315),
      new RouteSpace(904, 782, 305),
  ]),
    47 => new Route(9, 23, GRAY, [
      new RouteSpace(599, 935, 16),
      new RouteSpace(536, 917, 16),
      new RouteSpace(474, 899, 16),
  ]),
    48 => new Route(9, 31, GRAY, [
      new RouteSpace(646, 901, 273),
      new RouteSpace(649, 834, 273),
  ]),
    49 => new Route(10, 22, RED, [
      new RouteSpace(630, 375, 22),
      new RouteSpace(690, 399, 22),
      new RouteSpace(751, 424, 22),
      new RouteSpace(813, 449, 22),
      new RouteSpace(872, 474, 22),
  ]),
    50 => new Route(10, 28, PINK, [
      new RouteSpace(530, 389, -59),
      new RouteSpace(498, 444, -59),
      new RouteSpace(463, 501, -59),
  ]),
    51 => new Route(10, 32, YELLOW, [
      new RouteSpace(186, 263, 12),
      new RouteSpace(250, 278, 12),
      new RouteSpace(313, 292, 12),
      new RouteSpace(377, 307, 12),
      new RouteSpace(440, 321, 12),
      new RouteSpace(504, 335, 12),
  ]),
    52 => new Route(10, 36, BLUE, [
      new RouteSpace(606, 295, -47),
      new RouteSpace(652, 248, -47),
      new RouteSpace(698, 203, -47),
      new RouteSpace(743, 156, -47),
  ]),
    53 => new Route(11, 19, GRAY, [
      new RouteSpace(1163, 961, 352),
      new RouteSpace(1099, 971, 352),
  ]),
    54 => new Route(12, 21, GRAY, [
      new RouteSpace(961, 636, -74),
      new RouteSpace(943, 698, -74),
  ]),
    55 => new Route(12, 21, GRAY, [
      new RouteSpace(982, 642, -74),
      new RouteSpace(965, 704, -74),
  ]),
    56 => new Route(12, 22, GRAY, [
      new RouteSpace(950, 549, -115, true),
  ]),
    57 => new Route(12, 22, GRAY, [
      new RouteSpace(970, 539, -115),
  ]),
    58 => new Route(12, 27, BLUE, [
      new RouteSpace(1021, 576, -1),
      new RouteSpace(1086, 574, -1),
  ]),
    59 => new Route(12, 27, PINK, [
      new RouteSpace(1021, 599, -1),
      new RouteSpace(1086, 597, -1),
  ]),
    60 => new Route(13, 15, GRAY, [
      new RouteSpace(228, 814, -65),
      new RouteSpace(279, 772, -12),
  ]),
    61 => new Route(13, 28, ORANGE, [
      new RouteSpace(371, 737, -45),
      new RouteSpace(407, 683, -67),
      new RouteSpace(425, 619, -81),
  ]),
    62 => new Route(14, 18, WHITE, [
      new RouteSpace(1148, 749, -4),
      new RouteSpace(1214, 732, -24),
      new RouteSpace(1270, 696, -41),
  ]),
    63 => new Route(14, 19, GREEN, [
      new RouteSpace(1127, 797, 63),
      new RouteSpace(1156, 854, 63),
      new RouteSpace(1188, 912, 63),
  ]),
    64 => new Route(14, 21, GRAY, [
      new RouteSpace(991, 748, -2),
      new RouteSpace(1054, 746, -2),
  ]),
    65 => new Route(14, 27, GRAY, [
      new RouteSpace(1124, 639, -75),
      new RouteSpace(1109, 703, -75),
  ]),
    66 => new Route(15, 23, GRAY, [
      new RouteSpace(257, 855, -8),
      new RouteSpace(322, 852, 1),
      new RouteSpace(387, 859, 13),
  ]),
    67 => new Route(15, 30, YELLOW, [
      new RouteSpace(84, 735, -113, true),
      new RouteSpace(115, 794, -125, true),
      new RouteSpace(157, 844, -134, true),
  ]),
    68 => new Route(15, 30, PINK, [
      new RouteSpace(103, 723, -113),
      new RouteSpace(135, 780, -125),
      new RouteSpace(176, 830, -134),
  ]),
    69 => new Route(16, 19, RED, [
      new RouteSpace(1574, 1011, 49),
      new RouteSpace(1529, 964, 40),
      new RouteSpace(1474, 928, 28),
      new RouteSpace(1411, 912, 0),
      new RouteSpace(1345, 920, 345),
      new RouteSpace(1285, 947, 330),
  ]),
    70 => new Route(17, 20, BLUE, [
      new RouteSpace(1565, 152, 80),
      new RouteSpace(1575, 217, 80),
      new RouteSpace(1586, 280, 80),
  ]),
    71 => new Route(17, 29, BLACK, [
      new RouteSpace(1264, 173, -40),
      new RouteSpace(1318, 137, -28),
      new RouteSpace(1377, 111, -19),
      new RouteSpace(1442, 93, -13),
      new RouteSpace(1507, 86, 0),
  ]),
    72 => new Route(17, 33, GRAY, [
      new RouteSpace(1426, 202, -59),
      new RouteSpace(1468, 151, -42),
      new RouteSpace(1524, 115, -23),
  ]),
    73 => new Route(18, 24, YELLOW, [
      new RouteSpace(1296, 621, -62),
      new RouteSpace(1332, 565, -51),
      new RouteSpace(1381, 520, -33),
      new RouteSpace(1428, 476, -56),
  ]),
    74 => new Route(18, 26, BLACK, [
      new RouteSpace(1342, 631, -32),
      new RouteSpace(1402, 606, -13),
      new RouteSpace(1468, 600, 4),
  ]),
    75 => new Route(18, 27, GRAY, [
      new RouteSpace(1178, 631, 17),
      new RouteSpace(1240, 651, 17),
  ]),
    76 => new Route(20, 24, WHITE, [
      new RouteSpace(1492, 374, -31),
      new RouteSpace(1547, 340, -31),
  ]),
    77 => new Route(20, 24, GREEN, [
      new RouteSpace(1504, 393, -31),
      new RouteSpace(1559, 360, -31),
  ]),
    78 => new Route(20, 35, ORANGE, [
      new RouteSpace(1605, 384, 87),
      new RouteSpace(1608, 450, 87),
  ]),
    79 => new Route(20, 35, BLACK, [
      new RouteSpace(1627, 382, 87),
      new RouteSpace(1630, 448, 87),
  ]),
    80 => new Route(21, 31, BLUE, [
      new RouteSpace(707, 783, -7),
      new RouteSpace(771, 777, -7),
      new RouteSpace(837, 769, -7),
  ]),
    81 => new Route(23, 31, GRAY, [
      new RouteSpace(486, 857, -23),
      new RouteSpace(548, 830, -23),
      new RouteSpace(606, 803, -23),
  ]),
    82 => new Route(24, 26, GRAY, [
      new RouteSpace(1474, 485, 77),
      new RouteSpace(1489, 550, 77),
  ]),
    83 => new Route(24, 27, GREEN, [
      new RouteSpace(1178, 582, -30),
      new RouteSpace(1235, 549, -30),
      new RouteSpace(1291, 516, -30),
      new RouteSpace(1348, 484, -30),
      new RouteSpace(1405, 452, -30),
  ]),
    84 => new Route(24, 33, GRAY, [
      new RouteSpace(1436, 295, -93),
      new RouteSpace(1440, 362, -93),
  ]),
    85 => new Route(24, 35, GRAY, [
      new RouteSpace(1507, 446, 28),
      new RouteSpace(1565, 477, 28),
  ]),
    86 => new Route(25, 28, BLUE, [
      new RouteSpace(148, 333, 12),
      new RouteSpace(210, 351, 20),
      new RouteSpace(271, 381, 33),
      new RouteSpace(324, 419, 40),
      new RouteSpace(371, 464, 49),
      new RouteSpace(408, 517, 61),
  ]),
    87 => new Route(25, 30, GREEN, [
      new RouteSpace(65, 369, 111),
      new RouteSpace(45, 432, 102),
      new RouteSpace(38, 497, 93, true),
      new RouteSpace(39, 562, 86, true),
      new RouteSpace(51, 628, 73, true),
  ]),
    88 => new Route(25, 30, PINK, [
      new RouteSpace(88, 372, 111),
      new RouteSpace(68, 435, 102),
      new RouteSpace(61, 500, 93),
      new RouteSpace(62, 565, 86),
      new RouteSpace(74, 631, 73),
  ]),
    89 => new Route(25, 32, GRAY, [
      new RouteSpace(104, 272, 112),
  ]),
    90 => new Route(25, 32, GRAY, [
      new RouteSpace(125, 280, 112),
  ]),
    91 => new Route(26, 35, GRAY, [
      new RouteSpace(1539, 579, -50),
      new RouteSpace(1582, 529, -50),
  ]),
    92 => new Route(26, 35, GRAY, [
      new RouteSpace(1556, 593, -50),
      new RouteSpace(1598, 543, -50),
  ]),
    93 => new Route(28, 30, ORANGE, [
      new RouteSpace(129, 651, -18),
      new RouteSpace(190, 631, -18),
      new RouteSpace(251, 611, -18),
      new RouteSpace(311, 590, -18),
      new RouteSpace(373, 571, -18),
  ]),
    94 => new Route(28, 30, WHITE, [
      new RouteSpace(136, 672, -18),
      new RouteSpace(197, 652, -18),
      new RouteSpace(258, 632, -18),
      new RouteSpace(319, 611, -18),
      new RouteSpace(380, 591, -18),
  ]),
    95 => new Route(29, 33, GRAY, [
      new RouteSpace(1282, 219, 11),
      new RouteSpace(1346, 233, 11),
  ]),
    96 => new Route(29, 36, GRAY, [
      new RouteSpace(853, 123, 12),
      new RouteSpace(917, 136, 12),
      new RouteSpace(981, 149, 12),
      new RouteSpace(1044, 163, 12),
      new RouteSpace(1107, 177, 12),
      new RouteSpace(1171, 189, 12),
  ]),
    97 => new Route(32, 34, GRAY, [
      new RouteSpace(126, 183, 89),
  ]),
    98 => new Route(32, 34, GRAY, [
      new RouteSpace(149, 183, 89),
  ]),
  ];
}

/*
console.log(``.replace(/RouteSpace\((\d+), (\d+)/g, (_, x, y) => `RouteSpace(${Number(x) + 37}, ${Number(y) + 18}`))
*/
