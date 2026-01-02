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
      new RouteSpace(479, 698, -87),
    ]),
    2 => new Route(1, 7, RED, [
      new RouteSpace(460, 697, -87),
    ]),
    3 => new Route(1, 13, ORANGE, [
      new RouteSpace(467, 588, 83),
      new RouteSpace(459, 529, 83),
    ]),
    4 => new Route(1, 19, BLUE, [
      new RouteSpace(420, 619, 15),
    ]),
    5 => new Route(1, 19, WHITE, [
      new RouteSpace(415, 637, 15),
    ]),
    6 => new Route(1, 23, GREEN, [
      new RouteSpace(522, 648, 0),
      new RouteSpace(581, 648, 1),
    ]),
    7 => new Route(1, 26, GRAY, [
      new RouteSpace(509, 598, -55),
      new RouteSpace(559, 565, -17),
    ]),
    8 => new Route(2, 9, WHITE, [
      new RouteSpace(232, 816, 73),
      new RouteSpace(234, 879, -77),
      new RouteSpace(232, 941, 82),
    ]),
    9 => new Route(2, 21, PINK, [
      new RouteSpace(211, 707, -85),
      new RouteSpace(216, 647, -85),
      new RouteSpace(221, 589, -85),
    ]),
    10 => new Route(2, 21, BLACK, [
      new RouteSpace(231, 707, -85),
      new RouteSpace(236, 647, -85),
      new RouteSpace(241, 589, -85),
    ]),
    11 => new Route(2, 24, YELLOW, [
      new RouteSpace(265, 776, 15),
      new RouteSpace(315, 813, 57),
    ]),
    12 => new Route(2, 36, GREEN, [
      new RouteSpace(265, 738, -30),
    ]),
    13 => new Route(3, 4, ORANGE, [
      new RouteSpace(510, 340, 49),
      new RouteSpace(543, 394, 70),
    ]),
    14 => new Route(3, 6, WHITE, [
      new RouteSpace(409, 292, -1),
      new RouteSpace(350, 293, -1),
    ]),
    15 => new Route(3, 6, YELLOW, [
      new RouteSpace(412, 312, 0),
      new RouteSpace(351, 313, 0),
    ]),
    16 => new Route(3, 13, GRAY, [
      new RouteSpace(454, 356, -84),
      new RouteSpace(448, 416, -84),
    ]),
    17 => new Route(3, 13, GRAY, [
      new RouteSpace(474, 357, -84),
      new RouteSpace(469, 419, -86),
    ]),
    18 => new Route(3, 25, BLACK, [
      new RouteSpace(445, 253, 50),
      new RouteSpace(401, 212, 41),
      new RouteSpace(351, 176, 30),
      new RouteSpace(293, 150, 20),
    ]),
    19 => new Route(4, 13, WHITE, [
      new RouteSpace(509, 461, -13),
    ]),
    20 => new Route(4, 26, GRAY, [
      new RouteSpace(589, 500, 65),
    ]),
    21 => new Route(4, 31, GREEN, [
      new RouteSpace(621, 467, 21),
      new RouteSpace(677, 492, 29),
      new RouteSpace(728, 526, 40),
      new RouteSpace(772, 571, 48),
    ]),
    22 => new Route(5, 16, BLUE, [
      new RouteSpace(537, 1126, -23),
      new RouteSpace(482, 1150, -23),
    ]),
    23 => new Route(5, 17, GREEN, [
      new RouteSpace(572, 1049, 86),
      new RouteSpace(566, 990, 85),
    ]),
    24 => new Route(5, 17, YELLOW, [
      new RouteSpace(590, 1046, 84),
      new RouteSpace(585, 987, 84),
    ]),
    25 => new Route(5, 27, RED, [
      new RouteSpace(591, 1158, 84),
    ]),
    26 => new Route(5, 39, ORANGE, [
      new RouteSpace(704, 1062, -25),
      new RouteSpace(646, 1091, -27),
    ]),
    27 => new Route(5, 39, BLACK, [
      new RouteSpace(694, 1045, -26),
      new RouteSpace(638, 1073, -28),
    ]),
    28 => new Route(6, 13, BLUE, [
      new RouteSpace(323, 353, 60),
      new RouteSpace(361, 402, 44),
      new RouteSpace(408, 442, 38),
    ]),
    29 => new Route(6, 21, GREEN, [
      new RouteSpace(279, 362, -76),
      new RouteSpace(267, 420, -75),
      new RouteSpace(253, 478, -78),
    ]),
    30 => new Route(6, 25, GRAY, [
      new RouteSpace(265, 253, 72),
      new RouteSpace(247, 197, 71),
    ]),
    31 => new Route(6, 25, GRAY, [
      new RouteSpace(286, 249, 72),
      new RouteSpace(266, 189, 71),
    ]),
    32 => new Route(6, 37, PINK, [
      new RouteSpace(253, 345, -51),
      new RouteSpace(216, 392, -53),
    ]),
    33 => new Route(7, 8, GRAY, [
      new RouteSpace(523, 738, 0),
      new RouteSpace(583, 738, 0),
    ]),
    34 => new Route(7, 8, GRAY, [
      new RouteSpace(523, 759, 0),
      new RouteSpace(583, 759, 0),
    ]),
    35 => new Route(7, 24, BLUE, [
      new RouteSpace(399, 834, -17),
      new RouteSpace(446, 796, -64),
    ]),
    36 => new Route(7, 36, BLACK, [
      new RouteSpace(364, 721, 14),
      new RouteSpace(424, 736, 16),
    ]),
    37 => new Route(8, 10, YELLOW, [
      new RouteSpace(696, 732, -23),
      new RouteSpace(754, 715, -11),
      new RouteSpace(815, 719, 18),
      new RouteSpace(869, 745, 34),
    ]),
    38 => new Route(8, 23, WHITE, [
      new RouteSpace(635, 696, -85),
    ]),
    39 => new Route(8, 23, PINK, [
      new RouteSpace(655, 698, -85),
    ]),
    40 => new Route(8, 35, BLACK, [
      new RouteSpace(619, 803, -80),
    ]),
    41 => new Route(8, 35, RED, [
      new RouteSpace(639, 803, -80),
    ]),
    42 => new Route(9, 11, GRAY, [
      new RouteSpace(212, 1058, 107),
      new RouteSpace(197, 1121, 96),
      new RouteSpace(200, 1185, 82),
      new RouteSpace(214, 1248, 73),
      new RouteSpace(244, 1306, 53),
      new RouteSpace(291, 1352, 37),
    ], locomotives: 2),
    43 => new Route(9, 22, GRAY, [
      new RouteSpace(30, 672, 83),
      new RouteSpace(42, 734, 75),
      new RouteSpace(65, 797, 69),
      new RouteSpace(95, 854, 62),
      new RouteSpace(131, 908, 53),
      new RouteSpace(172, 959, 51),
    ], locomotives: 2),
    44 => new Route(9, 22, GRAY, [
      new RouteSpace(49, 666, 82),
      new RouteSpace(61, 727, 75),
      new RouteSpace(84, 789, 69),
      new RouteSpace(112, 845, 61),
      new RouteSpace(150, 898, 53),
      new RouteSpace(192, 950, 51),
    ], locomotives: 2),
    45 => new Route(9, 29, BLACK, [
      new RouteSpace(294, 977, -20),
    ]),
    46 => new Route(9, 30, GREEN, [
      new RouteSpace(245, 1054, 81),
    ]),
    47 => new Route(9, 33, BLUE, [
      new RouteSpace(293, 1026, 31),
    ]),
    48 => new Route(10, 12, GRAY, [
      new RouteSpace(972, 794, 8),
      new RouteSpace(1035, 785, -28),
    ], locomotives: 1),
    49 => new Route(10, 12, GRAY, [
      new RouteSpace(974, 816, 8),
      new RouteSpace(1038, 806, -28),
    ], locomotives: 1),
    50 => new Route(10, 14, GRAY, [
      new RouteSpace(951, 669, -70),
      new RouteSpace(930, 724, -70),
    ]),
    51 => new Route(10, 14, GRAY, [
      new RouteSpace(948, 732, -71),
      new RouteSpace(969, 674, -72),
    ]),
    52 => new Route(10, 27, GRAY, [
      new RouteSpace(926, 850, 90),
      new RouteSpace(917, 916, 104),
      new RouteSpace(897, 975, 116),
      new RouteSpace(860, 1031, 128),
      new RouteSpace(818, 1079, 132),
      new RouteSpace(772, 1124, 137),
      new RouteSpace(722, 1163, 144),
      new RouteSpace(667, 1199, 151),
    ], locomotives: 2),
    53 => new Route(10, 31, PINK, [
      new RouteSpace(884, 721, 53),
      new RouteSpace(847, 670, 53),
    ]),
    54 => new Route(10, 35, BLUE, [
      new RouteSpace(678, 841, -15),
      new RouteSpace(738, 824, -14),
      new RouteSpace(798, 809, -15),
      new RouteSpace(858, 794, -15),
    ]),
    55 => new Route(10, 39, RED, [
      new RouteSpace(895, 836, -70),
      new RouteSpace(872, 893, -63),
      new RouteSpace(840, 943, -52),
      new RouteSpace(796, 991, -45),
    ]),
    56 => new Route(11, 15, BLUE, [
      new RouteSpace(406, 1386, 5),
      new RouteSpace(466, 1391, 5),
      new RouteSpace(525, 1397, 4),
    ]),
    57 => new Route(11, 15, RED, [
      new RouteSpace(408, 1368, 5),
      new RouteSpace(467, 1373, 5),
      new RouteSpace(527, 1378, 5),
    ]),
    58 => new Route(11, 28, WHITE, [
      new RouteSpace(345, 1318, 77),
    ]),
    59 => new Route(11, 28, YELLOW, [
      new RouteSpace(327, 1323, 76),
    ]),
    60 => new Route(11, 34, GRAY, [
      new RouteSpace(336, 1437, 79),
      new RouteSpace(356, 1502, 63),
    ], locomotives: 1),
    61 => new Route(11, 34, GRAY, [
      new RouteSpace(356, 1433, 83),
      new RouteSpace(377, 1494, 62),
    ], locomotives: 1),
    62 => new Route(12, 14, WHITE, [
      new RouteSpace(1052, 704, 57),
      new RouteSpace(1012, 657, 44),
    ]),
    63 => new Route(12, 20, GRAY, [
      new RouteSpace(1094, 694, -80),
      new RouteSpace(1101, 633, 89),
      new RouteSpace(1099, 573, 87),
    ]),
    64 => new Route(13, 19, PINK, [
      new RouteSpace(410, 508, -38),
      new RouteSpace(374, 560, -76),
    ]),
    65 => new Route(13, 26, YELLOW, [
      new RouteSpace(505, 499, 25),
      new RouteSpace(561, 525, 25),
    ]),
    66 => new Route(14, 20, GRAY, [
      new RouteSpace(1047, 542, -37),
      new RouteSpace(998, 579, -37),
    ]),
    67 => new Route(14, 20, GRAY, [
      new RouteSpace(1059, 558, -37),
      new RouteSpace(1010, 596, -37),
    ]),
    68 => new Route(14, 31, BLUE, [
      new RouteSpace(916, 607, 1),
      new RouteSpace(857, 606, 1),
    ]),
    69 => new Route(14, 31, RED, [
      new RouteSpace(916, 626, 1),
      new RouteSpace(857, 626, 1),
    ]),
    70 => new Route(15, 27, GREEN, [
      new RouteSpace(598, 1332, -86),
      new RouteSpace(602, 1273, -85),
    ]),
    71 => new Route(15, 27, ORANGE, [
      new RouteSpace(580, 1332, -84),
      new RouteSpace(583, 1272, -85),
    ]),
    72 => new Route(15, 34, GRAY, [
      new RouteSpace(569, 1448, 112),
      new RouteSpace(540, 1502, 126),
      new RouteSpace(505, 1548, 132),
      new RouteSpace(460, 1568, 41),
    ]),
    73 => new Route(16, 27, WHITE, [
      new RouteSpace(483, 1188, 14),
      new RouteSpace(543, 1201, 11),
    ]),
    74 => new Route(16, 28, GRAY, [
      new RouteSpace(421, 1217, -69),
      new RouteSpace(374, 1258, -10),
    ]),
    75 => new Route(16, 30, RED, [
      new RouteSpace(370, 1154, 18),
      new RouteSpace(311, 1135, 17),
    ]),
    76 => new Route(16, 38, PINK, [
      new RouteSpace(446, 1118, -81),
    ]),
    77 => new Route(16, 38, BLACK, [
      new RouteSpace(426, 1118, -81),
    ]),
    78 => new Route(17, 29, PINK, [
      new RouteSpace(515, 945, -15),
      new RouteSpace(456, 958, -8),
      new RouteSpace(393, 959, 6),
    ]),
    79 => new Route(17, 38, GRAY, [
      new RouteSpace(528, 978, -46),
      new RouteSpace(485, 1023, -45),
    ]),
    80 => new Route(18, 25, RED, [
      new RouteSpace(152, 293, -62),
      new RouteSpace(180, 242, -61),
      new RouteSpace(209, 188, -62),
    ]),
    81 => new Route(18, 22, WHITE, [
      new RouteSpace(87, 376, 128),
      new RouteSpace(52, 428, 116),
      new RouteSpace(33, 486, 94),
      new RouteSpace(29, 549, 93),
    ]),
    82 => new Route(18, 32, ORANGE, [
      new RouteSpace(94, 287, 76),
      new RouteSpace(89, 224, 94),
      new RouteSpace(96, 165, -76),
    ]),
    83 => new Route(18, 32, YELLOW, [
      new RouteSpace(115, 288, 75),
      new RouteSpace(107, 224, 90),
      new RouteSpace(114, 164, -78),
    ]),
    84 => new Route(18, 37, BLUE, [
      new RouteSpace(157, 383, 59),
    ]),
    85 => new Route(18, 37, GREEN, [
      new RouteSpace(139, 394, 59),
    ]),
    86 => new Route(19, 21, RED, [
      new RouteSpace(339, 563, -113),
      new RouteSpace(289, 533, -182),
    ]),
    87 => new Route(19, 36, YELLOW, [
      new RouteSpace(328, 661, -66),
    ]),
    88 => new Route(19, 36, ORANGE, [
      new RouteSpace(348, 667, -66),
    ]),
    89 => new Route(21, 22, ORANGE, [
      new RouteSpace(76, 603, -8),
      new RouteSpace(138, 588, -23),
      new RouteSpace(194, 561, -31),
    ]),
    90 => new Route(22, 37, BLACK, [
      new RouteSpace(62, 560, -55),
      new RouteSpace(99, 511, -49),
      new RouteSpace(141, 464, -43),
    ]),
    91 => new Route(23, 26, BLUE, [
      new RouteSpace(623, 602, 71),
    ]),
    92 => new Route(23, 26, BLACK, [
      new RouteSpace(641, 596, 71),
    ]),
    93 => new Route(23, 31, GRAY, [
      new RouteSpace(693, 630, -9),
      new RouteSpace(751, 620, -10),
    ]),
    94 => new Route(23, 31, GRAY, [
      new RouteSpace(696, 648, -10),
      new RouteSpace(754, 638, -10),
    ]),
    95 => new Route(24, 29, RED, [
      new RouteSpace(346, 905, -85),
    ]),
    96 => new Route(24, 35, ORANGE, [
      new RouteSpace(403, 858, -1),
      new RouteSpace(461, 837, -37),
      new RouteSpace(576, 824, 34),
      new RouteSpace(516, 811, -10),
    ]),
    97 => new Route(24, 36, GRAY, [
      new RouteSpace(364, 804, -71),
      new RouteSpace(349, 749, -140),
    ]),
    98 => new Route(25, 32, GREEN, [
      new RouteSpace(181, 133, 14),
    ]),
    99 => new Route(25, 32, PINK, [
      new RouteSpace(185, 114, 14),
    ]),
    100 => new Route(27, 28, PINK, [
      new RouteSpace(376, 1283, 15),
      new RouteSpace(437, 1291, 0),
      new RouteSpace(498, 1279, -24),
      new RouteSpace(552, 1249, -37),
    ]),
    101 => new Route(27, 39, YELLOW, [
      new RouteSpace(648, 1181, -33),
      new RouteSpace(694, 1139, -54),
      new RouteSpace(730, 1087, -59),
    ]),
    102 => new Route(28, 30, BLACK, [
      new RouteSpace(294, 1221, -120),
      new RouteSpace(268, 1162, -109),
    ]),
    103 => new Route(29, 33, GRAY, [
      new RouteSpace(341, 1005, -89),
    ]),
    104 => new Route(30, 33, GRAY, [
      new RouteSpace(295, 1081, -35),
    ]),
    105 => new Route(30, 38, GRAY, [
      new RouteSpace(320, 1102, -13),
      new RouteSpace(383, 1090, -12),
    ]),
    106 => new Route(33, 38, ORANGE, [
      new RouteSpace(392, 1057, 7),
    ]),
    107 => new Route(35, 39, GREEN, [
      new RouteSpace(660, 885, 53),
      new RouteSpace(696, 936, 55),
      new RouteSpace(733, 986, 54),
    ]),
    108 => new Route(35, 39, WHITE, [
      new RouteSpace(644, 898, 53),
      new RouteSpace(680, 946, 53),
      new RouteSpace(716, 993, 53),
    ]),


  ];
}

/*
console.log(``.replace(/RouteSpace\((\d+), (\d+)/g, (_, x, y) => `RouteSpace(${Number(x) + 37}, ${Number(y) + 18}`))
*/
