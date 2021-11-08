/*declare const define;
declare const ebg;
declare const $;
declare const dojo: Dojo;
declare const _;
declare const g_gamethemeurl;

declare const board: HTMLDivElement;*/
var CARD_WIDTH = 150;
var CARD_HEIGHT = 100;
function setupTrainCarCards(stock) {
    var trainCarsUrl = g_gamethemeurl + "img/train-cards.jpg";
    for (var type = 0; type <= 8; type++) {
        stock.addItemType(type, type, trainCarsUrl, type);
    }
}
function setupDestinationCards(stock) {
    var destinationsUrl = g_gamethemeurl + "img/destinations.jpg";
    for (var id = 1; id <= 36; id++) {
        stock.addItemType(id, id, destinationsUrl, id);
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
    var color = COLORS[Number(cardTypeId)];
    cardDiv.innerHTML = "<span><strong>" + color + "</span></strong>";
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
    new DestinationCard(21, 25, 23, 11),
    new DestinationCard(22, 30, 1, 17),
    new DestinationCard(23, 29, 18, 8),
    new DestinationCard(24, 29, 21, 9),
    new DestinationCard(25, 32, 15, 9),
    new DestinationCard(26, 32, 20, 22),
    new DestinationCard(27, 33, 16, 10),
    new DestinationCard(28, 34, 17, 20),
    new DestinationCard(29, 34, 31, 13),
    new DestinationCard(30, 36, 11, 12),
    new DestinationCard(31, 36, 14, 11), // Winnipeg	Little Rock	11
];
function setupDestinationCardDiv(cardDiv, cardTypeId) {
    var destination = DESTINATIONS[Number(cardTypeId)];
    cardDiv.innerHTML = "<span><strong>" + CITIES[destination.from] + "</strong> to <strong>" + CITIES[destination.to] + "</strong> (<strong>" + destination.points + "</strong>)</span>";
}
var POINT_CASE_SIZE = 25.5;
var BOARD_POINTS_MARGIN = 38;
var Route = /** @class */ (function () {
    function Route(id, from, to, number, color) {
        this.id = id;
        this.from = from;
        this.to = to;
        this.number = number;
        this.color = color;
    }
    return Route;
}());
var ROUTES = [
    new Route(1, 1, 4, 2, GRAY),
    new Route(2, 1, 16, 5, BLUE),
    new Route(3, 1, 18, 1, GRAY),
    new Route(4, 1, 19, 4, YELLOW),
    new Route(5, 1, 19, 4, ORANGE),
    new Route(6, 1, 26, 2, GRAY),
    new Route(7, 1, 26, 2, GRAY),
    new Route(8, 2, 17, 2, GRAY),
    new Route(9, 2, 17, 2, GRAY),
    new Route(10, 2, 20, 2, YELLOW),
    new Route(11, 2, 20, 2, RED),
    new Route(12, 3, 10, 4, GRAY),
    new Route(12, 3, 32, 4, GRAY),
    new Route(13, 3, 34, 3, GRAY),
    new Route(14, 3, 36, 6, WHITE),
    new Route(15, 4, 16, 4, PINK),
    new Route(16, 4, 26, 5, GRAY),
    new Route(17, 5, 8, 3, RED),
    new Route(18, 5, 22, 4, BLUE),
    new Route(19, 5, 24, 3, ORANGE),
    new Route(20, 5, 24, 3, BLACK),
    new Route(21, 5, 27, 2, GREEN),
    new Route(22, 5, 27, 2, WHITE),
    new Route(23, 5, 33, 4, WHITE),
    new Route(24, 6, 9, 4, RED),
    new Route(25, 6, 11, 1, GRAY),
    new Route(26, 6, 11, 1, GRAY),
    new Route(27, 6, 14, 2, GRAY),
    new Route(28, 6, 21, 2, GRAY),
    new Route(29, 6, 21, 2, GRAY),
    new Route(30, 7, 10, 4, GREEN),
    new Route(31, 7, 12, 4, BLACK),
    new Route(32, 7, 12, 4, ORANGE),
    new Route(33, 7, 21, 4, RED),
    new Route(34, 7, 22, 4, PINK),
    new Route(35, 7, 23, 5, WHITE),
    new Route(36, 7, 28, 3, RED),
    new Route(37, 7, 28, 3, YELLOW),
    new Route(38, 8, 10, 6, ORANGE),
    new Route(39, 8, 22, 2, GRAY),
    new Route(40, 8, 22, 2, GRAY),
    new Route(41, 8, 29, 3, GRAY),
    new Route(42, 8, 33, 6, PINK),
    new Route(43, 8, 36, 4, BLACK),
    new Route(44, 9, 11, 6, GREEN),
    new Route(45, 9, 15, 6, BLACK),
    new Route(46, 9, 21, 5, YELLOW),
    new Route(47, 9, 23, 3, GRAY),
    new Route(48, 9, 31, 2, GRAY),
    new Route(49, 10, 22, 5, RED),
    new Route(50, 10, 28, 3, PINK),
    new Route(51, 10, 32, 6, YELLOW),
    new Route(52, 10, 36, 4, BLUE),
    new Route(53, 11, 19, 2, GRAY),
    new Route(54, 12, 21, 2, GRAY),
    new Route(55, 12, 21, 2, GRAY),
    new Route(56, 12, 22, 1, GRAY),
    new Route(57, 12, 22, 1, GRAY),
    new Route(58, 12, 27, 2, BLUE),
    new Route(59, 12, 27, 2, PINK),
    new Route(60, 13, 15, 2, GRAY),
    new Route(61, 13, 28, 3, ORANGE),
    new Route(62, 14, 18, 3, WHITE),
    new Route(63, 14, 19, 3, GREEN),
    new Route(64, 14, 21, 2, GRAY),
    new Route(65, 14, 27, 2, GRAY),
    new Route(66, 15, 23, 3, GRAY),
    new Route(67, 15, 30, 3, YELLOW),
    new Route(68, 15, 30, 3, PINK),
    new Route(69, 16, 19, 6, RED),
    new Route(70, 17, 20, 3, BLUE),
    new Route(71, 17, 29, 5, BLACK),
    new Route(72, 17, 33, 3, GRAY),
    new Route(73, 18, 24, 3, BLACK),
    new Route(74, 18, 26, 4, YELLOW),
    new Route(75, 18, 27, 2, GRAY),
    new Route(76, 20, 24, 2, WHITE),
    new Route(77, 20, 24, 2, GREEN),
    new Route(78, 20, 35, 2, ORANGE),
    new Route(79, 20, 35, 2, BLACK),
    new Route(80, 21, 31, 3, BLUE),
    new Route(81, 23, 31, 3, GRAY),
    new Route(82, 24, 26, 2, GRAY),
    new Route(83, 24, 27, 5, GREEN),
    new Route(84, 24, 33, 2, GRAY),
    new Route(85, 24, 35, 2, GRAY),
    new Route(86, 25, 28, 6, BLUE),
    new Route(87, 25, 30, 5, GREEN),
    new Route(88, 25, 30, 5, PINK),
    new Route(89, 25, 32, 1, GRAY),
    new Route(90, 25, 32, 1, GRAY),
    new Route(91, 26, 35, 2, GRAY),
    new Route(92, 26, 35, 2, GRAY),
    new Route(93, 28, 30, 5, ORANGE),
    new Route(94, 28, 30, 5, WHITE),
    new Route(95, 29, 33, 2, GRAY),
    new Route(96, 29, 36, 6, GRAY),
    new Route(97, 32, 34, 1, GRAY),
    new Route(98, 32, 34, 1, GRAY),
];
var TtrMap = /** @class */ (function () {
    function TtrMap(game, players) {
        var _this = this;
        this.game = game;
        this.players = players;
        this.points = new Map();
        var html = '';
        // points
        players.forEach(function (player) {
            html += "<div id=\"player-" + player.id + "-point-marker\" class=\"point-marker " + (_this.game.isColorBlindMode() ? 'color-blind' : '') + "\" data-player-no=\"" + player.playerNo + "\" style=\"background: #" + player.color + ";\"></div>";
            _this.points.set(Number(player.id), Number(player.score));
        });
        dojo.place(html, 'board');
        ROUTES.forEach(function (route) {
            dojo.place("<div id=\"route" + route.id + "\" class=\"route\">" + CITIES[route.from] + " to " + CITIES[route.to] + ", " + route.number + " " + COLORS[route.color] + "</div>", 'board');
            document.getElementById("route" + route.id).addEventListener('click', function () { return _this.game.claimRoute(route.id); });
        });
        this.movePoints();
    }
    TtrMap.prototype.setPoints = function (playerId, points) {
        this.points.set(playerId, points);
        this.movePoints();
    };
    TtrMap.prototype.setSelectableRoutes = function (selectable, possibleRoutes) {
        if (selectable) {
            possibleRoutes.forEach(function (route) { return document.getElementById("route" + route.id).classList.add('selectable'); });
        }
        else {
            dojo.query('.route').removeClass('selectable');
        }
    };
    TtrMap.prototype.getPointsCoordinates = function (points) {
        var top = points < 86 ? Math.min(Math.max(points - 34, 0), 17) * POINT_CASE_SIZE : (102 - points) * POINT_CASE_SIZE;
        var left = points < 52 ? Math.min(points, 34) * POINT_CASE_SIZE : (33 - Math.max(points - 52, 0)) * POINT_CASE_SIZE;
        return [17 + left, 15 + top];
    };
    TtrMap.prototype.movePoints = function () {
        var _this = this;
        this.points.forEach(function (points, playerId) {
            var markerDiv = document.getElementById("player-" + playerId + "-point-marker");
            var coordinates = _this.getPointsCoordinates(points);
            var left = coordinates[0];
            var top = coordinates[1];
            var topShift = 0;
            var leftShift = 0;
            _this.points.forEach(function (iPoints, iPlayerId) {
                if (iPoints === points && iPlayerId < playerId) {
                    topShift += 5;
                    leftShift += 5;
                }
            });
            markerDiv.style.transform = "translateX(" + (left + leftShift) + "px) translateY(" + (top + topShift) + "px)";
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
        this.destinations.image_items_per_row = 13;
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
        destinations.forEach(function (card) { return _this.destinations.addToStockWithId(card.id, '' + card.id); });
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
        document.getElementById('train-car-deck-hidden-pile1').addEventListener('click', function () { return _this.game.onHiddenTrainCarDeckClick(1); });
        document.getElementById('train-car-deck-hidden-pile2').addEventListener('click', function () { return _this.game.onHiddenTrainCarDeckClick(2); });
        this.visibleCardsStock = new ebg.stock();
        this.visibleCardsStock.setSelectionAppearance('class');
        this.visibleCardsStock.selectionClass = 'no-class-selection';
        this.visibleCardsStock.setSelectionMode(1);
        this.visibleCardsStock.create(game, $("visible-train-cards-stock"), CARD_WIDTH, CARD_HEIGHT);
        this.visibleCardsStock.onItemCreate = function (cardDiv, cardTypeId) { return setupTrainCarCardDiv(cardDiv, cardTypeId); };
        this.visibleCardsStock.image_items_per_row = 13;
        this.visibleCardsStock.centerItems = true;
        dojo.connect(this.visibleCardsStock, 'onChangeSelection', this, function (_, itemId) { return _this.game.onVisibleTrainCarCardClick(Number(itemId)); });
        setupTrainCarCards(this.visibleCardsStock);
        visibleCards.forEach(function (card) { return _this.visibleCardsStock.addToStockWithId(card.type, '' + card.id); });
    }
    TrainCarSelection.prototype.setSelectableTopDeck = function (selectable, number) {
        if (number === void 0) { number = 0; }
        dojo.toggleClass('train-car-deck-hidden-pile', 'selectable', selectable);
        dojo.toggleClass('train-car-deck-hidden-pile1', 'hidden', number < 1);
        dojo.toggleClass('train-car-deck-hidden-pile2', 'hidden', number < 2);
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
        trainCars.forEach(function (trainCar) { return _this.trainCarStock.addToStockWithId(trainCar.type, '' + trainCar.id); });
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
    PlayerTable.prototype.addDestinations = function (destinations) {
        var _this = this;
        destinations.forEach(function (destination) { return _this.destinationStock.addToStockWithId(destination.type_arg, '' + destination.id); }, 'destination-stock');
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
        this.map = new TtrMap(this, Object.values(gamedatas.players));
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
            case 'endScore':
                this.onEnteringEndScore();
                break;
        }
    };
    TicketToRide.prototype.onEnteringChooseAction = function (args) {
        this.trainCarSelection.setSelectableTopDeck(this.isCurrentPlayerActive(), args.maxHiddenCardsPick);
        this.map.setSelectableRoutes(this.isCurrentPlayerActive(), args.possibleRoutes);
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
    TicketToRide.prototype.onVisibleTrainCarCardClick = function (id) {
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
            //['factoriesFilled', ANIMATION_MS],
            ['points', 1],
            ['destinationsPicked', 1],
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
        var _a;
        this.destinationCardCounters[notif.args.playerId].incValue(notif.args.number);
        if ((_a = notif.args._private) === null || _a === void 0 ? void 0 : _a.destinations) {
            this.playerTable.addDestinations(notif.args._private.destinations);
        }
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
