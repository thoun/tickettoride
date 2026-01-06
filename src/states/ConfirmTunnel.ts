import { DistributionPopin, DistributionResult } from "../distribution-popin";
import { TicketToRideGame, TrainCar, TunnelAttempt } from "../tickettoride.d";
import { LOCOMOTIVE_TUNNEL, LOCOMOTIVE_FERRY } from "./ChooseAction";

export interface EnteringConfirmTunnelArgs {
    playerId: number;
    tunnelAttempt: TunnelAttempt;
    canPay: boolean;
    _private?: {
        trainCarsHand: TrainCar[];
    }
}

export class ConfirmTunnelState {
    protected game: TicketToRideGame;
    protected bga: Bga;

    constructor(game: TicketToRideGame, bga: Bga) {
        this.game = game;
        this.bga = bga;
    }

    public onEnteringState(args: EnteringConfirmTunnelArgs, isCurrentPlayerActive: boolean) {
        const route = this.game.getMap().routes[args.tunnelAttempt.routeId];
        this.game.map.setHoveredRoute(route, true, this.game.gamedatas.players[args.playerId]);
        this.game.trainCarSelection.showTunnelCards(args.tunnelAttempt.tunnelCards);

        if (isCurrentPlayerActive) {
            const confirmLabel = _("Confirm tunnel claim") + (args.canPay ? '' : ` (${_("You don't have enough cards")})`);
            // Claim a tunnel (confirm paying extra cost).
            this.bga.statusBar.addActionButton(
                confirmLabel, 
                () => {
                    if (args.tunnelAttempt.distribution) {
                        const locomotiveRestriction = this.game.getMap().locomotiveUsageRestriction;
                        const canUseLocomotives = locomotiveRestriction === 0
                            || ((locomotiveRestriction & LOCOMOTIVE_TUNNEL) !== 0 && route.tunnel)
                            || ((locomotiveRestriction & LOCOMOTIVE_FERRY) !== 0 && route.locomotives > 0);
                            
                        const confirmationQuestion = _("Cards including extra cost");
                        new DistributionPopin(args._private.trainCarsHand, { route, color: args.tunnelAttempt.color, distribution: args.tunnelAttempt.distribution }, args.tunnelAttempt.distribution.length + args.tunnelAttempt.extraCards, canUseLocomotives).show(confirmationQuestion).then((distribution: DistributionResult) => {
                            if (distribution) {
                                this.bga.actions.performAction('actClaimTunnel', { distribution: distribution.cardIds });
                            }
                        });
                    } else {
                        this.bga.actions.performAction('actClaimTunnel');
                    }
                }, 
                { disabled: !args.canPay }
            );
            // Skip a tunnel (deny paying extra cost).
            this.bga.statusBar.addActionButton(
                _("Skip tunnel claim"), 
                () => this.bga.actions.performAction('actSkipTunnel'), 
                { color: 'secondary'}
            );
        }
    }

    public onLeavingState(args: EnteringConfirmTunnelArgs, isCurrentPlayerActive: boolean) {
        this.game.map.setHoveredRoute(null);
        this.game.trainCarSelection.showTunnelCards([]);
    }
}