class DistributionResult {
    constructor(distributionCards, auto = false, ferryCards = 0) {
        this.auto = auto;
        this.ferryCards = ferryCards;
        this.cardIds = distributionCards.flat();
        const hasColorCards = Object.entries(distributionCards).some(([type, cards]) => Number(type) > 0 && Number(type) < 99 && cards.length > 0);
        this.locomotivesOnly = !hasColorCards;
    }
}
class DistributionPopin {
    constructor(trainCarsHand, claimingRoute, cost, canUseLocomotives, ferryCardsCount = 0) {
        this.trainCarsHand = trainCarsHand;
        this.claimingRoute = claimingRoute;
        this.cost = cost;
        this.canUseLocomotives = canUseLocomotives;
        this.ferryCardsCount = ferryCardsCount;
        this.distributionCards = [];
        this.selectedFerryCards = [];
    }
    show(title) {
        if (this.claimingRoute.route.canPayFerriesWithAnySetOfCards > 0) {
            return this.showMatchingFerrySets(title);
        }
        this.distributionCards = [];
        this.selectedFerryCards = [];
        return new Promise(resolve => {
            const distributionDlg = new ebg.popindialog();
            distributionDlg.create('distributionPopin');
            distributionDlg.setTitle(title);
            const isFerry = this.claimingRoute.route.ferryWaves > 0;
            const locomotiveCards = this.canUseLocomotives ? this.trainCarsHand.filter(card => card.type == 0).slice(0, this.cost) : [];
            let minLocomotives = isFerry || this.claimingRoute.route.canPayWithAnySetOfCards > 0 ? 0 : this.claimingRoute.route.locomotives;
            const maxLocomotives = this.canUseLocomotives ? locomotiveCards.length : 0;
            const showLocomotives = this.canUseLocomotives ? (minLocomotives > 0 || maxLocomotives > 0) : false;
            let colorCards = null;
            let minColorCards = 0;
            let maxColorCards = 0;
            if (this.claimingRoute.color > 0) {
                colorCards = this.trainCarsHand.filter(card => card.type == this.claimingRoute.color).slice(0, this.cost);
                minColorCards = isFerry || this.claimingRoute.route.canPayWithAnySetOfCards > 0 ? 0 : Math.max(0, Math.min(colorCards.length, this.cost - minLocomotives, this.cost - maxLocomotives));
                maxColorCards = Math.min(isFerry ? this.cost - this.claimingRoute.route.ferryWaves : this.cost - this.claimingRoute.route.locomotives, colorCards.length);
                if (!isFerry && !this.claimingRoute.route.canPayWithAnySetOfCards && maxColorCards < this.cost) {
                    minLocomotives = Math.min(maxLocomotives, this.cost - maxColorCards);
                }
            }
            const showColorCards = this.claimingRoute.color > 0 && (minColorCards > 0 || maxColorCards > 0);
            const locomotiveCardsToDisplay = locomotiveCards.slice(0, maxLocomotives);
            const colorCardsToDisplay = colorCards?.slice(0, maxColorCards);
            const singleCards = [...locomotiveCardsToDisplay, ...(colorCardsToDisplay ?? [])];
            const otherCardsForSet = this.trainCarsHand.filter(card => !singleCards.some(sc => sc.id == card.id));
            const showSet = this.claimingRoute.route.canPayWithAnySetOfCards > 0 && otherCardsForSet.length >= this.claimingRoute.route.canPayWithAnySetOfCards;
            const showUseMaximum = !isFerry && !(this.claimingRoute.route.canPayWithAnySetOfCards > 0);
            let html = this.routeSpacePreview();
            if (showLocomotives) {
                this.distributionCards[0] = [];
                if (this.claimingRoute.route.locomotives) {
                    html += `${_('${number} locomotives required').replace('${number}', `${this.claimingRoute.route.locomotives}`)}<br>`;
                }
                html += this.cardSection(locomotiveCardsToDisplay, showUseMaximum ? 0 : null);
            }
            if (showColorCards) {
                this.distributionCards[this.claimingRoute.color] = [];
                html += this.cardSection(colorCardsToDisplay, showUseMaximum ? this.claimingRoute.color : null);
            }
            if (this.claimingRoute.route.canPayWithAnySetOfCards > 0) {
                this.distributionCards[99] = [];
                html += `${_('Any set of ${number} cards').replace('${number}', `${this.claimingRoute.route.canPayWithAnySetOfCards}`)}<br>` + this.cardSection(otherCardsForSet, null);
            }
            if (isFerry && this.ferryCardsCount > 0) {
                html += `<div class="ferry-card-selection">
                    ${Array.from({ length: Math.min(this.ferryCardsCount, this.claimingRoute.route.ferryWaves) }, (unused, index) => `<div role="button" id="distribution-ferry-${index}" class="icon ferry-card-icon selectable"></div>`).join('')}
                </div><hr/>`;
            }
            html += `
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
                if (showUseMaximum) {
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
                if (showUseMaximum) {
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
            if (isFerry) {
                Array.from({ length: Math.min(this.ferryCardsCount, this.claimingRoute.route.ferryWaves) }, (_, index) => index).forEach(index => {
                    document.getElementById(`distribution-ferry-${index}`).addEventListener('click', () => this.onFerryCardClick(index));
                });
            }
            this.updateSelection();
            const closeFn = (result) => { resolve(result ? new DistributionResult(result, false, this.selectedFerryCards.length) : null); distributionDlg.destroy(); };
            distributionDlg.replaceCloseCallback(() => closeFn(null));
            document.getElementById('confirmDistribution-btn').addEventListener('click', () => closeFn(this.distributionCards));
            document.getElementById('cancelDistribution-btn').addEventListener('click', () => closeFn(null));
            if (!isFerry && (minLocomotives + minColorCards) === this.cost) {
                // all possible cards are preselected, meaning the player doesn't have a choice
                resolve(new DistributionResult(this.distributionCards, true));
                distributionDlg.destroy();
            }
        });
    }
    showMatchingFerrySets(title) {
        this.distributionCards = [];
        this.selectedFerryCards = [];
        return new Promise(resolve => {
            const dialog = new ebg.popindialog();
            dialog.create('distributionPopin');
            dialog.setTitle(title);
            let html = `<p>${_('Each ferry Locomotive symbol can be paid with a Locomotive or a set of ${number} cards of the same color. The remaining spaces require cards of one color, with Locomotives as wild cards.')
                .replace('${number}', String(this.claimingRoute.route.canPayFerriesWithAnySetOfCards))}</p>`;
            html += this.routeSpacePreview();
            const displayedCards = [];
            for (let color = 0; color <= 8; color++) {
                const cards = this.trainCarsHand.filter(card => card.type === color && (color > 0 || this.canUseLocomotives));
                this.distributionCards[color] = [];
                if (!cards.length) {
                    continue;
                }
                displayedCards.push(...cards);
                html += this.cardSection(cards, null);
            }
            html += `<button id="confirmDistribution-btn" class="bgabutton bgabutton_blue">${_('Confirm')}</button>
                <button id="cancelDistribution-btn" class="bgabutton bgabutton_gray">${_('Cancel')}</button>`;
            dialog.setContent(html);
            dialog.show();
            displayedCards.forEach(card => {
                const element = document.getElementById(`distribution-${card.id}`);
                const matchingCardsInHand = this.trainCarsHand.filter(handCard => handCard.type === card.type).length;
                if (card.type !== 0 && card.type !== this.claimingRoute.color && matchingCardsInHand < this.claimingRoute.route.canPayFerriesWithAnySetOfCards) {
                    element.classList.add('grayed');
                    element.title = _('Not enough cards of this color to make a ferry pair');
                    return;
                }
                element.classList.add('selectable');
                element.addEventListener('click', () => this.onDistributionCardClick(card.id, card.type));
                if (this.claimingRoute.distribution?.includes(card.id)) {
                    element.classList.add('selected');
                    this.distributionCards[card.type].push(card.id);
                }
            });
            const close = (confirm) => {
                resolve(confirm ? new DistributionResult(this.distributionCards) : null);
                dialog.destroy();
            };
            dialog.replaceCloseCallback(() => close(false));
            document.getElementById('cancelDistribution-btn').addEventListener('click', () => close(false));
            document.getElementById('confirmDistribution-btn').addEventListener('click', () => close(true));
            this.updateSelection();
        });
    }
    matchingFerrySelection() {
        const counts = Array.from({ length: 9 }, (_, color) => this.distributionCards[color]?.length ?? 0);
        const size = this.claimingRoute.route.canPayFerriesWithAnySetOfCards;
        const required = this.claimingRoute.route.locomotives;
        const baseColor = this.claimingRoute.color;
        const regularSpaces = this.cost - required;
        const selected = counts.reduce((sum, count) => sum + count, 0);
        if (regularSpaces < 0) {
            return { payments: [], incomplete: [], total: 0, valid: false };
        }
        let best = null;
        // Try the possible splits of locomotives and base-color cards. A ferry set
        // is built only from cards of one color, and can fill only a ferry symbol.
        for (let ferryLocomotives = 0; ferryLocomotives <= Math.min(counts[0], required); ferryLocomotives++) {
            for (let regularCards = 0; regularCards <= Math.min(baseColor > 0 ? counts[baseColor] : 0, regularSpaces); regularCards++) {
                const regularLocomotives = Math.min(counts[0] - ferryLocomotives, regularSpaces - regularCards);
                const sets = [];
                for (let type = 1; type <= 8; type++) {
                    const available = counts[type] - (type === baseColor ? regularCards : 0);
                    for (let index = 0; index < Math.floor(available / size) && sets.length < required - ferryLocomotives; index++) {
                        sets.push(Array(size).fill(type));
                    }
                }
                const payments = Array.from({ length: this.cost }, () => []);
                for (let index = 0; index < ferryLocomotives; index++) {
                    payments[index] = [0];
                }
                sets.forEach((set, index) => { payments[ferryLocomotives + index] = set; });
                for (let index = 0; index < regularLocomotives; index++) {
                    payments[required + index] = [0];
                }
                for (let index = 0; index < regularCards; index++) {
                    payments[required + regularLocomotives + index] = [baseColor];
                }
                const total = payments.filter(payment => payment.length > 0).length;
                const used = ferryLocomotives + regularLocomotives + regularCards + sets.length * size;
                const unassigned = selected - used;
                const candidate = { payments, total, valid: total === this.cost && unassigned === 0, unassigned };
                if (!best || candidate.total > best.total || (candidate.total === best.total && candidate.unassigned < best.unassigned)) {
                    best = candidate;
                }
            }
        }
        const remaining = [...counts];
        best.payments.forEach(payment => payment.forEach(type => remaining[type]--));
        const incomplete = Array(this.cost).fill(false);
        for (let type = 0; type <= 8; type++) {
            while (remaining[type] > 0) {
                const emptyFerry = best.payments.findIndex((payment, index) => index < required && payment.length === 0);
                const emptyRegular = best.payments.findIndex((payment, index) => index >= required && payment.length === 0);
                const target = emptyFerry >= 0 ? emptyFerry : emptyRegular >= 0 ? emptyRegular : Math.max(0, required - 1);
                if (!best.payments[target]) {
                    break;
                }
                const cardsToShow = Math.min(remaining[type], target < required && type > 0 ? size : 1);
                best.payments[target].push(...Array(cardsToShow).fill(type));
                incomplete[target] = true;
                remaining[type] -= cardsToShow;
            }
        }
        return { payments: best.payments, incomplete, total: best.total, valid: best.valid };
    }
    cardSection(cards, colorForUseMaximum) {
        return `
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>${cards.map(card => `<div class="train-car-color icon" data-color="${card.type}" id="distribution-${card.id}"></div>`).join('')}</div>
                ${colorForUseMaximum !== null ? `<div><button id="use-maximum-${colorForUseMaximum}-btn" class="bgabutton bgabutton_gray" style="width: auto;">${_('Use maximum of ${color}').replace('${color}', `<div class="train-car-color icon" data-color="${colorForUseMaximum}"></div>`)}</button></div>` : ''}
            </div><hr/>
        `;
    }
    routeSpacePreview() {
        const route = this.claimingRoute.route;
        const routeLength = route.spaces?.length ?? this.cost;
        const spaces = Math.max(routeLength, this.cost);
        return `<div class="distribution-route-preview" data-color="${this.claimingRoute.color}">
            <div id="distribution-route-spaces" class="distribution-route-spaces" aria-live="polite">
                ${Array.from({ length: spaces }, (unused, index) => {
            const extra = index >= routeLength;
            const logoType = extra ? 'extra-tunnel' : index < (route.ferryWaves || 0) ? 'ferry-wave' : index < route.locomotives ? 'locomotive' : 'regular';
            const label = extra ? _('Extra tunnel card') : index < (route.ferryWaves || 0) ? _('Wave space') : index < route.locomotives ? _('Locomotive space') : _('Route space');
            return `<div class="distribution-route-slot">
                        <div class="distribution-route-space${extra ? ' extra' : ''}" title="${label}" aria-label="${label} ${index + 1}"><span class="distribution-space-logo" data-type="${logoType}" aria-hidden="true"></span></div>
                        <div class="distribution-space-cards"></div>
                    </div>`;
        }).join('')}
            </div>
        </div>`;
    }
    updateRouteSpacePreview() {
        const spaces = Array.from(document.querySelectorAll('#distribution-route-spaces .distribution-route-space'));
        document.querySelectorAll('#distribution-route-spaces .distribution-ferry-pair').forEach(element => element.remove());
        const payments = [];
        const card = (type) => `<span class="train-car-color icon" data-color="${type}"></span>`;
        const route = this.claimingRoute.route;
        if (route.canPayFerriesWithAnySetOfCards) {
            const selection = this.matchingFerrySelection();
            spaces.forEach((space, index) => {
                const payment = selection.payments[index] || [];
                space.parentElement.querySelector('.distribution-space-cards').innerHTML = payment.map(card).join('');
                space.classList.toggle('paid', payment.length > 0 && !selection.incomplete[index]);
                space.classList.toggle('incomplete', Boolean(selection.incomplete[index]));
            });
            return;
        }
        const locomotiveCards = this.distributionCards[0] || [];
        let colorCards = this.claimingRoute.color > 0 ? (this.distributionCards[this.claimingRoute.color] || []) : [];
        let setCards = [];
        if (route.canPayWithAnySetOfCards) {
            const size = route.canPayWithAnySetOfCards;
            const ids = this.distributionCards[99] || [];
            setCards = Array.from({ length: Math.ceil(ids.length / size) }, (_, index) => ids.slice(index * size, (index + 1) * size).map(id => card(this.trainCarsHand.find(trainCar => trainCar.id === id).type)));
        }
        let locomotiveIndex = 0;
        let setIndex = 0;
        const ferryCardsUsed = this.selectedFerryCards.length;
        const coveredWaves = Math.min(route.ferryWaves || 0, ferryCardsUsed * 2, Math.max(ferryCardsUsed, this.cost - this.getSelectedCardCount()));
        const doubleCoveredCards = Math.max(0, coveredWaves - ferryCardsUsed);
        const pairedWaveStarts = [];
        for (let index = 0; index < spaces.length; index++) {
            if (index < coveredWaves) {
                if (index < doubleCoveredCards * 2) {
                    payments[index] = [];
                    if (index % 2 === 0) {
                        pairedWaveStarts.push(index);
                    }
                }
                else {
                    payments[index] = [`<span class="icon ferry-card-icon" title="${_('Ferry card')}"></span>`];
                }
            }
            else if (index < route.locomotives || index < (route.ferryWaves || 0)) {
                if (locomotiveIndex < locomotiveCards.length) {
                    payments[index] = [card(0)];
                    locomotiveIndex++;
                }
                else if (setIndex < setCards.length) {
                    payments[index] = setCards[setIndex++];
                }
            }
        }
        const remaining = [
            ...locomotiveCards.slice(locomotiveIndex).map(() => [card(0)]),
            ...colorCards.map(() => [card(this.claimingRoute.color)]),
            ...setCards.slice(setIndex),
        ];
        for (let index = 0; index < spaces.length && remaining.length; index++) {
            if (!payments[index]) {
                payments[index] = remaining.shift();
            }
        }
        spaces.forEach((space, index) => {
            space.parentElement.querySelector('.distribution-space-cards').innerHTML = (payments[index] || []).join('');
            space.classList.toggle('paid', index < coveredWaves || Boolean(payments[index]));
        });
        pairedWaveStarts.forEach(index => {
            spaces[index].parentElement.insertAdjacentHTML('beforeend', `<span class="distribution-ferry-pair" title="${_('Ferry card covering two waves')}"><span class="icon ferry-card-icon"></span></span>`);
        });
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
    maximumUsefulFerryCards() {
        const uncoveredSpaces = Math.max(0, this.cost - this.getSelectedCardCount());
        return Math.ceil(Math.min(this.claimingRoute.route.ferryWaves, uncoveredSpaces) / 2);
    }
    refreshFerryCards() {
        const maximum = this.maximumUsefulFerryCards();
        while (this.selectedFerryCards.length > maximum) {
            const index = this.selectedFerryCards.pop();
            document.getElementById(`distribution-ferry-${index}`).classList.remove('selected');
        }
        for (let index = 0; index < Math.min(this.ferryCardsCount, this.claimingRoute.route.ferryWaves); index++) {
            const element = document.getElementById(`distribution-ferry-${index}`);
            const available = this.selectedFerryCards.includes(index) || this.selectedFerryCards.length < maximum;
            element.classList.toggle('selectable', available);
            element.classList.toggle('grayed', !available);
        }
    }
    updateSelection() {
        if (this.claimingRoute.route.ferryWaves > 0) {
            this.refreshFerryCards();
        }
        this.updateRouteSpacePreview();
        if (this.claimingRoute.route.canPayFerriesWithAnySetOfCards > 0) {
            const { valid } = this.matchingFerrySelection();
            document.getElementById('confirmDistribution-btn').disabled = !valid;
            return;
        }
        const selectedCardCount = this.getSelectedCardCount();
        const isFerry = this.claimingRoute.route.ferryWaves > 0;
        const ferryCardValue = isFerry
            ? Math.min(2 * this.selectedFerryCards.length, this.claimingRoute.route.ferryWaves, Math.max(0, this.cost - selectedCardCount))
            : 0;
        const total = selectedCardCount + ferryCardValue;
        const validCount = isFerry
            ? total === this.cost && this.selectedFerryCards.length === Math.ceil(ferryCardValue / 2)
            : total === this.cost;
        let valid = validCount && (isFerry
            ? (this.distributionCards[0]?.length ?? 0) >= this.claimingRoute.route.ferryWaves - ferryCardValue
            : this.getSelectedCardCount(true) >= this.claimingRoute.route.locomotives);
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
            const setSize = this.claimingRoute.route.canPayFerriesWithAnySetOfCards;
            const full = setSize > 0
                ? this.distributionCards.flat().length >= this.cost + this.claimingRoute.route.locomotives * (setSize - 1)
                : this.getSelectedCardCount() >= this.cost;
            if (full) {
                return;
            }
            element.classList.add('selected');
            this.distributionCards[type].push(cardId);
        }
        this.updateSelection();
    }
    onFerryCardClick(index) {
        const element = document.getElementById(`distribution-ferry-${index}`);
        if (this.selectedFerryCards.includes(index)) {
            this.selectedFerryCards = this.selectedFerryCards.filter(selectedIndex => selectedIndex !== index);
            element.classList.remove('selected');
        }
        else {
            if (this.selectedFerryCards.length >= this.maximumUsefulFerryCards()) {
                return;
            }
            this.selectedFerryCards.push(index);
            element.classList.add('selected');
        }
        this.updateSelection();
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
            this.distributionCards[otherColor].push(card.id);
        });
        this.updateSelection();
    }
}

export { DistributionPopin, DistributionResult };
