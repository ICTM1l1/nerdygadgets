<?php
require_once __DIR__ . "/../Src/header.php";
$apiKey = config_get('temperature_api_key');

if (isset($_POST["ApiKey"]) && isset($_POST["Temperature"])) {
    if ($_POST["ApiKey"] == $apiKey) {
        $checkDB = getTemperature();
        if (!empty($checkDB)) {
            update("coldroomtemperatures", [
                "Temperature" => (int) $_POST["Temperature"]
            ], [
                "ColdRoomTemperatureID" => 1
            ]);
        }
        else {
            insert("coldroomtemperatures", [
                "Temperature" => (int) $_POST["Temperature"],
                "ColdRoomSensorNumber" => 1,
                "RecordedWhen" => date("Y-m-d G:i:s"),
                "ValidFrom" => date("Y-m-d G:i:s"),
                "ValidTo" => "9999-12-31 23:59:59"
            ]);
        }
    }
}