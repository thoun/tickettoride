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

        this.map = new TtrMap(this, Object.values(gamedatas.players), gamedatas.claimedRoutes);
        this.trainCarSelection = new TrainCarSelection(this, 
            gamedatas.visibleTrainCards,
            gamedatas.trainCarDeckCount,
            gamedatas.destinationDeckCount,
            gamedatas.trainCarDeckMaxCount,
            gamedatas.destinationDeckMaxCount,
        );
        this.destinationSelection = new DestinationSelection(this);

        const player = gamedatas.players[this.getPlayerId()];
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
        this.setupPreferences();

        (this as any).onScreenWidthChange = () => {
            this.map.setAutoZoom();
        }

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
            case 'chooseInitialDestinations': case 'chooseAdditionalDestinations':
                const chooseDestinationsArgs = args.args as EnteringChooseDestinationsArgs;
                chooseDestinationsArgs._private.destinations.forEach(destination => this.map.setSelectableDestination(destination, true));
                break;
            case 'chooseAction':
                this.onEnteringChooseAction(args.args as EnteringChooseActionArgs);
                break;
            case 'drawSecondCard':
                this.onEnteringDrawSecondCard(args.args as EnteringDrawSecondCardArgs);
                break;
            case 'endScore':
                this.onEnteringEndScore();
                break;
        }
    }

    private onEnteringChooseAction(args: EnteringChooseActionArgs) {
        this.trainCarSelection.setSelectableTopDeck((this as any).isCurrentPlayerActive(), args.maxHiddenCardsPick);
        
        //this.map.setSelectableRoutes((this as any).isCurrentPlayerActive(), args.possibleRoutes);

        this.playerTable?.setDraggable((this as any).isCurrentPlayerActive());
    }

    private onEnteringDrawSecondCard(args: EnteringDrawSecondCardArgs) {
        this.trainCarSelection.setSelectableTopDeck((this as any).isCurrentPlayerActive(), args.maxHiddenCardsPick);
        this.trainCarSelection.setSelectableVisibleCards(args.availableVisibleCards);
    }

    private onEnteringEndScore(fromReload: boolean = false) {
        const lastTurnBar = document.getElementById('last-round');
        if (lastTurnBar) {
            lastTurnBar.style.display = 'none';
        }
    }

    // onLeavingState: this method is called each time we are leaving a game state.
    //                 You can use this method to perform some user interface changes at this moment.
    //
    public onLeavingState(stateName: string) {
        log('Leaving state: '+stateName);

        switch (stateName) {
            case 'chooseInitialDestinations': case 'chooseAdditionalDestinations':
                this.destinationSelection.hide();
                const chooseDestinationsArgs = this.gamedatas.gamestate.args as EnteringChooseDestinationsArgs;
                chooseDestinationsArgs._private.destinations.forEach(destination => {
                    this.map.setSelectedDestination(destination, false);
                    this.map.setSelectableDestination(destination, false);
                });
                break;
            case 'chooseAction':
                //this.map.setSelectableRoutes(false, []);
                this.playerTable?.setDraggable(false);
                break;
            case 'drawSecondCard':
                this.trainCarSelection.removeSelectableVisibleCards();  
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

    private setupPreferences() {
        // Extract the ID and value from the UI control
        const onchange = (e) => {
          var match = e.target.id.match(/^preference_control_(\d+)$/);
          if (!match) {
            return;
          }
          var prefId = +match[1];
          var prefValue = +e.target.value;
          (this as any).prefs[prefId].value = prefValue;
          this.onPreferenceChange(prefId, prefValue);
        }
        
        // Call onPreferenceChange() when any value changes
        dojo.query(".preference_control").connect("onchange", onchange);
        
        // Call onPreferenceChange() now
        dojo.forEach(
          dojo.query("#ingame_menu_content .preference_control"),
          el => onchange({ target: el })
        );

        try {
            (document.getElementById('preference_control_203').closest(".preference_choice") as HTMLDivElement).style.display = 'none';
        } catch (e) {}
    }
      
    private onPreferenceChange(prefId: number, prefValue: number) {
        switch (prefId) {
            case 201:  
                dojo.toggleClass('train-car-deck-hidden-pile', 'buttonselection', prefValue == 1);
                break;
        }
    }

    public getPlayerId(): number {
        return Number((this as any).player_id);
    }

    private createPlayerPanels(gamedatas: TicketToRideGamedatas) {

        Object.values(gamedatas.players).forEach(player => {
            const playerId = Number(player.id);

            // public counters
            dojo.place(`<div class="counters">
                <div id="train-car-counter-${player.id}-wrapper" class="counter train-car-counter">
                    <div class="icon train" data-player-color="${player.color}"></div> 
                    <span id="train-car-counter-${player.id}"></span>
                </div>
                <div id="train-car-card-counter-${player.id}-wrapper" class="counter train-car-card-counter">
                    <div class="icon train-car-card"></div> 
                    <span id="train-car-card-counter-${player.id}"></span>
                </div>
                <div id="train-destinations-counter-${player.id}-wrapper" class="counter destinations-counter">
                    <div class="icon destination-card"></div> 
                    <span id="completed-destinations-counter-${player.id}">${this.getPlayerId() !== playerId ? '?' : ''}</span>/<span id="destination-card-counter-${player.id}"></span>
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
        });

        (this as any).addTooltipHtmlToClass('train-car-counter', _("Remaining train cars"));
        (this as any).addTooltipHtmlToClass('train-car-card-counter', _("Train cars cards"));
        (this as any).addTooltipHtmlToClass('destinations-counter', _("Completed / Total destination cards"));
    }
    
    private setPoints(playerId: number, points: number) {
        (this as any).scoreCtrl[playerId]?.toValue(points);
        //this.map.setPoints(playerId, points);
    }
    
    public setActiveDestination(destination: Destination, previousDestination: Destination = null): void {
        this.map.setActiveDestination(destination, previousDestination);
    }

    public canClaimRoute(route: Route, cardsColor: number): boolean {
        return (
            route.color == 0 || cardsColor == 0 || route.color == cardsColor
        ) && (
            (this.gamedatas.gamestate.args as EnteringChooseActionArgs).possibleRoutes.some(pr => pr.id == route.id)
        );
    }

    public setHighligthedDestination(destination: Destination | null): void {
        this.map.setHighligthedDestination(destination);
    }
    
    public setSelectedDestination(destination: Destination, visible: boolean): void {
        this.map.setSelectedDestination(destination, visible);
    }

    public setDestinationsToConnect(destinations: Destination[]): void {
        this.map.setDestinationsToConnect(destinations);
    }
    
    public getZoom(): number {
        return this.map.getZoom();
    }
    
    public getPlayerColor(): string {
        return this.gamedatas.players[this.getPlayerId()]?.color;
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

    public onVisibleTrainCarCardClick(id: number, stock: Stock) {
        if (dojo.hasClass(`${stock.container_div.id}_item_${id}`, 'disabled')) {
            stock.unselectItem(''+id);
            return;
        }

        const action = this.gamedatas.gamestate.name === 'drawSecondCard' ? 'drawSecondTableCard' : 'drawTableCard';

        if(!(this as any).checkAction(action)) {
            return;
        }

        this.takeAction(action, {
            id
        });
    }

    public claimRoute(routeId: number, color: number) {
        if(!(this as any).checkAction('claimRoute')) {
            return;
        }

        this.takeAction('claimRoute', {
            routeId,
            color
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
            ['newCardsOnTable', ANIMATION_MS],
            ['claimedRoute', ANIMATION_MS],
            ['destinationCompleted', ANIMATION_MS],
            ['points', 1],
            ['destinationsPicked', 1],
            ['trainCarPicked', 1],
            ['lastTurn', 1],
        ];

        notifs.forEach((notif) => {
            dojo.subscribe(notif[0], this, `notif_${notif[0]}`);
            (this as any).notifqueue.setSynchronous(notif[0], notif[1]);
        });
    }

    notif_points(notif: Notif<NotifPointsArgs>) {
        this.setPoints(notif.args.playerId, notif.args.points);
    }

    notif_destinationsPicked(notif: Notif<NotifDestinationsPickedArgs>) {
        this.destinationCardCounters[notif.args.playerId].incValue(notif.args.number);
        const destinations = notif.args._private?.[this.getPlayerId()]?.destinations;
        if (destinations) {
            this.playerTable.addDestinations(destinations, this.destinationSelection.destinations);
        } else {
            // TODO notif to player board ?
        }
        this.trainCarSelection.setDestinationCount(notif.args.remainingDestinationsInDeck);
    }

    notif_trainCarPicked(notif: Notif<NotifTrainCarsPickedArgs>) {
        this.trainCarCardCounters[notif.args.playerId].incValue(notif.args.number);
        const cards = notif.args._private?.[this.getPlayerId()]?.cards;
        if (cards) {
            this.playerTable.addTrainCars(cards, this.trainCarSelection);
        } else {
            // TODO notif to player board ?        
        }
        this.trainCarSelection.setTrainCarCount(notif.args.remainingTrainCarsInDeck);
    }

    notif_newCardsOnTable(notif: Notif<NotifNewCardsOnTableArgs>) {
        this.trainCarSelection.setNewCardsOnTable(notif.args.cards);
    }

    notif_claimedRoute(notif: Notif<NotifClaimedRouteArgs>) {
        const playerId = notif.args.playerId;
        const route: Route = notif.args.route;
        this.trainCarCardCounters[playerId].incValue(-route.number);
        this.trainCarCounters[playerId].incValue(-route.number);
        this.map.setClaimedRoutes([{
            playerId,
            routeId: route.id
        }]);
        if (playerId == this.getPlayerId()) {
            this.playerTable.removeCards(notif.args.removeCards);
        }
    }

    notif_destinationCompleted(notif: Notif<NotifDestinationCompletedArgs>) {
        const destination: Destination = notif.args.destination;
        this.completedDestinationsCounter.incValue(1);
        this.gamedatas.completedDestinations.push(destination);
        this.playerTable.markDestinationComplete(destination);
    }
    
    notif_lastTurn() {
        dojo.place(`<div id="last-round">
            ${_("This is the final round!")}
        </div>`, 'page-title');
    }

    /* This enable to inject translatable styled things to logs or action bar */
    /* @Override */
    public format_string_recursive(log: string, args: any) {
        try {
            if (log && args && !args.processed) {
                if (typeof args.from == 'string' && args.from[0] != '<') {
                    args.from = `<strong>${args.from}</strong>`;
                }
                if (typeof args.to == 'string' && args.to[0] != '<') {
                    args.to = `<strong>${args.to}</strong>`;
                }
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