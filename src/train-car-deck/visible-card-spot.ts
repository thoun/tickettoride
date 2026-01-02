import { animateCardToCounterAndDestroy } from "../slide-utils";
import { setupTrainCarCardDiv } from "../stock-utils";
import { TrainCar, TicketToRideGame, Card } from "../tickettoride.d";

/** 
 * Selection of new train cars.
 */ 
export class VisibleCardSpot {
    private card: TrainCar | null;
    private spotDiv: HTMLDivElement;

    /**
     * Init stocks and gauges.
     */ 
    constructor(
        private game: TicketToRideGame,
        private spotNumber: number,
    ) {
        this.spotDiv = document.getElementById(`visible-train-cards-stock${this.spotNumber}`) as HTMLDivElement;
    }
    
    /**
     * Set selectable visible cards (locomotive can't be selected if 1 visible card has been picked).
     */ 
    public setSelectableVisibleCards(availableVisibleCards: TrainCar[]) {
        this.getCardDiv()?.classList.toggle('disabled', !availableVisibleCards.some(card => card.id == this.card?.id));
    }

    /**
     * Reset visible cards state.
     */ 
    public removeSelectableVisibleCards() {
        this.getCardDiv()?.classList.remove('disabled');
    }

    /**
     * Set new visible cards.
     */ 
    public setNewCardOnTable(card: TrainCar | null, fromDeck: boolean) {
        if (this.card) {
            const oldCardDiv = this.getCardDiv();
            if (oldCardDiv?.closest(`#visible-train-cards-stock${this.spotNumber}`)) {
                oldCardDiv.parentElement.removeChild(oldCardDiv);
                this.card = null;
            }
        }

        // make sure there is no card remaining on the spot
        while (this.spotDiv.childElementCount > 0) {
            this.spotDiv.removeChild(this.spotDiv.firstElementChild);
        }

        this.card = card;
        if (card) { // the card 
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
    }

    /**
     * Get card div in the spot.
     */ 
    public getCardDiv(): HTMLDivElement | null {
        if (!this.card) {
            return null;
        }

        return document.getElementById(`train-car-card-${this.card.id}`) as HTMLDivElement;
    }

    /**
     * Create the card in the spot.
     */ 
    private createCard(card: Card) {
        this.spotDiv.insertAdjacentHTML('beforeend', `
            <div id="train-car-card-${card.id}" class="train-car-card" data-color="${this.card.type}"></div>
        `);
    }
    
    /**
     * Animation when train car cards are picked by another player.
     */
    public moveTrainCarCardToPlayerBoard(playerId: number) {
        this.createCard(this.card);

        const cardDiv = this.getCardDiv();
        if (cardDiv) {
            animateCardToCounterAndDestroy(this.game, cardDiv, `train-car-card-counter-${playerId}-wrapper`);
        }
    }

    /**
     * Get visible card color.
     */ 
    public getVisibleColor(): number {
        return this.card?.type;
    }
    
    /** 
     * Add an animation to the card (when it is created).
     */ 
    private addAnimationFrom(card: HTMLElement) {
        if (!this.game.bga.gameui.bgaAnimationsActive()) {
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