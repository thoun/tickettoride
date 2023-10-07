/**
 * Remaining stations animation : Remaining stations count is displayed over the map.
 */ 
class RemainingStationsAnimation extends WagonsAnimation {

    constructor(
        game: TicketToRideGame,
        private remainingStations: number,
        private playerColor: string,
        private actions: {
            end?: () => void,
        },
    ) {
        super(game, []);
    }

    public animate(): Promise<WagonsAnimation> {
        return new Promise(resolve => {

            dojo.place(`
            <div id="remaining-station-animation">
                ${this.remainingStations}
                <div class="icon station" data-player-color="${this.playerColor}"></div>
            </div>
            `, 'map');
            this.setWagonsVisibility(true);
    
            setTimeout(() => this.endAnimation(resolve), 1900);
        });
    }

    private endAnimation(resolve: any) {
        this.setWagonsVisibility(false);
        const number = document.getElementById('remaining-station-animation');
        number.parentElement.removeChild(number);

        resolve(this);

        this.game.endAnimation(this);
        this.actions.end?.();
    }
}
