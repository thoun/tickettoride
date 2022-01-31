var CARD_WIDTH = 250;
var CARD_HEIGHT = 161;
var DESTINATION_CARD_SHIFT = 32;
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
function getColor(color) {
    switch (color) {
        case 0: return _('Gray');
        case 1: return _('Pink');
        case 2: return _('White');
        case 3: return _('Blue');
        case 4: return _('Yellow');
        case 5: return _('Orange');
        case 6: return _('Black');
        case 7: return _('Red');
        case 8: return _('Green');
    }
}
function setupTrainCarCardDiv(cardDiv, cardTypeId) {
    cardDiv.title = Number(cardTypeId) == 0 ? _('Locomotive') : getColor(Number(cardTypeId));
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
var CITIES_NAMES = [
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
    var destination = DESTINATIONS.find(function (d) { return d.id == cardTypeId; });
    cardDiv.title = dojo.string.substitute(_('${from} to ${to}'), { from: CITIES_NAMES[destination.from], to: CITIES_NAMES[destination.to] }) + ", " + destination.points + " " + _('points');
}
function getBackgroundInlineStyleForDestination(destination) {
    var imagePosition = destination.type_arg - 1;
    var row = Math.floor(imagePosition / IMAGE_ITEMS_PER_ROW);
    var xBackgroundPercent = (imagePosition - (row * IMAGE_ITEMS_PER_ROW)) * 100;
    var yBackgroundPercent = row * 100;
    return "background-position: -" + xBackgroundPercent + "% -" + yBackgroundPercent + "%;";
}
/**
 * Animation with highlighted wagons.
 */
var WagonsAnimation = /** @class */ (function () {
    function WagonsAnimation(game, destinationRoutes) {
        var _this = this;
        this.game = game;
        this.wagons = [];
        this.zoom = this.game.getZoom();
        this.shadowDiv = document.getElementById('map-destination-highlight-shadow');
        destinationRoutes === null || destinationRoutes === void 0 ? void 0 : destinationRoutes.forEach(function (route) {
            var _a;
            return (_a = _this.wagons).push.apply(_a, Array.from(document.querySelectorAll("[id^=\"wagon-route" + route.id + "-space\"]")));
        });
    }
    WagonsAnimation.prototype.setWagonsVisibility = function (visible) {
        this.shadowDiv.dataset.visible = visible ? 'true' : 'false';
        this.wagons.forEach(function (wagon) { return wagon.classList.toggle('highlight', visible); });
    };
    return WagonsAnimation;
}());
var __extends = (this && this.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (Object.prototype.hasOwnProperty.call(b, p)) d[p] = b[p]; };
        return extendStatics(d, b);
    };
    return function (d, b) {
        if (typeof b !== "function" && b !== null)
            throw new TypeError("Class extends value " + String(b) + " is not a constructor or null");
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
/**
 * Destination animation : destination slides over the map, wagons used by destination are highlighted, destination is mark "done" or "uncomplete", and card slides back to original place.
 */
var DestinationCompleteAnimation = /** @class */ (function (_super) {
    __extends(DestinationCompleteAnimation, _super);
    function DestinationCompleteAnimation(game, destination, destinationRoutes, fromId, toId, actions, state, initialSize) {
        if (initialSize === void 0) { initialSize = 1; }
        var _this = _super.call(this, game, destinationRoutes) || this;
        _this.destination = destination;
        _this.fromId = fromId;
        _this.toId = toId;
        _this.actions = actions;
        _this.state = state;
        _this.initialSize = initialSize;
        return _this;
    }
    DestinationCompleteAnimation.prototype.animate = function () {
        var _this = this;
        return new Promise(function (resolve) {
            var _a, _b;
            var fromBR = document.getElementById(_this.fromId).getBoundingClientRect();
            dojo.place("\n            <div id=\"animated-destination-card-" + _this.destination.id + "\" class=\"destination-card\" style=\"" + _this.getCardPosition(_this.destination) + getBackgroundInlineStyleForDestination(_this.destination) + "\"></div>\n            ", 'map');
            var card = document.getElementById("animated-destination-card-" + _this.destination.id);
            (_b = (_a = _this.actions).start) === null || _b === void 0 ? void 0 : _b.call(_a, _this.destination);
            var cardBR = card.getBoundingClientRect();
            var x = (fromBR.x - cardBR.x) / _this.zoom;
            var y = (fromBR.y - cardBR.y) / _this.zoom;
            card.style.transform = "translate(" + x + "px, " + y + "px) scale(" + _this.initialSize + ")";
            _this.setWagonsVisibility(true);
            _this.game.setSelectedDestination(_this.destination, true);
            setTimeout(function () {
                card.classList.add('animated');
                card.style.transform = "";
                _this.markComplete(card, cardBR, resolve);
            }, 100);
        });
    };
    DestinationCompleteAnimation.prototype.markComplete = function (card, cardBR, resolve) {
        var _this = this;
        setTimeout(function () {
            var _a, _b;
            card.classList.add(_this.state);
            (_b = (_a = _this.actions).change) === null || _b === void 0 ? void 0 : _b.call(_a, _this.destination);
            setTimeout(function () {
                var toBR = document.getElementById(_this.toId).getBoundingClientRect();
                var x = (toBR.x - cardBR.x) / _this.zoom;
                var y = (toBR.y - cardBR.y) / _this.zoom;
                card.style.transform = "translate(" + x + "px, " + y + "px) scale(" + _this.initialSize + ")";
                setTimeout(function () { return _this.endAnimation(resolve, card); }, 500);
            }, 500);
        }, 750);
    };
    DestinationCompleteAnimation.prototype.endAnimation = function (resolve, card) {
        var _a, _b;
        this.setWagonsVisibility(false);
        this.game.setSelectedDestination(this.destination, false);
        resolve(this);
        this.game.endAnimation(this);
        (_b = (_a = this.actions).end) === null || _b === void 0 ? void 0 : _b.call(_a, this.destination);
        card.parentElement.removeChild(card);
    };
    DestinationCompleteAnimation.prototype.getCardPosition = function (destination) {
        var positions = [destination.from, destination.to].map(function (cityId) { return CITIES.find(function (city) { return city.id == cityId; }); });
        var x = (positions[0].x + positions[1].x) / 2;
        var y = (positions[0].y + positions[1].y) / 2;
        return "left: " + (x - CARD_WIDTH / 2) + "px; top: " + (y - CARD_HEIGHT / 2) + "px;";
    };
    return DestinationCompleteAnimation;
}(WagonsAnimation));
/**
 * Longest path animation : wagons used by longest path are highlighted, and length is displayed over the map.
 */
var LongestPathAnimation = /** @class */ (function (_super) {
    __extends(LongestPathAnimation, _super);
    function LongestPathAnimation(game, routes, length, playerColor) {
        var _this = _super.call(this, game, routes) || this;
        _this.routes = routes;
        _this.length = length;
        _this.playerColor = playerColor;
        return _this;
    }
    LongestPathAnimation.prototype.animate = function () {
        var _this = this;
        return new Promise(function (resolve) {
            dojo.place("\n            <div id=\"longest-path-animation\" style=\"color: #" + _this.playerColor + ";" + _this.getCardPosition() + "\">" + _this.length + "</div>\n            ", 'map');
            _this.setWagonsVisibility(true);
            setTimeout(function () { return _this.endAnimation(resolve); }, 1900);
        });
    };
    LongestPathAnimation.prototype.endAnimation = function (resolve) {
        this.setWagonsVisibility(false);
        var number = document.getElementById('longest-path-animation');
        number.parentElement.removeChild(number);
        resolve(this);
        this.game.endAnimation(this);
    };
    LongestPathAnimation.prototype.getCardPosition = function () {
        var x = 100;
        var y = 100;
        if (this.routes.length) {
            var positions = [this.routes[0].from, this.routes[this.routes.length - 1].to].map(function (cityId) { return CITIES.find(function (city) { return city.id == cityId; }); });
            x = (positions[0].x + positions[1].x) / 2;
            y = (positions[0].y + positions[1].y) / 2;
        }
        return "left: " + x + "px; top: " + y + "px;";
    };
    return LongestPathAnimation;
}(WagonsAnimation));
var City = /** @class */ (function () {
    function City(id, x, y) {
        this.id = id;
        this.x = x;
        this.y = y;
    }
    return City;
}());
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
var CITIES = [
    new City(1, 1395, 726),
    new City(2, 1701, 197),
    new City(3, 378, 99),
    new City(4, 1564, 739),
    new City(5, 1214, 442),
    new City(6, 973, 908),
    new City(7, 666, 622),
    new City(8, 991, 330),
    new City(9, 644, 950),
    new City(10, 559, 340),
    new City(11, 1049, 980),
    new City(12, 973, 590),
    new City(13, 327, 764),
    new City(14, 1101, 755),
    new City(15, 209, 871),
    new City(16, 1624, 1025),
    new City(17, 1571, 90),
    new City(18, 1302, 663),
    new City(19, 1223, 966),
    new City(20, 1606, 333),
    new City(21, 937, 747),
    new City(22, 937, 497),
    new City(23, 428, 884),
    new City(24, 1452, 414),
    new City(25, 94, 322),
    new City(26, 1514, 619),
    new City(27, 1133, 592),
    new City(28, 430, 564),
    new City(29, 1223, 209),
    new City(30, 69, 682),
    new City(31, 653, 787),
    new City(32, 133, 230),
    new City(33, 1421, 251),
    new City(34, 140, 132),
    new City(35, 1619, 498),
    new City(36, 786, 119), // Winnipeg
];
var ROUTES = [
    new Route(1, 1, 4, [
        new RouteSpace(1413, 729, 3),
        new RouteSpace(1479, 731, 3),
    ], GRAY),
    new Route(2, 1, 16, [
        new RouteSpace(1385, 763, 51),
        new RouteSpace(1427, 814, 51),
        new RouteSpace(1468, 865, 51),
        new RouteSpace(1508, 915, 51),
        new RouteSpace(1549, 965, 51),
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
        new RouteSpace(945, 461, -34),
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
        new RouteSpace(1354, 269, -39),
    ], WHITE),
    new Route(24, 6, 9, [
        new RouteSpace(691, 933, 351),
        new RouteSpace(755, 922, 351),
        new RouteSpace(819, 913, 351),
        new RouteSpace(884, 904, 351),
    ], RED),
    new Route(25, 6, 11, [
        new RouteSpace(964, 930, 49),
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
        new RouteSpace(534, 946, -14),
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
        new RouteSpace(913, 531, -115),
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
        new RouteSpace(47, 717, -113),
        new RouteSpace(78, 776, -125),
        new RouteSpace(120, 826, -134),
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
        new RouteSpace(1, 479, 93),
        new RouteSpace(2, 544, 86),
        new RouteSpace(14, 610, 73),
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
var DRAG_AUTO_ZOOM_DELAY = 2000;
var SIDES = ['left', 'right', 'top', 'bottom'];
var CORNERS = ['bottom-left', 'bottom-right', 'top-left', 'top-right'];
var MAP_WIDTH = 1744;
var MAP_HEIGHT = 1125;
var DECK_WIDTH = 250;
var PLAYER_WIDTH = 305;
var PLAYER_HEIGHT = 257; // avg height (4 destination cards)
var BOTTOM_RATIO = (MAP_WIDTH + DECK_WIDTH) / (MAP_HEIGHT + PLAYER_HEIGHT);
var LEFT_RATIO = (PLAYER_WIDTH + MAP_WIDTH + DECK_WIDTH) / (MAP_HEIGHT);
/**
 * Manager for in-map zoom.
 */
var InMapZoomManager = /** @class */ (function () {
    function InMapZoomManager() {
        var _this = this;
        this.pos = { dragging: false, top: 0, left: 0, x: 0, y: 0 }; // for map drag (if zoomed)
        this.zoomed = false; // indicates if in-map zoom is active
        this.mapZoomDiv = document.getElementById('map-zoom');
        this.mapDiv = document.getElementById('map');
        // Attach the handler
        this.mapDiv.addEventListener('mousedown', function (e) { return _this.mouseDownHandler(e); });
        document.addEventListener('mousemove', function (e) { return _this.mouseMoveHandler(e); });
        document.addEventListener('mouseup', function (e) { return _this.mouseUpHandler(); });
        document.getElementById('zoom-button').addEventListener('click', function () { return _this.toggleZoom(); });
        this.mapDiv.addEventListener('dragenter', function (e) {
            console.log('map dragenter', e);
        });
        this.mapDiv.addEventListener('dragover', function (e) {
            if (e.offsetX !== _this.dragClientX || e.offsetY !== _this.dragClientY) {
                _this.dragClientX = e.offsetX;
                _this.dragClientY = e.offsetY;
                _this.dragOverMouseMoved(e.offsetX, e.offsetY);
            }
        });
        this.mapDiv.addEventListener('dragleave', function (e) {
            clearTimeout(_this.autoZoomTimeout);
            console.log('map dragleave', e);
        });
        this.mapDiv.addEventListener('drop', function (e) {
            clearTimeout(_this.autoZoomTimeout);
            console.log('map drop', e);
        });
    }
    InMapZoomManager.prototype.dragOverMouseMoved = function (clientX, clientY) {
        var _this = this;
        console.log('map dragover');
        if (this.autoZoomTimeout) {
            clearTimeout(this.autoZoomTimeout);
        }
        this.autoZoomTimeout = setTimeout(function () {
            if (!_this.hoveredRoute) { // do not automatically change the zoom when player is dragging over a route!
                _this.toggleZoom(clientX / _this.mapDiv.clientWidth, clientY / _this.mapDiv.clientHeight);
            }
            _this.autoZoomTimeout = null;
        }, DRAG_AUTO_ZOOM_DELAY);
    };
    /**
     * Handle click on zoom button. Toggle between full map and in-map zoom.
     */
    InMapZoomManager.prototype.toggleZoom = function (scrollRatioX, scrollRatioY) {
        if (scrollRatioX === void 0) { scrollRatioX = null; }
        if (scrollRatioY === void 0) { scrollRatioY = null; }
        if (scrollRatioX && scrollRatioY) {
            console.log('scroll ratio', scrollRatioX, scrollRatioY);
        }
        this.zoomed = !this.zoomed;
        this.mapDiv.style.transform = this.zoomed ? "scale(1.8)" : '';
        dojo.toggleClass('zoom-button', 'zoomed', this.zoomed);
        dojo.toggleClass('map-zoom', 'scrollable', this.zoomed);
        this.mapDiv.style.cursor = this.zoomed ? 'grab' : 'default';
        if (this.zoomed) {
            if (scrollRatioX && scrollRatioY) {
                this.mapZoomDiv.scrollLeft = (this.mapZoomDiv.scrollWidth - this.mapZoomDiv.clientWidth) * scrollRatioX;
                this.mapZoomDiv.scrollTop = (this.mapZoomDiv.scrollHeight - this.mapZoomDiv.clientHeight) * scrollRatioY;
            }
        }
        else {
            this.mapZoomDiv.scrollTop = 0;
            this.mapZoomDiv.scrollLeft = 0;
        }
    };
    /**
     * Handle mouse down, to grap map and scroll in it (imitate mobile touch scroll).
     */
    InMapZoomManager.prototype.mouseDownHandler = function (e) {
        if (!this.zoomed) {
            return;
        }
        this.mapDiv.style.cursor = 'grabbing';
        this.pos = {
            dragging: true,
            left: this.mapDiv.scrollLeft,
            top: this.mapDiv.scrollTop,
            // Get the current mouse position
            x: e.clientX,
            y: e.clientY,
        };
    };
    /**
     * Handle mouse move, to grap map and scroll in it (imitate mobile touch scroll).
     */
    InMapZoomManager.prototype.mouseMoveHandler = function (e) {
        if (!this.zoomed || !this.pos.dragging) {
            return;
        }
        // How far the mouse has been moved
        var dx = e.clientX - this.pos.x;
        var dy = e.clientY - this.pos.y;
        var factor = 0.1;
        // Scroll the element
        this.mapZoomDiv.scrollTop -= dy * factor;
        this.mapZoomDiv.scrollLeft -= dx * factor;
    };
    /**
     * Handle mouse up, to grap map and scroll in it (imitate mobile touch scroll).
     */
    InMapZoomManager.prototype.mouseUpHandler = function () {
        if (!this.zoomed || !this.pos.dragging) {
            return;
        }
        this.mapDiv.style.cursor = 'grab';
        this.pos.dragging = false;
    };
    InMapZoomManager.prototype.setHoveredRoute = function (route) {
        this.hoveredRoute = route;
    };
    return InMapZoomManager;
}());
/**
 * Map creation and in-map zoom handler.
 */
var TtrMap = /** @class */ (function () {
    /**
     * Place map corner illustration and borders, cities, routes, and bind events.
     */
    function TtrMap(game, players, claimedRoutes) {
        var _this = this;
        this.game = game;
        this.players = players;
        // map border
        dojo.place("<div class=\"illustration\"></div>", 'map', 'first');
        SIDES.forEach(function (side) { return dojo.place("<div class=\"side " + side + "\"></div>", 'map-and-borders'); });
        CORNERS.forEach(function (corner) { return dojo.place("<div class=\"corner " + corner + "\"></div>", 'map-and-borders'); });
        CITIES.forEach(function (city) {
            return dojo.place("<div id=\"city" + city.id + "\" class=\"city\" \n                style=\"transform: translate(" + city.x + "px, " + city.y + "px)\"\n                title=\"" + CITIES_NAMES[city.id] + "\"\n            ></div>", 'map');
        });
        ROUTES.forEach(function (route) {
            return route.spaces.forEach(function (space, spaceIndex) {
                dojo.place("<div id=\"route" + route.id + "-space" + spaceIndex + "\" class=\"route-space\" \n                    style=\"transform: translate(" + space.x + "px, " + space.y + "px) rotate(" + space.angle + "deg)\"\n                    title=\"" + dojo.string.substitute(_('${from} to ${to}'), { from: CITIES_NAMES[route.from], to: CITIES_NAMES[route.to] }) + ", " + route.spaces.length + " " + getColor(route.color) + "\"\n                    data-route=\"" + route.id + "\" data-color=\"" + route.color + "\"\n                ></div>", 'map');
                var spaceDiv = document.getElementById("route" + route.id + "-space" + spaceIndex);
                _this.setSpaceEvents(spaceDiv, route);
            });
        });
        /* TO RESCALE all route coordinates
        
        console.log(ROUTES.map(route => `    new Route(${route.id}, ${route.from}, ${route.to}, [
${route.spaces.map(space => `        new RouteSpace(${Math.round(space.x)}, ${Math.round(space.y)}, ${space.angle}),`).join('\n')}
    ], ${getColor(route.color).toUpperCase()}),`).join('\n'));*/
        this.setClaimedRoutes(claimedRoutes, null);
        this.resizedDiv = document.getElementById('resized');
        this.mapDiv = document.getElementById('map');
        this.inMapZoomManager = new InMapZoomManager();
    }
    /**
     * Handle dragging train car cards over a route.
     */
    TtrMap.prototype.routeDragOver = function (e, route) {
        console.log('enterover', e);
        var cardsColor = Number(this.mapDiv.dataset.dragColor);
        var canClaimRoute = this.game.canClaimRoute(route, cardsColor);
        this.setHoveredRoute(route, canClaimRoute);
        if (canClaimRoute) {
            e.preventDefault();
        }
    };
    ;
    /**
     * Handle dropping train car cards over a route.
     */
    TtrMap.prototype.routeDragDrop = function (e, route) {
        console.log('drop', e);
        if (document.getElementById('map').dataset.dragColor == '') {
            return;
        }
        this.setHoveredRoute(null);
        var cardsColor = Number(this.mapDiv.dataset.dragColor);
        document.getElementById('map').dataset.dragColor = '';
        this.game.claimRoute(route.id, cardsColor);
    };
    ;
    /**
     * Bind events to route space.
     */
    TtrMap.prototype.setSpaceEvents = function (spaceDiv, route) {
        var _this = this;
        spaceDiv.addEventListener('dragenter', function (e) { return _this.routeDragOver(e, route); });
        spaceDiv.addEventListener('dragover', function (e) { return _this.routeDragOver(e, route); });
        spaceDiv.addEventListener('dragleave', function (e) {
            console.log('dragleave', e);
            _this.setHoveredRoute(null);
        });
        spaceDiv.addEventListener('drop', function (e) { return _this.routeDragDrop(e, route); });
        spaceDiv.addEventListener('click', function () { return _this.game.clickedRoute(route); });
    };
    /**
     * Highlight selectable route spaces.
     */
    TtrMap.prototype.setSelectableRoutes = function (selectable, possibleRoutes) {
        if (selectable) {
            possibleRoutes.forEach(function (route) { return ROUTES.find(function (r) { return r.id == route.id; }).spaces.forEach(function (_, index) { var _a; return (_a = document.getElementById("route" + route.id + "-space" + index)) === null || _a === void 0 ? void 0 : _a.classList.add('selectable'); }); });
        }
        else {
            dojo.query('.route-space').removeClass('selectable');
        }
    };
    /**
     * Place train cars on claimed routes.
     * fromPlayerId is for animation (null for no animation)
     */
    TtrMap.prototype.setClaimedRoutes = function (claimedRoutes, fromPlayerId) {
        var _this = this;
        claimedRoutes.forEach(function (claimedRoute) {
            var route = ROUTES.find(function (r) { return r.id == claimedRoute.routeId; });
            var color = _this.players.find(function (player) { return Number(player.id) == claimedRoute.playerId; }).color;
            _this.setWagons(route, color, fromPlayerId, false);
        });
    };
    TtrMap.prototype.animateWagonFromCounter = function (playerId, wagonId, toX, toY) {
        var wagon = document.getElementById(wagonId);
        var wagonBR = wagon.getBoundingClientRect();
        var fromBR = document.getElementById("train-car-counter-" + playerId + "-wrapper").getBoundingClientRect();
        var zoom = this.game.getZoom();
        var fromX = (fromBR.x - wagonBR.x) / zoom;
        var fromY = (fromBR.y - wagonBR.y) / zoom;
        wagon.style.transform = "translate(" + (fromX + toX) + "px, " + (fromY + toY) + "px)";
        setTimeout(function () {
            wagon.style.transition = 'transform 0.5s';
            wagon.style.transform = "translate(" + toX + "px, " + toY + "px";
        }, 0);
    };
    /**
     * Place train car on a route space.
     * fromPlayerId is for animation (null for no animation)
     * Phantom is for dragging over a route : wagons are showns translucent.
     */
    TtrMap.prototype.setWagon = function (route, space, spaceIndex, color, fromPlayerId, phantom) {
        var id = "wagon-route" + route.id + "-space" + spaceIndex + (phantom ? '-phantom' : '');
        if (document.getElementById(id)) {
            return;
        }
        var angle = -space.angle;
        while (angle < 0) {
            angle += 180;
        }
        while (angle >= 180) {
            angle -= 180;
        }
        var x = space.x;
        var y = space.y;
        dojo.place("<div id=\"" + id + "\" class=\"wagon angle" + Math.round(angle * 36 / 180) + " " + (phantom ? 'phantom' : '') + "\" data-player-color=\"" + color + "\" style=\"transform: translate(" + x + "px, " + y + "px)\"></div>", 'map');
        if (fromPlayerId) {
            this.animateWagonFromCounter(fromPlayerId, id, x, y);
        }
    };
    /**
     * Place train cars on a route.
     * fromPlayerId is for animation (null for no animation)
     * Phantom is for dragging over a route : wagons are showns translucent.
     */
    TtrMap.prototype.setWagons = function (route, color, fromPlayerId, phantom) {
        var _this = this;
        if (!phantom) {
            route.spaces.forEach(function (space, spaceIndex) {
                var spaceDiv = document.getElementById("route" + route.id + "-space" + spaceIndex);
                spaceDiv.parentElement.removeChild(spaceDiv);
            });
        }
        if (fromPlayerId) {
            route.spaces.forEach(function (space, spaceIndex) {
                setTimeout(function () {
                    _this.setWagon(route, space, spaceIndex, color, fromPlayerId, phantom);
                }, 200 * spaceIndex);
            });
        }
        else {
            route.spaces.forEach(function (space, spaceIndex) { return _this.setWagon(route, space, spaceIndex, color, fromPlayerId, phantom); });
        }
    };
    /**
     * Set map size, depending on available screen size.
     * Player table will be placed left or bottom, depending on window ratio.
     */
    TtrMap.prototype.setAutoZoom = function () {
        var _this = this;
        if (!this.mapDiv.clientWidth) {
            setTimeout(function () { return _this.setAutoZoom(); }, 200);
            return;
        }
        var screenRatio = document.getElementById('game_play_area').clientWidth / (window.innerHeight - 80);
        var leftDistance = Math.abs(LEFT_RATIO - screenRatio);
        var bottomDistance = Math.abs(BOTTOM_RATIO - screenRatio);
        var left = leftDistance < bottomDistance;
        this.game.setPlayerTablePosition(left);
        var gameWidth = (left ? PLAYER_WIDTH : 0) + MAP_WIDTH + DECK_WIDTH;
        var gameHeight = MAP_HEIGHT + (left ? 0 : PLAYER_HEIGHT);
        var horizontalScale = document.getElementById('game_play_area').clientWidth / gameWidth;
        var verticalScale = (window.innerHeight - 80) / gameHeight;
        this.scale = Math.min(1, horizontalScale, verticalScale);
        this.resizedDiv.style.transform = this.scale === 1 ? '' : "scale(" + this.scale + ")";
        this.resizedDiv.style.marginBottom = "-" + (1 - this.scale) * gameHeight + "px";
    };
    /**
     * Get current zoom.
     */
    TtrMap.prototype.getZoom = function () {
        return this.scale;
    };
    /**
     * Highlight active destination.
     */
    TtrMap.prototype.setActiveDestination = function (destination, previousDestination) {
        if (previousDestination === void 0) { previousDestination = null; }
        if (previousDestination) {
            if (previousDestination.id === destination.id) {
                return;
            }
            [previousDestination.from, previousDestination.to].forEach(function (city) {
                return document.getElementById("city" + city).dataset.selectedDestination = 'false';
            });
        }
        if (destination) {
            [destination.from, destination.to].forEach(function (city) {
                return document.getElementById("city" + city).dataset.selectedDestination = 'true';
            });
        }
    };
    /**
     * Highlight hovered route (when dragging train cars).
     */
    TtrMap.prototype.setHoveredRoute = function (route, valid) {
        if (valid === void 0) { valid = null; }
        this.inMapZoomManager.setHoveredRoute(route);
        if (route) {
            [route.from, route.to].forEach(function (city) {
                var cityDiv = document.getElementById("city" + city);
                cityDiv.dataset.hovered = 'true';
                cityDiv.dataset.valid = valid.toString();
            });
            if (valid) {
                this.setWagons(route, this.game.getPlayerColor(), null, true);
            }
        }
        else {
            ROUTES.forEach(function (r) { return [r.from, r.to].forEach(function (city) {
                return document.getElementById("city" + city).dataset.hovered = 'false';
            }); });
            // remove phantom wagons
            this.mapDiv.querySelectorAll('.wagon.phantom').forEach(function (spaceDiv) { return spaceDiv.parentElement.removeChild(spaceDiv); });
        }
    };
    /**
     * Highlight cities of selectable destination.
     */
    TtrMap.prototype.setSelectableDestination = function (destination, visible) {
        [destination.from, destination.to].forEach(function (city) {
            document.getElementById("city" + city).dataset.selectable = '' + visible;
        });
    };
    /**
     * Highlight cities of selected destination.
     */
    TtrMap.prototype.setSelectedDestination = function (destination, visible) {
        [destination.from, destination.to].forEach(function (city) {
            document.getElementById("city" + city).dataset.selected = '' + visible;
        });
    };
    /**
     * Highlight cities player must connect for its objectives.
     */
    TtrMap.prototype.setDestinationsToConnect = function (destinations) {
        this.mapDiv.querySelectorAll(".city[data-to-connect]").forEach(function (city) { return city.dataset.toConnect = 'false'; });
        var cities = [];
        destinations.forEach(function (destination) { return cities.push(destination.from, destination.to); });
        cities.forEach(function (city) { return document.getElementById("city" + city).dataset.toConnect = 'true'; });
    };
    /**
     * Highlight destination (on destination mouse over).
     */
    TtrMap.prototype.setHighligthedDestination = function (destination) {
        var visible = Boolean(destination).toString();
        var shadow = document.getElementById('map-destination-highlight-shadow');
        shadow.dataset.visible = visible;
        var cities;
        if (destination) {
            shadow.dataset.from = '' + destination.from;
            shadow.dataset.to = '' + destination.to;
            cities = [destination.from, destination.to];
        }
        else {
            cities = [shadow.dataset.from, shadow.dataset.to];
        }
        cities.forEach(function (city) { return document.getElementById("city" + city).dataset.highlight = visible; });
    };
    return TtrMap;
}());
/**
 * Selection of new destinations.
 */
var DestinationSelection = /** @class */ (function () {
    /**
     * Init stock.
     */
    function DestinationSelection(game) {
        var _this = this;
        this.game = game;
        this.destinations = new ebg.stock();
        this.destinations.setSelectionAppearance('class');
        this.destinations.selectionClass = 'selected';
        this.destinations.setSelectionMode(2);
        this.destinations.create(game, $("destination-stock"), CARD_WIDTH, CARD_HEIGHT);
        this.destinations.onItemCreate = function (cardDiv, cardTypeId) { return setupDestinationCardDiv(cardDiv, Number(cardTypeId)); };
        this.destinations.image_items_per_row = 10;
        this.destinations.centerItems = true;
        this.destinations.item_margin = 20;
        dojo.connect(this.destinations, 'onChangeSelection', this, function () { return _this.selectionChange(); });
        setupDestinationCards(this.destinations);
    }
    /**
     * Set visible destination cards.
     */
    DestinationSelection.prototype.setCards = function (destinations, minimumDestinations, visibleColors) {
        var _this = this;
        dojo.removeClass('destination-deck', 'hidden');
        destinations.forEach(function (destination) {
            _this.destinations.addToStockWithId(destination.type_arg, '' + destination.id);
            var cardDiv = document.getElementById("destination-stock_item_" + destination.id);
            // when mouse hover destination, highlight it on the map
            cardDiv.addEventListener('mouseenter', function () { return _this.game.setHighligthedDestination(destination); });
            cardDiv.addEventListener('mouseleave', function () { return _this.game.setHighligthedDestination(null); });
            // when destinatin is selected, another highlight on the map
            cardDiv.addEventListener('click', function () { return _this.game.setSelectedDestination(destination, _this.destinations.getSelectedItems().some(function (item) { return Number(item.id) == destination.id; })); });
        });
        this.minimumDestinations = minimumDestinations;
        visibleColors.forEach(function (color, index) {
            console.log(document.getElementById("visible-train-cards-mini" + index));
            document.getElementById("visible-train-cards-mini" + index).dataset.color = '' + color;
        });
    };
    /**
     * Hide destination selector.
     */
    DestinationSelection.prototype.hide = function () {
        this.destinations.removeAll();
        dojo.addClass('destination-deck', 'hidden');
    };
    /**
     * Get selected destinations ids.
     */
    DestinationSelection.prototype.getSelectedDestinationsIds = function () {
        return this.destinations.getSelectedItems().map(function (item) { return Number(item.id); });
    };
    /**
     * Toggle activation of confirm selection buttons, depending on minimumDestinations.
     */
    DestinationSelection.prototype.selectionChange = function () {
        if (document.getElementById('chooseInitialDestinations_button')) {
            dojo.toggleClass('chooseInitialDestinations_button', 'disabled', this.destinations.getSelectedItems().length < this.minimumDestinations);
        }
        if (document.getElementById('chooseAdditionalDestinations_button')) {
            dojo.toggleClass('chooseAdditionalDestinations_button', 'disabled', this.destinations.getSelectedItems().length < this.minimumDestinations);
        }
    };
    return DestinationSelection;
}());
var DBL_CLICK_TIMEOUT = 300;
/**
 * Level of cards in deck indicator.
 */
var Gauge = /** @class */ (function () {
    function Gauge(containerId, className, max) {
        this.max = max;
        dojo.place("\n        <div id=\"gauge-" + className + "\" class=\"gauge " + className + "\">\n            <div class=\"inner\" id=\"gauge-" + className + "-level\"></div>\n        </div>", containerId);
        this.levelDiv = document.getElementById("gauge-" + className + "-level");
    }
    Gauge.prototype.setCount = function (count) {
        this.levelDiv.style.height = 100 * count / this.max + "%";
    };
    return Gauge;
}());
/**
 * Selection of new train cars.
 */
var TrainCarSelection = /** @class */ (function () {
    /**
     * Init stocks and gauges.
     */
    function TrainCarSelection(game, visibleCards, trainCarDeckCount, destinationDeckCount, trainCarDeckMaxCount, destinationDeckMaxCount) {
        var _this = this;
        this.game = game;
        this.visibleCardsStocks = [];
        this.dblClickTimeout = null;
        document.getElementById('train-car-deck-hidden-pile1').addEventListener('click', function () { return _this.game.onHiddenTrainCarDeckClick(1); });
        document.getElementById('train-car-deck-hidden-pile2').addEventListener('click', function () { return _this.game.onHiddenTrainCarDeckClick(2); });
        document.getElementById('train-car-deck-hidden-pile').addEventListener('click', function () {
            if (_this.dblClickTimeout) {
                clearTimeout(_this.dblClickTimeout);
                _this.dblClickTimeout = null;
                _this.game.onHiddenTrainCarDeckClick(2);
            }
            else if (!dojo.hasClass('train-car-deck-hidden-pile', 'buttonselection')) {
                _this.dblClickTimeout = setTimeout(function () {
                    _this.game.onHiddenTrainCarDeckClick(1);
                    _this.dblClickTimeout = null;
                }, DBL_CLICK_TIMEOUT);
            }
        });
        var _loop_1 = function (i) {
            this_1.visibleCardsStocks[i] = new ebg.stock();
            this_1.visibleCardsStocks[i].setSelectionAppearance('class');
            this_1.visibleCardsStocks[i].selectionClass = 'no-class-selection';
            this_1.visibleCardsStocks[i].setSelectionMode(1);
            this_1.visibleCardsStocks[i].create(game, $("visible-train-cards-stock" + i), CARD_WIDTH, CARD_HEIGHT);
            this_1.visibleCardsStocks[i].onItemCreate = function (cardDiv, cardTypeId) { return setupTrainCarCardDiv(cardDiv, cardTypeId); };
            this_1.visibleCardsStocks[i].centerItems = true;
            dojo.connect(this_1.visibleCardsStocks[i], 'onChangeSelection', this_1, function (_, itemId) { return _this.game.onVisibleTrainCarCardClick(Number(itemId), _this.visibleCardsStocks[i]); });
            setupTrainCarCards(this_1.visibleCardsStocks[i]);
        };
        var this_1 = this;
        for (var i = 1; i <= 5; i++) {
            _loop_1(i);
        }
        this.setNewCardsOnTable(visibleCards);
        this.trainCarGauge = new Gauge('train-car-deck-hidden-pile', 'train-car', trainCarDeckMaxCount);
        this.destinationGauge = new Gauge('destination-deck-hidden-pile', 'destination', destinationDeckMaxCount);
        this.setTrainCarCount(trainCarDeckCount);
        this.setDestinationCount(destinationDeckCount);
    }
    /**
     * Set selection of hidden cards deck.
     */
    TrainCarSelection.prototype.setSelectableTopDeck = function (selectable, number) {
        if (number === void 0) { number = 0; }
        dojo.toggleClass('train-car-deck-hidden-pile', 'selectable', selectable);
        dojo.toggleClass('train-car-deck-hidden-pile1', 'hidden', number < 1);
        dojo.toggleClass('train-car-deck-hidden-pile2', 'hidden', number < 2);
    };
    /**
     * Set selectable visible cards (locomotive can't be selected if 1 visible card has been picked).
     */
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
    /**
     * Reset visible cards state.
     */
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
    /**
     * Set new visible cards.
     */
    TrainCarSelection.prototype.setNewCardsOnTable = function (cards) {
        var _this = this;
        cards.forEach(function (card) {
            var spot = card.location_arg;
            _this.visibleCardsStocks[spot].removeAll();
            _this.visibleCardsStocks[spot].addToStockWithId(card.type, '' + card.id, 'train-car-deck-hidden-pile');
        });
    };
    /**
     * Update train car gauge.
     */
    TrainCarSelection.prototype.setTrainCarCount = function (count) {
        this.trainCarGauge.setCount(count);
        document.getElementById("train-car-deck-level").dataset.level = "" + Math.min(10, Math.ceil(count / 10));
    };
    /**
     * Update destination gauge.
     */
    TrainCarSelection.prototype.setDestinationCount = function (count) {
        this.destinationGauge.setCount(count);
        document.getElementById("destination-deck-level").dataset.level = "" + Math.min(10, Math.ceil(count / 10));
    };
    /**
     * Make hidden train car cads selection buttons visible (user preference).
     */
    TrainCarSelection.prototype.setCardSelectionButtons = function (visible) {
        dojo.toggleClass('train-car-deck-hidden-pile', 'buttonselection', visible);
    };
    /**
     * Get HTML Element represented by "from" (0 means invisible, 1 to 5 are visible cards).
     */
    TrainCarSelection.prototype.getStockElement = function (from) {
        return from === 0 ? document.getElementById('train-car-deck-hidden-pile') : this.visibleCardsStocks[from].container_div;
    };
    /**
     * Animation to move a card to a player's counter (the destroy animated card).
     */
    TrainCarSelection.prototype.animateCardToCounterAndDestroy = function (cardId, destinationId) {
        var card = document.getElementById(cardId);
        var cardBR = card.getBoundingClientRect();
        var toBR = document.getElementById(destinationId).getBoundingClientRect();
        var zoom = this.game.getZoom();
        var x = (toBR.x - cardBR.x) / zoom;
        var y = (toBR.y - cardBR.y) / zoom;
        card.style.transform = "translate(" + x + "px, " + y + "px) scale(" + 0.15 / zoom + ")";
        setTimeout(function () { var _a; return (_a = card.parentElement) === null || _a === void 0 ? void 0 : _a.removeChild(card); }, 500);
    };
    /**
     * Animation when train car cards are picked by another player.
     */
    TrainCarSelection.prototype.moveTrainCarCardToPlayerBoard = function (playerId, from, color, number) {
        var _this = this;
        if (color === void 0) { color = 0; }
        if (number === void 0) { number = 1; }
        var _loop_4 = function (i) {
            setTimeout(function () {
                dojo.place("\n                <div id=\"animated-train-car-card-" + from + "-" + i + "\" class=\"animated-train-car-card " + (from === 0 ? 'from-hidden-pile' : '') + "\" data-color=\"" + color + "\"></div>\n                ", _this.getStockElement(from));
                _this.animateCardToCounterAndDestroy("animated-train-car-card-" + from + "-" + i, "train-car-card-counter-" + playerId + "-wrapper");
            }, 200 * i);
        };
        for (var i = 0; i < number; i++) {
            _loop_4(i);
        }
    };
    /**
     * Animation when destination cards are picked by another player.
     */
    TrainCarSelection.prototype.moveDestinationCardToPlayerBoard = function (playerId, number) {
        var _this = this;
        var _loop_5 = function (i) {
            setTimeout(function () {
                dojo.place("\n                <div id=\"animated-destination-card-" + i + "\" class=\"animated-destination-card\"></div>\n                ", 'destination-deck-hidden-pile');
                _this.animateCardToCounterAndDestroy("animated-destination-card-" + i, "destinations-counter-" + playerId + "-wrapper");
            }, 200 * i);
        };
        for (var i = 0; i < number; i++) {
            _loop_5(i);
        }
    };
    TrainCarSelection.prototype.getVisibleColors = function () {
        return this.visibleCardsStocks.map(function (stock) { return stock.items[0].type; });
    };
    return TrainCarSelection;
}());
/**
 * Player table : train car et destination cards.
 */
var PlayerTable = /** @class */ (function () {
    function PlayerTable(game, player, trainCars, destinations, completedDestinations) {
        var html = "\n            <div id=\"player-table\" class=\"player-table\">\n                <div id=\"player-table-" + player.id + "-destinations\" class=\"player-table-destinations\"></div>\n                <div id=\"player-table-" + player.id + "-train-cars\" class=\"player-table-train-cars\"></div>\n            </div>\n        ";
        dojo.place(html, 'resized');
        this.playerDestinations = new PlayerDestinations(game, player, destinations, completedDestinations);
        this.playerTrainCars = new PlayerTrainCars(game, player, trainCars);
    }
    /**
     * Place player table to the left or the bottom of the map.
     */
    PlayerTable.prototype.setPosition = function (left) {
        var playerHandDiv = document.getElementById("player-table");
        if (left) {
            document.getElementById('main-line').prepend(playerHandDiv);
        }
        else {
            document.getElementById('resized').appendChild(playerHandDiv);
        }
        playerHandDiv.classList.toggle('left', left);
        this.playerTrainCars.setPosition(left);
    };
    PlayerTable.prototype.addDestinations = function (destinations, originStock) {
        this.playerDestinations.addDestinations(destinations, originStock);
    };
    PlayerTable.prototype.markDestinationComplete = function (destination, destinationRoutes) {
        this.playerDestinations.markDestinationComplete(destination, destinationRoutes);
    };
    PlayerTable.prototype.addTrainCars = function (trainCars, from) {
        this.playerTrainCars.addTrainCars(trainCars, from);
    };
    PlayerTable.prototype.removeCards = function (removeCards) {
        this.playerTrainCars.removeCards(removeCards);
    };
    PlayerTable.prototype.setDraggable = function (draggable) {
        this.playerTrainCars.setDraggable(draggable);
    };
    PlayerTable.prototype.getPossibleColors = function (route) {
        return this.playerTrainCars.getPossibleColors(route);
    };
    PlayerTable.prototype.setSelectableTrainCarColors = function (routeId, possibleColors) {
        if (possibleColors === void 0) { possibleColors = null; }
        this.playerTrainCars.setSelectableTrainCarColors(routeId, possibleColors);
    };
    return PlayerTable;
}());
var __spreadArray = (this && this.__spreadArray) || function (to, from, pack) {
    if (pack || arguments.length === 2) for (var i = 0, l = from.length, ar; i < l; i++) {
        if (ar || !(i in from)) {
            if (!ar) ar = Array.prototype.slice.call(from, 0, i);
            ar[i] = from[i];
        }
    }
    return to.concat(ar || Array.prototype.slice.call(from));
};
var IMAGE_ITEMS_PER_ROW = 10;
/**
 * Player's destination cards.
 */
var PlayerDestinations = /** @class */ (function () {
    function PlayerDestinations(game, player, destinations, completedDestinations) {
        var _this = this;
        this.game = game;
        /** Highlighted destination on the map */
        this.selectedDestination = null;
        /** Destinations in "to do" column */
        this.destinationsTodo = [];
        /** Destinations in "done" column */
        this.destinationsDone = [];
        this.playerId = Number(player.id);
        var html = "\n        <div id=\"player-table-" + player.id + "-destinations-todo\" class=\"player-table-destinations-column todo\"></div>\n        <div id=\"player-table-" + player.id + "-destinations-done\" class=\"player-table-destinations-column done\"></div>\n        ";
        dojo.place(html, "player-table-" + player.id + "-destinations");
        this.addDestinations(destinations);
        destinations.filter(function (destination) { return completedDestinations.some(function (d) { return d.id == destination.id; }); }).forEach(function (destination) {
            return _this.markDestinationComplete(destination);
        });
        // highlight the first "to do" destination
        this.activateNextDestination(this.destinationsTodo);
    }
    /**
     * Add destinations to player's hand.
     */
    PlayerDestinations.prototype.addDestinations = function (destinations, originStock) {
        var _a;
        var _this = this;
        destinations.forEach(function (destination) {
            var html = "\n            <div id=\"destination-card-" + destination.id + "\" class=\"destination-card\" style=\"" + getBackgroundInlineStyleForDestination(destination) + "\"></div>\n            ";
            dojo.place(html, "player-table-" + _this.playerId + "-destinations-todo");
            var card = document.getElementById("destination-card-" + destination.id);
            setupDestinationCardDiv(card, destination.type_arg);
            card.addEventListener('click', function () { return _this.activateNextDestination(_this.destinationsDone.some(function (d) { return d.id == destination.id; }) ? _this.destinationsDone : _this.destinationsTodo); });
            // highlight destination's cities on the map, on mouse over
            card.addEventListener('mouseenter', function () { return _this.game.setHighligthedDestination(destination); });
            card.addEventListener('mouseleave', function () { return _this.game.setHighligthedDestination(null); });
            if (originStock) {
                _this.addAnimationFrom(card, document.getElementById(originStock.container_div.id + "_item_" + destination.id));
            }
        });
        originStock === null || originStock === void 0 ? void 0 : originStock.removeAll();
        (_a = this.destinationsTodo).push.apply(_a, destinations);
        this.destinationColumnsUpdated();
    };
    /**
     * Mark destination as complete (place it on the "complete" column).
     */
    PlayerDestinations.prototype.markDestinationCompleteNoAnimation = function (destination) {
        var index = this.destinationsTodo.findIndex(function (d) { return d.id == destination.id; });
        if (index !== -1) {
            this.destinationsTodo.splice(index, 1);
        }
        this.destinationsDone.push(destination);
        document.getElementById("player-table-" + this.playerId + "-destinations-done").appendChild(document.getElementById("destination-card-" + destination.id));
        this.destinationColumnsUpdated();
    };
    /**
     * Add an animation to mark a destination as complete.
     */
    PlayerDestinations.prototype.markDestinationCompleteAnimation = function (destination, destinationRoutes) {
        var _this = this;
        var newDac = new DestinationCompleteAnimation(this.game, destination, destinationRoutes, "destination-card-" + destination.id, "destination-card-" + destination.id, {
            start: function (d) { return document.getElementById("destination-card-" + d.id).classList.add('hidden-for-animation'); },
            change: function (d) { return _this.markDestinationCompleteNoAnimation(d); },
            end: function (d) { return document.getElementById("destination-card-" + d.id).classList.remove('hidden-for-animation'); },
        }, 'completed');
        this.game.addAnimation(newDac);
    };
    /**
     * Mark a destination as complete.
     */
    PlayerDestinations.prototype.markDestinationComplete = function (destination, destinationRoutes) {
        if (destinationRoutes && !(document.visibilityState === 'hidden' || this.game.instantaneousMode)) {
            this.markDestinationCompleteAnimation(destination, destinationRoutes);
        }
        else {
            this.markDestinationCompleteNoAnimation(destination);
        }
    };
    /**
     * Highlight another destination.
     */
    PlayerDestinations.prototype.activateNextDestination = function (destinationList) {
        var _this = this;
        var oldSelectedDestination = this.selectedDestination;
        if (this.selectedDestination && destinationList.some(function (d) { return d.id == _this.selectedDestination.id; }) && destinationList.length > 1) {
            destinationList.splice.apply(destinationList, __spreadArray([destinationList.length, 0], destinationList.splice(0, 1), false));
        }
        this.selectedDestination = destinationList[0];
        this.game.setActiveDestination(this.selectedDestination, oldSelectedDestination);
        document.getElementById("player-table-" + this.playerId + "-destinations-todo").classList.toggle('front', destinationList == this.destinationsTodo);
        document.getElementById("player-table-" + this.playerId + "-destinations-done").classList.toggle('front', destinationList == this.destinationsDone);
        this.destinationColumnsUpdated();
    };
    /**
     * Update destination cards placement when there is a change.
     */
    PlayerDestinations.prototype.destinationColumnsUpdated = function () {
        var doubleColumn = this.destinationsTodo.length > 0 && this.destinationsDone.length > 0;
        var destinationsDiv = document.getElementById("player-table-" + this.playerId + "-destinations");
        var maxBottom = Math.max(this.placeCards(this.destinationsTodo, doubleColumn ? DESTINATION_CARD_SHIFT : 0), this.placeCards(this.destinationsDone));
        var height = maxBottom + CARD_HEIGHT + "px";
        destinationsDiv.style.height = height;
        document.getElementById("player-table-" + this.playerId + "-train-cars").style.height = height;
        this.game.setDestinationsToConnect(this.destinationsTodo);
    };
    /**
     * Place cards on a column.
     */
    PlayerDestinations.prototype.placeCards = function (list, originalBottom) {
        if (originalBottom === void 0) { originalBottom = 0; }
        var maxBottom = 0;
        list.forEach(function (destination, index) {
            var bottom = originalBottom + index * DESTINATION_CARD_SHIFT;
            var card = document.getElementById("destination-card-" + destination.id);
            card.parentElement.prepend(card);
            card.style.bottom = bottom + "px";
            if (bottom > maxBottom) {
                maxBottom = bottom;
            }
        });
        return maxBottom;
    };
    /**
     * Add an animation to to the card (when it is created).
     */
    PlayerDestinations.prototype.addAnimationFrom = function (card, from) {
        if (document.visibilityState === 'hidden' || this.game.instantaneousMode) {
            return;
        }
        var destinationBR = card.getBoundingClientRect();
        var originBR = from.getBoundingClientRect();
        var deltaX = destinationBR.left - originBR.left;
        var deltaY = destinationBR.top - originBR.top;
        card.style.zIndex = '10';
        card.style.transition = "transform 0.5s linear";
        var zoom = this.game.getZoom();
        card.style.transform = "translate(" + -deltaX / zoom + "px, " + -deltaY / zoom + "px)";
        setTimeout(function () { return card.style.transform = null; });
        setTimeout(function () {
            card.style.zIndex = null;
            card.style.transition = null;
        }, 500);
    };
    return PlayerDestinations;
}());
/**
 * Player's train car cards.
 */
var PlayerTrainCars = /** @class */ (function () {
    function PlayerTrainCars(game, player, trainCars) {
        this.game = game;
        this.routeId = null;
        this.playerId = Number(player.id);
        this.addTrainCars(trainCars);
        console.log('bind tempDiv !');
        var tempDiv = document.getElementById("player-table-" + player.id + "-train-cars");
        tempDiv.addEventListener('dragenter', function (e) {
            console.log('tempDiv dragenter', e);
        });
        tempDiv.addEventListener('dragover', function (e) {
            console.log('tempDiv dragover', e);
        });
        tempDiv.addEventListener('dragleave', function (e) {
            console.log('tempDiv dragleave', e);
        });
        tempDiv.addEventListener('drop', function (e) {
            console.log('tempDiv drop', e);
        });
    }
    /**
     * Add train cars to player's hand.
     */
    PlayerTrainCars.prototype.addTrainCars = function (trainCars, from) {
        var _this = this;
        trainCars.forEach(function (trainCar) {
            var group = _this.getGroup(trainCar.type);
            var xBackgroundPercent = trainCar.type * 100;
            var deg = Math.round(-4 + Math.random() * 8);
            var html = "\n            <div id=\"train-car-card-" + trainCar.id + "\" class=\"train-car-card\" style=\"background-position: -" + xBackgroundPercent + "% 50%; transform: rotate(" + deg + "deg);\"></div>\n            ";
            dojo.place(html, group.getElementsByClassName('train-car-cards')[0]);
            if (from) {
                var card = document.getElementById("train-car-card-" + trainCar.id);
                _this.addAnimationFrom(card, group, from);
            }
        });
        this.updateCounters();
    };
    /**
     * Set player table position.
     */
    PlayerTrainCars.prototype.setPosition = function (left) {
        this.left = left;
        var div = document.getElementById("player-table-" + this.playerId + "-train-cars");
        div.classList.toggle('left', left);
        this.updateCounters(); // to realign
    };
    /**
     * Remove train cars from player's hand.
     */
    PlayerTrainCars.prototype.removeCards = function (removeCards) {
        var _this = this;
        removeCards.forEach(function (card) {
            var div = document.getElementById("train-car-card-" + card.id);
            if (div) {
                var groupDiv = div.closest('.train-car-group');
                div.parentElement.removeChild(div);
                if (!groupDiv.getElementsByClassName('train-car-card').length) {
                    groupDiv.parentElement.removeChild(groupDiv);
                }
                _this.updateCounters();
            }
        });
    };
    /**
     * Set if train car cards can be dragged.
     */
    PlayerTrainCars.prototype.setDraggable = function (draggable) {
        var groups = Array.from(document.getElementsByClassName('train-car-group'));
        groups.forEach(function (groupDiv) { return groupDiv.setAttribute('draggable', draggable.toString()); });
    };
    /**
     * Return a group of cards (cards of the same color).
     * If it doesn't exists, create it.
     */
    PlayerTrainCars.prototype.getGroup = function (type) {
        var _this = this;
        var group = document.getElementById("train-car-group-" + type);
        if (!group) {
            dojo.place("\n            <div id=\"train-car-group-" + type + "\" class=\"train-car-group\" data-type=\"" + type + "\">\n                <div id=\"train-car-group-" + type + "-counter\" class=\"train-car-group-counter\">0</div>\n                <div id=\"train-car-group-" + type + "-cards\" class=\"train-car-cards\"></div>\n            </div>\n            ", "player-table-" + this.playerId + "-train-cars");
            this.updateCounters();
            group = document.getElementById("train-car-group-" + type);
            group.addEventListener('dragstart', function (e) {
                console.log('dragstart', e);
                var dt = e.dataTransfer;
                dt.effectAllowed = 'move';
                document.getElementById('map').dataset.dragColor = '' + type;
                // we generate a clone of group (without positionning with transform on the group)
                var groupClone = document.createElement('div');
                groupClone.classList.add('train-car-group', 'drag');
                groupClone.innerHTML = group.innerHTML;
                document.body.appendChild(groupClone);
                groupClone.offsetHeight;
                dt.setDragImage(groupClone, -10, -25);
                setTimeout(function () { return document.body.removeChild(groupClone); });
                //train-car-group-0
                setTimeout(function () {
                    group.classList.add('hide');
                }, 0);
            });
            group.addEventListener('dragend', function (e) {
                console.log('dragend', e);
                group.classList.remove('hide');
                document.getElementById('map').dataset.dragColor = '';
            });
            group.addEventListener('click', function () {
                if (_this.routeId) {
                    _this.game.claimRoute(_this.routeId, type);
                }
                else {
                    _this.game.showMessage(_("Drag the cards on the route you want to claim, or click on the route to claim"), 'info');
                }
            });
        }
        return group;
    };
    PlayerTrainCars.prototype.getGroups = function () {
        return Array.from(document.getElementsByClassName('train-car-group'));
    };
    /**
     * Update counters on color groups.
     */
    PlayerTrainCars.prototype.updateCounters = function () {
        var _this = this;
        var groups = this.getGroups();
        var middleIndex = (groups.length - 1) / 2;
        groups.forEach(function (groupDiv, index) {
            var distanceFromIndex = index - middleIndex;
            var count = groupDiv.getElementsByClassName('train-car-card').length;
            groupDiv.dataset.count = '' + count;
            groupDiv.getElementsByClassName('train-car-group-counter')[0].innerHTML = "" + (count > 1 ? count : '');
            var angle = distanceFromIndex * 4;
            groupDiv.dataset.angle = '' + angle;
            groupDiv.style.transform = "translate" + (_this.left ? 'X(-' : 'Y(') + Math.pow(Math.abs(distanceFromIndex) * 2, 2) + "px) rotate(" + angle + "deg)";
            groupDiv.parentNode.appendChild(groupDiv);
        });
    };
    /**
     * Add an animation to to the card (when it is created).
     */
    PlayerTrainCars.prototype.addAnimationFrom = function (card, group, from) {
        if (document.visibilityState === 'hidden' || this.game.instantaneousMode) {
            return;
        }
        var trainCars = document.getElementById("player-table-" + this.playerId + "-train-cars");
        trainCars.classList.add('new-card-animation');
        var destinationBR = card.getBoundingClientRect();
        var originBR = from.getBoundingClientRect();
        var deltaX = destinationBR.left - originBR.left;
        var deltaY = destinationBR.top - originBR.top;
        card.style.zIndex = '10';
        card.style.transition = "transform 0.5s linear";
        var zoom = this.game.getZoom();
        var angle = -Number(group.dataset.angle);
        card.style.transform = "rotate(" + (this.left ? angle : angle - 90) + "deg) translate(" + -deltaX / zoom + "px, " + -deltaY / zoom + "px)";
        setTimeout(function () { return card.style.transform = null; }, 0);
        setTimeout(function () {
            card.style.zIndex = null;
            card.style.transition = null;
            trainCars.classList.remove('new-card-animation');
        }, 500);
    };
    /**
     * Get the colors a player can use to claim a given route.
     */
    PlayerTrainCars.prototype.getPossibleColors = function (route) {
        var groups = this.getGroups();
        var locomotiveGroup = groups.find(function (groupDiv) { return groupDiv.dataset.type == '0'; });
        var locomotives = locomotiveGroup ? Number(locomotiveGroup.dataset.count) : 0;
        var possibleColors = [];
        groups.forEach(function (groupDiv) {
            var count = Number(groupDiv.dataset.count);
            if (count + locomotives >= route.spaces.length) {
                var color = Number(groupDiv.dataset.type);
                if (color > 0) {
                    possibleColors.push(color);
                }
            }
        });
        return possibleColors;
    };
    /**
     * Get the colors a player can use to claim a given route.
     */
    PlayerTrainCars.prototype.setSelectableTrainCarColors = function (routeId, possibleColors) {
        this.routeId = routeId;
        var groups = this.getGroups();
        groups.forEach(function (groupDiv) {
            if (routeId) {
                var color = Number(groupDiv.dataset.type);
                groupDiv.classList.toggle('disabled', color != 0 && !possibleColors.includes(color));
            }
            else {
                groupDiv.classList.remove('disabled');
            }
        });
    };
    return PlayerTrainCars;
}());
/**
 * End score board.
 * It will start empty, and notifications will update it and start animations one by one.
 */
var EndScore = /** @class */ (function () {
    function EndScore(game, players, 
    /** fromReload: if a player refresh when game is over, we skip animations (as there will be no notifications to animate the score board) */
    fromReload, 
    /** bestScore is the top score for the game, so progression shown as train moving forward is relative to best score */
    bestScore) {
        var _this = this;
        this.game = game;
        this.players = players;
        this.bestScore = bestScore;
        /** Player scores (key is player id) */
        this.scoreCounters = [];
        /** Unrevealed destinations counters (key is player id) */
        this.destinationCounters = [];
        /** Complete destinations counters (key is player id) */
        this.completedDestinationCounters = [];
        /** Uncomplete destinations counters (key is player id) */
        this.uncompletedDestinationCounters = [];
        players.forEach(function (player) {
            dojo.place("<tr id=\"score" + player.id + "\">\n                <td id=\"score-name-" + player.id + "\" class=\"player-name\" style=\"color: #" + player.color + "\">" + player.name + "</td>\n                <td id=\"destinations-score-" + player.id + "\" class=\"destinations\">\n                    <div class=\"icons-grid\">\n                        <div id=\"destination-counter-" + player.id + "\" class=\"icon destination-card\"></div>\n                        <div id=\"completed-destination-counter-" + player.id + "\" class=\"icon completed-destination\"></div>\n                        <div id=\"uncompleted-destination-counter-" + player.id + "\" class=\"icon uncompleted-destination\"></div>\n                    </div>\n                </td>\n                <td id=\"train-score-" + player.id + "\" class=\"train\">\n                    <div id=\"train-image-" + player.id + "\" class=\"train-image\" data-player-color=\"" + player.color + "\"></div>\n                </td>\n                <td id=\"end-score-" + player.id + "\" class=\"total\"></td>\n            </tr>", 'score-table-body');
            var destinationCounter = new ebg.counter();
            destinationCounter.create("destination-counter-" + player.id);
            destinationCounter.setValue(fromReload ? 0 : _this.game.destinationCardCounters[player.id].getValue());
            _this.destinationCounters[Number(player.id)] = destinationCounter;
            var completedDestinationCounter = new ebg.counter();
            completedDestinationCounter.create("completed-destination-counter-" + player.id);
            completedDestinationCounter.setValue(fromReload ? player.completedDestinations.length : 0);
            _this.completedDestinationCounters[Number(player.id)] = completedDestinationCounter;
            var uncompletedDestinationCounter = new ebg.counter();
            uncompletedDestinationCounter.create("uncompleted-destination-counter-" + player.id);
            uncompletedDestinationCounter.setValue(fromReload ? destinationCounter.getValue() - completedDestinationCounter.getValue() : 0);
            _this.uncompletedDestinationCounters[Number(player.id)] = uncompletedDestinationCounter;
            var scoreCounter = new ebg.counter();
            scoreCounter.create("end-score-" + player.id);
            scoreCounter.setValue(Number(player.score));
            _this.scoreCounters[Number(player.id)] = scoreCounter;
        });
        // if we are at reload of end state, we display values, else we wait for notifications
        if (fromReload) {
            var longestPath_1 = Math.max.apply(Math, players.map(function (player) { return player.longestPathLength; }));
            this.setBestScore(bestScore);
            players.forEach(function (player) {
                if (Number(player.score) == bestScore) {
                    _this.highlightWinnerScore(player.id);
                }
                if (player.longestPathLength == longestPath_1) {
                    _this.setLongestPathWinner(player.id, longestPath_1);
                }
            });
        }
    }
    /**
     * Add golden highlight to top score player(s)
     */
    EndScore.prototype.highlightWinnerScore = function (playerId) {
        document.getElementById("score" + playerId).classList.add('highlight');
        document.getElementById("score-name-" + playerId).style.color = '';
    };
    /**
     * Save best score so we can move trains.
     */
    EndScore.prototype.setBestScore = function (bestScore) {
        var _this = this;
        this.bestScore = bestScore;
        this.players.forEach(function (player) { return _this.moveTrain(Number(player.id)); });
    };
    /**
     * Set score, and animate train to new score.
     */
    EndScore.prototype.setPoints = function (playerId, points) {
        this.scoreCounters[playerId].toValue(points);
        this.moveTrain(playerId);
    };
    /**
     * Move train to represent score progression.
     */
    EndScore.prototype.moveTrain = function (playerId) {
        var scorePercent = 100 * this.scoreCounters[playerId].getValue() / Math.max(50, this.bestScore);
        document.getElementById("train-image-" + playerId).style.right = 100 - scorePercent + "%";
    };
    /**
     * Show score animation for a revealed destination.
     */
    EndScore.prototype.scoreDestination = function (playerId, destination, destinationRoutes) {
        var _this = this;
        var newDac = new DestinationCompleteAnimation(this.game, destination, destinationRoutes, "destination-counter-" + playerId, (destinationRoutes ? 'completed' : 'uncompleted') + "-destination-counter-" + playerId, {
            end: function () {
                (destinationRoutes ? _this.completedDestinationCounters : _this.uncompletedDestinationCounters)[playerId].incValue(1);
                _this.destinationCounters[playerId].incValue(-1);
                if (_this.destinationCounters[playerId].getValue() == 0) {
                    document.getElementById("destination-counter-" + playerId).classList.add('hidden');
                }
            },
        }, destinationRoutes ? 'completed' : 'uncompleted', 0.15 / this.game.getZoom());
        this.game.addAnimation(newDac);
    };
    /**
     * Show longest path animation for a player.
     */
    EndScore.prototype.showLongestPath = function (playerColor, routes, length) {
        var newDac = new LongestPathAnimation(this.game, routes, length, playerColor);
        this.game.addAnimation(newDac);
    };
    /**
     * Add longest path badge to the longest path winner(s).
     */
    EndScore.prototype.setLongestPathWinner = function (playerId, length) {
        dojo.place("<div id=\"longest-path-bonus-card-" + playerId + "\" class=\"longest-path bonus-card bonus-card-icon\"></div>", "score-name-" + playerId);
        this.game.addTooltipHtml("longest-path-bonus-card-" + playerId, "\n        <div><strong>" + _('Longest path') + " : " + length + "</strong></div>\n        <div>The player who has the Longest Continuous Path of routes receives this special bonus card and adds 10 points to his score.</div>\n        <div class=\"longest-path bonus-card\"></div>\n        ");
    };
    return EndScore;
}());
var ANIMATION_MS = 500;
var SCORE_MS = 1500;
var isDebug = window.location.host == 'studio.boardgamearena.com';
var log = isDebug ? console.log.bind(window.console) : function () { };
var TicketToRide = /** @class */ (function () {
    function TicketToRide() {
        this.playerTable = null;
        this.trainCarCounters = [];
        this.trainCarCardCounters = [];
        this.destinationCardCounters = [];
        this.animations = [];
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
        var _this = this;
        // ignore loading of some pictures
        this.dontPreloadImage('publisher.png');
        log("Starting game setup");
        this.gamedatas = gamedatas;
        log('gamedatas', gamedatas);
        this.map = new TtrMap(this, Object.values(gamedatas.players), gamedatas.claimedRoutes);
        this.trainCarSelection = new TrainCarSelection(this, gamedatas.visibleTrainCards, gamedatas.trainCarDeckCount, gamedatas.destinationDeckCount, gamedatas.trainCarDeckMaxCount, gamedatas.destinationDeckMaxCount);
        this.destinationSelection = new DestinationSelection(this);
        var player = gamedatas.players[this.getPlayerId()];
        if (player) {
            this.playerTable = new PlayerTable(this, player, gamedatas.handTrainCars, gamedatas.handDestinations, gamedatas.completedDestinations);
        }
        this.createPlayerPanels(gamedatas);
        if (gamedatas.lastTurn) {
            this.notif_lastTurn();
        }
        if (Number(gamedatas.gamestate.id) >= 90) { // score or end
            this.onEnteringEndScore(true);
        }
        this.setupNotifications();
        this.setupPreferences();
        this.onScreenWidthChange = function () { return _this.map.setAutoZoom(); };
        log("Ending game setup");
    };
    ///////////////////////////////////////////////////
    //// Game & client states
    // onEnteringState: this method is called each time we are entering into a new game state.
    //                  You can use this method to perform some user interface changes at this moment.
    //
    TicketToRide.prototype.onEnteringState = function (stateName, args) {
        var _this = this;
        var _a;
        log('Entering state: ' + stateName, args.args);
        switch (stateName) {
            case 'chooseInitialDestinations':
            case 'chooseAdditionalDestinations':
                var chooseDestinationsArgs = args.args;
                (_a = chooseDestinationsArgs._private) === null || _a === void 0 ? void 0 : _a.destinations.forEach(function (destination) { return _this.map.setSelectableDestination(destination, true); });
                break;
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
    /**
     * Show selectable routes, and make train car draggable.
     */
    TicketToRide.prototype.onEnteringChooseAction = function (args) {
        var _a;
        this.trainCarSelection.setSelectableTopDeck(this.isCurrentPlayerActive(), args.maxHiddenCardsPick);
        this.map.setSelectableRoutes(this.isCurrentPlayerActive(), args.possibleRoutes);
        (_a = this.playerTable) === null || _a === void 0 ? void 0 : _a.setDraggable(this.isCurrentPlayerActive());
    };
    /**
     * Allow to pick a second card (locomotives will be grayed).
     */
    TicketToRide.prototype.onEnteringDrawSecondCard = function (args) {
        this.trainCarSelection.setSelectableTopDeck(this.isCurrentPlayerActive(), args.maxHiddenCardsPick);
        this.trainCarSelection.setSelectableVisibleCards(args.availableVisibleCards);
    };
    /**
     * Show score board.
     */
    TicketToRide.prototype.onEnteringEndScore = function (fromReload) {
        if (fromReload === void 0) { fromReload = false; }
        var lastTurnBar = document.getElementById('last-round');
        if (lastTurnBar) {
            lastTurnBar.style.display = 'none';
        }
        document.getElementById('score').style.display = 'flex';
        this.endScore = new EndScore(this, Object.values(this.gamedatas.players), fromReload, this.gamedatas.bestScore);
    };
    // onLeavingState: this method is called each time we are leaving a game state.
    //                 You can use this method to perform some user interface changes at this moment.
    //
    TicketToRide.prototype.onLeavingState = function (stateName) {
        var _a, _b;
        log('Leaving state: ' + stateName);
        switch (stateName) {
            case 'chooseInitialDestinations':
            case 'chooseAdditionalDestinations':
                this.destinationSelection.hide();
                var mapDiv = document.getElementById('map');
                mapDiv.querySelectorAll(".city[data-selectable]").forEach(function (city) { return city.dataset.selectable = 'false'; });
                mapDiv.querySelectorAll(".city[data-selected]").forEach(function (city) { return city.dataset.selected = 'false'; });
                break;
            case 'chooseAction':
                this.map.setSelectableRoutes(false, []);
                (_a = this.playerTable) === null || _a === void 0 ? void 0 : _a.setDraggable(false);
                (_b = this.playerTable) === null || _b === void 0 ? void 0 : _b.setSelectableTrainCarColors(null);
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
                    this.destinationSelection.setCards(chooseInitialDestinationsArgs._private.destinations, chooseInitialDestinationsArgs.minimum, this.trainCarSelection.getVisibleColors());
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
                    this.destinationSelection.setCards(chooseAdditionalDestinationsArgs._private.destinations, chooseAdditionalDestinationsArgs.minimum, this.trainCarSelection.getVisibleColors());
                    break;
            }
        }
    };
    ///////////////////////////////////////////////////
    //// Utility methods
    ///////////////////////////////////////////////////
    /**
     * Handle user preferences changes.
     */
    TicketToRide.prototype.setupPreferences = function () {
        var _this = this;
        // Extract the ID and value from the UI control
        var onchange = function (e) {
            var match = e.target.id.match(/^preference_control_(\d+)$/);
            if (!match) {
                return;
            }
            var prefId = +match[1];
            var prefValue = +e.target.value;
            _this.prefs[prefId].value = prefValue;
            _this.onPreferenceChange(prefId, prefValue);
        };
        // Call onPreferenceChange() when any value changes
        dojo.query(".preference_control").connect("onchange", onchange);
        // Call onPreferenceChange() now
        dojo.forEach(dojo.query("#ingame_menu_content .preference_control"), function (el) { return onchange({ target: el }); });
    };
    /**
     * Handle user preferences changes.
     */
    TicketToRide.prototype.onPreferenceChange = function (prefId, prefValue) {
        switch (prefId) {
            case 201: // 1 = buttons, 2 = double click to pick 2 cards
                dojo.toggleClass('train-car-deck-hidden-pile', 'buttonselection', prefValue == 1);
                break;
        }
    };
    TicketToRide.prototype.getPlayerId = function () {
        return Number(this.player_id);
    };
    /**
     * Place counters on player panels.
     */
    TicketToRide.prototype.createPlayerPanels = function (gamedatas) {
        var _this = this;
        Object.values(gamedatas.players).forEach(function (player) {
            var playerId = Number(player.id);
            document.getElementById("overall_player_board_" + player.id).dataset.playerColor = player.color;
            // public counters
            dojo.place("<div class=\"counters\">\n                <div id=\"train-car-counter-" + player.id + "-wrapper\" class=\"counter train-car-counter\">\n                    <div class=\"icon train\" data-player-color=\"" + player.color + "\"></div> \n                    <span id=\"train-car-counter-" + player.id + "\"></span>\n                </div>\n                <div id=\"train-car-card-counter-" + player.id + "-wrapper\" class=\"counter train-car-card-counter\">\n                    <div class=\"icon train-car-card\"></div> \n                    <span id=\"train-car-card-counter-" + player.id + "\"></span>\n                </div>\n                <div id=\"destinations-counter-" + player.id + "-wrapper\" class=\"counter destinations-counter\">\n                    <div class=\"icon destination-card\"></div> \n                    <span id=\"completed-destinations-counter-" + player.id + "\">" + (_this.getPlayerId() !== playerId ? '?' : '') + "</span>/<span id=\"destination-card-counter-" + player.id + "\"></span>\n                </div>\n            </div>", "player_board_" + player.id);
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
        });
        this.addTooltipHtmlToClass('train-car-counter', _("Remaining train cars"));
        this.addTooltipHtmlToClass('train-car-card-counter', _("Train cars cards"));
        this.addTooltipHtmlToClass('destinations-counter', _("Completed / Total destination cards"));
    };
    /**
     * Update player score.
     */
    TicketToRide.prototype.setPoints = function (playerId, points) {
        var _a;
        (_a = this.scoreCtrl[playerId]) === null || _a === void 0 ? void 0 : _a.toValue(points);
    };
    /**
     * Highlight active destination.
     */
    TicketToRide.prototype.setActiveDestination = function (destination, previousDestination) {
        if (previousDestination === void 0) { previousDestination = null; }
        this.map.setActiveDestination(destination, previousDestination);
    };
    /**
     * Check if a route can be claimed with dragged cards.
     */
    TicketToRide.prototype.canClaimRoute = function (route, cardsColor) {
        return (route.color == 0 || cardsColor == 0 || route.color == cardsColor) && (this.gamedatas.gamestate.args.possibleRoutes.some(function (pr) { return pr.id == route.id; }));
    };
    /**
     * Highlight destination (on destination mouse over).
     */
    TicketToRide.prototype.setHighligthedDestination = function (destination) {
        this.map.setHighligthedDestination(destination);
    };
    /**
     * Highlight cities of selected destination.
     */
    TicketToRide.prototype.setSelectedDestination = function (destination, visible) {
        this.map.setSelectedDestination(destination, visible);
    };
    /**
     * Highlight cities player must connect for its objectives.
     */
    TicketToRide.prototype.setDestinationsToConnect = function (destinations) {
        this.map.setDestinationsToConnect(destinations);
    };
    /**
     * Place player table to the left or the bottom of the map.
     */
    TicketToRide.prototype.setPlayerTablePosition = function (left) {
        var _a;
        (_a = this.playerTable) === null || _a === void 0 ? void 0 : _a.setPosition(left);
    };
    /**
     * Get current zoom.
     */
    TicketToRide.prototype.getZoom = function () {
        return this.map.getZoom();
    };
    /**
     * Get player color (hex without #).
     */
    TicketToRide.prototype.getPlayerColor = function () {
        var _a;
        return (_a = this.gamedatas.players[this.getPlayerId()]) === null || _a === void 0 ? void 0 : _a.color;
    };
    /**
     * Add an animation to the animation queue, and start it if there is no current animations.
     */
    TicketToRide.prototype.addAnimation = function (animation) {
        this.animations.push(animation);
        if (this.animations.length === 1) {
            this.animations[0].animate();
        }
        ;
    };
    /**
     * Start the next animation in animation queue.
     */
    TicketToRide.prototype.endAnimation = function (ended) {
        var index = this.animations.indexOf(ended);
        if (index !== -1) {
            this.animations.splice(index, 1);
        }
        if (this.animations.length >= 1) {
            this.animations[0].animate();
        }
        ;
    };
    /**
     * Handle route click.
     */
    TicketToRide.prototype.clickedRoute = function (route) {
        var _this = this;
        var _a;
        if (!this.isCurrentPlayerActive() || !this.canClaimRoute(route, 0)) {
            return;
        }
        document.querySelectorAll("[id^=\"claimRouteWithColor_button\"]").forEach(function (button) { return button.parentElement.removeChild(button); });
        if (route.color > 0) {
            this.claimRoute(route.id, route.color);
        }
        else {
            var possibleColors = ((_a = this.playerTable) === null || _a === void 0 ? void 0 : _a.getPossibleColors(route)) || [];
            if (possibleColors.length == 1) {
                this.claimRoute(route.id, possibleColors[0]);
            }
            else if (possibleColors.length > 1) {
                possibleColors.forEach(function (color) {
                    var label = dojo.string.substitute(_("Use ${color}"), {
                        'color': "<div class=\"train-car-color icon\" data-color=\"" + color + "\"></div> " + getColor(color)
                    });
                    _this.addActionButton("claimRouteWithColor_button" + color, label, function () { return _this.claimRoute(route.id, color); });
                });
                this.playerTable.setSelectableTrainCarColors(route.id, possibleColors);
            }
        }
    };
    /**
     * Apply destination selection (initial objectives).
     */
    TicketToRide.prototype.chooseInitialDestinations = function () {
        if (!this.checkAction('chooseInitialDestinations')) {
            return;
        }
        var destinationsIds = this.destinationSelection.getSelectedDestinationsIds();
        this.takeAction('chooseInitialDestinations', {
            destinationsIds: destinationsIds.join(',')
        });
    };
    /**
     * Pick destinations.
     */
    TicketToRide.prototype.drawDestinations = function () {
        if (!this.checkAction('drawDestinations')) {
            return;
        }
        this.takeAction('drawDestinations');
    };
    /**
     * Apply destination selection (additional objectives).
     */
    TicketToRide.prototype.chooseAdditionalDestinations = function () {
        if (!this.checkAction('chooseAdditionalDestinations')) {
            return;
        }
        var destinationsIds = this.destinationSelection.getSelectedDestinationsIds();
        this.takeAction('chooseAdditionalDestinations', {
            destinationsIds: destinationsIds.join(',')
        });
    };
    /**
     * Pick hidden train car(s).
     */
    TicketToRide.prototype.onHiddenTrainCarDeckClick = function (number) {
        var action = this.gamedatas.gamestate.name === 'drawSecondCard' ? 'drawSecondDeckCard' : 'drawDeckCards';
        if (!this.checkAction(action)) {
            return;
        }
        this.takeAction(action, {
            number: number
        });
    };
    /**
     * Pick visible train car.
     */
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
    /**
     * Claim a route.
     */
    TicketToRide.prototype.claimRoute = function (routeId, color) {
        if (!this.checkAction('claimRoute')) {
            return;
        }
        this.takeAction('claimRoute', {
            routeId: routeId,
            color: color
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
            ['trainCarPicked', ANIMATION_MS],
            ['lastTurn', 1],
            ['bestScore', 1],
            ['scoreDestination', 2000],
            ['longestPath', 2000],
            ['longestPathWinner', 1500],
            ['highlightWinnerScore', 1],
        ];
        notifs.forEach(function (notif) {
            dojo.subscribe(notif[0], _this, "notif_" + notif[0]);
            _this.notifqueue.setSynchronous(notif[0], notif[1]);
        });
    };
    /**
     * Update player score.
     */
    TicketToRide.prototype.notif_points = function (notif) {
        var _a;
        this.setPoints(notif.args.playerId, notif.args.points);
        (_a = this.endScore) === null || _a === void 0 ? void 0 : _a.setPoints(notif.args.playerId, notif.args.points);
    };
    /**
     * Update player destinations.
     */
    TicketToRide.prototype.notif_destinationsPicked = function (notif) {
        var _a, _b;
        this.destinationCardCounters[notif.args.playerId].incValue(notif.args.number);
        var destinations = (_b = (_a = notif.args._private) === null || _a === void 0 ? void 0 : _a[this.getPlayerId()]) === null || _b === void 0 ? void 0 : _b.destinations;
        if (destinations) {
            this.playerTable.addDestinations(destinations, this.destinationSelection.destinations);
        }
        else {
            this.trainCarSelection.moveDestinationCardToPlayerBoard(notif.args.playerId, notif.args.number);
        }
        this.trainCarSelection.setDestinationCount(notif.args.remainingDestinationsInDeck);
    };
    /**
     * Update player train cars.
     */
    TicketToRide.prototype.notif_trainCarPicked = function (notif) {
        var _a, _b, _c;
        this.trainCarCardCounters[notif.args.playerId].incValue(notif.args.number);
        if (notif.args.playerId == this.getPlayerId()) {
            var cards = notif.args.cards || ((_b = (_a = notif.args._private) === null || _a === void 0 ? void 0 : _a[this.getPlayerId()]) === null || _b === void 0 ? void 0 : _b.cards);
            this.playerTable.addTrainCars(cards, this.trainCarSelection.getStockElement(notif.args.from));
        }
        else {
            this.trainCarSelection.moveTrainCarCardToPlayerBoard(notif.args.playerId, notif.args.from, (_c = notif.args.cards) === null || _c === void 0 ? void 0 : _c[0].type, notif.args.number);
        }
        this.trainCarSelection.setTrainCarCount(notif.args.remainingTrainCarsInDeck);
    };
    /**
     * Update visible cards.
     */
    TicketToRide.prototype.notif_newCardsOnTable = function (notif) {
        this.trainCarSelection.setNewCardsOnTable(notif.args.cards);
    };
    /**
     * Update claimed routes.
     */
    TicketToRide.prototype.notif_claimedRoute = function (notif) {
        var playerId = notif.args.playerId;
        var route = notif.args.route;
        this.trainCarCardCounters[playerId].incValue(-route.number);
        this.trainCarCounters[playerId].incValue(-route.number);
        this.map.setClaimedRoutes([{
                playerId: playerId,
                routeId: route.id
            }], playerId);
        if (playerId == this.getPlayerId()) {
            this.playerTable.removeCards(notif.args.removeCards);
        }
    };
    /**
     * Mark a destination as complete.
     */
    TicketToRide.prototype.notif_destinationCompleted = function (notif) {
        var destination = notif.args.destination;
        this.completedDestinationsCounter.incValue(1);
        this.gamedatas.completedDestinations.push(destination);
        this.playerTable.markDestinationComplete(destination, notif.args.destinationRoutes);
    };
    /**
     * Show last turn banner.
     */
    TicketToRide.prototype.notif_lastTurn = function () {
        dojo.place("<div id=\"last-round\">\n            " + _("This is the final round!") + "\n        </div>", 'page-title');
    };
    /**
     * Save best score for end score animations.
     */
    TicketToRide.prototype.notif_bestScore = function (notif) {
        var _a;
        this.gamedatas.bestScore = notif.args.bestScore;
        (_a = this.endScore) === null || _a === void 0 ? void 0 : _a.setBestScore(notif.args.bestScore);
    };
    /**
     * Animate a destination for end score.
     */
    TicketToRide.prototype.notif_scoreDestination = function (notif) {
        var _a, _b;
        (_a = this.endScore) === null || _a === void 0 ? void 0 : _a.scoreDestination(notif.args.playerId, notif.args.destination, notif.args.destinationRoutes);
        if (!notif.args.destinationRoutes) {
            (_b = document.getElementById("destination-card-" + notif.args.destination.id)) === null || _b === void 0 ? void 0 : _b.classList.add('uncompleted');
        }
    };
    /**
     * Animate longest path for end score.
     */
    TicketToRide.prototype.notif_longestPath = function (notif) {
        var _a;
        (_a = this.endScore) === null || _a === void 0 ? void 0 : _a.showLongestPath(this.gamedatas.players[notif.args.playerId].color, notif.args.routes, notif.args.length);
    };
    /**
     * Add longest path badge for end score.
     */
    TicketToRide.prototype.notif_longestPathWinner = function (notif) {
        var _a;
        (_a = this.endScore) === null || _a === void 0 ? void 0 : _a.setLongestPathWinner(notif.args.playerId, notif.args.length);
    };
    /**
     * Highlight winner for end score.
     */
    TicketToRide.prototype.notif_highlightWinnerScore = function (notif) {
        var _a;
        (_a = this.endScore) === null || _a === void 0 ? void 0 : _a.highlightWinnerScore(notif.args.playerId);
    };
    /* This enable to inject translatable styled things to logs or action bar */
    /* @Override */
    TicketToRide.prototype.format_string_recursive = function (log, args) {
        try {
            if (log && args && !args.processed) {
                // make cities names in bold 
                if (typeof args.from == 'string' && args.from[0] != '<') {
                    args.from = "<strong>" + args.from + "</strong>";
                }
                if (typeof args.to == 'string' && args.to[0] != '<') {
                    args.to = "<strong>" + args.to + "</strong>";
                }
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
