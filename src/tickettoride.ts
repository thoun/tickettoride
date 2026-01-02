import { DestinationSelection } from "./destination-deck/destination-deck";
import { EndScore } from "./end-score/end-score";
import { TtrMap } from "./map/map";
import { PlayerTable } from "./player-table/player-table";
import { ChooseActionState, EnteringChooseActionArgs } from "./states/ChooseAction";
import { ConfirmTunnelState } from "./states/ConfirmTunnel";
import { DrawSecondCardState } from "./states/DrawSecondCard";
import { StateHandler } from "./states/state-handler";
import { getColor } from "./stock-utils";
import { Destination, EnteringChooseDestinationsArgs, NotifBadgeArgs, NotifBestScoreArgs, NotifClaimedRouteArgs, NotifDestinationCompletedArgs, NotifDestinationsPickedArgs, NotifFreeTunnelArgs, NotifLongestPathArgs, NotifMandalaRoutesArgs, NotifNewCardsOnTableArgs, NotifPointsArgs, NotifTrainCarsPickedArgs, Route, TicketToRideGame, TicketToRideGamedatas, TicketToRideMap, TicketToRidePlayer } from "./tickettoride.d";
import { TrainCarSelection } from "./train-car-deck/train-car-deck";
import { WagonsAnimation } from "./wagons-animation";

const ANIMATION_MS = 500;

const ACTION_TIMER_DURATION = 8;

export class Game implements TicketToRideGame {
    public bga: Bga;
    public gamedatas: TicketToRideGamedatas;

    public map: TtrMap;
    public trainCarSelection: TrainCarSelection;
    public playerTable: PlayerTable | null = null;
    private destinationSelection: DestinationSelection;
    private endScore: EndScore;

    private trainCarCounters: Counter[] = [];
    private trainCarCardCounters: Counter[] = [];
    public destinationCardCounters: Counter[] = [];
    private completedDestinationsCounter: Counter;

    private animations: WagonsAnimation[] = [];

    private isTouch = window.matchMedia('(hover: none)').matches;
    private routeToConfirm: { route: Route, color: number } | null = null;
    private originalTextChooseAction: string;
    private actionTimerId = null;

    private states: StateHandler<any>[] = [];

    private TOOLTIP_DELAY = document.body.classList.contains('touch-device') ? 1500 : undefined;

    constructor(bga: Bga) {
        this.bga = bga;

        this.states.push(
            new ChooseActionState(this, bga),
            new DrawSecondCardState(this, bga),
            new ConfirmTunnelState(this, bga),
        );
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
        this.gamedatas = gamedatas;
        
        const map = this.getMap();
        Object.entries(map.cities).forEach(entry => entry[1].id = Number(entry[0]));
        Object.entries(map.routes).forEach(entry => entry[1].id = Number(entry[0]));
        Object.entries(map.destinations).forEach(typeEntry => Object.entries(typeEntry[1]).forEach(entry => entry[1].id = Number(entry[0])));

        document.getElementById(`score`).insertAdjacentHTML(`beforebegin`, `<link rel="stylesheet" type="text/css" href="${g_gamethemeurl}img/${map.code}/map.css"/>`);

        this.bga.images.preloadImages([
            `${map.code}/map.jpg`,
            ...map.preloadImages.map(filename => `${map.code}/${filename}`)
        ]);

        console.log("Starting game setup");
        
        this.gamedatas = gamedatas;

        console.log('gamedatas', gamedatas);

        this.map = new TtrMap(this, map, Object.values(gamedatas.players), gamedatas.claimedRoutes, gamedatas.map.illustration);
        this.trainCarSelection = new TrainCarSelection(this, 
            gamedatas.visibleTrainCards,
            gamedatas.trainCarDeckCount,
            gamedatas.destinationDeckCount,
            gamedatas.trainCarDeckMaxCount,
            gamedatas.destinationDeckMaxCount,
        );
        this.destinationSelection = new DestinationSelection(this, map);

        const player = gamedatas.players[this.getPlayerId()];
        if (player) {
            this.playerTable = new PlayerTable(this, player, gamedatas.handTrainCars, gamedatas.handDestinations, gamedatas.completedDestinations);
        }

        this.createPlayerPanels(gamedatas);

        if (gamedatas.lastTurn) {
            this.notif_lastTurn(false);
        }
        if (Number(gamedatas.gamestate.id) >= 90) { // score or end
            this.onEnteringEndScore(true);
        }

        this.setupNotifications();

        this.bga.gameui.onScreenWidthChange = () => this.map.setAutoZoom();

        if (this.gamedatas.map.multilingualPdfRulesUrl || this.gamedatas.map.rulesDifferences) {
            this.bga.statusBar.addActionButton(
                _('Rules differences between USA and current map'), 
                () => this.createRulesPopin(), 
                { id: 'rules-differences-btn', destination: document.getElementById(`player_boards`) }
            );
        }

        if (this.gamedatas.map.vertical) {
            document.body.classList.add('vertical-map');
        }

        console.log("Ending game setup");
    }

    ///////////////////////////////////////////////////
    //// Game & client states

    private getStateByName(stateName: string): StateHandler<any> {
        const stateHandler = this.states.find(state => {
            const result = state.match(stateName);
            if (typeof result === 'string') {
                return stateName === result;
            } else if (typeof result === 'boolean') {
                return result;
            } else {
                throw new Error('Invalid state.match return type');
            }
        });
        if (stateHandler) {
            return stateHandler;
        } else {
            //throw new Error(`No state handler for stateName ${stateName}`);
            return undefined;
        }
    }

    public getCurrentStateHandler(): StateHandler<any> {
        return this.getStateByName(this.getCurrentPlayerStateName());
    }

    public getCurrentPlayerStateName(): string {
        return this.gamedatas.gamestate.private_state?.name ?? this.gamedatas.gamestate.name;
    }

    // onEnteringState: this method is called each time we are entering into a new game state.
    //                  You can use this method to perform some user interface changes at this moment.
    //
    public onEnteringState(stateName: string, args: any) {
        console.log('Entering state: '+stateName, args.args);

        if (this.gamedatas.gamestate.type !== 'multipleactiveplayer') {
            this.getStateByName(stateName)?.onEnteringState(args.args, this.bga.players.isCurrentPlayerActive());
        }

        switch (stateName) {
            case 'privateChooseInitialDestinations': case 'chooseInitialDestinations': case 'chooseAdditionalDestinations':
                if (args?.args) {
                    const chooseDestinationsArgs = args.args as EnteringChooseDestinationsArgs;
                    const destinations = chooseDestinationsArgs.destinations || chooseDestinationsArgs._private?.destinations;
                    if (destinations && this.bga.players.isCurrentPlayerActive()) {
                        destinations.forEach(destination => this.map.setSelectableDestination(destination, true));
                        this.destinationSelection.setCards(destinations, chooseDestinationsArgs.minimum, this.trainCarSelection.getVisibleColors());
                        this.destinationSelection.selectionChange();
                    }
                }
                break;
            case 'endScore':
                this.onEnteringEndScore();
                break;
        }
    }

    /**
     * Show score board.
     */ 
    private onEnteringEndScore(fromReload: boolean = false) {
        this.bga.gameArea.removeLastTurnBanner();

        document.getElementById('score').style.display = 'flex';

        this.endScore = new EndScore(this, Object.values(this.gamedatas.players), fromReload, this.gamedatas.bestScore);
    }

    // onLeavingState: this method is called each time we are leaving a game state.
    //                 You can use this method to perform some user interface changes at this moment.
    //
    public onLeavingState(stateName: string) {
        console.log('Leaving state: '+stateName);

        this.getStateByName(stateName)?.onLeavingState(this.gamedatas.gamestate.args, this.bga.players.isCurrentPlayerActive());

        switch (stateName) {
            case 'privateChooseInitialDestinations': case 'chooseInitialDestinations': case 'chooseAdditionalDestinations':
                this.destinationSelection.hide();
                const mapDiv = document.getElementById('map');
                mapDiv.querySelectorAll(`.city[data-selectable]`).forEach((city: HTMLElement) => city.dataset.selectable = 'false');
                mapDiv.querySelectorAll(`.city[data-selected]`).forEach((city: HTMLElement) => city.dataset.selected = 'false');
                break;
            case 'multiChooseInitialDestinations':
                (Array.from(document.getElementsByClassName('player-turn-order')) as HTMLDivElement[]).forEach(elem => elem.remove());
                break;
        }
    }

    // onUpdateActionButtons: in this method you can manage "action buttons" that are displayed in the
    //                        action status bar (ie: the HTML links in the status bar).
    //
    public onUpdateActionButtons(stateName: string, args: any) {
        const state = this.getStateByName(stateName);
        if (state) {
            const isCurrentPlayerActive = this.bga.players.isCurrentPlayerActive();
            if (this.gamedatas.gamestate.type === 'multipleactiveplayer') {
                state.onEnteringState(args, isCurrentPlayerActive);
            }
            state.onUpdateActionButtons(args, isCurrentPlayerActive);
        } else if (this.gamedatas.gamestate.type === 'multipleactiveplayer' && this.gamedatas.gamestate.private_state) {
            const leftState = this.states.find(state => state.match(this.gamedatas.gamestate.private_state.name));
            if (leftState) {
                const isCurrentPlayerActive = this.bga.players.isCurrentPlayerActive();
                leftState.onLeavingState(this.gamedatas.gamestate.private_state.args, isCurrentPlayerActive);
            }
        }

        if(this.bga.players.isCurrentPlayerActive()) {
            switch (stateName) {
                case 'privateChooseInitialDestinations':
                    this.bga.statusBar.addActionButton(_("Keep selected destinations"), () => this.chooseInitialDestinations(), { id: 'chooseInitialDestinations_button' });
                    this.destinationSelection.selectionChange();
                    break;   
                case 'chooseAdditionalDestinations':
                    this.bga.statusBar.addActionButton(_("Keep selected destinations"), () => this.chooseAdditionalDestinations(), { id: 'chooseAdditionalDestinations_button' });
                    dojo.addClass('chooseAdditionalDestinations_button', 'disabled');
                    break;
            }
        }
    } 
    

    ///////////////////////////////////////////////////
    //// Utility methods


    ///////////////////////////////////////////////////
    
    public isGlobetrotterBonusActive(): boolean {
        return this.gamedatas.isGlobetrotterBonusActive;
    }

    public isLongestPathBonusActive(): boolean {
        return this.gamedatas.isLongestPathBonusActive;
    }

    public setTooltip(id: string, html: string) {
        this.bga.gameui.addTooltipHtml(id, html, this.TOOLTIP_DELAY);
    }
    public setTooltipToClass(className: string, html: string) {
        this.bga.gameui.addTooltipHtmlToClass(className, html, this.TOOLTIP_DELAY);
    }
      
    /**
     * Handle user preferences changes.
     */ 
    // @ts-ignore
    onGameUserPreferenceChanged(prefId: number, prefValue: number) {
        switch (prefId) {
            case 201: // 1 = buttons, 2 = double click to pick 2 cards
                dojo.toggleClass('train-car-deck-hidden-pile', 'buttonselection', prefValue == 1);
                break;
            case 203:
                this.map.setOutline();
                break;
            case 204:
                document.getElementsByTagName('html')[0].dataset.colorBlind = (prefValue == 1).toString();
                this.playerTable?.updateColorBlindRotation();
                break;
            case 205:
                document.getElementById('train-car-deck').prepend(document.getElementById(prefValue == 1 ? 'train-car-deck-hidden-pile' : 'destination-deck-hidden-pile'));
                document.getElementById('train-car-deck').append(document.getElementById(prefValue == 1 ? 'destination-deck-hidden-pile': 'train-car-deck-hidden-pile'));
                document.getElementById('destination-deck-hidden-pile').classList.toggle('top', prefValue == 2);
                break;
        }
    }

    public isColorBlindMode(): boolean {
        return this.bga.userPreferences.get(204) === 1;
    }

    public getPlayerId(): number {
        return this.bga.players.getCurrentPlayerId();
    }

    public getPlayerScore(playerId: number): number {
        return this.bga.gameui.scoreCtrl[playerId]?.getValue() ?? Number(this.gamedatas.players[playerId].score);
    }

    public isDoubleRouteForbidden(): boolean {
        return Object.values(this.gamedatas.players).length < this.gamedatas.map.minimumPlayerForDoubleRoutes;
    }

    public getMap(): TicketToRideMap {
        return this.gamedatas.map;
    }

    public getCityName(id: number): string {
        return this.gamedatas.map.cities[id].name;
    }

    /**
     * Place counters on player panels.
     */ 
    private createPlayerPanels(gamedatas: TicketToRideGamedatas) {

        Object.values(gamedatas.players).forEach(player => {
            const playerId = Number(player.id);

            document.getElementById(`overall_player_board_${player.id}`).dataset.playerColor = player.color;

            // public counters
            this.bga.playerPanels.getElement(playerId).insertAdjacentHTML('beforeend', `<div class="counters">
                <div id="train-car-counter-${player.id}-wrapper" class="counter train-car-counter">
                    <div class="icon train" data-player-color="${player.color}" data-color-blind-player-no="${player.playerNo}"></div> 
                    <span id="train-car-counter-${player.id}"></span>
                </div>
                <div id="train-car-card-counter-${player.id}-wrapper" class="counter train-car-card-counter">
                    <div class="icon train-car-card-icon"></div> 
                    <span id="train-car-card-counter-${player.id}"></span>
                </div>
                <div id="destinations-counter-${player.id}-wrapper" class="counter destinations-counter">
                    <div class="icon destination-card"></div> 
                    <span id="completed-destinations-counter-${player.id}">${this.getPlayerId() !== playerId ? '?' : ''}</span>/<span id="destination-card-counter-${player.id}"></span>
                </div>
            </div>`);

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

            if (gamedatas.showTurnOrder && Number(gamedatas.gamestate.id) < 30) { // don't show turn order if game is already started (refresh or TB game)
                this.bga.playerPanels.getElement(playerId).insertAdjacentHTML('beforeend', `<div class="player-turn-order">${_('Player ${number}').replace('${number}', `<strong>${player.playerNo}</strong>`)}</div>`);
            }
        });

        this.setTooltipToClass('train-car-counter', _("Remaining train cars"));
        this.setTooltipToClass('train-car-card-counter', _("Train cars cards"));
        this.setTooltipToClass('destinations-counter', _("Completed / Total destination cards"));
    }
    
    /**
     * Update player score.
     */ 
    private setPoints(playerId: number, points: number) {
        this.bga.gameui.scoreCtrl[playerId]?.toValue(points);
    }
    
    /** 
     * Highlight active destination.
     */ 
    public setActiveDestination(destination: Destination, previousDestination: Destination = null): void {
        this.map.setActiveDestination(destination, previousDestination);
    }

    /** 
     * Check if a route can be claimed with dragged cards.
     */ 
    public canClaimRoute(route: Route, cardsColor: number): boolean {
        return (
            route.color == 0 || cardsColor == 0 || route.color == cardsColor
        ) && (
            (this.gamedatas.gamestate.args as EnteringChooseActionArgs).possibleRoutes.some(pr => pr.id == route.id)
        );
    }

    /** 
     * Highlight destination (on destination mouse over).
     */ 
    public setHighligthedDestination(destination: Destination | null): void {
        this.map.setHighligthedDestination(destination);
    }
    
    /** 
     * Highlight cities of selected destination.
     */ 
    public setSelectedDestination(destination: Destination, visible: boolean): void {
        this.map.setSelectedDestination(destination, visible);
    }

    /** 
     * Highlight cities player must connect for its objectives.
     */ 
    public setDestinationsToConnect(destinations: Destination[]): void {
        this.map.setDestinationsToConnect(destinations);
    }
    
    /** 
     * Place player table to the left or the bottom of the map.
     */ 
    public setPlayerTablePosition(left: boolean) {
        this.playerTable?.setPosition(left);
    }
    
    /** 
     * Get current zoom.
     */ 
    public getZoom(): number {
        return this.map.getZoom();
    }
    
    /** 
     * Get current player.
     */ 
    public getCurrentPlayer(): TicketToRidePlayer {
        return this.gamedatas.players[this.getPlayerId()];
    }

    /** 
     * Add an animation to the animation queue, and start it if there is no current animations.
     */ 
    public addAnimation(animation: WagonsAnimation) {
        this.animations.push(animation);
            if (this.animations.length === 1) {
                this.animations[0].animate();
            };
    }

    /** 
     * Start the next animation in animation queue.
     */ 
    public endAnimation(ended: WagonsAnimation) {
        const index = this.animations.indexOf(ended);
        if (index !== -1) {
            this.animations.splice(index, 1);
        }

        if (this.animations.length >= 1) {
            this.animations[0].animate();
        };
    }
    
    public selectedColorChanged(selectedColor: number | null) {
        if(!this.bga.players.isCurrentPlayerActive() || this.gamedatas.gamestate.name !== 'chooseAction') {
            return;
        }

        const args = this.gamedatas.gamestate.args as EnteringChooseActionArgs;
        if (selectedColor === null || selectedColor === 0) {
            this.map.setSelectableRoutes(true, args.possibleRoutes);
        } else {
            this.map.setSelectableRoutes(true, args.possibleRoutes.filter(route => route.color === selectedColor || route.color === 0));
        }
    }
    
    /** 
     * Handle route click.
     */ 
    public clickedRoute(route: Route, needToCheckDoubleRoute?: boolean): void { 
        if(!this.bga.players.isCurrentPlayerActive()) {
            return;
        }

        if (needToCheckDoubleRoute === undefined) {
            needToCheckDoubleRoute = this.askDoubleRouteActive();
        }

        const otherRoute = Object.values(this.getMap().routes).find(r => route.from == r.from && route.to == r.to && route.id != r.id);
        let askDoubleRouteColor = needToCheckDoubleRoute && otherRoute && otherRoute.color != route.color && this.canClaimRoute(route, 0) && this.canClaimRoute(otherRoute, 0);
        if (askDoubleRouteColor) {
            const selectedColor = this.playerTable.getSelectedColor();
            if (selectedColor) {
                askDoubleRouteColor = false;
            }
        }

        if(askDoubleRouteColor) {
            this.setActionBarAskDoubleRoad(route, otherRoute);
            return;
        }

        if(!this.canClaimRoute(route, 0)) {
            return;
        }

        document.querySelectorAll(`[id^="claimRouteWithColor_button"]`).forEach(button => button.parentElement.removeChild(button));
        if (route.color > 0 && !route.tunnel) {
            this.askRouteClaimConfirmation(route, route.color);
        } else {
            const selectedColor = this.playerTable.getSelectedColor();

            if (selectedColor !== null) {
                this.askRouteClaimConfirmation(route, selectedColor);
            } else {
                const possibleColors: number[] = this.playerTable?.getPossibleColors(route) || [];
                // do not filter for tunnel, or if locomotive is the only possibility
                const possibleColorsWithoutLocomotives = route.tunnel || possibleColors.length <= 1 ? 
                    possibleColors : 
                    possibleColors.filter(color => color != 0);

                if (possibleColorsWithoutLocomotives.length == 1) {
                    this.askRouteClaimConfirmation(route, possibleColorsWithoutLocomotives[0]);
                } else if (possibleColorsWithoutLocomotives.length > 1) {
                    this.setActionBarChooseColor(route, possibleColorsWithoutLocomotives);

                    this.playerTable.setSelectableTrainCarColors(route, possibleColorsWithoutLocomotives);
                }
            }
        }
    }

    /**
     * Timer for Confirm button
     */
    private startActionTimer(buttonId: string, time: number) {
        if (this.actionTimerId) {
            window.clearInterval(this.actionTimerId);
        }
        
        if (this.bga.userPreferences.get(207) == 2) {
            return false;
        }

        const button = document.getElementById(buttonId);
 
        const _actionTimerLabel = button.innerHTML;
        let _actionTimerSeconds = time;
        const actionTimerFunction = () => {
          const button = document.getElementById(buttonId);
          if (button == null) {
            window.clearInterval(this.actionTimerId);
          } else if (_actionTimerSeconds-- > 1) {
            button.innerHTML = _actionTimerLabel + ' (' + _actionTimerSeconds + ')';
          } else {
            window.clearInterval(this.actionTimerId);
            button.click();
          }
        };
        actionTimerFunction();
        this.actionTimerId = window.setInterval(() => actionTimerFunction(), 1000);
    }
    
    private setChooseActionGamestateDescription(newText?: string) {
        if (!this.originalTextChooseAction) {
            this.originalTextChooseAction = document.getElementById('pagemaintitletext').innerHTML;
        }

        document.getElementById('pagemaintitletext').innerHTML = newText ?? this.originalTextChooseAction;
    }
    
    /**
     * Sets the action bar (title and buttons) for Choose action.
     */
    public setActionBarChooseAction(fromCancel: boolean): void {
        document.getElementById(`generalactions`).innerHTML = '';
        if (fromCancel) {
            this.setChooseActionGamestateDescription();
        }
        if (this.actionTimerId) {
            window.clearInterval(this.actionTimerId);
        }

        const chooseActionArgs = this.gamedatas.gamestate.args as EnteringChooseActionArgs;
        this.bga.gameui.addActionButton('drawDestinations_button', dojo.string.substitute(_("Draw ${number} destination tickets"), { number: chooseActionArgs.maxDestinationsPick}), () => this.drawDestinations(), null, null, 'red');
        dojo.toggleClass('drawDestinations_button', 'disabled', !chooseActionArgs.maxDestinationsPick);
        if (chooseActionArgs.canPass) {
            this.bga.statusBar.addActionButton(_("Pass"), () => this.pass());
        }
    }
    
    /**
     * Sets the action bar (title and buttons) for the color route.
     */
    private setActionBarChooseColor(route: Route, possibleColors: number[]) {
        const confirmationQuestion = _("Choose color for the route from ${from} to ${to}")
            .replace('${from}', this.getCityName(route.from))
            .replace('${to}', this.getCityName(route.to));
        this.setChooseActionGamestateDescription(confirmationQuestion);

        document.getElementById(`generalactions`).innerHTML = '';

        possibleColors.forEach(color => {
            const label = dojo.string.substitute(_("Use ${color}"), {
                'color': `<div class="train-car-color icon" data-color="${color}"></div> ${getColor(color, 'train-car')}`
            });
            this.bga.gameui.addActionButton(`claimRouteWithColor_button${color}`, label, () => this.askRouteClaimConfirmation(route, color));
        });

        this.bga.gameui.addActionButton(`cancelRouteClaim-button`, _("Cancel"), () => this.cancelRouteClaim(), null, null, 'gray');
    }
    
    /**
     * Sets the action bar (title and buttons) for Confirm route claim.
     */
    private setActionBarConfirmRouteClaim(route: Route, color: number) {
        const chooseActionArgs = this.gamedatas.gamestate.args as EnteringChooseActionArgs;
        const colors = chooseActionArgs.costForRoute[route.id][color].map(cardColor => `<div class="train-car-color icon" data-color="${cardColor}"></div>`);
        const confirmationQuestion = _("Confirm ${color} route from ${from} to ${to} with ${colors} ?")
            .replace('${color}', getColor(route.color, 'route'))
            .replace('${from}', this.getCityName(route.from))
            .replace('${to}', this.getCityName(route.to))
            .replace('${colors}', `<div class="color-cards">${colors.join('')}</div>`);
        this.setChooseActionGamestateDescription(confirmationQuestion);

        document.getElementById(`generalactions`).innerHTML = '';
        this.bga.gameui.addActionButton(`confirmRouteClaim-button`, _("Confirm"), () => this.confirmRouteClaim());
        this.bga.gameui.addActionButton(`cancelRouteClaim-button`, _("Cancel"), () => this.cancelRouteClaim(), null, null, 'gray');
        this.startActionTimer(`confirmRouteClaim-button`, ACTION_TIMER_DURATION);
    }

    /**
     * Check if player should be asked for a route claim confirmation.
     */
    private confirmRouteClaimActive() {
        const preferenceValue = this.bga.userPreferences.get(202);
        return preferenceValue === 1 || (preferenceValue === 2 && this.isTouch);
    }

    /**
     * Check if player should be asked for the color he wants when he clicks on a double route.
     */
    private askDoubleRouteActive() {
        const preferenceValue = this.bga.userPreferences.get(209);
        return preferenceValue === 1;
    }

    private setActionBarAskDoubleRoad(clickedRoute: Route, otherRoute: Route) {
        const question = _("Which part of the double route do you want to claim?")
        this.setChooseActionGamestateDescription(question);

        document.getElementById(`generalactions`).innerHTML = '';
        [clickedRoute, otherRoute].forEach(route => {
            this.bga.gameui.addActionButton(`claimDoubleRoute${route.id}-button`, `<div class="train-car-color icon" data-color="${route.color}"></div> ${getColor(route.color, 'train-car')}`, () => this.clickedRoute(route, false));
        });
        this.bga.gameui.addActionButton(`cancelRouteClaim-button`, _("Cancel"), () => this.cancelRouteClaim(), null, null, 'gray');
    }

    /**
     * Ask confirmation for claimed route.
     */
    public askRouteClaimConfirmation(route: Route, color: number) {
        const selectedColor = this.playerTable.getSelectedColor();
        if (route.color !== 0 && selectedColor !== null && selectedColor !== 0 && route.color !== selectedColor) {
            const otherRoute = Object.values(this.getMap().routes).find(r => route.from == r.from && route.to == r.to && route.id != r.id);
            if (otherRoute.color === selectedColor) {
                this.askRouteClaimConfirmation(otherRoute, selectedColor);
            }
            return;
        }

        if (this.confirmRouteClaimActive()) {
            this.routeToConfirm = { route, color };
            this.map.setHoveredRoute(route, true);
            this.setActionBarConfirmRouteClaim(route, color);
        } else {
            this.claimRoute(route.id, color);
        }
    }

    /**
     * Player cancels claimed route.
     */
    public cancelRouteClaim() {
        this.setActionBarChooseAction(true);
        this.map.setHoveredRoute(null);
        this.playerTable?.setSelectableTrainCarColors(null);
        this.routeToConfirm = null;

        document.querySelectorAll(`[id^="claimRouteWithColor_button"]`).forEach(button => button.parentElement?.removeChild(button));
    }

    /**
     * Player confirms claimed route.
     */
    public confirmRouteClaim() {
        this.map.setHoveredRoute(null);
        this.claimRoute(this.routeToConfirm.route.id, this.routeToConfirm.color);
    }

    /** 
     * Apply destination selection (initial objectives).
     */ 
    public chooseInitialDestinations() {
        const destinationsIds = this.destinationSelection.getSelectedDestinationsIds();

        this.bga.actions.performAction('actChooseInitialDestinations', {
            destinationsIds: destinationsIds.join(',')
        });
    }

    /** 
     * Pick destinations.
     */ 
    public drawDestinations() {
        const confirmation = this.bga.userPreferences.get(206) !== 2;

        if (confirmation && this.gamedatas.gamestate.args.maxDestinationsPick) {
            this.bga.dialogs.confirmation( _('Are you sure you want to take new destinations?')).then(result => {
                if (result) {
                    this.bga.actions.performAction('actDrawDestinations');
                }
            }); 
        } else {
            this.bga.actions.performAction('actDrawDestinations');
        }
    }

    /** 
     * Apply destination selection (additional objectives).
     */ 
    public chooseAdditionalDestinations() {
        const destinationsIds = this.destinationSelection.getSelectedDestinationsIds();

        this.bga.actions.performAction('actChooseAdditionalDestinations', {
            destinationsIds: destinationsIds.join(',')
        });
    }

    /** 
     * Pick hidden train car(s).
     */ 
    public onHiddenTrainCarDeckClick(number: number) {
        const action = this.gamedatas.gamestate.name === 'drawSecondCard' ? 'actDrawSecondDeckCard' : 'actDrawDeckCards';
        
        this.bga.actions.performAction(action, {
            number
        });
    }

    /** 
     * Pick visible train car.
     */ 
    public onVisibleTrainCarCardClick(id: number) {
        const action = this.gamedatas.gamestate.name === 'drawSecondCard' ? 'actDrawSecondTableCard' : 'actDrawTableCard';

        this.bga.actions.performAction(action, {
            id
        });
    }

    /** 
     * Claim a route.
     */ 
    public claimRoute(routeId: number, color: number) {
        this.bga.actions.performAction('actClaimRoute', {
            routeId,
            color
        });
    }

    /** 
     * Pass (in case of no possible action).
     */ 
    public pass() {
        this.bga.actions.performAction('actPass');
    }

    private isFastEndScoring() {
        return this.bga.userPreferences.get(208) == 2;
    }

    ///////////////////////////////////////////////////
    //// Reaction to cometD notifications

    /*
        setupNotifications:

        In this method, you associate each of your game notifications with your local method to handle it.

        Note: game notification names correspond to "notify->all" and "notify->player" calls in
                your azul.game.php file.

    */
    setupNotifications() {
        //console.log( 'notifications subscriptions setup' );

        const skipEndOfGameAnimations = this.isFastEndScoring();

        const notifs = [
            ['newCardsOnTable', ANIMATION_MS],
            ['claimedRoute', ANIMATION_MS],
            ['destinationCompleted', ANIMATION_MS],
            ['points', 1],
            ['destinationsPicked', 1],
            ['trainCarPicked', ANIMATION_MS],
            ['freeTunnel', 2000],
            ['highlightVisibleLocomotives', 1000],
            ['notEnoughTrainCars', 1],
            ['lastTurn', 1],
            ['bestScore', 1],
            ['scoreDestination', skipEndOfGameAnimations ? 1 : 2000],
            ['longestPath', skipEndOfGameAnimations ? 1 : 2000],
            ['longestPathWinner', skipEndOfGameAnimations ? 1 : 1500],
            ['globetrotterWinner', skipEndOfGameAnimations ? 1 : 1500],
            ['scoreDestinationGrandTour', skipEndOfGameAnimations ? 1 : 2000],
            ['highlightWinnerScore', 1],
        ];

        notifs.forEach((notif) => {
            dojo.subscribe(notif[0], this, `notif_${notif[0]}`);
            (this.bga.gameui as any).notifqueue.setSynchronous(notif[0], notif[1]);
        });

        (this.bga.gameui as any).notifqueue.setIgnoreNotificationCheck('trainCarPicked', (notif: Notif<NotifTrainCarsPickedArgs>) => 
            notif.args.playerId == this.getPlayerId() && !notif.args.cards
        );
    }

    /** 
     * Update player score.
     */ 
    notif_points(notif: Notif<NotifPointsArgs>) {
        this.setPoints(notif.args.playerId, notif.args.points);
        this.endScore?.setPoints(notif.args.playerId, notif.args.points);
    }

    /** 
     * Update player destinations.
     */ 
    notif_destinationsPicked(notif: Notif<NotifDestinationsPickedArgs>) {
        this.destinationCardCounters[notif.args.playerId].incValue(notif.args.number);
        const destinations = notif.args._private?.destinations;
        if (destinations) {
            this.playerTable.addDestinations(destinations, this.destinationSelection.destinations);
        } else {
            this.trainCarSelection.moveDestinationCardToPlayerBoard(notif.args.playerId, notif.args.number);
        }
        this.trainCarSelection.setDestinationCount(notif.args.remainingDestinationsInDeck);
    }

    /** 
     * Update player train cars.
     */ 
    notif_trainCarPicked(notif: Notif<NotifTrainCarsPickedArgs>) {
        this.trainCarCardCounters[notif.args.playerId].incValue(notif.args.number);
        if (notif.args.playerId == this.getPlayerId()) {
            const cards = notif.args.cards;
            this.playerTable.addTrainCars(cards, this.trainCarSelection.getStockElement(notif.args.origin));
        } else {
            this.trainCarSelection.moveTrainCarCardToPlayerBoard(notif.args.playerId, notif.args.origin, notif.args.number);
        }
        this.trainCarSelection.setTrainCarCount(notif.args.remainingTrainCarsInDeck);
    }

    /** 
     * Update visible cards.
     */ 
    notif_newCardsOnTable(notif: Notif<NotifNewCardsOnTableArgs>) {
        if (notif.args.locomotiveRefill) {
            this.bga.sounds.play(`ttr-clear-train-car-cards`);
            this.bga.gameui.disableNextMoveSound();
        }

        this.trainCarSelection.setNewCardsOnTable(notif.args.spotsCards, true);
        this.trainCarSelection.setTrainCarCount(notif.args.remainingTrainCarsInDeck);
    }

    /**
     * Animate the 3 visible locomotives (bump) before they are replaced.
     */ 
    notif_highlightVisibleLocomotives() {
        this.trainCarSelection.highlightVisibleLocomotives();
    }

    /** 
     * Update claimed routes.
     */ 
    notif_claimedRoute(notif: Notif<NotifClaimedRouteArgs>) {
        const playerId = notif.args.playerId;
        const route: Route = notif.args.route;

        this.trainCarCardCounters[playerId].incValue(-notif.args.removeCards.length);
        this.trainCarCounters[playerId].toValue(notif.args.remainingTrainCars);
        this.map.setClaimedRoutes([{
            playerId,
            routeId: route.id
        }], playerId);
        if (playerId == this.getPlayerId()) {
            this.playerTable.removeCards(notif.args.removeCards);
        }
    }

    /** 
     * Mark a destination as complete.
     */ 
    notif_destinationCompleted(notif: Notif<NotifDestinationCompletedArgs>) {
        const destination: Destination = notif.args.destination;
        this.completedDestinationsCounter.incValue(1);
        this.gamedatas.completedDestinations.push(destination);
        this.playerTable.markDestinationComplete(destination, notif.args.destinationRoutes);

        this.bga.sounds.play(`ttr-completed-in-game`);
        this.bga.gameui.disableNextMoveSound();
    }

    /** 
     * Show the 3 cards when attempting a tunnel (case withno extra cards required, play automatically).
     */ 
    notif_freeTunnel(notif: Notif<NotifFreeTunnelArgs>) {
        if (this.bga.gameui.bgaAnimationsActive()) {
            this.trainCarSelection.showTunnelCards(notif.args.tunnelCards);
            setTimeout(() => this.trainCarSelection.showTunnelCards([]), 2000);
        }
    }
    
    /** 
     * Show an error message and animate train car counter to show the player can't take the route because he doesn't have enough train cars left.
     */ 
    notif_notEnoughTrainCars() {
        this.bga.dialogs.showMessage(_("Not enough train cars left to claim the route."), 'error');
        const animatedElement = document.getElementById(`train-car-counter-${this.getPlayerId()}-wrapper`);
        animatedElement.classList.remove('animate-low-count');
        setTimeout(() => animatedElement.classList.add('animate-low-count'), 1);

        if (document.getElementById(`confirmRouteClaim-button`)) {
            this.cancelRouteClaim();
        } else {            
            document.querySelectorAll(`[id^="claimRouteWithColor_button"]`).forEach(button => button.parentElement?.removeChild(button));
        }
    }
    
    /** 
     * Show last turn banner.
     */ 
    notif_lastTurn(animate: boolean = true) {
        this.bga.gameArea.addLastTurnBanner(_("This is the final round!"));
    }
    
    /** 
     * Save best score for end score animations.
     */ 
    notif_bestScore(notif: Notif<NotifBestScoreArgs>) {
        this.gamedatas.bestScore = notif.args.bestScore;
        this.endScore?.setBestScore(notif.args.bestScore);
    }

    /** 
     * Animate a destination for end score.
     */ 
    notif_scoreDestination(notif: Notif<NotifDestinationCompletedArgs>) {
        const playerId = notif.args.playerId;
        const player = this.gamedatas.players[playerId];
        this.endScore?.scoreDestination(playerId, notif.args.destination, notif.args.destinationRoutes, this.isFastEndScoring());
        if (notif.args.destinationRoutes) {
            player.completedDestinations.push(notif.args.destination);
        } else {
            player.uncompletedDestinations.push(notif.args.destination);
            document.getElementById(`destination-card-${notif.args.destination.id}`)?.classList.add('uncompleted');
        }
        this.endScore?.updateDestinationsTooltip(player);
    }

    /** 
     * Add Globetrotter badge for end score.
     */ 
    notif_globetrotterWinner(notif: Notif<NotifBadgeArgs>) {
        this.endScore?.setGlobetrotterWinner(notif.args.playerId, notif.args.length);
    }

    /** 
     * Animate longest path for end score.
     */ 
    notif_longestPath(notif: Notif<NotifLongestPathArgs>) {
        this.endScore?.showLongestPath(this.gamedatas.players[notif.args.playerId].color, notif.args.routes, notif.args.length, this.isFastEndScoring());
    }

    /** 
     * Animate mandala routes for end score.
     */ 
    notif_scoreDestinationGrandTour(notif: Notif<NotifMandalaRoutesArgs>) {
        this.endScore?.showMandalaRoutes(notif.args.routes, notif.args.destination, this.isFastEndScoring());
    }

    /** 
     * Add longest path badge for end score.
     */ 
    notif_longestPathWinner(notif: Notif<NotifBadgeArgs>) {
        this.endScore?.setLongestPathWinner(notif.args.playerId, notif.args.length);
    }

    /** 
     * Highlight winner for end score.
     */ 
    notif_highlightWinnerScore(notif: Notif<NotifLongestPathArgs>) {
        this.endScore?.highlightWinnerScore(notif.args.playerId);

        this.bga.sounds.play(`ttr-scoring-end`);
        this.bga.gameui.disableNextMoveSound();
    }

    public bgaFormatText(log: string, args: any) {
        try {
            if (log && args && !args.processed) {
                if (typeof args.color == 'number') {
                    args.color = `<div class="train-car-color icon" data-color="${args.color}"></div>`;
                }
                if (typeof args.colors == 'object') {
                    args.colors = args.colors.map(color => `<div class="train-car-color icon" data-color="${color}"></div>`).join('');
                }

                // make cities names in bold 
                ['from', 'to', 'count', 'extraCards', 'pickedCards'].forEach(field => {
                    if (args[field] !== null && args[field] !== undefined && args[field][0] != '<') {
                        args[field] = `<strong>${_(args[field])}</strong>`;
                    }
                });

                ['you', 'actplayer', 'player_name'].forEach(field => {
                    if (typeof args[field] === 'string' && args[field].indexOf('#ffed00;') !== -1 && args[field].indexOf('text-shadow') === -1) {
                        args[field] = args[field].replace('#ffed00;', '#ffed00; text-shadow: 0 0 1px black, 0 0 2px black, 0 0 3px black;');
                    }
                });
            }
        } catch (e) {
            console.error(log,args,"Exception thrown", e.stack);
        }
        return { log, args };
    }

    private createRulesPopin()  {
        const url = this.gamedatas.map.multilingualPdfRulesUrl;
        //`https://cdn.svc.asmodee.net/production-daysofwonder/uploads/2023/09/720114-T2RMC2-Rules_switzerland-ML-2017.pdf`; // TODO

        let html = `
        <div id="popin_showMapRulebook_container" class="tickettoride_popin_container">
            <div id="popin_showMapRulebook_underlay" class="tickettoride_popin_underlay"></div>
                <div id="popin_showMapRulebook_wrapper" class="tickettoride_popin_wrapper">
                <div id="popin_showMapRulebook" class="tickettoride_popin">
                    <a id="popin_showMapRulebook_close" class="closeicon"><i class="fa fa-times fa-2x" aria-hidden="true"></i></a>
                                
                    <h2>${_('Rules differences')}</h2>
                    <div id="playermat-container-modal">
                        ${this.gamedatas.map.rulesDifferences ? `
                            <ul>
                                ${this.gamedatas.map.rulesDifferences.map(line => `<li>${_(line)}</li>`).join('')}
                            </ul>
                        ` : ''}
                    
                        ${url ? `
                        <p class="block-buttons">
                            ${_('Multilingual rulebook:')}
                            <button id="show-rulebook" style="width: auto;" class="bgabutton bgabutton_blue">${_('Show rulebook')}</button>
                            <a href="${url}" target="_blank" class="bgabutton bgabutton_blue">${_('Open rulebook in a new tab')}</a>
                        </p>
                        <div id="rulebook-iframe"></div>
                        ` : ''}
                    </div>
                </div>
            </div>
        </div>`;
        document.body.insertAdjacentHTML('beforeend', html);

        document.getElementById(`popin_showMapRulebook_close`).addEventListener(`click`, () => this.closePopin());
        document.getElementById(`popin_showMapRulebook_underlay`).addEventListener(`click`, () => this.closePopin());

        if (url) {
            document.getElementById(`show-rulebook`).addEventListener(`click`, () => this.viewRulebook(url));
        }
    }

    private viewRulebook(url: string) {
        const rulebookContainer = document.getElementById(`rulebook-iframe`);
        const show = rulebookContainer.innerHTML === '';
        if (show) {
            const html = `<iframe src="${url}" style="width: 100%; height: 60vh"></iframe>`;
            rulebookContainer.innerHTML = html;
        } else {
            rulebookContainer.innerHTML = '';
        }
        document.getElementById(`show-rulebook`).innerHTML = show ? _('Hide rulebook') : _('Show rulebook');
    }

    private closePopin() {
        document.getElementById('popin_showMapRulebook_container').remove();
    }
}