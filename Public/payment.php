<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../Src/header.php";
//check of alle NAW gegevens zijn ingevuld anders redirect terug naar afrekenen.php
$currentCart = unserialize($_SESSION["cart"]);
$price = $currentCart->getTotalPrice();
if ($price <= 0) {
    //???, je kan niet afrekenen zonder producten
    redirect("/nerdygadgets");
}
initiatePayment(number_format($price, 2, '.', ''), random_int(1, 9999));

require_once __DIR__ . "/../Src/footer.php";
?>
