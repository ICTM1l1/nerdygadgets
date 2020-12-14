<?php
require_once __DIR__ . "/../Src/header.php";
$apiKey = "55ed7846125b1aa3abc20c2c430133cc17e9a61a9f2b906dc15ee7c0179eacdc4b102927ea9e1e84c359d35e7dfe0a4fade8e6de5ba8c5c0c072fc98a293e473";

if (isset($_POST["ApiKey"])) {
    if ($_POST["ApiKey"] == $apiKey) {
        $checkDB = getTemperature();
        if (!empty($checkDB)) {
            update("coldroomtemperatures", [
                "Temperature" => (int)$_POST["Temperature"]
            ], [
                "ColdRoomTemperatureID" => 1
            ]);
        }
        else {
            insert("coldroomtemperatures", [
                "Temperature" => (int)$_POST["Temperature"],
                "ColdRoomSensorNumber" => 1,
                "RecordedWhen" => date("Y-m-d G:i:s"),
                "ValidFrom" => date("Y-m-d G:i:s"),
                "ValidTo" => "9999-12-31 23:59:59"
            ]);
        }
    }
}