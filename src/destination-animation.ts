type DestinationAnimationCallback = (destination: Destination) => void;

/**
 * Destination animation : destination slides over the map, wagons used by destination are highlighted, destination is mark "done" or "uncomplete", and card slides back to original place.
 */ 
class DestinationCompleteAnimation extends WagonsAnimation {

    constructor(
        game: TicketToRideGame,
        private destination: Destination,
        destinationRoutes: Route[],
        private fromId: string,
        private toId: string,
        private actions: {
            start?: DestinationAnimationCallback,
            change?: DestinationAnimationCallback,
            end?: DestinationAnimationCallback,
        },
        private state: 'completed' | 'uncompleted',
        private initialSize: number = 1,
    ) {
        super(game, destinationRoutes);
    }

    public animate(): Promise<WagonsAnimation> {
        return new Promise(resolve => {
            const fromBR = document.getElementById(this.fromId).getBoundingClientRect();

            dojo.place(`
            <div id="animated-destination-card-${this.destination.id}" class="destination-card" style="${this.getCardPosition(this.destination)}${getBackgroundInlineStyleForDestination(this.game.getMap(), this.destination)}"></div>
            `, 'map');

            const noMask = Array.isArray(this.destination.to);
            const card = document.getElementById(`animated-destination-card-${this.destination.id}`);
            if (noMask) {
                card.classList.add('no-mask');
            }
            this.actions.start?.(this.destination);
            const cardBR = card.getBoundingClientRect();
            
            const x = (fromBR.x - cardBR.x) / this.zoom;
            const y = (fromBR.y - cardBR.y) / this.zoom;
            card.style.transform = `translate(${x}px, ${y}px) scale(${this.initialSize})`;
    
            this.setWagonsVisibility(true);
            this.game.setSelectedDestination(this.destination, true);

            setTimeout(() => {
                card.classList.add('animated');
                card.style.transform = ``;

                this.markComplete(card, cardBR, resolve, noMask);
            }, 100);
        });
    }
    
    private markComplete(card: HTMLElement, cardBR: DOMRect, resolve: any, noMask: boolean) {
        
        setTimeout(() => {
            if (noMask) {
                card.classList.add('no-mask');
            }
            card.classList.add(this.state);
            this.actions.change?.(this.destination);

            setTimeout(() => {
                const toBR = document.getElementById(this.toId).getBoundingClientRect();
                
                const x = (toBR.x - cardBR.x) / this.zoom;
                const y = (toBR.y - cardBR.y) / this.zoom;
                card.style.transform = `translate(${x}px, ${y}px) scale(${this.initialSize})`;
                
                setTimeout(() => this.endAnimation(resolve, card), 500);
            }, 500);
        }, 750);
    }

    private endAnimation(resolve: any, card: HTMLElement) {
        this.setWagonsVisibility(false);
        this.game.setSelectedDestination(this.destination, false);

        resolve(this);

        this.game.endAnimation(this);
        this.actions.end?.(this.destination);

        card.parentElement.removeChild(card);
    }
    
    private getCardPosition(destination: Destination) {
        if (Array.isArray(destination.to)) {
            const from = this.game.getMap().cities[destination.from];
            return `left: ${from.x - CARD_WIDTH/2}px; top: ${from.y - CARD_HEIGHT/2}px;`;
        } else {
            const positions = [destination.from, destination.to].map(cityId => this.game.getMap().cities[cityId]);

            let x = (positions[0].x + positions[1].x) / 2;
            let y = (positions[0].y + positions[1].y) / 2;

            return `left: ${x - CARD_WIDTH/2}px; top: ${y - CARD_HEIGHT/2}px;`;
        }
    }
}
