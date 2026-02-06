/**
 * Animation with highlighted wagons.
 */
class WagonsAnimation {
    constructor(game, destinationRoutes) {
        this.game = game;
        this.wagons = [];
        this.zoom = this.game.getZoom();
        this.shadowDiv = document.getElementById('map-destination-highlight-shadow');
        destinationRoutes?.forEach(route => this.wagons.push(...Array.from(document.querySelectorAll(`[id^="wagon-route${route.id}-space"]`))));
    }
    setWagonsVisibility(visible) {
        this.shadowDiv.dataset.visible = visible ? 'true' : 'false';
        this.wagons.forEach(wagon => wagon.classList.toggle('highlight', visible));
    }
}

/**
 * Destination animation : destination slides over the map, wagons used by destination are highlighted, destination is mark "done" or "uncomplete", and card slides back to original place.
 */
class DestinationCompleteAnimation extends WagonsAnimation {
    constructor(game, destination, destinationRoutes, fromId, toId, actions, state, initialSize = 1) {
        super(game, destinationRoutes);
        this.destination = destination;
        this.fromId = fromId;
        this.toId = toId;
        this.actions = actions;
        this.state = state;
        this.initialSize = initialSize;
    }
    animate() {
        return new Promise(resolve => {
            const fromBR = document.getElementById(this.fromId).getBoundingClientRect();
            document.getElementById('map').insertAdjacentHTML('beforeend', `
            <div id="animated-destination-card-${this.destination.id}" class="destination-card" style="${this.getCardPosition(this.destination)}${getBackgroundInlineStyleForDestination(this.game.getMap(), this.destination)}"></div>
            `);
            const noMask = Array.isArray(this.destination.to);
            const card = document.getElementById(`animated-destination-card-${this.destination.id}`);
            if (noMask) {
                card.classList.add('no-mask');
            }
            this.actions.start?.(this.destination);
            const cardBR = card.getBoundingClientRect();
            const x = (fromBR.x - cardBR.x) / this.zoom;
            const y = (fromBR.y - cardBR.y) / this.zoom;
            card.style.transform = `translate(${x}px, ${y}px) scale(${this.initialSize})`;
            this.setWagonsVisibility(true);
            this.game.setSelectedDestination(this.destination, true);
            setTimeout(() => {
                card.classList.add('animated');
                card.style.transform = ``;
                this.markComplete(card, cardBR, resolve, noMask);
            }, 100);
        });
    }
    markComplete(card, cardBR, resolve, noMask) {
        setTimeout(() => {
            if (noMask) {
                card.classList.add('no-mask');
            }
            card.classList.add(this.state);
            this.actions.change?.(this.destination);
            setTimeout(() => {
                const toBR = document.getElementById(this.toId).getBoundingClientRect();
                const x = (toBR.x - cardBR.x) / this.zoom;
                const y = (toBR.y - cardBR.y) / this.zoom;
                card.style.transform = `translate(${x}px, ${y}px) scale(${this.initialSize})`;
                setTimeout(() => this.endAnimation(resolve, card), 500);
            }, 500);
        }, 750);
    }
    endAnimation(resolve, card) {
        this.setWagonsVisibility(false);
        this.game.setSelectedDestination(this.destination, false);
        resolve(this);
        this.game.endAnimation(this);
        this.actions.end?.(this.destination);
        card.parentElement.removeChild(card);
    }
    getCardPosition(destination) {
        if (Array.isArray(destination.to)) {
            const from = this.game.getMap().cities[destination.from];
            return `left: ${from.x - CARD_WIDTH / 2}px; top: ${from.y - CARD_HEIGHT / 2}px;`;
        }
        else {
            const positions = [destination.from, destination.to].map(cityId => this.game.getMap().cities[cityId]);
            let x = (positions[0].x + positions[1].x) / 2;
            let y = (positions[0].y + positions[1].y) / 2;
            return `left: ${x - CARD_WIDTH / 2}px; top: ${y - CARD_HEIGHT / 2}px;`;
        }
    }
}

const IMAGE_ITEMS_PER_ROW = 10;
/**
 * Player's destination cards.
 */
class PlayerDestinations {
    constructor(game, player, destinations, completedDestinations) {
        this.game = game;
        /** Highlighted destination on the map */
        this.selectedDestination = null;
        /** Destinations in "to do" column */
        this.destinationsTodo = [];
        /** Destinations in "done" column */
        this.destinationsDone = [];
        this.playerId = Number(player.id);
        let html = `
        <div id="player-table-${player.id}-destinations-todo" class="player-table-destinations-column todo"></div>
        <div id="player-table-${player.id}-destinations-done" class="player-table-destinations-column done">
            <div id="stations-information-button">
                <i class="fa fa-info-circle" aria-hidden="true"></i>
            </div>
        </div>
        `;
        document.getElementById(`player-table-${player.id}-destinations`).insertAdjacentHTML('beforeend', html);
        this.addDestinations(destinations);
        destinations.filter(destination => completedDestinations.some(d => d.id == destination.id)).forEach(destination => this.markDestinationComplete(destination));
        // highlight the first "to do" destination
        this.activateNextDestination(this.destinationsTodo);
        if (this.game.getMap().stations !== null) {
            if (player.remainingStations < this.game.getMap().stations) {
                document.getElementById('stations-information-button').classList.add('visible');
            }
            document.getElementById(`stations-information-button`).addEventListener('click', () => {
                const stationsInformationsDialog = new ebg.popindialog();
                stationsInformationsDialog.create('stationsInformationsDialog');
                stationsInformationsDialog.setTitle(_("Destinations with stations"));
                stationsInformationsDialog.setMaxWidth(500);
                // Show the dialog
                stationsInformationsDialog.setContent(`
                    ${_("When the game ends, the stations will automatically be used on the tickets scoring the most points.")}<br>
                    ${_("This means that destinations using stations will not be marked as complete until the end of the game.")}<br><br>
                    <button id="stationsInformationsDialog-close-button" class="bgabutton bgabutton_blue">${_("Close")}</button>
                `);
                stationsInformationsDialog.show();
                document.getElementById(`stationsInformationsDialog-close-button`).addEventListener('click', event => {
                    event.preventDefault();
                    stationsInformationsDialog.destroy();
                });
            });
        }
    }
    /**
     * Add destinations to player's hand.
     */
    addDestinations(destinations, originStock) {
        destinations.forEach(destination => {
            let html = `
            <div id="destination-card-${destination.id}" class="destination-card" style="${getBackgroundInlineStyleForDestination(this.game.getMap(), destination)}"></div>
            `;
            document.getElementById(`player-table-${this.playerId}-destinations-todo`).insertAdjacentHTML('beforeend', html);
            const card = document.getElementById(`destination-card-${destination.id}`);
            setupDestinationCardDiv(this.game, card, destination.type * 1000 + destination.type_arg);
            /*
            Can't add a tooltip, because showing a tooltip will mess with the hover effect.
            const DESTINATIONS = this.getDestinations(game);
            const destinationInfos = DESTINATIONS.find(d => d.id == destination.type_arg);
            this.game.setTooltip(`destination-card-${destination.id}`, `
                <div>${dojo.string.substitute(_('${from} to ${to}'), {from: this.game.getCityName(destinationInfos.from), to: this.game.getCityName(destinationInfos.to)})}, ${destinationInfos.points} ${_('points')}</div>
                <div class="destination-card" style="${getBackgroundInlineStyleForDestination(destination)}"></div>
            `);*/
            card.addEventListener('click', () => this.activateNextDestination(this.destinationsDone.some(d => d.id == destination.id) ? this.destinationsDone : this.destinationsTodo));
            // highlight destination's cities on the map, on mouse over
            card.addEventListener('mouseenter', () => this.game.setHighligthedDestination(destination));
            card.addEventListener('mouseleave', () => this.game.setHighligthedDestination(null));
            if (originStock) {
                this.addAnimationFrom(card, document.getElementById(`${originStock.container_div.id}_item_${destination.id}`));
            }
        });
        originStock?.removeAll();
        this.destinationsTodo.push(...destinations);
        this.destinationColumnsUpdated();
    }
    /**
     * Mark destination as complete (place it on the "complete" column).
     */
    markDestinationCompleteNoAnimation(destination) {
        const index = this.destinationsTodo.findIndex(d => d.id == destination.id);
        if (index !== -1) {
            this.destinationsTodo.splice(index, 1);
        }
        this.destinationsDone.push(destination);
        const card = document.getElementById(`destination-card-${destination.id}`);
        document.getElementById(`player-table-${this.playerId}-destinations-done`).appendChild(card);
        if (Array.isArray(destination.to)) {
            card.classList.add('no-mask');
        }
        this.destinationColumnsUpdated();
    }
    /**
     * Add an animation to mark a destination as complete.
     */
    markDestinationCompleteAnimation(destination, destinationRoutes) {
        const newDac = new DestinationCompleteAnimation(this.game, destination, destinationRoutes, `destination-card-${destination.id}`, `destination-card-${destination.id}`, {
            start: d => document.getElementById(`destination-card-${d.id}`).classList.add('hidden-for-animation'),
            change: d => this.markDestinationCompleteNoAnimation(d),
            end: d => document.getElementById(`destination-card-${d.id}`).classList.remove('hidden-for-animation'),
        }, 'completed');
        this.game.addAnimation(newDac);
    }
    /**
     * Mark a destination as complete.
     */
    markDestinationComplete(destination, destinationRoutes) {
        if (destinationRoutes && this.game.bga.gameui.bgaAnimationsActive()) {
            this.markDestinationCompleteAnimation(destination, destinationRoutes);
        }
        else {
            this.markDestinationCompleteNoAnimation(destination);
        }
    }
    /**
     * Highlight another destination.
     */
    activateNextDestination(destinationList) {
        const oldSelectedDestination = this.selectedDestination;
        if (this.selectedDestination && destinationList.some(d => d.id == this.selectedDestination.id) && destinationList.length > 1) {
            destinationList.splice(destinationList.length, 0, ...destinationList.splice(0, 1));
        }
        this.selectedDestination = destinationList[0];
        this.game.setActiveDestination(this.selectedDestination, oldSelectedDestination);
        document.getElementById(`player-table-${this.playerId}-destinations-todo`).classList.toggle('front', destinationList == this.destinationsTodo);
        document.getElementById(`player-table-${this.playerId}-destinations-done`).classList.toggle('front', destinationList == this.destinationsDone);
        this.destinationColumnsUpdated();
    }
    /**
     * Update destination cards placement when there is a change.
     */
    destinationColumnsUpdated() {
        const doubleColumn = this.destinationsTodo.length > 0 && this.destinationsDone.length > 0;
        const destinationsDiv = document.getElementById(`player-table-${this.playerId}-destinations`);
        const maxBottom = Math.max(this.placeCards(this.destinationsTodo, doubleColumn ? DESTINATION_CARD_SHIFT : 0), this.placeCards(this.destinationsDone));
        const height = `${maxBottom + CARD_HEIGHT}px`;
        destinationsDiv.style.height = height;
        document.getElementById(`player-table-${this.playerId}-train-cars`).style.height = height;
        this.game.setDestinationsToConnect(this.destinationsTodo);
    }
    /**
     * Place cards on a column.
     */
    placeCards(list, originalBottom = 0) {
        let maxBottom = 0;
        list.forEach((destination, index) => {
            const bottom = originalBottom + index * DESTINATION_CARD_SHIFT;
            const card = document.getElementById(`destination-card-${destination.id}`);
            card.parentElement.prepend(card);
            card.style.bottom = `${bottom}px`;
            if (bottom > maxBottom) {
                maxBottom = bottom;
            }
        });
        return maxBottom;
    }
    /**
     * Add an animation to the card (when it is created).
     */
    addAnimationFrom(card, from) {
        if (!this.game.bga.gameui.bgaAnimationsActive()) {
            return;
        }
        const destinationBR = card.getBoundingClientRect();
        const originBR = from.getBoundingClientRect();
        const deltaX = destinationBR.left - originBR.left;
        const deltaY = destinationBR.top - originBR.top;
        card.style.zIndex = '10';
        card.style.transition = `transform 0.5s linear`;
        const zoom = this.game.getZoom();
        card.style.transform = `translate(${-deltaX / zoom}px, ${-deltaY / zoom}px)`;
        setTimeout(() => card.style.transform = null);
        setTimeout(() => {
            card.style.zIndex = null;
            card.style.transition = null;
        }, 500);
    }
}

const CARD_WIDTH = 250;
const CARD_HEIGHT = 161;
const DESTINATION_CARD_SHIFT = 32;
function setupTrainCarCards(stock) {
    const trainCarsUrl = `${g_gamethemeurl}img/train-cards.jpg`;
    for (let type = 0; type <= 8; type++) {
        stock.addItemType(type, type, trainCarsUrl, type);
    }
}
function setupDestinationCards(map, stock) {
    const destinations = getDestinations(map);
    destinations.forEach(destination => {
        const file = `${g_gamethemeurl}img/${map.code}/destinations-${destination.type}-${destination.setTypeArg}.jpg`;
        stock.addItemType(destination.uniqueId, -1000 * destination.type + destination.typeArg, file, (destination.typeArg % 100) - 1);
    });
}
const GRAY = 0;
const PINK = 1;
const WHITE = 2;
const BLUE = 3;
const YELLOW = 4;
const ORANGE = 5;
const BLACK = 6;
const RED = 7;
const GREEN = 8;
function getColor(color, type) {
    switch (color) {
        case 0: return type == 'route' ? _('Gray') : _('Locomotive');
        case 1: return _('Pink');
        case 2: return _('White');
        case 3: return _('Blue');
        case 4: return _('Yellow');
        case 5: return _('Orange');
        case 6: return _('Black');
        case 7: return _('Red');
        case 8: return _('Green');
    }
}
function setupTrainCarCardDiv(cardDiv, cardTypeId) {
    cardDiv.title = getColor(Number(cardTypeId), 'train-car');
}
class DestinationCard {
    constructor(type, typeArg, from, to, points) {
        this.type = type;
        this.typeArg = typeArg;
        this.from = from;
        this.to = to;
        this.points = points;
        this.uniqueId = 1000 * type + typeArg;
        this.setTypeArg = Math.floor(typeArg / 100);
    }
}
function getDestinations(map) {
    const destinations = [];
    Object.entries(map.destinations).forEach(typeEntry => Object.entries(typeEntry[1]).forEach(destinationEntry => destinations.push(new DestinationCard(Number(typeEntry[0]), Number(destinationEntry[0]), destinationEntry[1].from, destinationEntry[1].to, destinationEntry[1].points))));
    return destinations;
}
function setupDestinationCardDiv(game, cardDiv, cardUniqueId) {
    const destinations = getDestinations(game.getMap());
    const destination = destinations.find(d => d.uniqueId == cardUniqueId);
    cardDiv.title = `${dojo.string.substitute(_('${from} to ${to}'), {
        from: game.getCityName(destination.from),
        to: Array.isArray(destination.to) ? destination.to.map(city => game.getCityName(city)).join(' / ') : game.getCityName(destination.to),
    })}, ${Array.isArray(destination.points) ? destination.points.join(' / ') : destination.points} ${_('points')}`;
}
function getBackgroundInlineStyleForDestination(map, destination) {
    const setTypeArg = Math.floor(destination.type_arg / 100);
    let file = `destinations-${destination.type}-${setTypeArg}.jpg`;
    const imagePosition = (destination.type_arg % 100) - 1;
    const row = Math.floor(imagePosition / IMAGE_ITEMS_PER_ROW);
    const xBackgroundPercent = (imagePosition - (row * IMAGE_ITEMS_PER_ROW)) * 100;
    const yBackgroundPercent = row * 100;
    return `background-image: url('${g_gamethemeurl}img/${map.code}/${file}'); background-position: -${xBackgroundPercent}% -${yBackgroundPercent}%;`;
}

// @ts-ignore
const [Stock] = await globalThis.importDojoLibs(["ebg/stock"]);
/**
 * Selection of new destinations.
 */
class DestinationSelection {
    /**
     * Init stock.
     */
    constructor(game, map) {
        this.game = game;
        const DESTINATION_CARD_WIDTH = map.vertical ? CARD_HEIGHT : CARD_WIDTH;
        const DESTINATION_CARD_HEIGHT = map.vertical ? CARD_WIDTH : CARD_HEIGHT;
        // @ts-ignore
        this.destinations = new ebg.stock();
        this.destinations.setSelectionAppearance('class');
        this.destinations.selectionClass = 'selected';
        this.destinations.setSelectionMode(2);
        // @ts-ignore
        this.destinations.create(game.bga.gameui, document.getElementById(`destination-stock`), DESTINATION_CARD_WIDTH, DESTINATION_CARD_HEIGHT);
        this.destinations.onItemCreate = (cardDiv, cardUniqueId) => setupDestinationCardDiv(game, cardDiv, Number(cardUniqueId));
        this.destinations.image_items_per_row = 10;
        this.destinations.centerItems = true;
        // @ts-ignore
        this.destinations.item_margin = 20;
        // @ts-ignore
        dojo.connect(this.destinations, 'onChangeSelection', this, () => this.selectionChange());
        setupDestinationCards(map, this.destinations);
    }
    /**
     * Set visible destination cards.
     */
    setCards(destinations, minimumDestinations, visibleColors) {
        dojo.removeClass('destination-deck', 'hidden');
        destinations.forEach(destination => {
            this.destinations.addToStockWithId(destination.type * 1000 + destination.type_arg, '' + destination.id);
            const cardDiv = document.getElementById(`destination-stock_item_${destination.id}`);
            // when mouse hover destination, highlight it on the map
            cardDiv.addEventListener('mouseenter', () => this.game.setHighligthedDestination(destination));
            cardDiv.addEventListener('mouseleave', () => this.game.setHighligthedDestination(null));
            // when destinatin is selected, another highlight on the map
            cardDiv.addEventListener('click', () => this.game.setSelectedDestination(destination, this.destinations.getSelectedItems().some(item => Number(item.id) == destination.id)));
        });
        this.minimumDestinations = minimumDestinations;
        visibleColors.forEach((color, index) => {
            document.getElementById(`visible-train-cards-mini${index}`).dataset.color = '' + color;
        });
    }
    /**
     * Hide destination selector.
     */
    hide() {
        this.destinations.removeAll();
        dojo.addClass('destination-deck', 'hidden');
    }
    /**
     * Get selected destinations ids.
     */
    getSelectedDestinationsIds() {
        return this.destinations.getSelectedItems().map(item => Number(item.id));
    }
    /**
     * Toggle activation of confirm selection buttons, depending on minimumDestinations.
     */
    selectionChange() {
        document.getElementById('chooseInitialDestinations_button')?.classList.toggle('disabled', this.destinations.getSelectedItems().length < this.minimumDestinations);
        document.getElementById('chooseAdditionalDestinations_button')?.classList.toggle('disabled', this.destinations.getSelectedItems().length < this.minimumDestinations);
    }
}

/**
 * Longest path animation : wagons used by longest path are highlighted, and length is displayed over the map.
 */
class LongestPathAnimation extends WagonsAnimation {
    constructor(game, routes, length, playerColor, actions) {
        super(game, routes);
        this.routes = routes;
        this.length = length;
        this.playerColor = playerColor;
        this.actions = actions;
    }
    animate() {
        return new Promise(resolve => {
            document.getElementById('map').insertAdjacentHTML('beforeend', `
            <div id="longest-path-animation" style="color: #${this.playerColor};${this.getCardPosition()}">${this.length}</div>
            `);
            this.setWagonsVisibility(true);
            setTimeout(() => this.endAnimation(resolve), 1900);
        });
    }
    endAnimation(resolve) {
        this.setWagonsVisibility(false);
        const number = document.getElementById('longest-path-animation');
        number.parentElement.removeChild(number);
        resolve(this);
        this.game.endAnimation(this);
        this.actions.end?.();
    }
    getCardPosition() {
        let x = 100;
        let y = 100;
        if (this.routes.length) {
            const map = this.game.getMap();
            const positions = [this.routes[0].from, this.routes[this.routes.length - 1].to].map(cityId => map.cities[cityId]);
            x = (positions[0].x + positions[1].x) / 2;
            y = (positions[0].y + positions[1].y) / 2;
        }
        return `left: ${x}px; top: ${y}px;`;
    }
}

/**
 * Longest path animation : wagons used by longest path are highlighted, and length is displayed over the map.
 */
class MandalaRoutesAnimation extends WagonsAnimation {
    constructor(game, routes, destination, actions) {
        super(game, routes);
        this.routes = routes;
        this.actions = actions;
        this.cities = [];
        [destination.from, destination.to].forEach(cityId => this.cities.push(document.getElementById(`city${cityId}`)));
    }
    animate() {
        return new Promise(resolve => {
            this.cities.forEach(city => city.dataset.highlight = 'true');
            this.setWagonsVisibility(true);
            setTimeout(() => this.endAnimation(resolve), 1900);
        });
    }
    endAnimation(resolve) {
        this.setWagonsVisibility(false);
        this.cities.forEach(city => city.dataset.highlight = 'false');
        resolve(this);
        this.game.endAnimation(this);
        this.actions.end?.();
    }
    getCardPosition() {
        let x = 100;
        let y = 100;
        if (this.routes.length) {
            const map = this.game.getMap();
            const positions = [this.routes[0].from, this.routes[this.routes.length - 1].to].map(cityId => map.cities[cityId]);
            x = (positions[0].x + positions[1].x) / 2;
            y = (positions[0].y + positions[1].y) / 2;
        }
        return `left: ${x}px; top: ${y}px;`;
    }
}

/**
 * Remaining stations animation : Remaining stations count is displayed over the map.
 */
class RemainingStationsAnimation extends WagonsAnimation {
    constructor(game, remainingStations, playerColor, actions) {
        super(game, []);
        this.remainingStations = remainingStations;
        this.playerColor = playerColor;
        this.actions = actions;
    }
    animate() {
        return new Promise(resolve => {
            dojo.place(`
            <div id="remaining-station-animation">
                ${this.remainingStations}
                <div class="icon station" data-player-color="${this.playerColor}"></div>
            </div>
            `, 'map');
            this.setWagonsVisibility(true);
            setTimeout(() => this.endAnimation(resolve), 1900);
        });
    }
    endAnimation(resolve) {
        this.setWagonsVisibility(false);
        const number = document.getElementById('remaining-station-animation');
        number.parentElement.removeChild(number);
        resolve(this);
        this.game.endAnimation(this);
        this.actions.end?.();
    }
}

/**
 * End score board.
 * It will start empty, and notifications will update it and start animations one by one.
 */
class EndScore {
    constructor(game, players, 
    /** fromReload: if a player refresh when game is over, we skip animations (as there will be no notifications to animate the score board) */
    fromReload, 
    /** bestScore is the top score for the game, so progression shown as train moving forward is relative to best score */
    bestScore) {
        this.game = game;
        this.players = players;
        this.bestScore = bestScore;
        /** Player scores (key is player id) */
        this.scoreCounters = [];
        /** Unrevealed destinations counters (key is player id) */
        this.destinationCounters = [];
        /** Complete destinations counters (key is player id) */
        this.completedDestinationCounters = [];
        /** Uncomplete destinations counters (key is player id) */
        this.uncompletedDestinationCounters = [];
        players.forEach(player => {
            const playerId = Number(player.id);
            document.getElementById('score-table-body').insertAdjacentHTML('beforeend', `<tr id="score${player.id}">
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
            </tr>`);
            const destinationCounter = new ebg.counter();
            destinationCounter.create(`destination-counter-${player.id}`);
            destinationCounter.setValue(fromReload ? 0 : this.game.destinationCardCounters[player.id].getValue());
            this.destinationCounters[playerId] = destinationCounter;
            const completedDestinationCounter = new ebg.counter();
            completedDestinationCounter.create(`completed-destination-counter-${player.id}`);
            completedDestinationCounter.setValue(fromReload ? player.completedDestinations.length : 0);
            this.completedDestinationCounters[playerId] = completedDestinationCounter;
            const uncompletedDestinationCounter = new ebg.counter();
            uncompletedDestinationCounter.create(`uncompleted-destination-counter-${player.id}`);
            uncompletedDestinationCounter.setValue(fromReload ? player.uncompletedDestinations.length : 0);
            this.uncompletedDestinationCounters[playerId] = uncompletedDestinationCounter;
            const scoreCounter = new ebg.counter();
            scoreCounter.create(`end-score-${player.id}`);
            scoreCounter.setValue(this.game.getPlayerScore(playerId));
            this.scoreCounters[playerId] = scoreCounter;
            this.moveTrain(playerId);
        });
        // if we are at reload of end state, we display values, else we wait for notifications
        if (fromReload) {
            const longestPath = Math.max(...players.map(player => player.longestPathLength));
            this.setBestScore(bestScore);
            const maxCompletedDestinations = players.map(player => player.completedDestinations.length).reduce((a, b) => (a > b) ? a : b, 0);
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
    highlightWinnerScore(playerId) {
        document.getElementById(`score${playerId}`).classList.add('highlight');
        document.getElementById(`score-name-${playerId}`).style.color = '';
    }
    /**
     * Save best score so we can move trains.
     */
    setBestScore(bestScore) {
        this.bestScore = bestScore;
        this.players.forEach(player => this.moveTrain(Number(player.id)));
    }
    /**
     * Set score, and animate train to new score.
     */
    setPoints(playerId, points) {
        this.scoreCounters[playerId].toValue(points);
        this.moveTrain(playerId);
    }
    /**
     * Move train to represent score progression.
     */
    moveTrain(playerId) {
        const scorePercent = 100 * this.scoreCounters[playerId].getValue() / Math.max(50, this.bestScore);
        document.getElementById(`train-image-${playerId}`).style.right = `${100 - scorePercent}%`;
    }
    /**
     * Show score animation for a revealed destination.
     */
    scoreDestination(playerId, destination, destinationRoutes, isFastEndScoring = false) {
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
        const newDac = new DestinationCompleteAnimation(this.game, destination, destinationRoutes, `destination-counter-${playerId}`, `${destinationRoutes ? 'completed' : 'uncompleted'}-destination-counter-${playerId}`, {
            change: () => {
                this.game.bga.sounds.play(`ttr-${destinationRoutes ? 'completed' : 'uncompleted'}-end`);
                this.game.bga.gameui.disableNextMoveSound();
            },
            end: endFunction,
        }, state, 0.15 / this.game.getZoom());
        this.game.addAnimation(newDac);
    }
    updateDestinationsTooltip(player) {
        let html = `<div class="destinations-flex">
            <div>
                ${player.completedDestinations?.map(destination => `<div class="destination-card completed" style="${getBackgroundInlineStyleForDestination(this.game.getMap(), destination)}"></div>`)}
            </div>
            <div>
                ${player.uncompletedDestinations?.map(destination => `<div class="destination-card uncompleted" style="${getBackgroundInlineStyleForDestination(this.game.getMap(), destination)}"></div>`)}
            </div>
        </div>`;
        if (document.getElementById(`destinations-score-${player.id}`)) {
            this.game.setTooltip(`destinations-score-${player.id}`, html);
        }
    }
    /**
     * Show mandala routes animation for a player.
     */
    showMandalaRoutes(routes, destination, isFastEndScoring = false) {
        if (isFastEndScoring) {
            return;
        }
        const newDac = new MandalaRoutesAnimation(this.game, routes, destination, {
            end: () => {
                //this.game.bga.sounds.play(`ttr-longest-line-scoring`);
                //this.game.bga.gameui.disableNextMoveSound();
            }
        });
        this.game.addAnimation(newDac);
    }
    /**
     * Show longest path animation for a player.
     */
    showLongestPath(playerColor, routes, length, isFastEndScoring = false) {
        if (isFastEndScoring) {
            return;
        }
        const newDac = new LongestPathAnimation(this.game, routes, length, playerColor, {
            end: () => {
                this.game.bga.sounds.play(`ttr-longest-line-scoring`);
                this.game.bga.gameui.disableNextMoveSound();
            }
        });
        this.game.addAnimation(newDac);
    }
    /**
     * Add Globetrotter badge to the Globetrotter winner(s).
     */
    setGlobetrotterWinner(playerId, length) {
        document.getElementById(`bonus-card-icons-${playerId}`).insertAdjacentHTML('beforeend', `<div id="globetrotter-bonus-card-${playerId}" class="globetrotter bonus-card bonus-card-icon"></div>`);
        this.game.setTooltip(`globetrotter-bonus-card-${playerId}`, `
        <div><strong>${_('Most Completed Tickets')} : ${length}</strong></div>
        <div>${_('The player who completed the most Destination tickets receives this special bonus card and adds 15 points to his score.')}</div>
        <div class="globetrotter bonus-card"></div>
        `);
    }
    /**
     * Show longest path animation for a player.
     */
    showRemainingStations(playerColor, remainingStations, isFastEndScoring = false) {
        if (isFastEndScoring) {
            return;
        }
        const newDac = new RemainingStationsAnimation(this.game, remainingStations, playerColor, {});
        this.game.addAnimation(newDac);
    }
    /**
     * Add longest path badge to the longest path winner(s).
     */
    setLongestPathWinner(playerId, length) {
        document.getElementById(`bonus-card-icons-${playerId}`).insertAdjacentHTML('beforeend', `<div id="longest-path-bonus-card-${playerId}" class="longest-path bonus-card bonus-card-icon"></div>`);
        this.game.setTooltip(`longest-path-bonus-card-${playerId}`, `
        <div><strong>${_('Longest path')} : ${length}</strong></div>
        <div>${_('The player who has the Longest Continuous Path of routes receives this special bonus card and adds 10 points to his score.')}</div>
        <div class="longest-path bonus-card"></div>
        `);
    }
}

const CROSSHAIR_SIZE = 20;
/**
 * Player's train car cards.
 */
class PlayerTrainCars {
    constructor(game, player, trainCars) {
        this.game = game;
        this.left = true;
        this.route = null;
        this.city = null;
        this.selectable = false;
        this.selectedColor = null;
        this.playerId = Number(player.id);
        this.addTrainCars(trainCars);
    }
    /**
     * Add train cars to player's hand.
     */
    addTrainCars(trainCars, from) {
        trainCars.forEach(trainCar => {
            const group = this.getGroup(trainCar.type);
            const deg = Math.round(-4 + Math.random() * 8);
            let card = document.getElementById(`train-car-card-${trainCar.id}`);
            const groupTrainCarCards = group.getElementsByClassName('train-car-cards')[0];
            if (!card) {
                let html = `
                <div id="train-car-card-${trainCar.id}" class="train-car-card" data-color="${trainCar.type}"></div>
                `;
                groupTrainCarCards.insertAdjacentHTML('beforeend', html);
                card = document.getElementById(`train-car-card-${trainCar.id}`);
            }
            else {
                groupTrainCarCards.appendChild(card);
            }
            card.dataset.handRotation = `${deg}`;
            const degWithColorBlind = this.left && this.game.isColorBlindMode() ? 180 + deg : deg;
            card.style.transform = `rotate(${degWithColorBlind}deg)`;
            if (from) {
                const card = document.getElementById(`train-car-card-${trainCar.id}`);
                this.addAnimationFrom(card, group, from, deg, degWithColorBlind);
            }
        });
        this.updateCounters();
    }
    /**
     * Set player table position.
     */
    setPosition(left) {
        this.left = left;
        const div = document.getElementById(`player-table-${this.playerId}-train-cars`);
        div.classList.toggle('left', left);
        this.updateCounters(); // to realign
        this.updateColorBlindRotation();
    }
    /**
     * Rotate 180° on train car cards, if they are on the left, and if color-blind option is on.
     */
    updateColorBlindRotation() {
        const cards = Array.from(document.getElementById(`player-table-${this.playerId}-train-cars`).getElementsByClassName('train-car-card'));
        cards.forEach(card => {
            const deg = Number(card.dataset.handRotation);
            const degWithColorBlind = this.left && this.game.isColorBlindMode() ? 180 + deg : deg;
            card.style.transform = `rotate(${degWithColorBlind}deg)`;
        });
    }
    /**
     * Remove train cars from player's hand.
     */
    removeCards(removeCards) {
        removeCards.forEach(card => {
            const div = document.getElementById(`train-car-card-${card.id}`);
            if (div) {
                const groupDiv = div.closest('.train-car-group');
                div.parentElement.removeChild(div);
                if (!groupDiv.getElementsByClassName('train-car-card').length) {
                    groupDiv.parentElement.removeChild(groupDiv);
                }
                this.updateCounters();
            }
        });
    }
    /**
     * Set if train car cards can be dragged.
     */
    setDraggable(draggable) {
        const groups = Array.from(document.getElementsByClassName('train-car-group'));
        groups.forEach(groupDiv => groupDiv.setAttribute('draggable', draggable.toString()));
    }
    /**
     * Set if train car cards can be selected by a click.
     */
    setSelectable(selectable) {
        this.selectable = selectable;
        if (!selectable && this.selectedColor) {
            this.deselectColor(this.selectedColor);
        }
    }
    /**
     * Return a group of cards (cards of the same color).
     * If it doesn't exists, create it.
     */
    getGroup(type) {
        let group = document.getElementById(`train-car-group-${type}`);
        if (!group) {
            document.getElementById(`player-table-${this.playerId}-train-cars`).insertAdjacentHTML(type == 0 ? 'afterbegin' : 'beforeend', `
            <div id="train-car-group-${type}" class="train-car-group" data-type="${type}">
                <div id="train-car-group-${type}-counter" class="train-car-group-counter">0</div>
                <div id="train-car-group-${type}-cards" class="train-car-cards"></div>
            </div>
            `);
            this.updateCounters();
            group = document.getElementById(`train-car-group-${type}`);
            group.addEventListener('dragstart', (e) => {
                this.deselectColor(this.selectedColor);
                const dt = e.dataTransfer;
                dt.effectAllowed = 'move';
                dt.setData('text/plain', '' + type);
                const mapDiv = document.getElementById('map');
                mapDiv.dataset.dragColor = '' + type;
                // we generate a clone of group (without positionning with transform on the group)
                const groupClone = document.createElement('div');
                groupClone.classList.add('train-car-group', 'drag');
                groupClone.innerHTML = group.innerHTML;
                document.body.appendChild(groupClone);
                groupClone.offsetHeight;
                dt.setDragImage(groupClone, -CROSSHAIR_SIZE, -CROSSHAIR_SIZE);
                setTimeout(() => document.body.removeChild(groupClone));
                setTimeout(() => {
                    group.classList.add('hide');
                }, 0);
                this.game.map.addDragOverlay();
                return true;
            });
            group.addEventListener('dragend', (e) => {
                setTimeout(() => group.classList.remove('hide'), 50);
                const mapDiv = document.getElementById('map');
                mapDiv.dataset.dragColor = '';
                this.game.map.removeDragOverlay();
            });
            group.addEventListener('click', () => {
                if (this.route) {
                    this.game.chooseActionState.clickedRouteColorChosen(this.route, type);
                }
                else if (this.city) {
                    this.game.chooseActionState.clickedCityColorChosen(this.city, type);
                }
                else if (this.selectable) {
                    if (this.selectedColor === type) {
                        this.deselectColor(type);
                    }
                    else if (this.selectedColor !== null) {
                        this.deselectColor(this.selectedColor);
                        this.selectColor(type);
                    }
                    else {
                        this.selectColor(type);
                    }
                }
            });
        }
        return group;
    }
    getSelectedColor() {
        return this.selectedColor;
    }
    selectColor(color) {
        const group = document.getElementById(`train-car-group-${color}`);
        group?.classList.add('selected');
        this.selectedColor = color;
        this.game.selectedColorChanged(this.selectedColor);
    }
    deselectColor(color) {
        if (color === null) {
            return;
        }
        const group = document.getElementById(`train-car-group-${color}`);
        group?.classList.remove('selected');
        this.selectedColor = null;
        this.game.selectedColorChanged(this.selectedColor);
    }
    getGroups() {
        return Array.from(document.getElementsByClassName('train-car-group'));
    }
    /**
     * Update counters on color groups.
     */
    updateCounters() {
        const groups = this.getGroups();
        const middleIndex = (groups.length - 1) / 2;
        groups.forEach((groupDiv, index) => {
            const distanceFromIndex = index - middleIndex;
            const count = groupDiv.getElementsByClassName('train-car-card').length;
            groupDiv.dataset.count = '' + count;
            groupDiv.getElementsByClassName('train-car-group-counter')[0].innerHTML = `${count > 1 ? count : ''}`;
            const angle = distanceFromIndex * 4;
            groupDiv.dataset.angle = '' + angle;
            groupDiv.style.transform = `translate${this.left ? 'X(-' : 'Y('}${Math.pow(Math.abs(distanceFromIndex) * 2, 2)}px) rotate(${angle}deg)`;
            groupDiv.parentNode.appendChild(groupDiv);
        });
    }
    /**
     * Add an animation to the card (when it is created).
     */
    addAnimationFrom(card, group, from, deg, degWithColorBlind) {
        if (!this.game.bga.gameui.bgaAnimationsActive()) {
            return;
        }
        const trainCars = document.getElementById(`player-table-${this.playerId}-train-cars`);
        trainCars.classList.add('new-card-animation');
        const destinationBR = card.getBoundingClientRect();
        const originBR = from.getBoundingClientRect();
        const deltaX = destinationBR.left - originBR.left;
        const deltaY = destinationBR.top - originBR.top;
        card.style.zIndex = '10';
        card.style.transition = `transform 0.5s linear`;
        const zoom = this.game.getZoom();
        const angle = -Number(group.dataset.angle);
        card.style.transform = `rotate(${this.left ? angle : angle - 90}deg) translate(${-deltaX / zoom}px, ${-deltaY / zoom}px)`;
        setTimeout(() => {
            card.style.transform = `rotate(${deg}deg)`;
            if (degWithColorBlind != deg) {
                setTimeout(() => card.style.transform = `rotate(${degWithColorBlind}deg)`, 500);
            }
        }, 0);
        setTimeout(() => {
            card.style.zIndex = null;
            card.style.transition = null;
            trainCars.classList.remove('new-card-animation');
        }, 500);
    }
    /**
     * Get the colors a player can use to claim a given route.
     */
    setSelectableTrainCarColors(route, possibleColors) {
        this.route = route;
        const groups = this.getGroups();
        groups.forEach(groupDiv => {
            if (route) {
                const color = Number(groupDiv.dataset.type);
                groupDiv.classList.toggle('disabled', color != 0 && !possibleColors.includes(color));
            }
            else {
                groupDiv.classList.remove('disabled');
            }
        });
    }
    /**
     * Get the colors a player can use to claim a given city.
     */
    setSelectableTrainCarColorsForStation(city, possibleColors) {
        this.city = city;
        const groups = this.getGroups();
        groups.forEach(groupDiv => {
            if (city) {
                const color = Number(groupDiv.dataset.type);
                groupDiv.classList.toggle('disabled', !possibleColors.includes(color));
            }
            else {
                groupDiv.classList.remove('disabled');
            }
        });
    }
}

const DRAG_AUTO_ZOOM_DELAY = 2000;
const SIDES = ['left', 'right', 'top', 'bottom'];
const CORNERS = ['bottom-left', 'bottom-right', 'top-left', 'top-right'];
const HORIZONTAL_MAP_WIDTH = 1744;
const HORIZONTAL_MAP_HEIGHT = 1125;
const DECK_WIDTH = 250;
const PLAYER_WIDTH = 305;
const PLAYER_HEIGHT = 257; // avg height (4 destination cards)
/**
 * Manager for in-map zoom.
 */
class InMapZoomManager {
    constructor(map) {
        this.pos = { dragging: false, top: 0, left: 0, x: 0, y: 0 }; // for map drag (if zoomed)
        this.zoomed = false; // indicates if in-map zoom is active
        this.mapZoomDiv = document.getElementById('map-zoom');
        this.mapDiv = document.getElementById('map');
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
    dragOverMouseMoved(clientX, clientY) {
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
    toggleZoom(scrollRatioX = null, scrollRatioY = null) {
        this.zoomed = !this.zoomed;
        this.mapDiv.style.transform = this.zoomed ? `scale(1.8)` : '';
        dojo.toggleClass('zoom-button', 'zoomed', this.zoomed);
        dojo.toggleClass('map-zoom', 'scrollable', this.zoomed);
        this.mapDiv.style.cursor = this.zoomed ? 'grab' : 'default';
        if (this.zoomed) {
            if (scrollRatioX && scrollRatioY) {
                this.mapZoomDiv.scrollLeft = (this.mapZoomDiv.scrollWidth - this.mapZoomDiv.clientWidth) * scrollRatioX;
                this.mapZoomDiv.scrollTop = (this.mapZoomDiv.scrollHeight - this.mapZoomDiv.clientHeight) * scrollRatioY;
            }
        }
        else {
            this.mapZoomDiv.scrollTop = 0;
            this.mapZoomDiv.scrollLeft = 0;
        }
    }
    /**
     * Handle mouse down, to grap map and scroll in it (imitate mobile touch scroll).
     */
    mouseDownHandler(e) {
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
    mouseMoveHandler(e) {
        if (!this.zoomed || !this.pos.dragging) {
            return;
        }
        // How far the mouse has been moved
        const dx = e.clientX - this.pos.x;
        const dy = e.clientY - this.pos.y;
        const factor = 0.1;
        // Scroll the element
        this.mapZoomDiv.scrollTop -= dy * factor;
        this.mapZoomDiv.scrollLeft -= dx * factor;
    }
    /**
     * Handle mouse up, to grap map and scroll in it (imitate mobile touch scroll).
     */
    mouseUpHandler() {
        if (!this.zoomed || !this.pos.dragging) {
            return;
        }
        this.mapDiv.style.cursor = 'grab';
        this.pos.dragging = false;
    }
    setHoveredRoute(route) {
        this.hoveredRoute = route;
    }
    setHoveredCity(city) {
        this.hoveredCity = city;
    }
}
/**
 * Map creation and in-map zoom handler.
 */
class TtrMap {
    /**
     * Place map corner illustration and borders, cities, routes, and bind events.
     */
    constructor(game, map, players, claimedRoutes, builtStations, illustration) {
        this.game = game;
        this.map = map;
        this.players = players;
        this.dragOverlay = null;
        this.crosshairTarget = null;
        this.crosshairHalfSize = 0;
        this.crosshairShift = 0;
        this.claimedRoutesIds = [];
        this.claimedCitiesIds = [];
        // map border
        document.getElementById('map').insertAdjacentHTML('afterbegin', `
            <div class="illustration" data-illustration="${illustration}"></div>
            <div id="cities"></div>
            <div id="route-spaces"></div>
            <div id="train-cars"></div>
            <div id="stations"></div>
        `);
        SIDES.forEach(side => document.getElementById('map-and-borders').insertAdjacentHTML('beforeend', `<div class="side ${side}"></div>`));
        CORNERS.forEach(corner => document.getElementById('map-and-borders').insertAdjacentHTML('beforeend', `<div class="corner ${corner}"></div>`));
        map.bigCities.forEach(bigCity => document.getElementById('cities').insertAdjacentHTML('beforeend', `<div class="big-city" style="left: ${bigCity.x}px; top: ${bigCity.y}px; width: ${bigCity.width}px;"></div>`));
        Object.entries(map.cities).forEach(entry => {
            const id = Number(entry[0]);
            const city = entry[1];
            document.getElementById('cities').insertAdjacentHTML('beforeend', `<div id="city${id}" class="city" 
                style="transform: translate(${city.x}px, ${city.y}px)"
                title="${game.getCityName(id)}"
            ></div>`);
        });
        this.createRouteSpaces('route-spaces');
        if (map.stations !== null) {
            this.createCities('cities');
        }
        this.setClaimedRoutes(claimedRoutes, null);
        this.setBuiltStations(builtStations, null);
        this.resizedDiv = document.getElementById('resized');
        this.mapDiv = document.getElementById('map');
        this.inMapZoomManager = new InMapZoomManager(map);
        this.game.setTooltipToClass(`train-car-deck-hidden-pile-tooltip`, `<strong>${_('Train cars deck')}</strong><br><br>
        ${_('Click here to pick one or two hidden train car cards')}`);
        this.game.setTooltip(`destination-deck-hidden-pile`, `<strong>${_('Destinations deck')}</strong><br><br>
        ${_('Click here to take three new destination cards (keep at least one)')}`);
    }
    createRouteSpaces(destination, shiftX = 0, shiftY = 0) {
        Object.values(this.map.routes).filter(route => !this.claimedRoutesIds.includes(route.id)).forEach(route => route.spaces.forEach((space, spaceIndex) => {
            let title = `${dojo.string.substitute(_('${from} to ${to}'), {
                from: this.game.getCityName(route.from),
                to: this.game.getCityName(route.to),
            })}, ${route.spaces.length} ${getColor(route.color, 'route')}`;
            if (route.tunnel) {
                title += ` (${_("Tunnel")})`;
            }
            if (route.locomotives) {
                title += ` (${_("${number} locomotive(s) required").replace('${number}', `${route.locomotives}`)})`;
            }
            document.getElementById(destination).insertAdjacentHTML('beforeend', `<div id="${destination}-route${route.id}-space${spaceIndex}" class="route-space ${route.tunnel ? 'tunnel' : ''}" 
                    style="transform: translate(${space.x + shiftX}px, ${space.y + shiftY}px) rotate(${space.angle}deg);"
                    title="${title}"
                    data-route="${route.id}" data-color="${route.color}"
                ></div>`);
            const spaceDiv = document.getElementById(`${destination}-route${route.id}-space${spaceIndex}`);
            if (destination == 'route-spaces') {
                this.setSpaceClickEvents(spaceDiv, route);
            }
            else {
                this.setSpaceDragEvents(spaceDiv, route);
            }
        }));
    }
    createCities(destination, shiftX = 0, shiftY = 0) {
        Object.entries(this.map.cities).forEach(entry => {
            const city = entry[1];
            city.id = Number(entry[0]);
            dojo.place(`<div id="city${city.id}${destination === 'map-drag-overlay' ? '-drag' : ''}" class="city" 
                style="transform: translate(${city.x + shiftX}px, ${city.y + shiftY}px); ${destination === 'map-drag-overlay' ? 'border: 2px solid red;' : ''}"
                title="${this.game.getCityName(city.id)}"
            ></div>`, destination);
            const cityDiv = document.getElementById(`city${city.id}${destination === 'map-drag-overlay' ? '-drag' : ''}`);
            if (!this.claimedCitiesIds.some(id => id == city.id)) {
                if (destination == 'cities') {
                    this.setCityClickEvents(cityDiv, city);
                }
                else {
                    this.setCityDragEvents(cityDiv, city);
                }
            }
        });
    }
    /**
     * Handle dragging train car cards over a route.
     */
    routeDragOver(e, route) {
        const cardsColor = Number(this.mapDiv.dataset.dragColor);
        let overRoute = route;
        if (cardsColor > 0 && route.color > 0 && cardsColor != route.color) {
            const otherRoute = Object.values(this.map.routes).find(r => route.from == r.from && route.to == r.to && route.id != r.id);
            if (otherRoute && otherRoute.color == cardsColor) {
                overRoute = otherRoute;
            }
        }
        let canClaimRoute = this.game.chooseActionState.canClaimRoute(overRoute, cardsColor);
        this.setHoveredRoute(overRoute, canClaimRoute);
        if (canClaimRoute) {
            e.preventDefault();
        }
    }
    ;
    /**
     * Handle dragging train car cards over a city.
     */
    cityDragOver(e, city) {
        const cardsColor = Number(this.mapDiv.dataset.dragColor);
        let canClaimCity = this.game.chooseActionState.canClaimCity(city, cardsColor);
        this.setHoveredCity(city, canClaimCity);
        if (canClaimCity) {
            e.preventDefault();
        }
    }
    ;
    /**
     * Handle dropping train car cards over a route.
     */
    routeDragDrop(e, route) {
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
        this.game.chooseActionState.clickedRouteColorChosen(overRoute, cardsColor);
    }
    ;
    /**
     * Handle dropping train car cards over a city.
     */
    cityDragDrop(e, city) {
        e.preventDefault();
        const mapDiv = document.getElementById('map');
        if (mapDiv.dataset.dragColor == '') {
            return;
        }
        this.setHoveredCity(null);
        const cardsColor = Number(this.mapDiv.dataset.dragColor);
        mapDiv.dataset.dragColor = '';
        this.game.chooseActionState.clickedCityColorChosen(city, cardsColor);
    }
    /**
     * Bind click events to route space.
     */
    setSpaceClickEvents(spaceDiv, route) {
        spaceDiv.addEventListener('dragenter', e => this.routeDragOver(e, route));
        spaceDiv.addEventListener('dragover', e => this.routeDragOver(e, route));
        spaceDiv.addEventListener('dragleave', e => this.setHoveredRoute(null));
        spaceDiv.addEventListener('drop', e => this.routeDragDrop(e, route));
        spaceDiv.addEventListener('click', () => this.game.chooseActionState.clickedRoute(route));
    }
    /**
     * Bind drag events to route space.
     */
    setSpaceDragEvents(spaceDiv, route) {
        spaceDiv.addEventListener('dragenter', e => this.routeDragOver(e, route));
        spaceDiv.addEventListener('dragover', e => this.routeDragOver(e, route));
        spaceDiv.addEventListener('dragleave', e => this.setHoveredRoute(null));
        spaceDiv.addEventListener('drop', e => this.routeDragDrop(e, route));
    }
    /**
     * Bind click events to vity.
     */
    setCityClickEvents(spaceDiv, city) {
        spaceDiv.addEventListener('click', () => this.game.chooseActionState.clickedCity(city));
    }
    /**
     * Bind drag events to city.
     */
    setCityDragEvents(cityDiv, city) {
        cityDiv.addEventListener('dragenter', e => this.cityDragOver(e, city));
        cityDiv.addEventListener('dragover', e => this.cityDragOver(e, city));
        cityDiv.addEventListener('dragleave', e => this.setHoveredRoute(null));
        cityDiv.addEventListener('drop', e => this.cityDragDrop(e, city));
    }
    /**
     * Highlight selectable route spaces.
     */
    setSelectableRoutes(selectable, possibleRoutes) {
        dojo.query('.route-space').removeClass('selectable');
        if (selectable) {
            possibleRoutes.forEach(route => this.map.routes[route.id].spaces.forEach((_, index) => document.getElementById(`route-spaces-route${route.id}-space${index}`)?.classList.add('selectable')));
        }
    }
    /**
     * Highlight selectable cities.
     */
    setSelectableStations(selectable, possibleStations) {
        dojo.query('.city').removeClass('selectable');
        if (selectable) {
            possibleStations?.forEach(city => document.getElementById(`city${city.id}`)?.classList.add('selectable'));
        }
    }
    /**
     * Place train cars on claimed routes.
     * fromPlayerId is for animation (null for no animation)
     */
    setClaimedRoutes(claimedRoutes, fromPlayerId) {
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
                            ${this.game.gamedatas.map.minimumPlayerForDoubleRoutes <= 3 ?
                                _('In 2 player games, only one of the Double-Routes can be used.') :
                                _('In 2 or 3 player games, only one of the Double-Routes can be used.')}</strong>`);
                        }
                    });
                }
            }
        });
    }
    animateWagonFromCounter(playerId, wagonId, toX, toY) {
        const wagon = document.getElementById(wagonId);
        const wagonBR = wagon.getBoundingClientRect();
        const fromBR = document.getElementById(`train-car-counter-${playerId}-wrapper`).getBoundingClientRect();
        const zoom = this.game.getZoom();
        const fromX = (fromBR.x - wagonBR.x) / zoom;
        const fromY = (fromBR.y - wagonBR.y) / zoom;
        wagon.style.transform = `translate(${fromX + toX}px, ${fromY + toY}px)`;
        setTimeout(() => {
            wagon.style.transition = 'transform 0.5s';
            wagon.style.transform = `translate(${toX}px, ${toY}px)`;
        }, 0);
    }
    /**
     * Place stations claimed city.
     * fromPlayerId is for animation (null for no animation)
     */
    setBuiltStations(builtStations, fromPlayerId) {
        builtStations.forEach(builtStation => {
            this.claimedCitiesIds.push(builtStation.cityId);
            const city = this.map.cities[builtStation.cityId];
            const player = this.players.find(player => Number(player.id) == builtStation.playerId);
            this.setStation(city, player, fromPlayerId, false);
        });
    }
    animateStationFromCounter(playerId, stationId, toX, toY) {
        const station = document.getElementById(stationId);
        const stationBR = station.getBoundingClientRect();
        const fromBR = document.getElementById(`station-counter-${playerId}-wrapper`).getBoundingClientRect();
        const zoom = this.game.getZoom();
        const fromX = (fromBR.x - stationBR.x) / zoom;
        const fromY = (fromBR.y - stationBR.y) / zoom;
        station.style.transform = `translate(${fromX + toX}px, ${fromY + toY}px)`;
        setTimeout(() => {
            station.style.transition = 'transform 0.5s';
            station.style.transform = `translate(${toX}px, ${toY}px)`;
        }, 0);
    }
    /**
     * Place train car on a route space.
     * fromPlayerId is for animation (null for no animation)
     * Phantom is for dragging over a route : wagons are showns translucent.
     */
    setWagon(route, space, spaceIndex, player, fromPlayerId, phantom, isLowestFromDoubleHorizontalRoute) {
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
        const alreadyPlacedWagons = Array.from(document.querySelectorAll('.wagon'));
        const xy = x + y;
        if (isLowestFromDoubleHorizontalRoute) { // we shift a little the train car to let the other route visible
            x += 10 * Math.abs(Math.sin(angle * Math.PI / 180));
            y += 10 * Math.abs(Math.cos(angle * Math.PI / 180));
        }
        const wagonHtml = `<div id="${id}" class="wagon angle${angleClassNumber} ${phantom ? 'phantom' : ''} ${space.top ? 'top' : ''}" data-player-color="${player.color}" data-color-blind-player-no="${player.playerNo}" data-xy="${xy}" style="transform: translate(${x}px, ${y}px)"></div>`;
        // we consider a wagon must be more visible than another if its X + Y is > as the other
        if (!alreadyPlacedWagons.length) {
            document.getElementById('train-cars').insertAdjacentHTML('beforeend', wagonHtml);
        }
        else {
            let placed = false;
            for (let i = 0; i < alreadyPlacedWagons.length; i++) {
                if (Number(alreadyPlacedWagons[i].dataset.xy) > xy) {
                    document.getElementById(alreadyPlacedWagons[i].id).insertAdjacentHTML('beforebegin', wagonHtml);
                    placed = true;
                    break;
                }
            }
            if (!placed) {
                document.getElementById('train-cars').insertAdjacentHTML('beforeend', wagonHtml);
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
    setWagons(route, player, fromPlayerId, phantom) {
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
                    this.game.bga.sounds.play(`ttr-placed-train-car`);
                }, 200 * spaceIndex);
            });
            this.game.bga.gameui.disableNextMoveSound();
        }
        else {
            route.spaces.forEach((space, spaceIndex) => this.setWagon(route, space, spaceIndex, player, fromPlayerId, phantom, isLowestFromDoubleHorizontalRoute));
        }
    }
    /**
     * Place station on a city.
     * Phantom is for dragging over a route : station is shown translucent.
     */
    setStation(city, player, fromPlayerId, phantom) {
        const id = `station${city.id}${phantom ? '-phantom' : ''}`;
        if (document.getElementById(id)) {
            return;
        }
        let x = city.x;
        let y = city.y;
        const stationHtml = `<div id="${id}" class="station ${phantom ? 'phantom' : ''}" data-player-color="${player.color}" data-color-blind-player-no="${player.playerNo}" style="transform: translate(${x}px, ${y}px)"></div>`;
        dojo.place(stationHtml, 'stations');
        if (fromPlayerId) {
            this.animateStationFromCounter(fromPlayerId, id, x, y);
        }
    }
    /**
     * Check if the route is mostly horizontal, and the lowest from a double route
     */
    isLowestFromDoubleHorizontalRoute(route) {
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
    getMapWidth() {
        return this.map.vertical ? HORIZONTAL_MAP_HEIGHT : HORIZONTAL_MAP_WIDTH;
    }
    getMapHeight() {
        return this.map.vertical ? HORIZONTAL_MAP_WIDTH : HORIZONTAL_MAP_HEIGHT;
    }
    /**
     * Set map size, depending on available screen size.
     * Player table will be placed left or bottom, depending on window ratio.
     */
    setAutoZoom() {
        if (!this.mapDiv.clientWidth) {
            setTimeout(() => this.setAutoZoom(), 200);
            return;
        }
        const MAP_WIDTH = this.getMapWidth();
        const MAP_HEIGHT = this.getMapHeight();
        const BOTTOM_RATIO = (MAP_WIDTH + DECK_WIDTH) / (MAP_HEIGHT + PLAYER_HEIGHT);
        const LEFT_RATIO = (PLAYER_WIDTH + MAP_WIDTH + DECK_WIDTH) / MAP_HEIGHT;
        const screenRatio = document.getElementById('game_play_area').clientWidth / (window.innerHeight - 80);
        const leftDistance = Math.abs(LEFT_RATIO - screenRatio);
        const bottomDistance = Math.abs(BOTTOM_RATIO - screenRatio);
        const left = leftDistance < bottomDistance || this.game.bga.players.isCurrentPlayerSpectator();
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
    getZoom() {
        return this.scale;
    }
    /**
     * Highlight active destination.
     */
    setActiveDestination(destination, previousDestination = null) {
        if (previousDestination) {
            if (previousDestination.id === destination.id) {
                return;
            }
            [previousDestination.from, previousDestination.to].filter(city => city > 0).forEach(city => document.getElementById(`city${city}`).dataset.selectedDestination = 'false');
        }
        if (destination) {
            [destination.from, destination.to].filter(city => city > 0).forEach(city => document.getElementById(`city${city}`).dataset.selectedDestination = 'true');
        }
    }
    /**
     * Highlight hovered route (when dragging train cars).
     */
    setHoveredRoute(route, valid = null, player = null) {
        this.inMapZoomManager.setHoveredRoute(route);
        if (route) {
            [route.from, route.to].filter(city => city > 0).forEach(city => {
                const cityDiv = document.getElementById(`city${city}`);
                cityDiv.dataset.hovered = 'true';
                cityDiv.dataset.valid = valid.toString();
            });
            if (valid) {
                this.setWagons(route, player || this.game.getCurrentPlayer(), null, true);
            }
        }
        else {
            Object.values(this.map.routes).forEach(r => [r.from, r.to].filter(city => city > 0).forEach(city => document.getElementById(`city${city}`).dataset.hovered = 'false'));
            // remove phantom wagons
            this.mapDiv.querySelectorAll('.wagon.phantom').forEach(spaceDiv => spaceDiv.parentElement.removeChild(spaceDiv));
        }
    }
    /**
     * Highlight hovered city (when dragging train cars).
     */
    setHoveredCity(city, valid = null) {
        this.inMapZoomManager.setHoveredCity(city);
        if (city) {
            if (valid) {
                this.setStation(city, this.game.getCurrentPlayer(), null, true);
            }
        }
        else {
            // remove phantom stations
            this.mapDiv.querySelectorAll('.station.phantom').forEach(spaceDiv => spaceDiv.parentElement.removeChild(spaceDiv));
        }
    }
    /**
     * Highlight cities of selectable destination.
     */
    setSelectableDestination(destination, visible) {
        [destination.from, destination.to].filter(city => city > 0).forEach(city => {
            document.getElementById(`city${city}`).dataset.selectable = '' + visible;
        });
    }
    /**
     * Highlight cities of selected destination.
     */
    setSelectedDestination(destination, visible) {
        [destination.from, destination.to].filter(city => city > 0).forEach(city => {
            document.getElementById(`city${city}`).dataset.selected = '' + visible;
        });
    }
    /**
     * Highlight cities player must connect for its objectives.
     */
    setDestinationsToConnect(destinations) {
        this.mapDiv.querySelectorAll(`.city[data-to-connect]`).forEach((city) => city.dataset.toConnect = 'false');
        const cities = [];
        destinations.forEach(destination => cities.push(destination.from, destination.to));
        cities.filter(city => city > 0).forEach(city => document.getElementById(`city${city}`).dataset.toConnect = 'true');
    }
    /**
     * Highlight destination (on destination mouse over).
     */
    setHighligthedDestination(destination) {
        const visible = Boolean(destination).toString();
        const shadow = document.getElementById('map-destination-highlight-shadow');
        shadow.dataset.visible = visible;
        let cities;
        if (destination) {
            shadow.dataset.from = '' + destination.from;
            shadow.dataset.to = '' + destination.to;
            cities = [destination.from, destination.to];
        }
        else {
            cities = [shadow.dataset.from, shadow.dataset.to];
        }
        cities.filter(city => Number(city) > 0).forEach(city => document.getElementById(`city${city}`).dataset.highlight = visible);
    }
    /**
     * Create the crosshair target when drag starts over the drag overlay.
     */
    overlayDragEnter(e) {
        if (!this.crosshairTarget) {
            this.crosshairTarget = document.createElement('div');
            this.crosshairTarget.id = 'map-drag-target';
            this.crosshairTarget.style.left = e.offsetX + 'px';
            this.crosshairTarget.style.top = e.offsetY + 'px';
            this.crosshairTarget.style.width = this.crosshairHalfSize * 2 + 'px';
            this.crosshairTarget.style.height = this.crosshairHalfSize * 2 + 'px';
            this.crosshairTarget.style.marginLeft = -(this.crosshairHalfSize + this.crosshairShift) + 'px';
            this.crosshairTarget.style.marginTop = -(this.crosshairHalfSize + this.crosshairShift) + 'px';
            this.dragOverlay.appendChild(this.crosshairTarget);
        }
    }
    /**
     * Move the crosshair target.
     */
    overlayDragMove(e) {
        if (this.crosshairTarget && e.target.id === this.dragOverlay.id) {
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
    addDragOverlay() {
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
        let lastEvent = null;
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
    removeDragOverlay() {
        this.crosshairTarget = null;
        document.getElementById(`map`).removeChild(this.dragOverlay);
        this.dragOverlay = null;
    }
    /**
     * Set outline for train cars on the map, according to preferences.
     */
    setOutline() {
        const preference = Number(this.game.bga.userPreferences.get(203));
        const outline = preference === 1 || (preference === 2 && this.mapDiv?.getBoundingClientRect().width < 1000);
        this.mapDiv.dataset.bigShadows = outline.toString();
    }
}

/**
 * Player table : train car et destination cards.
 */
class PlayerTable {
    constructor(game, player, trainCars, destinations, completedDestinations) {
        let html = `
            <div id="player-table" class="player-table">
                <div id="player-table-${player.id}-destinations" class="player-table-destinations"></div>
                <div id="player-table-${player.id}-train-cars" class="player-table-train-cars"></div>
            </div>
        `;
        document.getElementById('resized').insertAdjacentHTML('beforeend', html);
        this.playerDestinations = new PlayerDestinations(game, player, destinations, completedDestinations);
        this.playerTrainCars = new PlayerTrainCars(game, player, trainCars);
    }
    /**
     * Place player table to the left or the bottom of the map.
     */
    setPosition(left) {
        const playerHandDiv = document.getElementById(`player-table`);
        if (left) {
            document.getElementById('main-line').prepend(playerHandDiv);
        }
        else {
            document.getElementById('resized').appendChild(playerHandDiv);
        }
        playerHandDiv.classList.toggle('left', left);
        this.playerTrainCars.setPosition(left);
    }
    addDestinations(destinations, originStock) {
        this.playerDestinations.addDestinations(destinations, originStock);
    }
    markDestinationComplete(destination, destinationRoutes) {
        this.playerDestinations.markDestinationComplete(destination, destinationRoutes);
    }
    addTrainCars(trainCars, from) {
        this.playerTrainCars.addTrainCars(trainCars, from);
    }
    removeCards(removeCards) {
        this.playerTrainCars.removeCards(removeCards);
    }
    setDraggable(draggable) {
        this.playerTrainCars.setDraggable(draggable);
    }
    setSelectable(selectable) {
        this.playerTrainCars.setSelectable(selectable);
    }
    setSelectableTrainCarColors(route, possibleColors = null) {
        this.playerTrainCars.setSelectableTrainCarColors(route, possibleColors);
    }
    setSelectableTrainCarColorsForStation(city, possibleColors = null) {
        this.playerTrainCars.setSelectableTrainCarColorsForStation(city, possibleColors);
    }
    getSelectedColor() {
        return this.playerTrainCars.getSelectedColor();
    }
    updateColorBlindRotation() {
        return this.playerTrainCars.updateColorBlindRotation();
    }
}

class DistributionResult {
    constructor(distributionCards, auto = false) {
        this.auto = auto;
        this.cardIds = distributionCards.flat();
        const colorKey = Object.keys(distributionCards).map(Number).filter(n => ![0, 99].includes(n))[0];
        const hasColorCards = colorKey && distributionCards[colorKey].length > 0;
        this.locomotivesOnly = !hasColorCards;
    }
}
class DistributionPopin {
    constructor(trainCarsHand, claimingRoute, cost, canUseLocomotives) {
        this.trainCarsHand = trainCarsHand;
        this.claimingRoute = claimingRoute;
        this.cost = cost;
        this.canUseLocomotives = canUseLocomotives;
        this.distributionCards = [];
    }
    show(title) {
        this.distributionCards = [];
        return new Promise(resolve => {
            const distributionDlg = new ebg.popindialog();
            distributionDlg.create('distributionPopin');
            distributionDlg.setTitle(title);
            const locomotiveCards = this.canUseLocomotives ? this.trainCarsHand.filter(card => card.type == 0).slice(0, this.cost) : [];
            let minLocomotives = this.claimingRoute.route.canPayWithAnySetOfCards > 0 ? 0 : this.claimingRoute.route.locomotives;
            const maxLocomotives = this.canUseLocomotives ? locomotiveCards.length : 0;
            const showLocomotives = this.canUseLocomotives ? (minLocomotives > 0 || maxLocomotives > 0) : false;
            let colorCards = null;
            let minColorCards = 0;
            let maxColorCards = 0;
            if (this.claimingRoute.color > 0) {
                colorCards = this.trainCarsHand.filter(card => card.type == this.claimingRoute.color).slice(0, this.cost);
                minColorCards = this.claimingRoute.route.canPayWithAnySetOfCards > 0 ? 0 : Math.max(0, Math.min(this.cost - minLocomotives, this.cost - maxLocomotives));
                maxColorCards = Math.min(this.cost - this.claimingRoute.route.locomotives, colorCards.length);
                if (!this.claimingRoute.route.canPayWithAnySetOfCards && maxColorCards < this.cost) {
                    minLocomotives = Math.min(maxLocomotives, this.cost - maxColorCards);
                }
            }
            const showColorCards = this.claimingRoute.color > 0 && (minColorCards > 0 || maxColorCards > 0);
            const locomotiveCardsToDisplay = locomotiveCards.slice(0, maxLocomotives);
            const colorCardsToDisplay = colorCards?.slice(0, maxColorCards);
            const singleCards = [...locomotiveCardsToDisplay, ...(colorCardsToDisplay ?? [])];
            const otherCardsForSet = this.trainCarsHand.filter(card => !singleCards.some(sc => sc.id == card.id));
            const showSet = this.claimingRoute.route.canPayWithAnySetOfCards > 0 && otherCardsForSet.length >= this.claimingRoute.route.canPayWithAnySetOfCards;
            let html = ``;
            if (showLocomotives) {
                this.distributionCards[0] = [];
                if (this.claimingRoute.route.locomotives) {
                    html += `${_('${number} locomotives required').replace('${number}', `${this.claimingRoute.route.locomotives}`)}<br>`;
                }
                html += this.cardSection(locomotiveCardsToDisplay, this.claimingRoute.route.canPayWithAnySetOfCards > 0 ? null : 0);
            }
            if (showColorCards) {
                this.distributionCards[this.claimingRoute.color] = [];
                html += this.cardSection(colorCardsToDisplay, this.claimingRoute.route.canPayWithAnySetOfCards > 0 ? null : this.claimingRoute.color);
            }
            if (this.claimingRoute.route.canPayWithAnySetOfCards > 0) {
                this.distributionCards[99] = [];
                html += `${_('Any set of ${number} cards').replace('${number}', `${this.claimingRoute.route.canPayWithAnySetOfCards}`)}<br>` + this.cardSection(otherCardsForSet, null);
            }
            html += `
                <div class="total">
                    Total : <span id="distribution-current-size">0</span> / ${this.cost}
                <div>
                    <button id="confirmDistribution-btn" class="bgabutton bgabutton_blue" style="width: auto;">${_('Confirm')}</button>
                    <button id="cancelDistribution-btn" class="bgabutton bgabutton_gray" style="width: auto;">${_('Cancel')}</button>
                </div>
            `;
            distributionDlg.setContent(html);
            distributionDlg.show();
            if (showLocomotives) {
                locomotiveCardsToDisplay.forEach((card, index) => {
                    const element = document.getElementById(`distribution-${card.id}`);
                    if (index < minLocomotives) {
                        element.classList.add('selected', 'grayed');
                        this.distributionCards[0].push(card.id);
                    }
                    else {
                        element.classList.add('selectable');
                        element.addEventListener('click', () => this.onDistributionCardClick(card.id, 0));
                        if (this.claimingRoute.distribution?.includes(card.id)) {
                            element.classList.add('selected');
                            this.distributionCards[0].push(card.id);
                        }
                    }
                });
                if (!showSet) {
                    document.getElementById(`use-maximum-${0}-btn`).addEventListener('click', () => this.useMaximum(0));
                }
            }
            if (showColorCards) {
                colorCardsToDisplay.forEach((card, index) => {
                    const element = document.getElementById(`distribution-${card.id}`);
                    if (index < minColorCards) {
                        element.classList.add('selected', 'grayed');
                        this.distributionCards[this.claimingRoute.color].push(card.id);
                    }
                    else {
                        element.classList.add('selectable');
                        element.addEventListener('click', () => this.onDistributionCardClick(card.id, this.claimingRoute.color));
                        if (this.claimingRoute.distribution?.includes(card.id)) {
                            element.classList.add('selected');
                            this.distributionCards[this.claimingRoute.color].push(card.id);
                        }
                    }
                });
                if (!showSet) {
                    document.getElementById(`use-maximum-${this.claimingRoute.color}-btn`).addEventListener('click', () => this.useMaximum(this.claimingRoute.color));
                }
            }
            if (showSet) {
                otherCardsForSet.forEach(card => {
                    const element = document.getElementById(`distribution-${card.id}`);
                    element.classList.add('selectable');
                    element.addEventListener('click', () => this.onDistributionCardClick(card.id, 99));
                });
            }
            this.updateTotal();
            const closeFn = (result) => { resolve(result ? new DistributionResult(result) : null); distributionDlg.destroy(); };
            distributionDlg.replaceCloseCallback(() => closeFn(null));
            document.getElementById('confirmDistribution-btn').addEventListener('click', () => closeFn(this.distributionCards));
            document.getElementById('cancelDistribution-btn').addEventListener('click', () => closeFn(null));
            if ((minLocomotives + minColorCards) === this.cost) {
                // all possible cards are preselected, meaning the player doesn't have a choice
                resolve(new DistributionResult(this.distributionCards, true));
                distributionDlg.destroy();
            }
        });
    }
    cardSection(cards, colorForUseMaximum) {
        return `
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>${cards.map(card => `<div class="train-car-color icon" data-color="${card.type}" id="distribution-${card.id}"></div>`).join('')}</div>
                ${colorForUseMaximum !== null ? `<div><button id="use-maximum-${colorForUseMaximum}-btn" class="bgabutton bgabutton_gray" style="width: auto;">${_('Use maximum of ${color}').replace('${color}', `<div class="train-car-color icon" data-color="${colorForUseMaximum}"></div>`)}</button></div>` : ''}
            </div><hr/>
        `;
    }
    getSelectedCardCount(locomotivesAndSetOnly = false) {
        let value = this.distributionCards[0]?.length ?? 0;
        if (!locomotivesAndSetOnly && this.claimingRoute.color !== 0) {
            value += this.distributionCards[this.claimingRoute.color]?.length ?? 0;
        }
        if (this.claimingRoute.route.canPayWithAnySetOfCards && this.distributionCards[99]) {
            value += Math.floor(this.distributionCards[99].length / this.claimingRoute.route.canPayWithAnySetOfCards);
        }
        return value;
    }
    updateTotal() {
        const element = document.getElementById(`distribution-current-size`);
        const selectedCardCount = this.getSelectedCardCount();
        element.innerText = `${selectedCardCount}`;
        const validCount = selectedCardCount === this.cost;
        element.dataset.valid = JSON.stringify(validCount);
        let valid = validCount && this.getSelectedCardCount(true) >= this.claimingRoute.route.locomotives;
        if (this.claimingRoute.route.canPayWithAnySetOfCards && this.distributionCards[99]) {
            if (this.distributionCards[99].length % this.claimingRoute.route.canPayWithAnySetOfCards !== 0) {
                valid = false;
            }
        }
        document.getElementById('confirmDistribution-btn').disabled = !valid;
        const useMax0 = document.getElementById(`use-maximum-0-btn`);
        const useMaxColor = document.getElementById(`use-maximum-${this.claimingRoute.color}-btn`);
        if (useMax0) {
            useMax0.disabled = selectedCardCount >= this.cost;
        }
        if (useMaxColor) {
            useMaxColor.disabled = selectedCardCount >= this.cost;
        }
    }
    onDistributionCardClick(cardId, type) {
        const element = document.getElementById(`distribution-${cardId}`);
        if (this.distributionCards[type].includes(cardId)) {
            element.classList.remove('selected');
            this.distributionCards[type] = this.distributionCards[type].filter(id => id != cardId);
        }
        else {
            if (this.getSelectedCardCount() >= this.cost) {
                return;
            }
            element.classList.add('selected');
            this.distributionCards[type].push(cardId);
        }
        this.updateTotal();
    }
    useMaximum(color) {
        const selectedCardIds = this.distributionCards.flat();
        const cardsToSelect = this.trainCarsHand.filter(card => card.type == color && !selectedCardIds.includes(card.id)).slice(0, this.cost - selectedCardIds.length);
        cardsToSelect.forEach(card => {
            const element = document.getElementById(`distribution-${card.id}`);
            element.classList.add('selected');
            this.distributionCards[color].push(card.id);
            selectedCardIds.push(card.id);
        });
        const otherColor = color > 0 ? 0 : this.claimingRoute.color;
        const otherCardsToSelect = this.trainCarsHand.filter(card => card.type == otherColor && !selectedCardIds.includes(card.id)).slice(0, this.cost - selectedCardIds.length);
        otherCardsToSelect.forEach(card => {
            const element = document.getElementById(`distribution-${card.id}`);
            element.classList.add('selected');
            this.distributionCards[color].push(card.id);
        });
        this.updateTotal();
    }
}

const LOCOMOTIVE_TUNNEL = 0b01;
const LOCOMOTIVE_FERRY = 0b10;
/**
 * The state handles multiple "sub-states" in case of route selection, in this order :
 * - ask double route
 * - ask color
 * - ask number of locomotives to use
 * - ask confirmation
 */
class ChooseActionState {
    constructor(game, bga) {
        this.claimingRoute = null;
        this.cityToConfirm = null;
        this.isTouch = window.matchMedia('(hover: none)').matches;
        this.game = game;
        this.bga = bga;
    }
    /**
     * Show selectable routes, and make train car draggable.
     */
    onEnteringState(args, isCurrentPlayerActive) {
        this.game.trainCarSelection.setSelectableTopDeck(isCurrentPlayerActive, args.maxHiddenCardsPick);
        this.game.map.setSelectableRoutes(isCurrentPlayerActive, args.possibleRoutes);
        this.game.map.setSelectableStations(isCurrentPlayerActive, args.possibleStations);
        this.game.playerTable?.setDraggable(isCurrentPlayerActive);
        this.game.playerTable?.setSelectable(isCurrentPlayerActive);
        if (isCurrentPlayerActive) {
            if (args.maxDestinationsPick) {
                document.getElementById('destination-deck-hidden-pile').classList.add('selectable');
            }
        }
        this.setActionBarChooseAction(isCurrentPlayerActive);
    }
    onLeavingState(args, isCurrentPlayerActive) {
        this.game.map.setHoveredRoute(null);
        this.game.map.setHoveredCity(null);
        this.game.map.setSelectableRoutes(false, []);
        this.game.map.setSelectableStations(false, []);
        this.game.playerTable?.setDraggable(false);
        this.game.playerTable?.setSelectable(false);
        this.game.playerTable?.setSelectableTrainCarColors(null);
        document.getElementById('destination-deck-hidden-pile').classList.remove('selectable');
        Array.from(document.getElementsByClassName('train-car-group hide')).forEach(group => group.classList.remove('hide'));
    }
    /**
     * Sets the action bar (title and buttons) for Choose action.
     */
    setActionBarChooseAction(isCurrentPlayerActive) {
        if (this.args.canBuildStation) {
            if (!this.args.canTakeTrainCarCards) {
                this.bga.statusBar.setTitle(isCurrentPlayerActive ?
                    _('${you} must claim a route, build a station or draw destination tickets') :
                    _('${actplayer} must claim a route, build a station or draw destination tickets'), this.args);
            }
            else {
                this.bga.statusBar.setTitle(isCurrentPlayerActive ?
                    _('${you} must draw train car cards, claim a route, build a station or draw destination tickets') :
                    _('${actplayer} must draw train car cards, claim a route, build a station or draw destination tickets'), this.args);
            }
        }
        else {
            if (!this.args.canTakeTrainCarCards) {
                this.bga.statusBar.setTitle(isCurrentPlayerActive ?
                    _('${you} must claim a route or draw destination tickets') :
                    _('${actplayer} must claim a route or draw destination tickets'), this.args);
            }
            else {
                this.bga.statusBar.setTitle(isCurrentPlayerActive ?
                    _('${you} must draw train car cards, claim a route or draw destination tickets') :
                    _('${actplayer} must draw train car cards, claim a route or draw destination tickets'), this.args);
            }
        }
        if (isCurrentPlayerActive) {
            this.bga.statusBar.removeActionButtons();
            this.bga.statusBar.addActionButton(dojo.string.substitute(_("Draw ${number} destination tickets"), { number: this.args.maxDestinationsPick }), () => this.game.drawDestinations(), { color: 'alert', disabled: !this.args.maxDestinationsPick });
            if (this.args.canPass) {
                // Pass (only in case of no possible action)
                this.bga.statusBar.addActionButton(_("Pass"), () => this.bga.actions.performAction('actPass'));
            }
        }
    }
    setActionBarAskDoubleRoad(clickedRoute, otherRoute) {
        const question = _("Which part of the double route do you want to claim?");
        this.bga.statusBar.setTitle(question);
        this.bga.statusBar.removeActionButtons();
        [clickedRoute, otherRoute].forEach(route => {
            this.bga.statusBar.addActionButton(`<div class="train-car-color icon" data-color="${route.color}"></div> ${getColor(route.color, 'train-car')}`, () => this.clickedRouteDoubleRouteConfirmed(route));
        });
        this.bga.statusBar.addActionButton(_("Cancel"), () => this.cancelRouteClaim(), { color: 'secondary' });
    }
    /**
     * Ask confirmation for claimed route.
     */
    clickedRouteColorChosen(route, color) {
        const selectedColor = this.game.playerTable.getSelectedColor();
        if (route.color !== 0 && selectedColor !== null && selectedColor !== 0 && route.color !== selectedColor) {
            const otherRoute = Object.values(this.game.getMap().routes).find(r => route.from == r.from && route.to == r.to && route.id != r.id);
            if (otherRoute.color === selectedColor) {
                this.clickedRouteColorChosen(otherRoute, selectedColor);
            }
            return;
        }
        this.claimingRoute = { route, color, distribution: null };
        this.clickedRouteDistributionChosen();
    }
    clickedRouteDistributionChosen() {
        if (this.confirmRouteClaimActive()) {
            this.game.map.setHoveredRoute(this.claimingRoute.route, true);
            this.setActionBarConfirmRouteClaim();
        }
        else {
            this.claimRoute();
        }
    }
    /**
     * Sets the action bar (title and buttons) for the color station.
     */
    setActionBarChooseColorStation(city, possibleColors) {
        const confirmationQuestion = _("Choose color for the station on ${city}")
            .replace('${city}', city.name);
        this.bga.statusBar.setTitle(confirmationQuestion);
        this.bga.statusBar.removeActionButtons();
        possibleColors.forEach(color => {
            const label = dojo.string.substitute(_("Use ${color}"), {
                'color': `<div class="train-car-color icon" data-color="${color}"></div> ${getColor(color, 'train-car')}`
            });
            this.bga.statusBar.addActionButton(label, () => this.clickedCityColorChosen(city, color));
        });
        this.bga.statusBar.addActionButton(_("Cancel"), () => this.cancelRouteClaim(), { color: 'secondary' });
    }
    /**
     * Sets the action bar (title and buttons) for Confirm city claim.
     */
    setActionBarConfirmStation(city, color) {
        const chooseActionArgs = this.game.gamedatas.gamestate.args;
        const colors = chooseActionArgs.costForStation[color].map(cardColor => `<div class="train-car-color icon" data-color="${cardColor}"></div>`);
        const confirmationQuestion = _("Confirm station on ${city_name} with ${colors} ?")
            .replace('${city_name}', this.game.getCityName(city.id))
            .replace('${colors}', `<div class="color-cards">${colors.join('')}</div>`);
        this.bga.statusBar.setTitle(confirmationQuestion);
        this.bga.statusBar.removeActionButtons();
        this.bga.statusBar.addActionButton(_("Confirm station"), () => this.confirmStation(), { autoclick: this.bga.userPreferences.get(207) != 2 });
        this.bga.statusBar.addActionButton(_("Cancel"), () => this.cancelStation(), { color: 'secondary' });
    }
    /**
     * Ask confirmation for claimed city.
     */
    clickedCityColorChosen(city, color) {
        this.cityToConfirm = { city, color };
        this.game.map.setHoveredCity(city, true);
        this.setActionBarConfirmStation(city, color);
    }
    /**
     * Player cancels claimed route.
     */
    cancelRouteClaim() {
        this.setActionBarChooseAction(this.bga.players.isCurrentPlayerActive());
        this.game.map.setHoveredRoute(null);
        this.game.playerTable?.setSelectableTrainCarColors(null);
        this.claimingRoute = null;
        document.querySelectorAll(`[id^="claimRouteWithColor_button"]`).forEach(button => button.parentElement?.removeChild(button));
    }
    /**
     * Player cancels claimed city.
     */
    cancelStation() {
        this.setActionBarChooseAction(true);
        this.game.map.setHoveredCity(null);
        this.game.playerTable?.setSelectableTrainCarColors(null);
        this.cityToConfirm = null;
        document.querySelectorAll(`[id^="claimCityWithColor_button"]`).forEach(button => button.parentElement?.removeChild(button));
    }
    /**
     * Player confirms claimed route.
     */
    confirmRouteClaim() {
        this.game.map.setHoveredRoute(null);
        this.claimRoute();
    }
    /**
     * Player confirms claimed city.
     */
    confirmStation() {
        this.game.map.setHoveredCity(null);
        this.buildStation(this.cityToConfirm.city.id, this.cityToConfirm.color);
    }
    /**
     * Handle route click.
     */
    clickedRoute(route) {
        if (!this.bga.players.isCurrentPlayerActive()) {
            return;
        }
        const needToCheckDoubleRoute = this.askDoubleRouteActive();
        const otherRoute = Object.values(this.game.getMap().routes).find(r => route.from == r.from && route.to == r.to && route.id != r.id);
        let askDoubleRouteColor = needToCheckDoubleRoute && otherRoute && otherRoute.color != route.color && this.canClaimRoute(route, 0) && this.canClaimRoute(otherRoute, 0);
        if (askDoubleRouteColor) {
            const selectedColor = this.game.playerTable.getSelectedColor();
            if (selectedColor) {
                askDoubleRouteColor = false;
            }
        }
        if (askDoubleRouteColor) {
            this.setActionBarAskDoubleRoad(route, otherRoute);
            return;
        }
        if (!this.canClaimRoute(route, 0)) {
            return;
        }
        this.clickedRouteDoubleRouteConfirmed(route);
    }
    /**
     * Check if a route can be claimed with dragged cards.
     */
    canClaimCity(city, cardsColor) {
        return this.args.possibleStations.some(ps => ps.id == city.id);
    }
    /**
     * Handle city click.
     */
    clickedCity(city) {
        if (!this.bga.players.isCurrentPlayerActive()) {
            return;
        }
        if (!this.canClaimCity(city, 0)) {
            return;
        }
        this.game.map.setHoveredCity(null);
        document.querySelectorAll(`[id^="claimRouteWithColor_button"]`).forEach(button => button.parentElement.removeChild(button));
        const selectedColor = this.game.playerTable.getSelectedColor();
        if (selectedColor !== null) {
            this.clickedCityColorChosen(city, selectedColor);
        }
        else {
            const possibleColors = [];
            const costForStation = this.args.costForStation;
            if (costForStation) {
                for (let i = 0; i <= 8; i++) {
                    if (costForStation[i]) {
                        possibleColors.push(i);
                    }
                }
            }
            if (possibleColors.length == 1) {
                this.clickedCityColorChosen(city, possibleColors[0]);
            }
            else if (possibleColors.length > 1) {
                this.setActionBarChooseColorStation(city, possibleColors);
                this.game.playerTable.setSelectableTrainCarColorsForStation(city, possibleColors);
            }
        }
    }
    showDistributionPopin(route) {
        const locomotiveRestriction = this.game.getMap().locomotiveUsageRestriction;
        const canUseLocomotives = locomotiveRestriction === 0
            || ((locomotiveRestriction & LOCOMOTIVE_TUNNEL) !== 0 && route.tunnel)
            || ((locomotiveRestriction & LOCOMOTIVE_FERRY) !== 0 && route.locomotives > 0);
        return route.canPayWithAnySetOfCards > 0 || (locomotiveRestriction && canUseLocomotives);
    }
    clickedRouteDoubleRouteConfirmed(route) {
        document.querySelectorAll(`[id^="claimRouteWithColor_button"]`).forEach(button => button.parentElement.removeChild(button));
        const showDistributionPopin = this.showDistributionPopin(route);
        if ((route.color === 0 || route.tunnel || showDistributionPopin) && this.game.playerTable.getSelectedColor() === null) {
            const possibleColors = [];
            const costForRoute = this.args.costForRoute[route.id];
            if (costForRoute) {
                for (let i = 0; i <= 8; i++) {
                    if (costForRoute[i]) {
                        possibleColors.push(i);
                    }
                }
            }
            // do not filter for tunnel, or if locomotive is the only possibility
            const possibleColorsWithoutLocomotives = route.tunnel || possibleColors.length <= 1 ?
                possibleColors :
                possibleColors.filter(color => color != 0);
            if (showDistributionPopin || possibleColorsWithoutLocomotives.length > 1) {
                this.setActionBarChooseColor(route, possibleColorsWithoutLocomotives, showDistributionPopin);
                this.game.playerTable.setSelectableTrainCarColors(route, possibleColorsWithoutLocomotives);
                return;
            }
            else {
                this.clickedRouteColorChosen(route, possibleColorsWithoutLocomotives[0]);
                return;
            }
        }
        this.clickedRouteColorChosen(route, this.game.playerTable.getSelectedColor() ?? route.color);
    }
    /**
     * Sets the action bar (title and buttons) for the color route.
     */
    setActionBarChooseColor(route, possibleColors, showDistributionPopin) {
        const confirmationQuestion = _("Choose color for the route from ${from} to ${to}")
            .replace('${from}', this.game.getCityName(route.from))
            .replace('${to}', this.game.getCityName(route.to));
        this.bga.statusBar.setTitle(confirmationQuestion);
        this.bga.statusBar.removeActionButtons();
        possibleColors.forEach(color => {
            const label = dojo.string.substitute(_("Use ${color}"), {
                'color': `<div class="train-car-color icon" data-color="${color}"></div> ${getColor(color, 'train-car')}`
            });
            this.bga.statusBar.addActionButton(label, () => this.clickedRouteColorChosen(route, color), { id: `claimRouteWithColor_button${color}`, });
        });
        if (showDistributionPopin) {
            this.claimingRoute = { route, color: route.color, distribution: null };
            this.bga.statusBar.addActionButton(_("Custom..."), () => {
                const locomotiveRestriction = this.game.getMap().locomotiveUsageRestriction;
                const canUseLocomotives = locomotiveRestriction === 0
                    || ((locomotiveRestriction & LOCOMOTIVE_TUNNEL) !== 0 && route.tunnel)
                    || ((locomotiveRestriction & LOCOMOTIVE_FERRY) !== 0 && route.locomotives > 0);
                new DistributionPopin(this.args._private.trainCarsHand, this.claimingRoute, this.claimingRoute.route.spaces.length, canUseLocomotives).show(confirmationQuestion).then((distribution) => {
                    if (distribution) {
                        if (distribution.locomotivesOnly) {
                            this.claimingRoute.color = 0;
                        }
                        this.claimingRoute.distribution = distribution.cardIds;
                        if (distribution.auto) {
                            this.clickedRouteDistributionChosen();
                        }
                        else {
                            // do not ask for confirmation, the popin is a kind of confirmation
                            this.claimRoute();
                        }
                    }
                    else {
                        this.cancelRouteClaim();
                    }
                });
            }, { id: `claimRouteWithColor_button99`, });
        }
        this.bga.statusBar.addActionButton(_("Cancel"), () => this.cancelRouteClaim(), { color: 'secondary' });
    }
    /**
     * Sets the action bar (title and buttons) for Confirm route claim.
     */
    setActionBarConfirmRouteClaim() {
        const colors = this.args.costForRoute[this.claimingRoute.route.id][this.claimingRoute.color].map(cardColor => `<div class="train-car-color icon" data-color="${cardColor}"></div>`);
        const confirmationQuestion = _("Confirm ${color} route from ${from} to ${to} with ${colors} ?")
            .replace('${color}', getColor(this.claimingRoute.route.color, 'route'))
            .replace('${from}', this.game.getCityName(this.claimingRoute.route.from))
            .replace('${to}', this.game.getCityName(this.claimingRoute.route.to))
            .replace('${colors}', `<div class="color-cards">${colors.join('')}</div>`);
        this.bga.statusBar.setTitle(confirmationQuestion);
        this.bga.statusBar.removeActionButtons();
        this.bga.statusBar.addActionButton(_("Confirm"), () => this.confirmRouteClaim(), { id: `confirmRouteClaim-button`, autoclick: this.bga.userPreferences.get(207) != 2 });
        this.bga.statusBar.addActionButton(_("Cancel"), () => this.cancelRouteClaim(), { color: 'secondary' });
    }
    /**
     * Claim a route.
     */
    claimRoute() {
        this.bga.actions.performAction('actClaimRoute', {
            routeId: this.claimingRoute.route.id,
            color: this.claimingRoute.color,
            distribution: this.claimingRoute.distribution,
        });
    }
    /**
     * Claim a city.
     */
    buildStation(cityId, color) {
        this.bga.actions.performAction('actBuildStation', {
            cityId,
            color
        });
    }
    /**
     * Check if a route can be claimed with dragged cards.
     */
    canClaimRoute(route, cardsColor) {
        return (route.color == 0 || cardsColor == 0 || route.color == cardsColor) && (this.args.possibleRoutes.some(pr => pr.id == route.id));
    }
    /**
     * Check if player should be asked for a route claim confirmation.
     */
    confirmRouteClaimActive() {
        const preferenceValue = this.bga.userPreferences.get(202);
        return preferenceValue === 1 || (preferenceValue === 2 && this.isTouch);
    }
    /**
     * Check if player should be asked for the color he wants when he clicks on a double route.
     */
    askDoubleRouteActive() {
        const preferenceValue = this.bga.userPreferences.get(209);
        return preferenceValue === 1;
    }
}

class ConfirmTunnelState {
    constructor(game, bga) {
        this.game = game;
        this.bga = bga;
    }
    onEnteringState(args, isCurrentPlayerActive) {
        const route = this.game.getMap().routes[args.tunnelAttempt.routeId];
        this.game.map.setHoveredRoute(route, true, this.game.gamedatas.players[args.playerId]);
        this.game.trainCarSelection.showTunnelCards(args.tunnelAttempt.tunnelCards);
        if (isCurrentPlayerActive) {
            const confirmLabel = _("Confirm tunnel claim") + (args.canPay ? '' : ` (${_("You don't have enough cards")})`);
            // Claim a tunnel (confirm paying extra cost).
            this.bga.statusBar.addActionButton(confirmLabel, () => {
                if (args.tunnelAttempt.distribution) {
                    const locomotiveRestriction = this.game.getMap().locomotiveUsageRestriction;
                    const canUseLocomotives = locomotiveRestriction === 0
                        || ((locomotiveRestriction & LOCOMOTIVE_TUNNEL) !== 0 && route.tunnel)
                        || ((locomotiveRestriction & LOCOMOTIVE_FERRY) !== 0 && route.locomotives > 0);
                    const confirmationQuestion = _("Cards including extra cost");
                    new DistributionPopin(args._private.trainCarsHand, { route, color: args.tunnelAttempt.color, distribution: args.tunnelAttempt.distribution }, args.tunnelAttempt.distribution.length + args.tunnelAttempt.extraCards, canUseLocomotives).show(confirmationQuestion).then((distribution) => {
                        if (distribution) {
                            this.bga.actions.performAction('actClaimTunnel', { distribution: distribution.cardIds });
                        }
                    });
                }
                else {
                    this.bga.actions.performAction('actClaimTunnel');
                }
            }, { disabled: !args.canPay });
            // Skip a tunnel (deny paying extra cost).
            this.bga.statusBar.addActionButton(_("Skip tunnel claim"), () => this.bga.actions.performAction('actSkipTunnel'), { color: 'secondary' });
        }
    }
    onLeavingState(args, isCurrentPlayerActive) {
        this.game.map.setHoveredRoute(null);
        this.game.trainCarSelection.showTunnelCards([]);
    }
}

class DrawSecondCardState {
    constructor(game, bga) {
        this.game = game;
        this.bga = bga;
    }
    /**
     * Allow to pick a second card (locomotives will be grayed).
     */
    onEnteringState(args, isCurrentPlayerActive) {
        this.game.trainCarSelection.setSelectableTopDeck(isCurrentPlayerActive, args.maxHiddenCardsPick);
        this.game.trainCarSelection.setSelectableVisibleCards(args.availableVisibleCards);
    }
    onLeavingState(args, isCurrentPlayerActive) {
        this.game.trainCarSelection.removeSelectableVisibleCards();
    }
}

/**
 * Animation to move a card to a player's counter (the destroy animated card).
 */
function animateCardToCounterAndDestroy(game, cardOrCardId, destinationId) {
    const card = typeof (cardOrCardId) === 'string' ? document.getElementById(cardOrCardId) : cardOrCardId;
    card.classList.add('animated', 'transform-origin-top-left');
    const cardBR = card.getBoundingClientRect();
    const toBR = document.getElementById(destinationId).getBoundingClientRect();
    const zoom = game.getZoom();
    const x = (toBR.x - cardBR.x) / zoom;
    const y = (toBR.y - cardBR.y) / zoom;
    card.style.transform = `translate(${x}px, ${y}px) scale(${0.15 / zoom})`;
    setTimeout(() => card.parentElement?.removeChild(card), 500);
}

/**
 * Selection of new train cars.
 */
class VisibleCardSpot {
    /**
     * Init stocks and gauges.
     */
    constructor(game, spotNumber) {
        this.game = game;
        this.spotNumber = spotNumber;
        this.spotDiv = document.getElementById(`visible-train-cards-stock${this.spotNumber}`);
    }
    /**
     * Set selectable visible cards (locomotive can't be selected if 1 visible card has been picked).
     */
    setSelectableVisibleCards(availableVisibleCards) {
        this.getCardDiv()?.classList.toggle('disabled', !availableVisibleCards.some(card => card.id == this.card?.id));
    }
    /**
     * Reset visible cards state.
     */
    removeSelectableVisibleCards() {
        this.getCardDiv()?.classList.remove('disabled');
    }
    /**
     * Set new visible cards.
     */
    setNewCardOnTable(card, fromDeck) {
        if (this.card) {
            const oldCardDiv = this.getCardDiv();
            if (oldCardDiv?.closest(`#visible-train-cards-stock${this.spotNumber}`)) {
                oldCardDiv.parentElement.removeChild(oldCardDiv);
                this.card = null;
            }
        }
        // make sure there is no card remaining on the spot
        while (this.spotDiv.childElementCount > 0) {
            this.spotDiv.removeChild(this.spotDiv.firstElementChild);
        }
        this.card = card;
        if (card) { // the card 
            this.createCard(card);
            const cardDiv = this.getCardDiv();
            setupTrainCarCardDiv(cardDiv, card.type);
            cardDiv.classList.add('selectable');
            cardDiv.addEventListener('click', () => {
                if (!cardDiv.classList.contains('disabled')) {
                    this.game.onVisibleTrainCarCardClick(this.card.id);
                }
            });
            if (fromDeck) {
                this.addAnimationFrom(cardDiv);
            }
        }
    }
    /**
     * Get card div in the spot.
     */
    getCardDiv() {
        if (!this.card) {
            return null;
        }
        return document.getElementById(`train-car-card-${this.card.id}`);
    }
    /**
     * Create the card in the spot.
     */
    createCard(card) {
        this.spotDiv.insertAdjacentHTML('beforeend', `
            <div id="train-car-card-${card.id}" class="train-car-card" data-color="${this.card.type}"></div>
        `);
    }
    /**
     * Animation when train car cards are picked by another player.
     */
    moveTrainCarCardToPlayerBoard(playerId) {
        this.createCard(this.card);
        const cardDiv = this.getCardDiv();
        if (cardDiv) {
            animateCardToCounterAndDestroy(this.game, cardDiv, `train-car-card-counter-${playerId}-wrapper`);
        }
    }
    /**
     * Get visible card color.
     */
    getVisibleColor() {
        return this.card?.type;
    }
    /**
     * Add an animation to the card (when it is created).
     */
    addAnimationFrom(card) {
        if (!this.game.bga.gameui.bgaAnimationsActive()) {
            return;
        }
        const from = document.getElementById('train-car-deck-hidden-pile');
        const destinationBR = card.getBoundingClientRect();
        const originBR = from.getBoundingClientRect();
        const deltaX = destinationBR.left - originBR.left;
        const deltaY = destinationBR.top - originBR.top;
        card.style.zIndex = '10';
        const zoom = this.game.getZoom();
        card.style.transform = `translate(${-deltaX / zoom}px, ${-deltaY / zoom}px)`;
        setTimeout(() => {
            card.style.transition = `transform 0.5s linear`;
            card.style.transform = null;
        });
        setTimeout(() => {
            card.style.zIndex = null;
            card.style.transition = null;
        }, 500);
    }
}

const DBL_CLICK_TIMEOUT = 300;
/**
 * Level of cards in deck indicator.
 */
class Gauge {
    constructor(containerId, className, max) {
        this.max = max;
        document.getElementById(containerId).insertAdjacentHTML('beforeend', `
        <div id="gauge-${className}" class="gauge ${className}">
            <div class="inner" id="gauge-${className}-level"></div>
        </div>`);
        this.levelDiv = document.getElementById(`gauge-${className}-level`);
    }
    setCount(count) {
        this.levelDiv.style.height = `${100 * count / this.max}%`;
    }
}
/**
 * Selection of new train cars.
 */
class TrainCarSelection {
    /**
     * Init stocks and gauges.
     */
    constructor(game, visibleCards, trainCarDeckCount, destinationDeckCount, trainCarDeckMaxCount, destinationDeckMaxCount) {
        this.game = game;
        this.visibleCardsSpots = [];
        this.dblClickTimeout = null;
        document.getElementById('destination-deck-hidden-pile').addEventListener('click', () => this.game.drawDestinations());
        document.getElementById('train-car-deck-hidden-pile1').addEventListener('click', () => this.game.onHiddenTrainCarDeckClick(1));
        document.getElementById('train-car-deck-hidden-pile2').addEventListener('click', () => this.game.onHiddenTrainCarDeckClick(2));
        document.getElementById('train-car-deck-hidden-pile').addEventListener('click', () => {
            if (this.dblClickTimeout) {
                clearTimeout(this.dblClickTimeout);
                this.dblClickTimeout = null;
                this.game.onHiddenTrainCarDeckClick(2);
            }
            else if (!dojo.hasClass('train-car-deck-hidden-pile', 'buttonselection')) {
                this.dblClickTimeout = setTimeout(() => {
                    this.game.onHiddenTrainCarDeckClick(1);
                    this.dblClickTimeout = null;
                }, DBL_CLICK_TIMEOUT);
            }
        });
        for (let i = 1; i <= 5; i++) {
            this.visibleCardsSpots[i] = new VisibleCardSpot(game, i);
        }
        this.setNewCardsOnTable(visibleCards, false);
        this.trainCarGauge = new Gauge('train-car-deck-hidden-pile', 'train-car', trainCarDeckMaxCount);
        this.destinationGauge = new Gauge('destination-deck-hidden-pile', 'destination', destinationDeckMaxCount);
        this.setTrainCarCount(trainCarDeckCount);
        this.setDestinationCount(destinationDeckCount);
    }
    /**
     * Set selection of hidden cards deck.
     */
    setSelectableTopDeck(selectable, number = 0) {
        dojo.toggleClass('train-car-deck-hidden-pile', 'selectable', selectable);
        dojo.toggleClass('train-car-deck-hidden-pile1', 'hidden', number < 1);
        dojo.toggleClass('train-car-deck-hidden-pile2', 'hidden', number < 2);
    }
    /**
     * Set selectable visible cards (locomotive can't be selected if 1 visible card has been picked).
     */
    setSelectableVisibleCards(availableVisibleCards) {
        for (let i = 1; i <= 5; i++) {
            this.visibleCardsSpots[i].setSelectableVisibleCards(availableVisibleCards);
        }
    }
    /**
     * Reset visible cards state.
     */
    removeSelectableVisibleCards() {
        for (let i = 1; i <= 5; i++) {
            this.visibleCardsSpots[i].removeSelectableVisibleCards();
        }
    }
    /**
     * Set new visible cards.
     */
    setNewCardsOnTable(spotsCards, fromDeck) {
        Object.keys(spotsCards).forEach(spot => {
            const card = spotsCards[spot];
            this.visibleCardsSpots[spot].setNewCardOnTable(card, fromDeck);
        });
    }
    /**
     * Update train car gauge.
     */
    setTrainCarCount(count) {
        this.trainCarGauge.setCount(count);
        document.getElementById(`train-car-deck-level`).dataset.level = `${Math.min(10, Math.ceil(count / 10))}`;
    }
    /**
     * Update destination gauge.
     */
    setDestinationCount(count) {
        this.destinationGauge.setCount(count);
        document.getElementById(`destination-deck-level`).dataset.level = `${Math.min(10, Math.ceil(count / 10))}`;
    }
    /**
     * Make hidden train car cads selection buttons visible (user preference).
     */
    setCardSelectionButtons(visible) {
        dojo.toggleClass('train-car-deck-hidden-pile', 'buttonselection', visible);
    }
    /**
     * Get HTML Element represented by "origin" (0 means invisible, 1 to 5 are visible cards).
     */
    getStockElement(origin) {
        return origin === 0 ? document.getElementById('train-car-deck-hidden-pile') : document.getElementById(`visible-train-cards-stock${origin}`);
    }
    /**
     * Animation when train car cards are picked by another player.
     */
    moveTrainCarCardToPlayerBoard(playerId, from, number = 1) {
        if (from > 0) {
            this.visibleCardsSpots[from].moveTrainCarCardToPlayerBoard(playerId);
        }
        else {
            for (let i = 0; i < number; i++) {
                setTimeout(() => {
                    document.getElementById('train-car-deck-hidden-pile').insertAdjacentHTML('beforeend', `
                        <div id="animated-train-car-card-0-${i}" class="animated train-car-card from-hidden-pile"></div>
                    `);
                    animateCardToCounterAndDestroy(this.game, `animated-train-car-card-0-${i}`, `train-car-card-counter-${playerId}-wrapper`);
                }, 200 * i);
            }
        }
    }
    /**
     * Animation when destination cards are picked by another player.
     */
    moveDestinationCardToPlayerBoard(playerId, number) {
        for (let i = 0; i < number; i++) {
            setTimeout(() => {
                document.getElementById('destination-deck-hidden-pile').insertAdjacentHTML('beforeend', `
                <div id="animated-destination-card-${i}" class="animated-destination-card"></div>
                `);
                animateCardToCounterAndDestroy(this.game, `animated-destination-card-${i}`, `destinations-counter-${playerId}-wrapper`);
            }, 200 * i);
        }
    }
    /**
     * List visible cards colors.
     */
    getVisibleColors() {
        return this.visibleCardsSpots.map(stock => stock.getVisibleColor());
    }
    /**
     * Animate the 3 visible locomotives (bump) before they are replaced.
     */
    highlightVisibleLocomotives() {
        this.visibleCardsSpots.filter(stock => stock.getVisibleColor() === 0).forEach(stock => {
            const cardDiv = stock.getCardDiv();
            if (cardDiv) {
                cardDiv.classList.remove('highlight-locomotive');
                cardDiv.classList.add('highlight-locomotive');
            }
        });
    }
    /**
     * Show the 3 cards drawn for the tunnel claim. Clear them if called with empty array.
     */
    showTunnelCards(tunnelCards) {
        if (tunnelCards?.length) {
            document.getElementById('train-car-deck-hidden-pile').insertAdjacentHTML('beforeend', `<div id="tunnel-cards"></div>`);
            tunnelCards.forEach((card, index) => {
                document.getElementById('tunnel-cards').insertAdjacentHTML('beforeend', `<div id="tunnel-card-${index}" class="train-car-card tunnel-card animated" data-color="${card.type}"></div>`);
                const element = document.getElementById(`tunnel-card-${index}`);
                const shift = 75 * (this.game.bga.userPreferences.get(205) == 2 ? -1 : 1);
                setTimeout(() => element.style.transform = `translateY(${105 * (index - 1) + shift}px) scale(0.6)`);
            });
        }
        else {
            this.game.bga.gameui.fadeOutAndDestroy('tunnel-cards');
            //document.getElementById('tunnel-cards')?.remove();
        }
    }
}

const ANIMATION_MS = 500;
class Game {
    constructor(bga) {
        this.playerTable = null;
        this.trainCarCounters = [];
        this.stationCounters = [];
        this.trainCarCardCounters = [];
        this.destinationCardCounters = [];
        this.animations = [];
        this.TOOLTIP_DELAY = document.body.classList.contains('touch-device') ? 1500 : undefined;
        this.distributionCards = null;
        this.bga = bga;
        this.chooseActionState = new ChooseActionState(this, bga);
        this.bga.states.register('chooseAction', this.chooseActionState);
        this.bga.states.register('drawSecondCard', new DrawSecondCardState(this, bga));
        this.bga.states.register('confirmTunnel', new ConfirmTunnelState(this, bga));
        this.bga.userPreferences.onChange = (id, val) => this.onUserPreferenceChanged(id, val);
    }
    /*
        setup:

        This method must set up the game user interface according to current game situation specified
        in parameters.

        The method is called each time the game interface is displayed to a player, ie:
        _ when the game starts
        _ when a player refreshes the game page (F5)

        "gamedatas" argument contains all datas retrieved by your "getAllDatas" PHP method.
    */
    setup(gamedatas) {
        this.gamedatas = gamedatas;
        const map = this.getMap();
        Object.entries(map.cities).forEach(entry => entry[1].id = Number(entry[0]));
        Object.entries(map.routes).forEach(entry => entry[1].id = Number(entry[0]));
        Object.entries(map.destinations).forEach(typeEntry => Object.entries(typeEntry[1]).forEach(entry => entry[1].id = Number(entry[0])));
        document.getElementById(`score`).insertAdjacentHTML(`beforebegin`, `<link rel="stylesheet" type="text/css" href="${g_gamethemeurl}img/${map.code}/map.css"/>`);
        this.bga.images.preloadImages([
            `${map.code}/map.jpg`,
            ...map.preloadImages.map(filename => `${map.code}/${filename}`)
        ]);
        console.log("Starting game setup");
        this.gamedatas = gamedatas;
        console.log('gamedatas', gamedatas);
        this.map = new TtrMap(this, map, Object.values(gamedatas.players), gamedatas.claimedRoutes, gamedatas.builtStations, gamedatas.map.illustration);
        this.trainCarSelection = new TrainCarSelection(this, gamedatas.visibleTrainCards, gamedatas.trainCarDeckCount, gamedatas.destinationDeckCount, gamedatas.trainCarDeckMaxCount, gamedatas.destinationDeckMaxCount);
        this.destinationSelection = new DestinationSelection(this, map);
        const player = gamedatas.players[this.getPlayerId()];
        if (player) {
            this.playerTable = new PlayerTable(this, player, gamedatas.handTrainCars, gamedatas.handDestinations, gamedatas.completedDestinations);
        }
        this.createPlayerPanels(gamedatas);
        if (gamedatas.lastTurn) {
            this.notif_lastTurn(false);
        }
        if (Number(gamedatas.gamestate.id) >= 90) { // score or end
            this.onEnteringEndScore(true);
        }
        this.setupNotifications();
        this.bga.gameui.onScreenWidthChange = () => this.map.setAutoZoom();
        if (this.gamedatas.map.multilingualPdfRulesUrl || this.gamedatas.map.rulesDifferences) {
            this.bga.statusBar.addActionButton(_('Rules differences between USA and current map'), () => this.createRulesPopin(), { id: 'rules-differences-btn', destination: document.getElementById(`player_boards`) });
        }
        if (this.gamedatas.map.vertical) {
            document.body.classList.add('vertical-map');
        }
        console.log("Ending game setup");
    }
    ///////////////////////////////////////////////////
    //// Game & client states
    // onEnteringState: this method is called each time we are entering into a new game state.
    //                  You can use this method to perform some user interface changes at this moment.
    //
    onEnteringState(stateName, args) {
        console.log('Entering state: ' + stateName, args.args);
        switch (stateName) {
            case 'privateChooseInitialDestinations':
            case 'chooseInitialDestinations':
            case 'chooseAdditionalDestinations':
                if (args?.args) {
                    const chooseDestinationsArgs = args.args;
                    const destinations = chooseDestinationsArgs.destinations || chooseDestinationsArgs._private?.destinations;
                    if (destinations && this.bga.players.isCurrentPlayerActive()) {
                        destinations.forEach(destination => this.map.setSelectableDestination(destination, true));
                        this.destinationSelection.setCards(destinations, chooseDestinationsArgs.minimum, this.trainCarSelection.getVisibleColors());
                        this.destinationSelection.selectionChange();
                    }
                }
                break;
            case 'endScore':
                this.onEnteringEndScore();
                break;
        }
    }
    /**
     * Show score board.
     */
    onEnteringEndScore(fromReload = false) {
        this.bga.gameArea.removeLastTurnBanner();
        document.getElementById('score').style.display = 'flex';
        this.endScore = new EndScore(this, Object.values(this.gamedatas.players), fromReload, this.gamedatas.bestScore);
    }
    // onLeavingState: this method is called each time we are leaving a game state.
    //                 You can use this method to perform some user interface changes at this moment.
    //
    onLeavingState(stateName) {
        console.log('Leaving state: ' + stateName);
        switch (stateName) {
            case 'privateChooseInitialDestinations':
            case 'chooseInitialDestinations':
            case 'chooseAdditionalDestinations':
                this.destinationSelection.hide();
                const mapDiv = document.getElementById('map');
                mapDiv.querySelectorAll(`.city[data-selectable]`).forEach((city) => city.dataset.selectable = 'false');
                mapDiv.querySelectorAll(`.city[data-selected]`).forEach((city) => city.dataset.selected = 'false');
                break;
            case 'multiChooseInitialDestinations':
                this.destinationSelection.hide();
                Array.from(document.getElementsByClassName('player-turn-order')).forEach(elem => elem.remove());
                break;
        }
    }
    // onUpdateActionButtons: in this method you can manage "action buttons" that are displayed in the
    //                        action status bar (ie: the HTML links in the status bar).
    //
    onUpdateActionButtons(stateName, args) {
        if (this.bga.players.isCurrentPlayerActive()) {
            switch (stateName) {
                case 'privateChooseInitialDestinations':
                    this.bga.statusBar.addActionButton(_("Keep selected destinations"), () => this.chooseInitialDestinations(), { id: 'chooseInitialDestinations_button' });
                    this.destinationSelection.selectionChange();
                    break;
                case 'chooseAdditionalDestinations':
                    this.bga.statusBar.addActionButton(_("Keep selected destinations"), () => this.chooseAdditionalDestinations(), { id: 'chooseAdditionalDestinations_button' });
                    dojo.addClass('chooseAdditionalDestinations_button', 'disabled');
                    break;
            }
        }
    }
    ///////////////////////////////////////////////////
    //// Utility methods
    ///////////////////////////////////////////////////
    isGlobetrotterBonusActive() {
        return this.gamedatas.isGlobetrotterBonusActive;
    }
    isLongestPathBonusActive() {
        return this.gamedatas.isLongestPathBonusActive;
    }
    setTooltip(id, html) {
        this.bga.gameui.addTooltipHtml(id, html, this.TOOLTIP_DELAY);
    }
    setTooltipToClass(className, html) {
        this.bga.gameui.addTooltipHtmlToClass(className, html, this.TOOLTIP_DELAY);
    }
    /**
     * Handle user preferences changes.
     */
    onUserPreferenceChanged(prefId, prefValue) {
        switch (prefId) {
            case 201: // 1 = buttons, 2 = double click to pick 2 cards
                dojo.toggleClass('train-car-deck-hidden-pile', 'buttonselection', prefValue == 1);
                break;
            case 203:
                this.map.setOutline();
                break;
            case 204:
                document.getElementsByTagName('html')[0].dataset.colorBlind = (prefValue == 1).toString();
                this.playerTable?.updateColorBlindRotation();
                break;
            case 205:
                document.getElementById('train-car-deck').prepend(document.getElementById(prefValue == 1 ? 'train-car-deck-hidden-pile' : 'destination-deck-hidden-pile'));
                document.getElementById('train-car-deck').append(document.getElementById(prefValue == 1 ? 'destination-deck-hidden-pile' : 'train-car-deck-hidden-pile'));
                document.getElementById('destination-deck-hidden-pile').classList.toggle('top', prefValue == 2);
                break;
        }
    }
    isColorBlindMode() {
        return this.bga.userPreferences.get(204) === 1;
    }
    getPlayerId() {
        return this.bga.players.getCurrentPlayerId();
    }
    getPlayerScore(playerId) {
        return this.bga.gameui.scoreCtrl[playerId]?.getValue() ?? Number(this.gamedatas.players[playerId].score);
    }
    isDoubleRouteForbidden() {
        return Object.values(this.gamedatas.players).length < this.gamedatas.map.minimumPlayerForDoubleRoutes;
    }
    getMap() {
        return this.gamedatas.map;
    }
    getCityName(id) {
        return this.gamedatas.map.cities[id].name;
    }
    /**
     * Place counters on player panels.
     */
    createPlayerPanels(gamedatas) {
        Object.values(gamedatas.players).forEach(player => {
            const playerId = Number(player.id);
            document.getElementById(`overall_player_board_${player.id}`).dataset.playerColor = player.color;
            // public counters
            this.bga.playerPanels.getElement(playerId).insertAdjacentHTML('beforeend', `<div class="counters">
                <div id="train-car-counter-${player.id}-wrapper" class="counter train-car-counter">
                    <div class="icon train" data-player-color="${player.color}" data-color-blind-player-no="${player.playerNo}"></div> 
                    <span id="train-car-counter-${player.id}"></span>
                </div>${this.gamedatas.map.stations !== null ? `
                <div id="station-counter-${player.id}-wrapper" class="counter station-counter">
                    <div class="icon station" data-player-color="${player.color}" data-color-blind-player-no="${player.playerNo}"></div> 
                    <span id="station-counter-${player.id}"></span>
                </div>` : ''}
                <div id="train-car-card-counter-${player.id}-wrapper" class="counter train-car-card-counter">
                    <div class="icon train-car-card-icon"></div> 
                    <span id="train-car-card-counter-${player.id}"></span>
                </div>
                <div id="destinations-counter-${player.id}-wrapper" class="counter destinations-counter">
                    <div class="icon destination-card"></div> 
                    <span id="completed-destinations-counter-${player.id}">${this.getPlayerId() !== playerId ? '?' : ''}</span>/<span id="destination-card-counter-${player.id}"></span>
                </div>
            </div>`);
            const trainCarCounter = new ebg.counter();
            trainCarCounter.create(`train-car-counter-${player.id}`);
            trainCarCounter.setValue(player.remainingTrainCarsCount);
            this.trainCarCounters[playerId] = trainCarCounter;
            if (this.gamedatas.map.stations !== null) {
                const stationCounter = new ebg.counter();
                stationCounter.create(`station-counter-${player.id}`);
                stationCounter.setValue(player.remainingStations);
                this.stationCounters[playerId] = stationCounter;
            }
            const trainCarCardCounter = new ebg.counter();
            trainCarCardCounter.create(`train-car-card-counter-${player.id}`);
            trainCarCardCounter.setValue(player.trainCarsCount);
            this.trainCarCardCounters[playerId] = trainCarCardCounter;
            const destinationCardCounter = new ebg.counter();
            destinationCardCounter.create(`destination-card-counter-${player.id}`);
            destinationCardCounter.setValue(player.destinationsCount);
            this.destinationCardCounters[playerId] = destinationCardCounter;
            // private counters
            if (this.getPlayerId() === playerId) {
                this.completedDestinationsCounter = new ebg.counter();
                this.completedDestinationsCounter.create(`completed-destinations-counter-${player.id}`);
                this.completedDestinationsCounter.setValue(gamedatas.completedDestinations.length);
            }
            if (gamedatas.showTurnOrder && Number(gamedatas.gamestate.id) < 30) { // don't show turn order if game is already started (refresh or TB game)
                this.bga.playerPanels.getElement(playerId).insertAdjacentHTML('beforeend', `<div class="player-turn-order">${_('Player ${number}').replace('${number}', `<strong>${player.playerNo}</strong>`)}</div>`);
            }
        });
        this.setTooltipToClass('train-car-counter', _("Remaining train cars"));
        this.setTooltipToClass('train-car-card-counter', _("Train cars cards"));
        this.setTooltipToClass('destinations-counter', _("Completed / Total destination cards"));
    }
    /**
     * Update player score.
     */
    setPoints(playerId, points) {
        this.bga.gameui.scoreCtrl[playerId]?.toValue(points);
    }
    /**
     * Highlight active destination.
     */
    setActiveDestination(destination, previousDestination = null) {
        this.map.setActiveDestination(destination, previousDestination);
    }
    /**
     * Highlight destination (on destination mouse over).
     */
    setHighligthedDestination(destination) {
        this.map.setHighligthedDestination(destination);
    }
    /**
     * Highlight cities of selected destination.
     */
    setSelectedDestination(destination, visible) {
        this.map.setSelectedDestination(destination, visible);
    }
    /**
     * Highlight cities player must connect for its objectives.
     */
    setDestinationsToConnect(destinations) {
        this.map.setDestinationsToConnect(destinations);
    }
    /**
     * Place player table to the left or the bottom of the map.
     */
    setPlayerTablePosition(left) {
        this.playerTable?.setPosition(left);
    }
    /**
     * Get current zoom.
     */
    getZoom() {
        return this.map.getZoom();
    }
    /**
     * Get current player.
     */
    getCurrentPlayer() {
        return this.gamedatas.players[this.getPlayerId()];
    }
    /**
     * Add an animation to the animation queue, and start it if there is no current animations.
     */
    addAnimation(animation) {
        this.animations.push(animation);
        if (this.animations.length === 1) {
            this.animations[0].animate();
        }
        ;
    }
    /**
     * Start the next animation in animation queue.
     */
    endAnimation(ended) {
        const index = this.animations.indexOf(ended);
        if (index !== -1) {
            this.animations.splice(index, 1);
        }
        if (this.animations.length >= 1) {
            this.animations[0].animate();
        }
        ;
    }
    selectedColorChanged(selectedColor) {
        if (!this.bga.players.isCurrentPlayerActive() || this.gamedatas.gamestate.name !== 'chooseAction') {
            return;
        }
        const args = this.gamedatas.gamestate.args;
        if (selectedColor === null || selectedColor === 0) {
            this.map.setSelectableRoutes(true, args.possibleRoutes);
        }
        else {
            this.map.setSelectableRoutes(true, args.possibleRoutes.filter(route => route.color === selectedColor || route.color === 0));
        }
    }
    /**
     * Apply destination selection (initial objectives).
     */
    chooseInitialDestinations() {
        const destinationsIds = this.destinationSelection.getSelectedDestinationsIds();
        this.bga.actions.performAction('actChooseInitialDestinations', {
            destinationsIds: destinationsIds.join(',')
        });
    }
    /**
     * Pick destinations.
     */
    drawDestinations() {
        const confirmation = this.bga.userPreferences.get(206) !== 2;
        if (confirmation && this.gamedatas.gamestate.args.maxDestinationsPick) {
            this.bga.dialogs.confirmation(_('Are you sure you want to take new destinations?')).then(result => {
                if (result) {
                    this.bga.actions.performAction('actDrawDestinations');
                }
            });
        }
        else {
            this.bga.actions.performAction('actDrawDestinations');
        }
    }
    /**
     * Apply destination selection (additional objectives).
     */
    chooseAdditionalDestinations() {
        const destinationsIds = this.destinationSelection.getSelectedDestinationsIds();
        this.bga.actions.performAction('actChooseAdditionalDestinations', {
            destinationsIds: destinationsIds.join(',')
        });
    }
    /**
     * Pick hidden train car(s).
     */
    onHiddenTrainCarDeckClick(number) {
        const action = this.gamedatas.gamestate.name === 'drawSecondCard' ? 'actDrawSecondDeckCard' : 'actDrawDeckCards';
        this.bga.actions.performAction(action, {
            number
        });
    }
    /**
     * Pick visible train car.
     */
    onVisibleTrainCarCardClick(id) {
        const action = this.gamedatas.gamestate.name === 'drawSecondCard' ? 'actDrawSecondTableCard' : 'actDrawTableCard';
        this.bga.actions.performAction(action, {
            id
        });
    }
    isFastEndScoring() {
        return this.bga.userPreferences.get(208) == 2;
    }
    ///////////////////////////////////////////////////
    //// Reaction to cometD notifications
    /*
        setupNotifications:

        In this method, you associate each of your game notifications with your local method to handle it.

        Note: game notification names correspond to "notify->all" and "notify->player" calls in
                your azul.game.php file.

    */
    setupNotifications() {
        //console.log( 'notifications subscriptions setup' );
        const skipEndOfGameAnimations = this.isFastEndScoring();
        const notifs = [
            ['newCardsOnTable', ANIMATION_MS],
            ['claimedRoute', ANIMATION_MS],
            ['builtStation', ANIMATION_MS],
            ['destinationCompleted', ANIMATION_MS],
            ['points', 1],
            ['destinationsPicked', 1],
            ['trainCarPicked', ANIMATION_MS],
            ['freeTunnel', 2000],
            ['highlightVisibleLocomotives', 1000],
            ['notEnoughTrainCars', 1],
            ['lastTurn', 1],
            ['bestScore', 1],
            ['scoreDestination', skipEndOfGameAnimations ? 1 : 2000],
            ['longestPath', skipEndOfGameAnimations ? 1 : 2000],
            ['longestPathWinner', skipEndOfGameAnimations ? 1 : 1500],
            ['globetrotterWinner', skipEndOfGameAnimations ? 1 : 1500],
            ['remainingStations', skipEndOfGameAnimations ? 1 : 1500],
            ['scoreDestinationGrandTour', skipEndOfGameAnimations ? 1 : 2000],
            ['highlightWinnerScore', 1],
        ];
        notifs.forEach((notif) => {
            dojo.subscribe(notif[0], this, `notif_${notif[0]}`);
            this.bga.gameui.notifqueue.setSynchronous(notif[0], notif[1]);
        });
        this.bga.gameui.notifqueue.setIgnoreNotificationCheck('trainCarPicked', (notif) => notif.args.playerId == this.getPlayerId() && !notif.args.cards);
    }
    /**
     * Update player score.
     */
    notif_points(notif) {
        this.setPoints(notif.args.playerId, notif.args.points);
        this.endScore?.setPoints(notif.args.playerId, notif.args.points);
    }
    /**
     * Update player destinations.
     */
    notif_destinationsPicked(notif) {
        this.destinationCardCounters[notif.args.playerId].incValue(notif.args.number);
        const destinations = notif.args._private?.destinations;
        if (destinations) {
            this.playerTable.addDestinations(destinations, this.destinationSelection.destinations);
        }
        else {
            this.trainCarSelection.moveDestinationCardToPlayerBoard(notif.args.playerId, notif.args.number);
        }
        this.trainCarSelection.setDestinationCount(notif.args.remainingDestinationsInDeck);
    }
    /**
     * Update player train cars.
     */
    notif_trainCarPicked(notif) {
        this.trainCarCardCounters[notif.args.playerId].incValue(notif.args.number);
        if (notif.args.playerId == this.getPlayerId()) {
            const cards = notif.args.cards;
            this.playerTable.addTrainCars(cards, this.trainCarSelection.getStockElement(notif.args.origin));
        }
        else {
            this.trainCarSelection.moveTrainCarCardToPlayerBoard(notif.args.playerId, notif.args.origin, notif.args.number);
        }
        this.trainCarSelection.setTrainCarCount(notif.args.remainingTrainCarsInDeck);
    }
    /**
     * Update visible cards.
     */
    notif_newCardsOnTable(notif) {
        if (notif.args.locomotiveRefill) {
            this.bga.sounds.play(`ttr-clear-train-car-cards`);
            this.bga.gameui.disableNextMoveSound();
        }
        this.trainCarSelection.setNewCardsOnTable(notif.args.spotsCards, true);
        this.trainCarSelection.setTrainCarCount(notif.args.remainingTrainCarsInDeck);
    }
    /**
     * Animate the 3 visible locomotives (bump) before they are replaced.
     */
    notif_highlightVisibleLocomotives() {
        this.trainCarSelection.highlightVisibleLocomotives();
    }
    /**
     * Update claimed routes.
     */
    notif_claimedRoute(notif) {
        const playerId = notif.args.playerId;
        const route = notif.args.route;
        this.trainCarCardCounters[playerId].incValue(-notif.args.removeCards.length);
        this.trainCarCounters[playerId].toValue(notif.args.remainingTrainCars);
        this.map.setClaimedRoutes([{
                playerId,
                routeId: route.id
            }], playerId);
        if (playerId == this.getPlayerId()) {
            this.playerTable.removeCards(notif.args.removeCards);
        }
    }
    /**
     * Update built stations.
     */
    notif_builtStation(notif) {
        const playerId = notif.args.playerId;
        const city = notif.args.city;
        this.trainCarCardCounters[playerId].incValue(-notif.args.removeCards.length);
        this.stationCounters[playerId].incValue(-1);
        this.map.setBuiltStations([{
                playerId,
                cityId: city.id
            }], playerId);
        if (playerId == this.getPlayerId()) {
            this.playerTable.removeCards(notif.args.removeCards);
            document.getElementById('stations-information-button').classList.add('visible');
        }
    }
    /**
     * Mark a destination as complete.
     */
    notif_destinationCompleted(notif) {
        const destination = notif.args.destination;
        this.completedDestinationsCounter.incValue(1);
        this.gamedatas.completedDestinations.push(destination);
        this.playerTable.markDestinationComplete(destination, notif.args.destinationRoutes);
        this.bga.sounds.play(`ttr-completed-in-game`);
        this.bga.gameui.disableNextMoveSound();
    }
    /**
     * Show the 3 cards when attempting a tunnel (case withno extra cards required, play automatically).
     */
    notif_freeTunnel(notif) {
        if (this.bga.gameui.bgaAnimationsActive()) {
            this.trainCarSelection.showTunnelCards(notif.args.tunnelCards);
            setTimeout(() => this.trainCarSelection.showTunnelCards([]), 2000);
        }
    }
    /**
     * Show an error message and animate train car counter to show the player can't take the route because he doesn't have enough train cars left.
     */
    notif_notEnoughTrainCars() {
        this.bga.dialogs.showMessage(_("Not enough train cars left to claim the route."), 'error');
        const animatedElement = document.getElementById(`train-car-counter-${this.getPlayerId()}-wrapper`);
        animatedElement.classList.remove('animate-low-count');
        setTimeout(() => animatedElement.classList.add('animate-low-count'), 1);
        if (document.getElementById(`confirmRouteClaim-button`)) {
            this.chooseActionState.cancelRouteClaim();
        }
        else {
            document.querySelectorAll(`[id^="claimRouteWithColor_button"]`).forEach(button => button.parentElement?.removeChild(button));
        }
    }
    /**
     * Show last turn banner.
     */
    notif_lastTurn(animate = true) {
        this.bga.gameArea.addLastTurnBanner(_("This is the final round!"));
    }
    /**
     * Save best score for end score animations.
     */
    notif_bestScore(notif) {
        this.gamedatas.bestScore = notif.args.bestScore;
        this.endScore?.setBestScore(notif.args.bestScore);
    }
    /**
     * Animate a destination for end score.
     */
    notif_scoreDestination(notif) {
        const playerId = notif.args.playerId;
        const player = this.gamedatas.players[playerId];
        this.endScore?.scoreDestination(playerId, notif.args.destination, notif.args.destinationRoutes, this.isFastEndScoring());
        if (notif.args.destinationRoutes) {
            player.completedDestinations.push(notif.args.destination);
        }
        else {
            player.uncompletedDestinations.push(notif.args.destination);
            document.getElementById(`destination-card-${notif.args.destination.id}`)?.classList.add('uncompleted');
        }
        this.endScore?.updateDestinationsTooltip(player);
    }
    /**
     * Add Globetrotter badge for end score.
     */
    notif_globetrotterWinner(notif) {
        this.endScore?.setGlobetrotterWinner(notif.args.playerId, notif.args.length);
    }
    /**
     * Animate longest path for end score.
     */
    notif_longestPath(notif) {
        this.endScore?.showLongestPath(this.gamedatas.players[notif.args.playerId].color, notif.args.routes, notif.args.length, this.isFastEndScoring());
    }
    /**
     * Animate mandala routes for end score.
     */
    notif_scoreDestinationGrandTour(notif) {
        this.endScore?.showMandalaRoutes(notif.args.routes, notif.args.destination, this.isFastEndScoring());
    }
    /**
     * Add longest path badge for end score.
     */
    notif_longestPathWinner(notif) {
        this.endScore?.setLongestPathWinner(notif.args.playerId, notif.args.length);
    }
    /**
     * Animate remaining stations for end score.
     */
    notif_remainingStations(notif) {
        this.endScore?.showRemainingStations(this.gamedatas.players[notif.args.playerId].color, notif.args.remainingStations, this.isFastEndScoring());
    }
    /**
     * Highlight winner for end score.
     */
    notif_highlightWinnerScore(notif) {
        this.endScore?.highlightWinnerScore(notif.args.playerId);
        this.bga.sounds.play(`ttr-scoring-end`);
        this.bga.gameui.disableNextMoveSound();
    }
    bgaFormatText(log, args) {
        try {
            if (log && args && !args.processed) {
                if (typeof args.color == 'number') {
                    args.color = `<div class="train-car-color icon" data-color="${args.color}"></div>`;
                }
                if (typeof args.colors == 'object') {
                    args.colors = args.colors.map(color => `<div class="train-car-color icon" data-color="${color}"></div>`).join('');
                }
                // make cities names in bold 
                ['from', 'to', 'count', 'extraCards', 'pickedCards'].forEach(field => {
                    if (args[field] !== null && args[field] !== undefined && args[field][0] != '<') {
                        args[field] = `<strong>${_(args[field])}</strong>`;
                    }
                });
                ['you', 'actplayer', 'player_name'].forEach(field => {
                    if (typeof args[field] === 'string' && args[field].indexOf('#ffed00;') !== -1 && args[field].indexOf('text-shadow') === -1) {
                        args[field] = args[field].replace('#ffed00;', '#ffed00; text-shadow: 0 0 1px black, 0 0 2px black, 0 0 3px black;');
                    }
                });
            }
        }
        catch (e) {
            console.error(log, args, "Exception thrown", e.stack);
        }
        return { log, args };
    }
    createRulesPopin() {
        const url = this.gamedatas.map.multilingualPdfRulesUrl;
        //`https://cdn.svc.asmodee.net/production-daysofwonder/uploads/2023/09/720114-T2RMC2-Rules_switzerland-ML-2017.pdf`; // TODO
        let html = `
        <div id="popin_showMapRulebook_container" class="tickettoride_popin_container">
            <div id="popin_showMapRulebook_underlay" class="tickettoride_popin_underlay"></div>
                <div id="popin_showMapRulebook_wrapper" class="tickettoride_popin_wrapper">
                <div id="popin_showMapRulebook" class="tickettoride_popin">
                    <a id="popin_showMapRulebook_close" class="closeicon"><i class="fa fa-times fa-2x" aria-hidden="true"></i></a>
                                
                    <h2>${_('Rules differences')}</h2>
                    <div id="playermat-container-modal">
                        ${this.gamedatas.map.rulesDifferences ? `
                            <ul>
                                ${this.gamedatas.map.rulesDifferences.map(line => `<li>${_(line)}</li>`).join('')}
                            </ul>
                        ` : ''}
                    
                        ${url ? `
                        <p class="block-buttons">
                            ${_('Multilingual rulebook:')}
                            <button id="show-rulebook" style="width: auto;" class="bgabutton bgabutton_blue">${_('Show rulebook')}</button>
                            <a href="${url}" target="_blank" class="bgabutton bgabutton_blue">${_('Open rulebook in a new tab')}</a>
                        </p>
                        <div id="rulebook-iframe"></div>
                        ` : ''}
                    </div>
                </div>
            </div>
        </div>`;
        document.body.insertAdjacentHTML('beforeend', html);
        document.getElementById(`popin_showMapRulebook_close`).addEventListener(`click`, () => this.closePopin());
        document.getElementById(`popin_showMapRulebook_underlay`).addEventListener(`click`, () => this.closePopin());
        if (url) {
            document.getElementById(`show-rulebook`).addEventListener(`click`, () => this.viewRulebook(url));
        }
    }
    viewRulebook(url) {
        const rulebookContainer = document.getElementById(`rulebook-iframe`);
        const show = rulebookContainer.innerHTML === '';
        if (show) {
            const html = `<iframe src="${url}" style="width: 100%; height: 60vh"></iframe>`;
            rulebookContainer.innerHTML = html;
        }
        else {
            rulebookContainer.innerHTML = '';
        }
        document.getElementById(`show-rulebook`).innerHTML = show ? _('Hide rulebook') : _('Show rulebook');
    }
    closePopin() {
        document.getElementById('popin_showMapRulebook_container').remove();
    }
}

export { Game };
