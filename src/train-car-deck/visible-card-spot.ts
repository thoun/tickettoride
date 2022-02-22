/** 
 * Selection of new train cars.
 */ 
class VisibleCardSpot {
    public visibleCardsStock: Stock;
    private card: TrainCar;

    /**
     * Init stocks and gauges.
     */ 
    constructor(
        private game: TicketToRideGame,
        private spotNumber: number,
    ) {
    }
    
    /**
     * Set selectable visible cards (locomotive can't be selected if 1 visible card has been picked).
     */ 
    public setSelectableVisibleCards(availableVisibleCards: TrainCar[]) {
        this.getCardDiv().classList.toggle('disabled', !availableVisibleCards.some(card => card.id == this.card.id));
    }

    /**
     * Reset visible cards state.
     */ 
    public removeSelectableVisibleCards() {
        this.getCardDiv().classList.remove('disabled');
    }

    /**
     * Set new visible cards.
     */ 
    public setNewCardOnTable(card: TrainCar, fromDeck: boolean) {
        if (this.card) {
            const oldCardDiv = this.getCardDiv();
            oldCardDiv?.parentElement.removeChild(oldCardDiv);
        }

        this.card = card;
        this.createCard(card);

        const cardDiv = this.getCardDiv();
        setupTrainCarCardDiv(cardDiv, card.type);
        cardDiv.classList.add('selectable');
        cardDiv.addEventListener('click', () => {
            if (!cardDiv.classList.contains('disabled')) {
                this.game.onVisibleTrainCarCardClick(this.card.id);
            }
        });

        if (fromDeck) {
            this.addAnimationFrom(cardDiv);
        }
    }

    /**
     * Get card div in the spot.
     */ 
    public getCardDiv(): HTMLDivElement {
        return document.getElementById(`train-car-card-${this.card.id}`) as HTMLDivElement;
    }

    /**
     * Create the card in the spot.
     */ 
    private createCard(card: Card) {
        dojo.place(`<div id="train-car-card-${card.id}" class="train-car-card" data-color="${this.card.type}"></div>`, `visible-train-cards-stock${this.spotNumber}`);
    }
    
    /**
     * Animation when train car cards are picked by another player.
     */
    public moveTrainCarCardToPlayerBoard(playerId: number) {
        this.createCard(this.card);

        animateCardToCounterAndDestroy(this.game, this.getCardDiv(), `train-car-card-counter-${playerId}-wrapper`);
    }

    /**
     * Get visible card color.
     */ 
    public getVisibleColor(): number {
        return this.card.type;
    }
    
    /** 
     * Add an animation to the card (when it is created).
     */ 
    private addAnimationFrom(card: HTMLElement) {
        if (document.visibilityState === 'hidden' || (this.game as any).instantaneousMode) {
            return;
        }

        const from = document.getElementById('train-car-deck-hidden-pile');
        const destinationBR = card.getBoundingClientRect();
        const originBR = from.getBoundingClientRect();

        const deltaX = destinationBR.left - originBR.left;
        const deltaY = destinationBR.top - originBR.top;

        card.style.zIndex = '10';
        const zoom = this.game.getZoom();
        card.style.transform = `translate(${-deltaX/zoom}px, ${-deltaY/zoom}px)`;
        setTimeout(() => {
            card.style.transition = `transform 0.5s linear`;
            card.style.transform = null;
        });

        setTimeout(() => {
            card.style.zIndex = null;
            card.style.transition = null;
        }, 500);
    }
}