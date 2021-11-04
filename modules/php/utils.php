<?php

require_once(__DIR__.'/objects/train-car.php');
require_once(__DIR__.'/objects/destination.php');

trait UtilTrait {

//////////////////////////////////////////////////////////////////////////////
//////////// Utility functions
////////////

    function getTrainCarFromDb($dbObject) {
        if (!$dbObject || !array_key_exists('id', $dbObject)) {
            throw new BgaSystemException("Train car doesn't exists ".json_encode($dbObject));
        }
        return new TrainCar($dbObject);
    }

    function getTrainCarsFromDb(array $dbObjects) {
        return array_map(function($dbObject) { return $this->getTrainCarFromDb($dbObject); }, array_values($dbObjects));
    }

    function getDestinationFromDb($dbObject) {
        if (!$dbObject || !array_key_exists('id', $dbObject)) {
            throw new BgaSystemException("Destination doesn't exists ".json_encode($dbObject));
        }
        return new Destination($dbObject, $this->DESTINATIONS);
    }

    function getDestinationsFromDb(array $dbObjects) {
        return array_map(function($dbObject) { return $this->getDestinationFromDb($dbObject); }, array_values($dbObjects));
    }

    function getInitialTrainCarsNumber() {
        return TRAIN_CARS_PER_PLAYER;
    }

    function getLowestTrainCarsCount() {
        return intval(self::getUniqueValueFromDB("SELECT min(`player_remaining_train_cars`) FROM player"));
    }

    function getRemainingTrainCarsCount(int $playerId) {
        return intval(self::getUniqueValueFromDB("SELECT `player_remaining_train_cars` FROM player WHERE player_id = $playerId"));
    }

    function getNonZombiePlayersIds() {
        $sql = "SELECT player_id FROM player WHERE player_eliminated = 0 AND player_zombie = 0 ORDER BY player_no";
        $dbResults = self::getCollectionFromDB($sql);
        return array_map(function($dbResult) { return intval($dbResult['player_id']); }, array_values($dbResults));
    }

    function getClaimedRoutesIds($playerId = null) {
        $sql = "SELECT route_id FROM claimed_routes ";
        if ($playerId !== null) {
            $sql .= "WHERE player_id = $playerId ";
        }
        $dbResults = self::getCollectionFromDB($sql);
        return array_map(function($dbResult) { return intval($dbResult['route_id']); }, array_values($dbResults));
    }

    private function everyPlayerHasDestinations() {
        $playersIds = $this->getNonZombiePlayersIds();
        foreach($playersIds as $playerId) {
            $cardsOfPlayer = $this->getDestinationsFromDb($this->destinations->getCardsInLocation('hand', $playerId));
            if (count($cardsOfPlayer) == 0) {
                return false;
            }
        }
        return true;
    }

    function getPlayerName(int $playerId) {
        return self::getUniqueValueFromDb("SELECT player_name FROM player WHERE player_id = $playerId");
    }

    function getPlayerScore(int $playerId) {
        return intval(self::getUniqueValueFromDB("SELECT player_score FROM player where `player_id` = $playerId"));
    }

    function incScore(int $playerId, int $delta, $message = null, $messageArgs = []) {
        self::DbQuery("UPDATE player SET `player_score` = `player_score` + $delta where `player_id` = $playerId");

        if ($message != null) {
            self::notifyAllPlayers('points', $message, $messageArgs + [
                'playerId' => $playerId,
                'player_name' => $this->getPlayerName($playerId),
                'points' => $this->getPlayerScore($playerId),
                'delta' => $points,
            ]);
        }
    }
}
