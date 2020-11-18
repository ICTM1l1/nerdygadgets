<?php
require_once __DIR__ . "/../Src/header.php";

/** @var Cart $cart */
$cart = session_get('cart');
$price = $cart->getTotalPrice();

if (empty($price) || empty($cart->getItems())) {
    add_user_error('Er zijn geen items in de winkelwagen gevonden om af te rekenen.');
    redirect(get_url('shoppingcart.php'));
}

if (isset($_POST['checkout'])) {
    $name = get_form_data_post('name');
    $postalCode = get_form_data_post('postalcode');
    $street = get_form_data_post('streetname');
    $city = get_form_data_post('city');
    $email = get_form_data_post('email');
    $phoneNumber = get_form_data_post('phonenumber');

    $values_valid = true;
    if (empty($name) || empty($postalCode) || empty($street) || empty($city) || empty($email) || empty($phoneNumber)) {
        $values_valid = false;
        add_user_error('Niet all verplichte velden met een * zijn ingevuld.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $values_valid = false;
        add_user_error('Ongeldige email opgegeven.');
    }

    if ($values_valid) {
        $customer = getCustomerByName($name);
        $customer_id = $customer['CustomerID'] ?? 0;
        if (empty($customer_id)) {
            $customer_id = createCustomer($name, $phoneNumber, $street, $postalCode, $city, $email);
        }

        if (!empty($customer_id)) {
            session_save('customer_id', $customer_id);
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
                            <input type="text" id="name" name="name" class="form-control col-sm-9" placeholder="Naam">
                        </div>

                        <div class="form-group form-row">
                            <label for="postalcode" class="col-sm-3 text-left">Postcode <span class="text-danger">*</span></label>
                            <input type="text" maxlength="6" id="postalcode" name="postalcode" class="form-control col-sm-9" placeholder="Postcode">
                        </div>

                        <div class="form-group form-row">
                            <label for="streetname" class="col-sm-3 text-left">Straatnaam <span class="text-danger">*</span></label>
                            <input type="text" id="streetname" name="streetname" class="form-control col-sm-9" placeholder="Straatnaam">
                        </div>

                        <div class="form-group form-row">
                            <label for="city" class="col-sm-3 text-left">Woonplaats <span class="text-danger">*</span></label>
                            <input type="text" id="city" name="city" class="form-control col-sm-9" placeholder="Woonplaats">
                        </div>

                        <div class="form-group form-row">
                            <label for="email" class="col-sm-3 text-left">Email <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" class="form-control col-sm-9" placeholder="Email">
                        </div>

                        <div class="form-group form-row">
                            <label for="phonenumber" class="col-sm-3 text-left">Telefoonnummer <span class="text-danger">*</span></label>
                            <input type="tel" id="phonenumber" name="phonenumber" class="form-control col-sm-9" placeholder="Telefoonnummer">
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