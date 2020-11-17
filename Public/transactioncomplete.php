<?php
require_once __DIR__ . "/../Src/header.php";

$paymentStatus = checkPayment(session_get('paymentId'));
if ($paymentStatus) {
    //Vul de NAW gegevens in de database in en verklein het voorraad van de producten met het aantal dat is gekocht
    $cart = unserialize($_SESSION["cart"]);
    $products = $cart->getItems();
    foreach ($products as $product) {
        $productId = $product["id"];
        $productAmount = $product["amount"];
        $currentQuantity = getProduct($productId)["QuantityOnHand"];
        $customerId = random_int(1,9999999);
        //insert nieuwe customer met customerId en NAW gegevens
        insert("orders", ["OrderId" => $_SESSION["orderId"], "StockItemId" => $productId, "Quantity" => $productAmount, "CustomerId" => $customerId]);
        update("stockitemholdings", ["QuantityOnHand" => $currentQuantity - $productAmount], ["StockItemId" => $productId]);
        //Remove $productAmount aantal producten van $productId
    }
    $cart = new Cart(); //leeg de winkelwagen
    $_SESSION["orderId"] = NIL;
    $_SESSION["cart"] = serialize($cart); //stop de nieuwe winkelwagen in de session
}
?>

<br><br>
<div class="col-sm-12 mt-2 text-center">
    <h1 style=<?= $paymentStatus ? "color:green" : "color:red" ?>>
        <?= $paymentStatus ? "Transactie compleet" : "Transactie mislukt" ?>
    </h1>
</div>

<?php
require_once __DIR__ . "/../Src/footer.php";
?>
