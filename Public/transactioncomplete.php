<?php
require_once __DIR__ . "/../Src/header.php";

$paymentPaid = checkPayment(session_get('paymentId'));
if ($paymentPaid) {
    //Vul de NAW gegevens in de database in en verklein het voorraad van de producten met het aantal dat is gekocht
    $cart = unserialize(session_get("cart"));
    $products = $cart->getItems();
    $customerId = random_int(1,9e9);
    $orderId = insert("orders", [
        "CustomerId" => 832,
        "SalespersonPersonID" => "2",
        "ContactPersonID" => "3032",
        "OrderDate" => date("Y-m-d"),
        "ExpectedDeliveryDate" => date("Y-m-d" , time() + (1  * 1 * 1 * 60 * 60)),// year month day hour minutes seconds,
        "IsUndersupplyBackordered" => 0,
        "LastEditedBy" => 7
    ]);

    foreach ($products as $product) {
        $productId = $product["id"];
        $productAmount = (int) $product["amount"] ?? 0;
        $productfromDB = getProduct($productId);
        $currentQuantity = (int) $productfromDB["QuantityOnHand"] ?? 0;

        insert("orderlines", [
            "OrderID" => $orderId,
            "StockItemID" => $productId,
            "Description" => $productfromDB["StockItemName"],
            "PackageTypeID" => '7',
            "Quantity" => $productAmount,
            "UnitPrice" => $productfromDB["SellPrice"],
            "TaxRate" => '15',
            "PickedQuantity" => $productAmount,
            "PickingCompletedWhen" => date("Y-m-d", time()),
            "LastEditedBy" => "4"
        ]);
        update("stockitemholdings",
            ["QuantityOnHand" => $currentQuantity - $productAmount],
            ["StockItemId" => $productId]);
        //Remove $productAmount aantal producten van $productId
    }
    $cart = new Cart(); //leeg de winkelwagen
    unset($_SESSION["paymentId"]);
    $_SESSION["cart"] = serialize($cart); //stop de nieuwe winkelwagen in de session
}
?>

<div class="container-fluid">
    <div class="products-overview w-50 ml-auto mr-auto mt-5 mb-5">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="mb-5 float-left">3. Afronden</h1>

                <div class="form-progress float-right">
                    <!-- Grey with black text -->
                    <nav class="navbar navbar-expand-sm bg-primary navbar-dark">
                        <ul class="navbar-nav">
                            <li class="nav-item border-right border-white">
                                <a class="nav-link" href="#">Bezorggegevens</a>
                            </li>
                            <li class="nav-item border-right border-white">
                                <a class="nav-link" href="#">Afrekenen</a>
                            </li>
                            <li class="nav-item active">
                                <a class="nav-link" href="#">Afronden</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <h1 style=<?= $paymentPaid ? "color:green" : "color:red" ?>>
                    <?= $paymentPaid ? "Transactie compleet" : "Transactie mislukt" ?>
                </h1>

                <div class="form-group">
                    <button class="btn btn-success float-right my-4" type="button" name="back"
                            onclick="window.location.href='<?= get_url('index.php') ?>'">
                        3. Afronden
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . "/../Src/footer.php";
?>