const SIDES = ['left', 'right', 'top', 'bottom'];
const CORNERS = ['bottom-left', 'bottom-right', 'top-left', 'top-right'];

const MAP_WIDTH = 1744;
const MAP_HEIGHT = 1125;
const DECK_WIDTH = 250;
const PLAYER_WIDTH = 305;
const PLAYER_HEIGHT = 257; // avg height (4 destination cards)

const BOTTOM_RATIO = (MAP_WIDTH + DECK_WIDTH) / (MAP_HEIGHT + PLAYER_HEIGHT);
const LEFT_RATIO = (PLAYER_WIDTH + MAP_WIDTH + DECK_WIDTH) / (MAP_HEIGHT);

class TtrMap {
    //private points = new Map<number, number>();
    private scale: number;
    private resizedDiv: HTMLDivElement;
    private mapZoomDiv: HTMLDivElement;
    private mapDiv: HTMLDivElement;
    private pos = { dragging: false, top: 0, left: 0, x: 0, y: 0 };
    private zoomed = false;

    constructor(
        private game: TicketToRideGame,
        private players: TicketToRidePlayer[],
        claimedRoutes: ClaimedRoute[],
    ) {
        // map border
        dojo.place(`<div class="illustration"></div>`, 'map-and-borders');
        SIDES.forEach(side => dojo.place(`<div class="side ${side}"></div>`, 'map-and-borders'));
        CORNERS.forEach(corner => dojo.place(`<div class="corner ${corner}"></div>`, 'map-and-borders'));

        /*let html = '';

        // points
        players.forEach(player => {
            html += `<div id="player-${player.id}-point-marker" class="point-marker ${this.game.isColorBlindMode() ? 'color-blind' : ''}" data-player-no="${player.playerNo}" style="background: #${player.color};"></div>`;
            this.points.set(Number(player.id), Number(player.score));
        });
        dojo.place(html, 'map');*/
        

        CITIES.forEach(city => 
            dojo.place(`<div id="city${city.id}" class="city" 
                style="transform: translate(${city.x*FACTOR}px, ${city.y*FACTOR}px)"
                title="${CITIES_NAMES[city.id]}"
            ></div>`, 'map')
        );


        ROUTES.forEach(route => 
            route.spaces.forEach((space, spaceIndex) => {
                dojo.place(`<div id="route${route.id}-space${spaceIndex}" class="route-space" 
                    style="transform: translate(${space.x*FACTOR}px, ${space.y*FACTOR}px) rotate(${space.angle}deg)"
                    title="${CITIES_NAMES[route.from]} to ${CITIES_NAMES[route.to]}, ${(route.spaces as any).length} ${_(COLORS[route.color])}"
                    data-route="${route.id}" data-color="${route.color}"
                ></div>`, 'map');
                const spaceDiv = document.getElementById(`route${route.id}-space${spaceIndex}`);
                this.setSpaceEvents(spaceDiv, route);
            }) // TODO translate title
        );

        /*console.log(ROUTES.map(route => `    new Route(${route.id}, ${route.from}, ${route.to}, [
${route.spaces.map(space => `        new RouteSpace(${(space.x*0.986 + 10).toFixed(2)}, ${(space.y*0.986 + 10).toFixed(2)}, ${space.angle}),`).join('\n')}
    ], ${COLORS[route.color]}),`).join('\n'));*/

        //this.movePoints();
        this.setClaimedRoutes(claimedRoutes);

        this.resizedDiv = document.getElementById('resized') as HTMLDivElement;
        this.mapZoomDiv = document.getElementById('map-zoom') as HTMLDivElement;
        this.mapDiv = document.getElementById('map') as HTMLDivElement;
        // Attach the handler
        this.mapDiv.addEventListener('mousedown', e => this.mouseDownHandler(e));
        document.addEventListener('mousemove', e => this.mouseMoveHandler(e));
        document.addEventListener('mouseup', e => this.mouseUpHandler());
        document.getElementById('zoom-button').addEventListener('click', () => this.toggleZoom());
        
        /*this.mapDiv.addEventListener('dragenter', e => this.mapDiv.classList.add('drag-over'));
        this.mapDiv.addEventListener('dragleave', e => this.mapDiv.classList.remove('drag-over'));
        this.mapDiv.addEventListener('drop', e => this.mapDiv.classList.remove('drag-over'));*/
    }
    
    private enterover(e: DragEvent, route: Route) {
        const cardsColor = Number(this.mapDiv.dataset.dragColor);
        const canClaimRoute = this.game.canClaimRoute(route, cardsColor);
        this.setHoveredRoute(route, canClaimRoute);
        if (canClaimRoute) {
            e.preventDefault();
        }
    };

    private setSpaceEvents(spaceDiv: HTMLElement, route: Route) {
        spaceDiv.addEventListener('dragenter', e => this.enterover(e, route));
        spaceDiv.addEventListener('dragover', e => this.enterover(e, route));
        spaceDiv.addEventListener('dragleave', (e) => {
            this.setHoveredRoute(null);
        });
        spaceDiv.addEventListener('drop', () => {
            if (document.getElementById('map').dataset.dragColor == '') {
                return;
            }

            this.setHoveredRoute(null);
            const cardsColor = Number(this.mapDiv.dataset.dragColor);
            document.getElementById('map').dataset.dragColor = '';
            this.game.claimRoute(route.id, cardsColor);
        });
        spaceDiv.addEventListener('click', () => {
            this.game.clickedRoute(route);
        });
    }

    /*public setPoints(playerId: number, points: number) {
        this.points.set(playerId, points);
        this.movePoints();
    }*/
    
    public setSelectableRoutes(selectable: boolean, possibleRoutes: Route[]) {
        if (selectable) {
            possibleRoutes.forEach(route => ROUTES.find(r => r.id == route.id).spaces.forEach((_, index) => 
                document.getElementById(`route${route.id}-space${index}`)?.classList.add('selectable'))
            );
        } else {            
            dojo.query('.route-space').removeClass('selectable');
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
        /*const claimedRoute = {
            playerId: 2343492
        };
        ROUTES.forEach(route => {*/
        claimedRoutes.forEach(claimedRoute => {
            const route = ROUTES.find(r => r.id == claimedRoute.routeId);
            const color = this.players.find(player => Number(player.id) == claimedRoute.playerId).color;
            this.setWagons(route, color);
        });
    }

    private setWagons(route: Route, color: string, phantom: boolean = false) {
        route.spaces.forEach((space, spaceIndex) => {
            const id = `wagon-route${route.id}-space${spaceIndex}${phantom ? '-phantom' : ''}`;
            if (document.getElementById(id)) {
                return;
            }

            if (!phantom) {
                const spaceDiv = document.getElementById(`route${route.id}-space${spaceIndex}`);
                spaceDiv.parentElement.removeChild(spaceDiv);
            }

            let angle = -space.angle;
            while (angle < 0) {
                angle += 180;
            }
            while (angle >= 180) {
                angle -= 180;
            }
            dojo.place(`<div id="${id}" class="wagon angle${Math.round(angle * 36 / 180)} ${phantom ? 'phantom' : ''}" data-player-color="${color}" style="transform: translate(${space.x * FACTOR}px, ${space.y * FACTOR}px)"></div>`, 'map');
        });
    }

    public setAutoZoom() {

        if (!this.mapDiv.clientWidth) {
            setTimeout(() => this.setAutoZoom(), 200);
            return;
        }

        const screenRatio = document.getElementById('game_play_area').clientWidth / (window.innerHeight - 80);
        const leftDistance = Math.abs(LEFT_RATIO - screenRatio);
        const bottomDistance = Math.abs(BOTTOM_RATIO - screenRatio);
        const left = leftDistance < bottomDistance;
        this.game.setPlayerTablePosition(left);

        const gameWidth = (left ? PLAYER_WIDTH : 0) + MAP_WIDTH + DECK_WIDTH;
        const gameHeight = MAP_HEIGHT + (left ? 0 : PLAYER_HEIGHT);

        const horizontalScale = document.getElementById('game_play_area').clientWidth / gameWidth;
        const verticalScale = (window.innerHeight - 80) / gameHeight;
        this.scale = Math.min(1, horizontalScale, verticalScale);

        this.resizedDiv.style.transform = this.scale === 1 ? '' : `scale(${this.scale})`;
        this.resizedDiv.style.marginBottom = `-${(1 - this.scale) * gameHeight}px`;
    }
    
    public getZoom(): number {
        return this.scale;
    }

    private mouseDownHandler(e: MouseEvent) {
        if (!this.zoomed) {
            return;
        }
        //this.mapDiv.style.cursor = 'grabbing';

        this.pos = {
            dragging: true,
            left: this.mapDiv.scrollLeft,
            top: this.mapDiv.scrollTop,
            // Get the current mouse position
            x: e.clientX,
            y: e.clientY,
        };
    }

    private mouseMoveHandler(e: MouseEvent) {
        if (!this.zoomed || !this.pos.dragging) {
            return;
        }

        // How far the mouse has been moved
        const dx = e.clientX - this.pos.x;
        const dy = e.clientY - this.pos.y;

        const factor = 0.1;

        // Scroll the element
        this.mapZoomDiv.scrollTop -= dy*factor;
        this.mapZoomDiv.scrollLeft -= dx*factor;
    }

    private mouseUpHandler() {
        if (!this.zoomed || !this.pos.dragging) {
            return;
        }
        
        //this.mapDiv.style.cursor = 'grab';
        this.pos.dragging = false;
    }

    private toggleZoom() {      
        this.zoomed = !this.zoomed;
        this.mapDiv.style.transform = this.zoomed ? `scale(1.8)` : '';
        dojo.toggleClass('zoom-button', 'zoomed', this.zoomed);
        dojo.toggleClass('map-zoom', 'scrollable', this.zoomed);

        if (!this.zoomed) {
            this.mapZoomDiv.scrollTop = 0;
            this.mapZoomDiv.scrollLeft = 0;
        }
    }

    public setActiveDestination(destination: Destination, previousDestination: Destination = null) {
        if (previousDestination) {
            if (previousDestination.id === destination.id) {
                return;
            }

            [previousDestination.from, previousDestination.to].forEach(city => 
                document.getElementById(`city${city}`).dataset.selectedDestination = 'false'
            );
        }

        if (destination) {
            [destination.from, destination.to].forEach(city => 
                document.getElementById(`city${city}`).dataset.selectedDestination = 'true'
            );
        }
    }

    public setHoveredRoute(route: Route | null, valid: boolean | null = null) {
        if (route) {

            [route.from, route.to].forEach(city => {
                const cityDiv = document.getElementById(`city${city}`);
                cityDiv.dataset.hovered = 'true';
                cityDiv.dataset.valid = valid.toString();
            });

            if (valid) {
                this.setWagons(route, this.game.getPlayerColor(), true);
            }

        } else {

            ROUTES.forEach(r => [r.from, r.to].forEach(city => 
                document.getElementById(`city${city}`).dataset.hovered = 'false'
            ));

            // remove phantom wagons
            this.mapDiv.querySelectorAll('.wagon.phantom').forEach(spaceDiv => spaceDiv.parentElement.removeChild(spaceDiv));
        }
    }

    public setSelectableDestination(destination: Destination, visible: boolean): void {
        [destination.from, destination.to].forEach(city => {
            document.getElementById(`city${city}`).dataset.selectable = ''+visible;
        });
    }
    public setSelectedDestination(destination: Destination, visible: boolean): void {
        [destination.from, destination.to].forEach(city => {
            document.getElementById(`city${city}`).dataset.selected = ''+visible;
        });
    }
    public setDestinationsToConnect(destinations: Destination[]): void {
        this.mapDiv.querySelectorAll(`.city[data-to-connect]`).forEach((city: HTMLElement) => city.dataset.toConnect = 'false');
        const cities = [];
        destinations.forEach(destination => cities.push(destination.from, destination.to));
        cities.forEach(city => {
            document.getElementById(`city${city}`).dataset.toConnect = 'true';
        });
    }

    public setHighligthedDestination(destination: Destination | null): void {
        const visible = Boolean(destination).toString();
        const shadow = document.getElementById('map-destination-highlight-shadow');
        shadow.dataset.visible = visible;

        let cities;
        if (destination) {
            shadow.dataset.from = ''+destination.from;
            shadow.dataset.to = ''+destination.to;
            cities = [destination.from, destination.to];
        } else {
            cities = [shadow.dataset.from, shadow.dataset.to];
        }
        cities.forEach(city => {
            document.getElementById(`city${city}`).dataset.highlight = visible;
        });
    }
}