import { Route } from "../tickettoride.d";
import { StateHandler } from "./state-handler";

export interface EnteringChooseActionArgs {
    possibleRoutes: Route[];
    costForRoute: { [routeId: number]: { [color: number]: number[] } };
    maxHiddenCardsPick: number;
    maxDestinationsPick: number;
    canTakeTrainCarCards: boolean;
    canPass: boolean;
}

export class ChooseActionState extends StateHandler<EnteringChooseActionArgs> {
    public match(): string { return `chooseAction`; }

    /**
     * Show selectable routes, and make train car draggable.
     */ 
    public onEnteringState(args: EnteringChooseActionArgs, isCurrentPlayerActive: boolean) {
        if (!args.canTakeTrainCarCards) {
            this.bga.statusBar.setTitle(isCurrentPlayerActive ?
                    _('${you} must claim a route or draw destination tickets') :
                    _('${actplayer} must claim a route or draw destination tickets'),
                args);
        }

        this.game.trainCarSelection.setSelectableTopDeck(isCurrentPlayerActive, args.maxHiddenCardsPick);
        
        this.game.map.setSelectableRoutes(isCurrentPlayerActive, args.possibleRoutes);

        this.game.playerTable?.setDraggable(isCurrentPlayerActive);
        this.game.playerTable?.setSelectable(isCurrentPlayerActive);
    }

    public onLeavingState(args: EnteringChooseActionArgs, isCurrentPlayerActive: boolean) {
        this.game.map.setSelectableRoutes(false, []);
        this.game.playerTable?.setDraggable(false);
        this.game.playerTable?.setSelectable(false);   
        this.game.playerTable?.setSelectableTrainCarColors(null);
        document.getElementById('destination-deck-hidden-pile').classList.remove('selectable');
        (Array.from(document.getElementsByClassName('train-car-group hide')) as HTMLDivElement[]).forEach(group => group.classList.remove('hide'));
    }

    public onUpdateActionButtons(args: EnteringChooseActionArgs, isCurrentPlayerActive: boolean) {
        if (isCurrentPlayerActive) {
            if (args.maxDestinationsPick) {
                document.getElementById('destination-deck-hidden-pile').classList.add('selectable');
            }
            this.game.setActionBarChooseAction(false);
        }
    }
}