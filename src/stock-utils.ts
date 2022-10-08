const CARD_WIDTH = 250;
const CARD_HEIGHT = 161;
const DESTINATION_CARD_SHIFT = 32;

function setupTrainCarCards(stock: Stock) {
    const trainCarsUrl = `${g_gamethemeurl}img/train-cards.jpg`;
    for (let type=0; type<=8; type++) {
        stock.addItemType(type, type, trainCarsUrl, type);
    }
}

function setupDestinationCards(stock: Stock) {
    const destinationsUrl = `${g_gamethemeurl}img/destinations.jpg`;
    for (let id=1; id<=30; id++) {
        stock.addItemType(100 + id, 100 + id, destinationsUrl, id - 1);
    }
    const destinations1910Url = `${g_gamethemeurl}img/destinations-1910.jpg`;
    for (let id=1; id<=35; id++) {
        stock.addItemType(200 + id, 200 + id, destinations1910Url, id - 1);
    }
    const destinationsMegaUrl = `${g_gamethemeurl}img/destinations-mega.jpg`;
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
    // base game
    new DestinationCard(101, 2, 16, 12), // Boston	Miami	12
    new DestinationCard(102, 3, 23, 13), // Calgary	Phoenix	13
    new DestinationCard(103, 3, 28, 7), // Calgary	Salt Lake City	7
    new DestinationCard(104, 5, 19, 7), // Chicago	New Orleans	7
    new DestinationCard(105, 5, 31, 9), // Chicago	Santa Fe	9
    new DestinationCard(106, 6, 20, 11), // Dallas	New York	11
    new DestinationCard(107, 7, 9, 4), // Denver	El Paso	4
    new DestinationCard(108, 7, 24, 11), // Denver	Pittsburgh	11
    new DestinationCard(109, 8, 9, 10), // Duluth	El Paso	10
    new DestinationCard(110, 8, 11, 8), // Duluth	Houston	8
    new DestinationCard(111, 10, 15, 8), // Helena	Los Angeles	8
    new DestinationCard(112, 12, 11, 5), // Kansas City	Houston	5
    new DestinationCard(113, 15, 5, 16), // Los Angeles	Chicago	16
    new DestinationCard(114, 15, 16, 20), // Los Angeles	Miami	20
    new DestinationCard(115, 15, 20, 21), // Los Angeles	New York	21
    new DestinationCard(116, 17, 1, 9), // Montréal	Atlanta	9
    new DestinationCard(117, 17, 19, 13), // Montréal	New Orleans	13
    new DestinationCard(118, 20, 1, 6), // New York	Atlanta	6
    new DestinationCard(119, 25, 18, 17), // Portland	Nashville	17
    new DestinationCard(120, 25, 23, 11), // Portland	Phoenix	11
    new DestinationCard(121, 30, 1, 17), // San Francisco	Atlanta	17
    new DestinationCard(122, 29, 18, 8), // Sault St. Marie	Nashville	8
    new DestinationCard(123, 29, 21, 9), // Sault St. Marie	Oklahoma City	9
    new DestinationCard(124, 32, 15, 9), // Seattle	Los Angeles	9
    new DestinationCard(125, 32, 20, 22), // Seattle	New York	22
    new DestinationCard(126, 33, 16, 10), // Toronto	Miami	10
    new DestinationCard(127, 34, 17, 20), // Vancouver	Montréal	20
    new DestinationCard(128, 34, 31, 13), // Vancouver	Santa Fe	13
    new DestinationCard(129, 36, 11, 12), // Winnipeg	Houston	12
    new DestinationCard(130, 36, 14, 11), // Winnipeg	Little Rock	11
    
    // 1910
    new DestinationCard(201, 3, 18, 14), // Calgary	Nashville	14
    new DestinationCard(202, 5, 1, 5), // Chicago	Atlanta 5
    new DestinationCard(203, 5, 2, 5), // Chicago	Boston	5
    new DestinationCard(204, 5, 20, 5), // Chicago	New-York	5
    new DestinationCard(205, 7, 27, 6), // Denver	Saint Louis	6
    new DestinationCard(206, 8, 6, 7), // Duluth	Dallas	7
    new DestinationCard(207, 11, 35, 10), // Houston Washington	10
    new DestinationCard(208, 12, 2, 11), // Kansas City	Boston	11
    new DestinationCard(209, 13, 16, 21), // Las Vegas	Miami 21
    new DestinationCard(210, 13, 20, 19), // Las Vegas	New-York 19
    new DestinationCard(211, 15, 1, 15), // Los Angeles	Atlanta	15
    new DestinationCard(212, 15, 3, 12), // Los Angeles	Calgary	12
    new DestinationCard(213, 15, 21, 9), // Los Angeles	Oklahoma City	12
    new DestinationCard(214, 17, 6, 13), // Montréal	Dallas	13
    new DestinationCard(215, 17, 26, 7), // Montréal	Raleigh	7
    new DestinationCard(216, 18, 20, 6), // Nashville  New York	6
    new DestinationCard(217, 20, 16, 10), // New York	Miami	10
    new DestinationCard(218, 22, 19, 8), // Omaha	New Orleans	8
    new DestinationCard(219, 23, 2, 19), // Phoenix	Boston	19
    new DestinationCard(220, 24, 19, 8), // Pittsburgh	New Orleans	8
    new DestinationCard(221, 25, 11, 16), // Portland	Houston	16
    new DestinationCard(222, 25, 24, 19), // Portland	Pittsburgh	19
    new DestinationCard(223, 28, 5, 11), // Salt Lake City	Chicago	11
    new DestinationCard(224, 28, 12, 7), // Salt Lake City	Kansas City	7
    new DestinationCard(225, 30, 29, 17), // San Francisco	Sault St. Marie	17
    new DestinationCard(226, 30, 35, 21), // San Francisco	Washington	21
    new DestinationCard(227, 29, 16, 12), // Sault St. Marie	Miami	12
    new DestinationCard(228, 32, 13, 10), // Seattle	Las Vegas	10
    new DestinationCard(229, 32, 21, 14), // Seattle	Oklahoma City	14
    new DestinationCard(230, 27, 16, 8), // Saint Louis	Miami	8
    new DestinationCard(231, 33, 4, 6), // Toronto	Charleston	6
    new DestinationCard(232, 34, 7, 11), // Vancouver	Denver	11
    new DestinationCard(233, 34, 8, 13), // Vancouver	Duluth	13
    new DestinationCard(234, 35, 1, 4), // Washington	Atlanta 4
    new DestinationCard(235, 36, 31, 10), // Winnipeg	Santa Fe	10

    // mega
    new DestinationCard(301, 2, 16, 12), // Boston	Miami	12
    new DestinationCard(302, 2, 35, 4), // Boston	Washington	4
    new DestinationCard(303, 3, 23, 13), // Calgary	Phoenix	13
    new DestinationCard(304, 3, 28, 7), // Calgary	Salt Lake City	7
    new DestinationCard(305, 5, 19, 7), // Chicago	New Orleans	7
    new DestinationCard(306, 5, 31, 9), // Chicago	Santa Fe	9
    new DestinationCard(307, 6, 20, 11), // Dallas	New York	11
    new DestinationCard(308, 7, 9, 4), // Denver	El Paso	4
    new DestinationCard(309, 7, 24, 11), // Denver	Pittsburgh	11
    new DestinationCard(310, 8, 9, 10), // Duluth	El Paso	10
    new DestinationCard(311, 8, 11, 8), // Duluth	Houston	8
    new DestinationCard(312, 10, 15, 8), // Helena	Los Angeles	8
    new DestinationCard(313, 12, 11, 5), // Kansas City	Houston	5
    new DestinationCard(314, 15, 5, 16), // Los Angeles Chicago	16
    new DestinationCard(315, 15, 16, 19), // Los Angeles	Miami	19
    new DestinationCard(316, 15, 20, 20), // Los Angeles	New York	20
    new DestinationCard(317, 17, 1, 9), // Montréal	Atlanta	9
    new DestinationCard(318, 17, 5, 7), // Montréal	Chicago	7
    new DestinationCard(319, 17, 19, 13), // Montréal  New Orleans	13
    new DestinationCard(320, 20, 1, 6), // New York	Atlanta	6
    new DestinationCard(321, 25, 18, 17), // Portland	Nashville	17
    new DestinationCard(322, 25, 23, 11), // Portland	Phoenix	11
    new DestinationCard(323, 30, 1, 17), // San Francisco	Atlanta	17
    new DestinationCard(324, 29, 18, 8), // Sault St. Marie	Nashville	8
    new DestinationCard(325, 29, 21, 8), // Sault St. Marie	Oklahoma City	8
    new DestinationCard(326, 32, 15, 9), // Seattle	Los Angeles	9
    new DestinationCard(327, 32, 20, 20), // Seattle	New York	20
    new DestinationCard(328, 33, 16, 10), // Toronto	Miami	10
    new DestinationCard(329, 34, 17, 20), // Vancouver	Montréal	20
    new DestinationCard(330, 34, 25, 2), // Vancouver	Portland	2
    new DestinationCard(331, 34, 31, 13), // Vancouver	Santa Fe	13
    new DestinationCard(332, 36, 11, 12), // Winnipeg	Houston	12
    new DestinationCard(333, 36, 14, 11), // Winnipeg	Little Rock	11
    new DestinationCard(334, 36, 22, 6), // Winnipeg	Omaha	6
];

function setupDestinationCardDiv(cardDiv: HTMLDivElement, cardUniqueId: number) {
    const destination = DESTINATIONS.find(d => d.id == cardUniqueId); // TODO1910 cards with changed values !!!
    cardDiv.title = `${dojo.string.substitute(_('${from} to ${to}'), {from: CITIES_NAMES[destination.from], to: CITIES_NAMES[destination.to]})}, ${destination.points} ${_('points')}`;
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