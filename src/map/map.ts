const FACTOR = 1.057;

const SIDES = ['left', 'right', 'top', 'bottom'];
const CORNERS = ['bottom-left', 'bottom-right', 'top-left', 'top-right'];

class City {
    constructor(
        public id: number,
        public x: number,
        public y: number,
    ) {}
}

class RouteSpace {
    constructor(
        public x: number,
        public y: number,
        public angle: number,
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
    new City(1, 1320, 687), // Atlanta
    new City(2, 1609, 186), // Boston
    new City(3, 358, 94), // Calgary
    new City(4, 1480, 699), // Charleston
    new City(5, 1149, 418), // Chicago
    new City(6, 921, 859), // Dallas
    new City(7, 630, 588), // Denver
    new City(8, 938, 312), // Duluth
    new City(9, 609, 899), // El Paso
    new City(10, 529, 322), // Helena
    new City(11, 992, 927), // Houston
    new City(12, 921, 558), // Kansas City
    new City(13, 309, 723), // Las Vegas
    new City(14, 1042, 714), // Little Rock
    new City(15, 198, 824), // Los Angeles
    new City(16, 1536, 970), // Miami
    new City(17, 1486, 85), // Montréal
    new City(18, 1232, 627), // Nashville
    new City(19, 1157, 914), // New Orleans
    new City(20, 1519, 315), // New York
    new City(21, 886, 707), // Oklahoma City
    new City(22, 886, 470), // Omaha
    new City(23, 405, 836), // Phoenix
    new City(24, 1374, 392), // Pittsburgh
    new City(25, 89, 305), // Portland
    new City(26, 1432, 586), // Raleigh
    new City(27, 1072, 560), // Saint Louis
    new City(28, 407, 534), // Salt Lake City
    new City(29, 1157, 198), // Sault St. Marie
    new City(30, 65, 645), // San Francisco
    new City(31, 618, 745), // Santa Fe
    new City(32, 126, 218), // Seattle
    new City(33, 1344, 237), // Toronto
    new City(34, 132, 125), // Vancouver
    new City(35, 1532, 471), // Washington
    new City(36, 744, 113), // Winnipeg
];

const ROUTES = [
    new Route(1, 1, 4, [
        new RouteSpace(1337.16, 689.35, 3),
        new RouteSpace(1399.27, 691.33, 3),
    ], GRAY),
    new Route(2, 1, 16, [
        new RouteSpace(1310.53, 721.89, 51),
        new RouteSpace(1349.97, 770.21, 51),
        new RouteSpace(1388.43, 818.52, 51),
        new RouteSpace(1426.88, 865.85, 51),
        new RouteSpace(1465.34, 913.18, 51),
    ], BLUE),
    new Route(3, 1, 18, [
        new RouteSpace(1235.60, 637.10, 35),
    ], GRAY),
    new Route(4, 1, 19, [
        new RouteSpace(1133.05, 841.20, 291),
        new RouteSpace(1160.66, 785.98, 303),
        new RouteSpace(1198.13, 736.68, 310),
        new RouteSpace(1241.51, 692.31, 319),
    ], YELLOW),
    new Route(5, 1, 19, [
        new RouteSpace(1148.83, 856.97, 291),
        new RouteSpace(1177.42, 801.76, 303),
        new RouteSpace(1214.89, 752.46, 310),
        new RouteSpace(1257.29, 709.07, 319),
    ], ORANGE),
    new Route(6, 1, 26, [
        new RouteSpace(1310.53, 633.15, -41),
        new RouteSpace(1356.88, 592.73, -41),
    ], GRAY),
    new Route(7, 1, 26, [
        new RouteSpace(1370.68, 608.50, -41),
        new RouteSpace(1324.34, 649.91, -41),
    ], GRAY),
    new Route(8, 2, 17, [
        new RouteSpace(1497.87, 92.82, 40),
        new RouteSpace(1545.20, 131.28, 40),
    ], GRAY),
    new Route(9, 2, 17, [
        new RouteSpace(1483.08, 108.60, 40),
        new RouteSpace(1530.41, 148.04, 40),
    ], GRAY),
    new Route(10, 2, 20, [
        new RouteSpace(1542.24, 208.19, 122),
        new RouteSpace(1509.71, 260.44, 122),
    ], YELLOW),
    new Route(11, 2, 20, [
        new RouteSpace(1559.99, 219.03, 122),
        new RouteSpace(1527.45, 271.29, 122),
    ], RED),
    new Route(12, 3, 10, [
        new RouteSpace(357, 120, 50),
        new RouteSpace(397, 168, 50),
        new RouteSpace(436, 215, 50),
        new RouteSpace(476, 262, 50),
    ], GRAY),
    new Route(12, 3, 32, [
        new RouteSpace(146, 200, 0),
        new RouteSpace(208, 196, -7),
        new RouteSpace(266, 173, -37),
        new RouteSpace(307, 124, -63),
    ], GRAY),
    new Route(13, 3, 34, [
        new RouteSpace(150, 97, -6),
        new RouteSpace(212, 90, -6),
        new RouteSpace(274, 83, -6),
    ], GRAY),
    new Route(14, 3, 36, [
        new RouteSpace(370, 62, -23),
        new RouteSpace(429, 44, -11),
        new RouteSpace(490, 35, -2),
        new RouteSpace(553, 36, 4),
        new RouteSpace(615, 48, 15),
        new RouteSpace(672, 69, 25),
    ], WHITE),
    new Route(15, 4, 16, [
        new RouteSpace(1447.59, 726.82, 87),
        new RouteSpace(1452.52, 788.94, 82),
        new RouteSpace(1467.31, 850.07, 73),
        new RouteSpace(1491.96, 906.27, 59),
    ], PINK),
    new Route(16, 4, 26, [
        new RouteSpace(1432.80, 599.63, 35),
        new RouteSpace(1460.41, 641.04, -65),
    ], GRAY),
    new Route(17, 5, 8, [
        new RouteSpace(940.78, 325.52, 28),
        new RouteSpace(996.99, 351.16, 21),
        new RouteSpace(1057.13, 368.90, 14),
    ], RED),
    new Route(18, 5, 22, [
        new RouteSpace(894.44, 435.95, -34),
        new RouteSpace(943.74, 402.43, -34),
        new RouteSpace(1004.87, 387.64, 8),
        new RouteSpace(1063.05, 396.51, 8),
    ], BLUE),
    new Route(19, 5, 24, [
        new RouteSpace(1154.75, 367.92, -14),
        new RouteSpace(1215.88, 355.10, -9),
        new RouteSpace(1278.00, 352.14, 3),
    ], ORANGE),
    new Route(20, 5, 24, [
        new RouteSpace(1162.63, 389.61, -14),
        new RouteSpace(1223.77, 376.79, -9),
        new RouteSpace(1284.90, 373.83, 3),
    ], BLACK),
    new Route(21, 5, 27, [
        new RouteSpace(1041.36, 491.17, -57),
        new RouteSpace(1073.89, 440.88, -57),
    ], GREEN),
    new Route(22, 5, 27, [
        new RouteSpace(1060.09, 500.04, -57),
        new RouteSpace(1092.63, 449.76, -57),
    ], WHITE),
    new Route(23, 5, 33, [
        new RouteSpace(1122.21, 351.16, -48),
        new RouteSpace(1169.54, 310.73, -35),
        new RouteSpace(1225.74, 283.12, -17),
        new RouteSpace(1280.95, 254.53, -39),
    ], WHITE),
    new Route(24, 6, 9, [
        new RouteSpace(653.86, 882.61, 351),
        new RouteSpace(714.00, 872.75, 351),
        new RouteSpace(775.14, 863.88, 351),
        new RouteSpace(836.27, 855.00, 351),
    ], RED),
    new Route(25, 6, 11, [
        new RouteSpace(912.19, 879.65, 49),
    ], GRAY),
    new Route(26, 6, 11, [
        new RouteSpace(928.95, 865.85, 49),
    ], GRAY),
    new Route(27, 6, 14, [
        new RouteSpace(932.90, 785.00, -55),
        new RouteSpace(968.39, 734.71, -55),
    ], GRAY),
    new Route(28, 6, 21, [
        new RouteSpace(861.90, 734.71, -97),
        new RouteSpace(869.79, 795.84, -97),
    ], GRAY),
    new Route(29, 6, 21, [
        new RouteSpace(882.61, 732.74, -97),
        new RouteSpace(890.50, 793.87, -97),
    ], GRAY),
    new Route(30, 7, 10, [
        new RouteSpace(515.82, 349.18, 67),
        new RouteSpace(537.51, 405.39, 67),
        new RouteSpace(561.17, 461.59, 67),
        new RouteSpace(583.85, 519.76, 67),
    ], GREEN),
    new Route(31, 7, 12, [
        new RouteSpace(654.84, 572.02, 5),
        new RouteSpace(716.96, 573.01, -5),
        new RouteSpace(778.09, 564.13, -11),
        new RouteSpace(838.24, 545.40, -23),
    ], BLACK),
    new Route(32, 7, 12, [
        new RouteSpace(654.84, 594.70, 5),
        new RouteSpace(716.96, 595.68, -5),
        new RouteSpace(778.09, 586.81, -11),
        new RouteSpace(838.24, 568.08, -23),
    ], ORANGE),
    new Route(33, 7, 21, [
        new RouteSpace(626.25, 622.31, 224),
        new RouteSpace(678.51, 658.79, 205),
        new RouteSpace(736.68, 675.55, 189),
        new RouteSpace(798.80, 681.47, 184),
    ], RED),
    new Route(34, 7, 22, [
        new RouteSpace(632.17, 535.54, -38),
        new RouteSpace(684.42, 503.00, -26),
        new RouteSpace(741.61, 480.32, -16),
        new RouteSpace(802.74, 466.52, -12),
    ], PINK),
    new Route(35, 7, 23, [
        new RouteSpace(388.62, 774.15, -68),
        new RouteSpace(415.25, 720.91, -61),
        new RouteSpace(447.78, 667.66, -55),
        new RouteSpace(488.21, 622.31, -39),
        new RouteSpace(543.43, 590.75, -20),
    ], WHITE),
    new Route(36, 7, 28, [
        new RouteSpace(421.16, 513.85, 11),
        new RouteSpace(482.29, 525.68, 11),
        new RouteSpace(540.47, 537.51, 11),
    ], RED),
    new Route(37, 7, 28, [
        new RouteSpace(417.22, 534.55, 11),
        new RouteSpace(478.35, 546.38, 11),
        new RouteSpace(536.52, 558.22, 11),
    ], YELLOW),
    new Route(99, 7, 31, [
        new RouteSpace(585.82, 621.32, 273),
        new RouteSpace(582.87, 682.45, 273),
    ], GRAY),
    new Route(38, 8, 10, [
        new RouteSpace(545.40, 302.84, -1),
        new RouteSpace(607.52, 302.84, -1),
        new RouteSpace(669.63, 301.86, -1),
        new RouteSpace(731.75, 300.87, -1),
        new RouteSpace(793.87, 299.88, -1),
        new RouteSpace(855.99, 298.90, -1),
    ], ORANGE),
    new Route(39, 8, 22, [
        new RouteSpace(874.72, 343.27, -68),
        new RouteSpace(852.04, 400.46, -68),
    ], GRAY),
    new Route(40, 8, 22, [
        new RouteSpace(894.44, 350.17, -68),
        new RouteSpace(871.76, 407.36, -68),
    ], GRAY),
    new Route(41, 8, 29, [
        new RouteSpace(961.49, 254.53, -23),
        new RouteSpace(1017.69, 231.85, -23),
        new RouteSpace(1074.88, 207.20, -23),
    ], GRAY),
    new Route(42, 8, 33, [
        new RouteSpace(949.66, 287.07, -8),
        new RouteSpace(1010.79, 276.22, -8),
        new RouteSpace(1071.92, 265.37, -8),
        new RouteSpace(1133.05, 255.51, -8),
        new RouteSpace(1193.20, 244.67, -8),
        new RouteSpace(1255.32, 233.82, -8),
    ], PINK),
    new Route(43, 8, 36, [
        new RouteSpace(742, 132, 44),
        new RouteSpace(787, 175, 44),
        new RouteSpace(831, 218, 44),
        new RouteSpace(875, 261, 44),
    ], BLACK),
    new Route(44, 9, 11, [
        new RouteSpace(612.45, 910.22, 30),
        new RouteSpace(668.65, 935.85, 18),
        new RouteSpace(728.79, 949.66, 8),
        new RouteSpace(790.91, 955.57, 2),
        new RouteSpace(853.03, 951.63, -10),
        new RouteSpace(912.19, 935.85, -19),
    ], GREEN),
    new Route(45, 9, 15, [
        new RouteSpace(209.17, 838.24, 36),
        new RouteSpace(262.42, 869.79, 24),
        new RouteSpace(321.58, 890.50, 15),
        new RouteSpace(382.71, 902.33, 9),
        new RouteSpace(444.83, 904.30, -4),
        new RouteSpace(504.97, 895.43, -14),
    ], BLACK),
    new Route(46, 9, 21, [
        new RouteSpace(620.33, 863.88, 342),
        new RouteSpace(677.52, 841.20, 334),
        new RouteSpace(732.74, 809.65, 326),
        new RouteSpace(780.07, 770.21, 315),
        new RouteSpace(820.49, 722.88, 305),
    ], YELLOW),
    new Route(47, 9, 23, [
        new RouteSpace(531.59, 867.82, 16),
        new RouteSpace(472.43, 850.07, 16),
        new RouteSpace(413.27, 833.31, 16),
    ], GRAY),
    new Route(48, 9, 31, [
        new RouteSpace(575.96, 835.28, 273),
        new RouteSpace(578.92, 772.18, 273),
    ], GRAY),
    new Route(49, 10, 22, [
        new RouteSpace(561.17, 337.35, 22),
        new RouteSpace(617.38, 360.03, 22),
        new RouteSpace(675.55, 383.69, 22),
        new RouteSpace(733.72, 407.36, 22),
        new RouteSpace(789.93, 431.02, 22),
    ], RED),
    new Route(50, 10, 28, [
        new RouteSpace(466.52, 351.16, -59),
        new RouteSpace(435.95, 403.41, -59),
        new RouteSpace(403.41, 456.66, -59),
    ], PINK),
    new Route(51, 10, 32, [
        new RouteSpace(141.14, 231.85, 12),
        new RouteSpace(201.28, 245.65, 12),
        new RouteSpace(261.43, 259.46, 12),
        new RouteSpace(321.58, 273.26, 12),
        new RouteSpace(381.72, 287.07, 12),
        new RouteSpace(441.87, 299.88, 12),
    ], YELLOW),
    new Route(52, 10, 36, [
        new RouteSpace(538, 262, -47),
        new RouteSpace(582, 218, -47),
        new RouteSpace(625, 175, -47),
        new RouteSpace(668, 131, -47),
    ], BLUE),
    new Route(53, 11, 19, [
        new RouteSpace(1065.02, 892.47, 352),
        new RouteSpace(1004.87, 901.34, 352),
    ], GRAY),
    new Route(54, 12, 21, [
        new RouteSpace(873.74, 584.84, -74),
        new RouteSpace(856.97, 643.01, -74),
    ], GRAY),
    new Route(55, 12, 21, [
        new RouteSpace(894.44, 590.75, -74),
        new RouteSpace(877.68, 648.93, -74),
    ], GRAY),
    new Route(56, 12, 22, [
        new RouteSpace(863.88, 502.01, -115),
    ], GRAY),
    new Route(57, 12, 22, [
        new RouteSpace(882.61, 493.14, -115),
    ], GRAY),
    new Route(58, 12, 27, [
        new RouteSpace(930.92, 527.65, -1),
        new RouteSpace(992.06, 525.68, -1),
    ], BLUE),
    new Route(59, 12, 27, [
        new RouteSpace(930.92, 549.34, -1),
        new RouteSpace(992.06, 547.37, -1),
    ], PINK),
    new Route(60, 13, 15, [
        new RouteSpace(180.58, 753.44, -65),
        new RouteSpace(228.89, 713.02, -12),
    ], GRAY),
    new Route(61, 13, 28, [
        new RouteSpace(315.66, 680.48, -45),
        new RouteSpace(350.17, 629.21, -67),
        new RouteSpace(366.93, 569.06, -81),
    ], ORANGE),
    new Route(62, 14, 18, [
        new RouteSpace(1051.22, 691.33, -4),
        new RouteSpace(1113.33, 675.55, -24),
        new RouteSpace(1166.58, 641.04, -41),
    ], WHITE),
    new Route(63, 14, 19, [
        new RouteSpace(1031.50, 736.68, 63),
        new RouteSpace(1059.10, 790.91, 63),
        new RouteSpace(1088.68, 846.13, 63),
    ], GREEN),
    new Route(64, 14, 21, [
        new RouteSpace(902.33, 690.34, -2),
        new RouteSpace(962.48, 688.37, -2),
    ], GRAY),
    new Route(65, 14, 27, [
        new RouteSpace(1028.54, 587.80, -75),
        new RouteSpace(1013.75, 647.94, -75),
    ], GRAY),
    new Route(66, 15, 23, [
        new RouteSpace(208.19, 791.90, -8),
        new RouteSpace(269.32, 788.94, 1),
        new RouteSpace(331.44, 795.84, 13),
    ], GRAY),
    new Route(67, 15, 30, [
        new RouteSpace(44.51, 678.51, -113),
        new RouteSpace(74.09, 733.72, -125),
        new RouteSpace(113.53, 781.05, -134),
    ], YELLOW),
    new Route(68, 15, 30, [
        new RouteSpace(62.26, 666.68, -113),
        new RouteSpace(92.82, 720.91, -125),
        new RouteSpace(131.28, 768.23, -134),
    ], PINK),
    new Route(69, 16, 19, [
        new RouteSpace(1454.49, 939.80, 49),
        new RouteSpace(1411.11, 895.43, 40),
        new RouteSpace(1359.83, 860.92, 28),
        new RouteSpace(1299.69, 846.13, 0),
        new RouteSpace(1237.57, 853.03, 345),
        new RouteSpace(1180.38, 878.67, 330),
    ], RED),
    new Route(70, 17, 20, [
        new RouteSpace(1445.62, 126.35, 80),
        new RouteSpace(1455.48, 188.47, 80),
        new RouteSpace(1465.34, 247.63, 80),
    ], BLUE),
    new Route(71, 17, 29, [
        new RouteSpace(1161, 147, -40),
        new RouteSpace(1212, 113, -28),
        new RouteSpace(1268, 88, -19),
        new RouteSpace(1329, 71, -13),
        new RouteSpace(1391, 64, 0),
    ], BLACK),
    new Route(72, 17, 33, [
        new RouteSpace(1314.48, 173.68, -59),
        new RouteSpace(1353.92, 125.36, -42),
        new RouteSpace(1407.16, 91.84, -23),
    ], GRAY),
    new Route(73, 18, 24, [
        new RouteSpace(1191.23, 570.05, -62),
        new RouteSpace(1224.75, 517.79, -51),
        new RouteSpace(1271.09, 475.39, -33),
        new RouteSpace(1316.45, 432.99, -56),
    ], YELLOW),
    new Route(74, 18, 26, [
        new RouteSpace(1234.61, 579.91, -32),
        new RouteSpace(1291.80, 556.24, -13),
        new RouteSpace(1353.92, 550.33, 4),
    ], BLACK),
    new Route(75, 18, 27, [
        new RouteSpace(1079.81, 579.91, 17),
        new RouteSpace(1137.98, 598.64, 17),
    ], GRAY),
    new Route(76, 20, 24, [
        new RouteSpace(1376.60, 336.37, -31),
        new RouteSpace(1428.85, 304.81, -31),
    ], WHITE),
    new Route(77, 20, 24, [
        new RouteSpace(1387.44, 355.10, -31),
        new RouteSpace(1439.70, 323.55, -31),
    ], GREEN),
    new Route(78, 20, 35, [
        new RouteSpace(1483.08, 346.23, 87),
        new RouteSpace(1486.04, 408.34, 87),
    ], ORANGE),
    new Route(79, 20, 35, [
        new RouteSpace(1503.79, 344.25, 87),
        new RouteSpace(1506.75, 406.37, 87),
    ], BLACK),
    new Route(80, 21, 31, [
        new RouteSpace(634.14, 723.86, -7),
        new RouteSpace(694.28, 717.95, -7),
        new RouteSpace(756.40, 710.06, -7),
    ], BLUE),
    new Route(81, 23, 31, [
        new RouteSpace(425.11, 793.87, -23),
        new RouteSpace(483.28, 768.23, -23),
        new RouteSpace(538.50, 742.60, -23),
    ], GRAY),
    new Route(82, 24, 26, [
        new RouteSpace(1359.83, 441.87, 77),
        new RouteSpace(1373.64, 503.00, 77),
    ], GRAY),
    new Route(83, 24, 27, [
        new RouteSpace(1079.81, 533.57, -30),
        new RouteSpace(1133.05, 502.01, -30),
        new RouteSpace(1186.30, 471.45, -30),
        new RouteSpace(1240.53, 440.88, -30),
        new RouteSpace(1293.77, 410.32, -30),
    ], GREEN),
    new Route(84, 24, 33, [
        new RouteSpace(1323.35, 262.42, -93),
        new RouteSpace(1327.30, 325.52, -93),
    ], GRAY),
    new Route(85, 24, 35, [
        new RouteSpace(1390.40, 405.39, 28),
        new RouteSpace(1445.62, 433.98, 28),
    ], GRAY),
    new Route(86, 25, 28, [
        new RouteSpace(104.66, 297.91, 12),
        new RouteSpace(163.82, 314.67, 20),
        new RouteSpace(221.00, 343.27, 33),
        new RouteSpace(271.29, 379.75, 40),
        new RouteSpace(315.66, 422.15, 49),
        new RouteSpace(351.16, 472.43, 61),
    ], BLUE),
    new Route(87, 25, 30, [
        new RouteSpace(26.76, 332.42, 111),
        new RouteSpace(8.03, 391.58, 102),
        new RouteSpace(1.13, 452.71, 93),
        new RouteSpace(2.11, 514.83, 86),
        new RouteSpace(12.96, 576.95, 73),
    ], GREEN),
    new Route(88, 25, 30, [
        new RouteSpace(48.45, 335.38, 111),
        new RouteSpace(29.72, 394.54, 102),
        new RouteSpace(22.82, 455.67, 93),
        new RouteSpace(23.80, 517.79, 86),
        new RouteSpace(34.65, 579.91, 73),
    ], PINK),
    new Route(89, 25, 32, [
        new RouteSpace(63.24, 240.72, 112),
    ], GRAY),
    new Route(90, 25, 32, [
        new RouteSpace(82.96, 247.63, 112),
    ], GRAY),
    new Route(91, 26, 35, [
        new RouteSpace(1420.97, 530.61, -50),
        new RouteSpace(1461.39, 483.28, -50),
    ], GRAY),
    new Route(92, 26, 35, [
        new RouteSpace(1436.74, 544.41, -50),
        new RouteSpace(1477.17, 497.08, -50),
    ], GRAY),
    new Route(93, 28, 30, [
        new RouteSpace(86.91, 598.64, -18),
        new RouteSpace(145.08, 579.91, -18),
        new RouteSpace(202.27, 561.17, -18),
        new RouteSpace(259.46, 541.45, -18),
        new RouteSpace(317.63, 522.72, -18),
    ], ORANGE),
    new Route(94, 28, 30, [
        new RouteSpace(93.81, 618.36, -18),
        new RouteSpace(151.00, 599.63, -18),
        new RouteSpace(209.17, 580.89, -18),
        new RouteSpace(266.36, 561.17, -18),
        new RouteSpace(324.53, 542.44, -18),
    ], WHITE),
    new Route(95, 29, 33, [
        new RouteSpace(1177.42, 190.44, 11),
        new RouteSpace(1238.56, 203.26, 11),
    ], GRAY),
    new Route(96, 29, 36, [
        new RouteSpace(772.18, 99.73, 12),
        new RouteSpace(832.32, 111.56, 12),
        new RouteSpace(893.46, 124.38, 12),
        new RouteSpace(952.62, 137.19, 12),
        new RouteSpace(1012.76, 150.01, 12),
        new RouteSpace(1072.91, 161.84, 12),
    ], GRAY),
    new Route(97, 32, 34, [
        new RouteSpace(84, 156, 89),
    ], GRAY),
    new Route(98, 32, 34, [
        new RouteSpace(106, 156, 89),
    ], GRAY),
  ];

class TtrMap {
    //private points = new Map<number, number>();
    private scale: number;
    private resizedDiv: HTMLDivElement;
    private mapZoomDiv: HTMLDivElement;
    private mapDiv: HTMLDivElement;
    private pos = { dragging: false, top: 0, left: 0, x: 0, y: 0 };
    private zoomed = false;

    constructor(
        private game: TicketToRideGame,
        private players: TicketToRidePlayer[],
        claimedRoutes: ClaimedRoute[],
    ) {
        // map border
        dojo.place(`<div class="illustration"></div>`, 'map-and-borders');
        SIDES.forEach(side => dojo.place(`<div class="side ${side}"></div>`, 'map-and-borders'));
        CORNERS.forEach(corner => dojo.place(`<div class="corner ${corner}"></div>`, 'map-and-borders'));

        /*let html = '';

        // points
        players.forEach(player => {
            html += `<div id="player-${player.id}-point-marker" class="point-marker ${this.game.isColorBlindMode() ? 'color-blind' : ''}" data-player-no="${player.playerNo}" style="background: #${player.color};"></div>`;
            this.points.set(Number(player.id), Number(player.score));
        });
        dojo.place(html, 'map');*/
        

        CITIES.forEach(city => 
            dojo.place(`<div id="city${city.id}" class="city" 
                style="transform: translate(${city.x*FACTOR}px, ${city.y*FACTOR}px)"
                title="${CITIES_NAMES[city.id]}"
            ></div>`, 'map')
        );


        ROUTES.forEach(route => 
            route.spaces.forEach((space, spaceIndex) => {
                dojo.place(`<div id="route${route.id}-space${spaceIndex}" class="route space" 
                    style="transform: translate(${space.x*FACTOR}px, ${space.y*FACTOR}px) rotate(${space.angle}deg)"
                    title="${CITIES_NAMES[route.from]} to ${CITIES_NAMES[route.to]}, ${(route.spaces as any).length} ${COLORS[route.color]}"
                    data-route="${route.id}"
                ></div>`, 'map');
                const spaceDiv = document.getElementById(`route${route.id}-space${spaceIndex}`);
                //spaceDiv.addEventListener('click', () => this.game.claimRoute(route.id));   
                const enterover = (e: DragEvent) => {
                    const cardsColor = Number(this.mapDiv.dataset.dragColor);
                    const canClaimRoute = this.game.canClaimRoute(route, cardsColor);
                    this.setHoveredRoute(route, canClaimRoute);
                    if (canClaimRoute) {
                        e.preventDefault();
                    }
                };
                spaceDiv.addEventListener('dragenter', enterover);
                spaceDiv.addEventListener('dragover', enterover);
                spaceDiv.addEventListener('dragleave', (e) => {
                    this.setHoveredRoute(null);
                });
                spaceDiv.addEventListener('drop', (e) => {
                    this.setHoveredRoute(null);
                    const cardsColor = Number(this.mapDiv.dataset.dragColor);
                    
                    this.game.claimRoute(route.id, cardsColor);
                });
            })
        );

        /*console.log(ROUTES.map(route => `    new Route(${route.id}, ${route.from}, ${route.to}, [
${route.spaces.map(space => `        new RouteSpace(${(space.x*0.986 + 10).toFixed(2)}, ${(space.y*0.986 + 10).toFixed(2)}, ${space.angle}),`).join('\n')}
    ], ${COLORS[route.color]}),`).join('\n'));*/

        //this.movePoints();
        this.setClaimedRoutes(claimedRoutes);

        this.resizedDiv = document.getElementById('resized') as HTMLDivElement;
        this.mapZoomDiv = document.getElementById('map-zoom') as HTMLDivElement;
        this.mapDiv = document.getElementById('map') as HTMLDivElement;
        // Attach the handler
        this.mapDiv.addEventListener('mousedown', e => this.mouseDownHandler(e));
        document.addEventListener('mousemove', e => this.mouseMoveHandler(e));
        document.addEventListener('mouseup', e => this.mouseUpHandler());
        document.getElementById('zoom-button').addEventListener('click', () => this.toggleZoom());
        
        /*this.mapDiv.addEventListener('dragenter', e => this.mapDiv.classList.add('drag-over'));
        this.mapDiv.addEventListener('dragleave', e => this.mapDiv.classList.remove('drag-over'));
        this.mapDiv.addEventListener('drop', e => this.mapDiv.classList.remove('drag-over'));*/
    }

    /*public setPoints(playerId: number, points: number) {
        this.points.set(playerId, points);
        this.movePoints();
    }*/
    
    /*public setSelectableRoutes(selectable: boolean, possibleRoutes: Route[]) {
        if (selectable) {
            possibleRoutes.forEach(route => ROUTES.find(r => r.id == route.id).spaces.forEach((_, index) => 
                document.getElementById(`route${route.id}-space${index}`)?.classList.add('selectable'))
            );
        } else {            
            dojo.query('.route').removeClass('selectable');
        }
    }*/

    /*private getPointsCoordinates(points: number) {
        const top = points < 86 ? Math.min(Math.max(points - 34, 0), 17) * POINT_CASE_SIZE : (102 - points) * POINT_CASE_SIZE;
        const left = points < 52 ? Math.min(points, 34) * POINT_CASE_SIZE : (33 - Math.max(points - 52, 0))*POINT_CASE_SIZE;

        return [17 + left, 15 + top];
    }

    private movePoints() {
        this.points.forEach((points, playerId) => {
            const markerDiv = document.getElementById(`player-${playerId}-point-marker`);

            const coordinates = this.getPointsCoordinates(points);
            const left = coordinates[0];
            const top = coordinates[1];
    
            let topShift = 0;
            let leftShift = 0;
            this.points.forEach((iPoints, iPlayerId) => {
                if (iPoints === points && iPlayerId < playerId) {
                    topShift += 5;
                    leftShift += 5;
                }
            });
    
            markerDiv.style.transform = `translateX(${left + leftShift}px) translateY(${top + topShift}px)`;
        });
    }*/

    public setClaimedRoutes(claimedRoutes: ClaimedRoute[]) {
        /*const claimedRoute = {
            playerId: 2343492
        };
        ROUTES.forEach(route => {*/
        claimedRoutes.forEach(claimedRoute => {
            const route = ROUTES.find(r => r.id == claimedRoute.routeId);
            const color = this.players.find(player => Number(player.id) == claimedRoute.playerId).color;
            route.spaces.forEach((space, spaceIndex) => {
                const spaceDiv = document.getElementById(`route${route.id}-space${spaceIndex}`);
                spaceDiv.parentElement.removeChild(spaceDiv);

                let angle = -space.angle;
                while (angle < 0) {
                    angle += 180;
                }
                while (angle >= 180) {
                    angle -= 180;
                }
                dojo.place(`<div class="wagon angle${Math.round(angle * 36 / 180)}" data-player-color="${color}" style="transform: translate(${space.x*FACTOR}px, ${space.y*FACTOR}px)"></div>`, 'map');
            });
        });
    }

    public setAutoZoom() {
        const mapAndDeckWidth = this.mapDiv.clientWidth + document.getElementById('train-car-deck').clientWidth;

        if (!mapAndDeckWidth) {
            setTimeout(() => this.setAutoZoom(), 200);
            return;
        }

        this.scale = Math.min(1, document.getElementById('game_play_area').clientWidth / mapAndDeckWidth);

        this.resizedDiv.style.transform = this.scale === 1 ? '' : `scale(${this.scale})`;
        this.resizedDiv.style.marginRight = `-${(1 - this.scale) * 100}%`;
        this.resizedDiv.style.marginBottom = `-${(1 - this.scale) * 100}%`;
        document.getElementById('map-zoom-wrapper').style.height = `${this.resizedDiv.clientHeight * this.scale}px`;
        //this.resizedDiv.style.height = this.scale === 1 ? '' : `${this.resizedDiv.clientHeight * this.scale}px`;
    }

    private mouseDownHandler(e: MouseEvent) {
        if (!this.zoomed) {
            return;
        }
        //this.mapDiv.style.cursor = 'grabbing';

        this.pos = {
            dragging: true,
            left: this.mapDiv.scrollLeft,
            top: this.mapDiv.scrollTop,
            // Get the current mouse position
            x: e.clientX,
            y: e.clientY,
        };
    }

    private mouseMoveHandler(e: MouseEvent) {
        if (!this.zoomed || !this.pos.dragging) {
            return;
        }

        // How far the mouse has been moved
        const dx = e.clientX - this.pos.x;
        const dy = e.clientY - this.pos.y;

        const factor = 0.1;

        // Scroll the element
        this.mapZoomDiv.scrollTop -= dy*factor;
        this.mapZoomDiv.scrollLeft -= dx*factor;
    }

    private mouseUpHandler() {
        if (!this.zoomed || !this.pos.dragging) {
            return;
        }
        
        //this.mapDiv.style.cursor = 'grab';
        this.pos.dragging = false;
    }

    private toggleZoom() {      
        this.zoomed = !this.zoomed;
        this.mapDiv.style.transform = this.zoomed ? `scale(1.8)` : '';
        dojo.toggleClass('zoom-button', 'zoomed', this.zoomed);
        dojo.toggleClass('map-zoom', 'scrollable', this.zoomed);

        if (!this.zoomed) {
            this.mapZoomDiv.scrollTop = 0;
            this.mapZoomDiv.scrollLeft = 0;
        }
    }

    public setActiveDestination(destination: Destination, previousDestination: Destination = null) {
        if (previousDestination) {
            if (previousDestination.id === destination.id) {
                return;
            }

            [previousDestination.from, previousDestination.to].forEach(city => 
                document.getElementById(`city${city}`).dataset.selectedDestination = 'false'
            );
        }

        if (destination) {
            [destination.from, destination.to].forEach(city => 
                document.getElementById(`city${city}`).dataset.selectedDestination = 'true'
            );
        }
    }

    public setHoveredRoute(route: Route | null, valid: boolean | null = null) {
        if (route) {
            [route.from, route.to].forEach(city => {
                const cityDiv = document.getElementById(`city${city}`);
                cityDiv.dataset.hovered = 'true';
                cityDiv.dataset.valid = valid.toString();
            });
        } else {
            ROUTES.forEach(r => [r.from, r.to].forEach(city => 
                document.getElementById(`city${city}`).dataset.hovered = 'false'
                // document.querySelectorAll(`.space[data-route="${route.id}"]`).forEach(spaceDiv => spaceDiv.classList.add('drag-over'));
            ));
        }
    }

    public setSelectableDestination(destination: Destination, visible: boolean): void {
        [destination.from, destination.to].forEach(city => {
            document.getElementById(`city${city}`).dataset.selectable = ''+visible;
        });
    }
    public setSelectedDestination(destination: Destination, visible: boolean): void {
        [destination.from, destination.to].forEach(city => {
            document.getElementById(`city${city}`).dataset.selected = ''+visible;
        });
    }

    public setHighligthedDestination(destination: Destination | null): void {
        const visible = Boolean(destination).toString();
        const shadow = document.getElementById('map-destination-highlight-shadow');
        shadow.dataset.visible = visible;

        let cities;
        if (destination) {
            shadow.dataset.from = ''+destination.from;
            shadow.dataset.to = ''+destination.to;
            cities = [destination.from, destination.to];
        } else {
            cities = [shadow.dataset.from, shadow.dataset.to];
        }
        cities.forEach(city => {
            document.getElementById(`city${city}`).dataset.highlight = visible;
        });
    }
}