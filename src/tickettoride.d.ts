interface Card {
    id: number;
    // TODO
}

interface TrainCar extends Card {

}

interface Destination extends Card {

}

interface TicketToRidePlayer extends Player {
    trainCarsNumber: number;
    destinationsNumber: number;
    remainingTrainCars: number;
}

/**
 * Your game interfaces
 */

interface TicketToRideGamedatas {
    current_player_id: string;
    decision: {decision_type: string};
    game_result_neutralized: string;
    gamestate: Gamestate;
    gamestates: { [gamestateId: number]: Gamestate };
    neutralized_player_id: string;
    notifications: {last_packet_id: string, move_nbr: string}
    playerorder: (string | number)[];
    players: { [playerId: number]: TicketToRidePlayer };
    tablespeed: string;

    // Add here variables you set up in getAllDatas
    

    handTrainCars: TrainCar[];
    handDestinations: Destination[];
}

interface TicketToRideGame extends Game {
    getPlayerId(): number;
}

interface EnteringChooseDestinationsArgs {
    destinations: Card[];
    minimum: number;
}

// TODO
interface NotifFirstPlayerTokenArgs {
    playerId: number;
}