const DBL_CLICK_TIMEOUT = 300;

/** 
 * Level of cards in deck indicator.
 */ 
class Gauge {
    private levelDiv: HTMLDivElement;

    constructor(containerId: string,
        className: string,
        private max: number,
    ) {
        dojo.place(`
        <div id="gauge-${className}" class="gauge ${className}">
            <div class="inner" id="gauge-${className}-level"></div>
        </div>`, containerId);

        this.levelDiv = document.getElementById(`gauge-${className}-level`) as HTMLDivElement;
    }

    public setCount(count: number) {
        this.levelDiv.style.height = `${100 * count / this.max}%`;
    }
}

/** 
 * Selection of new train cars.
 */ 
class TrainCarSelection {
    public visibleCardsStocks: Stock[] = [];
    private trainCarGauge: Gauge;
    private destinationGauge: Gauge;
    private dblClickTimeout = null;

    /**
     * Init stocks and gauges.
     */ 
    constructor(
        private game: TicketToRideGame,
        visibleCards: TrainCar[],
        trainCarDeckCount: number,
        destinationDeckCount: number,
        trainCarDeckMaxCount: number,
        destinationDeckMaxCount: number,
    ) {

        document.getElementById('train-car-deck-hidden-pile1').addEventListener('click', () => this.game.onHiddenTrainCarDeckClick(1));
        document.getElementById('train-car-deck-hidden-pile2').addEventListener('click', () => this.game.onHiddenTrainCarDeckClick(2));

        document.getElementById('train-car-deck-hidden-pile').addEventListener('click', () => {
            if (this.dblClickTimeout) {
                clearTimeout(this.dblClickTimeout);
                this.dblClickTimeout = null;
                this.game.onHiddenTrainCarDeckClick(2);
            } else if (!dojo.hasClass('train-car-deck-hidden-pile', 'buttonselection')) {
                this.dblClickTimeout = setTimeout(() => {
                    this.game.onHiddenTrainCarDeckClick(1);
                }, DBL_CLICK_TIMEOUT);
            }
        });

        for (let i=1; i<=5; i++) {
            this.visibleCardsStocks[i] = new ebg.stock() as Stock;
            this.visibleCardsStocks[i].setSelectionAppearance('class');
            this.visibleCardsStocks[i].selectionClass = 'no-class-selection';
            this.visibleCardsStocks[i].setSelectionMode(1);
            this.visibleCardsStocks[i].create(game, $(`visible-train-cards-stock${i}`), CARD_WIDTH, CARD_HEIGHT);
            this.visibleCardsStocks[i].onItemCreate = (cardDiv, cardTypeId) => setupTrainCarCardDiv(cardDiv, cardTypeId);
            this.visibleCardsStocks[i].centerItems = true;
            dojo.connect(this.visibleCardsStocks[i], 'onChangeSelection', this, (_, itemId: string) => this.game.onVisibleTrainCarCardClick(Number(itemId), this.visibleCardsStocks[i]));
            setupTrainCarCards(this.visibleCardsStocks[i]);
        }

        this.setNewCardsOnTable(visibleCards);

        
        this.trainCarGauge = new Gauge('train-car-deck-hidden-pile', 'train-car', trainCarDeckMaxCount);
        this.destinationGauge = new Gauge('destination-deck-hidden-pile', 'destination', destinationDeckMaxCount);
        this.setTrainCarCount(trainCarDeckCount);
        this.setDestinationCount(destinationDeckCount);
    }

    /**
     * Set selection of hidden cards deck.
     */ 
    public setSelectableTopDeck(selectable: boolean, number: number = 0) {
        dojo.toggleClass('train-car-deck-hidden-pile', 'selectable', selectable);
        dojo.toggleClass('train-car-deck-hidden-pile1', 'hidden', number < 1);
        dojo.toggleClass('train-car-deck-hidden-pile2', 'hidden', number < 2);
    }
    
    /**
     * Set selectable visible cards (locomotive can't be selected if 1 visible card has been picked).
     */ 
    public setSelectableVisibleCards(availableVisibleCards: TrainCar[]) {
        for (let i=1; i<=5; i++) {
            const stock = this.visibleCardsStocks[i];
            stock.items.forEach(item => {
                const itemId = Number(item.id);
                if (!availableVisibleCards.some(card => card.id == itemId)) {
                    document.getElementById(`${stock.container_div.id}_item_${itemId}`)?.classList.add('disabled');
                }
            });
        }
    }

    /**
     * Reset visible cards state.
     */ 
     public removeSelectableVisibleCards() {
        for (let i=1; i<=5; i++) {
            const stock = this.visibleCardsStocks[i];
            stock.items.forEach(item => document.getElementById(`${stock.container_div.id}_item_${item.id}`)?.classList.remove('disabled'));
        }
    }

    /**
     * Set new visible cards.
     */ 
    public setNewCardsOnTable(cards: TrainCar[]) {
        cards.forEach(card => {
            const spot = card.location_arg;
            this.visibleCardsStocks[spot].removeAll();

            this.visibleCardsStocks[spot].addToStockWithId(card.type, ''+card.id, 'train-car-deck-hidden-pile');
        });
    }

    /**
     * Update train car gauge.
     */ 
    public setTrainCarCount(count: number) {
        this.trainCarGauge.setCount(count);
        document.getElementById(`train-car-deck-level`).dataset.level = `${Math.min(10, Math.ceil(count / 10))}`;
    }

    /**
     * Update destination gauge.
     */ 
    public setDestinationCount(count: number) {
        this.destinationGauge.setCount(count);
        document.getElementById(`destination-deck-level`).dataset.level = `${Math.min(10, Math.ceil(count / 10))}`;
    }

    /**
     * Make hidden train car cads selection buttons visible (user preference).
     */ 
    public setCardSelectionButtons(visible: boolean) {
        dojo.toggleClass('train-car-deck-hidden-pile', 'buttonselection', visible);
    }
    
    /**
     * Get HTML Element represented by "from" (0 means invisible, 1 to 5 are visible cards).
     */ 
    public getStockElement(from: number): HTMLElement {
        return from === 0 ? document.getElementById('train-car-deck-hidden-pile') : this.visibleCardsStocks[from].container_div; 
    }

    /**
     * Animation to move a card to a player's counter (the destroy animated card).
     */ 
    private animateCardToCounterAndDestroy(cardId: string, destinationId: string) {
        const card = document.getElementById(cardId);
        const cardBR = card.getBoundingClientRect();

        const toBR = document.getElementById(destinationId).getBoundingClientRect();
            
        const zoom = this.game.getZoom();
        const x = (toBR.x - cardBR.x) / zoom;
        const y = (toBR.y - cardBR.y) / zoom;

        card.style.transform = `translate(${x}px, ${y}px) scale(${0.15 / zoom})`;
        setTimeout(() => card.parentElement?.removeChild(card), 500);
    }
    
    /**
     * Animation when train car cards are picked by another player.
     */ 
    public moveTrainCarCardToPlayerBoard(playerId: number, from: number, color: number = 0, number: number = 1) {
        for (let i=0; i<number; i++) {
            setTimeout(() => {
                dojo.place(`
                <div id="animated-train-car-card-${from}-${i}" class="animated-train-car-card ${from === 0 ? 'from-hidden-pile' : ''}" data-color="${color}"></div>
                `, this.getStockElement(from));

                this.animateCardToCounterAndDestroy(
                    `animated-train-car-card-${from}-${i}`, 
                    `train-car-card-counter-${playerId}-wrapper`
                );
            }, 200 * i);
        }
    }
    
    /**
     * Animation when destination cards are picked by another player.
     */ 
    public moveDestinationCardToPlayerBoard(playerId: number, number: number) {
        for (let i=0; i<number; i++) {
            setTimeout(() => {
                dojo.place(`
                <div id="animated-destination-card-${i}" class="animated-destination-card"></div>
                `, 'destination-deck-hidden-pile');

                this.animateCardToCounterAndDestroy(
                    `animated-destination-card-${i}`, 
                    `destinations-counter-${playerId}-wrapper`
                );
            }, 200 * i);
        }
    }
}