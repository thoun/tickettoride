const DRAG_AUTO_ZOOM_DELAY = 2000;

const SIDES = ['left', 'right', 'top', 'bottom'];
const CORNERS = ['bottom-left', 'bottom-right', 'top-left', 'top-right'];

const MAP_WIDTH = 1744;
const MAP_HEIGHT = 1125;
const DECK_WIDTH = 250;
const PLAYER_WIDTH = 305;
const PLAYER_HEIGHT = 257; // avg height (4 destination cards)

const BOTTOM_RATIO = (MAP_WIDTH + DECK_WIDTH) / (MAP_HEIGHT + PLAYER_HEIGHT);
const LEFT_RATIO = (PLAYER_WIDTH + MAP_WIDTH + DECK_WIDTH) / (MAP_HEIGHT);

/** 
 * Manager for in-map zoom.
 */ 
class InMapZoomManager {
    private mapZoomDiv: HTMLDivElement;
    private mapDiv: HTMLDivElement;
    private pos = { dragging: false, top: 0, left: 0, x: 0, y: 0 }; // for map drag (if zoomed)
    private zoomed = false; // indicates if in-map zoom is active

    private hoveredRoute: Route;
    private autoZoomTimeout: number;
    private dragClientX: number;
    private dragClientY: number;

    constructor(map: TicketToRideMap) {        
        this.mapZoomDiv = document.getElementById('map-zoom') as HTMLDivElement;
        this.mapDiv = document.getElementById('map') as HTMLDivElement;

        // Attach the handler
        this.mapDiv.addEventListener('mousedown', e => this.mouseDownHandler(e));
        document.addEventListener('mousemove', e => this.mouseMoveHandler(e));
        document.addEventListener('mouseup', e => this.mouseUpHandler());
        document.getElementById('zoom-button').addEventListener('click', () => this.toggleZoom());
        
        this.mapDiv.addEventListener('dragover', e => {
            if (e.offsetX !== this.dragClientX || e.offsetY !== this.dragClientY) {
                this.dragClientX = e.offsetX;
                this.dragClientY = e.offsetY;
                this.dragOverMouseMoved(e.offsetX, e.offsetY);
            }
        });
        this.mapDiv.addEventListener('dragleave', e => {
            clearTimeout(this.autoZoomTimeout);
            this.autoZoomTimeout = null;
        });
        this.mapDiv.addEventListener('drop', e => {
            clearTimeout(this.autoZoomTimeout);
            this.autoZoomTimeout = null;
        });
    }

    private dragOverMouseMoved(clientX: number, clientY: number) {
        if (this.autoZoomTimeout) {
            clearTimeout(this.autoZoomTimeout);
        }
        this.autoZoomTimeout = setTimeout(() => {
            if (!this.hoveredRoute) { // do not automatically change the zoom when player is dragging over a route!
                this.toggleZoom(clientX / this.mapDiv.clientWidth, clientY / this.mapDiv.clientHeight);
            }
            this.autoZoomTimeout = null;
        }, DRAG_AUTO_ZOOM_DELAY);
    }

    /** 
     * Handle click on zoom button. Toggle between full map and in-map zoom.
     */ 
    private toggleZoom(scrollRatioX: number = null, scrollRatioY: number = null) {
        this.zoomed = !this.zoomed;
        this.mapDiv.style.transform = this.zoomed ? `scale(1.8)` : '';
        dojo.toggleClass('zoom-button', 'zoomed', this.zoomed);
        dojo.toggleClass('map-zoom', 'scrollable', this.zoomed);
        
        this.mapDiv.style.cursor = this.zoomed ? 'grab' : 'default';

        if (this.zoomed) {
            if (scrollRatioX && scrollRatioY) {
                this.mapZoomDiv.scrollLeft = (this.mapZoomDiv.scrollWidth - this.mapZoomDiv.clientWidth) * scrollRatioX;
                this.mapZoomDiv.scrollTop = (this.mapZoomDiv.scrollHeight - this.mapZoomDiv.clientHeight ) * scrollRatioY;
            }
        } else {
            this.mapZoomDiv.scrollTop = 0;
            this.mapZoomDiv.scrollLeft = 0;
        }
    }

    /** 
     * Handle mouse down, to grap map and scroll in it (imitate mobile touch scroll).
     */ 
    private mouseDownHandler(e: MouseEvent) {
        if (!this.zoomed) {
            return;
        }
        this.mapDiv.style.cursor = 'grabbing';

        this.pos = {
            dragging: true,
            left: this.mapDiv.scrollLeft,
            top: this.mapDiv.scrollTop,
            // Get the current mouse position
            x: e.clientX,
            y: e.clientY,
        };
    }

    /** 
     * Handle mouse move, to grap map and scroll in it (imitate mobile touch scroll).
     */ 
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

    /** 
     * Handle mouse up, to grap map and scroll in it (imitate mobile touch scroll).
     */ 
    private mouseUpHandler() {
        if (!this.zoomed || !this.pos.dragging) {
            return;
        }
        
        this.mapDiv.style.cursor = 'grab';
        this.pos.dragging = false;
    }

    setHoveredRoute(route: Route | null) {
        this.hoveredRoute = route;
    }
}

/** 
 * Map creation and in-map zoom handler.
 */ 
class TtrMap {
    private scale: number;
    private inMapZoomManager: InMapZoomManager;
    private resizedDiv: HTMLDivElement;
    private mapDiv: HTMLDivElement;
    
    private dragOverlay: HTMLDivElement = null;
    private crosshairTarget: HTMLDivElement = null;
    private crosshairHalfSize: number = 0;
    private crosshairShift: number = 0;

    private claimedRoutesIds = [];

    /** 
     * Place map corner illustration and borders, cities, routes, and bind events.
     */ 
    constructor(
        private game: TicketToRideGame,
        private map: TicketToRideMap,
        private players: TicketToRidePlayer[],
        claimedRoutes: ClaimedRoute[],
        illustration: number,
    ) {
        // map border
        dojo.place(`
            <div class="illustration" data-illustration="${illustration}"></div>
            <div id="cities"></div>
            <div id="route-spaces"></div>
            <div id="train-cars"></div>
        `, 'map', 'first');
        SIDES.forEach(side => dojo.place(`<div class="side ${side}"></div>`, 'map-and-borders'));
        CORNERS.forEach(corner => dojo.place(`<div class="corner ${corner}"></div>`, 'map-and-borders'));
        map.bigCities.forEach(bigCity => dojo.place(`<div class="big-city" style="left: ${bigCity.x}px; top: ${bigCity.y}px; width: ${bigCity.width}px;"></div>`, 'cities')); 

        Object.entries(map.cities).forEach(entry => {
            const id = Number(entry[0]);
            const city = entry[1];
            dojo.place(`<div id="city${id}" class="city" 
                style="transform: translate(${city.x}px, ${city.y}px)"
                title="${game.getCityName(id)}"
            ></div>`, 'cities')
        });

        this.createRouteSpaces('route-spaces');

        this.setClaimedRoutes(claimedRoutes, null);

        this.resizedDiv = document.getElementById('resized') as HTMLDivElement;
        this.mapDiv = document.getElementById('map') as HTMLDivElement;

        this.inMapZoomManager = new InMapZoomManager(map);

        this.game.setTooltipToClass(`train-car-deck-hidden-pile-tooltip`, `<strong>${_('Train cars deck')}</strong><br><br>
        ${_('Click here to pick one or two hidden train car cards')}`);

        this.game.setTooltip(`destination-deck-hidden-pile`, `<strong>${_('Destinations deck')}</strong><br><br>
        ${_('Click here to take three new destination cards (keep at least one)')}`);
    }

    private createRouteSpaces(destination: 'route-spaces' | 'map-drag-overlay', shiftX: number = 0, shiftY: number = 0) {
        Object.values(this.map.routes).filter(route => !this.claimedRoutesIds.includes(route.id)).forEach(route => 
            route.spaces.forEach((space, spaceIndex) => {
                let title = `${dojo.string.substitute(_('${from} to ${to}'), {
                    from: this.game.getCityName(route.from),
                    to: this.game.getCityName(route.to),
                })}, ${(route.spaces as any).length} ${getColor(route.color, 'route')}`;
                if (route.tunnel) {
                    title += ` (${/* TODO MAPS _*/("Tunnel")})`;
                }
                if (route.locomotives) {
                    title += ` (${/* TODO MAPS _*/("${number} locomotive(s) required").replace('${number}', `${route.locomotives}`)})`;
                }  
                
                dojo.place(`<div id="${destination}-route${route.id}-space${spaceIndex}" class="route-space ${route.tunnel ? 'tunnel' : ''}" 
                    style="transform: translate(${space.x + shiftX}px, ${space.y + shiftY}px) rotate(${space.angle}deg);"
                    title="${title}"
                    data-route="${route.id}" data-color="${route.color}"
                ></div>`, destination);
                const spaceDiv = document.getElementById(`${destination}-route${route.id}-space${spaceIndex}`);
                if (destination == 'route-spaces') {
                    this.setSpaceClickEvents(spaceDiv, route);
                } else {
                    this.setSpaceDragEvents(spaceDiv, route);
                }
            })
        );
    }
    
    /** 
     * Handle dragging train car cards over a route.
     */ 
    private routeDragOver(e: DragEvent, route: Route) {
        const cardsColor = Number(this.mapDiv.dataset.dragColor);

        let overRoute = route;
        if (cardsColor > 0 && route.color > 0 && cardsColor != route.color) {
            const otherRoute = Object.values(this.map.routes).find(r => route.from == r.from && route.to == r.to && route.id != r.id);
            if (otherRoute && otherRoute.color == cardsColor) {
                overRoute = otherRoute;
            }
        }
        let canClaimRoute = this.game.canClaimRoute(overRoute, cardsColor);

        this.setHoveredRoute(overRoute, canClaimRoute);
        if (canClaimRoute) {
            e.preventDefault();
        }
    };
    
    /** 
     * Handle dropping train car cards over a route.
     */ 
    private routeDragDrop(e: DragEvent, route: Route) {
        e.preventDefault();

        const mapDiv = document.getElementById('map');
        if (mapDiv.dataset.dragColor == '') {
            return;
        }

        this.setHoveredRoute(null);
        const cardsColor = Number(this.mapDiv.dataset.dragColor);
        mapDiv.dataset.dragColor = '';
        
        let overRoute = route;
        if (cardsColor > 0 && route.color > 0 && cardsColor != route.color) {
            const otherRoute = Object.values(this.map.routes).find(r => route.from == r.from && route.to == r.to && route.id != r.id);
            if (otherRoute && otherRoute.color == cardsColor) {
                overRoute = otherRoute;
            }
        }
        
        this.game.askRouteClaimConfirmation(overRoute, cardsColor);
    };

    /** 
     * Bind click events to route space.
     */ 
    private setSpaceClickEvents(spaceDiv: HTMLElement, route: Route) {
        spaceDiv.addEventListener('dragenter', e => this.routeDragOver(e, route));
        spaceDiv.addEventListener('dragover', e => this.routeDragOver(e, route));
        spaceDiv.addEventListener('dragleave', e => this.setHoveredRoute(null));
        spaceDiv.addEventListener('drop', e => this.routeDragDrop(e, route));
        spaceDiv.addEventListener('click', () => this.game.clickedRoute(route));
    }

    /** 
     * Bind drag events to route space.
     */ 
    private setSpaceDragEvents(spaceDiv: HTMLElement, route: Route) {
        spaceDiv.addEventListener('dragenter', e => this.routeDragOver(e, route));
        spaceDiv.addEventListener('dragover', e => this.routeDragOver(e, route));
        spaceDiv.addEventListener('dragleave', e => this.setHoveredRoute(null));
        spaceDiv.addEventListener('drop', e => this.routeDragDrop(e, route));
    }
    
    /** 
     * Highlight selectable route spaces.
     */ 
    public setSelectableRoutes(selectable: boolean, possibleRoutes: Route[]) {
        dojo.query('.route-space').removeClass('selectable');

        if (selectable) {
            possibleRoutes.forEach(route => this.map.routes[route.id].spaces.forEach((_, index) => 
                document.getElementById(`route-spaces-route${route.id}-space${index}`)?.classList.add('selectable'))
            );
        }
    }

    /** 
     * Place train cars on claimed routes.
     * fromPlayerId is for animation (null for no animation)
     */ 
    public setClaimedRoutes(claimedRoutes: ClaimedRoute[], fromPlayerId: number) {
        claimedRoutes.forEach(claimedRoute => {
            this.claimedRoutesIds.push(claimedRoute.routeId);
            const route = this.map.routes[claimedRoute.routeId];
            const player = this.players.find(player => Number(player.id) == claimedRoute.playerId);
            this.setWagons(route, player, fromPlayerId, false);

            if (this.game.isDoubleRouteForbidden()) {
                const otherRoute = Object.values(this.map.routes).find(r => route.from == r.from && route.to == r.to && route.id != r.id);
                if (otherRoute) {
                    this.claimedRoutesIds.push(otherRoute.id);
                    otherRoute.spaces.forEach((space, spaceIndex) => {
                        const spaceDiv = document.getElementById(`route-spaces-route${otherRoute.id}-space${spaceIndex}`);
                        if (spaceDiv) {
                            spaceDiv.classList.add('forbidden');
                            this.game.setTooltip(spaceDiv.id, `<strong><span style="color: darkred">${_('Important Note:')}</span> 
                            ${_('In 2 or 3 player games, only one of the Double-Routes can be used.')}</strong>`);
                        }
                    });
                }
            }
        });
    }

    private animateWagonFromCounter(playerId: number, wagonId: string, toX: number, toY: number) {
        const wagon = document.getElementById(wagonId);
        const wagonBR = wagon.getBoundingClientRect();

        const fromBR = document.getElementById(`train-car-counter-${playerId}-wrapper`).getBoundingClientRect();
            
        const zoom = this.game.getZoom();
        const fromX = (fromBR.x - wagonBR.x) / zoom;
        const fromY = (fromBR.y - wagonBR.y) / zoom;

        wagon.style.transform = `translate(${fromX + toX}px, ${fromY + toY}px)`;
        setTimeout(() => {
            wagon.style.transition = 'transform 0.5s';
            wagon.style.transform = `translate(${toX}px, ${toY}px`;
        }, 0);
    }

    /** 
     * Place train car on a route space.
     * fromPlayerId is for animation (null for no animation)
     * Phantom is for dragging over a route : wagons are showns translucent.
     */ 
    private setWagon(route: Route, space: RouteSpace, spaceIndex: number, player: TicketToRidePlayer, fromPlayerId: number, phantom: boolean, isLowestFromDoubleHorizontalRoute: boolean) {
        const id = `wagon-route${route.id}-space${spaceIndex}${phantom ? '-phantom' : ''}`;
        if (document.getElementById(id)) {
            return;
        }

        let angle = -space.angle;
        while (angle < 0) {
            angle += 180;
        }
        while (angle >= 180) {
            angle -= 180;
        }
        let x = space.x;
        let y = space.y;
        const EASE_WEIGHT = 0.75;
        const angleOnOne = (Math.acos(-2 * angle / 180 + 1) / Math.PI) * EASE_WEIGHT + (angle / 180 * (1 - EASE_WEIGHT));
        const angleClassNumber = Math.round(angleOnOne * 36);

        const alreadyPlacedWagons = Array.from(document.querySelectorAll('.wagon')) as HTMLDivElement[];
        const xy = x + y;

        if (isLowestFromDoubleHorizontalRoute) { // we shift a little the train car to let the other route visible
            x += 10 * Math.abs(Math.sin(angle * Math.PI / 180));
            y += 10 * Math.abs(Math.cos(angle * Math.PI / 180));
        }

        const wagonHtml = `<div id="${id}" class="wagon angle${angleClassNumber} ${phantom ? 'phantom' : ''} ${space.top ? 'top' : ''}" data-player-color="${player.color}" data-color-blind-player-no="${player.playerNo}" data-xy="${xy}" style="transform: translate(${x}px, ${y}px)"></div>`;
        // we consider a wagon must be more visible than another if its X + Y is > as the other
        if (!alreadyPlacedWagons.length) {
            dojo.place(wagonHtml, 'train-cars');
        } else {
            let placed = false;
            for (let i = 0; i < alreadyPlacedWagons.length; i++) {
                if (Number(alreadyPlacedWagons[i].dataset.xy) > xy) {
                    dojo.place(wagonHtml, alreadyPlacedWagons[i].id, 'before');
                    placed = true;
                    break;
                }
            }

            if (!placed) {
                dojo.place(wagonHtml, 'train-cars');
            }
        }
        
        if (fromPlayerId) {
            this.animateWagonFromCounter(fromPlayerId, id, x, y);
        }
    }

    /** 
     * Place train cars on a route.
     * fromPlayerId is for animation (null for no animation)
     * Phantom is for dragging over a route : wagons are showns translucent.
     */ 
    private setWagons(route: Route, player: TicketToRidePlayer, fromPlayerId: number, phantom: boolean) {
        if (!phantom) {
            route.spaces.forEach((space, spaceIndex) => {
                const spaceDiv = document.getElementById(`route-spaces-route${route.id}-space${spaceIndex}`);
                spaceDiv?.parentElement.removeChild(spaceDiv);
            });
        }

        const isLowestFromDoubleHorizontalRoute = this.isLowestFromDoubleHorizontalRoute(route);

        if (fromPlayerId) {
            route.spaces.forEach((space, spaceIndex) => {
                setTimeout(() => {
                    this.setWagon(route, space, spaceIndex, player, fromPlayerId, phantom, isLowestFromDoubleHorizontalRoute);
                    playSound(`ttr-placed-train-car`);
                }, 200 * spaceIndex);
            });
            (this.game as any).disableNextMoveSound();
        } else {
            route.spaces.forEach((space, spaceIndex) => this.setWagon(route, space, spaceIndex, player, fromPlayerId, phantom, isLowestFromDoubleHorizontalRoute));
        }
    }

    /** 
     * Check if the route is mostly horizontal, and the lowest from a double route
     */ 
    private isLowestFromDoubleHorizontalRoute(route: Route) {
        const otherRoute = Object.values(this.map.routes).find(r => route.from == r.from && route.to == r.to && route.id != r.id);
        if (!otherRoute) { // not a double route
            return false;
        }

        const routeAvgX = route.spaces.map(space => space.x).reduce((a, b) => a + b, 0);
        const routeAvgY = route.spaces.map(space => space.y).reduce((a, b) => a + b, 0);
        const otherRouteAvgX = otherRoute.spaces.map(space => space.x).reduce((a, b) => a + b, 0);
        const otherRouteAvgY = otherRoute.spaces.map(space => space.y).reduce((a, b) => a + b, 0);

        if (Math.abs(routeAvgX - otherRouteAvgX) > Math.abs(routeAvgY - otherRouteAvgY)) { // not mostly horizontal
            return false;
        }

        if (routeAvgY <= otherRouteAvgY) { // not the lowest one
            return false;
        }

        return true;
    }

    /** 
     * Set map size, depending on available screen size.
     * Player table will be placed left or bottom, depending on window ratio.
     */ 
    public setAutoZoom() {

        if (!this.mapDiv.clientWidth) {
            setTimeout(() => this.setAutoZoom(), 200);
            return;
        }

        const screenRatio = document.getElementById('game_play_area').clientWidth / (window.innerHeight - 80);
        const leftDistance = Math.abs(LEFT_RATIO - screenRatio);
        const bottomDistance = Math.abs(BOTTOM_RATIO - screenRatio);
        const left = leftDistance < bottomDistance || (this.game as any).isSpectator;
        this.game.setPlayerTablePosition(left);

        const gameWidth = (left ? PLAYER_WIDTH : 0) + MAP_WIDTH + DECK_WIDTH;
        const gameHeight = MAP_HEIGHT + (left ? 0 : PLAYER_HEIGHT * 0.75);

        const horizontalScale = document.getElementById('game_play_area').clientWidth / gameWidth;
        const verticalScale = (window.innerHeight - 80) / gameHeight;
        this.scale = Math.min(1, horizontalScale, verticalScale);

        this.resizedDiv.style.transform = this.scale === 1 ? '' : `scale(${this.scale})`;
        this.resizedDiv.style.marginBottom = `-${(1 - this.scale) * gameHeight}px`;

        this.setOutline();
    }
    
    /** 
     * Get current zoom.
     */ 
    public getZoom(): number {
        return this.scale;
    }

    /** 
     * Highlight active destination.
     */ 
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

    /** 
     * Highlight hovered route (when dragging train cars).
     */ 
    public setHoveredRoute(route: Route | null, valid: boolean | null = null, player: TicketToRidePlayer | null = null) {
        this.inMapZoomManager.setHoveredRoute(route);

        if (route) {

            [route.from, route.to].forEach(city => {
                const cityDiv = document.getElementById(`city${city}`);
                cityDiv.dataset.hovered = 'true';
                cityDiv.dataset.valid = valid.toString();
            });

            if (valid) {
                this.setWagons(route, player || this.game.getCurrentPlayer(), null, true);
            }

        } else {

            Object.values(this.map.routes).forEach(r => [r.from, r.to].forEach(city => 
                document.getElementById(`city${city}`).dataset.hovered = 'false'
            ));

            // remove phantom wagons
            this.mapDiv.querySelectorAll('.wagon.phantom').forEach(spaceDiv => spaceDiv.parentElement.removeChild(spaceDiv));
        }
    }

    /** 
     * Highlight cities of selectable destination.
     */ 
    public setSelectableDestination(destination: Destination, visible: boolean): void {
        [destination.from, destination.to].forEach(city => {
            document.getElementById(`city${city}`).dataset.selectable = ''+visible;
        });
    }

    /** 
     * Highlight cities of selected destination.
     */ 
    public setSelectedDestination(destination: Destination, visible: boolean): void {
        [destination.from, destination.to].forEach(city => {
            document.getElementById(`city${city}`).dataset.selected = ''+visible;
        });
    }

    /** 
     * Highlight cities player must connect for its objectives.
     */ 
    public setDestinationsToConnect(destinations: Destination[]): void {
        this.mapDiv.querySelectorAll(`.city[data-to-connect]`).forEach((city: HTMLElement) => city.dataset.toConnect = 'false');
        const cities = [];
        destinations.forEach(destination => cities.push(destination.from, destination.to));
        cities.forEach(city => document.getElementById(`city${city}`).dataset.toConnect = 'true');
    }

    /** 
     * Highlight destination (on destination mouse over).
     */ 
    public setHighligthedDestination(destination: Destination | null): void {
        const visible = Boolean(destination).toString();
        const shadow = document.getElementById('map-destination-highlight-shadow');
        shadow.dataset.visible = visible;

        let cities: (string | number)[];
        if (destination) {
            shadow.dataset.from = ''+destination.from;
            shadow.dataset.to = ''+destination.to;
            cities = [destination.from, destination.to];
        } else {
            cities = [shadow.dataset.from, shadow.dataset.to];
        }
        cities.forEach(city => document.getElementById(`city${city}`).dataset.highlight = visible);
    }

    /** 
     * Create the crosshair target when drag starts over the drag overlay.
     */ 
    private overlayDragEnter(e: DragEvent | MouseEvent) {
        if (!this.crosshairTarget) {
            this.crosshairTarget = document.createElement('div');
            this.crosshairTarget.id = 'map-drag-target';
            this.crosshairTarget.style.left = e.offsetX + 'px';
            this.crosshairTarget.style.top = e.offsetY + 'px';
            this.crosshairTarget.style.width = this.crosshairHalfSize*2 + 'px';
            this.crosshairTarget.style.height = this.crosshairHalfSize*2 + 'px';
            this.crosshairTarget.style.marginLeft = -(this.crosshairHalfSize + this.crosshairShift) + 'px';
            this.crosshairTarget.style.marginTop = -(this.crosshairHalfSize + this.crosshairShift) + 'px';
            this.dragOverlay.appendChild(this.crosshairTarget);
        }
    }

    /** 
     * Move the crosshair target.
     */ 
    private overlayDragMove(e: DragEvent | MouseEvent) {
        if (this.crosshairTarget && (e.target as any).id === this.dragOverlay.id) {
            this.crosshairTarget.style.left = e.offsetX + 'px';
            this.crosshairTarget.style.top = e.offsetY + 'px';
        }
    }

    /** 
     * Create a div overlay when dragging starts. 
     * This allow to create a crosshair target on it, and to make it shifted from mouse position.
     * In touch mode, crosshair must be shifted from finger to see the target. For this, the drag zones on the overlay are shifted in accordance.
     * If there isn't touch support, the crosshair is centered on the mouse.
     */ 
    public addDragOverlay() {
        const id = 'map-drag-overlay';
        this.dragOverlay = document.createElement('div');
        this.dragOverlay.id = id;
        document.getElementById(`map`).appendChild(this.dragOverlay);
        
        this.crosshairHalfSize = CROSSHAIR_SIZE / this.game.getZoom();
        const touchDevice = 'ontouchstart' in window;
        this.crosshairShift = touchDevice ? this.crosshairHalfSize : 0;
        this.createRouteSpaces(id, 100 + this.crosshairShift, 100 + this.crosshairShift);

        this.dragOverlay.addEventListener('dragenter', e => this.overlayDragEnter(e));

        let debounceTimer = null;
        let lastEvent: DragEvent = null;
        this.dragOverlay.addEventListener('dragover', e => {
            lastEvent = e;
            if (!debounceTimer) {
                debounceTimer = setTimeout(() => {
                    this.overlayDragMove(lastEvent);
                    debounceTimer = null;
                }, 50);
            }
        });
    }

    /** 
     * Detroy the div overlay when dragging ends.
     */ 
    public removeDragOverlay() {
        this.crosshairTarget = null;
        document.getElementById(`map`).removeChild(this.dragOverlay);
        this.dragOverlay = null;
    }

    /** 
     * Set outline for train cars on the map, according to preferences.
     */ 
    public setOutline() {
        const preference = Number((this.game as any).prefs[203]?.value);
        const outline = preference === 1 || (preference === 2 && this.mapDiv?.getBoundingClientRect().width < 1000);
        this.mapDiv.dataset.bigShadows = outline.toString();
    }
}