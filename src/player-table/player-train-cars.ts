const CROSSHAIR_SIZE = 20;

/** 
 * Player's train car cards.
 */ 
class PlayerTrainCars {
    public playerId: number;
    private left: boolean = true;
    private route: Route | null = null;
    private selectable: boolean = false;
    private selectedColor: number | null = null;

    constructor(
        private game: TicketToRideGame, 
        player: TicketToRidePlayer,
        trainCars: TrainCar[]) {

        this.playerId = Number(player.id);

        this.addTrainCars(trainCars);
    }
    
    /** 
     * Add train cars to player's hand.
     */ 
    public addTrainCars(trainCars: TrainCar[], from?: HTMLElement) {
        trainCars.forEach(trainCar => {
            const group = this.getGroup(trainCar.type);

            const deg = Math.round(-4 + Math.random() * 8);

            let card = document.getElementById(`train-car-card-${trainCar.id}`);
            const groupTrainCarCards = group.getElementsByClassName('train-car-cards')[0] as HTMLElement;
            if (!card) {
                let html = `
                <div id="train-car-card-${trainCar.id}" class="train-car-card" data-color="${trainCar.type}"></div>
                `;
                dojo.place(html, groupTrainCarCards);
                card = document.getElementById(`train-car-card-${trainCar.id}`);
            } else {
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
    public setPosition(left: boolean) {
        this.left = left;
        const div = document.getElementById(`player-table-${this.playerId}-train-cars`);
        div.classList.toggle('left', left);

        this.updateCounters(); // to realign
        this.updateColorBlindRotation();
    }
    
    /** 
     * Rotate 180° on train car cards, if they are on the left, and if color-blind option is on.
     */ 
     public updateColorBlindRotation(): void {
        const cards = Array.from(document.getElementById(`player-table-${this.playerId}-train-cars`).getElementsByClassName('train-car-card')) as HTMLDivElement[];
        cards.forEach(card => {
            const deg = Number(card.dataset.handRotation);
            const degWithColorBlind = this.left && this.game.isColorBlindMode() ? 180 + deg : deg;
            card.style.transform = `rotate(${degWithColorBlind}deg)`;
        });
    }
    
    /** 
     * Remove train cars from player's hand.
     */ 
    public removeCards(removeCards: TrainCar[]) {
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
    public setDraggable(draggable: boolean) {
        const groups = Array.from(document.getElementsByClassName('train-car-group')) as HTMLDivElement[];
        groups.forEach(groupDiv => groupDiv.setAttribute('draggable', draggable.toString()));
    }

    /** 
     * Set if train car cards can be selected by a click.
     */ 
     public setSelectable(selectable: boolean) {
        this.selectable = selectable;
        if (!selectable && this.selectedColor) {
            this.deselectColor(this.selectedColor);
        }
    }
    
    /** 
     * Return a group of cards (cards of the same color).
     * If it doesn't exists, create it.
     */ 
    private getGroup(type: number) {
        let group = document.getElementById(`train-car-group-${type}`);
        if (!group) {
            dojo.place(`
            <div id="train-car-group-${type}" class="train-car-group" data-type="${type}">
                <div id="train-car-group-${type}-counter" class="train-car-group-counter">0</div>
                <div id="train-car-group-${type}-cards" class="train-car-cards"></div>
            </div>
            `, `player-table-${this.playerId}-train-cars`, type == 0 ? 'first' : 'last');
            this.updateCounters();

            group = document.getElementById(`train-car-group-${type}`);

            group.addEventListener('dragstart', (e) => {
                this.deselectColor(this.selectedColor);
                const dt = e.dataTransfer;
                dt.effectAllowed = 'move';
                dt.setData('text/plain', ''+type);
                
                const mapDiv = document.getElementById('map');
                mapDiv.dataset.dragColor = ''+type;
                
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
                group.classList.remove('hide');
                const mapDiv = document.getElementById('map');
                mapDiv.dataset.dragColor = '';

                this.game.map.removeDragOverlay();
            });

            group.addEventListener('click', () => {
                if (this.route) {
                    this.game.askRouteClaimConfirmation(this.route, type);
                } else if (this.selectable) {
                    if (this.selectedColor === type) {
                        this.deselectColor(type);
                    } else if (this.selectedColor !== null) {
                        this.deselectColor(this.selectedColor);
                        this.selectColor(type);
                    } else {
                        this.selectColor(type);
                    }
                }
            }); 
        }
        return group;
    }

    public getSelectedColor(): number | null {
        return this.selectedColor;
    }

    private selectColor(color: number) {
        const group = document.getElementById(`train-car-group-${color}`);
        group?.classList.add('selected');
        this.selectedColor = color;
    }

    private deselectColor(color: number) {
        if (color === null) {
            return;
        }

        const group = document.getElementById(`train-car-group-${color}`);
        group?.classList.remove('selected');
        this.selectedColor = null;
    }

    private getGroups(): HTMLDivElement[] {
        return Array.from(document.getElementsByClassName('train-car-group')) as HTMLDivElement[];
    }

    /** 
     * Update counters on color groups.
     */ 
    private updateCounters() {
        const groups = this.getGroups();

        const middleIndex = (groups.length - 1) / 2;

        groups.forEach((groupDiv, index) => {
            const distanceFromIndex = index - middleIndex;
            const count = groupDiv.getElementsByClassName('train-car-card').length;
            groupDiv.dataset.count = ''+count;
            groupDiv.getElementsByClassName('train-car-group-counter')[0].innerHTML = `${count > 1 ? count : ''}`;
            const angle = distanceFromIndex * 4;
            groupDiv.dataset.angle = ''+angle;
            groupDiv.style.transform = `translate${this.left ? 'X(-' : 'Y('}${Math.pow(Math.abs(distanceFromIndex) * 2, 2)}px) rotate(${angle}deg)`;
            groupDiv.parentNode.appendChild(groupDiv);
        });
    }
    
    /** 
     * Add an animation to the card (when it is created).
     */ 
    private addAnimationFrom(card: HTMLElement, group: HTMLElement, from: HTMLElement, deg: number, degWithColorBlind: number) {
        if (document.visibilityState === 'hidden' || (this.game as any).instantaneousMode) {
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
        card.style.transform = `rotate(${this.left ? angle : angle-90}deg) translate(${-deltaX/zoom}px, ${-deltaY/zoom}px)`;
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
    public getPossibleColors(route: Route): number[] {
        const groups = this.getGroups();

        const locomotiveGroup = groups.find(groupDiv => groupDiv.dataset.type == '0');
        const locomotives = locomotiveGroup ? Number(locomotiveGroup.dataset.count) : 0;

        const possibleColors = [];

        groups.forEach(groupDiv => {
            const count = Number(groupDiv.dataset.count);
            if (count + locomotives >= route.spaces.length) {
                const color = Number(groupDiv.dataset.type);
                if (color > 0) {
                    possibleColors.push(color);
                }
            } 
        });

        if (locomotives >= route.spaces.length) {
            possibleColors.push(0);
        }

        return possibleColors;
    }
    /** 
     * Get the colors a player can use to claim a given route.
     */ 
     setSelectableTrainCarColors(route: Route | null, possibleColors: number[] | null) {
        this.route = route;

        const groups = this.getGroups();

        groups.forEach(groupDiv => {
            if (route) {
                const color = Number(groupDiv.dataset.type);
                groupDiv.classList.toggle('disabled', color != 0 && !possibleColors.includes(color));
            } else {
                groupDiv.classList.remove('disabled');
            }
        });
    }
}