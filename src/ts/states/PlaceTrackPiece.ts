import { Game } from '../Game';
import { getColor } from '../stock-utils';

interface EnteringPlaceTrackPieceArgs {
    possibleRouteIds: number[];
    remainingTrackPieces: {[color: number]: {[length: number]: number}};
}

export class PlaceTrackPieceState {
    private args: EnteringPlaceTrackPieceArgs;
    private active = false;

    constructor(private game: Game, private bga: Bga) {}

    public onEnteringState(args: EnteringPlaceTrackPieceArgs, isCurrentPlayerActive: boolean) {
        this.args = args;
        this.active = isCurrentPlayerActive;
        this.game.trainCarSelection.setSelectableTopDeck(false, 0);
        this.game.trainCarSelection.setSelectableVisibleCards([]);
        this.showRoutes();
    }

    private showRoutes() {
        this.game.map.setSelectableRoutes(this.active, this.args.possibleRouteIds.map(id => this.game.getMap().routes[id]));
        if (this.active) {
            this.bga.statusBar.removeActionButtons();
            this.bga.statusBar.setTitle(_('${you} must choose a route to place a Track Piece'));
        }
    }

    public clickedRoute(route: Route) {
        if (!this.active || !this.args.possibleRouteIds.includes(route.id)) {
            return;
        }
        const length = route.spaces.length;
        this.bga.statusBar.removeActionButtons();
        this.bga.statusBar.setTitle(_('Choose a Track Piece colour for ${from} to ${to}')
            .replace('${from}', this.game.getCityName(route.from))
            .replace('${to}', this.game.getCityName(route.to)));
        Object.entries(this.args.remainingTrackPieces).forEach(([color, counts]) => {
            const label = `<div class="train-car-color icon" data-color="${color}"></div> ${getColor(Number(color), 'route')}`;
            this.bga.statusBar.addActionButton(label, () => this.bga.actions.performAction('actPlaceTrackPiece', {
                routeId: route.id,
                color: Number(color),
            }), { disabled: !counts[length]});
        });
        this.bga.statusBar.addActionButton(_('Cancel'), () => this.showRoutes(), { color: 'secondary' });
    }

    public onLeavingState() {
        this.active = false;
        this.game.map.setSelectableRoutes(false, []);
        this.game.trainCarSelection.removeSelectableVisibleCards();
    }
}
