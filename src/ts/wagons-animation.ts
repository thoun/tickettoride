import { Game } from "./Game";

/**
 * Animation with highlighted wagons.
 */ 
export abstract class WagonsAnimation {
    protected highlightedPieces: Element[] = [];
    protected zoom: number;
    private shadowDiv: HTMLDivElement;

    constructor(
        protected game: Game,
        destinationRoutes: Route[],
        stationCityIds: number[] = [],
    ) {
        this.zoom = this.game.getZoom();
        this.shadowDiv = document.getElementById('map-destination-highlight-shadow') as HTMLDivElement;
        destinationRoutes?.forEach(route => this.highlightedPieces.push(...Array.from(document.querySelectorAll(`[id^="wagon-route${route.id}-space"]`))));
        stationCityIds.forEach(cityId => {
            const station = document.getElementById(`station${cityId}`);
            if (station) {
                this.highlightedPieces.push(station);
            }
        });
    }

    protected setWagonsVisibility(visible: boolean) {
        this.shadowDiv.dataset.visible = visible ? 'true' : 'false';
        this.highlightedPieces.forEach(piece => piece.classList.toggle('highlight', visible));
    }

    public abstract animate(): Promise<WagonsAnimation>;
}
