const POINT_CASE_SIZE = 25.5;
const BOARD_POINTS_MARGIN = 38;

const SIDES = ['left', 'right', 'top', 'bottom'];
const CORNERS = ['bottom-left', 'bottom-right', 'top-left', 'top-right'];

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
    new Route(2, 1, 16, [
        new RouteSpace(1319, 722, 51),
        new RouteSpace(1359, 771, 51),
        new RouteSpace(1398, 820, 51),
        new RouteSpace(1437, 868, 51),
        new RouteSpace(1476, 916, 51),
    ], BLUE),
    new Route(3, 1, 18, [
        new RouteSpace(1243, 636, 35),
    ], GRAY),
    new Route(4, 1, 19, [
        new RouteSpace(1139, 843, 291),
        new RouteSpace(1167, 787, 303),
        new RouteSpace(1205, 737, 310),
        new RouteSpace(1249, 692, 319),
    ], YELLOW),
    new Route(5, 1, 19, [
        new RouteSpace(1155, 859, 291),
        new RouteSpace(1184, 803, 303),
        new RouteSpace(1222, 753, 310),
        new RouteSpace(1265, 709, 319),
    ], ORANGE),
    new Route(6, 1, 26, [
        new RouteSpace(1319, 632, -41),
        new RouteSpace(1366, 591, -41),
    ], GRAY),
    new Route(7, 1, 26, [
        new RouteSpace(1380, 607, -41),
        new RouteSpace(1333, 649, -41),
    ], GRAY),
    new Route(8, 2, 17, [
        new RouteSpace(1509, 84, 40),
        new RouteSpace(1557, 123, 40),
    ], GRAY),
    new Route(9, 2, 17, [
        new RouteSpace(1494, 100, 40),
        new RouteSpace(1542, 140, 40),
    ], GRAY),
    new Route(10, 2, 20, [
        new RouteSpace(1554, 201, 122),
        new RouteSpace(1521, 254, 122),
    ], YELLOW),
    new Route(11, 2, 20, [
        new RouteSpace(1572, 212, 122),
        new RouteSpace(1539, 265, 122),
    ], RED),
    new Route(12, 3, 10, 4, GRAY),
    new Route(12, 3, 32, 4, GRAY),
    new Route(13, 3, 34, 3, GRAY),
    new Route(14, 3, 36, 6, WHITE),
    new Route(15, 4, 16, [
        new RouteSpace(1458, 727, 87),
        new RouteSpace(1463, 790, 82),
        new RouteSpace(1478, 852, 73),
        new RouteSpace(1503, 909, 59),
    ], PINK),
    new Route(16, 4, 26, [
        new RouteSpace(1443, 598, 35),
        new RouteSpace(1471, 640, -65),
    ], GRAY),
    new Route(17, 5, 8, [
        new RouteSpace(944, 320, 28),
        new RouteSpace(1001, 346, 21),
        new RouteSpace(1062, 364, 14),
    ], RED),
    new Route(18, 5, 22, [
        new RouteSpace(897, 432, -34),
        new RouteSpace(947, 398, -34),
        new RouteSpace(1009, 383, 8),
        new RouteSpace(1068, 392, 8),
    ], BLUE),
    new Route(19, 5, 24, [
        new RouteSpace(1161, 363, -14),
        new RouteSpace(1223, 350, -9),
        new RouteSpace(1286, 347, 3),
    ], ORANGE),
    new Route(20, 5, 24, [
        new RouteSpace(1169, 385, -14),
        new RouteSpace(1231, 372, -9),
        new RouteSpace(1293, 369, 3),
    ], BLACK),
    new Route(21, 5, 27, [
        new RouteSpace(1046, 488, -57),
        new RouteSpace(1079, 437, -57),
    ], GREEN),
    new Route(22, 5, 27, [
        new RouteSpace(1065, 497, -57),
        new RouteSpace(1098, 446, -57),
    ], WHITE),
    new Route(23, 5, 33, [
        new RouteSpace(1128, 346, -48),
        new RouteSpace(1176, 305, -35),
        new RouteSpace(1233, 277, -17),
        new RouteSpace(1289, 248, -39),
    ], WHITE),
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
    new Route(30, 7, 10, [
        new RouteSpace(513, 344, 67),
        new RouteSpace(535, 401, 67),
        new RouteSpace(559, 458, 67),
        new RouteSpace(582, 517, 67),
    ], GREEN),
    new Route(31, 7, 12, [
        new RouteSpace(654, 570, 5),
        new RouteSpace(717, 571, -5),
        new RouteSpace(779, 562, -11),
        new RouteSpace(840, 543, -23),
    ], BLACK),
    new Route(32, 7, 12, [
        new RouteSpace(654, 593, 5),
        new RouteSpace(717, 594, -5),
        new RouteSpace(779, 585, -11),
        new RouteSpace(840, 566, -23),
    ], ORANGE),
    new Route(33, 7, 21, [
        new RouteSpace(625, 621, 224),
        new RouteSpace(678, 658, 205),
        new RouteSpace(737, 675, 189),
        new RouteSpace(800, 681, 184),
    ], RED),
    new Route(34, 7, 22, [
        new RouteSpace(631, 533, -38),
        new RouteSpace(684, 500, -26),
        new RouteSpace(742, 477, -16),
        new RouteSpace(804, 463, -12),
    ], PINK),
    new Route(35, 7, 23, [
        new RouteSpace(384, 775, -68),
        new RouteSpace(411, 721, -61),
        new RouteSpace(444, 667, -55),
        new RouteSpace(485, 621, -39),
        new RouteSpace(541, 589, -20),
    ], WHITE),
    new Route(36, 7, 28, [
        new RouteSpace(417, 511, 11),
        new RouteSpace(479, 523, 11),
        new RouteSpace(538, 535, 11),
    ], RED),
    new Route(37, 7, 28, [
        new RouteSpace(413, 532, 11),
        new RouteSpace(475, 544, 11),
        new RouteSpace(534, 556, 11),
    ], YELLOW),

    new Route(99, 7, 31, [
        new RouteSpace(584, 620, 273),
        new RouteSpace(581, 682, 273),
    ], GRAY),
    
    new Route(38, 8, 10, [
        new RouteSpace(543, 297, -1),
        new RouteSpace(606, 297, -1),
        new RouteSpace(669, 296, -1),
        new RouteSpace(732, 295, -1),
        new RouteSpace(795, 294, -1),
        new RouteSpace(858, 293, -1),
    ], ORANGE),
    new Route(39, 8, 22, [
        new RouteSpace(877, 338, -68),
        new RouteSpace(854, 396, -68),
    ], GRAY),
    new Route(40, 8, 22, [
        new RouteSpace(897, 345, -68),
        new RouteSpace(874, 403, -68),
    ], GRAY),
    new Route(41, 8, 29, [
        new RouteSpace(965, 248, -23),
        new RouteSpace(1022, 225, -23),
        new RouteSpace(1080, 200, -23),
    ], GRAY),
    new Route(42, 8, 33, [
        new RouteSpace(953, 281, -8),
        new RouteSpace(1015, 270, -8),
        new RouteSpace(1077, 259, -8),
        new RouteSpace(1139, 249, -8),
        new RouteSpace(1200, 238, -8),
        new RouteSpace(1263, 227, -8),
    ], PINK),
    new Route(43, 8, 36, 4, BLACK),
    new Route(44, 9, 11, [        
        new RouteSpace(611, 913, 30),
        new RouteSpace(668, 939, 18),
        new RouteSpace(729, 953, 8),
        new RouteSpace(792, 959, 2),
        new RouteSpace(855, 955, -10),
        new RouteSpace(915, 939, -19),
    ], GREEN),
    new Route(45, 9, 15, [
        new RouteSpace(202, 840, 36),
        new RouteSpace(256, 872, 24),
        new RouteSpace(316, 893, 15),
        new RouteSpace(378, 905, 9),
        new RouteSpace(441, 907, -4),
        new RouteSpace(502, 898, -14),
    ], BLACK),
    new Route(46, 9, 21, [
        new RouteSpace(619, 866, 342),
        new RouteSpace(677, 843, 334),
        new RouteSpace(733, 811, 326),
        new RouteSpace(781, 771, 315),
        new RouteSpace(822, 723, 305),
    ], YELLOW),
    new Route(47, 9, 23, [
        new RouteSpace(529, 870, 16),
        new RouteSpace(469, 852, 16),
        new RouteSpace(409, 835, 16),
    ], GRAY),
    new Route(48, 9, 31, [
        new RouteSpace(574, 837, 273),
        new RouteSpace(577, 773, 273),
    ], GRAY),
    new Route(49, 10, 22, [
        new RouteSpace(559, 332, 22),
        new RouteSpace(616, 355, 22),
        new RouteSpace(675, 379, 22),
        new RouteSpace(734, 403, 22),
        new RouteSpace(791, 427, 22),
    ], RED),
    new Route(50, 10, 28, [
        new RouteSpace(463, 346, -59),
        new RouteSpace(432, 399, -59),
        new RouteSpace(399, 453, -59),
    ], PINK),
    new Route(51, 10, 32, [
        new RouteSpace(133, 225, 12),
        new RouteSpace(194, 239, 12),
        new RouteSpace(255, 253, 12),
        new RouteSpace(316, 267, 12),
        new RouteSpace(377, 281, 12),
        new RouteSpace(438, 294, 12),
    ], YELLOW),
    new Route(52, 10, 36, 4, BLUE),
    new Route(53, 11, 19, [        
        new RouteSpace(1070, 895, 352),
        new RouteSpace(1009, 904, 352),
    ], GRAY),
    new Route(54, 12, 21, [
        new RouteSpace(876, 583, -74),
        new RouteSpace(859, 642, -74),
    ], GRAY),
    new Route(55, 12, 21, [
        new RouteSpace(897, 589, -74),
        new RouteSpace(880, 648, -74),
    ], GRAY),
    new Route(56, 12, 22, [
        new RouteSpace(866, 499, -115),
    ], GRAY),
    new Route(57, 12, 22, [
        new RouteSpace(885, 490, -115),
    ], GRAY),
    new Route(58, 12, 27, [
        new RouteSpace(934, 525, -1),
        new RouteSpace(996, 523, -1),            
    ], BLUE),
    new Route(59, 12, 27, [
        new RouteSpace(934, 547, -1),
        new RouteSpace(996, 545, -1), 
    ], PINK),
    new Route(60, 13, 15, [
        new RouteSpace(173, 754, -65),
        new RouteSpace(222, 713, -12),
    ], GRAY),
    new Route(61, 13, 28, [
        new RouteSpace(310, 680, -45),
        new RouteSpace(345, 628, -67),
        new RouteSpace(362, 567, -81),
    ], ORANGE),
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
    new Route(65, 14, 27, [
        new RouteSpace(1033, 586, -75),
        new RouteSpace(1018, 647, -75),
    ], GRAY),
    new Route(66, 15, 23, [
        new RouteSpace(201, 793, -8),
        new RouteSpace(263, 790, 1),
        new RouteSpace(326, 797, 13),
    ], GRAY),
    new Route(67, 15, 30, [
        new RouteSpace(35, 678, -113),
        new RouteSpace(65, 734, -125),
        new RouteSpace(105, 782, -134),
    ], YELLOW),
    new Route(68, 15, 30, [
        new RouteSpace(53, 666, -113),
        new RouteSpace(84, 721, -125),
        new RouteSpace(123, 769, -134),
    ], PINK),
    new Route(69, 16, 19, [
        new RouteSpace(1465, 943, 49),
        new RouteSpace(1421, 898, 40),
        new RouteSpace(1369, 863, 28),
        new RouteSpace(1308, 848, 0),
        new RouteSpace(1245, 855, 345),
        new RouteSpace(1187, 881, 330),
    ], RED),
    new Route(70, 17, 20, [
        new RouteSpace(1456, 118, 80),
        new RouteSpace(1466, 181, 80),
        new RouteSpace(1476, 241, 80),
    ], BLUE),
    new Route(71, 17, 29, 5, BLACK),
    new Route(72, 17, 33, [
        new RouteSpace(1323, 166, -59),
        new RouteSpace(1363, 117, -42),
        new RouteSpace(1417, 83, -23),
    ], GRAY),
    new Route(73, 18, 24, [
        new RouteSpace(1198, 568, -62),
        new RouteSpace(1232, 515, -51),
        new RouteSpace(1279, 472, -33),
        new RouteSpace(1325, 429, -56),
    ], YELLOW),
    new Route(74, 18, 26, [
        new RouteSpace(1242, 578, -32),
        new RouteSpace(1300, 554, -13),
        new RouteSpace(1363, 548, 4),
    ], BLACK),
    new Route(75, 18, 27, [
        new RouteSpace(1085, 578, 17),
        new RouteSpace(1144, 597, 17),
    ], GRAY),
    new Route(76, 20, 24, [
        new RouteSpace(1386, 331, -31),
        new RouteSpace(1439, 299, -31),
    ], WHITE),
    new Route(77, 20, 24, [
        new RouteSpace(1397, 350, -31),
        new RouteSpace(1450, 318, -31),
    ], GREEN),
    new Route(78, 20, 35, [
        new RouteSpace(1494, 341, 87),
        new RouteSpace(1497, 404, 87),
    ], ORANGE),
    new Route(79, 20, 35, [
        new RouteSpace(1515, 339, 87),
        new RouteSpace(1518, 402, 87),
    ], BLACK),
    new Route(80, 21, 31, [
        new RouteSpace(633, 724, -7),
        new RouteSpace(694, 718, -7),
        new RouteSpace(757, 710, -7),
    ], BLUE),
    new Route(81, 23, 31, [
        new RouteSpace(421, 795, -23),
        new RouteSpace(480, 769, -23),
        new RouteSpace(536, 743, -23),
    ], GRAY),
    new Route(82, 24, 26, [
        new RouteSpace(1369, 438, 77),
        new RouteSpace(1383, 500, 77),
    ], GRAY),
    new Route(83, 24, 27, [
        new RouteSpace(1085, 531, -30),
        new RouteSpace(1139, 499, -30),
        new RouteSpace(1193, 468, -30),
        new RouteSpace(1248, 437, -30),
        new RouteSpace(1302, 406, -30),
    ], GREEN),
    new Route(84, 24, 33, [
        new RouteSpace(1332, 256, -93),
        new RouteSpace(1336, 320, -93),
    ], GRAY),
    new Route(85, 24, 35, [
        new RouteSpace(1400, 401, 28),
        new RouteSpace(1456, 430, 28),
    ], GRAY),
    new Route(86, 25, 28, [
        new RouteSpace(96, 292, 12),
        new RouteSpace(156, 309, 20),
        new RouteSpace(214, 338, 33),
        new RouteSpace(265, 375, 40),
        new RouteSpace(310, 418, 49),
        new RouteSpace(346, 469, 61),
    ], BLUE),
    new Route(87, 25, 30, [
        new RouteSpace(17, 327, 111),
        new RouteSpace(-2, 387, 102),
        new RouteSpace(-9, 449, 93),
        new RouteSpace(-8, 512, 86),
        new RouteSpace(3, 575, 73),
    ], GREEN),
    new Route(88, 25, 30, [
        new RouteSpace(39, 330, 111),
        new RouteSpace(20, 390, 102),
        new RouteSpace(13, 452, 93),
        new RouteSpace(14, 515, 86),
        new RouteSpace(25, 578, 73),
    ], PINK),
    new Route(89, 25, 32, [
        new RouteSpace(54, 234, 112),
    ], GRAY),
    new Route(90, 25, 32, [
        new RouteSpace(74, 241, 112),
    ], GRAY),
    new Route(91, 26, 35, [
        new RouteSpace(1431, 528, -50),
        new RouteSpace(1472, 480, -50),
    ], GRAY),
    new Route(92, 26, 35, [
        new RouteSpace(1447, 542, -50),
        new RouteSpace(1488, 494, -50),
    ], GRAY),
    new Route(93, 28, 30, [
        new RouteSpace(78, 597, -18),
        new RouteSpace(137, 578, -18),
        new RouteSpace(195, 559, -18),
        new RouteSpace(253, 539, -18),
        new RouteSpace(312, 520, -18),
    ], ORANGE),
    new Route(94, 28, 30, [
        new RouteSpace(85, 617, -18),
        new RouteSpace(143, 598, -18),
        new RouteSpace(202, 579, -18),
        new RouteSpace(260, 559, -18),
        new RouteSpace(319, 540, -18),
    ], WHITE),
    new Route(95, 29, 33, [
        new RouteSpace(1184, 183, 11),
        new RouteSpace(1246, 196, 11),
    ], GRAY),
    new Route(96, 29, 36, [
        new RouteSpace(773, 91, 12),
        new RouteSpace(834, 103, 12),
        new RouteSpace(896, 116, 12),
        new RouteSpace(956, 129, 12),
        new RouteSpace(1017, 142, 12),
        new RouteSpace(1078, 154, 12),
    ], GRAY),
    new Route(97, 32, 34, 1, GRAY),
    new Route(98, 32, 34, 1, GRAY),
  ];

class TtrMap {
    //private points = new Map<number, number>();

    constructor(
        private game: TicketToRideGame,
        private players: TicketToRidePlayer[],
        claimedRoutes: ClaimedRoute[],
    ) {
        // map border
        dojo.place(`<div class="illustration"></div>`, 'board');
        SIDES.forEach(side => dojo.place(`<div class="side ${side}"></div>`, 'board'));
        CORNERS.forEach(corner => dojo.place(`<div class="corner ${corner}"></div>`, 'board'));

        /*let html = '';

        // points
        players.forEach(player => {
            html += `<div id="player-${player.id}-point-marker" class="point-marker ${this.game.isColorBlindMode() ? 'color-blind' : ''}" data-player-no="${player.playerNo}" style="background: #${player.color};"></div>`;
            this.points.set(Number(player.id), Number(player.score));
        });
        dojo.place(html, 'board');*/

        ROUTES.forEach(route => {
            if (typeof route.spaces === 'number') {
                dojo.place(`<div id="route${route.id}" class="route">${CITIES[route.from]} to ${CITIES[route.to]}, ${route.spaces} ${COLORS[route.color]}</div>`, 'board');
                document.getElementById(`route${route.id}`).addEventListener('click', () => this.game.claimRoute(route.id));   
            } else {
                route.spaces.forEach((space, spaceIndex) => {
                    dojo.place(`<div id="route${route.id}-space${spaceIndex}" class="route space" 
                        style="transform: translate(${space.x*0.986 + 10}px, ${space.y*0.986 + 10}px) rotate(${space.angle}deg)"
                        title="${CITIES[route.from]} to ${CITIES[route.to]}, ${(route.spaces as any).length} ${COLORS[route.color]}"
                    ></div>`, 'board');
                    document.getElementById(`route${route.id}-space${spaceIndex}`).addEventListener('click', () => this.game.claimRoute(route.id));   
                });
            }
        });

        //this.movePoints();
        this.setClaimedRoutes(claimedRoutes);
    }

    /*public setPoints(playerId: number, points: number) {
        this.points.set(playerId, points);
        this.movePoints();
    }*/
    
    public setSelectableRoutes(selectable: boolean, possibleRoutes: Route[]) {
        if (selectable) {
            possibleRoutes.forEach(route => document.getElementById(`route${route.id}`)?.classList.add('selectable'));
        } else {            
            dojo.query('.route').removeClass('selectable');
        }
    }

    /*private getPointsCoordinates(points: number) {
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
    }*/

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