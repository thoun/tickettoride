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
        1 => new Route(1, 16, WHITE, [
            new RouteSpace(965, 453, -31),
        ]),
        2 => new Route(1, 17, GRAY, [
            new RouteSpace(1008, 471, 90),
            new RouteSpace(997, 535, -69),
            new RouteSpace(956, 587, -42),
            new RouteSpace(896, 595, 28),
        ], locomotives: 1),
        3 => new Route(1, 23, PINK, [
            new RouteSpace(913, 272, 18),
            new RouteSpace(965, 307, 50),
            new RouteSpace(999, 365, 72),
        ]),
        4 => new Route(1, 32, GRAY, [
            new RouteSpace(1031, 471, 90),
            new RouteSpace(1031, 533, 90),
            new RouteSpace(1027, 596, 94),
            new RouteSpace(1014, 657, -73),
            new RouteSpace(990, 715, -60),
            new RouteSpace(956, 765, -47),
        ], locomotives: 1),
        5 => new Route(1, 46, GRAY, [
            new RouteSpace(1036, 219, 90),
            new RouteSpace(1036, 281, 90),
            new RouteSpace(1035, 344, 90),
        ], locomotives: 1),
        6 => new Route(2, 11, YELLOW, [
            new RouteSpace(406, 1167, -55),
        ]),
        7 => new Route(2, 21, GRAY, [
            new RouteSpace(464, 1002, -74),
            new RouteSpace(453, 1064, -88),
        ], locomotives: 1),
        8 => new Route(2, 28, WHITE, [
            new RouteSpace(487, 1158, 41),
        ]),
        9 => new Route(2, 39, GRAY, [
            new RouteSpace(277, 1050, 21),
            new RouteSpace(335, 1074, 21),
            new RouteSpace(393, 1097, 21),
        ], locomotives: 1),
        10 => new Route(3, 4, GRAY, [
            new RouteSpace(505, 710, 41),
            new RouteSpace(551, 751, 41),
            new RouteSpace(598, 791, 41),
            new RouteSpace(645, 833, 41),
        ], locomotives: 1),
        11 => new Route(3, 10, RED, [
            new RouteSpace(728, 816, -58),
        ]),
        12 => new Route(3, 25, GREEN, [
            new RouteSpace(740, 890, 23),
            new RouteSpace(790, 925, 57),
        ]),
        13 => new Route(3, 27, GRAY, [
            new RouteSpace(662, 920, -65),
        ], locomotives: 1),
        14 => new Route(4, 15, WHITE, [
            new RouteSpace(409, 704, -38),
        ]),
        15 => new Route(4, 15, RED, [
            new RouteSpace(423, 722, -38),
        ]),
        16 => new Route(4, 29, ORANGE, [
            new RouteSpace(409, 567, 64),
            new RouteSpace(436, 623, 64),
        ]),
        17 => new Route(4, 43, GRAY, [
            new RouteSpace(525, 664, -3),
        ], locomotives: 1),
        18 => new Route(5, 9, BLUE, [
            new RouteSpace(514, 1275, -20),
            new RouteSpace(571, 1253, -20),
            new RouteSpace(629, 1232, -20),
        ]),
        19 => new Route(5, 9, ORANGE, [
            new RouteSpace(522, 1299, -20),
            new RouteSpace(580, 1278, -20),
            new RouteSpace(638, 1256, -20),
        ]),
        20 => new Route(5, 28, RED, [
            new RouteSpace(582, 1179, -21),
            new RouteSpace(644, 1185, 26),
        ]),
        21 => new Route(5, 31, BLACK, [
            new RouteSpace(708, 1089, -75),
            new RouteSpace(692, 1150, -75),
        ]),
        22 => new Route(5, 31, YELLOW, [
            new RouteSpace(731, 1095, -75),
            new RouteSpace(715, 1156, -75),
        ]),
        23 => new Route(5, 33, GREEN, [
            new RouteSpace(729, 1261, 43),
        ]),
        24 => new Route(5, 33, WHITE, [
            new RouteSpace(714, 1279, 43),
        ]),
        25 => new Route(5, 35, GRAY, [
            new RouteSpace(746, 1189, -23),
        ]),
        26 => new Route(5, 38, GRAY, [
            new RouteSpace(675, 1297, -85),
            new RouteSpace(683, 1363, 70),
        ]),
        27 => new Route(6, 13, PINK, [
            new RouteSpace(826, 1583, 0),
            new RouteSpace(888, 1583, 0),
        ]),
        28 => new Route(6, 30, GRAY, [
            new RouteSpace(797, 1529, -64),
        ]),
        29 => new Route(6, 41, BLUE, [
            new RouteSpace(700, 1567, 14),
        ]),
        30 => new Route(7, 9, GRAY, [
            new RouteSpace(502, 1340, 36),
        ], locomotives: 1),
        31 => new Route(7, 37, YELLOW, [
            new RouteSpace(363, 1423, -11),
            new RouteSpace(424, 1410, -11),
            new RouteSpace(485, 1398, -11),
        ]),
        32 => new Route(7, 38, WHITE, [
            new RouteSpace(593, 1389, 19),
            new RouteSpace(652, 1408, 19),
        ]),
        33 => new Route(7, 41, GREEN, [
            new RouteSpace(573, 1419, 61),
            new RouteSpace(603, 1473, 61),
        ]),
        34 => new Route(8, 24, BLACK, [
            new RouteSpace(926, 1394, 42),
        ]),
        35 => new Route(8, 30, ORANGE, [
            new RouteSpace(846, 1396, -66),
        ]),
        36 => new Route(8, 30, YELLOW, [
            new RouteSpace(868, 1405, -66),
        ]),
        37 => new Route(8, 33, GRAY, [
            new RouteSpace(829, 1326, 19),
        ]),
        38 => new Route(8, 34, RED, [
            new RouteSpace(937, 1353, 9),
            new RouteSpace(1001, 1349, -18),
        ]),
        39 => new Route(8, 35, GRAY, [
            new RouteSpace(833, 1228, 53),
            new RouteSpace(860, 1286, 70),
        ]),
        40 => new Route(9, 11, RED, [
            new RouteSpace(420, 1255, 43),
        ]),
        41 => new Route(9, 28, PINK, [
            new RouteSpace(487, 1242, -55),
        ]),
        42 => new Route(9, 36, GRAY, [
            new RouteSpace(167, 1372, -42),
            new RouteSpace(222, 1344, -19),
            new RouteSpace(281, 1328, -9),
            new RouteSpace(342, 1317, -9),
            new RouteSpace(403, 1306, -9),
        ], locomotives: 1),
        43 => new Route(10, 17, ORANGE, [
            new RouteSpace(799, 649, -73),
            new RouteSpace(782, 709, -73),
        ]),
        44 => new Route(10, 32, YELLOW, [
            new RouteSpace(828, 786, 16),
        ]),
        45 => new Route(10, 43, GRAY, [
            new RouteSpace(601, 713, 70),
            new RouteSpace(640, 759, 28),
            new RouteSpace(704, 770, -10),
        ], locomotives: 1),
        46 => new Route(11, 39, GRAY, [
            new RouteSpace(214, 1073, -82),
            new RouteSpace(222, 1138, 69),
            new RouteSpace(264, 1187, 35),
            new RouteSpace(321, 1215, 10),
        ], locomotives: 1),
        47 => new Route(12, 26, PINK, [
            new RouteSpace(41, 915, -77),
        ]),
        48 => new Route(12, 36, GRAY, [
            new RouteSpace(40, 1049, 79),
            new RouteSpace(50, 1110, 79),
            new RouteSpace(64, 1172, 79),
            new RouteSpace(77, 1231, 79),
            new RouteSpace(90, 1291, 79),
            new RouteSpace(103, 1354, 79),
        ], locomotives: 2),
        49 => new Route(12, 39, BLUE, [
            new RouteSpace(94, 994, 9),
            new RouteSpace(156, 1004, 9),
        ]),
        50 => new Route(12, 44, YELLOW, [
            new RouteSpace(70, 946, -44),
            new RouteSpace(116, 903, -44),
            new RouteSpace(163, 861, -44),
        ]),
        51 => new Route(13, 30, GRAY, [
            new RouteSpace(858, 1500, 42),
            new RouteSpace(905, 1543, 42),
        ]),
        52 => new Route(13, 1002, GRAY, [
            new RouteSpace(993, 1624, 46),
            new RouteSpace(1038, 1669, 46),
        ], locomotives: 1),
        53 => new Route(14, 15, YELLOW, [
            new RouteSpace(331, 795, -71),
        ]),
        54 => new Route(14, 15, BLUE, [
            new RouteSpace(350, 802, -71),
        ]),
        55 => new Route(14, 21, GRAY, [
            new RouteSpace(372, 892, 31),
            new RouteSpace(422, 925, 31),
        ], locomotives: 1),
        56 => new Route(14, 39, WHITE, [
            new RouteSpace(280, 904, -59),
            new RouteSpace(248, 958, -59),
        ]),
        57 => new Route(14, 39, BLACK, [
            new RouteSpace(300, 916, -59),
            new RouteSpace(268, 970, -59),
        ]),
        58 => new Route(14, 44, GREEN, [
            new RouteSpace(264, 827, 13),
        ]),
        59 => new Route(14, 44, ORANGE, [
            new RouteSpace(259, 850, 13),
        ]),
        60 => new Route(15, 21, GRAY, [
            new RouteSpace(402, 786, 61),
            new RouteSpace(432, 840, 61),
            new RouteSpace(462, 894, 61),
        ], locomotives: 1),
        61 => new Route(15, 29, PINK, [
            new RouteSpace(358, 562, -72),
            new RouteSpace(347, 624, -85),
            new RouteSpace(353, 688, 75),
        ]),
        62 => new Route(15, 40, BLACK, [
            new RouteSpace(231, 617, 45),
            new RouteSpace(276, 663, 45),
            new RouteSpace(320, 706, 45),
        ]),
        63 => new Route(16, 17, YELLOW, [
            new RouteSpace(861, 521, -53),
        ]),
        64 => new Route(16, 17, RED, [
            new RouteSpace(880, 536, -53),
        ]),
        65 => new Route(16, 18, GREEN, [
            new RouteSpace(764, 382, 36),
            new RouteSpace(812, 418, 36),
            new RouteSpace(863, 454, 36),
        ]),
        66 => new Route(16, 23, BLUE, [
            new RouteSpace(874, 307, 80),
            new RouteSpace(887, 368, 80),
            new RouteSpace(899, 429, 80),
        ]),
        67 => new Route(17, 20, BLUE, [
            new RouteSpace(779, 541, 15),
        ]),
        68 => new Route(17, 20, BLACK, [
            new RouteSpace(773, 564, 15),
        ]),
        69 => new Route(17, 32, GREEN, [
            new RouteSpace(843, 633, 76),
            new RouteSpace(858, 694, 76),
            new RouteSpace(873, 753, 76),
        ]),
        70 => new Route(17, 32, PINK, [
            new RouteSpace(866, 627, 76),
            new RouteSpace(881, 688, 76),
            new RouteSpace(896, 747, 76),
        ]),
        71 => new Route(17, 43, WHITE, [
            new RouteSpace(653, 646, -16),
            new RouteSpace(713, 629, -16),
            new RouteSpace(773, 612, -16),
        ]),
        72 => new Route(18, 20, ORANGE, [
            new RouteSpace(719, 417, 89),
            new RouteSpace(721, 480, 89),
        ]),
        73 => new Route(18, 23, BLACK, [
            new RouteSpace(761, 315, -32),
            new RouteSpace(815, 282, -32),
        ]),
        74 => new Route(18, 29, GRAY, [
            new RouteSpace(444, 481, -28),
            new RouteSpace(496, 454, -28),
            new RouteSpace(552, 426, -28),
            new RouteSpace(606, 398, -28),
            new RouteSpace(660, 370, -28),
        ], locomotives: 1),
        75 => new Route(18, 42, GRAY, [
            new RouteSpace(678, 82, -32),
            new RouteSpace(634, 128, -57),
            new RouteSpace(613, 190, -88),
            new RouteSpace(624, 254, 65),
            new RouteSpace(664, 305, 41),
        ], locomotives: 1),
        76 => new Route(18, 45, PINK, [
            new RouteSpace(765, 217, -65),
            new RouteSpace(738, 274, -65),
        ]),
        77 => new Route(19, 26, YELLOW, [
            new RouteSpace(50, 785, 90),
        ]),
        78 => new Route(19, 40, ORANGE, [
            new RouteSpace(96, 663, -43),
            new RouteSpace(139, 621, -43),
        ]),
        79 => new Route(19, 44, GRAY, [
            new RouteSpace(110, 750, 37),
            new RouteSpace(159, 787, 37),
        ]),
        80 => new Route(19, 44, GRAY, [
            new RouteSpace(97, 769, 37),
            new RouteSpace(146, 806, 37),
        ]),
        81 => new Route(20, 29, GRAY, [
            new RouteSpace(472, 519, 4),
            new RouteSpace(534, 523, 4),
            new RouteSpace(595, 528, 4),
            new RouteSpace(657, 533, 4),
        ], locomotives: 1),
        82 => new Route(20, 43, RED, [
            new RouteSpace(625, 623, -38),
            new RouteSpace(674, 584, -38),
        ]),
        83 => new Route(21, 27, GRAY, [
            new RouteSpace(540, 913, -20),
            new RouteSpace(597, 927, 46),
        ], locomotives: 1),
        84 => new Route(21, 28, BLUE, [
            new RouteSpace(503, 1004, 80),
            new RouteSpace(514, 1065, 80),
            new RouteSpace(526, 1126, 80),
        ]),
        85 => new Route(21, 39, GRAY, [
            new RouteSpace(298, 1004, -15),
            new RouteSpace(357, 989, -15),
            new RouteSpace(418, 975, -15),
        ], locomotives: 1),
        86 => new Route(22, 25, YELLOW, [
            new RouteSpace(882, 1017, 33),
        ]),
        87 => new Route(22, 32, GRAY, [
            new RouteSpace(965, 825, 24),
            new RouteSpace(1013, 864, 53),
            new RouteSpace(1033, 925, 86),
            new RouteSpace(1029, 990, -69),
            new RouteSpace(988, 1039, -35),
        ], locomotives: 1),
        88 => new Route(22, 34, GRAY, [
            new RouteSpace(939, 1118, 83),
            new RouteSpace(954, 1181, 76),
            new RouteSpace(980, 1239, 58),
            new RouteSpace(1016, 1284, 38),
        ]),
        89 => new Route(22, 35, BLACK, [
            new RouteSpace(888, 1097, -39),
            new RouteSpace(840, 1135, -39),
        ]),
        90 => new Route(23, 45, ORANGE, [
            new RouteSpace(838, 207, 62),
        ]),
        91 => new Route(23, 46, RED, [
            new RouteSpace(920, 215, -28),
            new RouteSpace(975, 185, -28),
        ]),
        92 => new Route(24, 30, WHITE, [
            new RouteSpace(897, 1451, -6),
        ]),
        93 => new Route(24, 34, GREEN, [
            new RouteSpace(1007, 1394, -50),
        ]),
        94 => new Route(25, 27, BLACK, [
            new RouteSpace(696, 957, 0),
            new RouteSpace(758, 957, 0),
        ]),
        95 => new Route(25, 31, RED, [
            new RouteSpace(773, 999, -32),
        ]),
        96 => new Route(25, 31, BLUE, [
            new RouteSpace(785, 1019, -32),
        ]),
        97 => new Route(25, 32, WHITE, [
            new RouteSpace(870, 864, -69),
            new RouteSpace(848, 923, -69),
        ]),
        98 => new Route(25, 32, ORANGE, [
            new RouteSpace(891, 872, -69),
            new RouteSpace(870, 930, -69),
        ]),
        99 => new Route(25, 35, PINK, [
            new RouteSpace(819, 1046, -80),
            new RouteSpace(808, 1107, -80),
        ]),
        100 => new Route(26, 44, GRAY, [
            new RouteSpace(121, 842, -8),
        ]),
        101 => new Route(27, 31, ORANGE, [
            new RouteSpace(682, 994, 27),
        ]),
        102 => new Route(27, 31, PINK, [
            new RouteSpace(671, 1015, 27),
        ]),
        103 => new Route(28, 31, GREEN, [
            new RouteSpace(673, 1066, -35),
            new RouteSpace(622, 1102, -35),
            new RouteSpace(573, 1144, -43),
        ]),
        104 => new Route(29, 40, GREEN, [
            new RouteSpace(252, 548, -21),
            new RouteSpace(310, 527, -21),
        ]),
        105 => new Route(29, 43, GRAY, [
            new RouteSpace(441, 548, 36),
            new RouteSpace(489, 585, 36),
            new RouteSpace(541, 622, 36),
        ], locomotives: 1),
        106 => new Route(30, 33, PINK, [
            new RouteSpace(781, 1376, 76),
        ]),
        107 => new Route(30, 33, BLUE, [
            new RouteSpace(801, 1370, 76),
        ]),
        108 => new Route(30, 38, GREEN, [
            new RouteSpace(756, 1434, 16),
        ]),
        109 => new Route(30, 41, RED, [
            new RouteSpace(691, 1499, -21),
            new RouteSpace(750, 1477, -21),
        ]),
        110 => new Route(30, 41, BLACK, [
            new RouteSpace(699, 1521, -21),
            new RouteSpace(758, 1499, -21),
        ]),
        111 => new Route(33, 35, ORANGE, [
            new RouteSpace(787, 1239, -82),
        ]),
        112 => new Route(33, 38, RED, [
            new RouteSpace(729, 1367, -61),
        ]),
        113 => new Route(34, 35, WHITE, [
            new RouteSpace(849, 1203, 31),
            new RouteSpace(900, 1235, 31),
            new RouteSpace(953, 1268, 31),
            new RouteSpace(1006, 1300, 31),
        ]),
        114 => new Route(36, 37, BLACK, [
            new RouteSpace(176, 1422, 9),
            new RouteSpace(238, 1431, 9),
        ]),
        115 => new Route(36, 37, GRAY, [
            new RouteSpace(140, 1472, 62),
            new RouteSpace(193, 1504, 3),
            new RouteSpace(254, 1482, -41),
        ], locomotives: 1),
        116 => new Route(37, 41, GRAY, [
            new RouteSpace(332, 1471, 24),
            new RouteSpace(390, 1493, 17),
            new RouteSpace(450, 1510, 11),
            new RouteSpace(511, 1522, 6),
            new RouteSpace(574, 1526, 0),
        ], locomotives: 1),
        117 => new Route(38, 41, ORANGE, [
            new RouteSpace(666, 1468, -53),
        ]),
        118 => new Route(39, 44, RED, [
            new RouteSpace(203, 886, -88),
            new RouteSpace(201, 952, 85),
        ]),
        119 => new Route(40, 44, BLUE, [
            new RouteSpace(194, 638, 88),
            new RouteSpace(198, 700, 88),
            new RouteSpace(201, 762, 88),
        ]),
        120 => new Route(41, 1001, GRAY, [
            new RouteSpace(619, 1583, -67),
            new RouteSpace(591, 1638, -58),
            new RouteSpace(554, 1687, -49),
        ], locomotives: 1),
        121 => new Route(41, 1001, GRAY, [
            new RouteSpace(641, 1595, -67),
            new RouteSpace(611, 1650, -58),
            new RouteSpace(574, 1700, -49),
        ], locomotives: 1),
        122 => new Route(41, 2001, GRAY, [
            new RouteSpace(585, 1562, -30),
            new RouteSpace(531, 1593, -30),
            new RouteSpace(474, 1619, -24),
            new RouteSpace(414, 1640, -14),
            new RouteSpace(353, 1655, -10),
            new RouteSpace(291, 1663, -6),
            new RouteSpace(229, 1670, -3),
            new RouteSpace(167, 1671, 2),
            new RouteSpace(104, 1663, 12),
            new RouteSpace(42, 1647, 20),
        ], locomotives: 3),
        123 => new Route(42, 45, GRAY, [
            new RouteSpace(770, 112, 56),
        ], locomotives: 1),
        124 => new Route(42, 46, GRAY, [
            new RouteSpace(790, 67, 0),
            new RouteSpace(852, 67, 0),
            new RouteSpace(915, 67, 0),
            new RouteSpace(977, 67, 0),
            new RouteSpace(1026, 101, 72),
        ], locomotives: 2),
        125 => new Route(45, 46, YELLOW, [
            new RouteSpace(857, 140, -23),
            new RouteSpace(921, 127, 0),
            new RouteSpace(984, 135, 12),
        ]),
    ];
}
