class TrainCarSelection {
    private visibleCardsStock: Stock;

    constructor(
        private game: TicketToRideGame,
        visibleCards: TrainCar[],
    ) {

        document.getElementById('train-car-deck-hidden-pile1').addEventListener('click', () => this.game.onHiddenTrainCarDeckClick(1));
        document.getElementById('train-car-deck-hidden-pile2').addEventListener('click', () => this.game.onHiddenTrainCarDeckClick(2));

        this.visibleCardsStock = new ebg.stock() as Stock;
        this.visibleCardsStock.setSelectionAppearance('class');
        this.visibleCardsStock.selectionClass = 'no-class-selection';
        this.visibleCardsStock.setSelectionMode(1);
        this.visibleCardsStock.create(game, $(`visible-train-cards-stock`), CARD_WIDTH, CARD_HEIGHT);
        this.visibleCardsStock.onItemCreate = (cardDiv, cardTypeId) => setupTrainCarCardDiv(cardDiv, cardTypeId);
        this.visibleCardsStock.image_items_per_row = 13;
        this.visibleCardsStock.centerItems = true;
        dojo.connect(this.visibleCardsStock, 'onChangeSelection', this, (_, itemId: string) => this.game.onVisibleTrainCarCardClick(Number(itemId)));
        setupTrainCarCards(this.visibleCardsStock);

        visibleCards.forEach(card => this.visibleCardsStock.addToStockWithId(card.type, ''+card.id));
    }

    public setSelectableTopDeck(selectable: boolean, number: number = 0) {
        dojo.toggleClass('train-car-deck-hidden-pile', 'selectable', selectable);
        dojo.toggleClass('train-car-deck-hidden-pile1', 'hidden', number < 1);
        dojo.toggleClass('train-car-deck-hidden-pile2', 'hidden', number < 2);
    }
}