/** 
 * Player's train car cards.
 */ 
class PlayerTrainCars {
    public playerId: number;
    private left: boolean;
    private routeId: number | null = null;
    private selectable: boolean = false;
    private selectedColor: number | null = null;

    constructor(
        private game: TicketToRideGame, 
        player: TicketToRidePlayer,
        trainCars: TrainCar[]) {

        this.playerId = Number(player.id);

        this.addTrainCars(trainCars);

        console.log('bind tempDiv !')
        const tempDiv = document.getElementById(`player-table-${player.id}-train-cars`);
        tempDiv.addEventListener('dragenter', e => {
            console.log('tempDiv dragenter', e);
        });
        tempDiv.addEventListener('dragover', e => {
            console.log('tempDiv dragover', e);
        });
        tempDiv.addEventListener('dragleave', e => {
            console.log('tempDiv dragleave', e);
        });
        tempDiv.addEventListener('drop', e => {
            console.log('tempDiv drop', e);
        });
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
            card.style.transform = `rotate(${deg}deg)`;

            if (from) {
                const card = document.getElementById(`train-car-card-${trainCar.id}`);
                this.addAnimationFrom(card, group, from);
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
            `, `player-table-${this.playerId}-train-cars`);
            this.updateCounters();

            group = document.getElementById(`train-car-group-${type}`);

            group.addEventListener('dragstart', (e) => {
                console.log('dragstart', e);
                this.deselectColor(this.selectedColor);
                const dt = e.dataTransfer;
                dt.effectAllowed = 'move';
                
                document.getElementById('map').dataset.dragColor = ''+type;
                
                // we generate a clone of group (without positionning with transform on the group)
                const groupClone = document.createElement('div');
                groupClone.classList.add('train-car-group', 'drag');
                groupClone.innerHTML = group.innerHTML;
                document.body.appendChild(groupClone);
                groupClone.offsetHeight;

                dt.setDragImage(groupClone, -10, -25);
                setTimeout(() => document.body.removeChild(groupClone));
                
                //train-car-group-0
                setTimeout(() => {
                    group.classList.add('hide');
                }, 0);
            });
            group.addEventListener('dragend', (e) => {
                console.log('dragend', e);
                group.classList.remove('hide');
                document.getElementById('map').dataset.dragColor = '';
            });

            group.addEventListener('click', () => {
                if (this.routeId) {
                    this.game.claimRoute(this.routeId, type);
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
    private addAnimationFrom(card: HTMLElement, group: HTMLElement, from: HTMLElement) {
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
        setTimeout(() => card.style.transform = null, 0);

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

        return possibleColors;
    }
    /** 
     * Get the colors a player can use to claim a given route.
     */ 
     setSelectableTrainCarColors(routeId: number | null, possibleColors: number[] | null) {
        this.routeId = routeId;

        const groups = this.getGroups();

        groups.forEach(groupDiv => {
            if (routeId) {
                const color = Number(groupDiv.dataset.type);
                groupDiv.classList.toggle('disabled', color != 0 && !possibleColors.includes(color));
            } else {
                groupDiv.classList.remove('disabled');
            }
        });
    }
}