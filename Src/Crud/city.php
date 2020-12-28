<?php

/**
 * Gets a specific city.
 *
 * @param string $city
 *   The city to search for.
 *
 * @return array
 *   The found city.
 */
function getCity(string $city) {
    return selectFirst('
        SELECT CityID, CityName
        FROM cities 
        WHERE CityName = :cityName
    ', ['cityName' => $city,]);
}

/**
 * Gets the last city id.
 *
 * @return int
 *   The last city id.
 */
function getLastCityId() {
    return selectFirst('
        SELECT CityID, CityName
        FROM cities 
        ORDER BY CityID DESC
    ')['CityID'] ?? 0;
}

/**
 * Creates a city.
 *
 * @param string $cityName
 *   The name of the city.
 *
 * @return int
 *   The id of the city.
 */
function createCity(string $cityName) {
    return insert('cities', [
        'CityName' => $cityName,
        'StateProvinceId' => 27,
        'LastEditedBy' => 1,
        'ValidFrom' => date('Y-m-d'),
        'ValidTo' => date('Y-m-d'),
    ]);
}