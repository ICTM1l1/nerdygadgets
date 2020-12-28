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