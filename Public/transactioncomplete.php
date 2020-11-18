<?php
require_once __DIR__ . "/../Src/header.php";

$paymentPaid = checkPayment(session_get('paymentId'));
if ($paymentPaid) {
    // Add order, order lines and decrease the quantity on hand value.
    $cart = unserialize(session_get("cart"), [Cart::class]);
    $products = $cart->getItems();
    $customerId = 832;
    $currentDate = date('Y-m-d');

    $orderId = insert("orders", [
        "CustomerId" => $customerId,
        "SalespersonPersonID" => "2",
        "ContactPersonID" => "3032",
        "OrderDate" => date("Y-m-d"),
        "ExpectedDeliveryDate" => $currentDate,
        "IsUndersupplyBackordered" => 0,
        "LastEditedBy" => 7
    ]);

    foreach ($products as $product) {
        $productId = (int) ($product["id"] ?? 0);
        $productAmount = (int) ($product["amount"] ?? 0);
        $productFromDB = getProduct($productId);
        $currentQuantity = (int) ($productfromDB["QuantityOnHand"] ?? 0);

        insert("orderlines", [
            "OrderID" => $orderId,
            "StockItemID" => $productId,
            "Description" => $productFromDB["StockItemName"] ?? '',
            "PackageTypeID" => '7',
            "Quantity" => $productAmount,
            "UnitPrice" => $productFromDB["SellPrice"] ?? 0,
            "TaxRate" => '15',
            "PickedQuantity" => $productAmount,
            "PickingCompletedWhen" => $currentDate,
            "LastEditedBy" => "4"
        ]);

        update("stockitemholdings", [
            "QuantityOnHand" => $currentQuantity - $productAmount,
        ], [
            "StockItemId" => $productId,
        ]);
    }

    // Clear the cart and payment process.
    $cart = new Cart();
    session_key_unset('paymentId');
    session_save('cart', serialize($cart), true);
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