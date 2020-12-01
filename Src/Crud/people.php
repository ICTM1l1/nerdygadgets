<?php

function getPeopleByEmail(string $email) {
    return selectFirst("
        SELECT * 
        FROM people 
        WHERE LogonName = :email
    ", ['email' => $email]);
}

function createPeople(string $name, string $email, string $hashedPassword, string $phoneNumber) {
    return insert("people", [
        "FullName" => str_replace(' ', '_', $name),
        "PreferredName" => $name,
        "SearchName" => $name,
        "IsPermittedToLogon" => 1,
        "LogonName" => $email,
        "IsExternalLogonProvider" => 0,
        "HashedPassword" => $hashedPassword,
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

function updatePeople(int $personID, string $name, string $phoneNumber) {
    update("people", [
        "FullName" => $name,
        "PhoneNumber" => $phoneNumber
    ], [
        "PersonID" => $personID
    ]);
}