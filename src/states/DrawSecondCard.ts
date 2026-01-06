import { TicketToRideGame, TrainCar } from "../tickettoride.d";

interface EnteringDrawSecondCardArgs {
    availableVisibleCards: TrainCar[];
    maxHiddenCardsPick: number;
}

export class DrawSecondCardState {
    protected game: TicketToRideGame;
    protected bga: Bga;

    constructor(game: TicketToRideGame, bga: Bga) {
        this.game = game;
        this.bga = bga;
    }

    /**
     * Allow to pick a second card (locomotives will be grayed).
     */
    public onEnteringState(args: EnteringDrawSecondCardArgs, isCurrentPlayerActive: boolean) {
        this.game.trainCarSelection.setSelectableTopDeck(isCurrentPlayerActive, args.maxHiddenCardsPick);
        this.game.trainCarSelection.setSelectableVisibleCards(args.availableVisibleCards);
    }

    public onLeavingState(args: EnteringDrawSecondCardArgs, isCurrentPlayerActive: boolean) {
        this.game.trainCarSelection.removeSelectableVisibleCards();  
    }
}