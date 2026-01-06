<?php

namespace Bga\Games\TicketToRide;

use Bga\GameFramework\Table;
use Map;

require_once(__DIR__.'/objects/train-car.php');
require_once(__DIR__.'/objects/route.php');

const MAP_LIST = [
    1 => 'usa',
    2 => 'europe',
    3 => 'switzerland',
    4 => 'india',
    5 => 'nordiccountries',
];

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
        return MAP_LIST[$mapOption] ?? MAP_LIST[/*Table::getBgaEnvironment() === 'studio' ? 5 : */1];
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
