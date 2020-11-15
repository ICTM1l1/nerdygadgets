<?php
require_once __DIR__ . "/../Src/header.php";
require_once __DIR__ . "/../Src/payment.php";
?>

<?php
$price = "5.00"; //$_SESSION['Cart']->getTotalPrice();
initiatePayment($price, rand(1,9999));
?>

<?php
require_once __DIR__ . "/../Src/footer.php";
?>
