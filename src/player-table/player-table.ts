import { TicketToRideGame, TicketToRidePlayer, TrainCar, Destination, Route, City } from "../tickettoride.d";
import { PlayerDestinations } from "./player-destinations";
import { PlayerTrainCars } from "./player-train-cars";

/** 
 * Player table : train car et destination cards.
 */ 
export class PlayerTable {
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

        document.getElementById('resized').insertAdjacentHTML('beforeend', html);

        this.playerDestinations = new PlayerDestinations(game, player, destinations, completedDestinations);
        this.playerTrainCars = new PlayerTrainCars(game, player, trainCars);
    }
    
    /** 
     * Place player table to the left or the bottom of the map.
     */ 
    public setPosition(left: boolean) {
        const playerHandDiv = document.getElementById(`player-table`);
        if (left) {
            document.getElementById('main-line').prepend(playerHandDiv);
        } else {
            document.getElementById('resized').appendChild(playerHandDiv);
        }
        playerHandDiv.classList.toggle('left', left);

        this.playerTrainCars.setPosition(left);
    }

    public addDestinations(destinations: Destination[], originStock?: Stock) {
        this.playerDestinations.addDestinations(destinations, originStock);
    }

    public markDestinationComplete(destination: Destination, destinationRoutes?: Route[]) {
        this.playerDestinations.markDestinationComplete(destination, destinationRoutes);
    }
    
    public addTrainCars(trainCars: TrainCar[], from?: HTMLElement) {
        this.playerTrainCars.addTrainCars(trainCars, from);
    }
    
    public removeCards(removeCards: TrainCar[]) {
        this.playerTrainCars.removeCards(removeCards);
    }

    public setDraggable(draggable: boolean) {
        this.playerTrainCars.setDraggable(draggable);
    }

    public setSelectable(selectable: boolean) {
        this.playerTrainCars.setSelectable(selectable);
    }
    
    public setSelectableTrainCarColors(route: Route | null, possibleColors: number[] | null = null) {
        this.playerTrainCars.setSelectableTrainCarColors(route, possibleColors);
    }
    
    public setSelectableTrainCarColorsForStation(city: City | null, possibleColors: number[] | null = null) {
        this.playerTrainCars.setSelectableTrainCarColorsForStation(city, possibleColors);
    }

    public getSelectedColor(): number | null {
        return this.playerTrainCars.getSelectedColor();
    }

    public updateColorBlindRotation(): void {
        return this.playerTrainCars.updateColorBlindRotation();
    }
}