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

        // adventurer        

        this.trainCarStock = new ebg.stock() as Stock;
        this.trainCarStock.setSelectionAppearance('class');
        this.trainCarStock.selectionClass = 'selected';
        this.trainCarStock.create(this.game, $(`player-table-${this.playerId}-train-cars`), CARD_WIDTH, CARD_HEIGHT);
        this.trainCarStock.setSelectionMode(0);
        this.trainCarStock.setSelectionMode(0);
        //this.trainCarStock.onItemCreate = (cardDiv: HTMLDivElement, type: number) => setupAdventurerCard(game, cardDiv, type);
        dojo.connect(this.trainCarStock, 'onChangeSelection', this, (_, itemId: string) => {
            if (this.trainCarStock.getSelectedItems().length) {
                //this.game.cardClick(0, Number(itemId));
            }
            this.trainCarStock.unselectAll();
        });
        setupTrainCarCards(this.trainCarStock);

        trainCars.forEach(trainCar => this.trainCarStock.addToStockWithId(trainCar.type, ''+trainCar.id));

        // companions

        this.destinationStock = new ebg.stock() as Stock;
        this.destinationStock.setSelectionAppearance('class');
        this.destinationStock.selectionClass = 'selected';
        this.destinationStock.create(this.game, $(`player-table-${this.playerId}-destinations`), CARD_WIDTH, CARD_HEIGHT);
        this.destinationStock.setSelectionMode(0);
        this.destinationStock.setSelectionMode(0);
        //this.destinationStock.onItemCreate = (cardDiv: HTMLDivElement, type: number) => setupCompanionCard(game, cardDiv, type);
        dojo.connect(this.destinationStock, 'onChangeSelection', this, (_, itemId: string) => {
            if (this.destinationStock.getSelectedItems().length) {
                //this.game.cardClick(1, Number(itemId));
            }
            this.destinationStock.unselectAll();
        });
        setupDestinationCards(this.destinationStock);

        destinations.forEach(destination => this.destinationStock.addToStockWithId(destination.type_arg, ''+destination.id));

    }
}