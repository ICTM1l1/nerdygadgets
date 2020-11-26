<?php

/**
 * Creates a customer.
 *
 * @param string $name
 *   The name.
 * @param string $phoneNumber
 *   The phone number.
 * @param string $street
 *   The street.
 * @param string $postalCode
 *   The postal code.
 * @param string $city
 *   The city.
 *
 * @return int
 *   The customer id.
 */
function createCustomer(string $name, string $phoneNumber, string $street, string $postalCode, string $city) {
    $current_date = date('Y-m-d');

    $cityFromDb = getCity($city);
    $cityId = $cityFromDb['CityID'] ?? 0;
    if (empty($cityId)) {
        $cityId = createCity($city);
    }

    return insert('customers', [
        'CustomerName' => $name,
        'BillToCustomerID' => 1,
        'CustomerCategoryID' => 3,
        'PrimaryContactPersonID' => 1,
        'DeliveryMethodID' => 3,
        'DeliveryCityID' => $cityId,
        'PostalCityID' => $cityId,
        'AccountOpenedDate' => $current_date,
        'StandardDiscountPercentage' => 0,
        'IsStatementSent' => 0,
        'IsOnCreditHold' => 0,
        'PaymentDays' => 7,
        'PhoneNumber' => '(+31) ' . $phoneNumber,
        'FaxNumber' => '(+31) ' . $phoneNumber,
        'WebsiteURL' => get_base_url(),
        'DeliveryAddressLine1' => $street,
        'DeliveryPostalCode' => $postalCode,
        'PostalAddressLine1' => $street,
        'PostalPostalCode' => $postalCode,
        'LastEditedBy' => 1,
        'ValidFrom' => $current_date,
        'ValidTo' => $current_date,
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
        SELECT * FROM customers
        WHERE CustomerName = :customerName
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
        SELECT CustomerID, CustomerName, CityName, DeliveryAddressLine1, DeliveryPostalCode, PhoneNumber
        FROM customers
        JOIN cities ON DeliveryCityID = CityID
        WHERE CustomerID = :customerID
    ", ['customerID' => $customer]);
}