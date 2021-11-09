class DestinationSelection {
    public destinations: Stock;
    public minimumDestinations: number;

    constructor(
        private game: TicketToRideGame) {

        this.destinations = new ebg.stock() as Stock;
        this.destinations.setSelectionAppearance('class');
        this.destinations.selectionClass = 'destination-selection';
        this.destinations.setSelectionMode(2);
        this.destinations.create(game, $(`destination-stock`), CARD_WIDTH, CARD_HEIGHT);
        this.destinations.onItemCreate = (cardDiv, cardTypeId) => setupDestinationCardDiv(cardDiv, cardTypeId);
        this.destinations.image_items_per_row = 13;
        this.destinations.centerItems = true;
        dojo.connect(this.destinations, 'onChangeSelection', this, () => {
            if (document.getElementById('chooseInitialDestinations_button')) {
                dojo.toggleClass('chooseInitialDestinations_button', 'disabled', this.destinations.getSelectedItems().length < this.minimumDestinations);
            }
            if (document.getElementById('chooseAdditionalDestinations_button')) {
                dojo.toggleClass('chooseAdditionalDestinations_button', 'disabled', this.destinations.getSelectedItems().length < this.minimumDestinations);
            }
        });
        setupDestinationCards(this.destinations);
    }

    public setCards(destinations: Destination[], minimumDestinations: number) {
        dojo.removeClass('destination-deck', 'hidden');

        destinations.forEach(card => this.destinations.addToStockWithId(card.id, ''+card.id));

        this.minimumDestinations = minimumDestinations;
    }

    public hide() {
        this.destinations.removeAll();

        dojo.addClass('destination-deck', 'hidden');
    }

    public getSelectedDestinationsIds() {
        return this.destinations.getSelectedItems().map(item => Number(item.id));
    }
}