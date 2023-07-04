const CARD_WIDTH = 250;
const CARD_HEIGHT = 161;
const DESTINATION_CARD_SHIFT = 32;

function setupTrainCarCards(stock: Stock) {
    const trainCarsUrl = `${g_gamethemeurl}img/train-cards.jpg`;
    for (let type=0; type<=8; type++) {
        stock.addItemType(type, type, trainCarsUrl, type);
    }
}

function setupDestinationCards(map: TicketToRideMap, stock: Stock) {
    const destinations = getDestinations(map);
    destinations.forEach(destination => {
        const file = `${g_gamethemeurl}img/${map.code}/destinations-${destination.type}-${destination.setTypeArg}.jpg`;  
        stock.addItemType(destination.uniqueId, destination.uniqueId, file, (destination.typeArg % 100) - 1);
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

function getColor(color: number, type: 'route' | 'train-car') {
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

function setupTrainCarCardDiv(cardDiv: HTMLDivElement, cardTypeId) {
    cardDiv.title = getColor(Number(cardTypeId), 'train-car');
}

class DestinationCard {
    public uniqueId: number;
    public setTypeArg: number;

    constructor(
        public type: number,
        public typeArg: number,
        public from: number,
        public to: number,
        public points: number,
    ) {
        this.uniqueId = 1000 * type + typeArg;
        this.setTypeArg = Math.floor(typeArg / 100);
    }
}

function getDestinations(map: TicketToRideMap): DestinationCard[] {
    const destinations = [];
    Object.entries(map.destinations).forEach(typeEntry => Object.entries(typeEntry[1]).forEach(destinationEntry => 
        destinations.push(new DestinationCard(Number(typeEntry[0]), Number(destinationEntry[0]), destinationEntry[1].from, destinationEntry[1].to, destinationEntry[1].points))
    ));
    return destinations;
}

function setupDestinationCardDiv(game: TicketToRideGame, cardDiv: HTMLDivElement, cardUniqueId: number) {
    const destinations = getDestinations(game.getMap());
    const destination = destinations.find(d => d.uniqueId == cardUniqueId);
    cardDiv.title = `${dojo.string.substitute(_('${from} to ${to}'), {
        from: game.getCityName(destination.from), 
        to: game.getCityName(destination.to),
    })}, ${destination.points} ${_('points')}`;
}

function getBackgroundInlineStyleForDestination(map: TicketToRideMap, destination: Destination) {
    const setTypeArg = Math.floor(destination.type_arg / 100);
    let file = `destinations-${destination.type}-${setTypeArg}.jpg`;

    const imagePosition = (destination.type_arg % 100) - 1;
    const row = Math.floor(imagePosition / IMAGE_ITEMS_PER_ROW);
    const xBackgroundPercent = (imagePosition - (row * IMAGE_ITEMS_PER_ROW)) * 100;
    const yBackgroundPercent = row * 100;
    return `background-image: url('${g_gamethemeurl}img/${map.code}/${file}'); background-position: -${xBackgroundPercent}% -${yBackgroundPercent}%;`;
}