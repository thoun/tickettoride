
/**
 * Animation with highlighted wagons.
 */ 
abstract class WagonsAnimation {
    protected wagons: Element[] = [];
    protected stations: Element[] = [];
    protected zoom: number;
    private shadowDiv;

    constructor(
        protected game: TicketToRideGame,
        destinationRoutes: Route[],
        destinationStations?: number[],
    ) {
        this.zoom = this.game.getZoom();
        this.shadowDiv = document.getElementById('map-destination-highlight-shadow');
        destinationRoutes?.forEach(route => this.wagons.push(...Array.from(document.querySelectorAll(`[id^="wagon-route${route.id}-space"]`))));
        destinationStations?.forEach(cityId => this.stations.push(document.getElementById(`station${cityId}`)));
    }

    protected setWagonsVisibility(visible: boolean) {
        this.shadowDiv.dataset.visible = visible ? 'true' : 'false';
        this.wagons.forEach(wagon => wagon.classList.toggle('highlight', visible));
        this.stations.forEach(station => station.classList.toggle('highlight', visible));
    }

    public abstract animate(): Promise<WagonsAnimation>;
}
