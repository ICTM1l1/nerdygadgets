<?php
require_once __DIR__ . '/../Src/header.php';

$cart = getCart();
$price = $cart->getTotalPrice();

if (empty($price) || empty($cart->getItems())) {
    addUserError('Er zijn geen producten in de winkelwagen gevonden om af te rekenen.');
    redirect(getUrl('shoppingcart.php'));
}

$orderSuccessful = checkPayment(sessionGet('paymentId'));

$customerId = (int) sessionGet('customer_id', 0);
$loggedIn = (bool) sessionGet('LoggedIn', false);
$customer = getCustomer($customerId);

// Always clear the payment process in order to be able to start a new payment.
sessionKeyUnset('customer_id');
sessionKeyUnset('paymentId');

if ($orderSuccessful) {
    $connection = getDatabaseConnection(configGet('database_user_create_or_update'), configGet('database_password_create_or_update'));
    beginTransaction($connection);

    try {
        $products = $cart->getItems();

        $dateTime = new DateTime();
        $currentDate = $dateTime->format('Y-m-d');

        $dateTime->modify('+1 day');
        $deliveryDate = $dateTime->format('Y-m-d');

        $orderId = createOrder($customerId, $currentDate, $deliveryDate, $connection);

        foreach ($products as $product) {
            $productId = (int) ($product['id'] ?? 0);
            $productAmount = (int) ($product['amount'] ?? 0);
            $productFromDB = getProduct($productId);

            createOrderLine($orderId, $productFromDB, $productAmount, $currentDate, $connection);
        }

        commitTransaction($connection);

        resetCart();
        addUserMessage('De bestelling is succesvol geplaatst.');
    } catch (Exception $exception) {
        $orderSuccessful = false;
        addUserError('Bestelling kon niet worden geplaatst. Probeer het opnieuw of neem contact op met NerdyGadgets.');
        rollbackTransaction($connection);
    }
} elseif (!empty($customerId) && !$loggedIn) {
    deleteCustomer($customerId);
}

include __DIR__ . '/../Src/Html/alert.php'; ?>

<div class="container-fluid">
    <div class="products-overview w-50 ml-auto mr-auto mt-5 mb-5">
        <?php include_once __DIR__ . '/../Src/Html/order-progress.php'; ?>

        <div class="row">
            <div class="col-sm-12">
                <?php if ($orderSuccessful) : ?>
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
                        <a class="btn btn-success my-4" href="<?= getUrl('index.php') ?>">
                            3. Afronden
                        </a>
                    </div>
                <?php else : ?>
                    <h1 class="text-danger text-center">Producten afrekenen is mislukt</h1>

                    <div class="form-group mt-5 text-center">
                        <a href="<?= getUrl('checkout.php') ?>" class="btn btn-success my-4">
                            Opnieuw afrekenen
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../Src/footer.php';
?>