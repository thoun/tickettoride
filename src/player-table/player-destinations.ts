
const IMAGE_ITEMS_PER_ROW = 10;

class PlayerDestinations {
    public playerId: number;
    public destinationStock: Stock;
    private selectedDestination: Destination | null = null;
    private destinationsTodo: Destination[] = [];
    private destinationsDone: Destination[] = [];

    constructor(
        private game: TicketToRideGame, 
        player: TicketToRidePlayer,
        destinations: Destination[],
        completedDestinations: Destination[]) {

        this.playerId = Number(player.id);

        let html = `
        <div id="player-table-${player.id}-destinations-todo" class="player-table-destinations-column todo"></div>
        <div id="player-table-${player.id}-destinations-done" class="player-table-destinations-column done"></div>
        `;

        dojo.place(html, `player-table-${player.id}-destinations`);

        // destination cards

        /*this.destinationStock = new ebg.stock() as Stock;
        this.destinationStock.setSelectionAppearance('class');
        this.destinationStock.selectionClass = 'selected';
        this.destinationStock.create(this.game, $(`player-table-${this.playerId}-destinations`), CARD_WIDTH, CARD_HEIGHT);
        this.destinationStock.setSelectionMode(1);
        this.destinationStock.image_items_per_row = 10;
        this.destinationStock.onItemCreate = (cardDiv: HTMLDivElement, type: number) => setupDestinationCardDiv(cardDiv, type);
        setupDestinationCards(this.destinationStock);
        dojo.connect(this.destinationStock, 'onChangeSelection', this, () => this.activateNextDestination());*/

        this.addDestinations(destinations);
        destinations.filter(destination => completedDestinations.some(d => d.id == destination.id)).forEach(destination => this.markDestinationComplete(destination));
        
        this.activateNextDestination(this.destinationsTodo);
    }
        
    public addDestinations(destinations: Destination[], originStock?: Stock) {
        destinations.forEach(destination => {
            //const from = document.getElementById(`${originStock ? originStock.container_div.id : 'destination-stock'}_item_${destination.id}`)?.id || 'destination-stock';
            //this.destinationStock.addToStockWithId(destination.type_arg, ''+destination.id, from);
            

            const imagePosition = destination.type_arg - 1;
            const row = Math.floor(imagePosition / IMAGE_ITEMS_PER_ROW);
            const xBackgroundPercent = (imagePosition - (row * IMAGE_ITEMS_PER_ROW)) * 100;
            const yBackgroundPercent = row * 100;

            let html = `
            <div id="destination-card-${destination.id}" class="destination-card" style="background-position: -${xBackgroundPercent}% -${yBackgroundPercent}%;"></div>
            `;

            dojo.place(html, `player-table-${this.playerId}-destinations-todo`);

            
            document.getElementById(`destination-card-${destination.id}`).addEventListener('click', () => this.activateNextDestination(
                this.destinationsDone.some(d => d.id == destination.id) ? this.destinationsDone : this.destinationsTodo
            ));
        });

        originStock?.removeAll();

        this.destinationsTodo.push(...destinations);

        this.destinationColumnsUpdated();
    }

    public markDestinationComplete(destination: Destination) {
        //document.getElementById(`${this.destinationStock.container_div.id}_item_${destination.id}`).classList.add('done');

        const index = this.destinationsTodo.findIndex(d => d.id == destination.id);
        if (index !== -1) {
            this.destinationsTodo.splice(index, 1);
        }
        this.destinationsDone.push(destination);

        document.getElementById(`player-table-${this.playerId}-destinations-done`).appendChild(document.getElementById(`destination-card-${destination.id}`));
        this.destinationColumnsUpdated();
    }

    public activateNextDestination(destinationList: Destination[]) {
        const oldSelectedDestination = this.selectedDestination;
        if (this.selectedDestination && destinationList.some(d => d.id == this.selectedDestination.id) && destinationList.length > 1) {
            destinationList.splice(destinationList.length, 0, ...destinationList.splice(0, 1));
        }
        this.selectedDestination = destinationList[0];
        this.game.setActiveDestination(this.selectedDestination, oldSelectedDestination);

        document.getElementById(`player-table-${this.playerId}-destinations-todo`).classList.toggle('front', destinationList == this.destinationsTodo);
        document.getElementById(`player-table-${this.playerId}-destinations-done`).classList.toggle('front', destinationList == this.destinationsDone);

        this.destinationColumnsUpdated();
    }

    private destinationColumnsUpdated() {
        const doubleColumn = this.destinationsTodo.length > 0 && this.destinationsDone.length > 0;

        document.getElementById(`player-table-${this.playerId}`).classList.toggle('double-column-destinations', doubleColumn);
        document.getElementById(`player-table-${this.playerId}-destinations`).classList.toggle('double-column', doubleColumn);

        const maxBottom = Math.max(
            this.placeCards(this.destinationsTodo, doubleColumn ? DESTINATION_CARD_COLUMN_SHIFT : 0),
            this.placeCards(this.destinationsDone),
        );

        document.getElementById(`player-table-${this.playerId}-destinations`).style.height = `${maxBottom + CARD_HEIGHT}px`;
    }

    private placeCards(list: Destination[], originalBottom: number = 0): number {
        let maxBottom = 0;
        list.forEach((destination, index) => {
            const bottom = originalBottom + index * DESTINATION_CARD_SHIFT;
            const card = document.getElementById(`destination-card-${destination.id}`);
            card.parentElement.prepend(card);
            card.style.bottom = `${bottom}px`;

            if (bottom > maxBottom) {
                maxBottom = bottom;
            }
        });

        return maxBottom;
    }
}