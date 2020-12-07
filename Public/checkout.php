<?php
require_once __DIR__ . "/../Src/header.php";

/** @var Cart $cart */
$cart = session_get('cart');
$price = $cart->getTotalPrice();

if (empty($price) || empty($cart->getItems())) {
    add_user_error('Er zijn geen producten in de winkelwagen gevonden om af te rekenen.');
    redirect(get_url('shoppingcart.php'));
}

$personID = session_get('personID', 0);
$account = getCustomerByPeople($personID);

$name = get_form_data_post('name', $account['PrivateCustomerName'] ?? '');
$postalCode = get_form_data_post('postalcode', $account['DeliveryPostalCode'] ?? '');
$address = get_form_data_post('address', $account['DeliveryAddressLine1'] ?? '');
$city = get_form_data_post('city', $account['CityName'] ?? '');
$phoneNumber = get_form_data_post('phonenumber', $account['PhoneNumber'][1] ?? '');

if (isset($_POST['checkout'])) {
    $values_valid = true;
    if (empty($name) || empty($postalCode) || empty($address) || empty($city) || empty($phoneNumber)) {
        $values_valid = false;
        add_user_error('Niet all verplichte velden met een * zijn ingevuld.');
    }

    if ($values_valid) {
        $customer_id = $account['PrivateCustomerID'] ?? 0;
        if (empty($customer_id)) {
            $customer_id = createCustomer($name, $phoneNumber, $address, $postalCode, $city);
        }

        if (!empty($customer_id)) {
            session_save('customer_id', $customer_id, true);
            redirect('payment.php');
        }
    }
}

?>
<?php include __DIR__ . '/../Src/Html/alert.php'; ?>

    <div class="container-fluid">
        <div class="products-overview w-50 ml-auto mr-auto mt-5 mb-5">
            <?php include_once __DIR__ . '/../Src/Html/order-progress.php'; ?>

            <div class="row">
                <div class="col-sm-12">
                    <form class="text-center w-100" action="<?= get_url('checkout.php') ?>" method="post">
                        <div class="form-group form-row">
                            <label for="name" class="col-sm-3 text-left">Naam <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control col-sm-9"
                                   placeholder="Naam" value="<?= $name ?>">
                        </div>

                        <div class="form-group form-row">
                            <label for="postalcode" class="col-sm-3 text-left">Postcode <span class="text-danger">*</span></label>
                            <input type="text" maxlength="6" id="postalcode" name="postalcode" class="form-control col-sm-9"
                                   placeholder="Postcode" value="<?= $postalCode ?>">
                        </div>

                        <div class="form-group form-row">
                            <label for="address" class="col-sm-3 text-left">Adres <span class="text-danger">*</span></label>
                            <input type="text" id="address" name="address" class="form-control col-sm-9"
                                   placeholder="Adres" value="<?= $address ?>">
                        </div>

                        <div class="form-group form-row">
                            <label for="city" class="col-sm-3 text-left">Woonplaats <span class="text-danger">*</span></label>
                            <input type="text" id="city" name="city" class="form-control col-sm-9"
                                   placeholder="Woonplaats" value="<?= $city ?>">
                        </div>

                        <div class="form-group form-row">
                            <label for="phonenumber" class="col-sm-3 text-left">Telefoonnummer <span class="text-danger">*</span></label>
                            <input type="tel" id="phonenumber" name="phonenumber" class="form-control col-sm-9"
                                   placeholder="Telefoonnummer" value="<?= $phoneNumber ?>">
                        </div>

                        <div class="form-group">
                            <button class="btn btn-danger float-left my-4" type="button" name="back"
                                    onclick="window.location.href='<?= get_url('products-overview.php') ?>'">
                                Terug naar overzicht
                            </button>
                            <button class="btn btn-success float-right my-4" type="submit" name="checkout">
                                2. Afrekenen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
require_once __DIR__ . "/../Src/footer.php";
?>