
const IMAGE_ITEMS_PER_ROW = 10;

/** 
 * Player's destination cards.
 */ 
class PlayerDestinations {
    public playerId: number;
    /** Highlighted destination on the map */ 
    private selectedDestination: Destination | null = null;
    /** Destinations in "to do" column */ 
    private destinationsTodo: Destination[] = [];
    /** Destinations in "done" column */ 
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
        
        // highlight the first "to do" destination
        this.activateNextDestination(this.destinationsTodo);
    }
        
    /** 
     * Add destinations to player's hand.
     */ 
    public addDestinations(destinations: Destination[], originStock?: Stock) {
        destinations.forEach(destination => {
            let html = `
            <div id="destination-card-${destination.id}" class="destination-card" style="${getBackgroundInlineStyleForDestination(this.game.getMap(), destination)}"></div>
            `;

            dojo.place(html, `player-table-${this.playerId}-destinations-todo`);
            
            const card = document.getElementById(`destination-card-${destination.id}`) as HTMLDivElement;
            setupDestinationCardDiv(this.game, card, destination.type * 1000 + destination.type_arg);
            /*
            Can't add a tooltip, because showing a tooltip will mess with the hover effect.
            const DESTINATIONS = this.getDestinations(game);
            const destinationInfos = DESTINATIONS.find(d => d.id == destination.type_arg);            
            this.game.setTooltip(`destination-card-${destination.id}`, `
                <div>${dojo.string.substitute(_('${from} to ${to}'), {from: this.game.getCityName(destinationInfos.from), to: this.game.getCityName(destinationInfos.to)})}, ${destinationInfos.points} ${_('points')}</div>
                <div class="destination-card" style="${getBackgroundInlineStyleForDestination(destination)}"></div>
            `);*/

            card.addEventListener('click', () => this.activateNextDestination(
                this.destinationsDone.some(d => d.id == destination.id) ? this.destinationsDone : this.destinationsTodo
            ));
            
            // highlight destination's cities on the map, on mouse over
            card.addEventListener('mouseenter', () => this.game.setHighligthedDestination(destination));
            card.addEventListener('mouseleave', () => this.game.setHighligthedDestination(null));

            if (originStock) {
                this.addAnimationFrom(card, document.getElementById(`${originStock.container_div.id}_item_${destination.id}`));
            }
        });

        originStock?.removeAll();

        this.destinationsTodo.push(...destinations);

        this.destinationColumnsUpdated();
    }

    /** 
     * Mark destination as complete (place it on the "complete" column).
     */ 
    public markDestinationCompleteNoAnimation(destination: Destination) {
        const index = this.destinationsTodo.findIndex(d => d.id == destination.id);
        if (index !== -1) {
            this.destinationsTodo.splice(index, 1);
        }
        this.destinationsDone.push(destination);

        const card = document.getElementById(`destination-card-${destination.id}`);
        document.getElementById(`player-table-${this.playerId}-destinations-done`).appendChild(card);
        if (Array.isArray(destination.to)) {
            card.classList.add('no-mask');
        }
        this.destinationColumnsUpdated();
    }

    /** 
     * Add an animation to mark a destination as complete.
     */ 
    public markDestinationCompleteAnimation(destination: Destination, destinationRoutes: Route[]) {
        const newDac = new DestinationCompleteAnimation(
            this.game,
            destination, 
            destinationRoutes, 
            `destination-card-${destination.id}`,
            `destination-card-${destination.id}`,
            { 
                start: d => document.getElementById(`destination-card-${d.id}`).classList.add('hidden-for-animation'),
                change: d => this.markDestinationCompleteNoAnimation(d),
                end: d => document.getElementById(`destination-card-${d.id}`).classList.remove('hidden-for-animation'),
            },
            'completed'
        );

        this.game.addAnimation(newDac);
    }

    /** 
     * Mark a destination as complete.
     */ 
    public markDestinationComplete(destination: Destination, destinationRoutes?: Route[]) {
        if (destinationRoutes && !(document.visibilityState === 'hidden' || (this.game as any).instantaneousMode)) {
            this.markDestinationCompleteAnimation(destination, destinationRoutes);
        } else {
            this.markDestinationCompleteNoAnimation(destination);
        }
    }

    /** 
     * Highlight another destination.
     */ 
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

    /** 
     * Update destination cards placement when there is a change.
     */ 
    private destinationColumnsUpdated() {
        const doubleColumn = this.destinationsTodo.length > 0 && this.destinationsDone.length > 0;

        const destinationsDiv = document.getElementById(`player-table-${this.playerId}-destinations`);

        const maxBottom = Math.max(
            this.placeCards(this.destinationsTodo, doubleColumn ? DESTINATION_CARD_SHIFT : 0),
            this.placeCards(this.destinationsDone),
        );

        const height = `${maxBottom + CARD_HEIGHT}px`;
        destinationsDiv.style.height = height;
        document.getElementById(`player-table-${this.playerId}-train-cars`).style.height = height;

        this.game.setDestinationsToConnect(this.destinationsTodo);
    }

    /** 
     * Place cards on a column.
     */ 
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
    
    /** 
     * Add an animation to the card (when it is created).
     */ 
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
        const zoom = this.game.getZoom();
        card.style.transform = `translate(${-deltaX/zoom}px, ${-deltaY/zoom}px)`;
        setTimeout(() => card.style.transform = null);

        setTimeout(() => {
            card.style.zIndex = null;
            card.style.transition = null;
        }, 500);
    }
}
