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
    from: number;
    to: number;
}

interface Route {
    id: number;
    from: number;
    to: number;
    number: number;
    color: number;
}

interface ClaimedRoute {
    routeId: number;
    playerId: number;
}

interface TicketToRidePlayer extends Player {
    playerNo: number;
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
    claimedRoutes: ClaimedRoute[];
    visibleTrainCards: TrainCar[];

    // private informations for current player only
    handTrainCars: TrainCar[];
    handDestinations: Destination[];
    completedDestinations: Destination[];

    // counters
    trainCarDeckCount: number;
    destinationDeckCount: number;
    trainCarDeckMaxCount: number;
    destinationDeckMaxCount: number;
    lastTurn: boolean;
}

interface TicketToRideGame extends Game {
    getPlayerId(): number;
    onVisibleTrainCarCardClick(itemId: number, stock: Stock): void;
    onHiddenTrainCarDeckClick(number: number): void;
    claimRoute(id: number): void;
    isColorBlindMode(): boolean;
    setActiveDestination(destination: Destination, previousDestination?: Destination): void;
}

interface EnteringChooseDestinationsArgs {
    _private: {
        destinations: Destination[];
    };
    minimum: number;
}

interface EnteringChooseActionArgs {
    possibleRoutes: Route[];
    maxHiddenCardsPick: number;
    maxDestinationsPick: number;
}

interface EnteringDrawSecondCardArgs {
    availableVisibleCards: TrainCar[];
    maxHiddenCardsPick: number;
}

interface NotifPointsArgs {
    playerId: number;
    points: number;
}

interface NotifDestinationsPickedArgs {
    playerId: number;
    number: number;
    remainingDestinationsInDeck: number;
    _private: {
        [playerId: number]: {
            destinations: Destination[];
        };
    };
}

interface NotifTrainCarsPickedArgs {
    playerId: number;
    number: number;
    remainingTrainCarsInDeck: number;
    _private: {
        [playerId: number]: {
            cards: TrainCar[];
        };        
    };
}

interface NotifNewCardsOnTableArgs {
    cards: TrainCar[];
}

interface NotifNewCardsOnTableArgs {
    cards: TrainCar[];
}

interface NotifClaimedRouteArgs {
    playerId: number;
    route: Route;
}

interface NotifDestinationCompletedArgs {
    playerId: number;
    destination: Destination;
}