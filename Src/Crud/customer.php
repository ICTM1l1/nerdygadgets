<?php

/**
 * Creates a customer.
 *
 * @param string $name
 *   The name.
 * @param string $phoneNumber
 *   The phone number.
 * @param string $address
 *   The address.
 * @param string $postalCode
 *   The postal code.
 * @param string $city
 *   The city.
 * @param int $personID
 *   The person id.
 *
 * @return int
 *   The customer id.
 */
function createCustomer(string $name, string $phoneNumber, string $address, string $postalCode, string $city, int $personID = null) {
    $cityFromDb = getCity($city);
    $cityId = $cityFromDb['CityID'] ?? 0;
    if (empty($cityId)) {
        $cityId = createCity($city);
    }

    return insert('privatecustomer', [
        "PrivateCustomerName" => $name,
        "DeliveryMethodID" => 1,
        "DeliveryCityID" => $cityId,
        "PhoneNumber" => $phoneNumber,
        "PeopleID" => $personID,
        "DeliveryAddressLine1" => $address,
        "DeliveryPostalCode" => $postalCode,
    ]);
}

/**
 * Gets a specific customer.
 *
 * @param string $customer
 *   The customer to search for.
 *
 * @return array
 *   The found customer.
 */
function getCustomerByName(string $customer) {
    return selectFirst("
        SELECT * 
        FROM privatecustomer
        WHERE PrivateCustomerName = :customerName
    ", ['customerName' => $customer]);
}

/**
 * Gets a specific customer.
 *
 * @param int $customer
 *   The customer to search for.
 *
 * @return array
 *   The found customer.
 */
function getCustomer(int $customer) {
    return selectFirst("
        SELECT *
        FROM privatecustomer
        JOIN cities ON DeliveryCityID = CityID
        WHERE PrivateCustomerID = :customerID
    ", ['customerID' => $customer]);
}

function getCustomerByPeople(int $people) {
    return selectFirst("
        SELECT * 
        FROM privatecustomer C
        JOIN people P ON  C.PeopleID = P.PersonID
        JOIN cities ON DeliveryCityID = CityID
        WHERE PeopleID = :peopleID
    ", ['peopleID' => $people]);
}

function updateCustomer(int $peopleID, string $name, string $address, string $postalCode, string $phoneNumber, string $city) {
    $cityFromDb = getCity($city);
    $cityId = $cityFromDb['CityID'] ?? 0;
    if (empty($cityId)) {
        $cityId = createCity($city);
    }

    update("privatecustomer", [
        "PrivateCustomerName" => $name,
        "DeliveryPostalCode" => $postalCode,
        "PhoneNumber" => $phoneNumber,
        "DeliveryAddressLine1" => $address,
        "DeliveryCityID" => $cityId,
    ], [
        "PeopleID" => $peopleID
    ]);
}