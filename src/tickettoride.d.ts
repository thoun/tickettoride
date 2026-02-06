import { TtrMap } from "./map/map";
import { PlayerTable } from "./player-table/player-table";
import { ChooseActionState } from "./states/ChooseAction";
import { TrainCarSelection } from "./train-car-deck/train-car-deck";
import { WagonsAnimation } from "./wagons-animation";

export interface Card {
    id: number;
    type: number;
    type_arg: number;
    location: string;
    location_arg: number;
}

export interface TrainCar extends Card {
}

export interface Destination extends Card {
    from: number;
    to: number;
    points: number;
}

export interface RouteSpace {
    x: number;
    y: number;
    angle: number;
    top: boolean;
}

export interface Route {
    id: number;
    from: number;
    to: number;
    spaces: RouteSpace[];
    number?: number;
    color: number;
    locomotives: number;
    tunnel: boolean;
    canPayWithAnySetOfCards: number | null;
}

export interface ClaimingRoute {
    route: Route;
    color: number;
    distribution: number[] | null;
}

export interface ClaimedRoute {
    routeId: number;
    playerId: number;
}

export interface BuiltStation {
    cityId: number;
    playerId: number;
}

export interface TicketToRidePlayer extends Player {
    playerNo: number;
    trainCarsCount: number;
    destinationsCount: number;
    remainingTrainCarsCount: number;
    remainingStations?: number;

    // for end score
    completedDestinations?: Destination[];
    uncompletedDestinations?: Destination[];
    longestPathLength: number;
}

export interface City {
    id: number;
    name: string;
    x: number;
    y: number;
}

export interface BigCity {
    x: number;
    y: number;
    width: number;
}

export interface TicketToRideMap {
    code: string;
    cities: { [id: number]: City };
    routes: { [id: number]: Route };
    destinations: { [type: number]: 
        { [id: number]: Destination } 
    };
    bigCities: BigCity[];
    preloadImages: string[];
    illustration: number;
    locomotiveUsageRestriction: number;
    minimumPlayerForDoubleRoutes: number;
    multilingualPdfRulesUrl?: string;
    rulesDifferences?: string[];
    vertical: boolean;
    stations: number | null;
}

/**
 * Your game interfaces
 */

export interface TicketToRideGamedatas {
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
    builtStations: BuiltStation[];
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

export interface TicketToRideGame{
    trainCarSelection: TrainCarSelection;
    playerTable: PlayerTable | null;
    map: TtrMap;
    stationCounters: Counter[];
    destinationCardCounters: Counter[];

    bga: Bga;
    gamedatas: TicketToRideGamedatas;

    chooseActionState: ChooseActionState;

    getMap(): TicketToRideMap;
    getCityName(to: number): string;

    setPlayerTablePosition(left: boolean): void;
    getZoom(): number;
    getCurrentPlayer(): TicketToRidePlayer;
    setDestinationsToConnect(destinations: Destination[]): void;
    getPlayerId(): number;
    getPlayerScore(playerId: number): number;
    drawDestinations(): void;
    onVisibleTrainCarCardClick(itemId: number): void;
    onHiddenTrainCarDeckClick(number: number): void;
    setActiveDestination(destination: Destination, previousDestination?: Destination): void;
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

export interface EnteringChooseDestinationsArgs {
    _private?: {
        destinations: Destination[];
    };
    destinations?: Destination[];
    minimum: number;
}

export interface TunnelAttempt {    
    routeId: number;
    color: number;
    extraCards: number;
    tunnelCards: TrainCar[];
    distribution?: number[];
}

export interface NotifPointsArgs {
    playerId: number;
    points: number;
}

export interface NotifDestinationsPickedArgs {
    playerId: number;
    number: number;
    count: number;
    remainingDestinationsInDeck: number;
    _private?: {
        destinations: Destination[];
    };
}

export interface NotifTrainCarsPickedArgs {
    playerId: number;
    count: number;
    number: number;
    remainingTrainCarsInDeck: number;
    cards?: TrainCar[];
    origin: number; // 0 for hidden, else spot number
}

export interface NotifNewCardsOnTableArgs {
    spotsCards: { [spot: number]: TrainCar | null };
    remainingTrainCarsInDeck: number;
    locomotiveRefill: boolean;
}

export interface NotifClaimedRouteArgs {
    playerId: number;
    route: Route;
    removeCards: TrainCar[];
    remainingTrainCars: number;
}

export interface NotifBuiltStationArgs {
    playerId: number;
    city: City;
    removeCards: TrainCar[];
}

export interface NotifDestinationCompletedArgs {
    playerId: number;
    destination: Destination;
    destinationRoutes: Route[];
}

export interface NotifFreeTunnelArgs {
    tunnelCards: TrainCar[];
}

export interface NotifBestScoreArgs {
    bestScore: number;
}

export interface NotifScorePointArgs {
    playerId: number;
    points: number;
}

export interface NotifScoreDestinationArgs {
    playerId: number;
    points: number;
}

export interface NotifLongestPathArgs {
    playerId: number;
    length: number;
    routes: Route[];
}

export interface NotifMandalaRoutesArgs {
    playerId: number;
    destination: Destination;
    routes: Route[];
}


export interface NotifBadgeArgs {
    playerId: number;
    length: number;
}

export interface NotifRemainingStationsArgs {
    playerId: number;
    remainingStations: number;
}
