class PlayerTrainCars {
    public playerId: number;
    public trainCarStock: Stock;

    constructor(
        private game: TicketToRideGame, 
        player: TicketToRidePlayer,
        trainCars: TrainCar[]) {

        this.playerId = Number(player.id);

        // train cars cards        

        this.trainCarStock = new ebg.stock() as Stock;
        this.trainCarStock.setSelectionAppearance('class');
        this.trainCarStock.selectionClass = 'selected';
        this.trainCarStock.create(this.game, $(`player-table-${this.playerId}-train-cars`), CARD_WIDTH, CARD_HEIGHT);
        this.trainCarStock.setSelectionMode(0);
        this.trainCarStock.onItemCreate = (cardDiv, cardTypeId) => setupTrainCarCardDiv(cardDiv, cardTypeId);
        dojo.connect(this.trainCarStock, 'onChangeSelection', this, (_, itemId: string) => {
            if (this.trainCarStock.getSelectedItems().length) {
                //this.game.cardClick(0, Number(itemId));
            }
            this.trainCarStock.unselectAll();
        });
        setupTrainCarCards(this.trainCarStock);

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

            // TODO update group counter
            
            /* TODO const card = document.getElementById(`destination-card-${trainCar.id}`);
            card.addEventListener('click', () => this.activateNextDestination(
                this.destinationsDone.some(d => d.id == trainCar.id) ? this.destinationsDone : this.destinationsTodo
            ));

            // TODO animation
            if (originStock) {
                this.addAnimationFrom(card, document.getElementById(`${originStock.container_div.id}_item_${trainCar.id}`));
            }*/

        });

        this.updateCounters();
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
                const target = e.target as HTMLDivElement;
                e.dataTransfer.setData('text/plain', target.dataset.type);
                /*setTimeout(() => {
                    target.classList.add('hide');
                }, 0);*/
            });

            group.addEventListener('click', () => (this.game as any).showMessage(_("Drag the cards on the route you want to claim"), 'info'));   

            // TODO handle click on group
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
            groupDiv.style.transform = `translateY(${Math.pow(Math.abs(distanceFromIndex) * 2, 2)}px) rotate(${(distanceFromIndex) * 4}deg)`;
            groupDiv.parentNode.appendChild(groupDiv);

            // add rotation to underneath cards
        });
    }
}