import { TrainCar } from "../tickettoride.d";
import { StateHandler } from "./state-handler";

interface EnteringDrawSecondCardArgs {
    availableVisibleCards: TrainCar[];
    maxHiddenCardsPick: number;
}

export class DrawSecondCardState extends StateHandler<EnteringDrawSecondCardArgs> {
    public match(): string { return `drawSecondCard`; }

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