<?php

require_once(__DIR__.'/../../php/objects/route.php');

/**
 * Route on the map.
 * For double routes, there is 2 instances of Route.
 * For cities (from/to), it's always low id to high id.
 */
function getRoutes() {
  return [
    1 => new Route(1, 2, PINK, [
      new RouteSpace(453, 1608, 61),
    ]),
    2 => new Route(1, 6, GRAY, [
      new RouteSpace(475, 1563, 1),
      new RouteSpace(501, 1520, -80),
    ], false, 1),
    3 => new Route(1, 15, RED, [
      new RouteSpace(391, 1563, -158),
      new RouteSpace(343, 1520, -120),
    ], false, 1),
    4 => new Route(1, 24, WHITE, [
      new RouteSpace(417, 1519, -100),
      new RouteSpace(407, 1455, -100),
      new RouteSpace(396, 1391, -100),
    ], false, 1),
    5 => new Route(2, 14, GRAY, [
      new RouteSpace(533, 1644, 0),
    ], false, 1),
    6 => new Route(3, 4, BLACK, [
      new RouteSpace(151, 1063, 179),
      new RouteSpace(99, 1091, 120),
      new RouteSpace(74, 1148, 107),
      new RouteSpace(66, 1210, 93),
      new RouteSpace(78, 1271, 61),
    ], false, 2),
    7 => new Route(3, 18, YELLOW, [
      new RouteSpace(234, 1105, 44),
      new RouteSpace(283, 1153, 44),
    ], true),
    8 => new Route(3, 33, WHITE, [
      new RouteSpace(191, 1026, -89),
      new RouteSpace(230, 989, 1),
    ], false, 1),
    9 => new Route(4, 24, BLUE, [
      new RouteSpace(145, 1287, -33),
      new RouteSpace(210, 1260, -12),
      new RouteSpace(280, 1262, 12),
      new RouteSpace(345, 1291, 32),
    ], true),
    10 => new Route(4, 24, RED, [
      new RouteSpace(157, 1308, -33),
      new RouteSpace(215, 1285, -12),
      new RouteSpace(275, 1287, 12),
      new RouteSpace(333, 1313, 32),
    ], true),
    11 => new Route(4, 29, PINK, [
      new RouteSpace(104, 1379, 83),
      new RouteSpace(132, 1436, 49),
    ], false, 1),
    12 => new Route(5, 13, BLACK, [
      new RouteSpace(545, 515, -174),
      new RouteSpace(485, 495, -148),
      new RouteSpace(439, 451, -127),
    ]),
    13 => new Route(5, 13, ORANGE, [
      new RouteSpace(532, 537, -174),
      new RouteSpace(473, 516, -148),
      new RouteSpace(424, 473, -127),
    ]),
    14 => new Route(5, 37, GREEN, [
      new RouteSpace(630, 528, -9),
    ]),
    15 => new Route(5, 39, RED, [
      new RouteSpace(598, 579, 83),
      new RouteSpace(605, 643, 83),
      new RouteSpace(614, 706, 83),
    ]),
    16 => new Route(5, 39, WHITE, [
      new RouteSpace(575, 579, 83),
      new RouteSpace(583, 644, 83),
      new RouteSpace(592, 708, 83),
    ]),
    17 => new Route(6, 14, BLACK, [
      new RouteSpace(541, 1523, 70),
      new RouteSpace(561, 1584, 70),
    ], false, 1),
    18 => new Route(6, 23, GRAY, [
      new RouteSpace(556, 1441, -36),
      new RouteSpace(607, 1402, -36),
      new RouteSpace(660, 1363, -36),
    ]),
    19 => new Route(6, 26, BLUE, [
      new RouteSpace(523, 1404, -78),
      new RouteSpace(537, 1342, -78),
    ]),
    20 => new Route(6, 24, ORANGE, [
      new RouteSpace(474, 1427, -130),
      new RouteSpace(434, 1379, -130),
    ]),
    21 => new Route(7, 9, RED, [
      new RouteSpace(990, 981, -40),
      new RouteSpace(1030, 929, -65),
      new RouteSpace(1051, 870, -72),
    ]),
    22 => new Route(7, 17, BLACK, [
      new RouteSpace(939, 971, -91),
    ]),
    23 => new Route(7, 35, PINK, [
      new RouteSpace(981, 1058, 42),
      new RouteSpace(998, 1103, 133),
    ], false, 1),
    24 => new Route(7, 32, ORANGE, [
      new RouteSpace(889, 976, -135),
    ]),
    25 => new Route(7, 36, WHITE, [
      new RouteSpace(882, 1022, -181),
    ]),
    26 => new Route(8, 12, GREEN, [
      new RouteSpace(560, 87, 42),
      new RouteSpace(616, 123, 22),
    ], false, 1),
    27 => new Route(8, 34, PINK, [
      new RouteSpace(490, 74, 146),
      new RouteSpace(440, 114, 140),
      new RouteSpace(399, 159, 128),
      new RouteSpace(365, 214, 117),
    ], false, 2),
    28 => new Route(9, 16, PINK, [
      new RouteSpace(1044, 774, -107),
      new RouteSpace(1001, 723, -154),
    ]),
    29 => new Route(9, 17, YELLOW, [
      new RouteSpace(1021, 847, 140),
      new RouteSpace(971, 891, 140),
    ]),
    30 => new Route(10, 16, GREEN, [
      new RouteSpace(892, 617, 92),
      new RouteSpace(918, 673, 39),
    ]),
    31 => new Route(10, 19, BLUE, [
      new RouteSpace(942, 592, 30),
    ]),
    32 => new Route(10, 25, YELLOW, [
      new RouteSpace(852, 567, 171),
      new RouteSpace(785, 579, 171),
    ]),
    33 => new Route(11, 14, GREEN, [
      new RouteSpace(688, 1575, 155),
      new RouteSpace(629, 1601, 155),
    ], false, 1),
    34 => new Route(11, 14, BLUE, [
      new RouteSpace(696, 1599, 155),
      new RouteSpace(638, 1624, 155),
    ], false, 1),
    35 => new Route(11, 23, YELLOW, [
      new RouteSpace(743, 1510, -98),
      new RouteSpace(734, 1448, -98),
      new RouteSpace(726, 1384, -98),
    ]),
    36 => new Route(11, 23, WHITE, [
      new RouteSpace(718, 1512, -98),
      new RouteSpace(710, 1448, -98),
      new RouteSpace(701, 1385, -98),
    ]),
    37 => new Route(12, 21, WHITE, [
      new RouteSpace(678, 94, -53),
      new RouteSpace(734, 69, 0),
      new RouteSpace(796, 75, 11),
    ], false, 1),
    38 => new Route(12, 28, BLUE, [
      new RouteSpace(697, 163, 41),
      new RouteSpace(740, 208, 56),
      new RouteSpace(762, 271, 83),
      new RouteSpace(753, 332, 113),
      new RouteSpace(720, 385, 131),
    ]),
    39 => new Route(13, 22, PINK, [
      new RouteSpace(357, 409, -162),
    ], true),
    40 => new Route(13, 22, WHITE, [
      new RouteSpace(350, 433, -162),
    ], true),
    41 => new Route(15, 24, BLACK, [
      new RouteSpace(330, 1427, -84),
      new RouteSpace(353, 1366, -56),
    ], true),
    42 => new Route(15, 29, GREEN, [
      new RouteSpace(283, 1465, -176),
      new RouteSpace(218, 1461, -176),
    ], true),
    43 => new Route(15, 29, ORANGE, [
      new RouteSpace(294, 1509, -228),
      new RouteSpace(239, 1531, -178),
      new RouteSpace(184, 1506, -132),
    ], false, 1),
    44 => new Route(16, 17, WHITE, [
      new RouteSpace(952, 752, 98),
      new RouteSpace(932, 813, 118),
      new RouteSpace(923, 875, 77),
    ]),
    45 => new Route(16, 19, BLACK, [
      new RouteSpace(968, 661, -73),
    ]),
    46 => new Route(16, 25, GRAY, [
      new RouteSpace(888, 688, -148),
      new RouteSpace(836, 654, -148),
      new RouteSpace(783, 621, -148),
    ]),
    47 => new Route(16, 38, GRAY, [
      new RouteSpace(914, 727, 158),
      new RouteSpace(855, 751, 158),
      new RouteSpace(797, 774, 158),
      new RouteSpace(739, 799, 158),
    ]),
    48 => new Route(17, 32, BLUE, [
      new RouteSpace(890, 922, 179),
    ]),
    49 => new Route(18, 24, PINK, [
      new RouteSpace(353, 1217, 45),
      new RouteSpace(384, 1270, 78),
    ], true),
    50 => new Route(18, 33, ORANGE, [
      new RouteSpace(339, 1146, -74),
      new RouteSpace(341, 1085, -104),
      new RouteSpace(311, 1031, -133),
    ], true),
    51 => new Route(19, 21, GRAY, [
      new RouteSpace(1006, 573, -74),
      new RouteSpace(1025, 512, -76),
      new RouteSpace(1035, 451, -87),
      new RouteSpace(1035, 386, -96),
      new RouteSpace(1023, 324, -106),
      new RouteSpace(1002, 263, -116),
      new RouteSpace(970, 209, -126),
      new RouteSpace(928, 160, -133),
      new RouteSpace(880, 118, -142),
    ]),
  ];
}

