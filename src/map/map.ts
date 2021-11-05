class TtrMap {

    constructor(
        private game: TicketToRideGame
    ) {
        document.getElementById('board').addEventListener('click', (e: MouseEvent) => this.game.claimRoute(Math.floor(e.x / 10)));   
    }
}