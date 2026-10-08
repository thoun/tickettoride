import { Game } from "../Game";

interface EnteringChooseStartingCityArgs {
    possibleCityIds: number[];
}

export class ChooseStartingCityState {
    private possibleCityIds: number[] = [];
    private active = false;

    constructor(private game: Game, private bga: Bga) {}

    private onCityClick = (event: MouseEvent) => {
        const city = (event.target as Element).closest<HTMLElement>('.city');
        if (!city) {
            return;
        }
        // This state owns city selection; do not trigger station payment.
        event.stopPropagation();
        const cityId = Number(city.dataset.cityId);
        if (this.active && this.possibleCityIds.includes(cityId)) {
            this.bga.actions.performAction('actChooseStartingCity', { cityId });
        }
    };

    public onEnteringState(args: EnteringChooseStartingCityArgs, isCurrentPlayerActive: boolean) {
        this.possibleCityIds = args.possibleCityIds;
        this.active = isCurrentPlayerActive;
        this.game.map.setSelectableStations(this.active, this.possibleCityIds);
        const cities = document.getElementById('cities');
        cities.classList.toggle('choosing-starting-city', this.active);
        cities.addEventListener('click', this.onCityClick, true);
    }

    public onLeavingState() {
        document.getElementById('cities').removeEventListener('click', this.onCityClick, true);
        document.getElementById('cities').classList.remove('choosing-starting-city');
        this.game.map.setSelectableStations(false, null);
        this.possibleCityIds = [];
        this.active = false;
    }
}
