import { DistributionPopin, DistributionResult } from "../distribution-popin";
import { getColor } from "../stock-utils";
import { ClaimingRoute, Route, TicketToRideGame, TrainCar } from "../tickettoride.d";

export const LOCOMOTIVE_TUNNEL = 0b01;
export const LOCOMOTIVE_FERRY = 0b10;

export interface EnteringChooseActionArgs {
    possibleRoutes: Route[];
    costForRoute: { [routeId: number]: { [color: number]: number[] } };
    maxHiddenCardsPick: number;
    maxDestinationsPick: number;
    canTakeTrainCarCards: boolean;
    canPass: boolean;
    _private?: {
        trainCarsHand: TrainCar[];
    }
}

/**
 * The state handles multiple "sub-states" in case of route selection, in this order :
 * - ask double route
 * - ask color
 * - ask number of locomotives to use
 * - ask confirmation
 */
export class ChooseActionState {
    protected game: TicketToRideGame;
    protected bga: Bga;
    protected args: EnteringChooseActionArgs;

    private claimingRoute: ClaimingRoute | null = null;

    private isTouch = window.matchMedia('(hover: none)').matches;

    constructor(game: TicketToRideGame, bga: Bga) {
        this.game = game;
        this.bga = bga;
    }

    /**
     * Show selectable routes, and make train car draggable.
     */ 
    public onEnteringState(args: EnteringChooseActionArgs, isCurrentPlayerActive: boolean) {
        this.game.trainCarSelection.setSelectableTopDeck(isCurrentPlayerActive, args.maxHiddenCardsPick);
        
        this.game.map.setSelectableRoutes(isCurrentPlayerActive, args.possibleRoutes);

        this.game.playerTable?.setDraggable(isCurrentPlayerActive);
        this.game.playerTable?.setSelectable(isCurrentPlayerActive);

        if (isCurrentPlayerActive) {
            if (args.maxDestinationsPick) {
                document.getElementById('destination-deck-hidden-pile').classList.add('selectable');
            }
        }
        this.setActionBarChooseAction(isCurrentPlayerActive);
    }

    public onLeavingState(args: EnteringChooseActionArgs, isCurrentPlayerActive: boolean) {
        this.game.map.setHoveredRoute(null);
        this.game.map.setSelectableRoutes(false, []);
        this.game.playerTable?.setDraggable(false);
        this.game.playerTable?.setSelectable(false);   
        this.game.playerTable?.setSelectableTrainCarColors(null);
        document.getElementById('destination-deck-hidden-pile').classList.remove('selectable');
        (Array.from(document.getElementsByClassName('train-car-group hide')) as HTMLDivElement[]).forEach(group => group.classList.remove('hide'));
    }
        
    /**
     * Sets the action bar (title and buttons) for Choose action.
     */
    public setActionBarChooseAction(isCurrentPlayerActive: boolean): void {

        if (!this.args.canTakeTrainCarCards) {
            this.bga.statusBar.setTitle(isCurrentPlayerActive ?
                    _('${you} must claim a route or draw destination tickets') :
                    _('${actplayer} must claim a route or draw destination tickets'),
                this.args);
        } else {
            this.bga.statusBar.setTitle(isCurrentPlayerActive ?
                    _('${you} must draw train car cards, claim a route or draw destination tickets') :
                    _('${actplayer} must draw train car cards, claim a route or draw destination tickets'),
                this.args);
        }

        if (isCurrentPlayerActive) {
            this.bga.statusBar.removeActionButtons();

            this.bga.statusBar.addActionButton(
                dojo.string.substitute(_("Draw ${number} destination tickets"), { number: this.args.maxDestinationsPick}), 
                () => this.game.drawDestinations(), 
                { color: 'alert', disabled: !this.args.maxDestinationsPick },
            );
            if (this.args.canPass) {
                // Pass (only in case of no possible action)
                this.bga.statusBar.addActionButton(_("Pass"), () => this.bga.actions.performAction('actPass'));
            }
        }
    }

    private setActionBarAskDoubleRoad(clickedRoute: Route, otherRoute: Route) {
        const question = _("Which part of the double route do you want to claim?")
        this.bga.statusBar.setTitle(question);

        this.bga.statusBar.removeActionButtons();
        [clickedRoute, otherRoute].forEach(route => {
            this.bga.statusBar.addActionButton(`<div class="train-car-color icon" data-color="${route.color}"></div> ${getColor(route.color, 'train-car')}`, () => this.clickedRouteDoubleRouteConfirmed(route));
        });
        this.bga.statusBar.addActionButton(_("Cancel"), () => this.cancelRouteClaim(), { color: 'secondary' });
    }

    /**
     * Ask confirmation for claimed route.
     */
    public clickedRouteColorChosen(route: Route, color: number) {
        const selectedColor = this.game.playerTable.getSelectedColor();
        if (route.color !== 0 && selectedColor !== null && selectedColor !== 0 && route.color !== selectedColor) {
            const otherRoute = Object.values(this.game.getMap().routes).find(r => route.from == r.from && route.to == r.to && route.id != r.id);
            if (otherRoute.color === selectedColor) {
                this.clickedRouteColorChosen(otherRoute, selectedColor);
            }
            return;
        }

        this.claimingRoute = { route, color, distribution: null };

        const locomotiveRestriction = this.game.getMap().locomotiveUsageRestriction;
        const canUseLocomotives = locomotiveRestriction === 0
            || ((locomotiveRestriction & LOCOMOTIVE_TUNNEL) !== 0 && route.tunnel)
            || ((locomotiveRestriction & LOCOMOTIVE_FERRY) !== 0 && route.locomotives > 0);
        if (route.canPayWithAnySetOfCards || (locomotiveRestriction && canUseLocomotives)) {

            const confirmationQuestion = _("Cards to take ${color} route from ${from} to ${to}")
                .replace('${color}', getColor(this.claimingRoute.route.color, 'route'))
                .replace('${from}', this.game.getCityName(this.claimingRoute.route.from))
                .replace('${to}', this.game.getCityName(this.claimingRoute.route.to));

            new DistributionPopin(this.args._private.trainCarsHand, this.claimingRoute, this.claimingRoute.route.spaces.length, canUseLocomotives).show(confirmationQuestion).then((distribution: DistributionResult) => {
                if (distribution) {
                    this.claimingRoute.distribution = distribution.cardIds;
                    if (distribution.auto) {
                        this.clickedRouteDistributionChosen();
                    } else {
                        // do not ask for confirmation, the popin is a kind of confirmation
                        this.claimRoute();
                    }
                } else {
                    this.cancelRouteClaim();
                }
            });
            return;
        }

        this.clickedRouteDistributionChosen();
    }

    public clickedRouteDistributionChosen() {
        if (this.confirmRouteClaimActive()) {
            this.game.map.setHoveredRoute(this.claimingRoute.route, true);
            this.setActionBarConfirmRouteClaim();
        } else {
            this.claimRoute();
        }

    }

    /**
     * Player cancels claimed route.
     */
    public cancelRouteClaim() {
        this.setActionBarChooseAction(this.bga.players.isCurrentPlayerActive());
        this.game.map.setHoveredRoute(null);
        this.game.playerTable?.setSelectableTrainCarColors(null);
        this.claimingRoute = null;

        document.querySelectorAll(`[id^="claimRouteWithColor_button"]`).forEach(button => button.parentElement?.removeChild(button));
    }

    /**
     * Player confirms claimed route.
     */
    public confirmRouteClaim() {
        this.game.map.setHoveredRoute(null);
        this.claimRoute();
    }
    
    /** 
     * Handle route click.
     */ 
    public clickedRoute(route: Route): void { 
        if(!this.bga.players.isCurrentPlayerActive()) {
            return;
        }

        const needToCheckDoubleRoute = this.askDoubleRouteActive();

        const otherRoute = Object.values(this.game.getMap().routes).find(r => route.from == r.from && route.to == r.to && route.id != r.id);
        let askDoubleRouteColor = needToCheckDoubleRoute && otherRoute && otherRoute.color != route.color && this.canClaimRoute(route, 0) && this.canClaimRoute(otherRoute, 0);
        if (askDoubleRouteColor) {
            const selectedColor = this.game.playerTable.getSelectedColor();
            if (selectedColor) {
                askDoubleRouteColor = false;
            }
        }

        if (askDoubleRouteColor) {
            this.setActionBarAskDoubleRoad(route, otherRoute);
            return;
        }

        if(!this.canClaimRoute(route, 0)) {
            return;
        }

        this.clickedRouteDoubleRouteConfirmed(route);
    }

    public clickedRouteDoubleRouteConfirmed(route: Route) {
        document.querySelectorAll(`[id^="claimRouteWithColor_button"]`).forEach(button => button.parentElement.removeChild(button));
        if ((route.color === 0 || route.tunnel) && this.game.playerTable.getSelectedColor() === null) {
            const possibleColors: number[] = [];
            const costForRoute = this.args.costForRoute[route.id];
            if (costForRoute) {
                for (let i = 0; i <= 8; i++) {
                    if (costForRoute[i]) {
                        possibleColors.push(i);
                    }
                }
            }
            // do not filter for tunnel, or if locomotive is the only possibility
            const possibleColorsWithoutLocomotives = route.tunnel || possibleColors.length <= 1 ? 
                possibleColors : 
                possibleColors.filter(color => color != 0);

            if (possibleColorsWithoutLocomotives.length == 1) {
                this.clickedRouteColorChosen(route, possibleColorsWithoutLocomotives[0]);
                return;
            } else if (possibleColorsWithoutLocomotives.length > 1) {
                this.setActionBarChooseColor(route, possibleColorsWithoutLocomotives);

                this.game.playerTable.setSelectableTrainCarColors(route, possibleColorsWithoutLocomotives);
                return;
            }
        }

        this.clickedRouteColorChosen(route, this.game.playerTable.getSelectedColor() ?? route.color);
    }
    
    /**
     * Sets the action bar (title and buttons) for the color route.
     */
    private setActionBarChooseColor(route: Route, possibleColors: number[]) {
        const confirmationQuestion = _("Choose color for the route from ${from} to ${to}")
            .replace('${from}', this.game.getCityName(route.from))
            .replace('${to}', this.game.getCityName(route.to));
        this.bga.statusBar.setTitle(confirmationQuestion);

        this.bga.statusBar.removeActionButtons();

        possibleColors.forEach(color => {
            const label = dojo.string.substitute(_("Use ${color}"), {
                'color': `<div class="train-car-color icon" data-color="${color}"></div> ${getColor(color, 'train-car')}`
            });
            this.bga.statusBar.addActionButton(label, () => this.clickedRouteColorChosen(route, color), { id: `claimRouteWithColor_button${color}`, });
        });

        this.bga.statusBar.addActionButton(_("Cancel"), () => this.cancelRouteClaim(), { color: 'secondary' });
    }
    
    /**
     * Sets the action bar (title and buttons) for Confirm route claim.
     */
    private setActionBarConfirmRouteClaim() {
        const colors = this.args.costForRoute[this.claimingRoute.route.id][this.claimingRoute.color].map(cardColor => `<div class="train-car-color icon" data-color="${cardColor}"></div>`);
        const confirmationQuestion = _("Confirm ${color} route from ${from} to ${to} with ${colors} ?")
            .replace('${color}', getColor(this.claimingRoute.route.color, 'route'))
            .replace('${from}', this.game.getCityName(this.claimingRoute.route.from))
            .replace('${to}', this.game.getCityName(this.claimingRoute.route.to))
            .replace('${colors}', `<div class="color-cards">${colors.join('')}</div>`);
        this.bga.statusBar.setTitle(confirmationQuestion);

        this.bga.statusBar.removeActionButtons();
        this.bga.statusBar.addActionButton(_("Confirm"), () => this.confirmRouteClaim(), { id: `confirmRouteClaim-button`, autoclick: this.bga.userPreferences.get(207) != 2 });
        this.bga.statusBar.addActionButton(_("Cancel"), () => this.cancelRouteClaim(), { color: 'secondary' });
    }

    /** 
     * Claim a route.
     */ 
    public claimRoute() {
        this.bga.actions.performAction('actClaimRoute', {
            routeId: this.claimingRoute.route.id,
            color: this.claimingRoute.color,
            distribution: this.claimingRoute.distribution,
        });
    }
    
    /** 
     * Check if a route can be claimed with dragged cards.
     */ 
    public canClaimRoute(route: Route, cardsColor: number): boolean {
        return (
            route.color == 0 || cardsColor == 0 || route.color == cardsColor
        ) && (
            this.args.possibleRoutes.some(pr => pr.id == route.id)
        );
    }

    /**
     * Check if player should be asked for a route claim confirmation.
     */
    public confirmRouteClaimActive() {
        const preferenceValue = this.bga.userPreferences.get(202);
        return preferenceValue === 1 || (preferenceValue === 2 && this.isTouch);
    }

    /**
     * Check if player should be asked for the color he wants when he clicks on a double route.
     */
    public askDoubleRouteActive() {
        const preferenceValue = this.bga.userPreferences.get(209);
        return preferenceValue === 1;
    }
}