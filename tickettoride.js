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
        new RouteSpace(1346, 689, 3),
        new RouteSpace(1409, 691, 3),
    ], GRAY),
    new Route(2, 1, 16, [
        new RouteSpace(1319, 722, 51),
        new RouteSpace(1359, 771, 51),
        new RouteSpace(1398, 820, 51),
        new RouteSpace(1437, 868, 51),
        new RouteSpace(1476, 916, 51),
    ], BLUE),
    new Route(3, 1, 18, [
        new RouteSpace(1243, 636, 35),
    ], GRAY),
    new Route(4, 1, 19, [
        new RouteSpace(1139, 843, 291),
        new RouteSpace(1167, 787, 303),
        new RouteSpace(1205, 737, 310),
        new RouteSpace(1249, 692, 319),
    ], YELLOW),
    new Route(5, 1, 19, [
        new RouteSpace(1155, 859, 291),
        new RouteSpace(1184, 803, 303),
        new RouteSpace(1222, 753, 310),
        new RouteSpace(1265, 709, 319),
    ], ORANGE),
    new Route(6, 1, 26, [
        new RouteSpace(1319, 632, -41),
        new RouteSpace(1366, 591, -41),
    ], GRAY),
    new Route(7, 1, 26, [
        new RouteSpace(1380, 607, -41),
        new RouteSpace(1333, 649, -41),
    ], GRAY),
    new Route(8, 2, 17, [
        new RouteSpace(1509, 84, 40),
        new RouteSpace(1557, 123, 40),
    ], GRAY),
    new Route(9, 2, 17, [
        new RouteSpace(1494, 100, 40),
        new RouteSpace(1542, 140, 40),
    ], GRAY),
    new Route(10, 2, 20, [
        new RouteSpace(1554, 201, 122),
        new RouteSpace(1521, 254, 122),
    ], YELLOW),
    new Route(11, 2, 20, [
        new RouteSpace(1572, 212, 122),
        new RouteSpace(1539, 265, 122),
    ], RED),
    new Route(12, 3, 10, 4, GRAY),
    new Route(12, 3, 32, 4, GRAY),
    new Route(13, 3, 34, 3, GRAY),
    new Route(14, 3, 36, 6, WHITE),
    new Route(15, 4, 16, [
        new RouteSpace(1458, 727, 87),
        new RouteSpace(1463, 790, 82),
        new RouteSpace(1478, 852, 73),
        new RouteSpace(1503, 909, 59),
    ], PINK),
    new Route(16, 4, 26, [
        new RouteSpace(1443, 598, 35),
        new RouteSpace(1471, 640, -65),
    ], GRAY),
    new Route(17, 5, 8, [
        new RouteSpace(944, 320, 28),
        new RouteSpace(1001, 346, 21),
        new RouteSpace(1062, 364, 14),
    ], RED),
    new Route(18, 5, 22, [
        new RouteSpace(897, 432, -34),
        new RouteSpace(947, 398, -34),
        new RouteSpace(1009, 383, 8),
        new RouteSpace(1068, 392, 8),
    ], BLUE),
    new Route(19, 5, 24, [
        new RouteSpace(1161, 363, -14),
        new RouteSpace(1223, 350, -9),
        new RouteSpace(1286, 347, 3),
    ], ORANGE),
    new Route(20, 5, 24, [
        new RouteSpace(1169, 385, -14),
        new RouteSpace(1231, 372, -9),
        new RouteSpace(1293, 369, 3),
    ], BLACK),
    new Route(21, 5, 27, [
        new RouteSpace(1046, 488, -57),
        new RouteSpace(1079, 437, -57),
    ], GREEN),
    new Route(22, 5, 27, [
        new RouteSpace(1065, 497, -57),
        new RouteSpace(1098, 446, -57),
    ], WHITE),
    new Route(23, 5, 33, [
        new RouteSpace(1128, 346, -48),
        new RouteSpace(1176, 305, -35),
        new RouteSpace(1233, 277, -17),
        new RouteSpace(1289, 248, -39),
    ], WHITE),
    new Route(24, 6, 9, [
        new RouteSpace(653, 885, 351),
        new RouteSpace(714, 875, 351),
        new RouteSpace(776, 866, 351),
        new RouteSpace(838, 857, 351),
    ], RED),
    new Route(25, 6, 11, [
        new RouteSpace(915, 882, 49),
    ], GRAY),
    new Route(26, 6, 11, [
        new RouteSpace(932, 868, 49),
    ], GRAY),
    new Route(27, 6, 14, [
        new RouteSpace(936, 786, -55),
        new RouteSpace(972, 735, -55),
    ], GRAY),
    new Route(28, 6, 21, [
        new RouteSpace(864, 735, -97),
        new RouteSpace(872, 797, -97),
    ], GRAY),
    new Route(29, 6, 21, [
        new RouteSpace(885, 733, -97),
        new RouteSpace(893, 795, -97),
    ], GRAY),
    new Route(30, 7, 10, [
        new RouteSpace(513, 344, 67),
        new RouteSpace(535, 401, 67),
        new RouteSpace(559, 458, 67),
        new RouteSpace(582, 517, 67),
    ], GREEN),
    new Route(31, 7, 12, [
        new RouteSpace(654, 570, 5),
        new RouteSpace(717, 571, -5),
        new RouteSpace(779, 562, -11),
        new RouteSpace(840, 543, -23),
    ], BLACK),
    new Route(32, 7, 12, [
        new RouteSpace(654, 593, 5),
        new RouteSpace(717, 594, -5),
        new RouteSpace(779, 585, -11),
        new RouteSpace(840, 566, -23),
    ], ORANGE),
    new Route(33, 7, 21, [
        new RouteSpace(625, 621, 224),
        new RouteSpace(678, 658, 205),
        new RouteSpace(737, 675, 189),
        new RouteSpace(800, 681, 184),
    ], RED),
    new Route(34, 7, 22, [
        new RouteSpace(631, 533, -38),
        new RouteSpace(684, 500, -26),
        new RouteSpace(742, 477, -16),
        new RouteSpace(804, 463, -12),
    ], PINK),
    new Route(35, 7, 23, [
        new RouteSpace(384, 775, -68),
        new RouteSpace(411, 721, -61),
        new RouteSpace(444, 667, -55),
        new RouteSpace(485, 621, -39),
        new RouteSpace(541, 589, -20),
    ], WHITE),
    new Route(36, 7, 28, [
        new RouteSpace(417, 511, 11),
        new RouteSpace(479, 523, 11),
        new RouteSpace(538, 535, 11),
    ], RED),
    new Route(37, 7, 28, [
        new RouteSpace(413, 532, 11),
        new RouteSpace(475, 544, 11),
        new RouteSpace(534, 556, 11),
    ], YELLOW),
    new Route(99, 7, 31, [
        new RouteSpace(584, 620, 273),
        new RouteSpace(581, 682, 273),
    ], GRAY),
    new Route(38, 8, 10, [
        new RouteSpace(543, 297, -1),
        new RouteSpace(606, 297, -1),
        new RouteSpace(669, 296, -1),
        new RouteSpace(732, 295, -1),
        new RouteSpace(795, 294, -1),
        new RouteSpace(858, 293, -1),
    ], ORANGE),
    new Route(39, 8, 22, [
        new RouteSpace(877, 338, -68),
        new RouteSpace(854, 396, -68),
    ], GRAY),
    new Route(40, 8, 22, [
        new RouteSpace(897, 345, -68),
        new RouteSpace(874, 403, -68),
    ], GRAY),
    new Route(41, 8, 29, [
        new RouteSpace(965, 248, -23),
        new RouteSpace(1022, 225, -23),
        new RouteSpace(1080, 200, -23),
    ], GRAY),
    new Route(42, 8, 33, [
        new RouteSpace(953, 281, -8),
        new RouteSpace(1015, 270, -8),
        new RouteSpace(1077, 259, -8),
        new RouteSpace(1139, 249, -8),
        new RouteSpace(1200, 238, -8),
        new RouteSpace(1263, 227, -8),
    ], PINK),
    new Route(43, 8, 36, 4, BLACK),
    new Route(44, 9, 11, [
        new RouteSpace(611, 913, 30),
        new RouteSpace(668, 939, 18),
        new RouteSpace(729, 953, 8),
        new RouteSpace(792, 959, 2),
        new RouteSpace(855, 955, -10),
        new RouteSpace(915, 939, -19),
    ], GREEN),
    new Route(45, 9, 15, [
        new RouteSpace(202, 840, 36),
        new RouteSpace(256, 872, 24),
        new RouteSpace(316, 893, 15),
        new RouteSpace(378, 905, 9),
        new RouteSpace(441, 907, -4),
        new RouteSpace(502, 898, -14),
    ], BLACK),
    new Route(46, 9, 21, [
        new RouteSpace(619, 866, 342),
        new RouteSpace(677, 843, 334),
        new RouteSpace(733, 811, 326),
        new RouteSpace(781, 771, 315),
        new RouteSpace(822, 723, 305),
    ], YELLOW),
    new Route(47, 9, 23, [
        new RouteSpace(529, 870, 16),
        new RouteSpace(469, 852, 16),
        new RouteSpace(409, 835, 16),
    ], GRAY),
    new Route(48, 9, 31, [
        new RouteSpace(574, 837, 273),
        new RouteSpace(577, 773, 273),
    ], GRAY),
    new Route(49, 10, 22, [
        new RouteSpace(559, 332, 22),
        new RouteSpace(616, 355, 22),
        new RouteSpace(675, 379, 22),
        new RouteSpace(734, 403, 22),
        new RouteSpace(791, 427, 22),
    ], RED),
    new Route(50, 10, 28, [
        new RouteSpace(463, 346, -59),
        new RouteSpace(432, 399, -59),
        new RouteSpace(399, 453, -59),
    ], PINK),
    new Route(51, 10, 32, [
        new RouteSpace(133, 225, 12),
        new RouteSpace(194, 239, 12),
        new RouteSpace(255, 253, 12),
        new RouteSpace(316, 267, 12),
        new RouteSpace(377, 281, 12),
        new RouteSpace(438, 294, 12),
    ], YELLOW),
    new Route(52, 10, 36, 4, BLUE),
    new Route(53, 11, 19, [
        new RouteSpace(1070, 895, 352),
        new RouteSpace(1009, 904, 352),
    ], GRAY),
    new Route(54, 12, 21, [
        new RouteSpace(876, 583, -74),
        new RouteSpace(859, 642, -74),
    ], GRAY),
    new Route(55, 12, 21, [
        new RouteSpace(897, 589, -74),
        new RouteSpace(880, 648, -74),
    ], GRAY),
    new Route(56, 12, 22, [
        new RouteSpace(866, 499, -115),
    ], GRAY),
    new Route(57, 12, 22, [
        new RouteSpace(885, 490, -115),
    ], GRAY),
    new Route(58, 12, 27, [
        new RouteSpace(934, 525, -1),
        new RouteSpace(996, 523, -1),
    ], BLUE),
    new Route(59, 12, 27, [
        new RouteSpace(934, 547, -1),
        new RouteSpace(996, 545, -1),
    ], PINK),
    new Route(60, 13, 15, [
        new RouteSpace(173, 754, -65),
        new RouteSpace(222, 713, -12),
    ], GRAY),
    new Route(61, 13, 28, [
        new RouteSpace(310, 680, -45),
        new RouteSpace(345, 628, -67),
        new RouteSpace(362, 567, -81),
    ], ORANGE),
    new Route(62, 14, 18, [
        new RouteSpace(1056, 691, -4),
        new RouteSpace(1119, 675, -24),
        new RouteSpace(1173, 640, -41),
    ], WHITE),
    new Route(63, 14, 19, [
        new RouteSpace(1036, 737, 63),
        new RouteSpace(1064, 792, 63),
        new RouteSpace(1094, 848, 63),
    ], GREEN),
    new Route(64, 14, 21, [
        new RouteSpace(905, 690, -2),
        new RouteSpace(966, 688, -2),
    ], GRAY),
    new Route(65, 14, 27, [
        new RouteSpace(1033, 586, -75),
        new RouteSpace(1018, 647, -75),
    ], GRAY),
    new Route(66, 15, 23, [
        new RouteSpace(201, 793, -8),
        new RouteSpace(263, 790, 1),
        new RouteSpace(326, 797, 13),
    ], GRAY),
    new Route(67, 15, 30, [
        new RouteSpace(35, 678, -113),
        new RouteSpace(65, 734, -125),
        new RouteSpace(105, 782, -134),
    ], YELLOW),
    new Route(68, 15, 30, [
        new RouteSpace(53, 666, -113),
        new RouteSpace(84, 721, -125),
        new RouteSpace(123, 769, -134),
    ], PINK),
    new Route(69, 16, 19, [
        new RouteSpace(1465, 943, 49),
        new RouteSpace(1421, 898, 40),
        new RouteSpace(1369, 863, 28),
        new RouteSpace(1308, 848, 0),
        new RouteSpace(1245, 855, 345),
        new RouteSpace(1187, 881, 330),
    ], RED),
    new Route(70, 17, 20, [
        new RouteSpace(1456, 118, 80),
        new RouteSpace(1466, 181, 80),
        new RouteSpace(1476, 241, 80),
    ], BLUE),
    new Route(71, 17, 29, 5, BLACK),
    new Route(72, 17, 33, [
        new RouteSpace(1323, 166, -59),
        new RouteSpace(1363, 117, -42),
        new RouteSpace(1417, 83, -23),
    ], GRAY),
    new Route(73, 18, 24, [
        new RouteSpace(1198, 568, -62),
        new RouteSpace(1232, 515, -51),
        new RouteSpace(1279, 472, -33),
        new RouteSpace(1325, 429, -56),
    ], YELLOW),
    new Route(74, 18, 26, [
        new RouteSpace(1242, 578, -32),
        new RouteSpace(1300, 554, -13),
        new RouteSpace(1363, 548, 4),
    ], BLACK),
    new Route(75, 18, 27, [
        new RouteSpace(1085, 578, 17),
        new RouteSpace(1144, 597, 17),
    ], GRAY),
    new Route(76, 20, 24, [
        new RouteSpace(1386, 331, -31),
        new RouteSpace(1439, 299, -31),
    ], WHITE),
    new Route(77, 20, 24, [
        new RouteSpace(1397, 350, -31),
        new RouteSpace(1450, 318, -31),
    ], GREEN),
    new Route(78, 20, 35, [
        new RouteSpace(1494, 341, 87),
        new RouteSpace(1497, 404, 87),
    ], ORANGE),
    new Route(79, 20, 35, [
        new RouteSpace(1515, 339, 87),
        new RouteSpace(1518, 402, 87),
    ], BLACK),
    new Route(80, 21, 31, [
        new RouteSpace(633, 724, -7),
        new RouteSpace(694, 718, -7),
        new RouteSpace(757, 710, -7),
    ], BLUE),
    new Route(81, 23, 31, [
        new RouteSpace(421, 795, -23),
        new RouteSpace(480, 769, -23),
        new RouteSpace(536, 743, -23),
    ], GRAY),
    new Route(82, 24, 26, [
        new RouteSpace(1369, 438, 77),
        new RouteSpace(1383, 500, 77),
    ], GRAY),
    new Route(83, 24, 27, [
        new RouteSpace(1085, 531, -30),
        new RouteSpace(1139, 499, -30),
        new RouteSpace(1193, 468, -30),
        new RouteSpace(1248, 437, -30),
        new RouteSpace(1302, 406, -30),
    ], GREEN),
    new Route(84, 24, 33, [
        new RouteSpace(1332, 256, -93),
        new RouteSpace(1336, 320, -93),
    ], GRAY),
    new Route(85, 24, 35, [
        new RouteSpace(1400, 401, 28),
        new RouteSpace(1456, 430, 28),
    ], GRAY),
    new Route(86, 25, 28, [
        new RouteSpace(96, 292, 12),
        new RouteSpace(156, 309, 20),
        new RouteSpace(214, 338, 33),
        new RouteSpace(265, 375, 40),
        new RouteSpace(310, 418, 49),
        new RouteSpace(346, 469, 61),
    ], BLUE),
    new Route(87, 25, 30, [
        new RouteSpace(17, 327, 111),
        new RouteSpace(-2, 387, 102),
        new RouteSpace(-9, 449, 93),
        new RouteSpace(-8, 512, 86),
        new RouteSpace(3, 575, 73),
    ], GREEN),
    new Route(88, 25, 30, [
        new RouteSpace(39, 330, 111),
        new RouteSpace(20, 390, 102),
        new RouteSpace(13, 452, 93),
        new RouteSpace(14, 515, 86),
        new RouteSpace(25, 578, 73),
    ], PINK),
    new Route(89, 25, 32, [
        new RouteSpace(54, 234, 112),
    ], GRAY),
    new Route(90, 25, 32, [
        new RouteSpace(74, 241, 112),
    ], GRAY),
    new Route(91, 26, 35, [
        new RouteSpace(1431, 528, -50),
        new RouteSpace(1472, 480, -50),
    ], GRAY),
    new Route(92, 26, 35, [
        new RouteSpace(1447, 542, -50),
        new RouteSpace(1488, 494, -50),
    ], GRAY),
    new Route(93, 28, 30, [
        new RouteSpace(78, 597, -18),
        new RouteSpace(137, 578, -18),
        new RouteSpace(195, 559, -18),
        new RouteSpace(253, 539, -18),
        new RouteSpace(312, 520, -18),
    ], ORANGE),
    new Route(94, 28, 30, [
        new RouteSpace(85, 617, -18),
        new RouteSpace(143, 598, -18),
        new RouteSpace(202, 579, -18),
        new RouteSpace(260, 559, -18),
        new RouteSpace(319, 540, -18),
    ], WHITE),
    new Route(95, 29, 33, [
        new RouteSpace(1184, 183, 11),
        new RouteSpace(1246, 196, 11),
    ], GRAY),
    new Route(96, 29, 36, [
        new RouteSpace(773, 91, 12),
        new RouteSpace(834, 103, 12),
        new RouteSpace(896, 116, 12),
        new RouteSpace(956, 129, 12),
        new RouteSpace(1017, 142, 12),
        new RouteSpace(1078, 154, 12),
    ], GRAY),
    new Route(97, 32, 34, 1, GRAY),
    new Route(98, 32, 34, 1, GRAY),
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
            if (typeof route.spaces === 'number') {
                dojo.place("<div id=\"route" + route.id + "\" class=\"route\">" + CITIES[route.from] + " to " + CITIES[route.to] + ", " + route.spaces + " " + COLORS[route.color] + "</div>", 'board');
                document.getElementById("route" + route.id).addEventListener('click', function () { return _this.game.claimRoute(route.id); });
            }
            else {
                route.spaces.forEach(function (space, spaceIndex) {
                    dojo.place("<div id=\"route" + route.id + "-space" + spaceIndex + "\" class=\"route space\" \n                        style=\"transform: translate(" + (space.x * 0.986 + 10) + "px, " + (space.y * 0.986 + 10) + "px) rotate(" + space.angle + "deg)\"\n                        title=\"" + CITIES[route.from] + " to " + CITIES[route.to] + ", " + route.spaces.length + " " + COLORS[route.color] + "\"\n                    ></div>", 'board');
                    document.getElementById("route" + route.id + "-space" + spaceIndex).addEventListener('click', function () { return _this.game.claimRoute(route.id); });
                });
            }
        });
        //this.movePoints();
        this.setClaimedRoutes(claimedRoutes);
    }
    /*public setPoints(playerId: number, points: number) {
        this.points.set(playerId, points);
        this.movePoints();
    }*/
    TtrMap.prototype.setSelectableRoutes = function (selectable, possibleRoutes) {
        if (selectable) {
            possibleRoutes.forEach(function (route) { var _a; return (_a = document.getElementById("route" + route.id)) === null || _a === void 0 ? void 0 : _a.classList.add('selectable'); });
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
        this.destinations.selectionClass = 'destination-selection';
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
    function PlayerTable(game, player, trainCars, destinations) {
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
        // destionation cards
        this.destinationStock = new ebg.stock();
        this.destinationStock.setSelectionAppearance('class');
        this.destinationStock.selectionClass = 'selected';
        this.destinationStock.create(this.game, $("player-table-" + this.playerId + "-destinations"), CARD_WIDTH, CARD_HEIGHT);
        this.destinationStock.setSelectionMode(0);
        this.destinationStock.onItemCreate = function (cardDiv, type) { return setupDestinationCardDiv(cardDiv, type); };
        setupDestinationCards(this.destinationStock);
        this.addDestinations(destinations);
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
            this.playerTable = new PlayerTable(this, player, gamedatas.handTrainCars, gamedatas.handDestinations);
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
        this.map.setPoints(playerId, points);
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
        console.log(notif.args);
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
        this.trainCarCardCounters[playerId].incValue(-route.spaces);
        this.trainCarCounters[playerId].incValue(-route.spaces);
        this.map.setClaimedRoutes([{
                playerId: playerId,
                routeId: route.id
            }]);
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
