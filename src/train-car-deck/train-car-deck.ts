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
    public trainCarGauge: Gauge;
    public destinationGauge: Gauge;

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
        this.trainCarGauge.setCount(trainCarDeckCount);
        this.destinationGauge.setCount(destinationDeckCount);
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
            this.visibleCardsStocks[spot].addToStockWithId(card.type, ''+card.id);
        });
    }
}