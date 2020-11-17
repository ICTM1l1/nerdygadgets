<?php
require_once __DIR__ . "/../Src/header.php";

$price = "5.00"; //$_SESSION['Cart']->getTotalPrice();
initiatePayment($price, random_int(1,9999));

require_once __DIR__ . "/../Src/footer.php";
?>
