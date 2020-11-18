<?php

require_once __DIR__ . "/../Mollie/vendor/autoload.php";

/**
 * Initiates the payment.
 *
 * @param string $price
 *   The price to be payed.
 * @param int $order_nr
 *   The order number.
 */
function initiatePayment(string $price, int $order_nr) {
    $mollie = new \Mollie\Api\MollieApiClient();
    $mollie->setApiKey("test_sKWktBBCgNax7dGjt8sU6cF92zRuzb");
    $payment = $mollie->payments->create([
        "amount" => [
            "currency" => "EUR",
            "value" => $price
        ],
        "description" => "Order #{$order_nr}",
        "redirectUrl" => get_url('transactioncomplete.php'),
        "webhookUrl"  => "",
    ]);

    session_save('paymentId', $payment->id);
    redirect($payment->getCheckoutUrl());
}

/**
 * Determines if the payment has been payed.
 *
 * @param string $paymentId
 *   The payment id.
 *
 * @return bool
 *   Whether the payment has been payed or not.
 */
function checkPayment(string $paymentId) {
    if (empty($paymentId)) {
        return false;
    }

    $mollie = new \Mollie\Api\MollieApiClient();
    $mollie->setApiKey("test_sKWktBBCgNax7dGjt8sU6cF92zRuzb");
    $payment = $mollie->payments->get($paymentId);

    return $payment->isPaid();
}
?>
