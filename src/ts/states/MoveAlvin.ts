import { Game } from '../Game';

interface EnteringMoveAlvinArgs {
    possibleCityIds: number[];
}

export class MoveAlvinState {
    private possibleCityIds: number[] = [];
    private active = false;

    constructor(private game: Game, private bga: Bga) {}

    private onCityClick = (event: MouseEvent) => {
        const city = (event.target as Element).closest<HTMLElement>('.city');
        if (!city) {
            return;
        }
        event.stopPropagation();
        const cityId = Number(city.dataset.cityId);
        if (this.active && this.possibleCityIds.includes(cityId)) {
            this.bga.actions.performAction('actMoveAlvin', { cityId });
        }
    };

    public onEnteringState(args: EnteringMoveAlvinArgs, isCurrentPlayerActive: boolean) {
        this.possibleCityIds = args.possibleCityIds;
        this.active = isCurrentPlayerActive;
        this.game.trainCarSelection.setSelectableTopDeck(false, 0);
        this.game.trainCarSelection.setSelectableVisibleCards([]);
        this.game.map.setSelectableStations(this.active, this.possibleCityIds);
        const cities = document.getElementById('cities');
        cities.classList.toggle('moving-alvin', this.active);
        cities.addEventListener('click', this.onCityClick, true);
        if (this.active) {
            this.bga.statusBar.removeActionButtons();
        }
    }

    public onLeavingState() {
        const cities = document.getElementById('cities');
        cities.removeEventListener('click', this.onCityClick, true);
        cities.classList.remove('moving-alvin');
        this.game.map.setSelectableStations(false, null);
        this.game.trainCarSelection.removeSelectableVisibleCards();
        this.possibleCityIds = [];
        this.active = false;
    }
}
