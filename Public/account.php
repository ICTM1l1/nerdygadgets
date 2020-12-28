<?php
require_once __DIR__ . "/../Src/header.php";

csrfValidate(getCurrentUrl());

authorizeUser();

$personId = sessionGet('personID', 0);
$account = getCustomerByPeople($personId);
$adminAccount = null;
if (empty($account) && authorizeAdmin()) {
    $adminAccount = getPeople($personId);
}

$name = getFormDataPost('name', $account['PrivateCustomerName'] ?? '');
$password = getFormDataPost('password');
$email = getFormDataPost('email', $account['LogonName'] ?? '');
$postalCode = getFormDataPost('postalcode', $account['DeliveryPostalCode'] ?? '');
$address = getFormDataPost('address', $account['DeliveryAddressLine1'] ?? '');
$city = getFormDataPost('city', $account['CityName'] ?? '');
$phoneNumber = getFormDataPost('phonenumber', $account['PhoneNumber'] ?? '');

if (isset($_POST['update'])) {
    if (empty($name) || empty($password) || empty($email) || empty($address) || empty($postalCode)  || empty($city) || empty($phoneNumber)) {
        addUserError('Niet all verplichte velden met een * zijn ingevuld.');
        redirect(getUrl('account.php'));
    }

    $account = getPeopleByEmail($email);
    $accountPassword = $account['HashedPassword'] ?? '';
    if (!password_verify($password, $accountPassword)) {
        addUserError('Wachtwoord incorrect.');
        redirect(getUrl('account.php'));
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        addUserError('Ongeldig email address.');
        redirect(getUrl('account.php'));
    }

    if (!preg_match('/^[1-9][0-9]{3}?(?!sa|sd|ss)[a-z]{2}$/i', $postalCode)) {
        addUserError('Ongeldige postcode opgegeven.');
        redirect(getUrl('account.php'));
    }

    updatePeople($account['PersonID'] ?? 0, $name, $phoneNumber);
    updateCustomer($account['PersonID'] ?? 0, $name, $address, $postalCode, $phoneNumber, $city);

    addUserMessage('Account is succesvol bijgewerkt.');
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
                            <form class="text-center" action="<?= getUrl('account.php') ?>" method="post">
                                <input type="hidden" name="token" value="<?=csrfGetToken()?>"/>
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