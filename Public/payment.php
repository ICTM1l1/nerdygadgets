<?php
require_once __DIR__ . "/../Src/header.php";

/** @var Cart $cart */
$cart = unserialize(session_get('cart'), [Cart::class]);
$price = $cart->getTotalPrice();

initiatePayment(number_format($price, 2, '.', ''), random_int(1,9999));

require_once __DIR__ . "/../Src/footer.php";
