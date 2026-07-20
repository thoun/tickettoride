<?php

use Bga\Games\TicketToRide\Objects\Route;
use Bga\Games\TicketToRide\Objects\RouteSpace;

/**
 * Routes on the France map.
 *
 * For parallel routes, there is one Route instance per printed track.
 * City ids are always ordered from low to high.
 */
function getRoutes() {
    return [
        1 => new Route(1, 11, TRACKBED, [
            new RouteSpace(896, 191, -78),
            new RouteSpace(909, 131, -78),
        ]),
        2 => new Route(1, 19, TRACKBED, [
            new RouteSpace(941, 222, -40),
            new RouteSpace(989, 184, -40),
        ]),
        3 => new Route(1, 19, TRACKBED, [
            new RouteSpace(957, 241, -40),
            new RouteSpace(1004, 202, -40),
        ]),
        4 => new Route(1, 31, TRACKBED, [
            new RouteSpace(868, 333, -72),
            new RouteSpace(850, 393, -74),
        ]),
        5 => new Route(1, 31, TRACKBED, [
            new RouteSpace(890, 340, -72),
            new RouteSpace(873, 399, -72),
        ]),
        6 => new Route(1, 35, TRACKBED, [
            new RouteSpace(960, 325, 46),
            new RouteSpace(1001, 369, 47),
        ]),
        7 => new Route(1, 38, WHITE, [
            new RouteSpace(827, 275, -9),
        ]),
        8 => new Route(2, 18, BLUE, [
            new RouteSpace(459, 530, -34),
        ]),
        9 => new Route(2, 28, RED, [
            new RouteSpace(346, 552, 0),
        ]),
        10 => new Route(2, 28, ORANGE, [
            new RouteSpace(347, 574, 0),
        ]),
        11 => new Route(2, 34, TRACKBED, [
            new RouteSpace(417, 621, 83),
            new RouteSpace(423, 682, 87),
        ]),
        12 => new Route(2, 36, TRACKBED, [
            new RouteSpace(361, 440, 66),
            new RouteSpace(389, 500, 66),
        ]),
        13 => new Route(2, 42, PINK, [
            new RouteSpace(484, 580, 13),
        ]),
        14 => new Route(2, 42, GREEN, [
            new RouteSpace(479, 603, 13),
        ]),
        15 => new Route(3, 9, TRACKBED, [
            new RouteSpace(917, 1364, -14),
            new RouteSpace(977, 1349, -14),
            new RouteSpace(1036, 1333, -14),
        ]),
        16 => new Route(3, 22, TRACKBED, [
            new RouteSpace(840, 1306, -70),
            new RouteSpace(861, 1248, -70),
            new RouteSpace(882, 1191, -70),
            new RouteSpace(904, 1133, -70),
        ]),
        17 => new Route(3, 22, TRACKBED, [
            new RouteSpace(863, 1316, -70),
            new RouteSpace(884, 1257, -70),
            new RouteSpace(905, 1200, -70),
            new RouteSpace(926, 1142, -71),
        ]),
        18 => new Route(3, 22, TRACKBED, [
            new RouteSpace(885, 1323, -69),
            new RouteSpace(906, 1266, -69),
            new RouteSpace(927, 1207, -70),
            new RouteSpace(948, 1150, -70),
        ]),
        19 => new Route(3, 23, TRACKBED, [
            new RouteSpace(873, 1446, 81),
            new RouteSpace(885, 1507, 80),
        ]),
        20 => new Route(3, 23, TRACKBED, [
            new RouteSpace(850, 1449, 79),
            new RouteSpace(861, 1510, 80),
        ]),
        21 => new Route(3, 23, TRACKBED, [
            new RouteSpace(828, 1454, 80),
            new RouteSpace(839, 1515, 80),
        ]),
        22 => new Route(3, 25, TRACKBED, [
            new RouteSpace(710, 1408, -13),
            new RouteSpace(770, 1394, -13),
        ]),
        23 => new Route(3, 25, TRACKBED, [
            new RouteSpace(714, 1430, -12),
            new RouteSpace(774, 1416, -13),
        ]),
        24 => new Route(3, 29, TRACKBED, [
            new RouteSpace(907, 1413, 34),
            new RouteSpace(958, 1448, 34),
            new RouteSpace(1010, 1482, 34),
            new RouteSpace(1061, 1516, 35),
        ]),
        25 => new Route(3, 41, GRAY, [
            new RouteSpace(465, 1308, 8),
            new RouteSpace(526, 1317, 8),
            new RouteSpace(587, 1325, 8),
            new RouteSpace(648, 1334, 8),
            new RouteSpace(708, 1342, 8),
            new RouteSpace(769, 1350, 8),
        ]),
        26 => new Route(4, 6, TRACKBED, [
            new RouteSpace(78, 1130, -42),
            new RouteSpace(124, 1090, -42),
            new RouteSpace(170, 1049, -41),
        ]),
        27 => new Route(4, 6, TRACKBED, [
            new RouteSpace(94, 1148, -42),
            new RouteSpace(139, 1109, -41),
            new RouteSpace(185, 1067, -39),
        ]),
        28 => new Route(4, 32, BLACK, [
            new RouteSpace(94, 1235, 33),
        ]),
        29 => new Route(4, 5001, TRACKBED, [
            new RouteSpace(41, 1257, 79),
            new RouteSpace(52, 1317, 79),
            new RouteSpace(64, 1377, 79),
        ]),
        30 => new Route(5, 14, WHITE, [
            new RouteSpace(1115, 842, 11),
        ]),
        31 => new Route(5, 22, TRACKBED, [
            new RouteSpace(1017, 1030, -45),
            new RouteSpace(1060, 986, -44),
            new RouteSpace(1103, 942, -45),
            new RouteSpace(1146, 899, -45),
        ]),
        32 => new Route(5, 26, TRACKBED, [
            new RouteSpace(1241, 840, -22),
            new RouteSpace(1298, 816, -22),
        ]),
        33 => new Route(5, 3001, TRACKBED, [
            new RouteSpace(1216, 908, 60),
            new RouteSpace(1248, 961, 60),
        ]),
        34 => new Route(6, 10, TRACKBED, [
            new RouteSpace(325, 1038, 7),
            new RouteSpace(387, 1043, 8),
            new RouteSpace(448, 1048, 5),
        ]),
        35 => new Route(6, 16, TRACKBED, [
            new RouteSpace(220, 942, -85),
            new RouteSpace(226, 881, -85),
            new RouteSpace(231, 820, -85),
        ]),
        36 => new Route(6, 16, TRACKBED, [
            new RouteSpace(242, 942, -85),
            new RouteSpace(249, 881, -83),
            new RouteSpace(254, 820, -85),
        ]),
        37 => new Route(6, 32, TRACKBED, [
            new RouteSpace(214, 1102, -69),
            new RouteSpace(193, 1160, -68),
            new RouteSpace(171, 1217, -70),
        ]),
        38 => new Route(6, 34, TRACKBED, [
            new RouteSpace(288, 960, -54),
            new RouteSpace(324, 909, -54),
            new RouteSpace(359, 859, -53),
            new RouteSpace(394, 809, -54),
        ]),
        39 => new Route(6, 34, TRACKBED, [
            new RouteSpace(308, 973, -54),
            new RouteSpace(343, 923, -55),
            new RouteSpace(378, 872, -55),
            new RouteSpace(413, 821, -55),
        ]),
        40 => new Route(6, 41, TRACKBED, [
            new RouteSpace(293, 1083, 69),
            new RouteSpace(317, 1139, 68),
            new RouteSpace(341, 1196, 68),
            new RouteSpace(365, 1253, 68),
        ]),
        41 => new Route(6, 41, TRACKBED, [
            new RouteSpace(273, 1091, 67),
            new RouteSpace(296, 1148, 68),
            new RouteSpace(321, 1205, 67),
            new RouteSpace(344, 1261, 68),
        ]),
        42 => new Route(7, 13, TRACKBED, [
            new RouteSpace(724, 819, 89),
            new RouteSpace(727, 881, 87),
            new RouteSpace(730, 942, 89),
        ]),
        43 => new Route(7, 14, TRACKBED, [
            new RouteSpace(782, 777, 7),
            new RouteSpace(844, 782, 6),
            new RouteSpace(904, 789, 7),
            new RouteSpace(966, 796, 7),
        ]),
        44 => new Route(7, 10, TRACKBED, [
            new RouteSpace(671, 814, -56),
            new RouteSpace(637, 865, -57),
            new RouteSpace(604, 917, -56),
            new RouteSpace(570, 969, -56),
            new RouteSpace(536, 1019, -56),
        ]),
        45 => new Route(7, 30, TRACKBED, [
            new RouteSpace(731, 628, -86),
            new RouteSpace(726, 690, -87),
        ]),
        46 => new Route(7, 34, TRACKBED, [
            new RouteSpace(518, 763, -2),
            new RouteSpace(579, 760, -3),
            new RouteSpace(641, 757, -2),
        ]),
        47 => new Route(7, 42, YELLOW, [
            new RouteSpace(630, 663, 48),
        ]),
        48 => new Route(7, 42, BLACK, [
            new RouteSpace(650, 686, 48),
        ]),
        49 => new Route(7, 42, WHITE, [
            new RouteSpace(671, 709, 48),
        ]),
        50 => new Route(8, 12, GRAY, [
            new RouteSpace(402, 94, 5),
            new RouteSpace(340, 92, 1),
            new RouteSpace(279, 94, -5),
            new RouteSpace(219, 104, -15),
            new RouteSpace(161, 122, -19),
            new RouteSpace(104, 146, -24),
        ], locomotives: 1),
        51 => new Route(8, 21, TRACKBED, [
            new RouteSpace(97, 260, 75),
            new RouteSpace(113, 319, 76),
        ]),
        52 => new Route(8, 21, TRACKBED, [
            new RouteSpace(74, 266, 74),
            new RouteSpace(91, 325, 76),
        ]),
        53 => new Route(8, 36, TRACKBED, [
            new RouteSpace(133, 234, 36),
            new RouteSpace(183, 266, 34),
            new RouteSpace(234, 302, 35),
            new RouteSpace(285, 337, 35),
        ]),
        54 => new Route(8, 39, TRACKBED, [
            new RouteSpace(154, 200, 17),
            new RouteSpace(212, 219, 19),
            new RouteSpace(271, 236, 17),
        ]),
        55 => new Route(9, 15, YELLOW, [
            new RouteSpace(1060, 1266, 38),
        ]),
        56 => new Route(9, 23, TRACKBED, [
            new RouteSpace(1063, 1369, -56),
            new RouteSpace(1029, 1421, -56),
            new RouteSpace(995, 1473, -56),
            new RouteSpace(962, 1524, -56),
        ]),
        57 => new Route(9, 29, TRACKBED, [
            new RouteSpace(1106, 1358, -90),
            new RouteSpace(1105, 1420, -90),
            new RouteSpace(1106, 1482, -90),
        ]),
        58 => new Route(9, 4002, TRACKBED, [
            new RouteSpace(1160, 1298, 1),
            new RouteSpace(1222, 1299, 1),
            new RouteSpace(1284, 1299, 2),
        ]),
        59 => new Route(10, 13, TRACKBED, [
            new RouteSpace(560, 1067, -11),
            new RouteSpace(620, 1056, -10),
            new RouteSpace(681, 1044, -10),
        ]),
        60 => new Route(10, 20, BLUE, [
            new RouteSpace(490, 989, -82),
        ]),
        61 => new Route(10, 37, TRACKBED, [
            new RouteSpace(537, 1132, 69),
            new RouteSpace(560, 1189, 71),
        ]),
        62 => new Route(10, 41, TRACKBED, [
            new RouteSpace(463, 1122, -67),
            new RouteSpace(439, 1178, -67),
            new RouteSpace(416, 1235, -68),
        ]),
        63 => new Route(11, 12, GRAY, [
            new RouteSpace(827, 40, 5),
            new RouteSpace(766, 38, 0),
            new RouteSpace(704, 40, -5),
            new RouteSpace(643, 51, -14),
            new RouteSpace(585, 69, -20),
            new RouteSpace(525, 86, -31),
        ], locomotives: 1),
        64 => new Route(11, 17, TRACKBED, [
            new RouteSpace(844, 89, -29),
            new RouteSpace(790, 120, -29),
            new RouteSpace(736, 150, -28),
            new RouteSpace(683, 180, -29),
        ]),
        65 => new Route(11, 19, TRACKBED, [
            new RouteSpace(950, 84, 37),
            new RouteSpace(999, 121, 38),
        ]),
        66 => new Route(11, 38, TRACKBED, [
            new RouteSpace(873, 119, -55),
            new RouteSpace(838, 169, -54),
            new RouteSpace(801, 219, -54),
        ]),
        67 => new Route(11, 1001, TRACKBED, [
            new RouteSpace(963, 40, 0),
            new RouteSpace(1025, 40, 0),
        ]),
        68 => new Route(12, 17, TRACKBED, [
            new RouteSpace(517, 144, 31),
            new RouteSpace(570, 175, 30),
        ]),
        69 => new Route(12, 18, TRACKBED, [
            new RouteSpace(460, 169, 83),
            new RouteSpace(469, 231, 83),
            new RouteSpace(478, 292, 83),
            new RouteSpace(486, 352, 82),
            new RouteSpace(496, 414, 82),
        ]),
        70 => new Route(12, 39, TRACKBED, [
            new RouteSpace(384, 207, -56),
            new RouteSpace(417, 155, -57),
        ]),
        71 => new Route(13, 20, TRACKBED, [
            new RouteSpace(566, 953, 22),
            new RouteSpace(620, 976, 22),
            new RouteSpace(677, 1000, 22),
        ]),
        72 => new Route(13, 22, TRACKBED, [
            new RouteSpace(797, 1037, 8),
            new RouteSpace(858, 1046, 10),
            new RouteSpace(918, 1056, 8),
        ]),
        73 => new Route(13, 37, TRACKBED, [
            new RouteSpace(682, 1095, -55),
            new RouteSpace(648, 1146, -56),
            new RouteSpace(613, 1196, -55),
        ]),
        74 => new Route(14, 22, TRACKBED, [
            new RouteSpace(994, 856, -73),
            new RouteSpace(977, 915, -74),
            new RouteSpace(960, 974, -76),
        ]),
        75 => new Route(14, 22, TRACKBED, [
            new RouteSpace(1017, 864, -73),
            new RouteSpace(1000, 922, -73),
            new RouteSpace(982, 982, -74),
        ]),
        76 => new Route(14, 22, TRACKBED, [
            new RouteSpace(1039, 870, -72),
            new RouteSpace(1022, 928, -72),
            new RouteSpace(1004, 988, -73),
        ]),
        77 => new Route(14, 27, TRACKBED, [
            new RouteSpace(1075, 745, -39),
            new RouteSpace(1122, 705, -39),
            new RouteSpace(1168, 664, -41),
            new RouteSpace(1215, 625, -41),
        ]),
        78 => new Route(14, 27, TRACKBED, [
            new RouteSpace(1091, 762, -40),
            new RouteSpace(1137, 722, -41),
            new RouteSpace(1184, 682, -40),
            new RouteSpace(1230, 643, -39),
        ]),
        79 => new Route(14, 31, TRACKBED, [
            new RouteSpace(904, 505, 61),
            new RouteSpace(934, 559, 63),
            new RouteSpace(963, 613, 62),
            new RouteSpace(993, 667, 63),
            new RouteSpace(1024, 720, 61),
        ]),
        80 => new Route(14, 31, TRACKBED, [
            new RouteSpace(884, 518, 62),
            new RouteSpace(914, 571, 64),
            new RouteSpace(943, 624, 61),
            new RouteSpace(973, 678, 63),
            new RouteSpace(1004, 732, 62),
        ]),
        81 => new Route(14, 31, TRACKBED, [
            new RouteSpace(862, 530, 62),
            new RouteSpace(893, 583, 61),
            new RouteSpace(923, 636, 61),
            new RouteSpace(952, 690, 62),
            new RouteSpace(982, 744, 61),
        ]),
        82 => new Route(15, 22, TRACKBED, [
            new RouteSpace(985, 1116, 78),
            new RouteSpace(998, 1176, 77),
        ]),
        83 => new Route(15, 23, TRACKBED, [
            new RouteSpace(1001, 1279, -73),
            new RouteSpace(983, 1338, -73),
            new RouteSpace(965, 1397, -73),
            new RouteSpace(947, 1456, -73),
            new RouteSpace(929, 1515, -72),
        ]),
        84 => new Route(15, 4001, TRACKBED, [
            new RouteSpace(1075, 1216, -2),
            new RouteSpace(1136, 1214, -2),
            new RouteSpace(1198, 1210, -3),
            new RouteSpace(1260, 1208, 0),
        ]),
        85 => new Route(16, 28, TRACKBED, [
            new RouteSpace(250, 616, -85),
            new RouteSpace(244, 678, -85),
        ]),
        86 => new Route(16, 28, TRACKBED, [
            new RouteSpace(272, 618, -85),
            new RouteSpace(266, 680, -85),
        ]),
        87 => new Route(16, 34, TRACKBED, [
            new RouteSpace(324, 743, 5),
            new RouteSpace(385, 748, 6),
        ]),
        88 => new Route(17, 18, TRACKBED, [
            new RouteSpace(614, 250, -65),
            new RouteSpace(589, 307, -66),
            new RouteSpace(564, 364, -66),
            new RouteSpace(538, 421, -66),
        ]),
        89 => new Route(17, 38, PINK, [
            new RouteSpace(693, 242, 23),
        ]),
        90 => new Route(18, 21, TRACKBED, [
            new RouteSpace(204, 408, 17),
            new RouteSpace(262, 426, 17),
            new RouteSpace(321, 445, 17),
            new RouteSpace(381, 463, 17),
            new RouteSpace(439, 481, 18),
        ]),
        91 => new Route(18, 30, TRACKBED, [
            new RouteSpace(572, 518, 15),
            new RouteSpace(632, 534, 16),
            new RouteSpace(691, 551, 17),
        ]),
        92 => new Route(18, 31, TRACKBED, [
            new RouteSpace(631, 473, -2),
            new RouteSpace(693, 470, -2),
            new RouteSpace(754, 466, -3),
        ]),
        93 => new Route(18, 36, TRACKBED, [
            new RouteSpace(394, 403, 32),
            new RouteSpace(447, 435, 33),
        ]),
        94 => new Route(18, 38, TRACKBED, [
            new RouteSpace(573, 432, -37),
            new RouteSpace(622, 394, -37),
            new RouteSpace(671, 356, -38),
            new RouteSpace(719, 319, -37),
        ]),
        95 => new Route(18, 42, ORANGE, [
            new RouteSpace(546, 559, 57),
        ]),
        96 => new Route(19, 35, TRACKBED, [
            new RouteSpace(1058, 232, -90),
            new RouteSpace(1058, 294, -90),
            new RouteSpace(1057, 355, -90),
        ]),
        97 => new Route(19, 1002, GREEN, [
            new RouteSpace(1097, 129, -34),
        ]),
        98 => new Route(20, 34, TRACKBED, [
            new RouteSpace(482, 817, 75),
            new RouteSpace(500, 877, 75),
        ]),
        99 => new Route(21, 28, TRACKBED, [
            new RouteSpace(181, 443, 51),
            new RouteSpace(221, 490, 49),
        ]),
        100 => new Route(21, 28, TRACKBED, [
            new RouteSpace(164, 458, 50),
            new RouteSpace(204, 505, 50),
        ]),
        101 => new Route(21, 36, TRACKBED, [
            new RouteSpace(205, 370, 3),
            new RouteSpace(266, 373, 3),
        ]),
        102 => new Route(21, 39, TRACKBED, [
            new RouteSpace(189, 324, -26),
            new RouteSpace(247, 293, -26),
        ]),
        103 => new Route(22, 37, TRACKBED, [
            new RouteSpace(659, 1220, -28),
            new RouteSpace(713, 1191, -27),
            new RouteSpace(767, 1162, -27),
            new RouteSpace(821, 1132, -27),
            new RouteSpace(875, 1102, -28),
        ]),
        104 => new Route(22, 3003, TRACKBED, [
            new RouteSpace(1049, 1073, 4),
            new RouteSpace(1110, 1079, 5),
            new RouteSpace(1172, 1087, 8),
        ]),
        105 => new Route(22, 3003, TRACKBED, [
            new RouteSpace(1047, 1096, 4),
            new RouteSpace(1109, 1103, 8),
            new RouteSpace(1169, 1110, 7),
        ]),
        106 => new Route(23, 25, TRACKBED, [
            new RouteSpace(696, 1482, 34),
            new RouteSpace(748, 1515, 33),
            new RouteSpace(798, 1549, 34),
        ]),
        107 => new Route(23, 25, TRACKBED, [
            new RouteSpace(682, 1502, 34),
            new RouteSpace(734, 1536, 33),
            new RouteSpace(786, 1570, 34),
        ]),
        108 => new Route(23, 29, TRACKBED, [
            new RouteSpace(975, 1566, -6),
            new RouteSpace(1037, 1559, -6),
        ]),
        109 => new Route(23, 29, TRACKBED, [
            new RouteSpace(978, 1589, -5),
            new RouteSpace(1039, 1582, -6),
        ]),
        110 => new Route(23, 6002, GRAY, [
            new RouteSpace(924, 1619, 41),
            new RouteSpace(974, 1656, 36),
            new RouteSpace(1023, 1692, 31),
        ], locomotives: 1),
        111 => new Route(24, 27, GREEN, [
            new RouteSpace(1279, 547, -60),
        ]),
        112 => new Route(24, 27, BLACK, [
            new RouteSpace(1300, 560, -59),
        ]),
        113 => new Route(24, 35, TRACKBED, [
            new RouteSpace(1142, 441, 17),
            new RouteSpace(1201, 459, 17),
            new RouteSpace(1259, 477, 17),
        ]),
        114 => new Route(24, 40, TRACKBED, [
            new RouteSpace(1370, 551, 56),
            new RouteSpace(1405, 602, 56),
        ]),
        115 => new Route(24, 2001, YELLOW, [
            new RouteSpace(1366, 466, -35),
        ]),
        116 => new Route(24, 2001, BLUE, [
            new RouteSpace(1378, 486, -34),
        ]),
        117 => new Route(25, 33, TRACKBED, [
            new RouteSpace(560, 1506, -33),
            new RouteSpace(611, 1473, -34),
        ]),
        118 => new Route(25, 37, TRACKBED, [
            new RouteSpace(627, 1301, 62),
            new RouteSpace(657, 1355, 63),
        ]),
        119 => new Route(25, 41, TRACKBED, [
            new RouteSpace(439, 1340, 20),
            new RouteSpace(497, 1359, 18),
            new RouteSpace(556, 1378, 18),
            new RouteSpace(615, 1396, 17),
        ]),
        120 => new Route(25, 41, TRACKBED, [
            new RouteSpace(431, 1362, 18),
            new RouteSpace(489, 1381, 18),
            new RouteSpace(548, 1399, 17),
            new RouteSpace(607, 1419, 19),
        ]),
        121 => new Route(26, 40, ORANGE, [
            new RouteSpace(1370, 741, -57),
        ]),
        122 => new Route(26, 2002, TRACKBED, [
            new RouteSpace(1401, 780, -6),
            new RouteSpace(1462, 774, -6),
        ]),
        123 => new Route(26, 3002, TRACKBED, [
            new RouteSpace(1366, 866, 78),
            new RouteSpace(1375, 920, 78),
        ]),
        124 => new Route(27, 35, TRACKBED, [
            new RouteSpace(1113, 475, 37),
            new RouteSpace(1163, 511, 35),
            new RouteSpace(1213, 546, 36),
        ]),
        125 => new Route(27, 35, TRACKBED, [
            new RouteSpace(1099, 495, 36),
            new RouteSpace(1149, 530, 35),
            new RouteSpace(1199, 567, 36),
        ]),
        126 => new Route(27, 40, TRACKBED, [
            new RouteSpace(1319, 615, 25),
            new RouteSpace(1374, 641, 26),
        ]),
        127 => new Route(27, 40, TRACKBED, [
            new RouteSpace(1309, 638, 27),
            new RouteSpace(1365, 664, 26),
        ]),
        128 => new Route(29, 4003, TRACKBED, [
            new RouteSpace(1156, 1509, -38),
            new RouteSpace(1204, 1471, -38),
            new RouteSpace(1252, 1433, -38),
        ]),
        129 => new Route(29, 4003, TRACKBED, [
            new RouteSpace(1171, 1527, -38),
            new RouteSpace(1220, 1488, -39),
            new RouteSpace(1267, 1450, -38),
        ]),
        130 => new Route(29, 6003, GRAY, [
            new RouteSpace(1126, 1611, 60),
            new RouteSpace(1159, 1665, 58),
        ], locomotives: 1),
        131 => new Route(30, 31, BLUE, [
            new RouteSpace(761, 514, -51),
        ]),
        132 => new Route(30, 31, PINK, [
            new RouteSpace(780, 528, -51),
        ]),
        133 => new Route(30, 31, ORANGE, [
            new RouteSpace(799, 543, -51),
        ]),
        134 => new Route(32, 41, TRACKBED, [
            new RouteSpace(191, 1293, 12),
            new RouteSpace(251, 1306, 13),
            new RouteSpace(312, 1318, 12),
        ]),
        135 => new Route(32, 5002, TRACKBED, [
            new RouteSpace(145, 1338, 87),
            new RouteSpace(149, 1399, 87),
        ]),
        136 => new Route(33, 41, TRACKBED, [
            new RouteSpace(403, 1398, 49),
            new RouteSpace(443, 1445, 50),
            new RouteSpace(483, 1492, 49),
        ]),
        137 => new Route(33, 5004, TRACKBED, [
            new RouteSpace(416, 1611, -39),
            new RouteSpace(464, 1572, -38),
        ]),
        138 => new Route(33, 6001, GRAY, [
            new RouteSpace(564, 1563, 30),
            new RouteSpace(619, 1594, 29),
            new RouteSpace(672, 1621, 25),
            new RouteSpace(731, 1644, 18),
            new RouteSpace(791, 1663, 14),
        ], locomotives: 1),
        139 => new Route(34, 42, TRACKBED, [
            new RouteSpace(478, 686, -48),
            new RouteSpace(518, 640, -48),
        ]),
        140 => new Route(34, 42, TRACKBED, [
            new RouteSpace(495, 703, -49),
            new RouteSpace(535, 656, -49),
        ]),
        141 => new Route(34, 42, TRACKBED, [
            new RouteSpace(513, 716, -49),
            new RouteSpace(552, 669, -50),
        ]),
        142 => new Route(35, 1003, TRACKBED, [
            new RouteSpace(1097, 377, -61),
            new RouteSpace(1125, 322, -62),
            new RouteSpace(1153, 267, -63),
            new RouteSpace(1180, 212, -62),
        ]),
        143 => new Route(37, 41, TRACKBED, [
            new RouteSpace(452, 1273, -15),
            new RouteSpace(511, 1257, -15),
        ]),
        144 => new Route(40, 2002, RED, [
            new RouteSpace(1466, 704, 44),
        ]),
        145 => new Route(40, 2002, PINK, [
            new RouteSpace(1450, 721, 45),
        ]),
        146 => new Route(38, 39, TRACKBED, [
            new RouteSpace(417, 281, 0),
            new RouteSpace(478, 281, 0),
            new RouteSpace(540, 281, 0),
            new RouteSpace(602, 281, 0),
            new RouteSpace(663, 281, 1),
        ]),
        147 => new Route(31, 35, TRACKBED, [
            new RouteSpace(925, 425, 0),
            new RouteSpace(987, 426, 0),
        ]),
        148 => new Route(31, 35, TRACKBED, [
            new RouteSpace(925, 449, -1),
            new RouteSpace(987, 450, 2),
        ]),
        149 => new Route(28, 36, TRACKBED, [
            new RouteSpace(313, 438, -69),
            new RouteSpace(292, 497, -69),
        ]),
        150 => new Route(5, 27, TRACKBED, [
            new RouteSpace(1239, 686, -73),
            new RouteSpace(1221, 745, -73),
            new RouteSpace(1203, 804, -73),
        ]),
        151 => new Route(14, 26, TRACKBED, [
            new RouteSpace(1094, 806, -3),
            new RouteSpace(1155, 802, -3),
            new RouteSpace(1226, 797, -3),
            new RouteSpace(1287, 788, -4),
        ]),
        152 => new Route(6, 34, TRACKBED, [
            new RouteSpace(328, 986, -53),
            new RouteSpace(362, 936, -55),
            new RouteSpace(398, 886, -55),
            new RouteSpace(433, 835, -53),
        ]),
        153 => new Route(41, 5003, TRACKBED, [
            new RouteSpace(321, 1380, -57),
            new RouteSpace(287, 1430, -56),
            new RouteSpace(252, 1481, -56),
            new RouteSpace(219, 1532, -56),
        ]),
        154 => new Route(41, 5003, TRACKBED, [
            new RouteSpace(340, 1392, -56),
            new RouteSpace(306, 1443, -56),
            new RouteSpace(271, 1494, -56),
            new RouteSpace(237, 1545, -56),
        ]),
        155 => new Route(31, 38, RED, [
            new RouteSpace(786, 368, 69),
        ]),
        156 => new Route(36, 39, YELLOW, [
            new RouteSpace(331, 323, 85),
        ]),
        157 => new Route(30, 42, YELLOW, [
            new RouteSpace(656, 586, -19),
        ]),
        158 => new Route(30, 42, BLACK, [
            new RouteSpace(663, 608, -19),
        ]),
        159 => new Route(30, 42, WHITE, [
            new RouteSpace(672, 630, -20),
        ]),
    ];
}
