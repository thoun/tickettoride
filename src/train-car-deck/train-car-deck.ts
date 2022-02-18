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
    public visibleCardsSpots: VisibleCardSpot[] = [];
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
                    this.dblClickTimeout = null;
                }, DBL_CLICK_TIMEOUT);
            }
        });

        for (let i=1; i<=5; i++) {
            this.visibleCardsSpots[i] = new VisibleCardSpot(game, i);
        }

        this.setNewCardsOnTable(visibleCards, false);

        
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
            this.visibleCardsSpots[i].setSelectableVisibleCards(availableVisibleCards);
        }
    }

    /**
     * Reset visible cards state.
     */ 
     public removeSelectableVisibleCards() {
        for (let i=1; i<=5; i++) {
            this.visibleCardsSpots[i].removeSelectableVisibleCards();
        }
    }

    /**
     * Set new visible cards.
     */ 
    public setNewCardsOnTable(cards: TrainCar[], fromDeck: boolean) {
        cards.forEach(card => this.visibleCardsSpots[card.location_arg].setNewCardOnTable(card, fromDeck));
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
        return from === 0 ? document.getElementById('train-car-deck-hidden-pile') : document.getElementById(`visible-train-cards-stock${from}`); 
    }
    
    /**
     * Animation when train car cards are picked by another player.
     */ 
    public moveTrainCarCardToPlayerBoard(playerId: number, from: number, number: number = 1) {
        if (from > 0) {
            this.visibleCardsSpots[from].moveTrainCarCardToPlayerBoard(playerId);
        } else {
            for (let i=0; i<number; i++) {
                setTimeout(() => {
                    dojo.place(`
                    <div id="animated-train-car-card-0-${i}" class="animated train-car-card from-hidden-pile"></div>
                    `, document.getElementById('train-car-deck-hidden-pile'));

                    animateCardToCounterAndDestroy(this.game,
                        `animated-train-car-card-0-${i}`, 
                        `train-car-card-counter-${playerId}-wrapper`
                    );
                }, 200 * i);
            }
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

                animateCardToCounterAndDestroy(this.game,
                    `animated-destination-card-${i}`, 
                    `destinations-counter-${playerId}-wrapper`
                );
            }, 200 * i);
        }
    }

    /**
     * List visible cards colors.
     */ 
    public getVisibleColors(): number[] {
        return this.visibleCardsSpots.map(stock => stock.getVisibleColor());
    }
}