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
    for (var id = 0; id <= 8; id++) {
        stock.addItemType(id, id, trainCarsUrl, id);
    }
}
function setupDestinationCards(stock) {
    var destinationsUrl = g_gamethemeurl + "img/destinations.jpg";
    for (var id = 1; id <= 36; id++) {
        stock.addItemType(id, id, destinationsUrl, id);
    }
}
var TtrMap = /** @class */ (function () {
    function TtrMap(game) {
        var _this = this;
        this.game = game;
        document.getElementById('board').addEventListener('click', function (e) { return _this.game.claimRoute(Math.floor(e.x / 10)); });
    }
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
        //this.cards.onItemCreate = (card_div, card_type_id) => this.game.cards.setupNewCard(card_div, card_type_id);
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
var TrainSelectionSelection = /** @class */ (function () {
    function TrainSelectionSelection(game, visibleCards) {
        var _this = this;
        this.game = game;
        document.getElementById('drawDeckCards1').addEventListener('click', function () { return _this.game.onHiddenTrainCarDeckClick(1); });
        document.getElementById('drawDeckCards2').addEventListener('click', function () { return _this.game.onHiddenTrainCarDeckClick(2); });
        this.visibleCardsStock = new ebg.stock();
        this.visibleCardsStock.setSelectionAppearance('class');
        this.visibleCardsStock.selectionClass = 'no-class-selection';
        this.visibleCardsStock.setSelectionMode(1);
        this.visibleCardsStock.create(game, $("visible-train-cards-stock"), CARD_WIDTH, CARD_HEIGHT);
        //this.cards.onItemCreate = (card_div, card_type_id) => this.game.cards.setupNewCard(card_div, card_type_id);
        this.visibleCardsStock.image_items_per_row = 13;
        this.visibleCardsStock.centerItems = true;
        dojo.connect(this.visibleCardsStock, 'onChangeSelection', this, function (_, itemId) { return _this.game.onVisibleTrainCarCardClick(Number(itemId)); });
        setupTrainCarCards(this.visibleCardsStock);
        visibleCards.forEach(function (card) { return _this.visibleCardsStock.addToStockWithId(card.id, '' + card.id); });
    }
    return TrainSelectionSelection;
}());
var PlayerTable = /** @class */ (function () {
    function PlayerTable(game, player, trainCars, destinations) {
        var _this = this;
        this.game = game;
        this.playerId = Number(player.id);
        var html = "\n        <div id=\"player-table-" + this.playerId + "\" class=\"player-table whiteblock\">\n            <div id=\"player-table-" + this.playerId + "-train-cars\" class=\"player-table-train-cars\"></div>\n            <div id=\"player-table-" + this.playerId + "-destinations\" class=\"player-table-destinations\"></div>\n        </div>";
        dojo.place(html, 'player-hand');
        // adventurer        
        this.trainCarStock = new ebg.stock();
        this.trainCarStock.setSelectionAppearance('class');
        this.trainCarStock.selectionClass = 'selected';
        this.trainCarStock.create(this.game, $("player-table-" + this.playerId + "-train-cars"), CARD_WIDTH, CARD_HEIGHT);
        this.trainCarStock.setSelectionMode(0);
        this.trainCarStock.setSelectionMode(0);
        //this.trainCarStock.onItemCreate = (cardDiv: HTMLDivElement, type: number) => setupAdventurerCard(game, cardDiv, type);
        dojo.connect(this.trainCarStock, 'onChangeSelection', this, function (_, itemId) {
            if (_this.trainCarStock.getSelectedItems().length) {
                //this.game.cardClick(0, Number(itemId));
            }
            _this.trainCarStock.unselectAll();
        });
        setupTrainCarCards(this.trainCarStock);
        trainCars.forEach(function (trainCar) { return _this.trainCarStock.addToStockWithId(trainCar.type, '' + trainCar.id); });
        // companions
        this.destinationStock = new ebg.stock();
        this.destinationStock.setSelectionAppearance('class');
        this.destinationStock.selectionClass = 'selected';
        this.destinationStock.create(this.game, $("player-table-" + this.playerId + "-destinations"), CARD_WIDTH, CARD_HEIGHT);
        this.destinationStock.setSelectionMode(0);
        this.destinationStock.setSelectionMode(0);
        //this.destinationStock.onItemCreate = (cardDiv: HTMLDivElement, type: number) => setupCompanionCard(game, cardDiv, type);
        dojo.connect(this.destinationStock, 'onChangeSelection', this, function (_, itemId) {
            if (_this.destinationStock.getSelectedItems().length) {
                //this.game.cardClick(1, Number(itemId));
            }
            _this.destinationStock.unselectAll();
        });
        setupDestinationCards(this.destinationStock);
        destinations.forEach(function (destination) { return _this.destinationStock.addToStockWithId(destination.type_arg, '' + destination.id); });
    }
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
        this.trainSelectionSelection = new TrainSelectionSelection(this, gamedatas.visibleTrainCards);
        this.destinationSelection = new DestinationSelection(this);
        var player = gamedatas.players[this.getPlayerId()];
        if (player) {
            this.playerTable = new PlayerTable(this, player, gamedatas.handTrainCars, gamedatas.handDestinations);
        }
        this.createPlayerPanels(gamedatas);
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
            /*case 'chooseInitialDestinations':
                this.onEnteringChooseInitialDestinations(args.args as EnteringChooseDestinationsArgs);
                break;*/
        }
    };
    /*onEnteringChooseInitialDestinations(args: EnteringChooseDestinationsArgs) {
        args._private.destinations.forEach(card => this.cards.addToStockWithId(card.id, ''+card.id));

        if ((this as any).isCurrentPlayerActive()) {
            this.cards.setSelectionMode(2);
        }
    }*/
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
                    this.addActionButton('drawDestinations_button', _("Draw Destination Tickets"), function () { return _this.drawDestinations(); }, null, null, 'red');
                    dojo.toggleClass('drawDestinations_button', 'disabled', !args.availableDestinations);
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
    TicketToRide.prototype.createPlayerPanels = function (gamedatas) {
        var _this = this;
        Object.values(gamedatas.players).forEach(function (player) {
            var playerId = Number(player.id);
            // public counters
            dojo.place("<div class=\"counters\">\n                <div class=\"counter train-car-counter\">\n                    <div class=\"icon train-car\"></div> \n                    <span id=\"train-car-counter-" + player.id + "\"></span>\n                </div>\n                <div class=\"counter train-car-card-counter\">\n                    <div class=\"icon train-car-card\"></div> \n                    <span id=\"train-car-card-counter-" + player.id + "\"></span>\n                </div>\n                <div class=\"counter completed-destinations-counter\">\n                    <div class=\"icon destination-card\"></div> \n                    <span id=\"completed-destinations-counter-" + player.id + "\">" + (_this.getPlayerId() !== playerId ? '?' : '') + "</span>&nbsp;/&nbsp;<span id=\"destination-card-counter-" + player.id + "\"></span>\n                </div>\n            </div>", "player_board_" + player.id);
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
        this.addTooltipHtmlToClass('train-car-counter', 'TODO');
        this.addTooltipHtmlToClass('train-car-card-counter', 'TODO');
        this.addTooltipHtmlToClass('completed-destinations-counter', 'TODO');
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
            ['factoriesFilled', ANIMATION_MS],
        ];
        notifs.forEach(function (notif) {
            dojo.subscribe(notif[0], _this, "notif_" + notif[0]);
            _this.notifqueue.setSynchronous(notif[0], notif[1]);
        });
    };
    TicketToRide.prototype.notif_factoriesFilled = function (notif) {
        //this.factories.fillFactories(notif.args.factories, notif.args.remainingTiles);
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
