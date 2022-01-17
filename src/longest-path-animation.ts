class LongestPathAnimation extends WagonsAnimation {

    constructor(
        game: TicketToRideGame,
        private routes: Route[],
        private length: number,
        private playerColor: string,
    ) {
        super(game, routes);
    }

    public animate(): Promise<WagonsAnimation> {
        return new Promise(resolve => {

            dojo.place(`
            <div id="longest-path-animation" style="color: #${this.playerColor};${this.getCardPosition()}">${this.length}</div>
            `, 'map');
            this.setWagonsVisibility(true);
    
            setTimeout(() => this.endAnimation(resolve), 1900);
        });
    }

    private endAnimation(resolve: any) {
        this.setWagonsVisibility(false);
        const number = document.getElementById('longest-path-animation');
        number.parentElement.removeChild(number);

        resolve(this);

        this.game.endAnimation(this);
    }
    
    private getCardPosition() {
        let x = 100;
        let y = 100;
        if (this.routes.length) {
            const positions = [this.routes[0].from, this.routes[this.routes.length-1].to].map(cityId => CITIES.find(city => city.id == cityId));

            x = (positions[0].x + positions[1].x) / 2;
            y = (positions[0].y + positions[1].y) / 2;
        }

        return `left: ${x}px; top: ${y}px;`;
    }
}
