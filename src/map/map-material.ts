class City {
    constructor(
        public id: number,
        public x: number,
        public y: number,
    ) {}
}

class BigCity {
    constructor(
        public x: number,
        public y: number,
        public width: number,
    ) {}
}


class RouteSpace {
    constructor(
        public x: number,
        public y: number,
        public angle: number,
        public top: boolean = false,
    ) {}
}

class Route {
    constructor(
        public id: number,
        public from: number,
        public to: number,
        public spaces: RouteSpace[],
        public color: number,
    ) {}
}

const CITIES = [
    new City(1, 1395, 726), // Atlanta
    new City(2, 1701, 197), // Boston
    new City(3, 378, 99), // Calgary
    new City(4, 1564, 739), // Charleston
    new City(5, 1214, 442), // Chicago
    new City(6, 973, 908), // Dallas
    new City(7, 666, 622), // Denver
    new City(8, 991, 330), // Duluth
    new City(9, 644, 950), // El Paso
    new City(10, 559, 340), // Helena
    new City(11, 1049, 980), // Houston
    new City(12, 973, 590), // Kansas City
    new City(13, 327, 764), // Las Vegas
    new City(14, 1101, 755), // Little Rock
    new City(15, 209, 871), // Los Angeles
    new City(16, 1624, 1025), // Miami
    new City(17, 1571, 90), // Montréal
    new City(18, 1302, 663), // Nashville
    new City(19, 1223, 966), // New Orleans
    new City(20, 1606, 333), // New York
    new City(21, 937, 747), // Oklahoma City
    new City(22, 937, 497), // Omaha
    new City(23, 428, 884), // Phoenix
    new City(24, 1452, 414), // Pittsburgh
    new City(25, 94, 322), // Portland
    new City(26, 1514, 619), // Raleigh
    new City(27, 1133, 592), // Saint Louis
    new City(28, 430, 564), // Salt Lake City
    new City(29, 1223, 209), // Sault St. Marie
    new City(30, 69, 682), // San Francisco
    new City(31, 653, 787), // Santa Fe
    new City(32, 133, 230), // Seattle
    new City(33, 1421, 251), // Toronto
    new City(34, 140, 132), // Vancouver
    new City(35, 1619, 498), // Washington
    new City(36, 786, 119), // Winnipeg
];

const BIG_CITIES = [
    new BigCity(1226, 479, 70), // Chicago
    new BigCity(1007, 903, 64), // Dallas
    new BigCity(1046, 1022, 79), // Houston
    new BigCity(86, 904, 107), // Los Angeles
    new BigCity(1633, 1066, 62), // Miami
    new BigCity(1642, 359, 93), // New York
    new BigCity(38, 234, 69), // Seattle
];

const ROUTES = [
    new Route(1, 1, 4, [
        new RouteSpace(1413, 729, 3),
        new RouteSpace(1479, 731, 3),
    ], GRAY),
    new Route(2, 1, 16, [
        new RouteSpace(1385, 763, 51),
        new RouteSpace(1427, 814, 51),
        new RouteSpace(1468, 865, 51),
        new RouteSpace(1508, 915, 51),
        new RouteSpace(1549, 965, 51, true),
    ], BLUE),
    new Route(3, 1, 18, [
        new RouteSpace(1306, 673, 35),
    ], GRAY),
    new Route(4, 1, 19, [
        new RouteSpace(1198, 889, 291),
        new RouteSpace(1227, 831, 303),
        new RouteSpace(1266, 779, 310),
        new RouteSpace(1312, 732, 319),
    ], YELLOW),
    new Route(5, 1, 19, [
        new RouteSpace(1214, 906, 291),
        new RouteSpace(1245, 847, 303),
        new RouteSpace(1284, 795, 310),
        new RouteSpace(1329, 749, 319),
    ], ORANGE),
    new Route(6, 1, 26, [
        new RouteSpace(1385, 669, -41),
        new RouteSpace(1434, 627, -41),
    ], GRAY),
    new Route(7, 1, 26, [
        new RouteSpace(1449, 643, -41),
        new RouteSpace(1400, 687, -41),
    ], GRAY),
    new Route(8, 2, 17, [
        new RouteSpace(1583, 98, 40),
        new RouteSpace(1633, 139, 40),
    ], GRAY),
    new Route(9, 2, 17, [
        new RouteSpace(1568, 115, 40),
        new RouteSpace(1618, 156, 40),
    ], GRAY),
    new Route(10, 2, 20, [
        new RouteSpace(1630, 220, 122),
        new RouteSpace(1596, 275, 122),
    ], YELLOW),
    new Route(11, 2, 20, [
        new RouteSpace(1649, 232, 122),
        new RouteSpace(1615, 287, 122),
    ], RED),
    new Route(100, 3, 10, [
        new RouteSpace(377, 127, 50),
        new RouteSpace(420, 178, 50),
        new RouteSpace(461, 227, 50),
        new RouteSpace(503, 277, 50),
    ], GRAY),
    new Route(12, 3, 32, [
        new RouteSpace(154, 211, 0),
        new RouteSpace(220, 207, -7),
        new RouteSpace(281, 183, -37),
        new RouteSpace(324, 131, -63),
    ], GRAY),
    new Route(13, 3, 34, [
        new RouteSpace(159, 103, -6),
        new RouteSpace(224, 95, -6),
        new RouteSpace(290, 88, -6),
    ], GRAY),
    new Route(14, 3, 36, [
        new RouteSpace(391, 66, -23),
        new RouteSpace(453, 47, -11),
        new RouteSpace(518, 37, -2),
        new RouteSpace(585, 38, 4),
        new RouteSpace(650, 51, 15),
        new RouteSpace(710, 73, 25),
    ], WHITE),
    new Route(15, 4, 16, [
        new RouteSpace(1530, 768, 87),
        new RouteSpace(1535, 834, 82),
        new RouteSpace(1551, 899, 73),
        new RouteSpace(1577, 958, 59),
    ], PINK),
    new Route(16, 4, 26, [
        new RouteSpace(1514, 634, 35),
        new RouteSpace(1544, 678, -65),
    ], GRAY),
    new Route(17, 5, 8, [
        new RouteSpace(994, 344, 28),
        new RouteSpace(1054, 371, 21),
        new RouteSpace(1117, 390, 14),
    ], RED),
    new Route(18, 5, 22, [
        new RouteSpace(945, 461, -34, true),
        new RouteSpace(998, 425, -34),
        new RouteSpace(1062, 410, 8),
        new RouteSpace(1124, 419, 8),
    ], BLUE),
    new Route(19, 5, 24, [
        new RouteSpace(1221, 389, -14),
        new RouteSpace(1285, 375, -9),
        new RouteSpace(1351, 372, 3),
    ], ORANGE),
    new Route(20, 5, 24, [
        new RouteSpace(1229, 412, -14),
        new RouteSpace(1294, 398, -9),
        new RouteSpace(1358, 395, 3),
    ], BLACK),
    new Route(21, 5, 27, [
        new RouteSpace(1101, 519, -57),
        new RouteSpace(1135, 466, -57),
    ], GREEN),
    new Route(22, 5, 27, [
        new RouteSpace(1121, 529, -57),
        new RouteSpace(1155, 475, -57),
    ], WHITE),
    new Route(23, 5, 33, [
        new RouteSpace(1186, 371, -48),
        new RouteSpace(1236, 328, -35),
        new RouteSpace(1296, 299, -17),
        new RouteSpace(1354, 269, -39, true),
    ], WHITE),
    new Route(24, 6, 9, [
        new RouteSpace(691, 933, 351),
        new RouteSpace(755, 922, 351),
        new RouteSpace(819, 913, 351),
        new RouteSpace(884, 904, 351),
    ], RED),
    new Route(25, 6, 11, [
        new RouteSpace(964, 930, 49, true),
    ], GRAY),
    new Route(26, 6, 11, [
        new RouteSpace(982, 915, 49),
    ], GRAY),
    new Route(27, 6, 14, [
        new RouteSpace(986, 830, -55),
        new RouteSpace(1024, 777, -55),
    ], GRAY),
    new Route(28, 6, 21, [
        new RouteSpace(911, 777, -97),
        new RouteSpace(919, 841, -97),
    ], GRAY),
    new Route(29, 6, 21, [
        new RouteSpace(933, 775, -97),
        new RouteSpace(941, 839, -97),
    ], GRAY),
    new Route(30, 7, 10, [
        new RouteSpace(545, 369, 67),
        new RouteSpace(568, 428, 67),
        new RouteSpace(593, 488, 67),
        new RouteSpace(617, 549, 67),
    ], GREEN),
    new Route(31, 7, 12, [
        new RouteSpace(692, 605, 5),
        new RouteSpace(758, 606, -5),
        new RouteSpace(822, 596, -11),
        new RouteSpace(886, 576, -23),
    ], BLACK),
    new Route(32, 7, 12, [
        new RouteSpace(692, 629, 5),
        new RouteSpace(758, 630, -5),
        new RouteSpace(822, 620, -11),
        new RouteSpace(886, 600, -23),
    ], ORANGE),
    new Route(33, 7, 21, [
        new RouteSpace(662, 658, 224),
        new RouteSpace(717, 696, 205),
        new RouteSpace(779, 714, 189),
        new RouteSpace(844, 720, 184),
    ], RED),
    new Route(34, 7, 22, [
        new RouteSpace(668, 566, -38),
        new RouteSpace(723, 532, -26),
        new RouteSpace(784, 508, -16),
        new RouteSpace(848, 493, -12),
    ], PINK),
    new Route(35, 7, 23, [
        new RouteSpace(411, 818, -68),
        new RouteSpace(439, 762, -61),
        new RouteSpace(473, 706, -55),
        new RouteSpace(516, 658, -39),
        new RouteSpace(574, 624, -20),
    ], WHITE),
    new Route(36, 7, 28, [
        new RouteSpace(445, 543, 11),
        new RouteSpace(510, 556, 11),
        new RouteSpace(571, 568, 11),
    ], RED),
    new Route(37, 7, 28, [
        new RouteSpace(441, 565, 11),
        new RouteSpace(506, 578, 11),
        new RouteSpace(567, 590, 11),
    ], YELLOW),
    new Route(99, 7, 31, [
        new RouteSpace(619, 657, 273),
        new RouteSpace(616, 721, 273),
    ], GRAY),
    new Route(38, 8, 10, [
        new RouteSpace(576, 320, -1),
        new RouteSpace(642, 320, -1),
        new RouteSpace(708, 319, -1),
        new RouteSpace(773, 318, -1),
        new RouteSpace(839, 317, -1),
        new RouteSpace(905, 316, -1),
    ], ORANGE),
    new Route(39, 8, 22, [
        new RouteSpace(925, 363, -68),
        new RouteSpace(901, 423, -68),
    ], GRAY),
    new Route(40, 8, 22, [
        new RouteSpace(945, 370, -68),
        new RouteSpace(921, 431, -68),
    ], GRAY),
    new Route(41, 8, 29, [
        new RouteSpace(1016, 269, -23),
        new RouteSpace(1076, 245, -23),
        new RouteSpace(1136, 219, -23),
    ], GRAY),
    new Route(42, 8, 33, [
        new RouteSpace(1004, 303, -8),
        new RouteSpace(1068, 292, -8),
        new RouteSpace(1133, 280, -8),
        new RouteSpace(1198, 270, -8),
        new RouteSpace(1261, 259, -8),
        new RouteSpace(1327, 247, -8),
    ], PINK),
    new Route(43, 8, 36, [
        new RouteSpace(784, 140, 44),
        new RouteSpace(832, 185, 44),
        new RouteSpace(878, 230, 44),
        new RouteSpace(925, 276, 44),
    ], BLACK),
    new Route(44, 9, 11, [
        new RouteSpace(647, 962, 30),
        new RouteSpace(707, 989, 18),
        new RouteSpace(770, 1004, 8),
        new RouteSpace(836, 1010, 2),
        new RouteSpace(902, 1006, -10),
        new RouteSpace(964, 989, -19),
    ], GREEN),
    new Route(45, 9, 15, [
        new RouteSpace(221, 886, 36),
        new RouteSpace(277, 919, 24),
        new RouteSpace(340, 941, 15),
        new RouteSpace(405, 954, 9),
        new RouteSpace(470, 956, -4),
        new RouteSpace(534, 946, -14, true),
    ], BLACK),
    new Route(46, 9, 21, [
        new RouteSpace(656, 913, 342),
        new RouteSpace(716, 889, 334),
        new RouteSpace(775, 856, 326),
        new RouteSpace(825, 814, 315),
        new RouteSpace(867, 764, 305),
    ], YELLOW),
    new Route(47, 9, 23, [
        new RouteSpace(562, 917, 16),
        new RouteSpace(499, 899, 16),
        new RouteSpace(437, 881, 16),
    ], GRAY),
    new Route(48, 9, 31, [
        new RouteSpace(609, 883, 273),
        new RouteSpace(612, 816, 273),
    ], GRAY),
    new Route(49, 10, 22, [
        new RouteSpace(593, 357, 22),
        new RouteSpace(653, 381, 22),
        new RouteSpace(714, 406, 22),
        new RouteSpace(776, 431, 22),
        new RouteSpace(835, 456, 22),
    ], RED),
    new Route(50, 10, 28, [
        new RouteSpace(493, 371, -59),
        new RouteSpace(461, 426, -59),
        new RouteSpace(426, 483, -59),
    ], PINK),
    new Route(51, 10, 32, [
        new RouteSpace(149, 245, 12),
        new RouteSpace(213, 260, 12),
        new RouteSpace(276, 274, 12),
        new RouteSpace(340, 289, 12),
        new RouteSpace(403, 303, 12),
        new RouteSpace(467, 317, 12),
    ], YELLOW),
    new Route(52, 10, 36, [
        new RouteSpace(569, 277, -47),
        new RouteSpace(615, 230, -47),
        new RouteSpace(661, 185, -47),
        new RouteSpace(706, 138, -47),
    ], BLUE),
    new Route(53, 11, 19, [
        new RouteSpace(1126, 943, 352),
        new RouteSpace(1062, 953, 352),
    ], GRAY),
    new Route(54, 12, 21, [
        new RouteSpace(924, 618, -74),
        new RouteSpace(906, 680, -74),
    ], GRAY),
    new Route(55, 12, 21, [
        new RouteSpace(945, 624, -74),
        new RouteSpace(928, 686, -74),
    ], GRAY),
    new Route(56, 12, 22, [
        new RouteSpace(913, 531, -115, true),
    ], GRAY),
    new Route(57, 12, 22, [
        new RouteSpace(933, 521, -115),
    ], GRAY),
    new Route(58, 12, 27, [
        new RouteSpace(984, 558, -1),
        new RouteSpace(1049, 556, -1),
    ], BLUE),
    new Route(59, 12, 27, [
        new RouteSpace(984, 581, -1),
        new RouteSpace(1049, 579, -1),
    ], PINK),
    new Route(60, 13, 15, [
        new RouteSpace(191, 796, -65),
        new RouteSpace(242, 754, -12),
    ], GRAY),
    new Route(61, 13, 28, [
        new RouteSpace(334, 719, -45),
        new RouteSpace(370, 665, -67),
        new RouteSpace(388, 601, -81),
    ], ORANGE),
    new Route(62, 14, 18, [
        new RouteSpace(1111, 731, -4),
        new RouteSpace(1177, 714, -24),
        new RouteSpace(1233, 678, -41),
    ], WHITE),
    new Route(63, 14, 19, [
        new RouteSpace(1090, 779, 63),
        new RouteSpace(1119, 836, 63),
        new RouteSpace(1151, 894, 63),
    ], GREEN),
    new Route(64, 14, 21, [
        new RouteSpace(954, 730, -2),
        new RouteSpace(1017, 728, -2),
    ], GRAY),
    new Route(65, 14, 27, [
        new RouteSpace(1087, 621, -75),
        new RouteSpace(1072, 685, -75),
    ], GRAY),
    new Route(66, 15, 23, [
        new RouteSpace(220, 837, -8),
        new RouteSpace(285, 834, 1),
        new RouteSpace(350, 841, 13),
    ], GRAY),
    new Route(67, 15, 30, [
        new RouteSpace(47, 717, -113, true),
        new RouteSpace(78, 776, -125, true),
        new RouteSpace(120, 826, -134, true),
    ], YELLOW),
    new Route(68, 15, 30, [
        new RouteSpace(66, 705, -113),
        new RouteSpace(98, 762, -125),
        new RouteSpace(139, 812, -134),
    ], PINK),
    new Route(69, 16, 19, [
        new RouteSpace(1537, 993, 49),
        new RouteSpace(1492, 946, 40),
        new RouteSpace(1437, 910, 28),
        new RouteSpace(1374, 894, 0),
        new RouteSpace(1308, 902, 345),
        new RouteSpace(1248, 929, 330),
    ], RED),
    new Route(70, 17, 20, [
        new RouteSpace(1528, 134, 80),
        new RouteSpace(1538, 199, 80),
        new RouteSpace(1549, 262, 80),
    ], BLUE),
    new Route(71, 17, 29, [
        new RouteSpace(1227, 155, -40),
        new RouteSpace(1281, 119, -28),
        new RouteSpace(1340, 93, -19),
        new RouteSpace(1405, 75, -13),
        new RouteSpace(1470, 68, 0),
    ], BLACK),
    new Route(72, 17, 33, [
        new RouteSpace(1389, 184, -59),
        new RouteSpace(1431, 133, -42),
        new RouteSpace(1487, 97, -23),
    ], GRAY),
    new Route(73, 18, 24, [
        new RouteSpace(1259, 603, -62),
        new RouteSpace(1295, 547, -51),
        new RouteSpace(1344, 502, -33),
        new RouteSpace(1391, 458, -56),
    ], YELLOW),
    new Route(74, 18, 26, [
        new RouteSpace(1305, 613, -32),
        new RouteSpace(1365, 588, -13),
        new RouteSpace(1431, 582, 4),
    ], BLACK),
    new Route(75, 18, 27, [
        new RouteSpace(1141, 613, 17),
        new RouteSpace(1203, 633, 17),
    ], GRAY),
    new Route(76, 20, 24, [
        new RouteSpace(1455, 356, -31),
        new RouteSpace(1510, 322, -31),
    ], WHITE),
    new Route(77, 20, 24, [
        new RouteSpace(1467, 375, -31),
        new RouteSpace(1522, 342, -31),
    ], GREEN),
    new Route(78, 20, 35, [
        new RouteSpace(1568, 366, 87),
        new RouteSpace(1571, 432, 87),
    ], ORANGE),
    new Route(79, 20, 35, [
        new RouteSpace(1590, 364, 87),
        new RouteSpace(1593, 430, 87),
    ], BLACK),
    new Route(80, 21, 31, [
        new RouteSpace(670, 765, -7),
        new RouteSpace(734, 759, -7),
        new RouteSpace(800, 751, -7),
    ], BLUE),
    new Route(81, 23, 31, [
        new RouteSpace(449, 839, -23),
        new RouteSpace(511, 812, -23),
        new RouteSpace(569, 785, -23),
    ], GRAY),
    new Route(82, 24, 26, [
        new RouteSpace(1437, 467, 77),
        new RouteSpace(1452, 532, 77),
    ], GRAY),
    new Route(83, 24, 27, [
        new RouteSpace(1141, 564, -30),
        new RouteSpace(1198, 531, -30),
        new RouteSpace(1254, 498, -30),
        new RouteSpace(1311, 466, -30),
        new RouteSpace(1368, 434, -30),
    ], GREEN),
    new Route(84, 24, 33, [
        new RouteSpace(1399, 277, -93),
        new RouteSpace(1403, 344, -93),
    ], GRAY),
    new Route(85, 24, 35, [
        new RouteSpace(1470, 428, 28),
        new RouteSpace(1528, 459, 28),
    ], GRAY),
    new Route(86, 25, 28, [
        new RouteSpace(111, 315, 12),
        new RouteSpace(173, 333, 20),
        new RouteSpace(234, 363, 33),
        new RouteSpace(287, 401, 40),
        new RouteSpace(334, 446, 49),
        new RouteSpace(371, 499, 61),
    ], BLUE),
    new Route(87, 25, 30, [
        new RouteSpace(28, 351, 111),
        new RouteSpace(8, 414, 102),
        new RouteSpace(1, 479, 93, true),
        new RouteSpace(2, 544, 86, true),
        new RouteSpace(14, 610, 73, true),
    ], GREEN),
    new Route(88, 25, 30, [
        new RouteSpace(51, 354, 111),
        new RouteSpace(31, 417, 102),
        new RouteSpace(24, 482, 93),
        new RouteSpace(25, 547, 86),
        new RouteSpace(37, 613, 73),
    ], PINK),
    new Route(89, 25, 32, [
        new RouteSpace(67, 254, 112),
    ], GRAY),
    new Route(90, 25, 32, [
        new RouteSpace(88, 262, 112),
    ], GRAY),
    new Route(91, 26, 35, [
        new RouteSpace(1502, 561, -50),
        new RouteSpace(1545, 511, -50),
    ], GRAY),
    new Route(92, 26, 35, [
        new RouteSpace(1519, 575, -50),
        new RouteSpace(1561, 525, -50),
    ], GRAY),
    new Route(93, 28, 30, [
        new RouteSpace(92, 633, -18),
        new RouteSpace(153, 613, -18),
        new RouteSpace(214, 593, -18),
        new RouteSpace(274, 572, -18),
        new RouteSpace(336, 553, -18),
    ], ORANGE),
    new Route(94, 28, 30, [
        new RouteSpace(99, 654, -18),
        new RouteSpace(160, 634, -18),
        new RouteSpace(221, 614, -18),
        new RouteSpace(282, 593, -18),
        new RouteSpace(343, 573, -18),
    ], WHITE),
    new Route(95, 29, 33, [
        new RouteSpace(1245, 201, 11),
        new RouteSpace(1309, 215, 11),
    ], GRAY),
    new Route(96, 29, 36, [
        new RouteSpace(816, 105, 12),
        new RouteSpace(880, 118, 12),
        new RouteSpace(944, 131, 12),
        new RouteSpace(1007, 145, 12),
        new RouteSpace(1070, 159, 12),
        new RouteSpace(1134, 171, 12),
    ], GRAY),
    new Route(97, 32, 34, [
        new RouteSpace(89, 165, 89),
    ], GRAY),
    new Route(98, 32, 34, [
        new RouteSpace(112, 165, 89),
    ], GRAY),
  ];
