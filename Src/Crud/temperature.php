<?php

/**
 * @return array
 */
function getTemperature() {
    return select("SELECT * FROM coldroomtemperatures WHERE ColdRoomTemperatureID = 1;");
}