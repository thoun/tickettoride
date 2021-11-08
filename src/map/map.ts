const POINT_CASE_SIZE = 25.5;
const BOARD_POINTS_MARGIN = 38;

class Route {
    constructor(
        public id: number,
        public from: number,
        public to: number,
        public number: number,
        public color: number,
    ) {}
}

const ROUTES = [
    new Route(1, 1, 4, 2, GRAY),
    new Route(2, 1, 16, 5, BLUE),
    new Route(3, 1, 18, 1, GRAY),
    new Route(4, 1, 19, 4, YELLOW),
    new Route(5, 1, 19, 4, ORANGE),
    new Route(6, 1, 26, 2, GRAY),
    new Route(7, 1, 26, 2, GRAY),
    new Route(8, 2, 17, 2, GRAY),
    new Route(9, 2, 17, 2, GRAY),
    new Route(10, 2, 20, 2, YELLOW),
    new Route(11, 2, 20, 2, RED),
    new Route(12, 3, 10, 4, GRAY),
    new Route(12, 3, 32, 4, GRAY),
    new Route(13, 3, 34, 3, GRAY),
    new Route(14, 3, 36, 6, WHITE),
    new Route(15, 4, 16, 4, PINK),
    new Route(16, 4, 26, 5, GRAY),
    new Route(17, 5, 8, 3, RED),
    new Route(18, 5, 22, 4, BLUE),
    new Route(19, 5, 24, 3, ORANGE),
    new Route(20, 5, 24, 3, BLACK),
    new Route(21, 5, 27, 2, GREEN),
    new Route(22, 5, 27, 2, WHITE),
    new Route(23, 5, 33, 4, WHITE),
    new Route(24, 6, 9, 4, RED),
    new Route(25, 6, 11, 1, GRAY),
    new Route(26, 6, 11, 1, GRAY),
    new Route(27, 6, 14, 2, GRAY),
    new Route(28, 6, 21, 2, GRAY),
    new Route(29, 6, 21, 2, GRAY),
    new Route(30, 7, 10, 4, GREEN),
    new Route(31, 7, 12, 4, BLACK),
    new Route(32, 7, 12, 4, ORANGE),
    new Route(33, 7, 21, 4, RED),
    new Route(34, 7, 22, 4, PINK),
    new Route(35, 7, 23, 5, WHITE),
    new Route(36, 7, 28, 3, RED),
    new Route(37, 7, 28, 3, YELLOW),
    new Route(38, 8, 10, 6, ORANGE),
    new Route(39, 8, 22, 2, GRAY),
    new Route(40, 8, 22, 2, GRAY),
    new Route(41, 8, 29, 3, GRAY),
    new Route(42, 8, 33, 6, PINK),
    new Route(43, 8, 36, 4, BLACK),
    new Route(44, 9, 11, 6, GREEN),
    new Route(45, 9, 15, 6, BLACK),
    new Route(46, 9, 21, 5, YELLOW),
    new Route(47, 9, 23, 3, GRAY),
    new Route(48, 9, 31, 2, GRAY),
    new Route(49, 10, 22, 5, RED),
    new Route(50, 10, 28, 3, PINK),
    new Route(51, 10, 32, 6, YELLOW),
    new Route(52, 10, 36, 4, BLUE),
    new Route(53, 11, 19, 2, GRAY),
    new Route(54, 12, 21, 2, GRAY),
    new Route(55, 12, 21, 2, GRAY),
    new Route(56, 12, 22, 1, GRAY),
    new Route(57, 12, 22, 1, GRAY),
    new Route(58, 12, 27, 2, BLUE),
    new Route(59, 12, 27, 2, PINK),
    new Route(60, 13, 15, 2, GRAY),
    new Route(61, 13, 28, 3, ORANGE),
    new Route(62, 14, 18, 3, WHITE),
    new Route(63, 14, 19, 3, GREEN),
    new Route(64, 14, 21, 2, GRAY),
    new Route(65, 14, 27, 2, GRAY),
    new Route(66, 15, 23, 3, GRAY),
    new Route(67, 15, 30, 3, YELLOW),
    new Route(68, 15, 30, 3, PINK),
    new Route(69, 16, 19, 6, RED),
    new Route(70, 17, 20, 3, BLUE),
    new Route(71, 17, 29, 5, BLACK),
    new Route(72, 17, 33, 3, GRAY),
    new Route(73, 18, 24, 3, BLACK),
    new Route(74, 18, 26, 4, YELLOW),
    new Route(75, 18, 27, 2, GRAY),
    new Route(76, 20, 24, 2, WHITE),
    new Route(77, 20, 24, 2, GREEN),
    new Route(78, 20, 35, 2, ORANGE),
    new Route(79, 20, 35, 2, BLACK),
    new Route(80, 21, 31, 3, BLUE),
    new Route(81, 23, 31, 3, GRAY),
    new Route(82, 24, 26, 2, GRAY),
    new Route(83, 24, 27, 5, GREEN),
    new Route(84, 24, 33, 2, GRAY),
    new Route(85, 24, 35, 2, GRAY),
    new Route(86, 25, 28, 6, BLUE),
    new Route(87, 25, 30, 5, GREEN),
    new Route(88, 25, 30, 5, PINK),
    new Route(89, 25, 32, 1, GRAY),
    new Route(90, 25, 32, 1, GRAY),
    new Route(91, 26, 35, 2, GRAY),
    new Route(92, 26, 35, 2, GRAY),
    new Route(93, 28, 30, 5, ORANGE),
    new Route(94, 28, 30, 5, WHITE),
    new Route(95, 29, 33, 2, GRAY),
    new Route(96, 29, 36, 6, GRAY),
    new Route(97, 32, 34, 1, GRAY),
    new Route(98, 32, 34, 1, GRAY),
  ];

class TtrMap {
    private points = new Map<number, number>();

    constructor(
        private game: TicketToRideGame,
        private players: TicketToRidePlayer[],
    ) {
        let html = '';

        // points
        players.forEach(player => {
            html += `<div id="player-${player.id}-point-marker" class="point-marker ${this.game.isColorBlindMode() ? 'color-blind' : ''}" data-player-no="${player.playerNo}" style="background: #${player.color};"></div>`;
            this.points.set(Number(player.id), Number(player.score));
        });
        dojo.place(html, 'board');

        ROUTES.forEach(route => {
            dojo.place(`<span id="route${route.id}">&nbsp; ${CITIES[route.from]} to ${CITIES[route.to]}, ${route.number} ${COLORS[route.color]} &nbsp;</span>`, 'board');
            document.getElementById(`route${route.id}`).addEventListener('click', () => this.game.claimRoute(route.id));   
        });
    }

    public setPoints(playerId: number, points: number) {
        this.points.set(playerId, points);
        this.movePoints();
    }

    private getPointsCoordinates(points: number) {
        const top = points < 86 ? Math.min(Math.max(points - 34, 0), 17) * POINT_CASE_SIZE : (102 - points) * POINT_CASE_SIZE;
        const left = points < 52 ? Math.min(points, 34) * POINT_CASE_SIZE : (33 - Math.max(points - 52, 0))*POINT_CASE_SIZE;

        return [17 + left, 15 + top];
    }

    private movePoints() {
        this.points.forEach((points, playerId) => {
            const markerDiv = document.getElementById(`player-${playerId}-point-marker`);

            const coordinates = this.getPointsCoordinates(points);
            const left = coordinates[0];
            const top = coordinates[1];
    
            let topShift = 0;
            let leftShift = 0;
            this.points.forEach((iPoints, iPlayerId) => {
                if (iPoints === points && iPlayerId < playerId) {
                    topShift += 5;
                    leftShift += 5;
                }
            });
    
            markerDiv.style.transform = `translateX(${left + leftShift}px) translateY(${top + topShift}px)`;
        });
    }
}