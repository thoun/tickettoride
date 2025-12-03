<?php

namespace Bga\Games\TicketToRide;

use Bga\GameFramework\Table;
use Map;

require_once(__DIR__.'/objects/train-car.php');
require_once(__DIR__.'/objects/route.php');

trait UtilTrait {

//////////////////////////////////////////////////////////////////////////////
//////////// Utility functions
////////////

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

    function getExpansionOption() {
        $expansion = $this->getMap()->expansion;
        return $expansion !== null ? $this->tableOptions->get($expansion) : 0;
    }

    function getMapCode(): string {
        $mapOption = (int)$this->getUniqueValueFromDB("SELECT `global_value` FROM `global` where `global_id` = ".MAP_OPTION);
        return match ($mapOption) {
            1 => 'usa',
            2 => 'europe',
            3 => 'switzerland',
            4 => 'india',
            default => Table::getBgaEnvironment() === 'studio' ? 'nordiccountries' : 'usa',
        };
    }

    function getMap(): Map {
        if (!isset($this->map)) {
            $mapCode = $this->getMapCode();

            require_once(__DIR__.'/../maps/'.$mapCode.'/map.php');

            $this->map = getMap();
            $this->map->code = $mapCode;
        }
        return $this->map;
    }

    /**
     * Transforms a TrainCar Db object to TrainCar class.
     */
    function getTrainCarFromDb($dbObject): ?\TrainCar {
        if ($dbObject === null) {
            return null;
        }
        if (!$dbObject || !array_key_exists('id', $dbObject)) {
            throw new \BgaSystemException("Train car doesn't exists ".json_encode($dbObject));
        }
        return new \TrainCar($dbObject);
    }

    /**
     * Transforms a TrainCar Db object array to TrainCar class array.
     * 
     * @return TrainCar[]
     */
    function getTrainCarsFromDb(array $dbObjects): ?array {
        return array_map(fn($dbObject) => $this->getTrainCarFromDb($dbObject), array_values($dbObjects));
    }

    function getLowestTrainCarsCount() {
        return $this->getUniqueIntValueFromDB("SELECT min(`player_remaining_train_cars`) FROM player");
    }

    function getRemainingTrainCarsCount(int $playerId) {
        return $this->getUniqueIntValueFromDB("SELECT `player_remaining_train_cars` FROM player WHERE player_id = $playerId");
    }

    function getClaimedRoutes($playerId = null) {
        $sql = "SELECT route_id, player_id FROM claimed_routes ";
        if ($playerId !== null) {
            $sql .= "WHERE player_id = $playerId ";
        }
        $dbResults = $this->getCollectionFromDB($sql);
        return array_map(fn($dbResult) => new \ClaimedRoute($dbResult), array_values($dbResults));
    }
}
