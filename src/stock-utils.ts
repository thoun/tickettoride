/*declare const define;
declare const ebg;
declare const $;
declare const dojo: Dojo;
declare const _;
declare const g_gamethemeurl;

declare const board: HTMLDivElement;*/

const CARD_WIDTH = 250;
const CARD_HEIGHT = 161;

function setupTrainCarCards(stock: Stock) {
    const trainCarsUrl = `${g_gamethemeurl}img/train-cards.jpg`;
    for (let type=0; type<=8; type++) {
        stock.addItemType(type, type, trainCarsUrl, type);
    }
}

function setupDestinationCards(stock: Stock) {
    const destinationsUrl = `${g_gamethemeurl}img/destinations.jpg`;
    for (let id=1; id<=36; id++) {
        stock.addItemType(id, id, destinationsUrl, id - 1);
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

// TODO TEMP
const COLORS = [
  'GRAY',
  'PINK',
  'WHITE',
  'BLUE',
  'YELLOW',
  'ORANGE',
  'BLACK',
  'RED',
  'GREEN',
];
function setupTrainCarCardDiv(cardDiv: HTMLDivElement, cardTypeId) {
    cardDiv.title = Number(cardTypeId) == 0 ? 'Locomotive' : COLORS[Number(cardTypeId)];
}

class DestinationCard {
    constructor(
        public id: number,
        public from: number,
        public to: number,
        public points: number,
    ) {}
}

const CITIES_NAMES = [
    null,
    'Atlanta',
    'Boston',
    'Calgary',
    'Charleston',
    'Chicago',
    'Dallas',
    'Denver',
    'Duluth',
    'El Paso',
    'Helena',
    'Houston',
    'Kansas City',
    'Las Vegas',
    'Little Rock',
    'Los Angeles',
    'Miami',
    'Montréal',
    'Nashville',
    'New Orleans',
    'New York',
    'Oklahoma City',
    'Omaha',
    'Phoenix',
    'Pittsburgh',
    'Portland',
    'Raleigh',
    'Saint Louis',
    'Salt Lake City',
    'Sault St. Marie',
    'San Francisco',
    'Santa Fe',
    'Seattle',
    'Toronto',
    'Vancouver',
    'Washington',
    'Winnipeg',
  ];
const DESTINATIONS = [
    new DestinationCard(1, 2, 16, 12), // Boston	Miami	12
    new DestinationCard(2, 3, 23, 13), // Calgary	Phoenix	13
    new DestinationCard(3, 3, 28, 7), // Calgary	Salt Lake City	7
    new DestinationCard(4, 5, 19, 7), // Chicago	New Orleans	7
    new DestinationCard(5, 5, 31, 9), // Chicago	Santa Fe	9
    new DestinationCard(6, 6, 20, 11), // Dallas	New York	11
    new DestinationCard(7, 7, 9, 4), // Denver	El Paso	4
    new DestinationCard(8, 7, 24, 11), // Denver	Pittsburgh	11
    new DestinationCard(9, 8, 9, 10), // Duluth	El Paso	10
    new DestinationCard(10, 8, 11, 8), // Duluth	Houston	8
    new DestinationCard(11, 10, 15, 8), // Helena	Los Angeles	8
    new DestinationCard(12, 12, 11, 5), // Kansas City	Houston	5
    new DestinationCard(13, 15, 5, 16), // Los Angeles	Chicago	16
    new DestinationCard(14, 15, 16, 20), // Los Angeles	Miami	20
    new DestinationCard(15, 15, 20, 21), // Los Angeles	New York	21
    new DestinationCard(16, 17, 1, 9), // Montréal	Atlanta	9
    new DestinationCard(17, 17, 19, 13), // Montréal	New Orleans	13
    new DestinationCard(18, 20, 1, 6), // New York	Atlanta	6
    new DestinationCard(19, 25, 18, 17), // Portland	Nashville	17
    new DestinationCard(20, 25, 23, 11), // Portland	Phoenix	11
    new DestinationCard(21, 30, 1, 17), // San Francisco	Atlanta	17
    new DestinationCard(22, 29, 18, 8), // Sault St. Marie	Nashville	8
    new DestinationCard(23, 29, 21, 9), // Sault St. Marie	Oklahoma City	9
    new DestinationCard(24, 32, 15, 9), // Seattle	Los Angeles	9
    new DestinationCard(25, 32, 20, 22), // Seattle	New York	22
    new DestinationCard(26, 33, 16, 10), // Toronto	Miami	10
    new DestinationCard(27, 34, 17, 20), // Vancouver	Montréal	20
    new DestinationCard(28, 34, 31, 13), // Vancouver	Santa Fe	13
    new DestinationCard(29, 36, 11, 12), // Winnipeg	Houston	12
    new DestinationCard(30, 36, 14, 11), // Winnipeg	Little Rock	11
];
function setupDestinationCardDiv(cardDiv: HTMLDivElement, cardTypeId) {
    //const destination = DESTINATIONS.find(d => d.id == Number(cardTypeId));
    //cardDiv.innerHTML = `<span><strong>${CITIES_NAMES[destination.from]}</strong> to <strong>${CITIES_NAMES[destination.to]}</strong> (<strong>${destination.points}</strong>)</span>`;
}
