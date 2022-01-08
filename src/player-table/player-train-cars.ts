class PlayerTrainCars {
    public playerId: number;
    private left: boolean;

    constructor(
        private game: TicketToRideGame, 
        player: TicketToRidePlayer,
        trainCars: TrainCar[]) {

        this.playerId = Number(player.id);

        this.addTrainCars(trainCars);
    }
    
    public addTrainCars(trainCars: TrainCar[], stocks?: TrainCarSelection) {
        trainCars.forEach(trainCar => {
            const group = this.getGroup(trainCar.type);

            const xBackgroundPercent = trainCar.type * 100;
            const deg = Math.round(-4 + Math.random() * 8);

            let html = `
            <div id="train-car-card-${trainCar.id}" class="train-car-card" style="background-position: -${xBackgroundPercent}% 50%; transform: rotate(${deg}deg);"></div>
            `;

            dojo.place(html, group.getElementsByClassName('train-car-cards')[0] as HTMLElement);

            const originStock = stocks?.visibleCardsStocks?.find(stock => stock?.items.some(item => Number(item.id) == trainCar.id));
            const card = document.getElementById(`train-car-card-${trainCar.id}`);
            this.addAnimationFrom(card, originStock ? 
                document.getElementById(`${originStock.container_div.id}_item_${trainCar.id}`) :
                document.getElementById(`train-car-deck-hidden-pile`)
            );
        });

        this.updateCounters();
    }

    public setPosition(left: boolean) {
        this.left = left;
        const div = document.getElementById(`player-table-${this.playerId}-train-cars`);
        div.classList.toggle('left', left);

        this.updateCounters(); // to realign
    }
    
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

    public setDraggable(draggable: boolean) {
        const groups = Array.from(document.getElementsByClassName('train-car-group')) as HTMLDivElement[];
        groups.forEach(groupDiv => groupDiv.setAttribute('draggable', draggable.toString()));
    }
    
    private getGroup(type: number) {
        let group = document.getElementById(`train-car-group-${type}`);
        if (!group) {
            dojo.place(`
            <div id="train-car-group-${type}" class="train-car-group" data-type="${type}">
                <div id="train-car-group-${type}-counter" class="train-car-group-counter">0</div>
                <div id="train-car-group-${type}-cards" class="train-car-cards"></div>
            </div>
            `, `player-table-${this.playerId}-train-cars`);

            group = document.getElementById(`train-car-group-${type}`);

            group.addEventListener('dragstart', (e) => {
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
                group.classList.remove('hide');
                document.getElementById('map').dataset.dragColor = '';
            });

            group.addEventListener('click', () => (this.game as any).showMessage(_("Drag the cards on the route you want to claim"), 'info')); 
        }
        return group;
    }

    private updateCounters() {
        const groups = Array.from(document.getElementsByClassName('train-car-group')) as HTMLDivElement[];

        const middleIndex = (groups.length - 1) / 2;

        groups.forEach((groupDiv, index) => {
            const distanceFromIndex = index - middleIndex;
            const count = groupDiv.getElementsByClassName('train-car-card').length;
            groupDiv.getElementsByClassName('train-car-group-counter')[0].innerHTML = `${count > 1 ? count : ''}`;
            groupDiv.style.transform = `translate${this.left ? 'X(-' : 'Y('}${Math.pow(Math.abs(distanceFromIndex) * 2, 2)}px) rotate(${(/*this.left ? -distanceFromIndex :*/ distanceFromIndex) * 4}deg)`;
            groupDiv.parentNode.appendChild(groupDiv);
        });
    }
    
    private addAnimationFrom(card: HTMLElement, from: HTMLElement) {
        if (document.visibilityState === 'hidden' || (this.game as any).instantaneousMode) {
            return;
        }

        const destinationBR = card.getBoundingClientRect();
        const originBR = from.getBoundingClientRect();

        const deltaX = destinationBR.left - originBR.left;
        const deltaY = destinationBR.top - originBR.top;

        card.style.zIndex = '10';
        card.style.transition = `transform 0.5s linear`;
        const zoom = this.game.getZoom();
        card.style.transform = `rotate(-90deg) translate(${-deltaX/zoom}px, ${-deltaY/zoom}px)`;
        setTimeout(() => card.style.transform = null);

        setTimeout(() => {
            card.style.zIndex = null;
            card.style.transition = null;            
        }, 500);
    }
}