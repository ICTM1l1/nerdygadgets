<?php

/**
 * Gets the temperature.
 *
 * @return array
 *   The temperature.
 */
function getTemperature() {
    return selectFirst("
        SELECT Temperature 
        FROM coldroomtemperatures
        WHERE ColdRoomTemperatureID = 1;
    ");
}