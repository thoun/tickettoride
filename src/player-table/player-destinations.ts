
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

        this.addDestinations(destinations);
        destinations.filter(destination => completedDestinations.some(d => d.id == destination.id)).forEach(destination => 
            this.markDestinationComplete(destination)
        );
        
        this.activateNextDestination(this.destinationsTodo);
    }
        
    public addDestinations(destinations: Destination[], originStock?: Stock) {
        destinations.forEach(destination => {
            

            const imagePosition = destination.type_arg - 1;
            const row = Math.floor(imagePosition / IMAGE_ITEMS_PER_ROW);
            const xBackgroundPercent = (imagePosition - (row * IMAGE_ITEMS_PER_ROW)) * 100;
            const yBackgroundPercent = row * 100;

            let html = `
            <div id="destination-card-${destination.id}" class="destination-card" style="background-position: -${xBackgroundPercent}% -${yBackgroundPercent}%;"></div>
            `;

            dojo.place(html, `player-table-${this.playerId}-destinations-todo`);
            
            const card = document.getElementById(`destination-card-${destination.id}`);
            card.addEventListener('click', () => this.activateNextDestination(
                this.destinationsDone.some(d => d.id == destination.id) ? this.destinationsDone : this.destinationsTodo
            ));

            if (originStock) {
                this.addAnimationFrom(card, document.getElementById(`${originStock.container_div.id}_item_${destination.id}`));
            }
        });

        originStock?.removeAll();

        this.destinationsTodo.push(...destinations);

        this.destinationColumnsUpdated();
    }

    public markDestinationComplete(destination: Destination) {
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
            this.placeCards(this.destinationsTodo, doubleColumn ? DESTINATION_CARD_SHIFT : 0),
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
    
    private addAnimationFrom(card: HTMLElement, from: HTMLElement) {
        if (document.visibilityState === 'hidden' || (this.game as any).instantaneousMode) {
            return;
        }

        const destinationBR = card.getBoundingClientRect();
        const originBR = from.getBoundingClientRect();

        const deltaX = destinationBR.left - originBR.left;
        const deltaY = destinationBR.top - originBR.top;

        card.style.zIndex = '10';
        card.style.transition = `transform 0.5s linear`;
        const zoom = 1; // TODO?
        card.style.transform = `translate(${-deltaX/zoom}px, ${-deltaY/zoom}px)`;
        setTimeout(() => card.style.transform = null);

        setTimeout(() => {
            card.style.zIndex = null;
            card.style.transition = null;            
        }, 500);
    }
}
