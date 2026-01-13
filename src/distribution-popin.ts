import { ClaimingRoute, TrainCar } from "./tickettoride.d";

export class DistributionResult {
    public cardIds: number[];
    public locomotivesOnly: boolean;

    constructor(
        distributionCards: number[][],
        public auto: boolean = false,
    ) {
        this.cardIds = distributionCards.flat();
        const colorKey = Object.keys(distributionCards).map(Number).filter(n => ![0,99].includes(n))[0];
        const hasColorCards = colorKey && distributionCards[colorKey].length > 0;
        this.locomotivesOnly = !hasColorCards;
    }
}

export class DistributionPopin {
    private distributionCards: number[][] = [];

    constructor(
        public trainCarsHand: TrainCar[], 
        public claimingRoute: ClaimingRoute, 
        public cost: number,
        public canUseLocomotives: boolean
    ) {}

    public show(title: string): Promise<DistributionResult | null> {
        this.distributionCards = [];
        return new Promise(resolve => {
            
            const distributionDlg = new ebg.popindialog();
            distributionDlg.create('distributionPopin');
            distributionDlg.setTitle(title);


            const locomotiveCards = this.canUseLocomotives ? this.trainCarsHand.filter(card => card.type == 0).slice(0, this.cost) : [];
            let minLocomotives = this.claimingRoute.route.canPayWithAnySetOfCards > 0 ? 0 : this.claimingRoute.route.locomotives;
            const maxLocomotives = this.canUseLocomotives ? locomotiveCards.length : 0;
            const showLocomotives = this.canUseLocomotives ? (minLocomotives > 0 || maxLocomotives > 0) : false;

            let colorCards: TrainCar[] = null;
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
            const showColorCards = minColorCards > 0 || maxColorCards > 0;

            const locomotiveCardsToDisplay = locomotiveCards.slice(0, maxLocomotives);
            const colorCardsToDisplay = colorCards.slice(0, maxColorCards);
            const singleCards = [...locomotiveCardsToDisplay, ...colorCardsToDisplay];

            const otherCardsForSet = this.trainCarsHand.filter(card => !singleCards.some(sc => sc.id == card.id));
            const showSet = this.claimingRoute.route.canPayWithAnySetOfCards > 0 && otherCardsForSet.length >= this.claimingRoute.route.canPayWithAnySetOfCards;
            
            let html = ``;
            if (showLocomotives) {
                this.distributionCards[0] = [];
                if (this.claimingRoute.route.locomotives) {
                    html += `${_('${number} locomotives required').replace('${number}', `${this.claimingRoute.route.locomotives}`)}<br>`
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
                    } else {
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
                    } else {
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

            const closeFn = (result: number[][] | null) => { resolve(result ? new DistributionResult(result) : null); distributionDlg.destroy(); };
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
            if (this.getSelectedCardCount() >= this.cost) {
                return;
            }
            element.classList.add('selected');
            this.distributionCards[type].push(cardId);
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