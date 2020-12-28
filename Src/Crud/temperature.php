<?php

/**
 * Gets the temperature.
 *
 * @return array
 *   The temperature.
 */
function getTemperature() {
    return selectFirst('
        SELECT ColdRoomTemperatureID, Temperature 
        FROM coldroomtemperatures
        WHERE ColdRoomTemperatureID = 1;
    ');
}

/**
 * Creates or updates the measured temperature.
 *
 * @param int $temperature
 *   The measured temperature
 */
function createOrUpdateTemperatureMeasurement(int $temperature) {
    $measuredTemperature = getTemperature();
    if (!empty($measuredTemperature)) {
        updateTemperatureMeasurement($measuredTemperature['ColdRoomTemperatureID'] ?? 0, $temperature);
        return;
    }

    createTemperatureMeasurement($temperature);
}

/**
 * Creates a measurement for the temperature.
 *
 * @param int $temperature
 *   The temperature.
 *
 * @return int
 *   The id of the measurement.
 */
function createTemperatureMeasurement(int $temperature) {
    return insert('coldroomtemperatures', [
        'Temperature' => $temperature,
        'ColdRoomSensorNumber' => 1,
        'RecordedWhen' => date('Y-m-d G:i:s'),
        'ValidFrom' => date('Y-m-d G:i:s'),
        'ValidTo' => '9999-12-31 23:59:59'
    ]);
}

/**
 * Updates a measurement for the temperature.
 *
 * @param int $temperature
 *   The temperature.
 * @param int $id
 *   Id of the measurement.
 *
 * @return bool
 *   Whether the measurement was updated or not.
 */
function updateTemperatureMeasurement(int $temperature, int $id) {
    return update('coldroomtemperatures', [
        'Temperature' => $temperature
    ], [
        'ColdRoomTemperatureID' => $id
    ]);
}