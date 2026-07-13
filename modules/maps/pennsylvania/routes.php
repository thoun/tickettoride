<?php

use Bga\Games\TicketToRide\Objects\Route;
use Bga\Games\TicketToRide\Objects\RouteSpace;

/**
 * Route on the map.
 *
 * For double routes, there is 2 instances of Route.
 * For cities (from/to), it's always low id to high id.
 */
function getRoutes() {
    return [
        1 => new Route(1, 6, PINK, [
            new RouteSpace(1642, 133, -20),
            new RouteSpace(1583, 154, -20),
            new RouteSpace(1523, 176, -20),
            new RouteSpace(1464, 198, -20),
            new RouteSpace(1405, 219, -20),
            new RouteSpace(1346, 241, -20),
        ], stockShares: []),
        2 => new Route(1, 20, BLUE, [
            new RouteSpace(1677, 176, -90),
            new RouteSpace(1676, 238, 90),
            new RouteSpace(1677, 302, 89),
            new RouteSpace(1678, 364, -90),
            new RouteSpace(1677, 429, -90),
            new RouteSpace(1677, 492, -90),
        ], stockShares: [5, 10, 15]),
        3 => new Route(1, 20, GREEN, [
            new RouteSpace(1697, 174, -90),
            new RouteSpace(1697, 238, -90),
            new RouteSpace(1698, 301, -90),
            new RouteSpace(1698, 365, -90),
            new RouteSpace(1698, 427, -90),
            new RouteSpace(1698, 491, -90),
        ], stockShares: [5, 10, 15]),
        4 => new Route(1, 28, WHITE, [
            new RouteSpace(1312, 44, 6),
            new RouteSpace(1375, 51, 6),
            new RouteSpace(1438, 57, 6),
            new RouteSpace(1501, 64, 6),
            new RouteSpace(1564, 70, 6),
            new RouteSpace(1627, 76, 6),
        ], stockShares: [5]),
        5 => new Route(1, 28, RED, [
            new RouteSpace(1311, 67, 6),
            new RouteSpace(1374, 74, 6),
            new RouteSpace(1437, 80, 6),
            new RouteSpace(1500, 86, 4),
            new RouteSpace(1562, 93, 5),
            new RouteSpace(1625, 99, 6),
        ], stockShares: [5]),
        6 => new Route(2, 22, RED, [
            new RouteSpace(1405, 792, 63),
            new RouteSpace(1434, 848, 63),
            new RouteSpace(1463, 905, 63),
        ], stockShares: [15, 7]),
        7 => new Route(2, 22, BLACK, [
            new RouteSpace(1385, 802, 63),
            new RouteSpace(1414, 858, 63),
            new RouteSpace(1443, 915, 63),
        ], stockShares: [15, 7]),
        8 => new Route(2, 24, GREEN, [
            new RouteSpace(1255, 790, -21),
            new RouteSpace(1313, 768, -20),
        ], stockShares: [7]),
        9 => new Route(2, 26, WHITE, [
            new RouteSpace(1320, 563, -86),
            new RouteSpace(1322, 628, 84),
            new RouteSpace(1334, 691, 74),
        ], stockShares: [3, 6, 15, 7]),
        10 => new Route(2, 26, BLUE, [
            new RouteSpace(1344, 562, -86),
            new RouteSpace(1344, 624, 84),
            new RouteSpace(1357, 690, 74),
        ], stockShares: [3, 6, 15, 7]),
        11 => new Route(2, 27, ORANGE, [
            new RouteSpace(1413, 713, -44),
            new RouteSpace(1458, 669, -44),
        ], stockShares: [6, 3, 7]),
        12 => new Route(3, 11, GRAY, [
            new RouteSpace(579, 650, 65),
            new RouteSpace(606, 704, 64),
        ], stockShares: [15]),
        13 => new Route(3, 15, RED, [
            new RouteSpace(697, 782, 24),
            new RouteSpace(756, 804, 18),
            new RouteSpace(819, 824, 14),
            new RouteSpace(882, 835, 9),
            new RouteSpace(946, 843, 4),
        ], stockShares: [15]),
        14 => new Route(3, 15, ORANGE, [
            new RouteSpace(697, 804, 24),
            new RouteSpace(756, 826, 18),
            new RouteSpace(814, 843, 14),
            new RouteSpace(878, 861, 6),
            new RouteSpace(942, 868, 1),
        ], stockShares: [15]),
        15 => new Route(3, 16, YELLOW, [
            new RouteSpace(572, 793, -25),
        ], stockShares: [15]),
        16 => new Route(3, 16, BLUE, [
            new RouteSpace(586, 813, -25),
        ], stockShares: [15]),
        17 => new Route(3, 18, GREEN, [
            new RouteSpace(707, 740, 0),
            new RouteSpace(770, 740, 0),
        ], stockShares: []),
        18 => new Route(4, 20, BLACK, [
            new RouteSpace(1676, 619, -90),
            new RouteSpace(1677, 682, -90),
            new RouteSpace(1677, 746, -90),
            new RouteSpace(1677, 810, -90),
            new RouteSpace(1677, 872, -90),
            new RouteSpace(1676, 936, 90),
        ], stockShares: [3]),
        19 => new Route(4, 20, WHITE, [
            new RouteSpace(1700, 619, -90),
            new RouteSpace(1700, 682, -90),
            new RouteSpace(1700, 746, -90),
            new RouteSpace(1700, 809, -90),
            new RouteSpace(1698, 872, 90),
            new RouteSpace(1699, 935, -90),
        ], stockShares: [3]),
        20 => new Route(4, 22, GRAY, [
            new RouteSpace(1561, 970, 6),
            new RouteSpace(1622, 977, 5),
        ], stockShares: [15, 7, 3]),
        21 => new Route(4, 22, GRAY, [
            new RouteSpace(1561, 993, 5),
            new RouteSpace(1620, 1000, 5),
        ], stockShares: [15, 7, 3]),
        22 => new Route(5, 10, BLUE, [
            new RouteSpace(697, 1082, 0),
            new RouteSpace(762, 1084, 0),
            new RouteSpace(822, 1084, 0),
            new RouteSpace(887, 1084, 0),
            new RouteSpace(951, 1084, 0),
            new RouteSpace(1015, 1084, 0),
            new RouteSpace(1078, 1082, 0),
        ], stockShares: [4, 10]),
        23 => new Route(5, 14, RED, [
            new RouteSpace(997, 1027, 20),
            new RouteSpace(1057, 1048, 20),
            new RouteSpace(1117, 1069, 19),
        ], stockShares: [4, 15]),
        24 => new Route(5, 22, YELLOW, [
            new RouteSpace(1241, 1068, -4),
            new RouteSpace(1305, 1059, -11),
            new RouteSpace(1367, 1043, -18),
            new RouteSpace(1426, 1019, -26),
        ], stockShares: [15, 10]),
        25 => new Route(5, 22, PINK, [
            new RouteSpace(1247, 1090, -5),
            new RouteSpace(1310, 1081, -13),
            new RouteSpace(1372, 1064, -18),
            new RouteSpace(1432, 1040, -26),
        ], stockShares: [15, 10]),
        26 => new Route(5, 33, WHITE, [
            new RouteSpace(1099, 999, 44),
            new RouteSpace(1144, 1043, 44),
        ], stockShares: [15]),
        27 => new Route(6, 12, WHITE, [
            new RouteSpace(1096, 256, 0),
            new RouteSpace(1159, 256, 0),
            new RouteSpace(1222, 256, 0),
        ], stockShares: [8]),
        28 => new Route(6, 26, BLACK, [
            new RouteSpace(1295, 312, 80),
            new RouteSpace(1307, 375, 80),
            new RouteSpace(1317, 436, 80),
        ], stockShares: [8]),
        29 => new Route(6, 26, GREEN, [
            new RouteSpace(1317, 312, 80),
            new RouteSpace(1329, 375, 80),
            new RouteSpace(1339, 436, 80),
        ], stockShares: [8]),
        30 => new Route(6, 28, YELLOW, [
            new RouteSpace(1262, 130, 80),
            new RouteSpace(1273, 192, 80),
        ], stockShares: [8]),
        31 => new Route(6, 28, ORANGE, [
            new RouteSpace(1284, 126, 80),
            new RouteSpace(1294, 187, 81),
        ], stockShares: [8]),
        32 => new Route(6, 29, RED, [
            new RouteSpace(1233, 301, -33),
            new RouteSpace(1179, 337, -33),
        ], stockShares: [8]),
        33 => new Route(7, 9, GRAY, [
            new RouteSpace(610, 129, 62),
            new RouteSpace(640, 184, 62),
            new RouteSpace(670, 239, 62),
            new RouteSpace(701, 296, 61),
        ], stockShares: [15, 8]),
        34 => new Route(7, 13, ORANGE, [
            new RouteSpace(500, 86, -40),
            new RouteSpace(449, 123, -34),
            new RouteSpace(394, 157, -31),
            new RouteSpace(335, 187, -26),
            new RouteSpace(277, 209, -19),
        ], stockShares: [8, 5]),
        35 => new Route(7, 13, WHITE, [
            new RouteSpace(513, 105, -39),
            new RouteSpace(462, 143, -34),
            new RouteSpace(405, 178, -29),
            new RouteSpace(346, 207, -24),
            new RouteSpace(286, 231, -19),
        ], stockShares: [8, 5]),
        36 => new Route(7, 25, YELLOW, [
            new RouteSpace(622, 44, 0),
            new RouteSpace(685, 44, 0),
            new RouteSpace(748, 45, 0),
            new RouteSpace(811, 45, 0),
            new RouteSpace(874, 45, 0),
        ], stockShares: [8, 10, 6, 5, 2]),
        37 => new Route(7, 25, BLACK, [
            new RouteSpace(622, 67, 0),
            new RouteSpace(686, 66, 0),
            new RouteSpace(748, 66, 0),
            new RouteSpace(812, 68, -1),
            new RouteSpace(874, 68, 0),
        ], stockShares: [8, 10, 6, 5, 2]),
        38 => new Route(7, 30, GREEN, [
            new RouteSpace(553, 125, -63),
            new RouteSpace(524, 181, -63),
            new RouteSpace(496, 238, -63),
            new RouteSpace(468, 294, -63),
        ], stockShares: [15, 8, 10, 2]),
        39 => new Route(7, 1001, GRAY, [
            new RouteSpace(464, 40, 0),
        ], stockShares: [15, 8, 5]),
        40 => new Route(7, 1001, GRAY, [
            new RouteSpace(464, 65, 0),
        ], stockShares: [15, 8, 5]),
        41 => new Route(8, 10, GREEN, [
            new RouteSpace(704, 1036, -37),
            new RouteSpace(754, 999, -36),
        ], stockShares: [4, 15]),
        42 => new Route(8, 14, BLACK, [
            new RouteSpace(878, 988, 19),
        ], stockShares: [4, 15]),
        43 => new Route(8, 15, BLUE, [
            new RouteSpace(869, 933, -25),
            new RouteSpace(923, 907, -25),
        ], stockShares: [4, 15]),
        44 => new Route(9, 12, ORANGE, [
            new RouteSpace(788, 338, -20),
            new RouteSpace(846, 317, -19),
            new RouteSpace(906, 296, -20),
            new RouteSpace(964, 275, -20),
        ], stockShares: [15, 8, 10, 5, 2]),
        45 => new Route(9, 30, GRAY, [
            new RouteSpace(680, 364, -11),
            new RouteSpace(616, 373, 0),
            new RouteSpace(551, 371, 3),
            new RouteSpace(489, 363, 11),
        ], stockShares: [15, 8, 10, 5, 2]),
        46 => new Route(9, 32, GREEN, [
            new RouteSpace(778, 395, 36),
            new RouteSpace(829, 432, 36),
            new RouteSpace(880, 469, 37),
            new RouteSpace(930, 506, 37),
        ], stockShares: [15]),
        47 => new Route(10, 16, GRAY, [
            new RouteSpace(557, 899, 63),
            new RouteSpace(586, 954, 63),
            new RouteSpace(615, 1009, 63),
        ], stockShares: [4, 15, 10]),
        48 => new Route(10, 19, RED, [
            new RouteSpace(314, 1088, -1),
            new RouteSpace(378, 1087, -1),
            new RouteSpace(441, 1086, -1),
            new RouteSpace(504, 1085, -1),
            new RouteSpace(567, 1084, 0),
        ], stockShares: [4, 10]),
        49 => new Route(11, 21, PINK, [
            new RouteSpace(361, 524, 21),
            new RouteSpace(420, 547, 21),
            new RouteSpace(479, 570, 22),
        ], stockShares: []),
        50 => new Route(11, 30, BLACK, [
            new RouteSpace(468, 413, 65),
            new RouteSpace(495, 471, 65),
            new RouteSpace(522, 527, 65),
        ], stockShares: [8, 2]),
        51 => new Route(11, 32, WHITE, [
            new RouteSpace(604, 586, -6),
            new RouteSpace(667, 580, -6),
            new RouteSpace(729, 573, -6),
            new RouteSpace(792, 567, -6),
            new RouteSpace(855, 560, -6),
            new RouteSpace(918, 553, -6),
        ], stockShares: []),
        52 => new Route(12, 25, GREEN, [
            new RouteSpace(927, 102, -77),
            new RouteSpace(933, 172, 68),
            new RouteSpace(974, 223, 33),
        ], stockShares: [15, 8, 10, 2]),
        53 => new Route(12, 28, BLACK, [
            new RouteSpace(1207, 107, -38),
            new RouteSpace(1157, 147, -38),
            new RouteSpace(1106, 187, -38),
            new RouteSpace(1056, 227, -38),
        ], stockShares: [8, 6]),
        54 => new Route(12, 29, YELLOW, [
            new RouteSpace(1031, 309, 75),
            new RouteSpace(1074, 360, 27),
        ], stockShares: [15, 8, 6]),
        55 => new Route(13, 21, BLACK, [
            new RouteSpace(233, 311, 70),
            new RouteSpace(254, 371, 70),
            new RouteSpace(275, 431, 70),
        ], stockShares: [15, 8, 5]),
        56 => new Route(13, 30, BLUE, [
            new RouteSpace(270, 276, 23),
            new RouteSpace(328, 300, 21),
            new RouteSpace(387, 324, 21),
        ], stockShares: [15, 8, 5]),
        57 => new Route(13, 34, YELLOW, [
            new RouteSpace(161, 319, -63),
            new RouteSpace(133, 377, -63),
            new RouteSpace(104, 432, -63),
            new RouteSpace(76, 488, -63),
        ], stockShares: [5, 8]),
        58 => new Route(13, 34, GREEN, [
            new RouteSpace(181, 329, -63),
            new RouteSpace(152, 385, -63),
            new RouteSpace(123, 441, -63),
            new RouteSpace(95, 498, -63),
        ], stockShares: [5, 8]),
        59 => new Route(13, 1002, GRAY, [
            new RouteSpace(109, 102, 59),
            new RouteSpace(142, 157, 59),
            new RouteSpace(174, 210, 59),
        ], stockShares: [15, 8, 5]),
        60 => new Route(13, 1002, GRAY, [
            new RouteSpace(128, 87, 59),
            new RouteSpace(162, 141, 59),
            new RouteSpace(194, 195, 59),
        ], stockShares: [15, 8, 5]),
        61 => new Route(14, 15, YELLOW, [
            new RouteSpace(983, 903, -64),
            new RouteSpace(955, 959, -63),
        ], stockShares: [7, 15]),
        62 => new Route(14, 33, GRAY, [
            new RouteSpace(1008, 983, -22),
        ], stockShares: [15]),
        63 => new Route(15, 18, GRAY, [
            new RouteSpace(914, 775, 29),
            new RouteSpace(968, 804, 31),
        ], stockShares: [15]),
        64 => new Route(15, 24, PINK, [
            new RouteSpace(1086, 825, -5),
            new RouteSpace(1147, 820, -4),
        ], stockShares: [15]),
        65 => new Route(15, 26, GRAY, [
            new RouteSpace(1061, 792, -47),
            new RouteSpace(1103, 746, -46),
            new RouteSpace(1146, 698, -47),
            new RouteSpace(1190, 652, -47),
            new RouteSpace(1233, 607, -46),
            new RouteSpace(1276, 561, -48),
        ], stockShares: [7, 15]),
        66 => new Route(15, 33, BLACK, [
            new RouteSpace(1032, 915, 63),
        ], stockShares: [4]),
        67 => new Route(16, 23, PINK, [
            new RouteSpace(282, 808, 17),
            new RouteSpace(339, 819, 9),
            new RouteSpace(404, 824, 2),
            new RouteSpace(467, 824, -5),
        ], stockShares: [15, 10]),
        68 => new Route(16, 23, BLACK, [
            new RouteSpace(276, 828, 16),
            new RouteSpace(339, 842, 9),
            new RouteSpace(403, 848, 2),
            new RouteSpace(467, 845, -5),
        ], stockShares: [15, 10]),
        69 => new Route(17, 22, GREEN, [
            new RouteSpace(1224, 938, 18),
            new RouteSpace(1283, 956, 12),
            new RouteSpace(1348, 969, 3),
            new RouteSpace(1412, 971, -3),
        ], stockShares: [15]),
        70 => new Route(17, 22, ORANGE, [
            new RouteSpace(1222, 959, 17),
            new RouteSpace(1283, 977, 8),
            new RouteSpace(1348, 991, -3),
            new RouteSpace(1412, 993, -3),
        ], stockShares: [15]),
        71 => new Route(17, 24, YELLOW, [
            new RouteSpace(1191, 873, 82),
        ], stockShares: [7]),
        72 => new Route(17, 33, PINK, [
            new RouteSpace(1122, 955, -9),
        ], stockShares: [15]),
        73 => new Route(18, 32, YELLOW, [
            new RouteSpace(876, 702, -54),
            new RouteSpace(914, 651, -54),
            new RouteSpace(951, 600, -54),
        ], stockShares: [7, 15]),
        74 => new Route(19, 23, YELLOW, [
            new RouteSpace(221, 887, 85),
            new RouteSpace(227, 950, 85),
            new RouteSpace(232, 1012, 85),
        ], stockShares: []),
        75 => new Route(19, 31, BLUE, [
            new RouteSpace(91, 981, 63),
            new RouteSpace(131, 1033, 43),
            new RouteSpace(186, 1069, 23),
        ], stockShares: [10]),
        76 => new Route(20, 26, RED, [
            new RouteSpace(1390, 486, 0),
            new RouteSpace(1454, 489, 4),
            new RouteSpace(1520, 499, 10),
            new RouteSpace(1585, 513, 15),
            new RouteSpace(1646, 532, 20),
        ], stockShares: [8, 6, 3]),
        77 => new Route(20, 26, PINK, [
            new RouteSpace(1388, 509, 0),
            new RouteSpace(1452, 511, 5),
            new RouteSpace(1515, 519, 10),
            new RouteSpace(1578, 532, 15),
            new RouteSpace(1638, 550, 20),
        ], stockShares: [8, 6, 3]),
        78 => new Route(21, 23, RED, [
            new RouteSpace(281, 550, -80),
            new RouteSpace(271, 613, -82),
            new RouteSpace(260, 676, -79),
            new RouteSpace(249, 737, -80),
        ], stockShares: [10, 15]),
        79 => new Route(21, 30, ORANGE, [
            new RouteSpace(393, 396, -46),
            new RouteSpace(350, 440, -46),
        ], stockShares: [15, 8, 10]),
        80 => new Route(21, 34, WHITE, [
            new RouteSpace(231, 509, -15),
            new RouteSpace(170, 526, -15),
            new RouteSpace(109, 542, -15),
        ], stockShares: [8]),
        81 => new Route(22, 27, GRAY, [
            new RouteSpace(1622, 605, -70),
            new RouteSpace(1601, 663, -69),
            new RouteSpace(1578, 723, -69),
            new RouteSpace(1556, 782, -70),
            new RouteSpace(1537, 841, -69),
            new RouteSpace(1515, 901, -68),
        ], stockShares: [3, 7, 10, 15]),
        82 => new Route(22, 27, GRAY, [
            new RouteSpace(1642, 612, -70),
            new RouteSpace(1621, 671, -70),
            new RouteSpace(1599, 731, -69),
            new RouteSpace(1577, 790, -69),
            new RouteSpace(1556, 849, -70),
            new RouteSpace(1534, 909, -70),
        ], stockShares: [3, 7, 10, 15]),
        83 => new Route(23, 31, GREEN, [
            new RouteSpace(110, 874, -38),
            new RouteSpace(159, 835, -38),
        ], stockShares: [15]),
        84 => new Route(23, 31, WHITE, [
            new RouteSpace(124, 893, -38),
            new RouteSpace(174, 854, -38),
        ], stockShares: [15]),
        85 => new Route(23, 34, ORANGE, [
            new RouteSpace(66, 608, 63),
            new RouteSpace(96, 665, 57),
            new RouteSpace(132, 716, 52),
            new RouteSpace(176, 765, 44),
        ], stockShares: [15, 10, 5]),
        86 => new Route(23, 34, BLUE, [
            new RouteSpace(83, 598, 65),
            new RouteSpace(114, 655, 58),
            new RouteSpace(152, 707, 52),
            new RouteSpace(195, 754, 44),
        ], stockShares: [15, 10, 5]),
        87 => new Route(24, 33, GRAY, [
            new RouteSpace(1077, 864, 26),
            new RouteSpace(1131, 891, 27),
        ], stockShares: [15]),
        88 => new Route(24, 33, GRAY, [
            new RouteSpace(1067, 882, 26),
            new RouteSpace(1122, 911, 26),
        ], stockShares: [15]),
        89 => new Route(25, 28, PINK, [
            new RouteSpace(998, 44, 0),
            new RouteSpace(1061, 44, 0),
            new RouteSpace(1125, 44, 0),
            new RouteSpace(1188, 44, 0),
        ], stockShares: [6, 5]),
        90 => new Route(25, 28, BLUE, [
            new RouteSpace(998, 67, 0),
            new RouteSpace(1061, 67, 0),
            new RouteSpace(1125, 67, 0),
            new RouteSpace(1188, 67, 0),
        ], stockShares: [6, 5]),
        91 => new Route(26, 27, YELLOW, [
            new RouteSpace(1396, 553, 39),
            new RouteSpace(1445, 592, 39),
        ], stockShares: [7, 6, 3]),
        92 => new Route(26, 29, GRAY, [
            new RouteSpace(1175, 408, 32),
            new RouteSpace(1226, 440, 32),
            new RouteSpace(1280, 472, 32),
        ], stockShares: [15, 8, 6]),
        93 => new Route(26, 32, ORANGE, [
            new RouteSpace(1030, 540, -6),
            new RouteSpace(1090, 534, -6),
            new RouteSpace(1154, 527, -6),
            new RouteSpace(1216, 522, -5),
        ], stockShares: []),
        94 => new Route(29, 32, BLACK, [
            new RouteSpace(1031, 482, -46),
            new RouteSpace(1075, 436, -46),
        ], stockShares: [7, 8]),
        95 => new Route(31, 34, PINK, [
            new RouteSpace(27, 613, -85),
            new RouteSpace(24, 676, 90),
            new RouteSpace(27, 743, 85),
            new RouteSpace(36, 808, 81),
            new RouteSpace(50, 871, 76),
        ], stockShares: [5, 10, 15]),
    ];
}
