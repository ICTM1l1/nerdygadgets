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
 * @param int|null $person
 *   The person id.
 *
 * @return int
 *   The customer id.
 */
function createCustomer(string $name, string $phoneNumber, string $address, string $postalCode, string $city, int $person = null) {
    $cityFromDb = getCity($city);
    $cityId = $cityFromDb['CityID'] ?? 0;
    if (empty($cityId)) {
        $cityId = createCity($city);
    }

    return insert('privatecustomer', [
        'PrivateCustomerName' => $name,
        'DeliveryMethodID' => 1,
        'DeliveryCityID' => $cityId,
        'PhoneNumber' => $phoneNumber,
        'PeopleID' => $person,
        'DeliveryAddressLine1' => $address,
        'DeliveryPostalCode' => $postalCode,
    ]);
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
    return selectFirst('
        SELECT PrivateCustomerID, PrivateCustomerName, DeliveryPostalCode, DeliveryAddressLine1,
        CityName, PhoneNumber, PrivateCustomerID
        FROM privatecustomer AS p
        JOIN cities ON DeliveryCityID = CityID
        WHERE PrivateCustomerID = :customerID
    ', ['customerID' => $customer]);
}

/**
 * Gets the customer for a people.
 *
 * @param int $people
 *   The people.
 *
 * @return array
 *   The the found customer for the people.
 */
function getCustomerByPeople(int $people) {
    return selectFirst('
        SELECT PrivateCustomerID, PrivateCustomerName, DeliveryPostalCode, DeliveryAddressLine1,
        CityName, C.PhoneNumber, EmailAddress, P.PersonID, LogonName, FullName, PreferredName
        FROM privatecustomer C
        JOIN people P ON  C.PeopleID = P.PersonID
        JOIN cities ON DeliveryCityID = CityID
        WHERE PeopleID = :peopleID
    ', ['peopleID' => $people]);
}

/**
 * Updates a customer.
 *
 * @param int $people
 *   The people id.
 * @param string $name
 *   The name.
 * @param string $address
 *   The address.
 * @param string $postalCode
 *   The postal code.
 * @param string $phoneNumber
 *   The phone number.
 * @param string $city
 *   The city.
 */
function updateCustomer(int $people, string $name, string $address, string $postalCode, string $phoneNumber, string $city) {
    $cityFromDb = getCity($city);
    $cityId = $cityFromDb['CityID'] ?? 0;
    if (empty($cityId)) {
        $cityId = createCity($city);
    }

    update('privatecustomer', [
        'PrivateCustomerName' => $name,
        'DeliveryPostalCode' => $postalCode,
        'PhoneNumber' => $phoneNumber,
        'DeliveryAddressLine1' => $address,
        'DeliveryCityID' => $cityId,
    ], [
        'PeopleID' => $people
    ]);
}

/**
 * Deletes a customer.
 *
 * @param int $customer
 *   The id of the customer.
 */
function deleteCustomer(int $customer) {
    delete('privatecustomer', ['PrivateCustomerID' => $customer]);
}