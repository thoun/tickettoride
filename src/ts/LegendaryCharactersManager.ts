export class LegendaryCharacterManager {
    public getCardHTML(character: number, settings: { id?: string, text?: boolean }) {
        return `
        <div${settings.id ? ` id="${settings.id}"` : ``} class="legendary-character-card" data-character="${character}">${settings.text ?? true ? `
            <div class="timing bga-autofit">${this.getTiming(character)}</div>
            <div class="description bga-autofit bga-autofit__start">${this.getDescription(character)}</div>
        ` : ``}</div>
        `;
    }

    public getTooltipHTML(character: number) {
        return `
        <h3>${this.getCharacterName(character)}</h3>
        <div><strong>${this.getTiming(character)}</strong></div>
        <div>${this.getDescription(character)}</div>
        `;
    }
    
    public getCharacterName(character: number): string {
        switch (character) {
            case 1: return 'Phileas FOGG';
            case 2: return 'Irene ADLER';
            case 3: return 'Mina HARKER';
            case 4: return 'Captain NEMO';
            case 5: return 'Arsène LUPIN';
        }
    }

    private getTiming(character: number): string {
        return character === 3 ? /*TODOLC_*/('Once per turn') : /*TODOLC_*/('Once per game');
    }

    private getDescription(character: number): string {
        switch (character) {
            case 1: return /*TODOLC_*/('On your turn, you can claim a route that has already been claimed by another player, following normal Claim Routes rules as if the route was available. You must use a set of the required number of train cards of the color of the route. Place your trains next to the train cars already on the route.\nThis counts as your turn.').replace('\n', '<br>');
            case 2: return /*TODOLC_*/('At the end of the game, before calculating the final scores, discard up to 2 incomplete tickets.\nThese tickets do not count against your score.').replace('\n', '<br>');
            case 3: return /*TODOLC_*/('During your turn, when you play train cards, you can play 1 set of 2 train cards of any 1 color to count as a locomotive.').replace('\n', '<br>');
            case 4: return /*TODOLC_*/('On your turn, you can play up to 7 train cards to claim as many routes with them as possible, following normal Claim Routes rules. The routes must form a continuous path. Each train card can only be used for 1 route.\nThis counts as your turn.').replace('\n', '<br>');
            case 5: return /*TODOLC_*/('When you claim a route, you can ignore its color and claim it as if it was a gray route. You still have to claim it using a set of train cards of any 1 color.').replace('\n', '<br>');
        }
    }
}