import { DistributionPopin } from './distribution-popin.bundle.js';

window._ = text => text;
window.ebg = { popindialog: class {
    create(id) {
        this.underlay = document.createElement('div');
        this.underlay.className = 'test-underlay';
        this.element = document.createElement('section');
        this.element.id = `popin_${id}`;
        document.body.append(this.underlay, this.element);
        this.underlay.addEventListener('click', () => this.closeCallback?.());
    }
    setTitle(title) { this.title = title; }
    setContent(html) { this.content = html; }
    show() {
        this.element.innerHTML = `<button class="test-close" aria-label="Close">×</button><h2></h2><div class="test-content"></div>`;
        this.element.querySelector('h2').textContent = this.title;
        this.element.querySelector('.test-content').innerHTML = this.content;
        this.element.querySelector('.test-close').addEventListener('click', () => this.closeCallback?.());
    }
    replaceCloseCallback(callback) { this.closeCallback = callback; }
    destroy() { this.element.remove(); this.underlay.remove(); }
} };

const names = ['Locomotive', 'Pink', 'White', 'Blue', 'Yellow', 'Orange', 'Black', 'Red', 'Green'];
const presets = {
    tunnel: { spaces: 4, routeColor: 0, color: 3, locomotives: 0, tunnel: true, extra: 2, hand: [2, 0, 0, 6] },
    ferryWaves: { spaces: 5, color: 6, ferryWaves: 3, ferryCards: 2, hand: [3, 0, 0, 0, 0, 0, 4] },
    northernLights: { spaces: 4, color: 3, locomotives: 2, matchingSet: 2, hand: [2, 0, 0, 4, 0, 2, 2] },
    nordicSpecial: { spaces: 4, color: 6, locomotives: 1, anySet: 3, hand: [2, 1, 1, 1, 0, 1, 4] },
    nordicFerry: { spaces: 5, color: 3, locomotives: 2, hand: [3, 0, 0, 5] },
    locomotiveOnly: { spaces: 3, routeColor: 0, color: 0, hand: [4] },
};
const scenarioLabels = {
    tunnel: 'Tunnel with extra cards',
    ferryWaves: 'Ferry waves (Italy)',
    northernLights: 'Northern Lights matching ferry sets',
    nordicSpecial: 'Nordic Countries any-card sets',
    nordicFerry: 'Nordic Countries classic ferry',
    locomotiveOnly: 'Locomotives only',
};
const form = document.getElementById('fixture');
const scenario = document.getElementById('scenario');
const routeColor = document.getElementById('route-color');
const paymentColor = document.getElementById('payment-color');
Object.entries(scenarioLabels).forEach(([value, label]) => scenario.add(new Option(label, value)));
routeColor.add(new Option('Gray (any color)', 0));
names.slice(1).forEach((name, index) => routeColor.add(new Option(name, index + 1)));
paymentColor.add(new Option('Locomotives only', 0));
names.slice(1).forEach((name, index) => paymentColor.add(new Option(name, index + 1)));
document.getElementById('hand-controls').innerHTML = names.map((name, index) =>
    `<label>${name} <input id="hand-${index}" type="number" min="0" max="20" value="0"></label>`).join('');

function applyPreset() {
    const data = presets[scenario.value];
    for (const key of ['spaces', 'locomotives', 'ferryWaves', 'anySet', 'matchingSet', 'extra', 'ferryCards']) {
        const id = key.replace(/[A-Z]/g, letter => `-${letter.toLowerCase()}`);
        document.getElementById(id).value = data[key] ?? 0;
    }
    routeColor.value = data.routeColor ?? data.color;
    paymentColor.value = data.color;
    syncPaymentColor();
    document.getElementById('tunnel').checked = Boolean(data.tunnel);
    document.getElementById('can-use-locomotives').checked = true;
    names.forEach((_, index) => { document.getElementById(`hand-${index}`).value = data.hand[index] ?? 0; });
}
function syncPaymentColor() {
    const coloredRoute = Number(routeColor.value) > 0;
    if (coloredRoute) { paymentColor.value = routeColor.value; }
    paymentColor.disabled = coloredRoute;
}
routeColor.addEventListener('change', syncPaymentColor);
scenario.addEventListener('change', applyPreset);
applyPreset();

form.addEventListener('submit', async event => {
    event.preventDefault();
    const number = id => Number(document.getElementById(id).value);
    const count = number('spaces');
    const hand = names.flatMap((_, type) => Array.from({ length: number(`hand-${type}`) }, () => ({ id: 0, type })));
    hand.forEach((card, index) => { card.id = index + 1; });
    const route = {
        id: 1, from: 1, to: 2, color: number('route-color'), locomotives: number('locomotives'),
        spaces: Array.from({ length: count }, () => ({ x: 0, y: 0, angle: 0, top: false })),
        tunnel: document.getElementById('tunnel').checked,
        ferryWaves: number('ferry-waves'), canPayWithAnySetOfCards: number('any-set'),
        canPayFerriesWithAnySetOfCards: number('matching-set'),
    };
    const popin = new DistributionPopin(hand, { route, color: number('payment-color'), distribution: null },
        count + number('extra'), document.getElementById('can-use-locomotives').checked, number('ferry-cards'));
    const result = await popin.show(scenarioLabels[scenario.value]);
    document.getElementById('result').textContent = result
        ? `Selected card IDs: ${result.cardIds.join(', ')}\nCard colors: ${result.cardIds.map(id => names[hand.find(card => card.id === id).type]).join(', ')}\nFerry cards: ${result.ferryCards}`
        : 'Cancelled';
});
