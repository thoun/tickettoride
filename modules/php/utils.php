<?php

require_once(__DIR__.'/objects/train-car.php');
require_once(__DIR__.'/objects/destination.php');
require_once(__DIR__.'/objects/route.php');

trait UtilTrait {

//////////////////////////////////////////////////////////////////////////////
//////////// Utility functions
////////////

    function array_find(array $array, callable $fn) {
        foreach ($array as $value) {
            if($fn($value)) {
                return $value;
            }
        }
        return null;
    }

    function array_find_index(array $array, callable $fn) {
        foreach ($array as $index => $value) {
            if($fn($value)) {
                return $index;
            }
        }
        return null;
    }

    function array_some(array $array, callable $fn) {
        foreach ($array as $value) {
            if($fn($value)) {
                return true;
            }
        }
        return false;
    }
        
    function array_every(array $array, callable $fn) {
        foreach ($array as $value) {
            if(!$fn($value)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Save (insert or update) any object/array as variable.
     */
    function setGlobalVariable(string $name, /*object|array*/ $obj) {
        $jsonObj = json_encode($obj);
        $this->DbQuery("INSERT INTO `global_variables`(`name`, `value`)  VALUES ('$name', '$jsonObj') ON DUPLICATE KEY UPDATE `value` = '$jsonObj'");
    }

    /**
     * Return a variable object/array.
     * To force object/array type, set $asArray to false/true.
     */
    function getGlobalVariable(string $name, $asArray = null) {
        $json_obj = $this->getUniqueValueFromDB("SELECT `value` FROM `global_variables` where `name` = '$name'");
        if ($json_obj) {
            $object = json_decode($json_obj, $asArray);
            return $object;
        } else {
            return null;
        }
    }

    /**
     * Delete a variable object/array.
     */
    function deleteGlobalVariable(string $name) {
        $this->DbQuery("DELETE FROM `global_variables` where `name` = '$name'");
    }

    /**
     * Transforms a TrainCar Db object to TrainCar class.
     */
    function getTrainCarFromDb($dbObject) {
        if ($dbObject === null) {
            return null;
        }
        if (!$dbObject || !array_key_exists('id', $dbObject)) {
            throw new BgaSystemException("Train car doesn't exists ".json_encode($dbObject));
        }
        return new TrainCar($dbObject);
    }

    /**
     * Transforms a TrainCar Db object array to TrainCar class array.
     */
    function getTrainCarsFromDb(array $dbObjects) {
        return array_map(fn($dbObject) => $this->getTrainCarFromDb($dbObject), array_values($dbObjects));
    }

    /**
     * Transforms a Destination Db object to Destination class.
     */    
    function getDestinationFromDb($dbObject) {
        if (!$dbObject || !array_key_exists('id', $dbObject)) {
            throw new BgaSystemException("Destination doesn't exists ".json_encode($dbObject));
        }
        return new Destination($dbObject, $this->DESTINATIONS);
    }

    /**
     * Transforms a Destination Db object array to Destination class array.
     */    
    function getDestinationsFromDb(array $dbObjects) {
        return array_map(fn($dbObject) => $this->getDestinationFromDb($dbObject), array_values($dbObjects));
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
        return array_map(fn($dbResult) => intval($dbResult['player_id']), array_values($dbResults));
    }

    function getClaimedRoutes($playerId = null) {
        $sql = "SELECT route_id, player_id FROM claimed_routes ";
        if ($playerId !== null) {
            $sql .= "WHERE player_id = $playerId ";
        }
        $dbResults = self::getCollectionFromDB($sql);
        return array_map(fn($dbResult) => new ClaimedRoute($dbResult), array_values($dbResults));
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

        self::notifyAllPlayers('points', $message !== null ? $message : '', [
            'playerId' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
            'points' => $this->getPlayerScore($playerId),
            'delta' => $delta,
        ] + $messageArgs);
    }

    function getUniqueIntValueFromDB(string $sql) {
        return intval(self::getUniqueValueFromDB($sql));
    }

    function getCompletedDestinationsIds(int $playerId) {
        $sql = "SELECT `card_id` FROM `destination` WHERE `card_location` = 'hand' AND `card_location_arg` = $playerId AND  `completed` = 1";
        $dbResults = self::getCollectionFromDB($sql);
        return array_map(fn($dbResult) => intval($dbResult['card_id']), array_values($dbResults));
    }

    function getUnompletedDestinationsIds(int $playerId) {
        $sql = "SELECT `card_id` FROM `destination` WHERE `card_location` = 'hand' AND `card_location_arg` = $playerId AND  `completed` = 0";
        $dbResults = self::getCollectionFromDB($sql);
        return array_map(fn($dbResult) => intval($dbResult['card_id']), array_values($dbResults));
    }

    function checkCompletedDestinations(int $playerId) {
        $handDestinations = $this->getDestinationsFromDb($this->destinations->getCardsInLocation('hand', $playerId));
        $alreadyCompleted = $this->getCompletedDestinationsIds($playerId);

        foreach($handDestinations as $destination) {
            if (!in_array($destination->id, $alreadyCompleted)) {
                $destinationRoutes = $this->getDestinationRoutes($playerId, $destination);
                if ($destinationRoutes != null) {
                    self::DbQuery("UPDATE `destination` SET `completed` = 1 where `card_id` = $destination->id");

                    self::notifyPlayer($playerId, 'destinationCompleted', clienttranslate('${you} completed a new destination : ${from} - ${to}'), [
                        'playerId' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'destination' => $destination,
                        'from' => $this->getCityName($destination->from),
                        'to' => $this->getCityName($destination->to),
                        'you' => clienttranslate('You'),
                        'i18n' => ['you'],
                        'destinationRoutes' => $destinationRoutes,
                    ]);

                    self::incStat(1, 'completedDestinations');
                    self::incStat(1, 'completedDestinations', $playerId);
                }
            }
        }
    }

    function getCityName(string $id) {
        return $this->CITIES[$id]->name;
    }
}
