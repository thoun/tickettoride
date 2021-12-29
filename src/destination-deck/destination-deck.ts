class DestinationSelection {
    public destinations: Stock;
    public minimumDestinations: number;

    constructor(
        private game: TicketToRideGame) {

        this.destinations = new ebg.stock() as Stock;
        this.destinations.setSelectionAppearance('class');
        this.destinations.selectionClass = 'selected';
        this.destinations.setSelectionMode(2);
        this.destinations.create(game, $(`destination-stock`), CARD_WIDTH, CARD_HEIGHT);
        //this.destinations.onItemCreate = (cardDiv: HTMLDivElement, cardTypeId) => setupDestinationCardDiv(cardDiv, cardTypeId);
        this.destinations.image_items_per_row = 10;
        this.destinations.centerItems = true;
        this.destinations.item_margin = 20;
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

        destinations.forEach(destination => {
            this.destinations.addToStockWithId(destination.type_arg, ''+destination.id);

            const cardDiv = document.getElementById(`destination-stock_item_${destination.id}`);
            cardDiv.addEventListener('mouseenter', () => this.game.setHighligthedDestination(destination));
            cardDiv.addEventListener('mouseleave', () => this.game.setHighligthedDestination(null));
            cardDiv.addEventListener('click', () => this.game.setSelectedDestination(destination, this.destinations.getSelectedItems().some(item => Number(item.id) == destination.id)));
        });

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