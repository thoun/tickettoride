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
}

interface Destination extends Card {
    from: number;
    to: number;
    points: number;
}

interface RouteSpace {
    x: number;
    y: number;
    angle: number;
    top: boolean;
}

interface Route {
    id: number;
    from: number;
    to: number;
    spaces: RouteSpace[];
    number?: number;
    color: number;
    locomotives: number;
    tunnel: boolean;
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

    // for end score
    completedDestinations?: Destination[];
    uncompletedDestinations?: Destination[];
    longestPathLength: number;
}

interface City {
    id: number;
    name: string;
    x: number;
    y: number;
}

interface BigCity {
    x: number;
    y: number;
    width: number;
}

interface TicketToRideMap {
    code: string;
    cities: { [id: number]: City };
    routes: { [id: number]: Route };
    destinations: { [type: number]: 
        { [id: number]: Destination } 
    };
    bigCities: BigCity[];
    preloadImages: string[];
    illustration: number;
}

/**
 * Your game interfaces
 */

interface TicketToRideGamedatas {
    map: TicketToRideMap;
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
    visibleTrainCards: { [spot: number]: TrainCar | null };

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
    bestScore: number;

    isGlobetrotterBonusActive: boolean;
    isLongestPathBonusActive: boolean;
    showTurnOrder: boolean;
}

interface TicketToRideGame extends Game {
    map: TtrMap;

    getMap(): TicketToRideMap;
    getCityName(to: number): string;

    clickedRoute(route: Route): void;
    setPlayerTablePosition(left: boolean): void;
    getZoom(): number;
    getCurrentPlayer(): TicketToRidePlayer;
    setDestinationsToConnect(destinations: Destination[]): void;
    getPlayerId(): number;
    getPlayerScore(playerId: number): number;
    drawDestinations(): void;
    onVisibleTrainCarCardClick(itemId: number): void;
    onHiddenTrainCarDeckClick(number: number): void;
    askRouteClaimConfirmation(route: Route, color: number): void;
    setActiveDestination(destination: Destination, previousDestination?: Destination): void;
    canClaimRoute(route: Route, cardsColor: number): boolean;
    setHighligthedDestination(destination: Destination | null): void;
    setSelectedDestination(destination: Destination, visible: boolean): void;
    addAnimation(animation: WagonsAnimation): void;
    endAnimation(ended: WagonsAnimation): void;
    isColorBlindMode(): boolean;
    isDoubleRouteForbidden(): boolean;
    selectedColorChanged(selectedColor: number | null): void;
    setTooltip(id: string, html: string): void;
    setTooltipToClass(className: string, html: string): void;
    isGlobetrotterBonusActive(): boolean;
    isLongestPathBonusActive(): boolean;
}

interface EnteringChooseDestinationsArgs {
    _private?: {
        destinations: Destination[];
    };
    destinations?: Destination[];
    minimum: number;
}

interface EnteringChooseActionArgs {
    possibleRoutes: Route[];
    costForRoute: { [routeId: number]: { [color: number]: number[] } };
    maxHiddenCardsPick: number;
    maxDestinationsPick: number;
    canTakeTrainCarCards: boolean;
    canPass: boolean;
}

interface EnteringDrawSecondCardArgs {
    availableVisibleCards: TrainCar[];
    maxHiddenCardsPick: number;
}

interface TunnelAttempt {    
    routeId: number;
    color: number;
    extraCards: number;
    tunnelCards: TrainCar[];
}

interface EnteringConfirmTunnelArgs {
    playerId: number;
    tunnelAttempt: TunnelAttempt;
    canPay: boolean;
}

interface NotifPointsArgs {
    playerId: number;
    points: number;
}

interface NotifDestinationsPickedArgs {
    playerId: number;
    number: number;
    count: number;
    remainingDestinationsInDeck: number;
    _private: {
        [playerId: number]: {
            destinations: Destination[];
        };
    };
}

interface NotifTrainCarsPickedArgs {
    playerId: number;
    count: number;
    number: number;
    remainingTrainCarsInDeck: number;
    cards?: TrainCar[];
    origin: number; // 0 for hidden, else spot number
}

interface NotifNewCardsOnTableArgs {
    spotsCards: { [spot: number]: TrainCar | null };
    remainingTrainCarsInDeck: number;
    locomotiveRefill: boolean;
}

interface NotifClaimedRouteArgs {
    playerId: number;
    route: Route;
    removeCards: TrainCar[];
    remainingTrainCars: number;
}

interface NotifDestinationCompletedArgs {
    playerId: number;
    destination: Destination;
    destinationRoutes: Route[];
}

interface NotifFreeTunnelArgs {
    tunnelCards: TrainCar[];
}

interface NotifBestScoreArgs {
    bestScore: number;
}

interface NotifScorePointArgs {
    playerId: number;
    points: number;
}

interface NotifScoreDestinationArgs {
    playerId: number;
    points: number;
}

interface NotifLongestPathArgs {
    playerId: number;
    length: number;
    routes: Route[];
}


interface NotifBadgeArgs {
    playerId: number;
    length: number;
}
