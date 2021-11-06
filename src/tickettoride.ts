const ANIMATION_MS = 500;

const isDebug = window.location.host == 'studio.boardgamearena.com';
const log = isDebug ? console.log.bind(window.console) : function () { };

class TicketToRide implements TicketToRideGame {
    private gamedatas: TicketToRideGamedatas;

    private map: TtrMap;
    private trainCarSelection: TrainCarSelection;
    private destinationSelection: DestinationSelection;
    private playerTable: PlayerTable = null;

    private trainCarCounters: Counter[] = [];
    private trainCarCardCounters: Counter[] = [];
    private destinationCardCounters: Counter[] = [];
    private completedDestinationsCounter: Counter;

    constructor() {
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

    public setup(gamedatas: TicketToRideGamedatas) {
        // ignore loading of some pictures
        (this as any).dontPreloadImage('publisher.png');

        log("Starting game setup");
        
        this.gamedatas = gamedatas;

        log('gamedatas', gamedatas);

        this.map = new TtrMap(this, Object.values(gamedatas.players));
        this.trainCarSelection = new TrainCarSelection(this, gamedatas.visibleTrainCards);
        this.destinationSelection = new DestinationSelection(this);

        const player = gamedatas.players[this.getPlayerId()];
        if (player) {
            this.playerTable = new PlayerTable(this, player, gamedatas.handTrainCars, gamedatas.handDestinations);
        }

        this.createPlayerPanels(gamedatas);

        log("Ending game setup");
    }

    ///////////////////////////////////////////////////
    //// Game & client states

    // onEnteringState: this method is called each time we are entering into a new game state.
    //                  You can use this method to perform some user interface changes at this moment.
    //
    public onEnteringState(stateName: string, args: any) {
        log('Entering state: '+stateName, args.args);

        switch (stateName) {
            /*case 'chooseInitialDestinations':
                this.onEnteringChooseInitialDestinations(args.args as EnteringChooseDestinationsArgs);
                break;*/
        }
    }

    /*onEnteringChooseInitialDestinations(args: EnteringChooseDestinationsArgs) {
        args._private.destinations.forEach(card => this.cards.addToStockWithId(card.id, ''+card.id));

        if ((this as any).isCurrentPlayerActive()) {
            this.cards.setSelectionMode(2);
        }
    }*/

    // onLeavingState: this method is called each time we are leaving a game state.
    //                 You can use this method to perform some user interface changes at this moment.
    //
    public onLeavingState(stateName: string) {
        log('Leaving state: '+stateName);

        switch (stateName) {
            case 'chooseInitialDestinations': case 'chooseAdditionalDestinations':
                this.destinationSelection.hide();
                break;
        }
    }

    // onUpdateActionButtons: in this method you can manage "action buttons" that are displayed in the
    //                        action status bar (ie: the HTML links in the status bar).
    //
    public onUpdateActionButtons(stateName: string, args: any) {
        if((this as any).isCurrentPlayerActive()) {
            switch (stateName) {
                case 'chooseInitialDestinations':
                    const chooseInitialDestinationsArgs = args as EnteringChooseDestinationsArgs;
                    (this as any).addActionButton('chooseInitialDestinations_button', _("Keep selected destinations"), () => this.chooseInitialDestinations());
                    dojo.addClass('chooseInitialDestinations_button', 'disabled');
                    this.destinationSelection.setCards(chooseInitialDestinationsArgs._private.destinations, chooseInitialDestinationsArgs.minimum);
                    break;   
                case 'chooseAction':
                    const chooseActionArgs = args as EnteringChooseActionArgs;
                    (this as any).addActionButton('drawDestinations_button', dojo.string.substitute(_("Draw ${number} destination tickets"), { number: chooseActionArgs.maxDestinationsPick}), () => this.drawDestinations(), null, null, 'red');
                    dojo.toggleClass('drawDestinations_button', 'disabled', !chooseActionArgs.maxDestinationsPick);
                    break;
                case 'chooseAdditionalDestinations':
                    const chooseAdditionalDestinationsArgs = args as EnteringChooseDestinationsArgs;
                    (this as any).addActionButton('chooseAdditionalDestinations_button', _("Keep selected destinations"), () => this.chooseAdditionalDestinations());
                    dojo.addClass('chooseAdditionalDestinations_button', 'disabled');
                    this.destinationSelection.setCards(chooseAdditionalDestinationsArgs._private.destinations, chooseAdditionalDestinationsArgs.minimum);
                    break;  
            }
        }
    } 
    

    ///////////////////////////////////////////////////
    //// Utility methods


    ///////////////////////////////////////////////////

    public getPlayerId(): number {
        return Number((this as any).player_id);
    }

    public isColorBlindMode(): boolean {
        return (this as any).prefs[201].value == 1;
    }

    private createPlayerPanels(gamedatas: TicketToRideGamedatas) {

        Object.values(gamedatas.players).forEach(player => {
            const playerId = Number(player.id);

            // public counters
            dojo.place(`<div class="counters">
                <div class="counter train-car-counter">
                    <div class="icon train-car"></div> 
                    <span id="train-car-counter-${player.id}"></span>
                </div>
                <div class="counter train-car-card-counter">
                    <div class="icon train-car-card"></div> 
                    <span id="train-car-card-counter-${player.id}"></span>
                </div>
                <div class="counter destinations-counter">
                    <div class="icon destination-card"></div> 
                    <span id="completed-destinations-counter-${player.id}">${this.getPlayerId() !== playerId ? '?' : ''}</span>&nbsp;/&nbsp;<span id="destination-card-counter-${player.id}"></span>
                </div>
            </div>`, `player_board_${player.id}`);

            const trainCarCounter = new ebg.counter();
            trainCarCounter.create(`train-car-counter-${player.id}`);
            trainCarCounter.setValue(player.remainingTrainCarsCount);
            this.trainCarCounters[playerId] = trainCarCounter;

            const trainCarCardCounter = new ebg.counter();
            trainCarCardCounter.create(`train-car-card-counter-${player.id}`);
            trainCarCardCounter.setValue(player.trainCarsCount);
            this.trainCarCardCounters[playerId] = trainCarCardCounter;

            const destinationCardCounter = new ebg.counter();
            destinationCardCounter.create(`destination-card-counter-${player.id}`);
            destinationCardCounter.setValue(player.destinationsCount);
            this.destinationCardCounters[playerId] = destinationCardCounter;

            // private counters
            if (this.getPlayerId() === playerId) {
                this.completedDestinationsCounter = new ebg.counter();
                this.completedDestinationsCounter.create(`completed-destinations-counter-${player.id}`);
                this.completedDestinationsCounter.setValue(gamedatas.completedDestinations.length);
            }
            
            if (this.isColorBlindMode()) {
                dojo.place(`
                <div class="point-marker color-blind meeple-player-${player.id}" data-player-no="${player.playerNo}" style="background-color: #${player.color};"></div>
                `, `player_board_${player.id}`);
            }
        });

        (this as any).addTooltipHtmlToClass('train-car-counter', _("Remaining train cars"));
        (this as any).addTooltipHtmlToClass('train-car-card-counter', _("Train cars cards"));
        (this as any).addTooltipHtmlToClass('destinations-counter', _("Completed / Total destination cards"));
    }
    
    private setPoints(playerId: number, points: number) {
        (this as any).scoreCtrl[playerId]?.toValue(points);
        this.map.setPoints(playerId, points);
    }

    public chooseInitialDestinations() {
        if(!(this as any).checkAction('chooseInitialDestinations')) {
            return;
        }

        const destinationsIds = this.destinationSelection.getSelectedDestinationsIds();

        this.takeAction('chooseInitialDestinations', {
            destinationsIds: destinationsIds.join(',')
        });
    }

    public drawDestinations() {
        if(!(this as any).checkAction('drawDestinations')) {
            return;
        }

        this.takeAction('drawDestinations');
    }

    public chooseAdditionalDestinations() {
        if(!(this as any).checkAction('chooseAdditionalDestinations')) {
            return;
        }

        const destinationsIds = this.destinationSelection.getSelectedDestinationsIds();

        this.takeAction('chooseAdditionalDestinations', {
            destinationsIds: destinationsIds.join(',')
        });
    }

    public onHiddenTrainCarDeckClick(number: number) {
        const action = this.gamedatas.gamestate.name === 'drawSecondCard' ? 'drawSecondDeckCard' : 'drawDeckCards';
        
        if(!(this as any).checkAction(action)) {
            return;
        }

        this.takeAction(action, {
            number
        });
    }

    public onVisibleTrainCarCardClick(id: number) {
        const action = this.gamedatas.gamestate.name === 'drawSecondCard' ? 'drawSecondTableCard' : 'drawTableCard';

        if(!(this as any).checkAction(action)) {
            return;
        }

        this.takeAction(action, {
            id
        });
    }

    public claimRoute(routeId: number) {
        if(!(this as any).checkAction('claimRoute')) {
            return;
        }

        this.takeAction('claimRoute', {
            routeId
        });
    }

    public takeAction(action: string, data?: any) {
        data = data || {};
        data.lock = true;
        (this as any).ajaxcall(`/tickettoride/tickettoride/${action}.html`, data, this, () => {});
    }

    ///////////////////////////////////////////////////
    //// Reaction to cometD notifications

    /*
        setupNotifications:

        In this method, you associate each of your game notifications with your local method to handle it.

        Note: game notification names correspond to "notifyAllPlayers" and "notifyPlayer" calls in
                your azul.game.php file.

    */
    setupNotifications() {
        //log( 'notifications subscriptions setup' );

        const notifs = [
            //['factoriesFilled', ANIMATION_MS],
            ['points', 1],
        ];

        notifs.forEach((notif) => {
            dojo.subscribe(notif[0], this, `notif_${notif[0]}`);
            (this as any).notifqueue.setSynchronous(notif[0], notif[1]);
        });
    }

    /*notif_factoriesFilled(notif: Notif<NotifFirstPlayerTokenArgs>) {
        this.factories.fillFactories(notif.args.factories, notif.args.remainingTiles);
    }*/

    notif_points(notif: Notif<NotifPointsArgs>) {
        this.setPoints(notif.args.playerId, notif.args.points);
    }

    /* This enable to inject translatable styled things to logs or action bar */
    /* @Override */
    public format_string_recursive(log: string, args: any) {
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
        } catch (e) {
            console.error(log,args,"Exception thrown", e.stack);
        }
        return (this as any).inherited(arguments);
    }
}