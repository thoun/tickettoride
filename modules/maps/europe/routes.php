<?php

require_once(__DIR__.'/../../php/objects/route.php');

/**
 * Route on the map. 
 * For double routes, there is 2 instances of Route.
 * For cities (from/to), it's always low id to high id.
 */
function getRoutes() {
  return [
    1 => new Route(30, 8, RED, [
      new RouteSpace(465, 517, 118),
      new RouteSpace(495, 460, 117)
    ]),
    2 => new Route(18, 5, RED, [
      new RouteSpace(703, 463, 155),
      new RouteSpace(761, 436, 155),
      new RouteSpace(820, 408, 155)
    ]),
    3 => new Route(10, 10, RED, [
      new RouteSpace(998, 592, 31)
    ]),
    4 => new Route(43, 45, RED, [
      new RouteSpace(1173, 318, 116),
      new RouteSpace(1225, 279, 168),
      new RouteSpace(1289, 291, 32)
    ]),
    5 => new Route(21, 37, RED, [
      new RouteSpace(1454, 451, 4),
      new RouteSpace(1515, 427, 134),
      new RouteSpace(1529, 364, 73)
    ]),
    6 => new Route(46, 35, RED, [
      new RouteSpace(937, 779, 83),
      new RouteSpace(971, 836, 36),
      new RouteSpace(1035, 846, 162)
    ]),
    7 => new Route(25, 29, RED, [
      new RouteSpace(529, 789, 27),
      new RouteSpace(464, 773, 2),
      new RouteSpace(411, 809, 111),
      new RouteSpace(377, 843, 20)
    ]),
    8 => new Route(16, 39, RED, [
      new RouteSpace(1677, 964, 98),
      new RouteSpace(1686, 901, 99),
      new RouteSpace(1695, 836, 98)
    ], true),
    9 => new Route(22, 11, BLUE, [
      new RouteSpace(52, 1051, 65),
      new RouteSpace(104, 1091, 12)
    ]),
    10 => new Route(29, 30, BLUE, [
      new RouteSpace(331, 780, 125),
      new RouteSpace(363, 724, 113),
      new RouteSpace(385, 661, 107),
      new RouteSpace(398, 597, 97)
    ]),
    11 => new Route(8, 18, BLUE, [
      new RouteSpace(542, 414, 165),
      new RouteSpace(606, 431, 45)
    ]),
    12 => new Route(17, 5, BLUE, [
      new RouteSpace(745, 339, 7),
      new RouteSpace(809, 347, 7)
    ]),
    13 => new Route(44, 43, BLUE, [
      new RouteSpace(991, 544, 147),
      new RouteSpace(1042, 506, 138),
      new RouteSpace(1087, 457, 126),
      new RouteSpace(1123, 401, 120)
    ]),
    14 => new Route(45, 31, BLUE, [
      new RouteSpace(1361, 267, 126),
      new RouteSpace(1399, 214, 126),
      new RouteSpace(1436, 162, 126),
      new RouteSpace(1474, 110, 126)
    ]),
    15 => new Route(40, 12, BLUE, [
      new RouteSpace(1238, 874, 29),
      new RouteSpace(1294, 905, 29),
      new RouteSpace(1351, 936, 29)
    ]),
    16 => new Route(27, 42, BLUE, [
      new RouteSpace(760, 601, 79),
      new RouteSpace(773, 665, 79)
    ], true),
    17 => new Route(14, 8, GREEN, [
      new RouteSpace(398, 460, 145),
      new RouteSpace(451, 422, 144)
    ]),
    18 => new Route(18, 17, GREEN, [
      new RouteSpace(684, 422, 155),
      new RouteSpace(715, 384, 63)
    ]),
    19 => new Route(5, 44, GREEN, [
      new RouteSpace(868, 419, 76),
      new RouteSpace(893, 479, 63),
      new RouteSpace(928, 533, 52)
    ]),
    20 => new Route(30, 29, GREEN, [
      new RouteSpace(421, 605, 97),
      new RouteSpace(408, 669, 106),
      new RouteSpace(385, 730, 114),
      new RouteSpace(354, 788, 126)
    ]),
    21 => new Route(3, 35, GREEN, [
      new RouteSpace(1115, 1033, 3),
      new RouteSpace(1072, 1009, 93),
      new RouteSpace(1075, 945, 94),
      new RouteSpace(1079, 880, 93)
    ]),
    22 => new Route(45, 32, GREEN, [
      new RouteSpace(1303, 271, 32),
      new RouteSpace(1248, 237, 33),
      new RouteSpace(1202, 186, 68),
      new RouteSpace(1197, 121, 101)
    ]),
    23 => new Route(19, 34, GREEN, [
      new RouteSpace(1692, 542, 179),
      new RouteSpace(1712, 586, 90)
    ]),
    24 => new Route(42, 47, GREEN, [
      new RouteSpace(738, 705, 29),
      new RouteSpace(681, 675, 29)
    ], true),
    25 => new Route(6, 14, ORANGE, [
      new RouteSpace(231, 495, 149),
      new RouteSpace(295, 475, 178)
    ]),
    26 => new Route(23, 15, ORANGE, [
      new RouteSpace(358, 274, 67),
      new RouteSpace(333, 216, 67),
      new RouteSpace(308, 158, 67),
      new RouteSpace(283, 99, 67)
    ]),
    27 => new Route(24, 11, ORANGE, [
      new RouteSpace(190, 999, 42),
      new RouteSpace(227, 1054, 69),
      new RouteSpace(193, 1088, 160)
    ]),
    28 => new Route(30, 18, ORANGE, [
      new RouteSpace(507, 566, 163),
      new RouteSpace(566, 540, 150),
      new RouteSpace(621, 505, 144)
    ]),
    29 => new Route(27, 44, ORANGE, [
      new RouteSpace(788, 580, 53),
      new RouteSpace(847, 610, 4),
      new RouteSpace(910, 589, 140)
    ]),
    30 => new Route(46, 10, ORANGE, [
      new RouteSpace(971, 705, 138),
      new RouteSpace(1012, 655, 121)
    ]),
    31 => new Route(37, 26, ORANGE, [
      new RouteSpace(1564, 321, 6),
      new RouteSpace(1628, 303, 142)
    ]),
    32 => new Route(38, 2, ORANGE, [
      new RouteSpace(1363, 1100, 3),
      new RouteSpace(1427, 1099, 174),
      new RouteSpace(1491, 1079, 152)
    ], true),
    33 => new Route(22, 24, PINK, [
      new RouteSpace(30, 961, 90),
      new RouteSpace(49, 918, 180),
      new RouteSpace(114, 939, 35)
    ]),
    34 => new Route(29, 6, PINK, [
      new RouteSpace(312, 731, 90),
      new RouteSpace(308, 667, 83),
      new RouteSpace(285, 604, 56),
      new RouteSpace(236, 563, 21)
    ]),
    35 => new Route(14, 14, PINK, [
      new RouteSpace(387, 511, 46)
    ]),
    36 => new Route(18, 27, PINK, [
      new RouteSpace(669, 510, 74),
      new RouteSpace(701, 548, 163)
    ]),
    37 => new Route(5, 43, PINK, [
      new RouteSpace(897, 349, 161),
      new RouteSpace(962, 334, 173),
      new RouteSpace(1027, 330, 180),
      new RouteSpace(1091, 334, 9)
    ]),
    38 => new Route(10, 35, PINK, [
      new RouteSpace(1052, 662, 82),
      new RouteSpace(1060, 725, 81),
      new RouteSpace(1069, 789, 81)
    ]),
    39 => new Route(40, 3, PINK, [
      new RouteSpace(1164, 881, 133),
      new RouteSpace(1138, 942, 97),
      new RouteSpace(1150, 1007, 63)
    ]),
    40 => new Route(47, 25, PINK, [
      new RouteSpace(631, 700, 104),
      new RouteSpace(615, 762, 104)
    ], true),
    41 => new Route(24, 4, YELLOW, [
      new RouteSpace(228, 979, 2),
      new RouteSpace(293, 981, 3)
    ]),
    42 => new Route(30, 8, YELLOW, [
      new RouteSpace(445, 506, 117),
      new RouteSpace(474, 450, 118)
    ]),
    43 => new Route(1, 17, YELLOW, [
      new RouteSpace(543, 282, 104),
      new RouteSpace(590, 272, 13),
      new RouteSpace(646, 308, 51)
    ]),
    44 => new Route(20, 41, YELLOW, [
      new RouteSpace(831, 117, 134),
      new RouteSpace(878, 72, 139),
      new RouteSpace(931, 35, 152)
    ]),
    45 => new Route(45, 37, YELLOW, [
      new RouteSpace(1379, 280, 126),
      new RouteSpace(1422, 257, 36),
      new RouteSpace(1474, 295, 36)
    ]),
    46 => new Route(5, 43, YELLOW, [
      new RouteSpace(900, 374, 161),
      new RouteSpace(964, 360, 172),
      new RouteSpace(1028, 355, 180),
      new RouteSpace(1093, 360, 9)
    ]),
    47 => new Route(12, 9, YELLOW, [
      new RouteSpace(1373, 904, 68),
      new RouteSpace(1348, 845, 67),
      new RouteSpace(1322, 785, 67)
    ]),
    48 => new Route(47, 27, YELLOW, [
      new RouteSpace(673, 621, 138),
      new RouteSpace(721, 577, 139)
    ], true),
    49 => new Route(6, 30, BLACK, [
      new RouteSpace(241, 535, 6),
      new RouteSpace(305, 542, 6),
      new RouteSpace(370, 550, 7)
    ]),
    50 => new Route(23, 15, BLACK, [
      new RouteSpace(338, 284, 67),
      new RouteSpace(313, 225, 67),
      new RouteSpace(288, 167, 67),
      new RouteSpace(263, 108, 67)
    ]),
    51 => new Route(24, 29, BLACK, [
      new RouteSpace(175, 926, 129),
      new RouteSpace(217, 879, 135),
      new RouteSpace(269, 837, 147)
    ], true),
    52 => new Route(33, 42, BLACK, [
      new RouteSpace(803, 823, 79),
      new RouteSpace(791, 759, 79)
    ]),
    53 => new Route(2, 16, BLACK, [
      new RouteSpace(1573, 1073, 31),
      new RouteSpace(1637, 1084, 171),
      new RouteSpace(1677, 1057, 80)
    ]),
    54 => new Route(13, 32, BLACK, [
      new RouteSpace(1068, 186, 108),
      new RouteSpace(1099, 128, 127),
      new RouteSpace(1152, 90, 160)
    ]),
    55 => new Route(1, 1, BLACK, [
      new RouteSpace(517, 367, 114)
    ]),
    56 => new Route(18, 5, BLACK, [
      new RouteSpace(694, 443, 154),
      new RouteSpace(753, 416, 153),
      new RouteSpace(811, 388, 154)
    ]),
    57 => new Route(1, 18, WHITE, [
      new RouteSpace(576, 368, 45),
      new RouteSpace(621, 413, 45)
    ]),
    58 => new Route(18, 30, WHITE, [
      new RouteSpace(608, 483, 144),
      new RouteSpace(554, 519, 150),
      new RouteSpace(495, 545, 162)
    ]),
    59 => new Route(33, 7, WHITE, [
      new RouteSpace(854, 853, 173),
      new RouteSpace(914, 877, 50)
    ]),
    60 => new Route(31, 26, WHITE, [
      new RouteSpace(1541, 81, 19),
      new RouteSpace(1599, 113, 40),
      new RouteSpace(1640, 165, 63),
      new RouteSpace(1658, 228, 85)
    ]),
    61 => new Route(36, 9, WHITE, [
      new RouteSpace(1517, 722, 51),
      new RouteSpace(1465, 681, 26),
      new RouteSpace(1398, 675, 166),
      new RouteSpace(1340, 707, 137)
    ]),
    62 => new Route(44, 44, WHITE, [
      new RouteSpace(985, 611, 31)
    ]),
    63 => new Route(20, 41, WHITE, [
      new RouteSpace(846, 134, 134),
      new RouteSpace(892, 89, 139),
      new RouteSpace(946, 53, 152)
    ]),
    64 => new Route(29, 24, WHITE, [
      new RouteSpace(285, 853, 146),
      new RouteSpace(234, 894, 135),
      new RouteSpace(192, 942, 128)
    ], true),
    65 => new Route(23, 14, GRAY, [
      new RouteSpace(345, 366, 96),
      new RouteSpace(339, 430, 94, true)
    ]),
    66 => new Route(23, 14, GRAY, [
      new RouteSpace(368, 368, 96),
      new RouteSpace(361, 432, 95, true)
    ]),
    67 => new Route(23, 1, GRAY, [
      new RouteSpace(416, 320, 181, true),
      new RouteSpace(482, 324, 182, true)
    ]),
    68 => new Route(17, 20, GRAY, [
      new RouteSpace(695, 293, 125, true),
      new RouteSpace(730, 238, 125),
      new RouteSpace(766, 185, 124)
    ]),
    69 => new Route(17, 20, GRAY, [
      new RouteSpace(713, 306, 124, true),
      new RouteSpace(748, 251, 124),
      new RouteSpace(785, 197, 124)
    ]),
    70 => new Route(5, 13, GRAY, [
      new RouteSpace(863, 313, 99),
      new RouteSpace(889, 252, 126),
      new RouteSpace(946, 216, 168),
      new RouteSpace(1013, 216, 13)
    ]),
    71 => new Route(13, 43, GRAY, [
      new RouteSpace(1095, 252, 32),
      new RouteSpace(1131, 306, 76)
    ]),
    72 => new Route(32, 31, GRAY, [
      new RouteSpace(1247, 74, 0),
      new RouteSpace(1315, 75, 179),
      new RouteSpace(1379, 74, 179),
      new RouteSpace(1441, 74, 0)
    ]),
    73 => new Route(45, 21, GRAY, [
      new RouteSpace(1378, 339, 37),
      new RouteSpace(1406, 397, 91)
    ]),
    74 => new Route(43, 21, GRAY, [
      new RouteSpace(1172, 386, 47),
      new RouteSpace(1227, 423, 23),
      new RouteSpace(1290, 432, 176),
      new RouteSpace(1356, 431, 5)
    ]),
    75 => new Route(21, 19, GRAY, [
      new RouteSpace(1421, 492, 68),
      new RouteSpace(1464, 542, 31),
      new RouteSpace(1529, 561, 182),
      new RouteSpace(1593, 553, 163)
    ]),
    76 => new Route(19, 26, GRAY, [
      new RouteSpace(1672, 503, 133),
      new RouteSpace(1703, 446, 107),
      new RouteSpace(1708, 379, 82),
      new RouteSpace(1687, 316, 64)
    ]),
    77 => new Route(34, 36, GRAY, [
      new RouteSpace(1664, 623, 9),
      new RouteSpace(1600, 615, 9),
      new RouteSpace(1574, 655, 99),
      new RouteSpace(1563, 719, 99)
    ]),
    78 => new Route(34, 39, GRAY, [
      new RouteSpace(1708, 678, 94),
      new RouteSpace(1705, 742, 93)
    ]),
    79 => new Route(39, 36, GRAY, [
      new RouteSpace(1655, 782, 9),
      new RouteSpace(1592, 772, 9, true)
    ]),
    80 => new Route(36, 16, GRAY, [
      new RouteSpace(1553, 811, 90, true),
      new RouteSpace(1557, 876, 81, true),
      new RouteSpace(1580, 936, 55),
      new RouteSpace(1626, 982, 35)
    ]),
    81 => new Route(36, 12, GRAY, [
      new RouteSpace(1528, 810, 90, true),
      new RouteSpace(1506, 873, 129, true),
      new RouteSpace(1463, 897, 217),
      new RouteSpace(1419, 921, 127)
    ]),
    82 => new Route(21, 9, GRAY, [
      new RouteSpace(1369, 504, 108),
      new RouteSpace(1348, 567, 108),
      new RouteSpace(1328, 627, 108),
      new RouteSpace(1309, 688, 109)
    ]),
    83 => new Route(38, 28, GRAY, [
      new RouteSpace(1267, 1097, 0, true),
      new RouteSpace(1202, 1097, 1, true),
      new RouteSpace(1136, 1095, 180),
      new RouteSpace(1073, 1095, 180),
      new RouteSpace(1007, 1095, 180),
      new RouteSpace(942, 1094, 180)
    ]),
    84 => new Route(38, 3, GRAY, [
      new RouteSpace(1275, 1061, 25),
      new RouteSpace(1210, 1049, 180, true)
    ]),
    85 => new Route(3, 7, GRAY, [
      new RouteSpace(1121, 1062, 173),
      new RouteSpace(1054, 1056, 21),
      new RouteSpace(1007, 1011, 67),
      new RouteSpace(981, 951, 67, true)
    ]),
    86 => new Route(7, 28, GRAY, [
      new RouteSpace(959, 959, 67),
      new RouteSpace(946, 1021, 135),
      new RouteSpace(903, 1072, 130, true)
    ]),
    87 => new Route(28, 33, GRAY, [
      new RouteSpace(885, 1058, 128, true),
      new RouteSpace(906, 996, 91),
      new RouteSpace(886, 933, 53),
      new RouteSpace(838, 890, 29)
    ]),
    88 => new Route(42, 46, GRAY, [
      new RouteSpace(827, 696, 165),
      new RouteSpace(892, 706, 214)
    ]),
    89 => new Route(46, 44, GRAY, [
      new RouteSpace(935, 682, 95),
      new RouteSpace(940, 617, 94)
    ]),
    90 => new Route(25, 30, GRAY, [
      new RouteSpace(567, 774, 55),
      new RouteSpace(518, 730, 30),
      new RouteSpace(467, 686, 55),
      new RouteSpace(446, 623, 87)
    ]),
    91 => new Route(25, 4, GRAY, [
      new RouteSpace(537, 837, 160),
      new RouteSpace(478, 864, 149),
      new RouteSpace(426, 903, 139),
      new RouteSpace(381, 949, 132)
    ]),
    92 => new Route(30, 47, GRAY, [
      new RouteSpace(481, 608, 59),
      new RouteSpace(529, 652, 27),
      new RouteSpace(593, 665, -0)
    ], true),
    93 => new Route(25, 33, GRAY, [
      new RouteSpace(635, 797, 144),
      new RouteSpace(687, 759, 144),
      new RouteSpace(730, 781, 52),
      new RouteSpace(768, 832, 53)
    ], true),
    94 => new Route(35, 40, GRAY, [
      new RouteSpace(1125, 811, 153),
      new RouteSpace(1172, 810, 64)
    ], true),
    95 => new Route(40, 9, GRAY, [
      new RouteSpace(1241, 829, 165),
      new RouteSpace(1283, 779, 96)
    ], true),
    96 => new Route(10, 9, GRAY, [
      new RouteSpace(1085, 629, 29),
      new RouteSpace(1142, 660, 25),
      new RouteSpace(1199, 690, 27),
      new RouteSpace(1256, 720, 27)
    ], true),
    97 => new Route(38, 12, GRAY, [
      new RouteSpace(1334, 1051, 117),
      new RouteSpace(1364, 994, 117)
    ], true),
    98 => new Route(12, 2, GRAY, [
      new RouteSpace(1433, 982, 36),
      new RouteSpace(1490, 1025, 36)
    ], true),
    99 => new Route(10, 21, GRAY, [
      new RouteSpace(1064, 573, 133),
      new RouteSpace(1112, 530, 145),
      new RouteSpace(1170, 496, 153),
      new RouteSpace(1228, 470, 160),
      new RouteSpace(1292, 457, 180),
      new RouteSpace(1358, 458, 4)
    ], true),
    100 => new Route(41, 31, GRAY, [
      new RouteSpace(1018, 58, 52),
      new RouteSpace(1065, 48, 142),
      new RouteSpace(1128, 27, 180),
      new RouteSpace(1193, 27, 180),
      new RouteSpace(1258, 27, 180),
      new RouteSpace(1322, 27, 180),
      new RouteSpace(1386, 27, 180),
      new RouteSpace(1450, 39, 22)
    ], true),
    101 => new Route(29, 4, GRAY, [
      new RouteSpace(331, 873, 84),
      new RouteSpace(338, 938, 85)
    ], true)
  ];
}