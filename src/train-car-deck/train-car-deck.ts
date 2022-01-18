const DBL_CLICK_TIMEOUT = 300;

class Gauge {
    private levelDiv: HTMLDivElement;

    constructor(conainerId: string,
        className: string,
        private max: number,
    ) {
        dojo.place(`
        <div id="gauge-${className}" class="gauge ${className}">
            <div class="inner" id="gauge-${className}-level"></div>
        </div>`, conainerId);

        this.levelDiv = document.getElementById(`gauge-${className}-level`) as HTMLDivElement;
    }

    public setCount(count: number) {
        this.levelDiv.style.height = `${100 * count / this.max}%`;
    }
}

class TrainCarSelection {
    public visibleCardsStocks: Stock[] = [];
    private trainCarGauge: Gauge;
    private destinationGauge: Gauge;
    private dblClickTimeout = null;

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
            //this.visibleCardsStock.image_items_per_row = 9;
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

    public setSelectableTopDeck(selectable: boolean, number: number = 0) {
        dojo.toggleClass('train-car-deck-hidden-pile', 'selectable', selectable);
        dojo.toggleClass('train-car-deck-hidden-pile1', 'hidden', number < 1);
        dojo.toggleClass('train-car-deck-hidden-pile2', 'hidden', number < 2);
    }
    
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

    public removeSelectableVisibleCards() {
        for (let i=1; i<=5; i++) {
            const stock = this.visibleCardsStocks[i];
            stock.items.forEach(item => document.getElementById(`${stock.container_div.id}_item_${item.id}`)?.classList.remove('disabled'));
        }
    }

    public setNewCardsOnTable(cards: TrainCar[]) {
        cards.forEach(card => {
            const spot = card.location_arg;
            this.visibleCardsStocks[spot].removeAll();

            this.visibleCardsStocks[spot].addToStockWithId(card.type, ''+card.id, 'train-car-deck-hidden-pile');
        });
    }

    public setTrainCarCount(count: number) {
        this.trainCarGauge.setCount(count);
        document.getElementById(`train-car-deck-level`).dataset.level = `${Math.min(10, Math.floor(count / 10))}`;
    }

    public setDestinationCount(count: number) {
        this.destinationGauge.setCount(count);
        document.getElementById(`destination-deck-level`).dataset.level = `${Math.min(10, Math.floor(count / 10))}`;
    }

    public setCardSelectionButtons(visible: boolean) {
        dojo.toggleClass('train-car-deck-hidden-pile', 'buttonselection', visible);
    }
    
    public getStockElement(from: number): HTMLElement {
        return from === 0 ? document.getElementById('train-car-deck-hidden-pile') : this.visibleCardsStocks[from].container_div; 
    }
    
    public moveCardToPlayerBoard(playerId: number, from: number, color: number = 0) {
        dojo.place(`
        <div id="animated-train-car-card-${from}" class="animated-train-car-card ${from === 0 ? 'from-hidden-pile' : ''}" data-color="${color}"></div>
        `, this.getStockElement(from));        

        const card = document.getElementById(`animated-train-car-card-${from}`);
        const cardBR = card.getBoundingClientRect();

        const toBR = document.getElementById(`train-car-card-counter-${playerId}-wrapper`).getBoundingClientRect();
             
        const zoom = this.game.getZoom();
        const x = (toBR.x - cardBR.x) / zoom;
        const y = (toBR.y - cardBR.y) / zoom;

        card.style.transform = `translate(${x}px, ${y}px) scale(${0.15 / zoom})`;
        setTimeout(() => card.parentElement?.removeChild(card), 500);
    }
}