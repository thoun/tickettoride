import { TicketToRideGame, Route } from "./tickettoride.d";

/**
 * Animation with highlighted wagons.
 */ 
export abstract class WagonsAnimation {
    protected wagons: Element[] = [];
    protected zoom: number;
    private shadowDiv;

    constructor(
        protected game: TicketToRideGame,
        destinationRoutes: Route[],
    ) {
        this.zoom = this.game.getZoom();
        this.shadowDiv = document.getElementById('map-destination-highlight-shadow');
        destinationRoutes?.forEach(route => this.wagons.push(...Array.from(document.querySelectorAll(`[id^="wagon-route${route.id}-space"]`))));
    }

    protected setWagonsVisibility(visible: boolean) {
        this.shadowDiv.dataset.visible = visible ? 'true' : 'false';
        this.wagons.forEach(wagon => wagon.classList.toggle('highlight', visible));
    }

    public abstract animate(): Promise<WagonsAnimation>;
}
