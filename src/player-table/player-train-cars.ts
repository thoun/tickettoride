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

            const imagePosition = trainCar.type;
            //const row = Math.floor(imagePosition / IMAGE_ITEMS_PER_ROW);
            const xBackgroundPercent = (imagePosition/* - (row * IMAGE_ITEMS_PER_ROW)*/) * 100;
            //const yBackgroundPercent = row * 100;

            let html = `
            <div id="train-car-card-${trainCar.id}" class="train-car-card" style="background-position: -${xBackgroundPercent}% 50%;"></div>
            `;

            dojo.place(html, group);

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
    
    private getGroup(type: number) {
        let group = document.getElementById(`train-car-group-${type}`);
        if (!group) {
            dojo.place(`
            <div id="train-car-group-${type}" class="train-car-group" data-type="${type}">
                <div id="train-car-group-${type}-counter" class="train-car-group-counter">0</div>
            </div>
            `, `player-table-${this.playerId}-train-cars`);

            group = document.getElementById(`train-car-group-${type}`);

            // TODO handle click on group
        }
        return group;
    }

    private updateCounters() {
        const groups = Array.from(document.getElementsByClassName('train-car-group')) as HTMLDivElement[];

        const middleIndex = (groups.length - 1) / 2;

        groups.forEach((groupDiv, index) => {
            const distanceFromIndex = index - middleIndex;
            groupDiv.getElementsByClassName('train-car-group-counter')[0].innerHTML = `${groupDiv.childElementCount - 1}`;
            groupDiv.style.transform = `translateY(${Math.pow(Math.abs(distanceFromIndex) * 2, 2)}px) rotate(${(distanceFromIndex) * 4}deg)`;
            groupDiv.parentNode.appendChild(groupDiv);

            // add rotation to underneath cards
        });
    }
}