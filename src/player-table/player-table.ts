class PlayerTable {
    public playerId: number;
    public trainCarStock: Stock;
    public destinationStock: Stock;

    constructor(
        private game: TicketToRideGame, 
        player: TicketToRidePlayer,
        trainCars: TrainCar[],
        destinations: Destination[]) {

        this.playerId = Number(player.id);

        let html = `
        <div id="player-table-${this.playerId}" class="player-table whiteblock">
            <div id="player-table-${this.playerId}-train-cars" class="player-table-train-cars"></div>
            <div id="player-table-${this.playerId}-destinations" class="player-table-destinations"></div>
        </div>`;

        dojo.place(html, 'player-hand');

        // train cars cards        

        this.trainCarStock = new ebg.stock() as Stock;
        this.trainCarStock.setSelectionAppearance('class');
        this.trainCarStock.selectionClass = 'selected';
        this.trainCarStock.create(this.game, $(`player-table-${this.playerId}-train-cars`), CARD_WIDTH, CARD_HEIGHT);
        this.trainCarStock.setSelectionMode(0);
        this.trainCarStock.onItemCreate = (cardDiv, cardTypeId) => setupTrainCarCardDiv(cardDiv, cardTypeId);
        dojo.connect(this.trainCarStock, 'onChangeSelection', this, (_, itemId: string) => {
            if (this.trainCarStock.getSelectedItems().length) {
                //this.game.cardClick(0, Number(itemId));
            }
            this.trainCarStock.unselectAll();
        });
        setupTrainCarCards(this.trainCarStock);

        this.addTrainCars(trainCars);

        // destination cards

        this.destinationStock = new ebg.stock() as Stock;
        this.destinationStock.setSelectionAppearance('class');
        this.destinationStock.selectionClass = 'selected';
        this.destinationStock.create(this.game, $(`player-table-${this.playerId}-destinations`), CARD_WIDTH, CARD_HEIGHT);
        this.destinationStock.setSelectionMode(0);
        this.destinationStock.image_items_per_row = 10;
        this.destinationStock.onItemCreate = (cardDiv: HTMLDivElement, type: number) => setupDestinationCardDiv(cardDiv, type);
        setupDestinationCards(this.destinationStock);

        this.addDestinations(destinations);
    }
        
    public addDestinations(destinations: Destination[], originStock?: Stock) {
        destinations.forEach(destination => {
            const from = document.getElementById(`${originStock ? originStock.container_div.id : 'destination-stock'}_item_${destination.id}`)?.id || 'destination-stock';
            this.destinationStock.addToStockWithId(destination.type_arg, ''+destination.id, from);
            document.getElementById(`${this.destinationStock.container_div.id}_item_${destination.id}`).classList.add('todo'); // TODO
        });
        originStock?.removeAll();
    }
    
    public addTrainCars(trainCars: TrainCar[], stocks?: TrainCarSelection) {
        trainCars.forEach(trainCar => {
            const stock = stocks ? stocks.visibleCardsStocks[trainCar.location_arg] : null;
            const from = document.getElementById(`${stock ? stock.container_div.id : 'train-car-deck'}_item_${trainCar.id}`)?.id || 'train-car-deck';
            this.trainCarStock.addToStockWithId(trainCar.type, ''+trainCar.id, from);
            stock?.removeAll();
        });
    }
}