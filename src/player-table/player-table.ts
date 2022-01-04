class PlayerTable {
    private playerDestinations: PlayerDestinations;
    private playerTrainCars: PlayerTrainCars;

    constructor(
        game: TicketToRideGame, 
        player: TicketToRidePlayer,
        trainCars: TrainCar[],
        destinations: Destination[],
        completedDestinations: Destination[]) {

        let html = `
        <div id="player-table-${player.id}" class="player-table whiteblock">
            <div id="player-table-${player.id}-destinations" class="player-table-destinations"></div>
            <div id="player-table-${player.id}-train-cars" class="player-table-train-cars"></div>
        </div>`;

        dojo.place(html, 'player-hand');

        this.playerDestinations = new PlayerDestinations(game, player, destinations, completedDestinations);
        this.playerTrainCars = new PlayerTrainCars(game, player, trainCars);
    }

    public addDestinations(destinations: Destination[], originStock?: Stock) {
        this.playerDestinations.addDestinations(destinations, originStock);
    }

    public markDestinationComplete(destination: Destination, animation: boolean) {
        this.playerDestinations.markDestinationComplete(destination, animation);
    }
    
    public addTrainCars(trainCars: TrainCar[], stocks?: TrainCarSelection) {
        this.playerTrainCars.addTrainCars(trainCars, stocks);
    }
    
    public removeCards(removeCards: TrainCar[]) {
        this.playerTrainCars.removeCards(removeCards);
    }

    public setDraggable(draggable: boolean) {
        this.playerTrainCars.setDraggable(draggable);
    }
}