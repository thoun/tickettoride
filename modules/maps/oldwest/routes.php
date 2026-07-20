<?php

use Bga\Games\TicketToRide\Objects\Route;
use Bga\Games\TicketToRide\Objects\RouteSpace;

/**
 * Routes on the Old West map.
 *
 * For parallel routes, there is one Route instance per printed track.
 * City ids are always ordered from low to high.
 */
function getRoutes() {
    return [
        1 => new Route(1, 12, WHITE, [
            new RouteSpace(1251, 1298, 63),
            new RouteSpace(1225, 1243, 65),
        ]),
        2 => new Route(1, 14, BLACK, [
            new RouteSpace(1200, 1383, 4),
            new RouteSpace(1139, 1379, 5),
            new RouteSpace(1079, 1373, 5),
            new RouteSpace(1018, 1368, 5),
        ]),
        3 => new Route(1, 14, ORANGE, [
            new RouteSpace(1202, 1361, 5),
            new RouteSpace(1141, 1356, 5),
            new RouteSpace(1081, 1351, 5),
            new RouteSpace(1020, 1345, 5),
        ]),
        4 => new Route(1, 22, GRAY, [
            new RouteSpace(1309, 1483, 90),
            new RouteSpace(1310, 1543, 89),
        ]),
        5 => new Route(1, 22, GRAY, [
            new RouteSpace(1286, 1483, 90),
            new RouteSpace(1286, 1543, -90),
        ]),
        6 => new Route(1, 26, YELLOW, [
            new RouteSpace(1192, 1428, -14),
            new RouteSpace(1132, 1444, -13),
            new RouteSpace(1074, 1459, -16),
            new RouteSpace(1015, 1475, -15),
            new RouteSpace(957, 1491, -16),
        ]),
        7 => new Route(1, 30, RED, [
            new RouteSpace(1366, 1443, 42),
            new RouteSpace(1411, 1483, 42),
        ]),
        8 => new Route(1, 35, GREEN, [
            new RouteSpace(1324, 1337, -43),
        ]),
        9 => new Route(1, 35, PINK, [
            new RouteSpace(1340, 1354, -42),
        ]),
        10 => new Route(1, 39, BLUE, [
            new RouteSpace(1220, 1467, -33),
            new RouteSpace(1169, 1500, -33),
            new RouteSpace(1118, 1532, -33),
            new RouteSpace(1067, 1565, -32),
            new RouteSpace(1015, 1598, -33),
        ]),
        11 => new Route(2, 4, BLACK, [
            new RouteSpace(261, 569, -58),
            new RouteSpace(293, 518, -58),
        ]),
        12 => new Route(2, 13, YELLOW, [
            new RouteSpace(214, 552, -90),
            new RouteSpace(217, 490, -87),
        ]),
        13 => new Route(2, 13, BLUE, [
            new RouteSpace(190, 550, -87),
            new RouteSpace(193, 489, -87),
        ]),
        14 => new Route(2, 13, GREEN, [
            new RouteSpace(167, 548, -87),
            new RouteSpace(170, 488, -87),
        ]),
        15 => new Route(2, 29, RED, [
            new RouteSpace(208, 687, -90),
        ]),
        16 => new Route(2, 29, WHITE, [
            new RouteSpace(184, 687, -90),
        ]),
        17 => new Route(2, 29, ORANGE, [
            new RouteSpace(157, 690, 90),
        ]),
        18 => new Route(3, 15, YELLOW, [
            new RouteSpace(314, 1224, 76),
        ]),
        19 => new Route(3, 15, ORANGE, [
            new RouteSpace(334, 1218, 76),
        ]),
        20 => new Route(3, 15, PINK, [
            new RouteSpace(360, 1213, 76),
        ]),
        21 => new Route(3, 23, GRAY, [
            new RouteSpace(433, 1262, -10),
            new RouteSpace(493, 1252, -9),
            new RouteSpace(553, 1242, -9),
        ]),
        22 => new Route(3, 24, GREEN, [
            new RouteSpace(397, 1346, 69),
        ]),
        23 => new Route(3, 24, BLACK, [
            new RouteSpace(375, 1354, 69),
        ]),
        24 => new Route(3, 24, WHITE, [
            new RouteSpace(354, 1361, 70),
        ]),
        25 => new Route(4, 6, PINK, [
            new RouteSpace(403, 468, 13),
            new RouteSpace(462, 483, 14),
            new RouteSpace(520, 497, 14),
            new RouteSpace(579, 512, 14),
        ]),
        26 => new Route(4, 6, WHITE, [
            new RouteSpace(398, 491, 13),
            new RouteSpace(457, 505, 14),
            new RouteSpace(515, 520, 14),
            new RouteSpace(574, 535, 14),
        ]),
        27 => new Route(4, 13, GRAY, [
            new RouteSpace(263, 447, 9),
        ]),
        28 => new Route(4, 13, GRAY, [
            new RouteSpace(267, 424, 10),
        ]),
        29 => new Route(4, 28, GRAY, [
            new RouteSpace(291, 377, 61),
        ]),
        30 => new Route(4, 28, GRAY, [
            new RouteSpace(312, 365, 62),
        ]),
        31 => new Route(5, 7, RED, [
            new RouteSpace(1135, 377, -1),
            new RouteSpace(1074, 379, -2),
        ]),
        32 => new Route(5, 7, GREEN, [
            new RouteSpace(1134, 354, -1),
            new RouteSpace(1074, 355, -1),
        ]),
        33 => new Route(5, 9, YELLOW, [
            new RouteSpace(1229, 418, 61),
            new RouteSpace(1256, 473, 66),
            new RouteSpace(1283, 528, 66),
            new RouteSpace(1309, 582, 63),
        ]),
        34 => new Route(5, 9, PINK, [
            new RouteSpace(1209, 428, 64),
            new RouteSpace(1235, 483, 64),
            new RouteSpace(1261, 537, 64),
            new RouteSpace(1288, 592, 64),
        ]),
        35 => new Route(5, 16, BLUE, [
            new RouteSpace(1259, 381, 40),
            new RouteSpace(1306, 420, 40),
            new RouteSpace(1352, 459, 40),
        ]),
        36 => new Route(5, 18, GRAY, [
            new RouteSpace(1158, 303, 43),
            new RouteSpace(1114, 262, 43),
            new RouteSpace(1070, 221, 43),
        ]),
        37 => new Route(5, 41, BLACK, [
            new RouteSpace(1251, 305, -46),
            new RouteSpace(1293, 261, -46),
            new RouteSpace(1335, 217, -46),
        ]),
        38 => new Route(6, 21, BLUE, [
            new RouteSpace(725, 553, 18),
            new RouteSpace(782, 571, 18),
            new RouteSpace(840, 589, 18),
        ]),
        39 => new Route(6, 21, BLACK, [
            new RouteSpace(717, 575, 18),
            new RouteSpace(775, 594, 18),
            new RouteSpace(833, 612, 18),
        ]),
        40 => new Route(6, 25, ORANGE, [
            new RouteSpace(702, 467, -60),
            new RouteSpace(733, 414, -60),
            new RouteSpace(764, 362, -59),
            new RouteSpace(795, 309, -61),
        ]),
        41 => new Route(6, 40, RED, [
            new RouteSpace(623, 599, -64),
            new RouteSpace(596, 654, -64),
            new RouteSpace(569, 708, -64),
        ]),
        42 => new Route(7, 20, GRAY, [
            new RouteSpace(994, 316, 58),
        ]),
        43 => new Route(7, 20, GRAY, [
            new RouteSpace(974, 329, 59),
        ]),
        44 => new Route(7, 21, GRAY, [
            new RouteSpace(1002, 447, -70),
            new RouteSpace(981, 503, -70),
            new RouteSpace(960, 560, -69),
        ]),
        45 => new Route(7, 21, GRAY, [
            new RouteSpace(980, 439, -69),
            new RouteSpace(959, 495, -69),
            new RouteSpace(937, 552, -70),
        ]),
        46 => new Route(8, 15, GRAY, [
            new RouteSpace(359, 1001, -90),
            new RouteSpace(359, 1061, 90),
        ]),
        47 => new Route(8, 23, BLACK, [
            new RouteSpace(447, 984, 51),
            new RouteSpace(486, 1031, 50),
            new RouteSpace(524, 1077, 50),
            new RouteSpace(564, 1124, 50),
            new RouteSpace(603, 1170, 50),
        ]),
        48 => new Route(8, 23, RED, [
            new RouteSpace(428, 999, 50),
            new RouteSpace(467, 1046, 50),
            new RouteSpace(507, 1092, 50),
            new RouteSpace(546, 1138, 50),
            new RouteSpace(585, 1185, 50),
        ]),
        49 => new Route(8, 29, GRAY, [
            new RouteSpace(270, 810, 46),
            new RouteSpace(312, 854, 46),
        ]),
        50 => new Route(8, 31, GRAY, [
            new RouteSpace(293, 944, -21),
        ]),
        51 => new Route(8, 31, GRAY, [
            new RouteSpace(284, 922, -21),
        ]),
        52 => new Route(8, 40, ORANGE, [
            new RouteSpace(411, 863, -41),
            new RouteSpace(467, 814, -41),
        ]),
        53 => new Route(8, 40, BLUE, [
            new RouteSpace(437, 878, -42),
            new RouteSpace(482, 838, -42),
        ]),
        54 => new Route(9, 10, WHITE, [
            new RouteSpace(1388, 683, 49),
            new RouteSpace(1427, 730, 49),
        ]),
        55 => new Route(9, 10, ORANGE, [
            new RouteSpace(1369, 699, 50),
            new RouteSpace(1410, 745, 48),
        ]),
        56 => new Route(9, 16, GREEN, [
            new RouteSpace(1378, 581, -74),
        ]),
        57 => new Route(9, 19, BLUE, [
            new RouteSpace(1250, 680, -23),
            new RouteSpace(1194, 704, -23),
        ]),
        58 => new Route(10, 11, PINK, [
            new RouteSpace(1451, 871, -85),
        ]),
        59 => new Route(10, 11, BLACK, [
            new RouteSpace(1428, 869, -85),
        ]),
        60 => new Route(10, 19, RED, [
            new RouteSpace(1368, 803, 8),
            new RouteSpace(1308, 795, 8),
            new RouteSpace(1248, 787, 7),
            new RouteSpace(1188, 779, 8),
        ]),
        61 => new Route(10, 19, YELLOW, [
            new RouteSpace(1371, 780, 8),
            new RouteSpace(1311, 772, 7),
            new RouteSpace(1251, 764, 9),
            new RouteSpace(1191, 756, 7),
        ]),
        62 => new Route(11, 17, RED, [
            new RouteSpace(1354, 963, -9),
            new RouteSpace(1294, 973, -10),
            new RouteSpace(1234, 982, -9),
        ]),
        63 => new Route(11, 27, ORANGE, [
            new RouteSpace(1456, 1002, 78),
        ]),
        64 => new Route(11, 27, YELLOW, [
            new RouteSpace(1433, 1007, 76),
        ]),
        65 => new Route(11, 32, BLUE, [
            new RouteSpace(1336, 922, 10),
            new RouteSpace(1276, 911, 10),
            new RouteSpace(1217, 901, 10),
            new RouteSpace(1157, 890, 10),
            new RouteSpace(1097, 879, 10),
            new RouteSpace(1038, 869, 10),
        ]),
        66 => new Route(11, 32, GREEN, [
            new RouteSpace(1341, 899, 10),
            new RouteSpace(1281, 888, 10),
            new RouteSpace(1221, 877, 10),
            new RouteSpace(1161, 867, 10),
            new RouteSpace(1101, 856, 10),
            new RouteSpace(1041, 846, 10),
        ]),
        67 => new Route(12, 17, GREEN, [
            new RouteSpace(1168, 1113, 71),
            new RouteSpace(1157, 1048, -90),
        ]),
        68 => new Route(12, 14, PINK, [
            new RouteSpace(1126, 1210, -31),
            new RouteSpace(1075, 1242, -32),
            new RouteSpace(1023, 1274, -32),
            new RouteSpace(972, 1306, -32),
        ]),
        69 => new Route(12, 27, RED, [
            new RouteSpace(1261, 1138, -19),
            new RouteSpace(1318, 1118, -19),
            new RouteSpace(1376, 1098, -19),
        ]),
        70 => new Route(17, 38, WHITE, [
            new RouteSpace(1070, 1033, -28),
            new RouteSpace(1016, 1061, -28),
            new RouteSpace(964, 1090, -28),
            new RouteSpace(910, 1118, -28),
        ]),
        71 => new Route(13, 28, RED, [
            new RouteSpace(195, 350, -68),
        ]),
        72 => new Route(13, 28, BLACK, [
            new RouteSpace(217, 359, -68),
        ]),
        73 => new Route(13, 28, PINK, [
            new RouteSpace(239, 367, -68),
        ]),
        74 => new Route(14, 23, WHITE, [
            new RouteSpace(842, 1333, 18),
            new RouteSpace(785, 1315, 18),
            new RouteSpace(727, 1295, 18),
        ]),
        75 => new Route(14, 23, GREEN, [
            new RouteSpace(850, 1311, 17),
            new RouteSpace(792, 1293, 17),
            new RouteSpace(734, 1274, 17),
        ]),
        76 => new Route(14, 26, GRAY, [
            new RouteSpace(910, 1434, -75),
        ]),
        77 => new Route(14, 26, GRAY, [
            new RouteSpace(888, 1428, -76),
        ]),
        78 => new Route(14, 38, YELLOW, [
            new RouteSpace(891, 1272, 63),
            new RouteSpace(861, 1219, 63),
        ]),
        79 => new Route(15, 31, WHITE, [
            new RouteSpace(268, 1089, 63),
            new RouteSpace(241, 1035, 61),
        ]),
        80 => new Route(15, 31, GREEN, [
            new RouteSpace(289, 1073, 62),
            new RouteSpace(260, 1017, 62),
        ]),
        81 => new Route(15, 31, RED, [
            new RouteSpace(309, 1067, 62),
            new RouteSpace(281, 1013, 62),
        ]),
        82 => new Route(15, 34, GRAY, [
            new RouteSpace(233, 1115, 42),
            new RouteSpace(188, 1074, 42),
        ]),
        83 => new Route(15, 34, GRAY, [
            new RouteSpace(218, 1132, 42),
            new RouteSpace(172, 1093, 42),
        ]),
        84 => new Route(16, 41, PINK, [
            new RouteSpace(1406, 418, -90),
            new RouteSpace(1406, 358, -90),
            new RouteSpace(1407, 297, -90),
            new RouteSpace(1407, 236, -90),
        ]),
        85 => new Route(17, 27, PINK, [
            new RouteSpace(1246, 1022, 11),
            new RouteSpace(1306, 1033, 11),
            new RouteSpace(1365, 1045, 11),
        ]),
        86 => new Route(17, 32, BLACK, [
            new RouteSpace(1090, 962, 33),
            new RouteSpace(1039, 929, 33),
            new RouteSpace(988, 895, 33),
        ]),
        87 => new Route(18, 20, BLUE, [
            new RouteSpace(991, 227, -64),
        ]),
        88 => new Route(18, 25, WHITE, [
            new RouteSpace(945, 183, -17),
            new RouteSpace(887, 200, -17),
        ]),
        89 => new Route(18, 37, RED, [
            new RouteSpace(946, 126, 23),
            new RouteSpace(887, 106, 14),
            new RouteSpace(826, 97, 4),
            new RouteSpace(765, 98, -7),
            new RouteSpace(704, 110, -17),
        ]),
        90 => new Route(18, 41, ORANGE, [
            new RouteSpace(1086, 155, 0),
            new RouteSpace(1147, 156, 0),
            new RouteSpace(1208, 156, 0),
            new RouteSpace(1268, 156, 0),
            new RouteSpace(1329, 156, 0),
        ]),
        91 => new Route(19, 21, ORANGE, [
            new RouteSpace(1070, 707, 46),
            new RouteSpace(1022, 667, 34),
            new RouteSpace(967, 637, 24),
        ]),
        92 => new Route(19, 32, GRAY, [
            new RouteSpace(1029, 799, -17),
        ]),
        93 => new Route(19, 32, GRAY, [
            new RouteSpace(1022, 776, -18),
        ]),
        94 => new Route(20, 21, WHITE, [
            new RouteSpace(931, 355, -81),
            new RouteSpace(921, 414, -79),
            new RouteSpace(910, 475, -81),
            new RouteSpace(901, 535, -80),
        ]),
        95 => new Route(20, 25, BLACK, [
            new RouteSpace(891, 270, 11),
        ]),
        96 => new Route(20, 25, YELLOW, [
            new RouteSpace(895, 246, 11),
        ]),
        97 => new Route(21, 32, GRAY, [
            new RouteSpace(906, 702, 81),
            new RouteSpace(917, 762, 81),
        ]),
        98 => new Route(21, 32, GRAY, [
            new RouteSpace(930, 698, 81),
            new RouteSpace(940, 758, 81),
        ]),
        99 => new Route(22, 30, GREEN, [
            new RouteSpace(1366, 1595, -29),
            new RouteSpace(1419, 1565, -29),
        ]),
        100 => new Route(23, 24, GRAY, [
            new RouteSpace(578, 1286, -32),
            new RouteSpace(527, 1318, -32),
            new RouteSpace(475, 1351, -32),
        ]),
        101 => new Route(23, 24, GRAY, [
            new RouteSpace(591, 1306, -31),
            new RouteSpace(539, 1338, -32),
            new RouteSpace(488, 1370, -31),
        ]),
        102 => new Route(23, 38, BLUE, [
            new RouteSpace(702, 1199, -27),
            new RouteSpace(756, 1170, -27),
        ]),
        103 => new Route(23, 38, ORANGE, [
            new RouteSpace(713, 1220, -26),
            new RouteSpace(768, 1191, -27),
        ]),
        104 => new Route(24, 26, GRAY, [
            new RouteSpace(503, 1418, 8),
            new RouteSpace(563, 1427, 7),
            new RouteSpace(623, 1435, 8),
            new RouteSpace(683, 1443, 8),
            new RouteSpace(743, 1452, 7),
            new RouteSpace(803, 1460, 8),
        ]),
        105 => new Route(24, 26, GRAY, [
            new RouteSpace(499, 1442, 7),
            new RouteSpace(559, 1450, 8),
            new RouteSpace(619, 1458, 8),
            new RouteSpace(679, 1467, 8),
            new RouteSpace(739, 1475, 8),
            new RouteSpace(800, 1484, 7),
        ]),
        106 => new Route(24, 33, YELLOW, [
            new RouteSpace(451, 1477, 56),
        ]),
        107 => new Route(24, 33, BLUE, [
            new RouteSpace(431, 1488, 59),
        ]),
        108 => new Route(24, 33, RED, [
            new RouteSpace(411, 1500, 59),
        ]),
        109 => new Route(24, 34, GRAY, [
            new RouteSpace(320, 1411, 16),
            new RouteSpace(266, 1384, 36),
            new RouteSpace(222, 1338, 55),
            new RouteSpace(191, 1285, 67),
            new RouteSpace(168, 1229, 68),
            new RouteSpace(147, 1172, 71),
            new RouteSpace(128, 1114, 75),
        ]),
        110 => new Route(25, 37, PINK, [
            new RouteSpace(748, 216, 26),
            new RouteSpace(693, 189, 26),
        ]),
        111 => new Route(25, 37, GREEN, [
            new RouteSpace(759, 195, 25),
            new RouteSpace(704, 168, 26),
        ]),
        112 => new Route(26, 33, PINK, [
            new RouteSpace(796, 1549, 0),
            new RouteSpace(736, 1550, 0),
            new RouteSpace(675, 1549, 0),
            new RouteSpace(615, 1549, 0),
            new RouteSpace(554, 1549, 0),
        ]),
        113 => new Route(26, 33, ORANGE, [
            new RouteSpace(798, 1524, 0),
            new RouteSpace(736, 1526, 0),
            new RouteSpace(675, 1526, 0),
            new RouteSpace(615, 1525, 0),
            new RouteSpace(554, 1525, 0),
        ]),
        114 => new Route(26, 39, RED, [
            new RouteSpace(928, 1573, 55),
        ]),
        115 => new Route(26, 39, WHITE, [
            new RouteSpace(909, 1587, 54),
        ]),
        116 => new Route(27, 35, BLACK, [
            new RouteSpace(1447, 1138, -70),
            new RouteSpace(1427, 1195, -70),
            new RouteSpace(1406, 1252, -70),
        ]),
        117 => new Route(27, 35, BLUE, [
            new RouteSpace(1425, 1130, -70),
            new RouteSpace(1404, 1187, -70),
            new RouteSpace(1383, 1244, -70),
        ]),
        118 => new Route(28, 36, GREEN, [
            new RouteSpace(245, 222, -78),
            new RouteSpace(257, 163, -79),
        ]),
        119 => new Route(28, 36, ORANGE, [
            new RouteSpace(268, 228, -79),
            new RouteSpace(280, 168, -79),
        ]),
        120 => new Route(28, 36, YELLOW, [
            new RouteSpace(291, 231, -77),
            new RouteSpace(303, 172, -80),
        ]),
        121 => new Route(6, 36, GRAY, [
            new RouteSpace(591, 466, 49),
            new RouteSpace(551, 419, 50),
            new RouteSpace(512, 373, 50),
            new RouteSpace(473, 327, 50),
            new RouteSpace(433, 281, 49),
            new RouteSpace(394, 234, 49),
            new RouteSpace(355, 188, 49),
        ]),
        122 => new Route(6, 36, GRAY, [
            new RouteSpace(608, 450, 51),
            new RouteSpace(569, 404, 49),
            new RouteSpace(530, 358, 49),
            new RouteSpace(491, 312, 50),
            new RouteSpace(452, 265, 50),
            new RouteSpace(412, 220, 49),
            new RouteSpace(373, 173, 50),
        ]),
        123 => new Route(29, 31, BLUE, [
            new RouteSpace(220, 824, 80),
            new RouteSpace(230, 884, 80),
        ]),
        124 => new Route(29, 31, BLACK, [
            new RouteSpace(197, 828, 80),
            new RouteSpace(207, 888, 80),
        ]),
        125 => new Route(29, 31, PINK, [
            new RouteSpace(174, 832, 80),
            new RouteSpace(184, 892, 80),
        ]),
        126 => new Route(29, 34, YELLOW, [
            new RouteSpace(120, 829, -73),
            new RouteSpace(110, 890, -86),
            new RouteSpace(109, 951, 85),
        ]),
        127 => new Route(29, 40, GREEN, [
            new RouteSpace(264, 757, 0),
            new RouteSpace(325, 758, 0),
            new RouteSpace(386, 757, 0),
            new RouteSpace(446, 758, 0),
        ]),
        128 => new Route(30, 35, YELLOW, [
            new RouteSpace(1472, 1467, 75),
            new RouteSpace(1449, 1411, 63),
            new RouteSpace(1415, 1359, 53),
        ]),
        129 => new Route(31, 34, GRAY, [
            new RouteSpace(181, 999, -37),
        ]),
        130 => new Route(31, 34, GRAY, [
            new RouteSpace(166, 980, -39),
        ]),
        131 => new Route(32, 38, RED, [
            new RouteSpace(917, 912, -70),
            new RouteSpace(896, 969, -71),
            new RouteSpace(876, 1026, -70),
            new RouteSpace(855, 1084, -71),
        ]),
        132 => new Route(32, 38, PINK, [
            new RouteSpace(894, 904, -71),
            new RouteSpace(874, 961, -70),
            new RouteSpace(854, 1018, -71),
            new RouteSpace(833, 1075, -70),
        ]),
        133 => new Route(32, 40, YELLOW, [
            new RouteSpace(851, 827, 7),
            new RouteSpace(791, 818, 10),
            new RouteSpace(732, 809, 9),
            new RouteSpace(670, 800, 5),
            new RouteSpace(611, 791, 7),
        ]),
        134 => new Route(32, 40, WHITE, [
            new RouteSpace(855, 805, 9),
            new RouteSpace(794, 795, 9),
            new RouteSpace(734, 786, 8),
            new RouteSpace(674, 777, 9),
            new RouteSpace(614, 768, 8),
        ]),
        135 => new Route(33, 39, GREEN, [
            new RouteSpace(560, 1592, 8),
            new RouteSpace(620, 1600, 8),
            new RouteSpace(680, 1609, 8),
            new RouteSpace(740, 1617, 8),
            new RouteSpace(800, 1626, 9),
            new RouteSpace(860, 1634, 8),
        ]),
        136 => new Route(36, 37, BLUE, [
            new RouteSpace(372, 72, 7),
            new RouteSpace(432, 80, 7),
            new RouteSpace(493, 88, 7),
            new RouteSpace(553, 96, 7),
        ]),
        137 => new Route(36, 37, WHITE, [
            new RouteSpace(370, 96, 7),
            new RouteSpace(430, 103, 8),
            new RouteSpace(490, 111, 6),
            new RouteSpace(550, 119, 7),
        ]),
        138 => new Route(36, 37, BLACK, [
            new RouteSpace(366, 119, 7),
            new RouteSpace(426, 127, 7),
            new RouteSpace(487, 134, 8),
            new RouteSpace(547, 143, 7),
        ]),
        139 => new Route(22, 39, ORANGE, [
            new RouteSpace(1223, 1633, -2),
            new RouteSpace(1162, 1636, -3),
            new RouteSpace(1101, 1639, -4),
            new RouteSpace(1040, 1643, -3),
        ]),
    ];
}
