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
        return $this->getUniqueIntValueFromDB("SELECT min(`player_remaining_train_cars`) FROM player");
    }

    function getRemainingTrainCarsCount(int $playerId) {
        return $this->getUniqueIntValueFromDB("SELECT `player_remaining_train_cars` FROM player WHERE player_id = $playerId");
    }

    function getNonZombiePlayersIds() {
        $sql = "SELECT player_id FROM player WHERE player_eliminated = 0 AND player_zombie = 0 ORDER BY player_no";
        $dbResults = self::getCollectionFromDB($sql);
        return array_map(function($dbResult) { return intval($dbResult['player_id']); }, array_values($dbResults));
    }

    function getClaimedRoutes($playerId = null) {
        $sql = "SELECT route_id, player_id FROM claimed_routes ";
        if ($playerId !== null) {
            $sql .= "WHERE player_id = $playerId ";
        }
        $dbResults = self::getCollectionFromDB($sql);
        return array_values($dbResults);
    }

    function getPlayersIds() {
        return array_keys($this->loadPlayersBasicInfos());
    }

    function getPlayerCount() {
        return count($this->getPlayersIds());
    }

    function getPlayerName(int $playerId) {
        return self::getUniqueValueFromDb("SELECT player_name FROM player WHERE player_id = $playerId");
    }

    function getPlayerScore(int $playerId) {
        return $this->getUniqueIntValueFromDB("SELECT player_score FROM player where `player_id` = $playerId");
    }

    function incScore(int $playerId, int $delta, $message = null, $messageArgs = []) {
        self::DbQuery("UPDATE player SET `player_score` = `player_score` + $delta where `player_id` = $playerId");

        if ($message != null) {
            self::notifyAllPlayers('points', $message, [
                'playerId' => $playerId,
                'player_name' => $this->getPlayerName($playerId),
                'points' => $this->getPlayerScore($playerId),
                'delta' => $points,
            ] + $messageArgs);
        }
    }

    function getUniqueIntValueFromDB(string $sql) {
        return intval(self::getUniqueValueFromDB($sql));
    }

    function getCompletedDestinationsIds(int $playerId) {
        $sql = "SELECT `card_id` FROM `destination` WHERE `card_location` = 'hand' AND `card_location_arg` = $playerId AND  `completed` = 1";
        $dbResults = self::getCollectionFromDB($sql);
        return array_map(function($dbResult) { return intval($dbResult['card_id']); }, array_values($dbResults));
    }

    function checkCompletedDestinations(int $playerId) {
        $handDestinations = $this->getDestinationsFromDb($this->destinations->getCardsInLocation('hand', $playerId));
        $alreadyCompleted = $this->getCompletedDestinationsIds($playerId);

        foreach($handDestinations as $destination) {
            if (!in_array($destination->id, $alreadyCompleted) && $this->map->isDestinationCompleted($playerId, $destination)) {
                self::DbQuery("UPDATE `destination` SET `completed` = 1 where `card_id` = $destination->id");

                // TODO notif

                self::incStat(1, 'completedDestinations');
                self::incStat(1, 'completedDestinations', $playerId);
            }
        }
    }
}
