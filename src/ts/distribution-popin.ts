export class DistributionResult {
    public cardIds: number[];
    public locomotivesOnly: boolean;

    constructor(
        distributionCards: number[][],
        public auto: boolean = false,
        public ferryCards: number = 0,
    ) {
        this.cardIds = distributionCards.flat();
        const hasColorCards = Object.entries(distributionCards).some(([type, cards]) => Number(type) > 0 && Number(type) < 99 && cards.length > 0);
        this.locomotivesOnly = !hasColorCards;
    }
}

export class DistributionPopin {
    private distributionCards: number[][] = [];
    private selectedFerryCards: number[] = [];

    constructor(
        public trainCarsHand: TrainCar[], 
        public claimingRoute: ClaimingRoute, 
        public cost: number,
        public canUseLocomotives: boolean,
        public ferryCardsCount: number = 0,
    ) {}

    public show(title: string): Promise<DistributionResult | null> {
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

            let colorCards: TrainCar[] = null;
            let minColorCards = 0;
            let maxColorCards = 0;
            if (this.claimingRoute.color > 0) {
                colorCards = this.trainCarsHand.filter(card => card.type == this.claimingRoute.color).slice(0, this.cost);
                minColorCards = isFerry || this.claimingRoute.route.canPayWithAnySetOfCards > 0 ? 0 : Math.max(0, Math.min(this.cost - minLocomotives, this.cost - maxLocomotives));
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
            
            let html = ``;
            if (showLocomotives) {
                this.distributionCards[0] = [];
                if (this.claimingRoute.route.locomotives) {
                    html += `${_('${number} locomotives required').replace('${number}', `${this.claimingRoute.route.locomotives}`)}<br>`
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
                    } else {
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
                    } else {
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

            this.updateTotal();

            const closeFn = (result: number[][] | null) => { resolve(result ? new DistributionResult(result, false, this.selectedFerryCards.length) : null); distributionDlg.destroy(); };
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

    private showMatchingFerrySets(title: string): Promise<DistributionResult | null> {
        this.distributionCards = [];
        this.selectedFerryCards = [];
        return new Promise(resolve => {
            const dialog = new ebg.popindialog();
            dialog.create('distributionPopin');
            dialog.setTitle(title);
            let html = `<p>${_('Each ferry Locomotive symbol can be paid with a Locomotive or a set of ${number} cards of the same color. The remaining spaces require cards of one color, with Locomotives as wild cards.')
                .replace('${number}', String(this.claimingRoute.route.canPayFerriesWithAnySetOfCards))}</p>`;
            const displayedCards: TrainCar[] = [];
            for (let color = 0; color <= 8; color++) {
                const cards = this.trainCarsHand.filter(card => card.type === color && (color > 0 || this.canUseLocomotives));
                this.distributionCards[color] = [];
                if (!cards.length) { continue; }
                displayedCards.push(...cards);
                html += this.cardSection(cards, null);
            }
            html += `<div class="total">${_('Total')} : <span id="distribution-current-size">0</span> / ${this.cost}</div>
                <button id="confirmDistribution-btn" class="bgabutton bgabutton_blue">${_('Confirm')}</button>
                <button id="cancelDistribution-btn" class="bgabutton bgabutton_gray">${_('Cancel')}</button>`;
            dialog.setContent(html);
            dialog.show();
            displayedCards.forEach(card => {
                const element = document.getElementById(`distribution-${card.id}`);
                element.classList.add('selectable');
                element.addEventListener('click', () => this.onDistributionCardClick(card.id, card.type));
                if (this.claimingRoute.distribution?.includes(card.id)) {
                    element.classList.add('selected');
                    this.distributionCards[card.type].push(card.id);
                }
            });
            const close = (confirm: boolean) => {
                resolve(confirm ? new DistributionResult(this.distributionCards) : null);
                dialog.destroy();
            };
            dialog.replaceCloseCallback(() => close(false));
            document.getElementById('cancelDistribution-btn').addEventListener('click', () => close(false));
            document.getElementById('confirmDistribution-btn').addEventListener('click', () => close(true));
            this.updateTotal();
        });
    }

    private matchingFerrySelection(): {total: number; valid: boolean} {
        const counts = Array.from({length: 9}, (_, color) => this.distributionCards[color]?.length ?? 0);
        const size = this.claimingRoute.route.canPayFerriesWithAnySetOfCards;
        const required = this.claimingRoute.route.locomotives;
        const physicalCount = counts.reduce((sum, count) => sum + count, 0);
        const sets = (physicalCount - this.cost) / (size - 1);
        const regular = this.cost - sets - counts[0];
        const baseColor = this.claimingRoute.color;
        const remaining = [...counts];
        if (baseColor > 0) { remaining[baseColor] -= regular; }
        const valid = Number.isInteger(sets) && sets >= 0 && sets <= required
            && counts[0] + sets >= required && regular >= 0 && regular <= this.cost - required
            && (baseColor > 0 ? remaining[baseColor] >= 0 : regular === 0)
            && remaining.slice(1).every(count => count % size === 0)
            && remaining.slice(1).reduce((sum, count) => sum + count / size, 0) === sets;

        const normalCards = baseColor > 0 ? Math.min(counts[baseColor], this.cost - required) : 0;
        const forSets = [...counts];
        if (baseColor > 0) { forSets[baseColor] -= normalCards; }
        const total = counts[0] + normalCards + forSets.slice(1).reduce((sum, count) => sum + Math.floor(count / size), 0);
        return {total: valid ? this.cost : total, valid};
    }

    private cardSection(cards: TrainCar[], colorForUseMaximum: number | null): string {
        return `
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>${cards.map(card => `<div class="train-car-color icon" data-color="${card.type}" id="distribution-${card.id}"></div>`).join('')}</div>
                ${colorForUseMaximum !== null ? `<div><button id="use-maximum-${colorForUseMaximum}-btn" class="bgabutton bgabutton_gray" style="width: auto;">${_('Use maximum of ${color}').replace('${color}', `<div class="train-car-color icon" data-color="${colorForUseMaximum}"></div>`)}</button></div>` : ''}
            </div><hr/>
        `;
    }

    private getSelectedCardCount(locomotivesAndSetOnly: boolean = false): number {
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
        if (this.claimingRoute.route.canPayFerriesWithAnySetOfCards > 0) {
            const {total, valid} = this.matchingFerrySelection();
            element.innerText = String(total);
            element.dataset.valid = JSON.stringify(valid);
            (document.getElementById('confirmDistribution-btn') as HTMLButtonElement).disabled = !valid;
            return;
        }
        const selectedCardCount = this.getSelectedCardCount();
        const isFerry = this.claimingRoute.route.ferryWaves > 0;
        const ferryCardValue = isFerry
            ? Math.min(2 * this.selectedFerryCards.length, this.claimingRoute.route.ferryWaves, Math.max(0, this.cost - selectedCardCount))
            : 0;
        const total = selectedCardCount + ferryCardValue;
        element.innerText = `${total}`;
        const validCount = isFerry
            ? total === this.cost && ferryCardValue >= this.selectedFerryCards.length
            : total === this.cost;
        element.dataset.valid = JSON.stringify(validCount);
        let valid = validCount && (isFerry
            ? (this.distributionCards[0]?.length ?? 0) >= this.claimingRoute.route.ferryWaves - ferryCardValue
            : this.getSelectedCardCount(true) >= this.claimingRoute.route.locomotives);
        if (this.claimingRoute.route.canPayWithAnySetOfCards && this.distributionCards[99]) {
            if (this.distributionCards[99].length % this.claimingRoute.route.canPayWithAnySetOfCards !== 0) {
                valid = false;
            }
        }
        (document.getElementById('confirmDistribution-btn') as HTMLButtonElement).disabled = !valid;

        const useMax0 = document.getElementById(`use-maximum-0-btn`);
        const useMaxColor = document.getElementById(`use-maximum-${this.claimingRoute.color}-btn`);
        if (useMax0) {
            (useMax0 as HTMLButtonElement).disabled = selectedCardCount >= this.cost;
        }
        if (useMaxColor) {
            (useMaxColor as HTMLButtonElement).disabled = selectedCardCount >= this.cost;
        }
    }

    onDistributionCardClick(cardId: number, type: number) {
        const element = document.getElementById(`distribution-${cardId}`);
        if (this.distributionCards[type].includes(cardId)) {
            element.classList.remove('selected');
            this.distributionCards[type] = this.distributionCards[type].filter(id => id != cardId);
        } else {
            const setSize = this.claimingRoute.route.canPayFerriesWithAnySetOfCards;
            const full = setSize > 0
                ? this.distributionCards.flat().length >= this.cost + this.claimingRoute.route.locomotives * (setSize - 1)
                : this.getSelectedCardCount() >= this.cost - this.selectedFerryCards.length;
            if (full) {
                return;
            }
            element.classList.add('selected');
            this.distributionCards[type].push(cardId);
        }
        this.updateTotal();
    }

    private onFerryCardClick(index: number) {
        const element = document.getElementById(`distribution-ferry-${index}`);
        if (this.selectedFerryCards.includes(index)) {
            this.selectedFerryCards = this.selectedFerryCards.filter(selectedIndex => selectedIndex !== index);
            element.classList.remove('selected');
        } else {
            this.selectedFerryCards.push(index);
            element.classList.add('selected');
        }
        this.updateTotal();
    }

    useMaximum(color: number) {
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
