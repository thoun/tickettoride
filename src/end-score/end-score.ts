declare const playSound;

/**
 * End score board.
 * It will start empty, and notifications will update it and start animations one by one.
 */ 
class EndScore {
    /** Player scores (key is player id) */ 
    private scoreCounters: Counter[] = [];
    /** Unrevealed destinations counters (key is player id) */ 
    private destinationCounters: Counter[] = [];
    /** Complete destinations counters (key is player id) */ 
    private completedDestinationCounters: Counter[] = [];
    /** Uncomplete destinations counters (key is player id) */ 
    private uncompletedDestinationCounters: Counter[] = [];

    constructor(
        private game: TicketToRideGame, 
        private players: TicketToRidePlayer[],
        /** fromReload: if a player refresh when game is over, we skip animations (as there will be no notifications to animate the score board) */ 
        fromReload: boolean,
        /** bestScore is the top score for the game, so progression shown as train moving forward is relative to best score */ 
        private bestScore: number,
    ) {        

        players.forEach(player => {

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

        // if we are at reload of end state, we display values, else we wait for notifications
        if (fromReload) {
            const longestPath = Math.max(...players.map(player => player.longestPathLength));
            this.setBestScore(bestScore);
            players.forEach(player => {
                if (Number(player.score) == bestScore) {
                    this.highlightWinnerScore(player.id);
                }
                if (player.longestPathLength == longestPath) {
                    this.setLongestPathWinner(player.id, longestPath);
                }
            });
        }
    }

    /** 
     * Add golden highlight to top score player(s) 
     */ 
    public highlightWinnerScore(playerId: number | string) {
        document.getElementById(`score${playerId}`).classList.add('highlight');
        document.getElementById(`score-name-${playerId}`).style.color = '';
    }

    /** 
     * Save best score so we can move trains.
     */ 
    public setBestScore(bestScore: number) {
        this.bestScore = bestScore;

        this.players.forEach(player => this.moveTrain(Number(player.id)));
    }

    /** 
     * Set score, and animate train to new score.
     */ 
    public setPoints(playerId: number, points: number) {
        this.scoreCounters[playerId].toValue(points);
        this.moveTrain(playerId);
    }

    /** 
     * Move train to represent score progression.
     */ 
    private moveTrain(playerId: number) {
        const scorePercent = 100 * this.scoreCounters[playerId].getValue() / Math.max(50, this.bestScore);
        document.getElementById(`train-image-${playerId}`).style.right = `${100 - scorePercent}%`;
    }
    
    /** 
     * Show score animation for a revealed destination.
     */ 
    public scoreDestination(playerId: number, destination: Destination, destinationRoutes: Route[]) { 
        const newDac = new DestinationCompleteAnimation(
            this.game,
            destination, 
            destinationRoutes, 
            `destination-counter-${playerId}`,
            `${destinationRoutes ? 'completed' : 'uncompleted'}-destination-counter-${playerId}`,
            {
                change: () => {
                    console.log('playSound', `ttr-goal-${destinationRoutes ? 'yes' : 'no'}`);
                    playSound(`ttr-goal-${destinationRoutes ? 'yes' : 'no'}`);
                },

                end: () => {
                    (destinationRoutes ? this.completedDestinationCounters : this.uncompletedDestinationCounters)[playerId].incValue(1);
                    this.destinationCounters[playerId].incValue(-1);
                    if (this.destinationCounters[playerId].getValue() == 0) {
                        document.getElementById(`destination-counter-${playerId}`).classList.add('hidden');
                    }
                },
            },
            destinationRoutes ? 'completed' : 'uncompleted',
            0.15 / this.game.getZoom()
        );

        this.game.addAnimation(newDac);
    }
    
    /** 
     * Show longest path animation for a player.
     */ 
    public showLongestPath(playerColor: string, routes: Route[], length: number) {
        const newDac = new LongestPathAnimation(
            this.game,
            routes, 
            length,
            playerColor
        );

        this.game.addAnimation(newDac);
    }
    
    /** 
     * Add longest path badge to the longest path winner(s).
     */ 
    public setLongestPathWinner(playerId: number | string, length: number) {
        dojo.place(`<div id="longest-path-bonus-card-${playerId}" class="longest-path bonus-card bonus-card-icon"></div>`, `score-name-${playerId}`);

        (this.game as any).addTooltipHtml(`longest-path-bonus-card-${playerId}`, `
        <div><strong>${_('Longest path')} : ${length}</strong></div>
        <div>The player who has the Longest Continuous Path of routes receives this special bonus card and adds 10 points to his score.</div>
        <div class="longest-path bonus-card"></div>
        `);
    }
}