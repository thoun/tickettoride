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
        return 45;
    }
}
