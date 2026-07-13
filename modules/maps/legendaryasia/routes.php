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
        1 => new Route(22, 25, GRAY, [
            new RouteSpace(171, 34, -3),
            new RouteSpace(233, 31, -3),
            new RouteSpace(296, 28, -2),
        ]),
        2 => new Route(22, 25, RED, [
            new RouteSpace(172, 58, -3),
            new RouteSpace(234, 55, -3),
            new RouteSpace(298, 50, -4),
        ], mountain: 1),
        3 => new Route(3, 22, ORANGE, [
            new RouteSpace(180, 216, 45),
            new RouteSpace(140, 167, 52),
            new RouteSpace(105, 114, 62),
        ]),
        4 => new Route(3, 22, GREEN, [
            new RouteSpace(195, 199, 44),
            new RouteSpace(157, 152, 53),
            new RouteSpace(124, 100, 62),
        ]),
        5 => new Route(23, 25, GRAY, [
            new RouteSpace(425, 27, 1),
            new RouteSpace(487, 28, 1),
            new RouteSpace(550, 29, 1),
        ]),
        6 => new Route(23, 25, WHITE, [
            new RouteSpace(424, 50, 0),
            new RouteSpace(488, 51, 1),
            new RouteSpace(551, 52, 1),
        ], mountain: 1),
        7 => new Route(3, 25, BLUE, [
            new RouteSpace(330, 86, -45),
            new RouteSpace(290, 136, -57),
            new RouteSpace(259, 191, -65),
        ]),
        8 => new Route(17, 23, GRAY, [
            new RouteSpace(680, 32, 1),
            new RouteSpace(742, 34, 1),
            new RouteSpace(805, 36, 2),
        ]),
        9 => new Route(17, 23, PINK, [
            new RouteSpace(678, 55, 1),
            new RouteSpace(741, 56, 1),
            new RouteSpace(803, 56, -2),
        ], mountain: 1),
        10 => new Route(23, 29, GREEN, [
            new RouteSpace(599, 110, -87),
            new RouteSpace(592, 172, -84),
            new RouteSpace(583, 235, -82),
            new RouteSpace(575, 300, -83),
        ], mountain: 1),
        11 => new Route(10, 23, ORANGE, [
            new RouteSpace(640, 100, 68),
            new RouteSpace(672, 157, 53),
            new RouteSpace(716, 205, 42),
            new RouteSpace(767, 243, 31),
            new RouteSpace(826, 270, 19),
        ]),
        12 => new Route(12, 17, GRAY, [
            new RouteSpace(930, 39, 3),
            new RouteSpace(992, 42, 3),
            new RouteSpace(1055, 45, 3),
        ]),
        13 => new Route(12, 17, BLUE, [
            new RouteSpace(932, 60, 1),
            new RouteSpace(994, 63, 2),
            new RouteSpace(1057, 70, 2),
        ], mountain: 1),
        14 => new Route(10, 17, BLACK, [
            new RouteSpace(863, 105, -87),
            new RouteSpace(864, 169, 85),
            new RouteSpace(874, 233, 77),
        ]),
        15 => new Route(8, 12, GRAY, [
            new RouteSpace(1170, 62, 17),
            new RouteSpace(1233, 70, 0),
        ]),
        16 => new Route(12, 37, YELLOW, [
            new RouteSpace(1098, 112, -72),
            new RouteSpace(1116, 172, 38),
        ], mountain: 2),
        17 => new Route(8, 37, PINK, [
            new RouteSpace(1260, 130, -49),
            new RouteSpace(1210, 171, -32),
        ]),
        18 => new Route(8, 15, RED, [
            new RouteSpace(1343, 70, -14),
            new RouteSpace(1407, 61, -3),
            new RouteSpace(1472, 63, 5),
            new RouteSpace(1536, 73, 13),
            new RouteSpace(1596, 94, 24),
        ]),
        19 => new Route(24, 37, RED, [
            new RouteSpace(1209, 243, 33),
            new RouteSpace(1258, 280, 41),
            new RouteSpace(1290, 345, 49),
        ], mountain: 1),
        20 => new Route(24, 37, ORANGE, [
            new RouteSpace(1223, 223, 33),
            new RouteSpace(1272, 261, 41),
            new RouteSpace(1304, 325, 49),
        ], mountain: 1),
        21 => new Route(15, 38, GRAY, [
            new RouteSpace(1644, 178, -78),
            new RouteSpace(1622, 238, -62),
        ]),
        22 => new Route(15, 38, GRAY, [
            new RouteSpace(1666, 185, -78),
            new RouteSpace(1644, 247, -62),
        ], mountain: 1),
        23 => new Route(15, 24, BLACK, [
            new RouteSpace(1595, 157, -41),
            new RouteSpace(1546, 197, -39),
            new RouteSpace(1496, 237, -39),
            new RouteSpace(1447, 277, -39),
            new RouteSpace(1397, 316, -39),
        ]),
        24 => new Route(30, 38, GRAY, [
            new RouteSpace(1601, 345, -86),
            new RouteSpace(1576, 407, -50),
        ], locomotives: 1),
        25 => new Route(10, 24, GRAY, [
            new RouteSpace(946, 292, 11),
            new RouteSpace(1008, 303, 12),
            new RouteSpace(1070, 315, 11),
            new RouteSpace(1132, 326, 11),
            new RouteSpace(1195, 339, 13),
            new RouteSpace(1256, 351, 12),
        ]),
        26 => new Route(10, 39, YELLOW, [
            new RouteSpace(942, 316, 11),
            new RouteSpace(1004, 330, 15),
            new RouteSpace(1064, 351, 23),
            new RouteSpace(1121, 381, 34),
            new RouteSpace(1170, 423, 46),
        ]),
        27 => new Route(18, 39, BLUE, [
            new RouteSpace(973, 485, -54),
            new RouteSpace(1025, 442, -24),
            new RouteSpace(1090, 431, 5),
            new RouteSpace(1154, 450, 29),
        ], mountain: 1),
        28 => new Route(24, 39, GRAY, [
            new RouteSpace(1285, 391, -37),
            new RouteSpace(1235, 429, -37),
        ]),
        29 => new Route(24, 39, GRAY, [
            new RouteSpace(1299, 410, -37),
            new RouteSpace(1249, 448, -38),
        ]),
        30 => new Route(24, 30, GRAY, [
            new RouteSpace(1396, 359, -7),
            new RouteSpace(1461, 368, 24),
            new RouteSpace(1512, 410, 58),
        ], locomotives: 1),
        31 => new Route(24, 31, YELLOW, [
            new RouteSpace(1354, 433, 69),
            new RouteSpace(1385, 489, 53),
        ]),
        32 => new Route(24, 31, GREEN, [
            new RouteSpace(1375, 421, 69),
            new RouteSpace(1406, 477, 52),
        ]),
        33 => new Route(3, 29, PINK, [
            new RouteSpace(290, 228, -16),
            new RouteSpace(356, 221, 4),
            new RouteSpace(422, 234, 22),
            new RouteSpace(478, 270, 43),
            new RouteSpace(523, 319, 52),
        ], locomotives: 1),
        34 => new Route(3, 36, BLACK, [
            new RouteSpace(204, 286, -66),
        ]),
        35 => new Route(3, 36, WHITE, [
            new RouteSpace(223, 294, -66),
        ]),
        36 => new Route(3, 35, GRAY, [
            new RouteSpace(276, 280, 50),
            new RouteSpace(308, 337, 71),
            new RouteSpace(318, 403, -89),
        ], locomotives: 1),
        37 => new Route(2, 36, YELLOW, [
            new RouteSpace(83, 365, -3),
            new RouteSpace(147, 354, -19),
        ], mountain: 1),
        38 => new Route(35, 36, RED, [
            new RouteSpace(230, 371, 53),
            new RouteSpace(270, 420, 44),
        ]),
        39 => new Route(35, 36, ORANGE, [
            new RouteSpace(210, 383, 53),
            new RouteSpace(251, 433, 45),
        ]),
        40 => new Route(2, 4, PINK, [
            new RouteSpace(74, 408, 46),
            new RouteSpace(120, 453, 39),
            new RouteSpace(173, 488, 30),
        ]),
        41 => new Route(2, 4, WHITE, [
            new RouteSpace(59, 429, 46),
            new RouteSpace(106, 472, 38),
            new RouteSpace(159, 508, 30),
        ]),
        42 => new Route(4, 35, BLUE, [
            new RouteSpace(252, 488, -25),
        ]),
        43 => new Route(4, 35, YELLOW, [
            new RouteSpace(261, 508, -25),
        ]),
        44 => new Route(4, 21, RED, [
            new RouteSpace(203, 577, -63),
            new RouteSpace(180, 632, -70),
            new RouteSpace(163, 693, -79),
        ]),
        45 => new Route(4, 21, GREEN, [
            new RouteSpace(180, 568, -63),
            new RouteSpace(155, 627, -71),
            new RouteSpace(139, 688, -78),
        ]),
        46 => new Route(32, 35, BLACK, [
            new RouteSpace(327, 511, 60),
            new RouteSpace(359, 566, 60),
        ], mountain: 2),
        47 => new Route(32, 35, GREEN, [
            new RouteSpace(348, 499, 60),
            new RouteSpace(379, 554, 60),
        ], mountain: 2),
        48 => new Route(29, 35, BLACK, [
            new RouteSpace(377, 418, -30),
            new RouteSpace(431, 396, -21),
            new RouteSpace(493, 376, -13),
        ], mountain: 1),
        49 => new Route(10, 29, GRAY, [
            new RouteSpace(619, 377, 21),
            new RouteSpace(681, 394, 10),
            new RouteSpace(746, 395, -7),
            new RouteSpace(808, 373, -33),
            new RouteSpace(856, 329, -48),
        ]),
        50 => new Route(10, 29, GRAY, [
            new RouteSpace(623, 404, 20),
            new RouteSpace(687, 419, 9),
            new RouteSpace(750, 420, -6),
            new RouteSpace(814, 398, -33),
            new RouteSpace(863, 357, -48),
        ]),
        51 => new Route(27, 29, BLUE, [
            new RouteSpace(579, 423, 68),
            new RouteSpace(616, 479, 46),
        ], mountain: 1),
        52 => new Route(13, 32, PINK, [
            new RouteSpace(446, 619, 31),
            new RouteSpace(506, 641, 15),
        ], mountain: 2),
        53 => new Route(13, 32, BLUE, [
            new RouteSpace(431, 644, 31),
            new RouteSpace(495, 664, 13),
        ], mountain: 2),
        54 => new Route(13, 27, BLACK, [
            new RouteSpace(624, 555, -55),
            new RouteSpace(587, 609, -53),
        ], mountain: 1),
        55 => new Route(1, 27, GREEN, [
            new RouteSpace(697, 543, 24),
            new RouteSpace(730, 578, -67),
        ], mountain: 1),
        56 => new Route(14, 27, YELLOW, [
            new RouteSpace(703, 505, 4),
            new RouteSpace(767, 519, 22),
            new RouteSpace(824, 555, 44),
        ], mountain: 1),
        57 => new Route(1, 14, PINK, [
            new RouteSpace(753, 588, -67),
            new RouteSpace(801, 588, 23),
        ], mountain: 1),
        58 => new Route(1, 13, RED, [
            new RouteSpace(668, 633, -13),
            new RouteSpace(606, 649, -14),
        ]),
        59 => new Route(1, 6, ORANGE, [
            new RouteSpace(693, 672, -62),
            new RouteSpace(671, 732, -79),
        ]),
        60 => new Route(1, 7, GRAY, [
            new RouteSpace(777, 639, 2),
            new RouteSpace(840, 649, 18),
        ]),
        61 => new Route(6, 13, GRAY, [
            new RouteSpace(585, 704, 61),
            new RouteSpace(624, 755, 45),
        ]),
        62 => new Route(6, 13, GRAY, [
            new RouteSpace(566, 718, 61),
            new RouteSpace(604, 770, 45),
        ]),
        63 => new Route(13, 21, YELLOW, [
            new RouteSpace(355, 642, -48),
            new RouteSpace(307, 686, -37),
            new RouteSpace(252, 719, -26),
            new RouteSpace(191, 741, -14),
        ], locomotives: 1),
        64 => new Route(6, 7, YELLOW, [
            new RouteSpace(714, 753, -27),
            new RouteSpace(771, 724, -27),
            new RouteSpace(827, 695, -27),
        ]),
        65 => new Route(6, 7, PINK, [
            new RouteSpace(725, 773, -27),
            new RouteSpace(781, 744, -27),
            new RouteSpace(837, 716, -27),
        ]),
        66 => new Route(6, 9, RED, [
            new RouteSpace(662, 838, -89),
            new RouteSpace(668, 906, 75),
            new RouteSpace(699, 963, 50),
            new RouteSpace(743, 1002, 36),
        ], locomotives: 1),
        67 => new Route(6, 9, BLACK, [
            new RouteSpace(635, 846, -87),
            new RouteSpace(642, 912, 76),
            new RouteSpace(671, 972, 50),
            new RouteSpace(718, 1015, 36),
        ], locomotives: 1),
        68 => new Route(6, 9, GREEN, [
            new RouteSpace(859, 742, -42),
            new RouteSpace(812, 790, -50),
            new RouteSpace(780, 848, -72),
            new RouteSpace(771, 913, 88),
            new RouteSpace(782, 979, 70),
        ], locomotives: 1),
        69 => new Route(14, 18, BLACK, [
            new RouteSpace(889, 585, -36),
            new RouteSpace(925, 559, -52),
        ], mountain: 1),
        70 => new Route(14, 20, ORANGE, [
            new RouteSpace(912, 612, 6),
            new RouteSpace(973, 639, 43),
        ], mountain: 2),
        71 => new Route(7, 20, GRAY, [
            new RouteSpace(968, 679, 14),
        ]),
        72 => new Route(7, 20, GRAY, [
            new RouteSpace(963, 702, 11),
        ]),
        73 => new Route(7, 26, BLUE, [
            new RouteSpace(935, 738, 54),
            new RouteSpace(980, 784, 38),
        ]),
        74 => new Route(7, 26, BLACK, [
            new RouteSpace(918, 755, 54),
            new RouteSpace(962, 800, 37),
        ]),
        75 => new Route(18, 20, BLACK, [
            new RouteSpace(994, 575, 49),
            new RouteSpace(1019, 636, 86),
        ], mountain: 2),
        76 => new Route(20, 39, PINK, [
            new RouteSpace(1185, 527, -79),
            new RouteSpace(1163, 589, -60),
            new RouteSpace(1121, 641, -41),
            new RouteSpace(1064, 675, -19),
        ], mountain: 1),
        77 => new Route(11, 20, RED, [
            new RouteSpace(1069, 710, -2),
            new RouteSpace(1133, 707, -3),
        ], mountain: 2),
        78 => new Route(20, 26, WHITE, [
            new RouteSpace(1022, 762, 86),
        ], mountain: 1),
        79 => new Route(5, 26, GREEN, [
            new RouteSpace(1063, 835, 36),
        ]),
        80 => new Route(5, 26, ORANGE, [
            new RouteSpace(1049, 854, 37),
        ]),
        81 => new Route(5, 28, PINK, [
            new RouteSpace(1145, 895, 36),
        ]),
        82 => new Route(5, 28, RED, [
            new RouteSpace(1133, 916, 35),
        ]),
        83 => new Route(5, 33, BLUE, [
            new RouteSpace(1056, 934, -84),
            new RouteSpace(1057, 996, 79),
            new RouteSpace(1086, 1060, 53),
        ]),
        84 => new Route(5, 33, BLACK, [
            new RouteSpace(1083, 926, -86),
            new RouteSpace(1085, 995, 79),
            new RouteSpace(1112, 1053, 53),
        ]),
        85 => new Route(28, 33, GRAY, [
            new RouteSpace(1181, 983, -87),
            new RouteSpace(1170, 1048, -73),
        ], locomotives: 1),
        86 => new Route(11, 19, GRAY, [
            new RouteSpace(1241, 688, 9),
        ]),
        87 => new Route(11, 19, GRAY, [
            new RouteSpace(1237, 710, 9),
        ]),
        88 => new Route(11, 39, GREEN, [
            new RouteSpace(1235, 525, 60),
            new RouteSpace(1262, 582, 68),
            new RouteSpace(1281, 643, 76),
        ]),
        89 => new Route(11, 39, WHITE, [
            new RouteSpace(1257, 516, 60),
            new RouteSpace(1284, 573, 68),
            new RouteSpace(1304, 634, 76),
        ]),
        90 => new Route(19, 31, GRAY, [
            new RouteSpace(1399, 575, -79),
            new RouteSpace(1374, 636, -58),
            new RouteSpace(1330, 685, -39),
        ]),
        91 => new Route(19, 34, GRAY, [
            new RouteSpace(1346, 728, 0),
            new RouteSpace(1407, 705, -41),
        ], locomotives: 1),
        92 => new Route(31, 34, GRAY, [
            new RouteSpace(1453, 573, 39),
            new RouteSpace(1476, 618, -51),
        ], locomotives: 1),
        93 => new Route(16, 31, ORANGE, [
            new RouteSpace(1477, 536, 0),
            new RouteSpace(1542, 531, -10),
            new RouteSpace(1602, 510, -20),
            new RouteSpace(1661, 485, -33),
        ], locomotives: 1),
        94 => new Route(16, 30, GRAY, [
            new RouteSpace(1582, 470, 20),
            new RouteSpace(1646, 462, -33),
        ], locomotives: 1),
        95 => new Route(16, 34, WHITE, [
            new RouteSpace(1695, 510, -73),
            new RouteSpace(1670, 571, -59),
            new RouteSpace(1624, 617, -33),
            new RouteSpace(1565, 644, -16),
            new RouteSpace(1501, 656, -5),
        ], locomotives: 1),
        96 => new Route(16, 34, BLUE, [
            new RouteSpace(1714, 533, -73),
            new RouteSpace(1687, 591, -59),
            new RouteSpace(1641, 640, -32),
            new RouteSpace(1577, 665, -15),
            new RouteSpace(1516, 678, -5),
        ], locomotives: 1),
        97 => new Route(19, 28, YELLOW, [
            new RouteSpace(1196, 756, 68),
            new RouteSpace(1211, 819, 85),
            new RouteSpace(1202, 883, -69),
        ]),
        98 => new Route(19, 28, ORANGE, [
            new RouteSpace(1223, 755, 68),
            new RouteSpace(1236, 818, 87),
            new RouteSpace(1229, 885, -68),
        ]),
        99 => new Route(8, 12, WHITE, [
            new RouteSpace(1167, 86, 17),
            new RouteSpace(1230, 95, 0),
        ], mountain: 1),
        100 => new Route(13, 29, WHITE, [
            new RouteSpace(528, 412, -60),
            new RouteSpace(507, 474, -82),
            new RouteSpace(509, 540, 79),
            new RouteSpace(531, 603, 63),
        ], mountain: 1),
        101 => new Route(5, 11, WHITE, [
            new RouteSpace(1151, 762, -66),
            new RouteSpace(1125, 820, -66),
        ], mountain: 1),
    ];
}
