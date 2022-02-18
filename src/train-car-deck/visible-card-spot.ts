/** 
 * Selection of new train cars.
 */ 
class VisibleCardSpot {
    public visibleCardsStock: Stock;

    /**
     * Init stocks and gauges.
     */ 
    constructor(
        private game: TicketToRideGame,
        private spotNumber: number,
    ) {

        this.visibleCardsStock = new ebg.stock() as Stock;
        this.visibleCardsStock.setSelectionAppearance('class');
        this.visibleCardsStock.selectionClass = 'no-class-selection';
        this.visibleCardsStock.setSelectionMode(1);
        this.visibleCardsStock.create(game, $(`visible-train-cards-stock${spotNumber}`), CARD_WIDTH, CARD_HEIGHT);
        this.visibleCardsStock.onItemCreate = (cardDiv, cardTypeId) => setupTrainCarCardDiv(cardDiv, cardTypeId);
        this.visibleCardsStock.centerItems = true;
        dojo.connect(this.visibleCardsStock, 'onChangeSelection', this, (_, itemId: string) => this.game.onVisibleTrainCarCardClick(Number(itemId), this.visibleCardsStock));
        setupTrainCarCards(this.visibleCardsStock);
    }
    
    /**
     * Set selectable visible cards (locomotive can't be selected if 1 visible card has been picked).
     */ 
    public setSelectableVisibleCards(availableVisibleCards: TrainCar[]) {
        const stock = this.visibleCardsStock;
        stock.items.forEach(item => {
            const itemId = Number(item.id);
            if (!availableVisibleCards.some(card => card.id == itemId)) {
                document.getElementById(`${stock.container_div.id}_item_${itemId}`)?.classList.add('disabled');
            }
        });
    }

    /**
     * Reset visible cards state.
     */ 
     public removeSelectableVisibleCards() {
        const stock = this.visibleCardsStock;
        stock.items.forEach(item => document.getElementById(`${stock.container_div.id}_item_${item.id}`)?.classList.remove('disabled'));
    }

    /**
     * Set new visible cards.
     */ 
    public setNewCardsOnTable(card: TrainCar) {
            this.visibleCardsStock.removeAll();
            this.visibleCardsStock.addToStockWithId(card.type, ''+card.id, 'train-car-deck-hidden-pile');
    }
    
    /**
     * Get HTML Element represented by "from" (0 means invisible, 1 to 5 are visible cards).
     */ 
    public getStockElement(): HTMLElement {
        return this.visibleCardsStock.container_div; 
    }
    
    /**
     * Animation when train car cards are picked by another player.
     */
    public moveTrainCarCardToPlayerBoard(playerId: number, color: number = 0) {
        dojo.place(`
        <div id="animated-train-car-card-${this.spotNumber}" class="animated-train-car-card" data-color="${color}"></div>
        `, this.getStockElement());

        animateCardToCounterAndDestroy(this.game,
            `animated-train-car-card-${this.spotNumber}`, 
            `train-car-card-counter-${playerId}-wrapper`
        );
    }

    public getVisibleColor(): number {
        return this.visibleCardsStock.items[0].type;
    }
}