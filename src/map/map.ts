const POINT_CASE_SIZE = 25.5;
const BOARD_POINTS_MARGIN = 38;

class RouteSpace {
    constructor(
        public x: number,
        public y: number,
        public angle: number,
    ) {}
}

class Route {
    constructor(
        public id: number,
        public from: number,
        public to: number,
        public spaces: number | RouteSpace[],
        public color: number,
    ) {}
}

const ROUTES = [
    new Route(1, 1, 4, [
        new RouteSpace(1346, 689, 3),
        new RouteSpace(1409, 691, 3),
    ], GRAY),
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
    new Route(24, 6, 9, [
        new RouteSpace(653, 885, 351),
        new RouteSpace(714, 875, 351),
        new RouteSpace(776, 866, 351),
        new RouteSpace(838, 857, 351),
    ], RED),
    new Route(25, 6, 11, [        
        new RouteSpace(915, 882, 49),
    ], GRAY),
    new Route(26, 6, 11, [
        new RouteSpace(932, 868, 49),
    ], GRAY),
    new Route(27, 6, 14, [
        new RouteSpace(936, 786, -55),
        new RouteSpace(972, 735, -55),
    ], GRAY),
    new Route(28, 6, 21, [
        new RouteSpace(864, 735, -97),
        new RouteSpace(872, 797, -97),
    ], GRAY),
    new Route(29, 6, 21, [
        new RouteSpace(885, 733, -97),
        new RouteSpace(893, 795, -97),
    ], GRAY),
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
    new Route(44, 9, 11, [        
        new RouteSpace(611, 913, 30),
        new RouteSpace(668, 939, 18),
        new RouteSpace(729, 953, 8),
        new RouteSpace(792, 959, 2),
        new RouteSpace(855, 955, -10),
        new RouteSpace(915, 939, -19),
    ], GREEN),
    new Route(45, 9, 15, 6, BLACK),
    new Route(46, 9, 21, 5, YELLOW),
    new Route(47, 9, 23, 3, GRAY),
    new Route(48, 9, 31, 2, GRAY),
    new Route(49, 10, 22, 5, RED),
    new Route(50, 10, 28, 3, PINK),
    new Route(51, 10, 32, 6, YELLOW),
    new Route(52, 10, 36, 4, BLUE),
    new Route(53, 11, 19, [        
        new RouteSpace(1070, 895, 352),
        new RouteSpace(1009, 904, 352),
    ], GRAY),
    new Route(54, 12, 21, 2, GRAY),
    new Route(55, 12, 21, 2, GRAY),
    new Route(56, 12, 22, 1, GRAY),
    new Route(57, 12, 22, 1, GRAY),
    new Route(58, 12, 27, 2, BLUE),
    new Route(59, 12, 27, 2, PINK),
    new Route(60, 13, 15, 2, GRAY),
    new Route(61, 13, 28, 3, ORANGE),
    new Route(62, 14, 18, [
        new RouteSpace(1056, 691, -4),
        new RouteSpace(1119, 675, -24),
        new RouteSpace(1173, 640, -41),
    ], WHITE),
    new Route(63, 14, 19, [
        new RouteSpace(1036, 737, 63),
        new RouteSpace(1064, 792, 63),
        new RouteSpace(1094, 848, 63),
    ], GREEN),
    new Route(64, 14, 21, [
        new RouteSpace(905, 690, -2),
        new RouteSpace(966, 688, -2),
    ], GRAY),
    new Route(65, 14, 27, 2, GRAY),
    new Route(66, 15, 23, 3, GRAY),
    new Route(67, 15, 30, 3, YELLOW),
    new Route(68, 15, 30, 3, PINK),
    new Route(69, 16, 19, [
        new RouteSpace(1465, 943, 49),
        new RouteSpace(1421, 898, 40),
        new RouteSpace(1369, 863, 28),
        new RouteSpace(1308, 848, 0),
        new RouteSpace(1245, 855, 345),
        new RouteSpace(1187, 881, 330),
    ], RED),
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
        claimedRoutes: ClaimedRoute[],
    ) {
        let html = '';

        // points
        players.forEach(player => {
            html += `<div id="player-${player.id}-point-marker" class="point-marker ${this.game.isColorBlindMode() ? 'color-blind' : ''}" data-player-no="${player.playerNo}" style="background: #${player.color};"></div>`;
            this.points.set(Number(player.id), Number(player.score));
        });
        dojo.place(html, 'board');

        ROUTES.forEach(route => {
            if (typeof route.spaces === 'number') {
                dojo.place(`<div id="route${route.id}" class="route">${CITIES[route.from]} to ${CITIES[route.to]}, ${route.spaces} ${COLORS[route.color]}</div>`, 'board');
                document.getElementById(`route${route.id}`).addEventListener('click', () => this.game.claimRoute(route.id));   
            } else {
                route.spaces.forEach((space, spaceIndex) => {
                    dojo.place(`<div id="route${route.id}-space${spaceIndex}" class="route space" 
                        style="transform: translate(${space.x}px, ${space.y}px) rotate(${space.angle}deg)"
                        title="${CITIES[route.from]} to ${CITIES[route.to]}, ${(route.spaces as any).length} ${COLORS[route.color]}"
                    ></div>`, 'board');
                    document.getElementById(`route${route.id}-space${spaceIndex}`).addEventListener('click', () => this.game.claimRoute(route.id));   
                });
            }
        });

        this.movePoints();
        this.setClaimedRoutes(claimedRoutes);
    }

    public setPoints(playerId: number, points: number) {
        this.points.set(playerId, points);
        this.movePoints();
    }
    
    public setSelectableRoutes(selectable: boolean, possibleRoutes: Route[]) {
        if (selectable) {
            possibleRoutes.forEach(route => document.getElementById(`route${route.id}`).classList.add('selectable'));
        } else {            
            dojo.query('.route').removeClass('selectable');
        }
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

    public setClaimedRoutes(claimedRoutes: ClaimedRoute[]) {
        claimedRoutes.forEach(claimedRoute => {
            const route = ROUTES.find(r => r.id == claimedRoute.routeId);
            const color = `#${this.players.find(player => Number(player.id) == claimedRoute.playerId).color}`;
            if (typeof route.spaces === 'number') {
                document.getElementById(`route${claimedRoute.routeId}`).style.borderColor = color;
            } else {
                route.spaces.forEach((_, spaceIndex) => {
                    const spaceDiv = document.getElementById(`route${route.id}-space${spaceIndex}`);
                    spaceDiv.style.borderColor = color;
                    spaceDiv.style.background = color;
                });
            }
            
        });
    }
}