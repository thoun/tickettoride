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
            const playerId = Number(player.id);

            dojo.place(`<tr id="score${player.id}">
                <td id="score-name-${player.id}" class="player-name" style="color: #${player.color}">${player.name}<div id="bonus-card-icons-${player.id}" class="bonus-card-icons"></div></td>
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
            this.destinationCounters[playerId] = destinationCounter;
            
            const completedDestinationCounter: Counter = new ebg.counter();
            completedDestinationCounter.create(`completed-destination-counter-${player.id}`);
            completedDestinationCounter.setValue(fromReload ? player.completedDestinations.length : 0);
            this.completedDestinationCounters[playerId] = completedDestinationCounter;
            
            const uncompletedDestinationCounter: Counter = new ebg.counter();
            uncompletedDestinationCounter.create(`uncompleted-destination-counter-${player.id}`);
            uncompletedDestinationCounter.setValue(fromReload ?  player.uncompletedDestinations.length : 0);
            this.uncompletedDestinationCounters[playerId] = uncompletedDestinationCounter;

            const scoreCounter: Counter = new ebg.counter();
            scoreCounter.create(`end-score-${player.id}`);
            scoreCounter.setValue(this.game.getPlayerScore(playerId));
            this.scoreCounters[playerId] = scoreCounter;

            this.moveTrain(playerId);
        });

        // if we are at reload of end state, we display values, else we wait for notifications
        if (fromReload) {
            const longestPath = Math.max(...players.map(player => player.longestPathLength));
            this.setBestScore(bestScore);
            const maxCompletedDestinations = players.map(player => player.completedDestinations.length).reduce((a, b) => (a > b) ? a : b, 0)
            players.forEach(player => {
                if (Number(player.score) == bestScore) {
                    this.highlightWinnerScore(player.id);
                }
                if (this.game.isLongestPathBonusActive() && player.longestPathLength == longestPath) {
                    this.setLongestPathWinner(player.id, longestPath);
                }
                if (this.game.isGlobetrotterBonusActive() && player.completedDestinations.length == maxCompletedDestinations) {
                    this.setGlobetrotterWinner(player.id, maxCompletedDestinations);
                }
                this.updateDestinationsTooltip(player);
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
    public scoreDestination(playerId: number, destination: Destination, destinationRoutes: Route[], isFastEndScoring: boolean = false) { 
        const state = destinationRoutes ? 'completed' : 'uncompleted';
        const endFunction = () => {
            (destinationRoutes ? this.completedDestinationCounters : this.uncompletedDestinationCounters)[playerId].incValue(1);
            this.destinationCounters[playerId].incValue(-1);
            if (this.destinationCounters[playerId].getValue() == 0) {
                document.getElementById(`destination-counter-${playerId}`).classList.add('hidden');
            }
        };

        if (isFastEndScoring) {
            endFunction();
            return;
        }

        const newDac = new DestinationCompleteAnimation(
            this.game,
            destination, 
            destinationRoutes, 
            `destination-counter-${playerId}`,
            `${destinationRoutes ? 'completed' : 'uncompleted'}-destination-counter-${playerId}`,
            {
                change: () => {
                    playSound(`ttr-${destinationRoutes ? 'completed' : 'uncompleted'}-end`);
                    (this.game as any).disableNextMoveSound();
                },

                end: endFunction,
            },
            state,
            0.15 / this.game.getZoom()
        );

        this.game.addAnimation(newDac);
    }
    
    public updateDestinationsTooltip(player: TicketToRidePlayer) {
        let html = `<div class="destinations-flex">
            <div>
                ${player.completedDestinations?.map(destination =>
                    `<div class="destination-card completed" style="${getBackgroundInlineStyleForDestination(this.game.getMap(), destination)}"></div>`
                )}
            </div>
            <div>
                ${player.uncompletedDestinations?.map(destination =>
                    `<div class="destination-card uncompleted" style="${getBackgroundInlineStyleForDestination(this.game.getMap(), destination)}"></div>`
                )}
            </div>
        </div>`;

        if (document.getElementById(`destinations-score-${player.id}`)) {
            this.game.setTooltip(`destinations-score-${player.id}`, html);
        }
    }
    
    /** 
     * Show longest path animation for a player.
     */ 
    public showLongestPath(playerColor: string, routes: Route[], length: number, isFastEndScoring: boolean = false) {
        if (isFastEndScoring) {
            return;
        }
        
        const newDac = new LongestPathAnimation(
            this.game,
            routes, 
            length,
            playerColor,
            {
                end: () => {
                    playSound(`ttr-longest-line-scoring`);
                    (this.game as any).disableNextMoveSound();
                }
            }
        );
        

        this.game.addAnimation(newDac);
    }
    
    /** 
     * Add Globetrotter badge to the Globetrotter winner(s).
     */ 
    public setGlobetrotterWinner(playerId: number | string, length: number) {
        dojo.place(`<div id="globetrotter-bonus-card-${playerId}" class="globetrotter bonus-card bonus-card-icon"></div>`, `bonus-card-icons-${playerId}`);

        this.game.setTooltip(`globetrotter-bonus-card-${playerId}`, `
        <div><strong>${/* TODO1910_*/('Most Completed Tickets')} : ${length}</strong></div>
        <div>${/* TODO1910_*/('The player who completed the most Destination tickets receives this special bonus card and adds 15 points to his score.')}</div>
        <div class="globetrotter bonus-card"></div>
        `);
    }
    
    /** 
     * Add longest path badge to the longest path winner(s).
     */ 
    public setLongestPathWinner(playerId: number | string, length: number) {
        dojo.place(`<div id="longest-path-bonus-card-${playerId}" class="longest-path bonus-card bonus-card-icon"></div>`, `bonus-card-icons-${playerId}`);

        this.game.setTooltip(`longest-path-bonus-card-${playerId}`, `
        <div><strong>${_('Longest path')} : ${length}</strong></div>
        <div>${_('The player who has the Longest Continuous Path of routes receives this special bonus card and adds 10 points to his score.')}</div>
        <div class="longest-path bonus-card"></div>
        `);
    }
}