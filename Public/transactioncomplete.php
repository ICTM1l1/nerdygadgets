<?php
require_once __DIR__ . "/../Src/header.php";

/** @var Cart $cart */
$cart = session_get('cart');
$price = $cart->getTotalPrice();

if (empty($price) || empty($cart->getItems())) {
    add_user_error('Er zijn geen items in de winkelwagen gevonden om af te rekenen.');
    redirect(get_url('shoppingcart.php'));
}

$paymentPaid = checkPayment(session_get('paymentId'));

$customerId = session_get('customer_id');
$customer = getCustomer($customerId);

// Always clear the payment process in order to be able to start a new payment.
session_key_unset('customer_id');
session_key_unset('paymentId');

if ($paymentPaid) {
    // Add order, order lines and decrease the quantity on hand value.
    $cart = session_get("cart");
    $products = $cart->getItems();

    $dateTime = new DateTime();
    $currentDate = $dateTime->format('Y-m-d');

    $dateTime->modify('+1 day');
    $deliveryDate = $dateTime->format('Y-m-d');

    $orderId = insert("orders", [
        "CustomerId" => $customerId,
        "SalespersonPersonID" => "2",
        "ContactPersonID" => "3032",
        "OrderDate" => $currentDate,
        "ExpectedDeliveryDate" => $deliveryDate,
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
    session_save('cart', $cart, true);
}
?>

<div class="container-fluid">
    <div class="products-overview w-50 ml-auto mr-auto mt-5 mb-5">
        <?php include_once __DIR__ . '/../Src/Html/order-progress.php'; ?>

        <div class="row">
            <div class="col-sm-12">
                <?php if ($paymentPaid) : ?>
                    <h1 class="text-success">Bestelling is geplaatst</h1>
                    <p>
                        Uw bestelling is succesvol geplaatst en wordt morgen bezorgt.
                    </p>

                    <h1>Bezorggegevens</h1>
                    <ul class="list-group list-group-flush w-50">
                        <li class="list-group-item bg-dark">
                            Naam: <b class="float-right"><?= $customer['PrivateCustomerName'] ?? '' ?></b>
                        </li>
                        <li class="list-group-item bg-dark">
                            Adres: <b class="float-right"><?= $customer['DeliveryAddressLine1'] ?? '' ?></b>
                        </li>
                        <li class="list-group-item bg-dark">
                            Postcode: <b class="float-right"><?= $customer['DeliveryPostalCode'] ?? '' ?></b>
                        </li>
                        <li class="list-group-item bg-dark">
                            Woonplaats: <b class="float-right"><?= $customer['CityName'] ?? '' ?></b>
                        </li>
                        <li class="list-group-item bg-dark">
                            Telefoonnummer: <b class="float-right"><?= $customer['PhoneNumber'] ?? '' ?></b>
                        </li>
                    </ul>

                    <div class="form-group mt-5 text-center">
                        <button class="btn btn-success my-4" type="button" name="back"
                                onclick="window.location.href='<?= get_url('index.php') ?>'">
                            3. Afronden
                        </button>
                    </div>
                <?php else : ?>
                    <h1 class="text-danger text-center">Producten afrekenen is mislukt</h1>

                    <div class="form-group mt-5 text-center">
                        <button class="btn btn-success my-4" type="button" name="back"
                                onclick="window.location.href='<?= get_url('checkout.php') ?>'">
                            Opnieuw afrekenen
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . "/../Src/footer.php";
?>