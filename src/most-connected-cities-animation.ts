import { TicketToRideGame, Route } from "./tickettoride.d";
import { WagonsAnimation } from "./wagons-animation";

/**
 * Highlights all routes and cities in a player's largest connected network.
 */
export class MostConnectedCitiesAnimation extends WagonsAnimation {
    private cities: HTMLElement[];

    constructor(
        game: TicketToRideGame,
        routes: Route[],
        cityIds: number[],
        private length: number,
        private playerColor: string,
        private actions: {
            end?: () => void,
        },
    ) {
        super(game, routes);
        this.cities = cityIds
            .map(cityId => document.getElementById(`city${cityId}`))
            .filter((city): city is HTMLElement => city !== null);
    }

    public animate(): Promise<WagonsAnimation> {
        return new Promise(resolve => {
            document.getElementById('map').insertAdjacentHTML('beforeend', `
            <div id="most-connected-cities-animation" style="color: #${this.playerColor};${this.getPosition()}">${this.length}</div>
            `);
            this.cities.forEach(city => city.dataset.highlight = 'true');
            this.setWagonsVisibility(true);
            setTimeout(() => this.endAnimation(resolve), 1900);
        });
    }

    private endAnimation(resolve: any) {
        this.setWagonsVisibility(false);
        this.cities.forEach(city => city.dataset.highlight = 'false');
        document.getElementById('most-connected-cities-animation')?.remove();
        resolve(this);
        this.game.endAnimation(this);
        this.actions.end?.();
    }

    private getPosition() {
        if (this.cities.length === 0) {
            return 'left: 100px; top: 100px;';
        }
        const positions = this.cities.map(city => this.game.getMap().cities[Number(city.id.replace('city', ''))]);
        const x = positions.reduce((sum, city) => sum + city.x, 0) / positions.length;
        const y = positions.reduce((sum, city) => sum + city.y, 0) / positions.length;
        return `left: ${x}px; top: ${y}px;`;
    }
}
