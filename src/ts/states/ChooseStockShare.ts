import { Game } from "../Game";

export interface EnteringChooseStockShareArgs {
    routeId: number;
    stockShares: number[];
}

export class ChooseStockShareState {
    constructor(protected game: Game, protected bga: Bga) {}

    private onTableClick = (event: MouseEvent) => {
        const card = (event.target as Element).closest<HTMLElement>('.stock-shares-card.selectable');
        if (card) {
            this.bga.actions.performAction('actChooseStockShare', { type: Number(card.dataset.type) });
        }
    };

    public onEnteringState(args: EnteringChooseStockShareArgs, isCurrentPlayerActive: boolean) {
        if (isCurrentPlayerActive) {
            const tableZone = this.game.getPlayerZoneContentElement('table');
            const cards = tableZone.querySelectorAll<HTMLElement>('.stock-shares-card');
            cards.forEach(card => {
                const selectable = isCurrentPlayerActive && args.stockShares.includes(Number(card.dataset.type));
                card.classList.toggle('selectable', selectable);
                card.classList.toggle('disabled', !selectable);
            });
            tableZone.addEventListener('click', this.onTableClick);
        }
    }
    public onLeavingState(args: EnteringChooseStockShareArgs, isCurrentPlayerActive: boolean) {
        if (isCurrentPlayerActive) {
            const tableZone = this.game.getPlayerZoneContentElement('table');
            tableZone.removeEventListener('click', this.onTableClick);
            const cards = tableZone.querySelectorAll<HTMLElement>('.stock-shares-card');
            cards.forEach(card => card.classList.remove('selectable', 'disabled'));
        }
    }
}
