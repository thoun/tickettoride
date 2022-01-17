class EndScore {
    private scoreCounters: Counter[] = [];
    private destinationCounters: Counter[] = [];
    private completedDestinationCounters: Counter[] = [];
    private uncompletedDestinationCounters: Counter[] = [];

    constructor(
        private game: TicketToRideGame, 
        private players: TicketToRidePlayer[],
        fromReload: boolean,
        private bestScore: number,
    ) {        

        players.forEach(player => {
            // if we are a reload of end state, we display values, else we wait for notifications

            dojo.place(`<tr id="score${player.id}">
                <td id="score-name-${player.id}" class="player-name" style="color: #${player.color}">${player.name}</td>
                <td id="destinations-score-${player.id}" class="destinations">
                    <div class="icons-grid">
                        <div id="destination-counter-${player.id}" class="icon destination-card"></div>
                        <div id="completed-destination-counter-${player.id}" class="icon completed-destination"></div>
                        <div id="uncompleted-destination-counter-${player.id}" class="icon uncompleted-destination"></div>
                    </div>
                </td>
                <td id="train-score-${player.id}" class="train">
                    <div id="train-image-${player.id}" class="train-image" data-player-color="${player.color}"></div>
                </td>
                <td id="end-score-${player.id}" class="total"></td>
            </tr>`, 'score-table-body');

            const destinationCounter: Counter = new ebg.counter();
            destinationCounter.create(`destination-counter-${player.id}`);
            destinationCounter.setValue(fromReload ? 0 : (this.game as any).destinationCardCounters[player.id].getValue());
            this.destinationCounters[Number(player.id)] = destinationCounter;
            
            const completedDestinationCounter: Counter = new ebg.counter();
            completedDestinationCounter.create(`completed-destination-counter-${player.id}`);
            completedDestinationCounter.setValue(fromReload ? player.completedDestinations.length : 0);
            this.completedDestinationCounters[Number(player.id)] = completedDestinationCounter;
            
            const uncompletedDestinationCounter: Counter = new ebg.counter();
            uncompletedDestinationCounter.create(`uncompleted-destination-counter-${player.id}`);
            uncompletedDestinationCounter.setValue(fromReload ? destinationCounter.getValue() - completedDestinationCounter.getValue() : 0);
            this.uncompletedDestinationCounters[Number(player.id)] = uncompletedDestinationCounter;

            const scoreCounter: Counter = new ebg.counter();
            scoreCounter.create(`end-score-${player.id}`);
            scoreCounter.setValue(Number(player.score));
            this.scoreCounters[Number(player.id)] = scoreCounter;

        });

        if (fromReload) {
            this.setBestScore(bestScore);
            players.forEach(player => {
                if (Number(player.score) == bestScore) {
                    this.highlightWinnerScore(player.id);
                }
            });
        }
    }

    private highlightWinnerScore(playerId: number | string) {
        document.getElementById(`score${playerId}`).classList.add('highlight');
        document.getElementById(`score-name-${playerId}`).style.color = '';
    }

    public setBestScore(bestScore: number) {
        this.bestScore = bestScore;

        this.players.forEach(player => this.moveTrain(Number(player.id)));
    }

    public setPoints(playerId: number, points: number) {
        this.scoreCounters[playerId].toValue(points);
        this.moveTrain(playerId);
    }

    private moveTrain(playerId: number) {
        const scorePercent = 100 * this.scoreCounters[playerId].getValue() / Math.max(50, this.bestScore);
        document.getElementById(`train-image-${playerId}`).style.right = `${100 - scorePercent}%`;
    }
    
    public scoreDestination(playerId: number, destination: Destination, destinationRoutes: Route[]) { 
        const newDac = new DestinationCompleteAnimation(
            this.game,
            destination, 
            destinationRoutes, 
            `destination-counter-${playerId}`,
            `${destinationRoutes ? 'completed' : 'uncompleted'}-destination-counter-${playerId}`,
            {
                end: () => {
                    (destinationRoutes ? this.completedDestinationCounters : this.uncompletedDestinationCounters)[playerId].incValue(1);
                    this.destinationCounters[playerId].incValue(-1);
                    if (this.destinationCounters[playerId].getValue() == 0) {
                        document.getElementById(`destination-counter-${playerId}`).classList.add('hidden');
                    }
                },
            },
            destinationRoutes ? 'completed' : 'uncompleted',
            0.25
        );

        this.game.addAnimation(newDac);
    }
    
    public showLongestPath(playerColor: string, routes: Route[], length: number) {
        const newDac = new LongestPathAnimation(
            this.game,
            routes, 
            length,
            playerColor
        );

        this.game.addAnimation(newDac);
    }
}