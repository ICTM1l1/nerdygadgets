<?php
require_once __DIR__ . "/../Src/header.php";

csrf_validate(get_current_url());

authorizeUser();

$personID = session_get('personID', 0);
$account = getCustomerByPeople($personID);
$adminAccount = null;
if (empty($account) && authorizeAdmin()) {
    $adminAccount = getPeople($personID);
}

$name = get_form_data_post('name', $account['PrivateCustomerName'] ?? '');
$password = get_form_data_post('password');
$email = get_form_data_post('email', $account['LogonName'] ?? '');
$postalCode = get_form_data_post('postalcode', $account['DeliveryPostalCode'] ?? '');
$address = get_form_data_post('address', $account['DeliveryAddressLine1'] ?? '');
$city = get_form_data_post('city', $account['CityName'] ?? '');
$phoneNumber = get_form_data_post('phonenumber', $account['PhoneNumber'] ?? '');

if (isset($_POST["update"])) {
    if (empty($name) || empty($password) || empty($email) || empty($address) || empty($postalCode)  || empty($city) || empty($phoneNumber)) {
        add_user_error('Niet all verplichte velden met een * zijn ingevuld.');
        redirect(get_url("account.php"));
    }

    $account = getPeopleByEmail($email);
    $account_password = $account["HashedPassword"] ?? '';
    if (!password_verify($password, $account_password)) {
        add_user_error('Wachtwoord incorrect.');
        redirect(get_url("account.php"));
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        add_user_error('Ongeldig email address.');
        redirect(get_url("account.php"));
    }

    updatePeople($account['PersonID'] ?? 0, $name, $phoneNumber);
    updateCustomer($account['PersonID'] ?? 0, $name, $address, $postalCode, $phoneNumber, $city);

    add_user_message('Account is succesvol bijgewerkt.');
    redirect('account.php');
}
?>

    <div class="container-fluid">
        <div class="w-75 mt-1 ml-auto mr-auto">
            <?php include_once __DIR__ . '/../Src/Html/account-navbar.php'; ?>

            <div class="account-overview mt-3 mb-5 w-50 ml-auto mr-auto">
                <?php if (!empty($account)) : ?>
                    <div class="row">
                        <div class="col-sm-10">
                            <h1>Account van <?= $name ?></h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <form class="text-center" action="<?= get_url('account.php') ?>" method="post">
                                <input type="hidden" name="token" value="<?=csrf_get_token()?>"/>
                                <div class="form-group form-row">
                                    <label for="name" class="col-sm-3 text-left">Naam <span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control col-sm-9"
                                           placeholder="Naam" value="<?= $name ?>" required>
                                </div>

                                <div class="form-group form-row">
                                    <label for="email" class="col-sm-3 text-left">Email <span class="text-danger">*</span></label>
                                    <input type="email" id="email" name="email" class="form-control col-sm-9"
                                           placeholder="Email" value="<?= $email ?>" disabled="disabled">
                                </div>

                                <div class="form-group form-row">
                                    <label for="postalcode" class="col-sm-3 text-left">Postcode <span class="text-danger">*</span></label>
                                    <input type="text" maxlength="6" id="postalcode" name="postalcode" class="form-control col-sm-9"
                                           placeholder="Postcode" value="<?= $postalCode ?>" required>
                                </div>

                                <div class="form-group form-row">
                                    <label for="address" class="col-sm-3 text-left">Adres <span class="text-danger">*</span></label>
                                    <input type="text" id="address" name="address" class="form-control col-sm-9"
                                           placeholder="Address" value="<?= $address ?>" required>
                                </div>

                                <div class="form-group form-row">
                                    <label for="city" class="col-sm-3 text-left">Woonplaats <span class="text-danger">*</span></label>
                                    <input type="text" id="city" name="city" class="form-control col-sm-9"
                                           placeholder="Woonplaats" value="<?= $city ?>" required>
                                </div>

                                <div class="form-group form-row">
                                    <label for="phonenumber" class="col-sm-3 text-left">Telefoonnummer <span class="text-danger">*</span></label>
                                    <input type="tel" id="phonenumber" name="phonenumber" class="form-control col-sm-9"
                                           placeholder="Telefoonnummer" value="<?= $phoneNumber ?>" required>
                                </div>

                                <div class="form-group form-row">
                                    <label for="account_password" class="col-sm-3 text-left">Wachtwoord <span class="text-danger">*</span></label>
                                    <input type="password" id="account_password" name="password" class="form-control col-sm-9"
                                           placeholder="Wachtwoord" required>
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-success my-4" type="submit" name="update">
                                        Opslaan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php elseif (!empty($adminAccount)) : ?>
                    <div class="row">
                        <div class="col-sm-10">
                            <h1>Account van <?= $adminAccount['FullName'] ?? '' ?></h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">Email</div>
                        <div class="col-sm-9"><?= $adminAccount['EmailAddress'] ?? '' ?></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">Telefoonnummer</div>
                        <div class="col-sm-9"><?= $adminAccount['PhoneNumber'] ?? '' ?></div>
                    </div>
                <?php else : ?>
                    <p class="text-center">Er zijn geen account gegevens gevonden.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php
require_once __DIR__ . "/../Src/footer.php";
?>