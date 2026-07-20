<?php

use Bga\Games\TicketToRide\Objects\Route;
use Bga\Games\TicketToRide\Objects\RouteSpace;

/**
 * Routes on the Germany map.
 *
 * For parallel routes, there is one Route instance per printed track.
 * City ids are always ordered from low to high.
 */
function getRoutes() {
    return [
        1 => new Route(1, 26, GRAY, [
            new RouteSpace(702, 1483, 14),
            new RouteSpace(760, 1497, 14),
        ]),
        2 => new Route(1, 26, GRAY, [
            new RouteSpace(696, 1505, 15),
            new RouteSpace(754, 1520, 15),
        ]),
        3 => new Route(1, 28, ORANGE, [
            new RouteSpace(658, 1243, -87),
            new RouteSpace(656, 1303, -84),
            new RouteSpace(653, 1364, -85),
            new RouteSpace(649, 1425, -87),
        ]),
        4 => new Route(1, 34, GRAY, [
            new RouteSpace(599, 1458, 4),
        ]),
        5 => new Route(1, 34, GRAY, [
            new RouteSpace(598, 1481, 4),
        ]),
        6 => new Route(2, 7, GREEN, [
            new RouteSpace(1033, 596, 58),
            new RouteSpace(1060, 651, 70),
            new RouteSpace(1075, 711, 81),
            new RouteSpace(1082, 772, -90),
            new RouteSpace(1079, 832, -81),
        ]),
        7 => new Route(2, 13, BLUE, [
            new RouteSpace(626, 350, 28),
            new RouteSpace(679, 378, 28),
            new RouteSpace(733, 407, 28),
            new RouteSpace(786, 435, 28),
            new RouteSpace(839, 464, 28),
            new RouteSpace(893, 492, 28),
            new RouteSpace(946, 521, 28),
        ]),
        8 => new Route(2, 13, YELLOW, [
            new RouteSpace(616, 371, 28),
            new RouteSpace(671, 401, 28),
            new RouteSpace(720, 426, 28),
            new RouteSpace(776, 456, 28),
            new RouteSpace(829, 484, 28),
            new RouteSpace(883, 513, 28),
            new RouteSpace(936, 541, 28),
        ]),
        9 => new Route(2, 14, BLACK, [
            new RouteSpace(563, 593, -3),
            new RouteSpace(623, 591, -4),
            new RouteSpace(683, 588, -3),
            new RouteSpace(744, 585, -3),
            new RouteSpace(805, 583, -4),
            new RouteSpace(865, 580, -4),
            new RouteSpace(925, 577, -3),
        ]),
        10 => new Route(2, 21, ORANGE, [
            new RouteSpace(997, 617, -81),
            new RouteSpace(965, 684, -47),
            new RouteSpace(928, 727, -50),
            new RouteSpace(890, 778, -41),
        ]),
        11 => new Route(2, 23, RED, [
            new RouteSpace(958, 608, -48),
            new RouteSpace(902, 639, -10),
            new RouteSpace(842, 649, -9),
        ]),
        12 => new Route(2, 30, PINK, [
            new RouteSpace(872, 215, 38),
            new RouteSpace(916, 257, 49),
            new RouteSpace(952, 308, 60),
            new RouteSpace(977, 363, 69),
            new RouteSpace(996, 421, 77),
            new RouteSpace(1005, 482, -90),
        ]),
        13 => new Route(2, 32, WHITE, [
            new RouteSpace(784, 332, 22),
            new RouteSpace(839, 360, 33),
            new RouteSpace(888, 397, 41),
            new RouteSpace(931, 440, 49),
            new RouteSpace(967, 490, 59),
        ]),
        14 => new Route(3, 4, WHITE, [
            new RouteSpace(360, 391, 85),
        ]),
        15 => new Route(3, 9, BLUE, [
            new RouteSpace(202, 387, 18),
            new RouteSpace(260, 406, 18),
            new RouteSpace(317, 425, 18),
        ]),
        16 => new Route(3, 13, ORANGE, [
            new RouteSpace(413, 438, -14),
            new RouteSpace(470, 424, -15),
            new RouteSpace(518, 382, -65),
        ]),
        17 => new Route(3, 14, YELLOW, [
            new RouteSpace(382, 494, 71),
            new RouteSpace(401, 551, 72),
            new RouteSpace(449, 587, 0),
        ]),
        18 => new Route(3, 27, BLACK, [
            new RouteSpace(327, 497, -56),
            new RouteSpace(293, 548, -56),
            new RouteSpace(259, 598, -56),
        ]),
        19 => new Route(4, 9, GRAY, [
            new RouteSpace(187, 327, -47),
            new RouteSpace(245, 297, -16),
            new RouteSpace(305, 308, 28),
        ]),
        20 => new Route(4, 13, GRAY, [
            new RouteSpace(414, 311, -43),
            new RouteSpace(457, 296, 47),
            new RouteSpace(512, 327, 11),
        ]),
        21 => new Route(4, 17, GRAY, [
            new RouteSpace(397, 293, -42),
            new RouteSpace(441, 252, -43),
            new RouteSpace(486, 211, -42),
        ]),
        22 => new Route(4, 1001, GREEN, [
            new RouteSpace(350, 284, 85),
            new RouteSpace(349, 223, -83),
            new RouteSpace(361, 162, -73),
            new RouteSpace(383, 106, -61),
            new RouteSpace(414, 59, -49),
        ]),
        23 => new Route(5, 7, YELLOW, [
            new RouteSpace(1018, 907, -25),
        ]),
        24 => new Route(5, 10, BLACK, [
            new RouteSpace(732, 912, 2),
            new RouteSpace(792, 918, 2),
            new RouteSpace(853, 924, 5),
            new RouteSpace(913, 929, 6),
        ]),
        25 => new Route(5, 21, BLUE, [
            new RouteSpace(889, 849, 45),
            new RouteSpace(931, 891, 45),
        ]),
        26 => new Route(5, 29, PINK, [
            new RouteSpace(933, 969, -41),
            new RouteSpace(907, 1026, -90),
            new RouteSpace(907, 1086, -90),
            new RouteSpace(907, 1146, -90),
            new RouteSpace(908, 1208, -88),
            new RouteSpace(907, 1268, -90),
        ]),
        27 => new Route(6, 8, GRAY, [
            new RouteSpace(139, 767, -23),
        ]),
        28 => new Route(6, 8, GRAY, [
            new RouteSpace(149, 788, -24),
        ]),
        29 => new Route(6, 8, GRAY, [
            new RouteSpace(158, 809, -23),
        ]),
        30 => new Route(6, 16, GREEN, [
            new RouteSpace(235, 808, 41),
            new RouteSpace(290, 839, 19),
            new RouteSpace(352, 850, 0),
            new RouteSpace(414, 841, -19),
        ]),
        31 => new Route(6, 27, GRAY, [
            new RouteSpace(196, 710, -76),
        ]),
        32 => new Route(6, 27, GRAY, [
            new RouteSpace(218, 716, -76),
        ]),
        33 => new Route(7, 21, BLACK, [
            new RouteSpace(910, 820, 20),
            new RouteSpace(967, 840, 20),
            new RouteSpace(1024, 861, 20),
        ]),
        34 => new Route(7, 29, RED, [
            new RouteSpace(1057, 936, -75),
            new RouteSpace(1043, 995, -74),
            new RouteSpace(1029, 1054, -76),
            new RouteSpace(1014, 1112, -75),
            new RouteSpace(997, 1171, -70),
            new RouteSpace(975, 1227, -65),
            new RouteSpace(946, 1280, -57),
        ]),
        35 => new Route(8, 19, GRAY, [
            new RouteSpace(68, 870, -90),
        ]),
        36 => new Route(8, 19, GRAY, [
            new RouteSpace(91, 870, -90),
        ]),
        37 => new Route(8, 19, GRAY, [
            new RouteSpace(114, 870, -90),
        ]),
        38 => new Route(8, 3002, PINK, [
            new RouteSpace(37, 646, 70),
            new RouteSpace(58, 703, 70),
            new RouteSpace(78, 760, 70),
        ]),
        39 => new Route(9, 27, RED, [
            new RouteSpace(165, 423, 76),
            new RouteSpace(181, 481, 75),
            new RouteSpace(196, 540, 75),
            new RouteSpace(210, 599, 75),
        ]),
        40 => new Route(9, 3001, WHITE, [
            new RouteSpace(112, 411, -50),
            new RouteSpace(73, 457, -50),
        ]),
        41 => new Route(10, 14, ORANGE, [
            new RouteSpace(514, 663, 59),
            new RouteSpace(546, 715, 59),
            new RouteSpace(576, 766, 59),
            new RouteSpace(608, 818, 59),
            new RouteSpace(639, 870, 59),
        ]),
        42 => new Route(10, 14, GREEN, [
            new RouteSpace(536, 650, 59),
            new RouteSpace(568, 702, 59),
            new RouteSpace(598, 753, 59),
            new RouteSpace(630, 805, 59),
            new RouteSpace(661, 857, 59),
        ]),
        43 => new Route(10, 16, GRAY, [
            new RouteSpace(510, 878, 59),
            new RouteSpace(559, 919, 21),
            new RouteSpace(622, 922, -16),
        ]),
        44 => new Route(10, 21, RED, [
            new RouteSpace(705, 865, -67),
            new RouteSpace(746, 817, -30),
            new RouteSpace(808, 803, 7),
        ]),
        45 => new Route(10, 28, YELLOW, [
            new RouteSpace(665, 960, -90),
            new RouteSpace(667, 1020, -90),
            new RouteSpace(668, 1082, -90),
            new RouteSpace(668, 1142, -90),
        ]),
        46 => new Route(10, 28, PINK, [
            new RouteSpace(689, 960, -90),
            new RouteSpace(690, 1021, -90),
            new RouteSpace(691, 1081, -90),
            new RouteSpace(692, 1142, -90),
        ]),
        47 => new Route(10, 29, WHITE, [
            new RouteSpace(734, 951, 32),
            new RouteSpace(782, 988, 44),
            new RouteSpace(823, 1035, 55),
            new RouteSpace(854, 1087, 63),
            new RouteSpace(876, 1145, 77),
            new RouteSpace(883, 1206, -90),
            new RouteSpace(883, 1267, -90),
        ]),
        48 => new Route(11, 16, BLUE, [
            new RouteSpace(452, 879, -90),
            new RouteSpace(446, 940, -76),
            new RouteSpace(417, 996, -51),
            new RouteSpace(371, 1037, -34),
        ]),
        49 => new Route(11, 16, WHITE, [
            new RouteSpace(475, 893, 88),
            new RouteSpace(467, 954, -77),
            new RouteSpace(438, 1011, -50),
            new RouteSpace(392, 1052, -34),
        ]),
        50 => new Route(11, 19, GRAY, [
            new RouteSpace(140, 955, 31),
            new RouteSpace(192, 986, 31),
            new RouteSpace(244, 1017, 31),
            new RouteSpace(296, 1048, 31),
        ]),
        51 => new Route(11, 19, GRAY, [
            new RouteSpace(152, 935, 31),
            new RouteSpace(204, 966, 31),
            new RouteSpace(256, 997, 31),
            new RouteSpace(308, 1029, 31),
        ]),
        52 => new Route(11, 24, GRAY, [
            new RouteSpace(292, 1099, -36),
        ]),
        53 => new Route(11, 24, GRAY, [
            new RouteSpace(305, 1117, -36),
        ]),
        54 => new Route(11, 25, GRAY, [
            new RouteSpace(344, 1139, -77),
            new RouteSpace(330, 1198, -77),
        ]),
        55 => new Route(11, 25, GRAY, [
            new RouteSpace(366, 1144, -77),
            new RouteSpace(353, 1203, -77),
        ]),
        56 => new Route(11, 35, GRAY, [
            new RouteSpace(413, 1090, 21),
            new RouteSpace(469, 1112, 22),
        ]),
        57 => new Route(11, 35, GRAY, [
            new RouteSpace(405, 1112, 22),
            new RouteSpace(461, 1134, 21),
        ]),
        58 => new Route(12, 15, WHITE, [
            new RouteSpace(284, 1402, -72),
            new RouteSpace(265, 1460, -72),
            new RouteSpace(247, 1518, -73),
        ]),
        59 => new Route(12, 20, BLACK, [
            new RouteSpace(286, 1580, 11),
            new RouteSpace(345, 1593, 13),
        ]),
        60 => new Route(12, 33, GRAY, [
            new RouteSpace(284, 1520, -50),
            new RouteSpace(323, 1474, -50),
            new RouteSpace(362, 1428, -50),
        ]),
        61 => new Route(12, 2003, YELLOW, [
            new RouteSpace(178, 1553, 15),
            new RouteSpace(120, 1539, 13),
        ]),
        62 => new Route(12, 5002, ORANGE, [
            new RouteSpace(197, 1616, -57),
            new RouteSpace(202, 1676, 39),
        ]),
        63 => new Route(13, 14, RED, [
            new RouteSpace(543, 384, -75),
            new RouteSpace(529, 443, -76),
            new RouteSpace(515, 503, -74),
            new RouteSpace(501, 561, -76),
        ]),
        64 => new Route(13, 14, WHITE, [
            new RouteSpace(564, 389, -77),
            new RouteSpace(550, 448, -77),
            new RouteSpace(536, 507, -77),
            new RouteSpace(522, 565, -77),
        ]),
        65 => new Route(13, 17, BLACK, [
            new RouteSpace(535, 229, 81),
            new RouteSpace(545, 288, 80),
        ]),
        66 => new Route(13, 17, PINK, [
            new RouteSpace(558, 225, 81),
            new RouteSpace(569, 285, 81),
        ]),
        67 => new Route(13, 32, GREEN, [
            new RouteSpace(614, 302, -39),
            new RouteSpace(675, 297, 29),
        ]),
        68 => new Route(14, 16, GRAY, [
            new RouteSpace(451, 662, -82),
            new RouteSpace(447, 724, -90),
            new RouteSpace(451, 785, 82),
        ]),
        69 => new Route(14, 16, GRAY, [
            new RouteSpace(475, 663, -82),
            new RouteSpace(470, 723, -90),
            new RouteSpace(475, 785, 82),
        ]),
        70 => new Route(14, 23, BLUE, [
            new RouteSpace(564, 635, 42),
            new RouteSpace(618, 668, 20),
            new RouteSpace(679, 680, 0),
            new RouteSpace(741, 671, -17),
        ]),
        71 => new Route(14, 27, PINK, [
            new RouteSpace(270, 669, 13),
            new RouteSpace(332, 660, -29),
            new RouteSpace(387, 634, -22),
            new RouteSpace(447, 618, -11),
        ]),
        72 => new Route(15, 25, BLUE, [
            new RouteSpace(294, 1300, -90),
        ]),
        73 => new Route(15, 31, GRAY, [
            new RouteSpace(230, 1345, -5),
            new RouteSpace(168, 1342, 9),
            new RouteSpace(112, 1313, 47),
        ]),
        74 => new Route(15, 33, PINK, [
            new RouteSpace(348, 1371, 16),
        ]),
        75 => new Route(15, 2002, BLACK, [
            new RouteSpace(241, 1381, -28),
            new RouteSpace(188, 1410, -28),
        ]),
        76 => new Route(17, 30, ORANGE, [
            new RouteSpace(591, 150, -16),
            new RouteSpace(652, 144, -6),
            new RouteSpace(714, 144, 6),
            new RouteSpace(778, 158, 19),
        ]),
        77 => new Route(17, 32, YELLOW, [
            new RouteSpace(596, 198, 27),
            new RouteSpace(649, 229, 36),
            new RouteSpace(695, 270, 47),
        ]),
        78 => new Route(17, 1002, GRAY, [
            new RouteSpace(526, 69, 35),
            new RouteSpace(548, 124, 70),
        ]),
        79 => new Route(18, 19, GRAY, [
            new RouteSpace(84, 995, 73),
        ]),
        80 => new Route(18, 19, GRAY, [
            new RouteSpace(107, 989, 73),
        ]),
        81 => new Route(18, 24, GRAY, [
            new RouteSpace(154, 1087, 36),
            new RouteSpace(204, 1122, 36),
        ]),
        82 => new Route(18, 24, GRAY, [
            new RouteSpace(167, 1068, 35),
            new RouteSpace(217, 1104, 35),
        ]),
        83 => new Route(18, 31, GRAY, [
            new RouteSpace(97, 1095, -79),
            new RouteSpace(86, 1154, -79),
            new RouteSpace(74, 1214, -79),
        ]),
        84 => new Route(20, 22, YELLOW, [
            new RouteSpace(453, 1618, 14),
        ]),
        85 => new Route(20, 33, GREEN, [
            new RouteSpace(401, 1556, -87),
            new RouteSpace(402, 1495, -89),
            new RouteSpace(403, 1434, -90),
        ]),
        86 => new Route(20, 5001, WHITE, [
            new RouteSpace(358, 1643, -38),
        ]),
        87 => new Route(21, 23, YELLOW, [
            new RouteSpace(812, 704, 66),
            new RouteSpace(835, 760, 67),
        ]),
        88 => new Route(22, 26, GRAY, [
            new RouteSpace(556, 1619, 5),
            new RouteSpace(618, 1619, -6),
            new RouteSpace(678, 1609, -14),
            new RouteSpace(736, 1590, -22),
            new RouteSpace(791, 1561, -34),
        ]),
        89 => new Route(22, 34, RED, [
            new RouteSpace(512, 1580, -74),
            new RouteSpace(528, 1521, -74),
        ]),
        90 => new Route(22, 4003, PINK, [
            new RouteSpace(552, 1650, 19),
            new RouteSpace(609, 1670, 19),
        ]),
        91 => new Route(22, 5003, BLUE, [
            new RouteSpace(468, 1672, -46),
            new RouteSpace(411, 1698, -4),
        ]),
        92 => new Route(24, 25, GRAY, [
            new RouteSpace(268, 1197, 67),
        ]),
        93 => new Route(24, 25, GRAY, [
            new RouteSpace(289, 1189, 67),
        ]),
        94 => new Route(24, 31, GRAY, [
            new RouteSpace(214, 1174, -32),
            new RouteSpace(163, 1207, -34),
            new RouteSpace(113, 1240, -33),
        ]),
        95 => new Route(25, 31, GRAY, [
            new RouteSpace(250, 1255, -7),
            new RouteSpace(190, 1262, -8),
            new RouteSpace(130, 1270, -7),
        ]),
        96 => new Route(25, 33, GRAY, [
            new RouteSpace(336, 1288, 53),
            new RouteSpace(372, 1336, 53),
        ]),
        97 => new Route(25, 33, GRAY, [
            new RouteSpace(355, 1274, 54),
            new RouteSpace(391, 1323, 53),
        ]),
        98 => new Route(26, 28, BLUE, [
            new RouteSpace(696, 1244, 67),
            new RouteSpace(720, 1299, 67),
            new RouteSpace(744, 1355, 67),
            new RouteSpace(769, 1410, 67),
            new RouteSpace(793, 1466, 66),
        ]),
        99 => new Route(26, 28, BLACK, [
            new RouteSpace(718, 1235, 67),
            new RouteSpace(742, 1290, 67),
            new RouteSpace(766, 1345, 67),
            new RouteSpace(790, 1401, 67),
            new RouteSpace(814, 1456, 67),
        ]),
        100 => new Route(26, 29, ORANGE, [
            new RouteSpace(916, 1377, -101),
            new RouteSpace(908, 1439, -64),
            new RouteSpace(862, 1485, -26),
        ]),
        101 => new Route(26, 4002, RED, [
            new RouteSpace(857, 1553, 44),
            new RouteSpace(901, 1594, 45),
            new RouteSpace(944, 1637, 44),
        ]),
        102 => new Route(27, 3003, ORANGE, [
            new RouteSpace(168, 637, 20),
            new RouteSpace(108, 616, 22),
        ]),
        103 => new Route(28, 29, GREEN, [
            new RouteSpace(741, 1203, 35),
            new RouteSpace(791, 1238, 35),
            new RouteSpace(840, 1272, 35),
        ]),
        104 => new Route(28, 35, GRAY, [
            new RouteSpace(568, 1151, 21),
            new RouteSpace(624, 1172, 21),
        ]),
        105 => new Route(28, 35, GRAY, [
            new RouteSpace(560, 1172, 22),
            new RouteSpace(616, 1194, 21),
        ]),
        106 => new Route(29, 4001, YELLOW, [
            new RouteSpace(944, 1353, 55),
            new RouteSpace(978, 1404, 55),
            new RouteSpace(1012, 1455, 55),
            new RouteSpace(1046, 1506, 55),
        ]),
        107 => new Route(30, 32, RED, [
            new RouteSpace(797, 224, -55),
            new RouteSpace(760, 273, -54),
        ]),
        108 => new Route(31, 2001, GREEN, [
            new RouteSpace(61, 1324, -78),
        ]),
        109 => new Route(33, 34, GRAY, [
            new RouteSpace(462, 1392, 36),
            new RouteSpace(511, 1427, 36),
        ]),
        110 => new Route(33, 34, GRAY, [
            new RouteSpace(449, 1410, 36),
            new RouteSpace(498, 1445, 36),
        ]),

    ];
}
