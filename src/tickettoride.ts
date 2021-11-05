declare const define;
declare const ebg;
declare const $;
declare const dojo: Dojo;
declare const _;
declare const g_gamethemeurl;

declare const board: HTMLDivElement;

const ANIMATION_MS = 500;

const isDebug = window.location.host == 'studio.boardgamearena.com';
const log = isDebug ? console.log.bind(window.console) : function () { };

class TicketToRide implements TicketToRideGame {
    private gamedatas: TicketToRideGamedatas;

    private cards: Stock;
    private minimumDestinations: number;

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

        this.cards = new ebg.stock() as Stock;
        this.cards.setSelectionAppearance('class');
        this.cards.selectionClass = 'destination-selection';
        this.cards.create(this, $(`destination-stock`), 100, 100);
        this.cards.setSelectionMode(0);
        //this.cards.onItemCreate = (card_div, card_type_id) => this.game.cards.setupNewCard(card_div, card_type_id);
        this.cards.image_items_per_row = 10;
        this.cards.centerItems = true;
        dojo.connect(this.cards, 'onChangeSelection', this, () => dojo.toggleClass('chooseInitialDestinations_button', 'disabled', this.cards.getSelectedItems().length < this.minimumDestinations));
        this.setupDestinationCards(this.cards);

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
            case 'chooseInitialDestinations':
                this.onEnteringChooseInitialDestinations(args.args as EnteringChooseDestinationsArgs);
                break;
        }
    }

    onEnteringChooseInitialDestinations(args: EnteringChooseDestinationsArgs) {
        args.destinations.forEach(card => this.cards.addToStockWithId(card.id, ''+card.id));

        if ((this as any).isCurrentPlayerActive()) {
            this.cards.setSelectionMode(2);
        }
    }

    // onLeavingState: this method is called each time we are leaving a game state.
    //                 You can use this method to perform some user interface changes at this moment.
    //
    public onLeavingState(stateName: string) {
        log('Leaving state: '+stateName);

        switch (stateName) {
            case 'chooseInitialDestinations':
                this.onLeavingChooseInitialDestinations();
                break;
        }
    }

    onLeavingChooseInitialDestinations() {
        this.cards.setSelectionMode(0);
    }

    // onUpdateActionButtons: in this method you can manage "action buttons" that are displayed in the
    //                        action status bar (ie: the HTML links in the status bar).
    //
    public onUpdateActionButtons(stateName: string, args: any) {
        if((this as any).isCurrentPlayerActive()) {
            switch (stateName) {    
                case 'chooseInitialDestinations':
                    (this as any).addActionButton('chooseInitialDestinations_button', _("Keep selected destinations"), () => this.chooseInitialDestinations());
                    dojo.addClass('chooseInitialDestinations_button', 'disabled');
                    this.minimumDestinations = (args as EnteringChooseDestinationsArgs).minimum;
            }
        }
    } 
    

    ///////////////////////////////////////////////////
    //// Utility methods


    ///////////////////////////////////////////////////

    public getPlayerId(): number {
        return Number((this as any).player_id);
    }
    
    public setupDestinationCards(stock: Stock) {
        const keepcardsurl = `${g_gamethemeurl}img/destinations.jpg`;
        for (let id=1; id<=36; id++) {
            stock.addItemType(id, id, keepcardsurl, id);
        }
    }

    public chooseInitialDestinations() {
        if(!(this as any).checkAction('chooseInitialDestinations')) {
            return;
        }

        const destinationsIds = this.cards.getSelectedItems().map(item => Number(item.id));

        this.takeAction('chooseInitialDestinations', {
            destinationsIds: destinationsIds.join(',')
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
            ['factoriesFilled', ANIMATION_MS],
        ];

        notifs.forEach((notif) => {
            dojo.subscribe(notif[0], this, `notif_${notif[0]}`);
            (this as any).notifqueue.setSynchronous(notif[0], notif[1]);
        });
    }

    notif_factoriesFilled(notif: Notif<NotifFirstPlayerTokenArgs>) {
        //this.factories.fillFactories(notif.args.factories, notif.args.remainingTiles);
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