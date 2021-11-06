class TrainCarSelection {
    private visibleCardsStock: Stock;

    constructor(
        private game: TicketToRideGame,
        visibleCards: TrainCar[],
    ) {

        document.getElementById('drawDeckCards1').addEventListener('click', () => this.game.onHiddenTrainCarDeckClick(1));
        document.getElementById('drawDeckCards2').addEventListener('click', () => this.game.onHiddenTrainCarDeckClick(2));

        this.visibleCardsStock = new ebg.stock() as Stock;
        this.visibleCardsStock.setSelectionAppearance('class');
        this.visibleCardsStock.selectionClass = 'no-class-selection';
        this.visibleCardsStock.setSelectionMode(1);
        this.visibleCardsStock.create(game, $(`visible-train-cards-stock`), CARD_WIDTH, CARD_HEIGHT);
        //this.cards.onItemCreate = (card_div, card_type_id) => this.game.cards.setupNewCard(card_div, card_type_id);
        this.visibleCardsStock.image_items_per_row = 13;
        this.visibleCardsStock.centerItems = true;
        dojo.connect(this.visibleCardsStock, 'onChangeSelection', this, (_, itemId: string) => this.game.onVisibleTrainCarCardClick(Number(itemId)));
        setupTrainCarCards(this.visibleCardsStock);

        visibleCards.forEach(card => this.visibleCardsStock.addToStockWithId(card.id, ''+card.id));
    }
}