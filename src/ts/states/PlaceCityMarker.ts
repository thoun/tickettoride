import { Game } from '../Game';
import { getColor } from '../stock-utils';

interface EnteringPlaceCityMarkerArgs {
    possibleCityIds: number[];
    costByColor?: {[color: number]: number[]};
}

export class PlaceCityMarkerState {
    private args: EnteringPlaceCityMarkerArgs;
    private active = false;

    constructor(private game: Game, private bga: Bga) {}

    private onCityClick = (event: MouseEvent) => {
        const city = (event.target as Element).closest<HTMLElement>('.city');
        if (!city) {
            return;
        }
        event.stopPropagation();
        const cityId = Number(city.dataset.cityId);
        if (this.active && this.args.possibleCityIds.includes(cityId)) {
            this.showColors(cityId);
        }
    };

    public onEnteringState(args: EnteringPlaceCityMarkerArgs, isCurrentPlayerActive: boolean) {
        this.args = args;
        this.active = isCurrentPlayerActive;
        this.game.trainCarSelection.setSelectableTopDeck(false, 0);
        this.game.trainCarSelection.setSelectableVisibleCards([]);
        this.game.map.setSelectableStations(this.active, args.possibleCityIds);
        const cities = document.getElementById('cities');
        cities.classList.toggle('placing-city-marker', this.active);
        cities.addEventListener('click', this.onCityClick, true);
        this.showCities();
    }

    private showCities() {
        if (!this.active) {
            return;
        }
        this.bga.statusBar.removeActionButtons();
        this.bga.statusBar.setTitle(_('${you} may place a City Marker'));
        this.addPassButton();
    }

    private showColors(cityId: number) {
        this.bga.statusBar.removeActionButtons();
        this.bga.statusBar.setTitle(_('Choose a card color to place a City Marker in ${city_name}')
            .replace('${city_name}', this.game.getCityName(cityId)));
        Object.entries(this.args.costByColor ?? {}).forEach(([color, cards]) => {
            const icons = cards.map(type => `<div class="train-car-color icon" data-color="${type}"></div>`).join('');
            const label = `${getColor(Number(color), 'train-car')} <span class="color-cards">${icons}</span>`;
            this.bga.statusBar.addActionButton(label, () => this.bga.actions.performAction('actPlaceCityMarker', {
                cityId,
                color: Number(color),
            }));
        });
        this.bga.statusBar.addActionButton(_('Cancel'), () => this.showCities(), { color: 'secondary' });
        this.addPassButton();
    }

    private addPassButton() {
        this.bga.statusBar.addActionButton(_('Pass'), () => this.bga.actions.performAction('actPassCityMarker'), { color: 'secondary' });
    }

    public onLeavingState() {
        const cities = document.getElementById('cities');
        cities.removeEventListener('click', this.onCityClick, true);
        cities.classList.remove('placing-city-marker');
        this.game.map.setSelectableStations(false, null);
        this.game.trainCarSelection.removeSelectableVisibleCards();
        this.active = false;
    }
}
