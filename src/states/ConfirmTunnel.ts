interface EnteringConfirmTunnelArgs {
    playerId: number;
    tunnelAttempt: TunnelAttempt;
    canPay: boolean;
}

class ConfirmTunnelState extends StateHandler<EnteringConfirmTunnelArgs> {
    public match(): string { return `confirmTunnel`; }

    public onEnteringState(args: EnteringConfirmTunnelArgs, isCurrentPlayerActive: boolean) {
        const route = this.game.getMap().routes[args.tunnelAttempt.routeId];
        this.game.map.setHoveredRoute(route, true, this.game.gamedatas.players[args.playerId]);
        this.game.trainCarSelection.showTunnelCards(args.tunnelAttempt.tunnelCards);
    }

    public onLeavingState(args: EnteringConfirmTunnelArgs, isCurrentPlayerActive: boolean) {
        this.game.map.setHoveredRoute(null);
        this.game.trainCarSelection.showTunnelCards([]);
    }

    public onUpdateActionButtons(args: EnteringConfirmTunnelArgs, isCurrentPlayerActive: boolean) {
        if (isCurrentPlayerActive) {
            const confirmLabel = _("Confirm tunnel claim") + (args.canPay ? '' : ` (${_("You don't have enough cards")})`);
            // Claim a tunnel (confirm paying extra cost).
            this.game.statusBar.addActionButton(confirmLabel, () => this.game.bgaPerformAction('actClaimTunnel'), { disabled: !args.canPay });
            // Skip a tunnel (deny paying extra cost).
            this.game.statusBar.addActionButton(_("Skip tunnel claim"), () => this.game.bgaPerformAction('actSkipTunnel'), { color: 'secondary'});
        }
    }
}