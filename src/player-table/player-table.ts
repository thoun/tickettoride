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
            <div id="player-table" class="player-table">
                <div id="player-table-${player.id}-destinations" class="player-table-destinations"></div>
                <div id="player-table-${player.id}-train-cars" class="player-table-train-cars"></div>
            </div>
        `;

        dojo.place(html, 'resized');

        this.playerDestinations = new PlayerDestinations(game, player, destinations, completedDestinations);
        this.playerTrainCars = new PlayerTrainCars(game, player, trainCars);
    }
    
    public setPosition(left: boolean) {
        const playerHandDiv = document.getElementById(`player-table`);
        if (left) {
            document.getElementById('main-line').prepend(playerHandDiv);
        } else {
            document.getElementById('resized').appendChild(playerHandDiv);
        }
        playerHandDiv.classList.toggle('left', left);

        this.playerDestinations.setPosition(left);
        this.playerTrainCars.setPosition(left);
    }

    public addDestinations(destinations: Destination[], originStock?: Stock) {
        this.playerDestinations.addDestinations(destinations, originStock);
    }

    public markDestinationComplete(destination: Destination, destinationRoutes?: Route[]) {
        this.playerDestinations.markDestinationComplete(destination, destinationRoutes);
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
    
    public getPossibleColors(route: Route): number[] {
        return this.playerTrainCars.getPossibleColors(route);
    }
}