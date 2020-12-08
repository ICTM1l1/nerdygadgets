<?php

/**
 * Gets a people by email.
 *
 * @param string $email
 *   The email.
 *
 * @return array
 *   The found people.
 */
function getPeopleByEmail(string $email) {
    return selectFirst("
        SELECT * 
        FROM people 
        WHERE LogonName = :email
    ", ['email' => $email]);
}

/**
 * Gets a people by id.
 *
 * @param int $people
 *   The people id.
 *
 * @return array
 *   The found people.
 */
function getPeople(int $people) {
    return selectFirst("
        SELECT * 
        FROM people 
        WHERE PersonID = :personID
    ", ['personID' => $people]);
}

/**
 * Creates a people.
 *
 * @param string $name
 *   The name.
 * @param string $email
 *   The email.
 * @param string $password
 *   The hashed password.
 * @param string $phoneNumber
 *   The phone number.
 *
 * @return int
 *   The id of the people.
 */
function createPeople(string $name, string $email, string $password, string $phoneNumber) {
    return insert("people", [
        "FullName" => str_replace(' ', '_', $name),
        "PreferredName" => $name,
        "SearchName" => $name,
        "IsPermittedToLogon" => 1,
        "LogonName" => $email,
        "IsExternalLogonProvider" => 0,
        "HashedPassword" => $password,
        "IsSystemUser" => 0,
        "IsEmployee" => 0,
        "IsSalesperson" => 0,
        "PhoneNumber" => $phoneNumber,
        "EmailAddress" => $email,
        "ValidFrom" => date("Y-m-d G:i:s"),
        "ValidTo" => "9999-12-31 23:59:59",
        "LastEditedBy" => 1
    ]);
}

/**
 * Updates a people by id.
 *
 * @param int $personID
 *   The person ID.
 * @param string $name
 *   The name.
 * @param string $phoneNumber
 *   The phone number.
 */
function updatePeople(int $personID, string $name, string $phoneNumber) {
    update("people", [
        "FullName" => $name,
        "PhoneNumber" => $phoneNumber
    ], [
        "PersonID" => $personID
    ]);
}