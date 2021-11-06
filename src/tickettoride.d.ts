declare const define;
declare const ebg;
declare const $;
declare const dojo: Dojo;
declare const _;
declare const g_gamethemeurl;

interface Card {
    id: number;
    type: number;
    type_arg: number;
    location: string;
    location_arg: number;
}

interface TrainCar extends Card {
    // TODO
}

interface Destination extends Card {
    // TODO
}

interface TicketToRidePlayer extends Player {
    trainCarsCount: number;
    destinationsCount: number;
    remainingTrainCarsCount: number;
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
    visibleTrainCards: TrainCar[];

    // private informations for current player only
    handTrainCars: TrainCar[];
    handDestinations: Destination[];
    completedDestinations: Destination[];

    // counters
    trainCarDeckCount: number;
    destinationDeckCount: number;
}

interface TicketToRideGame extends Game {
    getPlayerId(): number;
    onVisibleTrainCarCardClick(itemId: number): void;
    onHiddenTrainCarDeckClick(number: number): void;
    claimRoute(id: number): void;
}

interface EnteringChooseDestinationsArgs {
    _private: {
        destinations: Card[];
    };
    minimum: number;
}

interface EnteringChooseActionArgs {
    possibleRoutes: any[]; // TODO
    maxHiddenCardsPick: number;
    maxDestinationsPick: number;
}

// TODO
interface NotifFirstPlayerTokenArgs {
    playerId: number;
}