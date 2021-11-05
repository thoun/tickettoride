/*declare const define;
declare const ebg;
declare const $;
declare const dojo: Dojo;
declare const _;
declare const g_gamethemeurl;

declare const board: HTMLDivElement;*/

const CARD_WIDTH = 150;
const CARD_HEIGHT = 100;

function setupTrainCarCards(stock: Stock) {
    const trainCarsUrl = `${g_gamethemeurl}img/train-cards.jpg`;
    for (let id=0; id<=8; id++) {
        stock.addItemType(id, id, trainCarsUrl, id);
    }
}

function setupDestinationCards(stock: Stock) {
    const destinationsUrl = `${g_gamethemeurl}img/destinations.jpg`;
    for (let id=1; id<=36; id++) {
        stock.addItemType(id, id, destinationsUrl, id);
    }
}