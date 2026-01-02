import { TicketToRideGame } from "../tickettoride.d";

export abstract class StateHandler<Args> {
    public abstract match(stateName: string): boolean | string;

    constructor(protected game: TicketToRideGame, protected bga: Bga) {
    }

    public onEnteringState(args: Args, isCurrentPlayerActive: boolean) {}
    public onLeavingState(args: Args, isCurrentPlayerActive: boolean) {}
    public onUpdateActionButtons(args: Args, isCurrentPlayerActive: boolean) {}

    public get args(): Args {
        return this.game.gamedatas.gamestate.private_state?.args ?? this.game.gamedatas.gamestate.args;
    }
}