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
    to: number | number[];
    points: number | number[];
}

interface DestinationReference {
    id: number;
    type: number;
    type_arg: number;
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
    canPayWithAnySetOfCards?: number | null;
    ferryWaves?: number;
    mountain?: number;
    bulletTrainSpaceIndex?: number;
}

interface ClaimingRoute {
    route: Route;
    color: number;
    distribution: number[] | null;
    ferryCards?: number;
}

interface ClaimedRoute {
    routeId: number;
    playerId: number;
    shiftIndex?: number;
}

interface BuiltStation {
    cityId: number;
    playerId: number;
}

interface MapSpecificData {
    remainingBulletTrains?: number;
    remainingTechnologyCards?: number[];
    remainingStockShareCards?: {[type: number]: number[] };
    stockShareCardsDummy?: {[type: number]: number[] };
    shareStockPoints?: {[type: number]: number[] };
}
interface PlayerMapSpecificData {
    technologyCards?: number[];
    mountainTrains?: number;
    bulletTrainPosition?: number;
    regionsCount?: number;
    ferryCards?: number;
    stockShareCards?: {[type: number]: number[] };
}

interface TicketToRidePlayer extends Player {
    playerNo: number;
    trainCarsCount: number;
    destinationsCount: number | null;
    remainingTrainCarsCount: number;
    remainingStations?: number;
    legendaryCharacter?: number;
    legendaryCharacterState?: any | null;
    mapSpecificData: PlayerMapSpecificData;

    // for end score
    completedDestinations?: Destination[];
    uncompletedDestinations?: Destination[];
    longestPathLength: number;
    mostConnectedCities: number;
    mandalaCount?: number;
}

interface City {
    id: number;
    name: string;
    x: number;
    y: number;
    extraCoordinates?: number[];
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
    locomotiveUsageRestriction: number;
    minimumPlayerForDoubleRoutes: number;
    minimumPlayerForTripleRoutes: number;
    differentLengthRoutesAreDoubleRoutes: boolean;
    multilingualPdfRulesUrl?: string;
    rulesDifferences?: string[];
    width: number;
    height: number;
    stations: number | null;
    pointsForGlobetrotter: number | null;
    pointsForMostConnectedCities: number | null;
    ferryCards: boolean;
    useTechnologyCards: boolean;
}

/**
 * Your game interfaces
 */

interface TicketToRideGamedatas extends Gamedatas<TicketToRidePlayer> {
    map: TicketToRideMap; 
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

    legendaryCharactersExpansionActive: boolean;
    isGlobetrotterBonusActive: boolean;
    isLongestPathBonusActive: boolean;
    showTurnOrder: boolean;
    mapSpecificData: MapSpecificData;
}

interface EnteringChooseDestinationsArgs {
    _private?: {
        destinations: DestinationReference[];
    };
    destinations?: DestinationReference[];
    minimum: number;
}

interface TunnelAttempt {    
    routeId: number;
    color: number;
    extraCards: number;
    tunnelCards: TrainCar[];
    distribution?: number[];
}

interface NotifPointsArgs {
    playerId: number;
    points: number;
}

interface NotifDestinationsPickedArgs {
    playerId: number;
    /**
     * total destinations
     */
    number: number;
    /**
     * picked destinations
     */
    count: number;
    remainingDestinationsInDeck: number;
    destinations?: DestinationReference[]; // private data
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
    routeId: number;
    removeCards: TrainCar[];
    remainingTrainCars: number;
    shifted?: boolean;
    shiftIndex?: number;
    claimWithBulletTrain?: boolean;
    remainingBulletTrains?: number;
    bulletTrainPosition?: number;
    ferryCardsUsed?: number;
    ferryCardsCount?: number;
}

interface NotifFerryCardDrawnArgs {
    playerId: number;
    ferryCardsCount: number;
}

interface NotifStockShareTakenArgs {
    playerId: number | null;
    type: number;
    cardNumber: number;
    nextCardNumber: number | null;
    remainingCount: number;
    ownerTypeCount: number;
    ownerTotalCount: number;
}

interface NotifTechnologyCardBoughtArgs {
    playerId: number;
    type: number;
    remainingCount: number;
    removeCards: TrainCar[];
}

interface NotifTechnologyCardsExpiredArgs {
    types: number[];
}

interface NotifAddMountainTrainsArgs {
    playerId: number;
    mountainCars: number;
    remainingTrainCars: number;
}

interface NotifBuiltStationArgs {
    playerId: number;
    cityId: number;
    removeCards: TrainCar[];
}

interface NotifDestinationCompletedArgs {
    playerId: number;
    destinationId: number;
    destinationType: number;
    destinationTypeArg: number;
    destinationRouteIds: number[] | null;
    stationCityIds: number[];
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

interface NotifScoreDestinationArgs extends NotifDestinationCompletedArgs {}

interface NotifDiscardDestinationArgs {
    playerId: number;
    destinationId: number;
}

interface NotifLongestPathArgs {
    playerId: number;
    length: number;
    routeIds: number[];
}

interface NotifMostConnectedCitiesArgs {
    playerId: number;
    cities: number;
    connectedCities: number[];
    routeIds: number[];
}

interface NotifMandalaRoutesArgs {
    playerId: number;
    cityIds: number[];
    routeIds: number[];
}


interface NotifBadgeArgs {
    playerId: number;
    length: number;
}

interface NotifBulletTrainBonusArgs {
    playerId: number;
    position: number;
}

interface NotifRegionsBonusArgs {
    playerId: number;
    points: number;
    regionsCount: number;
}

interface NotifRemainingStationsArgs {
    playerId: number;
    remainingStations: number;
}

interface NotifChooseCharacterArgs {
    playerId: number;
    character: number;
}
