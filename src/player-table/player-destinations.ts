
const IMAGE_ITEMS_PER_ROW = 10;

class DestinationCompleteAnimation {
    private wagons: Element[] = [];

    constructor(
        private destination: Destination,
        destinationRoutes: Route[],
        private zoom: number,
        private left: boolean,
        private markDestinationCompleteNoAnimation: (destination: Destination) => void,
    ) {
        destinationRoutes.forEach(route => this.wagons.push(...Array.from(document.querySelectorAll(`[id^="wagon-route${route.id}-space"]`))));
    }

    public animate(): Promise<DestinationCompleteAnimation> {
        return new Promise(resolve => {
            let x = this.left ? 1544 : 1270;
            let y = this.left ? 56 : -230;
            const card = document.getElementById(`destination-card-${this.destination.id}`);
            card.classList.add('animated');
            card.style.transform = `translate(${x}px, ${y}px)`;
    
            const shadow = document.getElementById('map-destination-highlight-shadow');
            shadow.dataset.visible = 'true';
    
            setTimeout(() => {
                card.classList.remove('animated');
    
                setTimeout(() => {
    
                    const brBefore = card.getBoundingClientRect();
                    this.markDestinationCompleteNoAnimation(this.destination);
                    const brAfter = card.getBoundingClientRect();
                    x += (brBefore.x - brAfter.x)/this.zoom;
                    y += (brBefore.y - brAfter.y)/this.zoom;
                    card.style.transform = `translate(${x}px, ${y}px)`;
                    this.wagons.forEach(wagon => wagon.classList.add('highlight'));
    
                    setTimeout(() => {
                        card.classList.add('animated');
                        
                        setTimeout(() => {
                            const shadow = document.getElementById('map-destination-highlight-shadow');
                            shadow.dataset.visible = 'false';
    
                            card.style.transform = ``;
    
                            setTimeout(() => {
                                this.wagons.forEach(wagon => wagon.classList.remove('highlight'));
                                resolve(this);
                            }, 500);
                        }, 500);
                    }, 500);
    
                }, 1);
            }, 750);
        });
    }
}

class PlayerDestinations {
    public playerId: number;
    public destinationStock: Stock;
    private selectedDestination: Destination | null = null;
    private destinationsTodo: Destination[] = [];
    private destinationsDone: Destination[] = [];
    private animations: DestinationCompleteAnimation[] = [];
    private left: boolean;

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

    public setPosition(left: boolean) {
        this.left = left;
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

    public markDestinationCompleteNoAnimation(destination: Destination) {
        const index = this.destinationsTodo.findIndex(d => d.id == destination.id);
        if (index !== -1) {
            this.destinationsTodo.splice(index, 1);
        }
        this.destinationsDone.push(destination);

        document.getElementById(`player-table-${this.playerId}-destinations-done`).appendChild(document.getElementById(`destination-card-${destination.id}`));
        this.destinationColumnsUpdated();
    }

    public markDestinationComplete(destination: Destination, destinationRoutes?: Route[]) {
        if (destinationRoutes && !(document.visibilityState === 'hidden' || (this.game as any).instantaneousMode)) {
            const newDac = new DestinationCompleteAnimation(destination, destinationRoutes, this.game.getZoom(), this.left, d => this.markDestinationCompleteNoAnimation(d));

            this.animations.push(newDac);
            if (this.animations.length === 1) {
                this.animations[0].animate().then(dac => this.endAnimation(dac));
            };
        } else {
            this.markDestinationCompleteNoAnimation(destination);
        }
    }

    public endAnimation(ended: DestinationCompleteAnimation) {
        const index = this.animations.indexOf(ended);
        if (index !== -1) {
            this.animations.splice(index, 1);
        }

        if (this.animations.length >= 1) {
            this.animations[0].animate().then(dac => this.endAnimation(dac));
        };
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

        document.getElementById(`player-table`).classList.toggle('double-column-destinations', doubleColumn);
        const destinationsDiv = document.getElementById(`player-table-${this.playerId}-destinations`);
        destinationsDiv .classList.toggle('double-column', doubleColumn);

        const maxBottom = Math.max(
            this.placeCards(this.destinationsTodo, doubleColumn ? DESTINATION_CARD_SHIFT : 0),
            this.placeCards(this.destinationsDone),
        );

        const height = `${maxBottom + CARD_HEIGHT}px`;
        destinationsDiv.style.height = height;
        document.getElementById(`player-table-${this.playerId}-train-cars`).style.height = height;

        this.game.setDestinationsToConnect(this.destinationsTodo);
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
        const zoom = this.game.getZoom();
        card.style.transform = `translate(${-deltaX/zoom}px, ${-deltaY/zoom}px)`;
        setTimeout(() => card.style.transform = null);

        setTimeout(() => {
            card.style.zIndex = null;
            card.style.transition = null;
        }, 500);
    }
}