/*declare const define;
declare const ebg;
declare const $;
declare const dojo: Dojo;
declare const _;
declare const g_gamethemeurl;

declare const board: HTMLDivElement;*/
var CARD_WIDTH = 250;
var CARD_HEIGHT = 161;
function setupTrainCarCards(stock) {
    var trainCarsUrl = g_gamethemeurl + "img/train-cards.jpg";
    for (var type = 0; type <= 8; type++) {
        stock.addItemType(type, type, trainCarsUrl, type);
    }
}
function setupDestinationCards(stock) {
    var destinationsUrl = g_gamethemeurl + "img/destinations.jpg";
    for (var id = 1; id <= 36; id++) {
        stock.addItemType(id, id, destinationsUrl, id - 1);
    }
}
var GRAY = 0;
var PINK = 1;
var WHITE = 2;
var BLUE = 3;
var YELLOW = 4;
var ORANGE = 5;
var BLACK = 6;
var RED = 7;
var GREEN = 8;
// TODO TEMP
var COLORS = [
    'GRAY',
    'PINK',
    'WHITE',
    'BLUE',
    'YELLOW',
    'ORANGE',
    'BLACK',
    'RED',
    'GREEN',
];
function setupTrainCarCardDiv(cardDiv, cardTypeId) {
    cardDiv.title = Number(cardTypeId) == 0 ? 'Locomotive' : COLORS[Number(cardTypeId)];
}
var DestinationCard = /** @class */ (function () {
    function DestinationCard(id, from, to, points) {
        this.id = id;
        this.from = from;
        this.to = to;
        this.points = points;
    }
    return DestinationCard;
}());
var CITIES = [
    null,
    'Atlanta',
    'Boston',
    'Calgary',
    'Charleston',
    'Chicago',
    'Dallas',
    'Denver',
    'Duluth',
    'El Paso',
    'Helena',
    'Houston',
    'Kansas City',
    'Las Vegas',
    'Little Rock',
    'Los Angeles',
    'Miami',
    'Montréal',
    'Nashville',
    'New Orleans',
    'New York',
    'Oklahoma City',
    'Omaha',
    'Phoenix',
    'Pittsburgh',
    'Portland',
    'Raleigh',
    'Saint Louis',
    'Salt Lake City',
    'Sault St. Marie',
    'San Francisco',
    'Santa Fe',
    'Seattle',
    'Toronto',
    'Vancouver',
    'Washington',
    'Winnipeg',
];
var DESTINATIONS = [
    new DestinationCard(1, 2, 16, 12),
    new DestinationCard(2, 3, 23, 13),
    new DestinationCard(3, 3, 28, 7),
    new DestinationCard(4, 5, 19, 7),
    new DestinationCard(5, 5, 31, 9),
    new DestinationCard(6, 6, 20, 11),
    new DestinationCard(7, 7, 9, 4),
    new DestinationCard(8, 7, 24, 11),
    new DestinationCard(9, 8, 9, 10),
    new DestinationCard(10, 8, 11, 8),
    new DestinationCard(11, 10, 15, 8),
    new DestinationCard(12, 12, 11, 5),
    new DestinationCard(13, 15, 5, 16),
    new DestinationCard(14, 15, 16, 20),
    new DestinationCard(15, 15, 20, 21),
    new DestinationCard(16, 17, 1, 9),
    new DestinationCard(17, 17, 19, 13),
    new DestinationCard(18, 20, 1, 6),
    new DestinationCard(19, 25, 18, 17),
    new DestinationCard(20, 25, 23, 11),
    new DestinationCard(21, 30, 1, 17),
    new DestinationCard(22, 29, 18, 8),
    new DestinationCard(23, 29, 21, 9),
    new DestinationCard(24, 32, 15, 9),
    new DestinationCard(25, 32, 20, 22),
    new DestinationCard(26, 33, 16, 10),
    new DestinationCard(27, 34, 17, 20),
    new DestinationCard(28, 34, 31, 13),
    new DestinationCard(29, 36, 11, 12),
    new DestinationCard(30, 36, 14, 11), // Winnipeg	Little Rock	11
];
function setupDestinationCardDiv(cardDiv, cardTypeId) {
    //const destination = DESTINATIONS.find(d => d.id == Number(cardTypeId));
    //cardDiv.innerHTML = `<span><strong>${CITIES[destination.from]}</strong> to <strong>${CITIES[destination.to]}</strong> (<strong>${destination.points}</strong>)</span>`;
}
var POINT_CASE_SIZE = 25.5;
var BOARD_POINTS_MARGIN = 38;
var SIDES = ['left', 'right', 'top', 'bottom'];
var CORNERS = ['bottom-left', 'bottom-right', 'top-left', 'top-right'];
var RouteSpace = /** @class */ (function () {
    function RouteSpace(x, y, angle) {
        this.x = x;
        this.y = y;
        this.angle = angle;
    }
    return RouteSpace;
}());
var Route = /** @class */ (function () {
    function Route(id, from, to, spaces, color) {
        this.id = id;
        this.from = from;
        this.to = to;
        this.spaces = spaces;
        this.color = color;
    }
    return Route;
}());
var ROUTES = [
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
var TtrMap = /** @class */ (function () {
    //private points = new Map<number, number>();
    function TtrMap(game, players, claimedRoutes) {
        var _this = this;
        this.game = game;
        this.players = players;
        // map border
        dojo.place("<div class=\"illustration\"></div>", 'board');
        SIDES.forEach(function (side) { return dojo.place("<div class=\"side " + side + "\"></div>", 'board'); });
        CORNERS.forEach(function (corner) { return dojo.place("<div class=\"corner " + corner + "\"></div>", 'board'); });
        /*let html = '';

        // points
        players.forEach(player => {
            html += `<div id="player-${player.id}-point-marker" class="point-marker ${this.game.isColorBlindMode() ? 'color-blind' : ''}" data-player-no="${player.playerNo}" style="background: #${player.color};"></div>`;
            this.points.set(Number(player.id), Number(player.score));
        });
        dojo.place(html, 'board');*/
        ROUTES.forEach(function (route) {
            return route.spaces.forEach(function (space, spaceIndex) {
                dojo.place("<div id=\"route" + route.id + "-space" + spaceIndex + "\" class=\"route space\" \n                    style=\"transform: translate(" + space.x + "px, " + space.y + "px) rotate(" + space.angle + "deg)\"\n                    title=\"" + CITIES[route.from] + " to " + CITIES[route.to] + ", " + route.spaces.length + " " + COLORS[route.color] + "\"\n                ></div>", 'board');
                document.getElementById("route" + route.id + "-space" + spaceIndex).addEventListener('click', function () { return _this.game.claimRoute(route.id); });
            });
        });
        /*console.log(ROUTES.map(route => `    new Route(${route.id}, ${route.from}, ${route.to}, [
${route.spaces.map(space => `        new RouteSpace(${(space.x*0.986 + 10).toFixed(2)}, ${(space.y*0.986 + 10).toFixed(2)}, ${space.angle}),`).join('\n')}
    ], ${COLORS[route.color]}),`).join('\n'));*/
        //this.movePoints();
        this.setClaimedRoutes(claimedRoutes);
    }
    /*public setPoints(playerId: number, points: number) {
        this.points.set(playerId, points);
        this.movePoints();
    }*/
    TtrMap.prototype.setSelectableRoutes = function (selectable, possibleRoutes) {
        if (selectable) {
            possibleRoutes.forEach(function (route) { return ROUTES.find(function (r) { return r.id == route.id; }).spaces.forEach(function (_, index) { var _a; return (_a = document.getElementById("route" + route.id + "-space" + index)) === null || _a === void 0 ? void 0 : _a.classList.add('selectable'); }); });
        }
        else {
            dojo.query('.route').removeClass('selectable');
        }
    };
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
    TtrMap.prototype.setClaimedRoutes = function (claimedRoutes) {
        var _this = this;
        claimedRoutes.forEach(function (claimedRoute) {
            var route = ROUTES.find(function (r) { return r.id == claimedRoute.routeId; });
            var color = "#" + _this.players.find(function (player) { return Number(player.id) == claimedRoute.playerId; }).color;
            if (typeof route.spaces === 'number') {
                document.getElementById("route" + claimedRoute.routeId).style.borderColor = color;
            }
            else {
                route.spaces.forEach(function (_, spaceIndex) {
                    var spaceDiv = document.getElementById("route" + route.id + "-space" + spaceIndex);
                    spaceDiv.style.borderColor = color;
                    spaceDiv.style.background = color;
                });
            }
        });
    };
    return TtrMap;
}());
var DestinationSelection = /** @class */ (function () {
    function DestinationSelection(game) {
        var _this = this;
        this.game = game;
        this.destinations = new ebg.stock();
        this.destinations.setSelectionAppearance('class');
        this.destinations.selectionClass = 'selected';
        this.destinations.setSelectionMode(2);
        this.destinations.create(game, $("destination-stock"), CARD_WIDTH, CARD_HEIGHT);
        this.destinations.onItemCreate = function (cardDiv, cardTypeId) { return setupDestinationCardDiv(cardDiv, cardTypeId); };
        this.destinations.image_items_per_row = 10;
        this.destinations.centerItems = true;
        dojo.connect(this.destinations, 'onChangeSelection', this, function () {
            if (document.getElementById('chooseInitialDestinations_button')) {
                dojo.toggleClass('chooseInitialDestinations_button', 'disabled', _this.destinations.getSelectedItems().length < _this.minimumDestinations);
            }
            if (document.getElementById('chooseAdditionalDestinations_button')) {
                dojo.toggleClass('chooseAdditionalDestinations_button', 'disabled', _this.destinations.getSelectedItems().length < _this.minimumDestinations);
            }
        });
        setupDestinationCards(this.destinations);
    }
    DestinationSelection.prototype.setCards = function (destinations, minimumDestinations) {
        var _this = this;
        dojo.removeClass('destination-deck', 'hidden');
        destinations.forEach(function (destination) { return _this.destinations.addToStockWithId(destination.type_arg, '' + destination.id); });
        this.minimumDestinations = minimumDestinations;
    };
    DestinationSelection.prototype.hide = function () {
        this.destinations.removeAll();
        dojo.addClass('destination-deck', 'hidden');
    };
    DestinationSelection.prototype.getSelectedDestinationsIds = function () {
        return this.destinations.getSelectedItems().map(function (item) { return Number(item.id); });
    };
    return DestinationSelection;
}());
var TrainCarSelection = /** @class */ (function () {
    function TrainCarSelection(game, visibleCards) {
        var _this = this;
        this.game = game;
        this.visibleCardsStocks = [];
        document.getElementById('train-car-deck-hidden-pile1').addEventListener('click', function () { return _this.game.onHiddenTrainCarDeckClick(1); });
        document.getElementById('train-car-deck-hidden-pile2').addEventListener('click', function () { return _this.game.onHiddenTrainCarDeckClick(2); });
        var _loop_1 = function (i) {
            this_1.visibleCardsStocks[i] = new ebg.stock();
            this_1.visibleCardsStocks[i].setSelectionAppearance('class');
            this_1.visibleCardsStocks[i].selectionClass = 'no-class-selection';
            this_1.visibleCardsStocks[i].setSelectionMode(1);
            this_1.visibleCardsStocks[i].create(game, $("visible-train-cards-stock" + i), CARD_WIDTH, CARD_HEIGHT);
            this_1.visibleCardsStocks[i].onItemCreate = function (cardDiv, cardTypeId) { return setupTrainCarCardDiv(cardDiv, cardTypeId); };
            //this.visibleCardsStock.image_items_per_row = 9;
            this_1.visibleCardsStocks[i].centerItems = true;
            dojo.connect(this_1.visibleCardsStocks[i], 'onChangeSelection', this_1, function (_, itemId) { return _this.game.onVisibleTrainCarCardClick(Number(itemId), _this.visibleCardsStocks[i]); });
            setupTrainCarCards(this_1.visibleCardsStocks[i]);
        };
        var this_1 = this;
        for (var i = 1; i <= 5; i++) {
            _loop_1(i);
        }
        this.setNewCardsOnTable(visibleCards);
    }
    TrainCarSelection.prototype.setSelectableTopDeck = function (selectable, number) {
        if (number === void 0) { number = 0; }
        dojo.toggleClass('train-car-deck-hidden-pile', 'selectable', selectable);
        dojo.toggleClass('train-car-deck-hidden-pile1', 'hidden', number < 1);
        dojo.toggleClass('train-car-deck-hidden-pile2', 'hidden', number < 2);
    };
    TrainCarSelection.prototype.setSelectableVisibleCards = function (availableVisibleCards) {
        var _loop_2 = function (i) {
            var stock = this_2.visibleCardsStocks[i];
            stock.items.forEach(function (item) {
                var _a;
                var itemId = Number(item.id);
                if (!availableVisibleCards.some(function (card) { return card.id == itemId; })) {
                    (_a = document.getElementById(stock.container_div.id + "_item_" + itemId)) === null || _a === void 0 ? void 0 : _a.classList.add('disabled');
                }
            });
        };
        var this_2 = this;
        for (var i = 1; i <= 5; i++) {
            _loop_2(i);
        }
    };
    TrainCarSelection.prototype.removeSelectableVisibleCards = function () {
        var _loop_3 = function (i) {
            var stock = this_3.visibleCardsStocks[i];
            stock.items.forEach(function (item) { var _a; return (_a = document.getElementById(stock.container_div.id + "_item_" + item.id)) === null || _a === void 0 ? void 0 : _a.classList.remove('disabled'); });
        };
        var this_3 = this;
        for (var i = 1; i <= 5; i++) {
            _loop_3(i);
        }
    };
    TrainCarSelection.prototype.setNewCardsOnTable = function (cards) {
        var _this = this;
        cards.forEach(function (card) {
            var spot = card.location_arg;
            _this.visibleCardsStocks[spot].removeAll();
            _this.visibleCardsStocks[spot].addToStockWithId(card.type, '' + card.id);
        });
    };
    return TrainCarSelection;
}());
var PlayerTable = /** @class */ (function () {
    function PlayerTable(game, player, trainCars, destinations, completedDestinations) {
        var _this = this;
        this.game = game;
        this.playerId = Number(player.id);
        var html = "\n        <div id=\"player-table-" + this.playerId + "\" class=\"player-table whiteblock\">\n            <div id=\"player-table-" + this.playerId + "-train-cars\" class=\"player-table-train-cars\"></div>\n            <div id=\"player-table-" + this.playerId + "-destinations\" class=\"player-table-destinations\"></div>\n        </div>";
        dojo.place(html, 'player-hand');
        // train cars cards        
        this.trainCarStock = new ebg.stock();
        this.trainCarStock.setSelectionAppearance('class');
        this.trainCarStock.selectionClass = 'selected';
        this.trainCarStock.create(this.game, $("player-table-" + this.playerId + "-train-cars"), CARD_WIDTH, CARD_HEIGHT);
        this.trainCarStock.setSelectionMode(0);
        this.trainCarStock.onItemCreate = function (cardDiv, cardTypeId) { return setupTrainCarCardDiv(cardDiv, cardTypeId); };
        dojo.connect(this.trainCarStock, 'onChangeSelection', this, function (_, itemId) {
            if (_this.trainCarStock.getSelectedItems().length) {
                //this.game.cardClick(0, Number(itemId));
            }
            _this.trainCarStock.unselectAll();
        });
        setupTrainCarCards(this.trainCarStock);
        this.addTrainCars(trainCars);
        // destination cards
        this.destinationStock = new ebg.stock();
        this.destinationStock.setSelectionAppearance('class');
        this.destinationStock.selectionClass = 'selected';
        this.destinationStock.create(this.game, $("player-table-" + this.playerId + "-destinations"), CARD_WIDTH, CARD_HEIGHT);
        this.destinationStock.setSelectionMode(0);
        this.destinationStock.image_items_per_row = 10;
        this.destinationStock.onItemCreate = function (cardDiv, type) { return setupDestinationCardDiv(cardDiv, type); };
        setupDestinationCards(this.destinationStock);
        this.addDestinations(destinations);
        destinations.filter(function (destination) { return completedDestinations.some(function (d) { return d.id == destination.id; }); }).forEach(function (destination) { return _this.markDestinationComplete(destination); });
    }
    PlayerTable.prototype.addDestinations = function (destinations, originStock) {
        var _this = this;
        destinations.forEach(function (destination) {
            var _a;
            var from = ((_a = document.getElementById((originStock ? originStock.container_div.id : 'destination-stock') + "_item_" + destination.id)) === null || _a === void 0 ? void 0 : _a.id) || 'destination-stock';
            _this.destinationStock.addToStockWithId(destination.type_arg, '' + destination.id, from);
        });
        originStock === null || originStock === void 0 ? void 0 : originStock.removeAll();
    };
    PlayerTable.prototype.markDestinationComplete = function (destination) {
        document.getElementById(this.destinationStock.container_div.id + "_item_" + destination.id).classList.add('done');
    };
    PlayerTable.prototype.addTrainCars = function (trainCars, stocks) {
        var _this = this;
        trainCars.forEach(function (trainCar) {
            var _a;
            var stock = stocks ? stocks.visibleCardsStocks[trainCar.location_arg] : null;
            var from = ((_a = document.getElementById((stock ? stock.container_div.id : 'train-car-deck') + "_item_" + trainCar.id)) === null || _a === void 0 ? void 0 : _a.id) || 'train-car-deck';
            _this.trainCarStock.addToStockWithId(trainCar.type, '' + trainCar.id, from);
            stock === null || stock === void 0 ? void 0 : stock.removeAll();
        });
    };
    return PlayerTable;
}());
var ANIMATION_MS = 500;
var isDebug = window.location.host == 'studio.boardgamearena.com';
var log = isDebug ? console.log.bind(window.console) : function () { };
var TicketToRide = /** @class */ (function () {
    function TicketToRide() {
        this.playerTable = null;
        this.trainCarCounters = [];
        this.trainCarCardCounters = [];
        this.destinationCardCounters = [];
    }
    /*
        setup:

        This method must set up the game user interface according to current game situation specified
        in parameters.

        The method is called each time the game interface is displayed to a player, ie:
        _ when the game starts
        _ when a player refreshes the game page (F5)

        "gamedatas" argument contains all datas retrieved by your "getAllDatas" PHP method.
    */
    TicketToRide.prototype.setup = function (gamedatas) {
        // ignore loading of some pictures
        this.dontPreloadImage('publisher.png');
        log("Starting game setup");
        this.gamedatas = gamedatas;
        log('gamedatas', gamedatas);
        this.map = new TtrMap(this, Object.values(gamedatas.players), gamedatas.claimedRoutes);
        this.trainCarSelection = new TrainCarSelection(this, gamedatas.visibleTrainCards);
        this.destinationSelection = new DestinationSelection(this);
        var player = gamedatas.players[this.getPlayerId()];
        if (player) {
            this.playerTable = new PlayerTable(this, player, gamedatas.handTrainCars, gamedatas.handDestinations, gamedatas.completedDestinations);
        }
        this.createPlayerPanels(gamedatas);
        if (gamedatas.lastTurn) {
            this.notif_lastTurn();
        }
        if (Number(gamedatas.gamestate.id) >= 98) { // score or end
            this.onEnteringEndScore(true);
        }
        this.setupNotifications();
        log("Ending game setup");
    };
    ///////////////////////////////////////////////////
    //// Game & client states
    // onEnteringState: this method is called each time we are entering into a new game state.
    //                  You can use this method to perform some user interface changes at this moment.
    //
    TicketToRide.prototype.onEnteringState = function (stateName, args) {
        log('Entering state: ' + stateName, args.args);
        switch (stateName) {
            case 'chooseAction':
                this.onEnteringChooseAction(args.args);
                break;
            case 'drawSecondCard':
                this.onEnteringDrawSecondCard(args.args);
                break;
            case 'endScore':
                this.onEnteringEndScore();
                break;
        }
    };
    TicketToRide.prototype.onEnteringChooseAction = function (args) {
        this.trainCarSelection.setSelectableTopDeck(this.isCurrentPlayerActive(), args.maxHiddenCardsPick);
        this.map.setSelectableRoutes(this.isCurrentPlayerActive(), args.possibleRoutes);
    };
    TicketToRide.prototype.onEnteringDrawSecondCard = function (args) {
        this.trainCarSelection.setSelectableTopDeck(this.isCurrentPlayerActive(), args.maxHiddenCardsPick);
        this.trainCarSelection.setSelectableVisibleCards(args.availableVisibleCards);
    };
    TicketToRide.prototype.onEnteringEndScore = function (fromReload) {
        if (fromReload === void 0) { fromReload = false; }
        var lastTurnBar = document.getElementById('last-round');
        if (lastTurnBar) {
            lastTurnBar.style.display = 'none';
        }
    };
    // onLeavingState: this method is called each time we are leaving a game state.
    //                 You can use this method to perform some user interface changes at this moment.
    //
    TicketToRide.prototype.onLeavingState = function (stateName) {
        log('Leaving state: ' + stateName);
        switch (stateName) {
            case 'chooseInitialDestinations':
            case 'chooseAdditionalDestinations':
                this.destinationSelection.hide();
                break;
            case 'chooseAction':
                this.map.setSelectableRoutes(false, []);
                break;
            case 'drawSecondCard':
                this.trainCarSelection.removeSelectableVisibleCards();
                break;
        }
    };
    // onUpdateActionButtons: in this method you can manage "action buttons" that are displayed in the
    //                        action status bar (ie: the HTML links in the status bar).
    //
    TicketToRide.prototype.onUpdateActionButtons = function (stateName, args) {
        var _this = this;
        if (this.isCurrentPlayerActive()) {
            switch (stateName) {
                case 'chooseInitialDestinations':
                    var chooseInitialDestinationsArgs = args;
                    this.addActionButton('chooseInitialDestinations_button', _("Keep selected destinations"), function () { return _this.chooseInitialDestinations(); });
                    dojo.addClass('chooseInitialDestinations_button', 'disabled');
                    this.destinationSelection.setCards(chooseInitialDestinationsArgs._private.destinations, chooseInitialDestinationsArgs.minimum);
                    break;
                case 'chooseAction':
                    var chooseActionArgs = args;
                    this.addActionButton('drawDestinations_button', dojo.string.substitute(_("Draw ${number} destination tickets"), { number: chooseActionArgs.maxDestinationsPick }), function () { return _this.drawDestinations(); }, null, null, 'red');
                    dojo.toggleClass('drawDestinations_button', 'disabled', !chooseActionArgs.maxDestinationsPick);
                    break;
                case 'chooseAdditionalDestinations':
                    var chooseAdditionalDestinationsArgs = args;
                    this.addActionButton('chooseAdditionalDestinations_button', _("Keep selected destinations"), function () { return _this.chooseAdditionalDestinations(); });
                    dojo.addClass('chooseAdditionalDestinations_button', 'disabled');
                    this.destinationSelection.setCards(chooseAdditionalDestinationsArgs._private.destinations, chooseAdditionalDestinationsArgs.minimum);
                    break;
            }
        }
    };
    ///////////////////////////////////////////////////
    //// Utility methods
    ///////////////////////////////////////////////////
    TicketToRide.prototype.getPlayerId = function () {
        return Number(this.player_id);
    };
    TicketToRide.prototype.isColorBlindMode = function () {
        return this.prefs[201].value == 1;
    };
    TicketToRide.prototype.createPlayerPanels = function (gamedatas) {
        var _this = this;
        Object.values(gamedatas.players).forEach(function (player) {
            var playerId = Number(player.id);
            // public counters
            dojo.place("<div class=\"counters\">\n                <div class=\"counter train-car-counter\">\n                    <div class=\"icon train-car\"></div> \n                    <span id=\"train-car-counter-" + player.id + "\"></span>\n                </div>\n                <div class=\"counter train-car-card-counter\">\n                    <div class=\"icon train-car-card\"></div> \n                    <span id=\"train-car-card-counter-" + player.id + "\"></span>\n                </div>\n                <div class=\"counter destinations-counter\">\n                    <div class=\"icon destination-card\"></div> \n                    <span id=\"completed-destinations-counter-" + player.id + "\">" + (_this.getPlayerId() !== playerId ? '?' : '') + "</span>&nbsp;/&nbsp;<span id=\"destination-card-counter-" + player.id + "\"></span>\n                </div>\n            </div>", "player_board_" + player.id);
            var trainCarCounter = new ebg.counter();
            trainCarCounter.create("train-car-counter-" + player.id);
            trainCarCounter.setValue(player.remainingTrainCarsCount);
            _this.trainCarCounters[playerId] = trainCarCounter;
            var trainCarCardCounter = new ebg.counter();
            trainCarCardCounter.create("train-car-card-counter-" + player.id);
            trainCarCardCounter.setValue(player.trainCarsCount);
            _this.trainCarCardCounters[playerId] = trainCarCardCounter;
            var destinationCardCounter = new ebg.counter();
            destinationCardCounter.create("destination-card-counter-" + player.id);
            destinationCardCounter.setValue(player.destinationsCount);
            _this.destinationCardCounters[playerId] = destinationCardCounter;
            // private counters
            if (_this.getPlayerId() === playerId) {
                _this.completedDestinationsCounter = new ebg.counter();
                _this.completedDestinationsCounter.create("completed-destinations-counter-" + player.id);
                _this.completedDestinationsCounter.setValue(gamedatas.completedDestinations.length);
            }
            if (_this.isColorBlindMode()) {
                dojo.place("\n                <div class=\"point-marker color-blind meeple-player-" + player.id + "\" data-player-no=\"" + player.playerNo + "\" style=\"background-color: #" + player.color + ";\"></div>\n                ", "player_board_" + player.id);
            }
        });
        this.addTooltipHtmlToClass('train-car-counter', _("Remaining train cars"));
        this.addTooltipHtmlToClass('train-car-card-counter', _("Train cars cards"));
        this.addTooltipHtmlToClass('destinations-counter', _("Completed / Total destination cards"));
    };
    TicketToRide.prototype.setPoints = function (playerId, points) {
        var _a;
        (_a = this.scoreCtrl[playerId]) === null || _a === void 0 ? void 0 : _a.toValue(points);
        //this.map.setPoints(playerId, points);
    };
    TicketToRide.prototype.chooseInitialDestinations = function () {
        if (!this.checkAction('chooseInitialDestinations')) {
            return;
        }
        var destinationsIds = this.destinationSelection.getSelectedDestinationsIds();
        this.takeAction('chooseInitialDestinations', {
            destinationsIds: destinationsIds.join(',')
        });
    };
    TicketToRide.prototype.drawDestinations = function () {
        if (!this.checkAction('drawDestinations')) {
            return;
        }
        this.takeAction('drawDestinations');
    };
    TicketToRide.prototype.chooseAdditionalDestinations = function () {
        if (!this.checkAction('chooseAdditionalDestinations')) {
            return;
        }
        var destinationsIds = this.destinationSelection.getSelectedDestinationsIds();
        this.takeAction('chooseAdditionalDestinations', {
            destinationsIds: destinationsIds.join(',')
        });
    };
    TicketToRide.prototype.onHiddenTrainCarDeckClick = function (number) {
        var action = this.gamedatas.gamestate.name === 'drawSecondCard' ? 'drawSecondDeckCard' : 'drawDeckCards';
        if (!this.checkAction(action)) {
            return;
        }
        this.takeAction(action, {
            number: number
        });
    };
    TicketToRide.prototype.onVisibleTrainCarCardClick = function (id, stock) {
        if (dojo.hasClass(stock.container_div.id + "_item_" + id, 'disabled')) {
            stock.unselectItem('' + id);
            return;
        }
        var action = this.gamedatas.gamestate.name === 'drawSecondCard' ? 'drawSecondTableCard' : 'drawTableCard';
        if (!this.checkAction(action)) {
            return;
        }
        this.takeAction(action, {
            id: id
        });
    };
    TicketToRide.prototype.claimRoute = function (routeId) {
        if (!this.checkAction('claimRoute')) {
            return;
        }
        this.takeAction('claimRoute', {
            routeId: routeId
        });
    };
    TicketToRide.prototype.takeAction = function (action, data) {
        data = data || {};
        data.lock = true;
        this.ajaxcall("/tickettoride/tickettoride/" + action + ".html", data, this, function () { });
    };
    ///////////////////////////////////////////////////
    //// Reaction to cometD notifications
    /*
        setupNotifications:

        In this method, you associate each of your game notifications with your local method to handle it.

        Note: game notification names correspond to "notifyAllPlayers" and "notifyPlayer" calls in
                your azul.game.php file.

    */
    TicketToRide.prototype.setupNotifications = function () {
        //log( 'notifications subscriptions setup' );
        var _this = this;
        var notifs = [
            ['newCardsOnTable', ANIMATION_MS],
            ['claimedRoute', ANIMATION_MS],
            ['destinationCompleted', ANIMATION_MS],
            ['points', 1],
            ['destinationsPicked', 1],
            ['trainCarPicked', 1],
            ['lastTurn', 1],
        ];
        notifs.forEach(function (notif) {
            dojo.subscribe(notif[0], _this, "notif_" + notif[0]);
            _this.notifqueue.setSynchronous(notif[0], notif[1]);
        });
    };
    TicketToRide.prototype.notif_points = function (notif) {
        this.setPoints(notif.args.playerId, notif.args.points);
    };
    TicketToRide.prototype.notif_destinationsPicked = function (notif) {
        var _a, _b;
        console.log('notif_destinationsPicked', notif.args);
        this.destinationCardCounters[notif.args.playerId].incValue(notif.args.number);
        var destinations = (_b = (_a = notif.args._private) === null || _a === void 0 ? void 0 : _a[this.getPlayerId()]) === null || _b === void 0 ? void 0 : _b.destinations;
        if (destinations) {
            this.playerTable.addDestinations(destinations, this.destinationSelection.destinations);
        }
        else {
            // TODO notif to player board ?
        }
    };
    TicketToRide.prototype.notif_trainCarPicked = function (notif) {
        var _a, _b;
        this.trainCarCardCounters[notif.args.playerId].incValue(notif.args.number);
        console.log('notif_trainCarPicked', notif.args);
        var cards = (_b = (_a = notif.args._private) === null || _a === void 0 ? void 0 : _a[this.getPlayerId()]) === null || _b === void 0 ? void 0 : _b.cards;
        if (cards) {
            this.playerTable.addTrainCars(cards, this.trainCarSelection);
        }
        else {
            // TODO notif to player board ?
        }
    };
    TicketToRide.prototype.notif_newCardsOnTable = function (notif) {
        this.trainCarSelection.setNewCardsOnTable(notif.args.cards);
    };
    TicketToRide.prototype.notif_claimedRoute = function (notif) {
        var playerId = notif.args.playerId;
        var route = notif.args.route;
        this.trainCarCardCounters[playerId].incValue(-route.number);
        this.trainCarCounters[playerId].incValue(-route.number);
        this.map.setClaimedRoutes([{
                playerId: playerId,
                routeId: route.id
            }]);
    };
    TicketToRide.prototype.notif_destinationCompleted = function (notif) {
        var destination = notif.args.destination;
        this.completedDestinationsCounter.incValue(1);
        this.gamedatas.completedDestinations.push(destination);
        this.playerTable.markDestinationComplete(destination);
    };
    TicketToRide.prototype.notif_lastTurn = function () {
        dojo.place("<div id=\"last-round\">\n            " + _("This is the final round!") + "\n        </div>", 'page-title');
    };
    /* This enable to inject translatable styled things to logs or action bar */
    /* @Override */
    TicketToRide.prototype.format_string_recursive = function (log, args) {
        try {
            if (log && args && !args.processed) {
                /*if (typeof args.lineNumber === 'number') {
                    args.lineNumber = `<strong>${args.line}</strong>`;
                }

                if (log.indexOf('${number} ${color}') !== -1 && typeof args.type === 'number') {

                    const number = args.number;
                    let html = '';
                    for (let i=0; i<number; i++) {
                        html += `<div class="tile tile${args.type}"></div>`;
                    }

                    log = _(log).replace('${number} ${color}', html);
                }*/
            }
        }
        catch (e) {
            console.error(log, args, "Exception thrown", e.stack);
        }
        return this.inherited(arguments);
    };
    return TicketToRide;
}());
define([
    "dojo", "dojo/_base/declare",
    "ebg/core/gamegui",
    "ebg/counter",
    "ebg/stock"
], function (dojo, declare) {
    return declare("bgagame.tickettoride", ebg.core.gamegui, new TicketToRide());
});
