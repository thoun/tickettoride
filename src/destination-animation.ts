type DestinationAnimationCallback = (destination: Destination) => void;

class DestinationCompleteAnimation {
    private wagons: Element[] = [];
    private zoom: number;

    constructor(
        private game: TicketToRideGame,
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
        destinationRoutes?.forEach(route => this.wagons.push(...Array.from(document.querySelectorAll(`[id^="wagon-route${route.id}-space"]`))));
    }

    public animate(): Promise<DestinationCompleteAnimation> {
        this.zoom = this.game.getZoom();
        return new Promise(resolve => {
            const fromBR = document.getElementById(this.fromId).getBoundingClientRect();

            dojo.place(`
            <div id="animated-destination-card-${this.destination.id}" class="destination-card" style="${this.getCardPosition(this.destination)}${getBackgroundInlineStyleForDestination(this.destination)}"></div>
            `, 'map');

            const card = document.getElementById(`animated-destination-card-${this.destination.id}`);
            this.actions.start?.(this.destination);
            const cardBR = card.getBoundingClientRect();
            
            const x = (fromBR.x - cardBR.x) / this.zoom;
            const y = (fromBR.y - cardBR.y) / this.zoom;
            card.style.transform = `translate(${x}px, ${y}px) scale(${this.initialSize})`;
    
            const shadow = document.getElementById('map-destination-highlight-shadow');
            shadow.dataset.visible = 'true';
            this.wagons.forEach(wagon => wagon.classList.add('highlight'));
            this.game.setSelectedDestination(this.destination, true);

            setTimeout(() => {
                card.classList.add('animated');
                card.style.transform = ``;

                this.markComplete(card, cardBR, resolve);
            }, 100);
        });
    }
    
    private markComplete(card: HTMLElement, cardBR: DOMRect, resolve: any) {
        
        setTimeout(() => {
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
        const shadow = document.getElementById('map-destination-highlight-shadow');
        shadow.dataset.visible = 'false';
        this.wagons.forEach(wagon => wagon.classList.remove('highlight'));
        this.game.setSelectedDestination(this.destination, false);

        resolve(this);

        this.game.endAnimation(this);
        this.actions.end?.(this.destination);

        card.parentElement.removeChild(card);
    }
    
    private getCardPosition(destination: Destination) {
        const positions = [destination.from, destination.to].map(cityId => CITIES.find(city => city.id == cityId));

        let x = (positions[0].x + positions[1].x) / 2;
        let y = (positions[0].y + positions[1].y) / 2;

        return `left: ${x - CARD_WIDTH/2}px; top: ${y - CARD_HEIGHT/2}px;`;
    }
}
