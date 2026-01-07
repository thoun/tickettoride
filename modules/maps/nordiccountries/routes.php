<?php

require_once(__DIR__.'/../../php/objects/route.php');

/**
 * Route on the map.
 * For double routes, there is 2 instances of Route.
 * For cities (from/to), it's always low id to high id.
 */
function getRoutes() {
  $routes = [
    1 => new Route(1, 2, PINK, [
      new RouteSpace(453, 1608, 61),
    ]),
    2 => new Route(1, 6, GRAY, [
      new RouteSpace(475, 1563, 1),
      new RouteSpace(501, 1520, -80),
    ], locomotives: 1),
    3 => new Route(1, 15, RED, [
      new RouteSpace(391, 1563, -158),
      new RouteSpace(343, 1520, -120),
    ], locomotives: 1),
    4 => new Route(1, 24, WHITE, [
      new RouteSpace(417, 1519, -100),
      new RouteSpace(407, 1455, -100),
      new RouteSpace(396, 1391, -100),
    ], locomotives: 1),
    5 => new Route(2, 14, GRAY, [
      new RouteSpace(533, 1644, 0),
    ], locomotives: 1),
    6 => new Route(3, 4, BLACK, [
      new RouteSpace(151, 1063, 179),
      new RouteSpace(99, 1091, 120),
      new RouteSpace(74, 1148, 107),
      new RouteSpace(66, 1210, 93),
      new RouteSpace(78, 1271, 61),
    ], locomotives: 2),
    7 => new Route(3, 18, YELLOW, [
      new RouteSpace(234, 1105, 44),
      new RouteSpace(283, 1153, 44),
    ], tunnel: true),
    8 => new Route(3, 33, WHITE, [
      new RouteSpace(191, 1026, -89),
      new RouteSpace(230, 989, 1),
    ], locomotives: 1),
    9 => new Route(4, 24, BLUE, [
      new RouteSpace(145, 1287, -33),
      new RouteSpace(210, 1260, -12),
      new RouteSpace(280, 1262, 12),
      new RouteSpace(345, 1291, 32),
    ], tunnel: true),
    10 => new Route(4, 24, RED, [
      new RouteSpace(157, 1308, -33),
      new RouteSpace(215, 1285, -12),
      new RouteSpace(275, 1287, 12),
      new RouteSpace(333, 1313, 32),
    ], tunnel: true),
    11 => new Route(4, 29, PINK, [
      new RouteSpace(104, 1379, 83),
      new RouteSpace(132, 1436, 49),
    ], locomotives: 1),
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
    ], locomotives: 1),
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
    23 => new Route(7, 30, GRAY, [
      new RouteSpace(911, 1076, 140),
      new RouteSpace(862, 1116, 140),
      new RouteSpace(814, 1156, 140),
      new RouteSpace(764, 1196, 140),
    ], locomotives: 2),
    24 => new Route(7, 30, YELLOW, [
      new RouteSpace(897, 1056, 140),
      new RouteSpace(847, 1097, 140),
      new RouteSpace(799, 1137, 140),
      new RouteSpace(750, 1178, 141),
    ], locomotives: 1),
    25 => new Route(7, 35, PINK, [
      new RouteSpace(981, 1058, 42),
      new RouteSpace(998, 1103, 133),
    ], locomotives: 1),
    26 => new Route(7, 32, ORANGE, [
      new RouteSpace(889, 976, -135),
    ]),
    27 => new Route(7, 36, WHITE, [
      new RouteSpace(882, 1022, -181),
    ]),
    28 => new Route(8, 12, GREEN, [
      new RouteSpace(560, 87, 42),
      new RouteSpace(616, 123, 22),
    ], locomotives: 1),
    29 => new Route(8, 34, PINK, [
      new RouteSpace(490, 74, 146),
      new RouteSpace(440, 114, 140),
      new RouteSpace(399, 159, 128),
      new RouteSpace(365, 214, 117),
    ], locomotives: 2),
    30 => new Route(9, 16, PINK, [
      new RouteSpace(1044, 774, -107),
      new RouteSpace(1001, 723, -154),
    ]),
    31 => new Route(9, 17, YELLOW, [
      new RouteSpace(1021, 847, 140),
      new RouteSpace(971, 891, 140),
    ]),
    32 => new Route(10, 16, GREEN, [
      new RouteSpace(892, 617, 92),
      new RouteSpace(918, 673, 39),
    ]),
    33 => new Route(10, 19, BLUE, [
      new RouteSpace(942, 592, 30),
    ]),
    34 => new Route(10, 25, YELLOW, [
      new RouteSpace(852, 567, 171),
      new RouteSpace(785, 579, 171),
    ]),
    35 => new Route(11, 14, GREEN, [
      new RouteSpace(688, 1575, 155),
      new RouteSpace(629, 1601, 155),
    ], locomotives: 1),
    36 => new Route(11, 14, BLUE, [
      new RouteSpace(696, 1599, 155),
      new RouteSpace(638, 1624, 155),
    ], locomotives: 1),
    37 => new Route(11, 23, YELLOW, [
      new RouteSpace(743, 1510, -98),
      new RouteSpace(734, 1448, -98),
      new RouteSpace(726, 1384, -98),
    ]),
    38 => new Route(11, 23, WHITE, [
      new RouteSpace(718, 1512, -98),
      new RouteSpace(710, 1448, -98),
      new RouteSpace(701, 1385, -98),
    ]),
    39 => new Route(12, 21, WHITE, [
      new RouteSpace(678, 94, -53),
      new RouteSpace(734, 69, 0),
      new RouteSpace(796, 75, 11),
    ], locomotives: 1),
    40 => new Route(12, 28, BLUE, [
      new RouteSpace(697, 163, 41),
      new RouteSpace(740, 208, 56),
      new RouteSpace(762, 271, 83),
      new RouteSpace(753, 332, 113),
      new RouteSpace(720, 385, 131),
    ]),
    41 => new Route(13, 22, PINK, [
      new RouteSpace(357, 409, -162),
    ], tunnel: true),
    42 => new Route(13, 22, WHITE, [
      new RouteSpace(350, 433, -162),
    ], tunnel: true),
    43 => new Route(15, 24, BLACK, [
      new RouteSpace(330, 1427, -84),
      new RouteSpace(353, 1366, -56),
    ]),
    44 => new Route(15, 29, GREEN, [
      new RouteSpace(283, 1465, -176),
      new RouteSpace(218, 1461, -176),
    ], tunnel: true),
    45 => new Route(15, 29, ORANGE, [
      new RouteSpace(294, 1509, -228),
      new RouteSpace(239, 1531, -178),
      new RouteSpace(184, 1506, -132),
    ], locomotives: 1),
    46 => new Route(16, 17, WHITE, [
      new RouteSpace(952, 752, 98),
      new RouteSpace(932, 813, 118),
      new RouteSpace(923, 875, 77),
    ]),
    47 => new Route(16, 19, BLACK, [
      new RouteSpace(968, 661, -73),
    ]),
    48 => new Route(16, 25, GRAY, [
      new RouteSpace(888, 688, -148),
      new RouteSpace(836, 654, -148),
      new RouteSpace(783, 621, -148),
    ]),
    49 => new Route(16, 38, GRAY, [
      new RouteSpace(914, 727, 158),
      new RouteSpace(855, 751, 158),
      new RouteSpace(797, 774, 158),
      new RouteSpace(739, 799, 158),
    ]),
    50 => new Route(17, 32, BLUE, [
      new RouteSpace(890, 922, 179),
    ]),
    51 => new Route(18, 24, PINK, [
      new RouteSpace(353, 1217, 45),
      new RouteSpace(384, 1270, 78),
    ], tunnel: true),
    52 => new Route(18, 33, ORANGE, [
      new RouteSpace(339, 1146, -74),
      new RouteSpace(341, 1085, -104),
      new RouteSpace(311, 1031, -133),
    ], tunnel: true),
    53 => new Route(19, 21, GRAY, [
      new RouteSpace(1006, 573, -74),
      new RouteSpace(1025, 512, -76),
      new RouteSpace(1035, 451, -87),
      new RouteSpace(1035, 386, -96),
      new RouteSpace(1023, 324, -106),
      new RouteSpace(1002, 263, -116),
      new RouteSpace(970, 209, -126),
      new RouteSpace(928, 160, -133),
      new RouteSpace(880, 118, -142),
    ], canPayWithAnySetOfCards: 4), // he Murmansk-Lieksa route is an exception. On this route, a player can use any four cards (including locomotives) as a substitute for a card of any color.
    54 => new Route(20, 22, ORANGE, [
      new RouteSpace(249, 607, -121),
      new RouteSpace(229, 546, -98),
      new RouteSpace(236, 484, -70),
      new RouteSpace(270, 431, -46),
    ], locomotives: 2),
    55 => new Route(20, 33, GREEN, [
      new RouteSpace(286, 693, 90),
      new RouteSpace(286, 754, 90),
      new RouteSpace(286, 817, 90),
      new RouteSpace(287, 879, 90),
      new RouteSpace(287, 943, 90),
    ], tunnel: true),
    56 => new Route(20, 33, RED, [
      new RouteSpace(241, 667, 141),
      new RouteSpace(203, 716, 116),
      new RouteSpace(188, 776, 95),
      new RouteSpace(188, 837, 84),
      new RouteSpace(202, 896, 65),
      new RouteSpace(240, 946, 42),
    ], locomotives: 2),
    57 => new Route(22, 34, YELLOW, [
      new RouteSpace(277, 374, -129),
      new RouteSpace(265, 315, -73),
      new RouteSpace(303, 267, -25),
    ], locomotives: 1),
    58 => new Route(23, 26, GRAY, [
      new RouteSpace(647, 1325, -184),
      new RouteSpace(584, 1311, -155),
    ]),
    59 => new Route(23, 30, RED, [
      new RouteSpace(721, 1285, -81),
    ]),
    60 => new Route(23, 30, ORANGE, [
      new RouteSpace(696, 1281, -81),
    ]),
    61 => new Route(24, 26, GREEN, [
      new RouteSpace(442, 1319, -20),
      new RouteSpace(503, 1297, -20),
    ]),
    62 => new Route(24, 26, YELLOW, [
      new RouteSpace(436, 1294, -20),
      new RouteSpace(496, 1273, -20),
    ]),
    63 => new Route(25, 28, ORANGE, [
      new RouteSpace(736, 531, -113),
      new RouteSpace(710, 474, -113),
    ]),
    64 => new Route(25, 37, WHITE, [
      new RouteSpace(708, 559, -135),
    ]),
    65 => new Route(25, 38, BLACK, [
      new RouteSpace(736, 640, 104),
      new RouteSpace(720, 702, 103),
      new RouteSpace(707, 763, 104),
    ]),
    66 => new Route(26, 30, BLACK, [
      new RouteSpace(594, 1266, -20),
      new RouteSpace(654, 1244, -19),
    ]),
    67 => new Route(26, 30, PINK, [
      new RouteSpace(585, 1241, -19),
      new RouteSpace(644, 1220, -19),
    ]),
    68 => new Route(26, 31, ORANGE, [
      new RouteSpace(540, 1210, -89),
      new RouteSpace(541, 1147, -89),
      new RouteSpace(542, 1086, -89),
      new RouteSpace(543, 1022, -89),
    ]),
    69 => new Route(27, 31, GREEN, [
      new RouteSpace(451, 965, 40),
      new RouteSpace(511, 978, -16),
    ]),
    70 => new Route(27, 33, BLACK, [
      new RouteSpace(382, 963, 146),
      new RouteSpace(326, 986, 167),
    ], tunnel: true),
    71 => new Route(28, 37, RED, [
      new RouteSpace(673, 473, 93),
    ]),
    72 => new Route(30, 35, GREEN, [
      new RouteSpace(765, 1235, 1),
      new RouteSpace(828, 1228, -13),
      new RouteSpace(884, 1205, -33),
      new RouteSpace(935, 1166, -39),
    ], locomotives: 2),
    73 => new Route(30, 31, GRAY, [
      new RouteSpace(687, 1156, -119),
      new RouteSpace(658, 1103, -119),
      new RouteSpace(627, 1047, -119),
      new RouteSpace(597, 992, -119),
    ]),
    74 => new Route(30, 31, GRAY, [
      new RouteSpace(666, 1168, -119),
      new RouteSpace(636, 1114, -119),
      new RouteSpace(607, 1058, -119),
      new RouteSpace(577, 1004, -119),
    ]),
    75 => new Route(30, 36, BLUE, [
      new RouteSpace(725, 1152, -70),
      new RouteSpace(751, 1096, -57),
      new RouteSpace(796, 1052, -35),
    ], locomotives: 1),
    76 => new Route(31, 39, YELLOW, [
      new RouteSpace(581, 915, -78),
      new RouteSpace(595, 854, -78),
      new RouteSpace(609, 791, -78),
    ]),
    77 => new Route(31, 39, PINK, [
      new RouteSpace(557, 909, -78),
      new RouteSpace(571, 849, -78),
      new RouteSpace(584, 787, -78),
    ]),
    78 => new Route(31, 38, BLUE, [
      new RouteSpace(621, 946, -8),
      new RouteSpace(676, 918, -48),
      new RouteSpace(696, 860, -92),
    ], locomotives: 1),
    79 => new Route(32, 36, RED, [
      new RouteSpace(838, 974, 91),
    ]),
    80 => new Route(32, 38, PINK, [
      new RouteSpace(795, 891, -141),
      new RouteSpace(747, 853, -141),
    ]),
    81 => new Route(38, 39, GRAY, [
      new RouteSpace(658, 777, -147),
    ], locomotives: 1),
  ];

  // Any three cards can be used as a substitute for a Locomotive card (A player can play additional Locomotive cards as a substitute for a color card)
  foreach ($routes as &$route) {
    if ($route->locomotives > 0) {
      $route->canPayWithAnySetOfCards = 3;
    }
  }

  return $routes;
}

