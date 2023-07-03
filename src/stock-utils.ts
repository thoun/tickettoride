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
    const destinationsUrl = `${g_gamethemeurl}img/${map.code}/destinations.jpg`;
    for (let id=1; id<=30; id++) {
        stock.addItemType(100 + id, 100 + id, destinationsUrl, id - 1);
    }
    const destinations1910Url = `${g_gamethemeurl}img/${map.code}/destinations-1910.jpg`;
    for (let id=1; id<=35; id++) {
        stock.addItemType(200 + id, 200 + id, destinations1910Url, id - 1);
    }
    const destinationsMegaUrl = `${g_gamethemeurl}img/${map.code}/destinations-mega.jpg`;
    for (let id=1; id<=34; id++) {
        stock.addItemType(300 + id, 300 + id, destinationsMegaUrl, id - 1);
    }
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
    constructor(
        public id: number,
        public from: number,
        public to: number,
        public points: number,
    ) {}
}

function getDestinations(game: TicketToRideGame): DestinationCard[] {
    const destinations = [];
    Object.entries(game.getMap().destinations).forEach(typeEntry => Object.entries(typeEntry[1]).forEach(destinationEntry => 
        destinations.push(new DestinationCard(Number(typeEntry[0]) * 100 + Number(destinationEntry[0]), destinationEntry[1].from, destinationEntry[1].to, destinationEntry[1].points))
    ));
    return destinations;
}

function setupDestinationCardDiv(game: TicketToRideGame, cardDiv: HTMLDivElement, cardUniqueId: number, expansion1910: number) {
    const DESTINATIONS = getDestinations(game);
    const destinations = DESTINATIONS.filter(d => expansion1910 > 0 ? d.id >= 200 : d.id < 200);
    const destination = destinations.find(d => d.id == cardUniqueId);
    cardDiv.title = `${dojo.string.substitute(_('${from} to ${to}'), {
        from: game.getCityName(destination.from), 
        to: game.getCityName(destination.to),
    })}, ${destination.points} ${_('points')}`;
}

function getBackgroundInlineStyleForDestination(destination: Destination) {
    let file;
    switch(destination.type) {
        case 1: 
            file = 'destinations.jpg';
            break;
        case 2: 
            file = 'destinations-1910.jpg';
            break;
        case 3: 
            file = 'destinations-mega.jpg';
            break;
    }

    const imagePosition = destination.type_arg - 1;
    const row = Math.floor(imagePosition / IMAGE_ITEMS_PER_ROW);
    const xBackgroundPercent = (imagePosition - (row * IMAGE_ITEMS_PER_ROW)) * 100;
    const yBackgroundPercent = row * 100;
    return `background-image: url('${g_gamethemeurl}img/${file}'); background-position: -${xBackgroundPercent}% -${yBackgroundPercent}%;`;
}