<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/Mollie/vendor/autoload.php";

function initiatePayment($amount, $ordernr) {
    $mollie = new \Mollie\Api\MollieApiClient();
    $mollie->setApiKey("test_sKWktBBCgNax7dGjt8sU6cF92zRuzb");
    $payment = $mollie->payments->create([
        "amount" => [
            "currency" => "EUR",
            "value" => $amount
        ],
        "description" => "Order #".$ordernr,
        "redirectUrl" => "http://localhost/nerdygadgets/transactioncomplete.php",
        "webhookUrl"  => "",
    ]);
    $_SESSION['paymentId'] = $payment->id;
    print("<meta http-equiv='Refresh' content=\"0; url='". $payment->getCheckoutUrl() . "'\" />");
    
}

function checkPayment($paymentId) {
    $mollie = new \Mollie\Api\MollieApiClient();
    $mollie->setApiKey("test_sKWktBBCgNax7dGjt8sU6cF92zRuzb");
    $payment = $mollie->payments->get($paymentId);
    return $payment->isPaid();
}
?>
