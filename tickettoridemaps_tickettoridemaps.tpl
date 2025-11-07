{OVERALL_GAME_HEADER}

<div id="score">
    <div id="table-wrapper">
        <table>
            <tbody id="score-table-body">
            </tbody>
        </table>
    </div>
</div>

<div id="map-zoom-wrapper">
    <div id="resized">
        <div id="main-line">
            <div id="map-and-borders">
                <div id="map-zoom" class="disable-scrollbars">
                    <div id="map"></div>
                </div>
                <div id="zoom-button"></div>
                <div id="map-destination-highlight-shadow"></div>
            </div>

            <div id="train-car-deck">
                <div id="train-car-deck-hidden-pile" class="hidden-pile stock train-car-deck-hidden-pile-tooltip">
                    <div id="train-car-deck-level" class="deck-level"></div>
                    <div role="button" id="train-car-deck-hidden-pile1" class="button left-radius train-car-deck-hidden-pile-tooltip" data-number="1">1</div>
                    <div role="button" id="train-car-deck-hidden-pile2" class="button right-radius train-car-deck-hidden-pile-tooltip" data-number="2">2</div>
                </div>
                <div id="visible-train-cards">
                    <div id="visible-train-cards-stock1" class="stock"></div>
                    <div id="visible-train-cards-stock2" class="stock"></div>
                    <div id="visible-train-cards-stock3" class="stock"></div>
                    <div id="visible-train-cards-stock4" class="stock"></div>
                    <div id="visible-train-cards-stock5" class="stock"></div>
                </div>

                <div id="destination-deck-hidden-pile" class="hidden-pile stock destination-deck-hidden-pile-tooltip">
                    <div id="destination-deck-level" class="deck-level"></div>
                </div>
                <div id="destination-deck" class="hidden">
                    <div id="destination-stock"></div>
                    <div id="visible-train-cards-mini">
                        <div id="visible-train-cards-mini1" class="train-car-color icon"></div>
                        <div id="visible-train-cards-mini2" class="train-car-color icon"></div>
                        <div id="visible-train-cards-mini3" class="train-car-color icon"></div>
                        <div id="visible-train-cards-mini4" class="train-car-color icon"></div>
                        <div id="visible-train-cards-mini5" class="train-car-color icon"></div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div>

<audio id="audiosrc_ttr-placed-train-car" src="{GAMETHEMEURL}img/placed-train-car.mp3" preload="none" autobuffer></audio>
<audio id="audiosrc_o_ttr-placed-train-car" src="{GAMETHEMEURL}img/placed-train-car.ogg" preload="none" autobuffer></audio>

<audio id="audiosrc_ttr-clear-train-car-cards" src="{GAMETHEMEURL}img/clear-train-car-cards.mp3" preload="none" autobuffer></audio>
<audio id="audiosrc_o_ttr-clear-train-car-cards" src="{GAMETHEMEURL}img/clear-train-car-cards.ogg" preload="none" autobuffer></audio>

<audio id="audiosrc_ttr-completed-in-game" src="{GAMETHEMEURL}img/completed-in-game.mp3" preload="none" autobuffer></audio>
<audio id="audiosrc_o_ttr-completed-in-game" src="{GAMETHEMEURL}img/completed-in-game.ogg" preload="none" autobuffer></audio>

<audio id="audiosrc_ttr-completed-end" src="{GAMETHEMEURL}img/completed-end.mp3" preload="none" autobuffer></audio>
<audio id="audiosrc_o_ttr-completed-end" src="{GAMETHEMEURL}img/completed-end.ogg" preload="none" autobuffer></audio>

<audio id="audiosrc_ttr-uncompleted-end" src="{GAMETHEMEURL}img/uncompleted-end.mp3" preload="none" autobuffer></audio>
<audio id="audiosrc_o_ttr-uncompleted-end" src="{GAMETHEMEURL}img/uncompleted-end.ogg" preload="none" autobuffer></audio>

<audio id="audiosrc_ttr-longest-line-scoring" src="{GAMETHEMEURL}img/longest-line-scoring.mp3" preload="none" autobuffer></audio>
<audio id="audiosrc_o_ttr-longest-line-scoring" src="{GAMETHEMEURL}img/longest-line-scoring.ogg" preload="none" autobuffer></audio>

<audio id="audiosrc_ttr-scoring-end" src="{GAMETHEMEURL}img/scoring-end.mp3" preload="none" autobuffer></audio>
<audio id="audiosrc_o_ttr-scoring-end" src="{GAMETHEMEURL}img/scoring-end.ogg" preload="none" autobuffer></audio>

{OVERALL_GAME_FOOTER}
