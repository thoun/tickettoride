import { Game } from "../../tickettoride";

export interface EnteringChooseLegendaryCharacterArgsArgs {
    remainingCharacters: number[];
}

export class ChooseLegendaryCharacterState {
    protected game: Game;
    protected bga: Bga;
    protected args: EnteringChooseLegendaryCharacterArgsArgs;

    constructor(game: Game, bga: Bga) {
        this.game = game;
        this.bga = bga;
    }

    /**
     * Show selectable characters.
     */ 
    public onEnteringState(args: EnteringChooseLegendaryCharacterArgsArgs, isCurrentPlayerActive: boolean) {
        document.getElementById('legendary-character-selection-popin')?.remove();

        const popin = document.createElement('div');
        popin.id = 'legendary-character-selection-popin';
        document.getElementById('map-and-borders').insertAdjacentElement('beforeend', popin);
        args.remainingCharacters.forEach(character => {
            const id = `legendary-character-card-${character}-selection`;
            const cardHTML = this.game.legendaryCharacterManager.getCardHTML(character, { id });          
            popin.insertAdjacentHTML('beforeend', cardHTML);
            const cardElement = document.getElementById(id);
            this.game.setTooltip(id, this.game.legendaryCharacterManager.getTooltipHTML(character));
            cardElement.addEventListener('click', () => this.chooseCharacter(character));
        });
        popin.classList.toggle('selectable', isCurrentPlayerActive);
    }

    public onLeavingState(args: EnteringChooseLegendaryCharacterArgsArgs, isCurrentPlayerActive: boolean) {
        document.getElementById('legendary-character-selection-popin')?.remove();
    }

    public chooseCharacter(character: number) {
        this.bga.actions.performAction('actChooseCharacter', { character });
    }
}