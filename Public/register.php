<?php
require_once __DIR__ . '/../Src/header.php';

csrfValidate(getCurrentUrl());

$name = getFormDataPost('name');
$password = getFormDataPost('password');
$password2 = getFormDataPost('password2');
$email = getFormDataPost('email');
$postalCode = getFormDataPost('postalcode');
$address = getFormDataPost('address');
$city = getFormDataPost('city');
$phoneNumber = getFormDataPost('phonenumber');

if (!empty($_POST)) {
    $valuesValid = true;
    if (empty($name) || empty($password) || empty($password2) || empty($email) || empty($postalCode)  || empty($city) || empty($phoneNumber)) {
        addUserError('Niet alle verplichte velden met een * zijn ingevuld.');
        $valuesValid = false;
    }

    if ($valuesValid && !($password === $password2)) {
        addUserError('Wachtwoorden komen niet overeen.');
        $valuesValid = false;
    }

    if ($valuesValid && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        addUserError('Ongeldige email opgegeven.');
        $valuesValid = false;
    }

    if ($valuesValid && !preg_match('/^[1-9][0-9]{3}?(?!sa|sd|ss)[a-z]{2}$/i', $postalCode)) {
        addUserError('Ongeldige postcode opgegeven.');
        $valuesValid = false;
    }

    $foundPeople = getPeopleByEmail($email);
    if (!empty($foundPeople)) {
        addUserError('Email wordt al gebruikt.');
        $valuesValid = false;
    }

    if (scorePassword($password, 8) < 75) {
        addUserError('Wachtwoord niet sterk genoeg. Een sterk wachtwoord voldoet aan de volgende eisen: <ul><li>1 hoofdletter</li><li>1 kleineletter</li><li>1 getal</li><li>Langer dan 8 karakters</li></ul>');
        $valuesValid = false;
    }

    if ($valuesValid) {
        if (validateRecaptcha()) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $personId = createPeople($name, $email, $hashedPassword, $phoneNumber);
            createCustomer($name, $phoneNumber, $address, $postalCode, $city, $personId);

            $account = getPeopleByEmail($email);

            sessionSave('LoggedIn', true, true);
            sessionSave('personID', $account['PersonID'] ?? 0, true);

            addUserMessage('Je bent succesvol ingelogd.');
            redirect(getUrl('account.php'));
        }

        addUserError('Recaptcha is niet goed uitgevoerd. Probeer het opnieuw.');
    }
}
?>
<?php include __DIR__ . '/../Src/Html/alert.php'; ?>

    <div class="container-fluid">
        <div class="products-overview w-50 ml-auto mr-auto mt-5 mb-5">
            <div class="row">
                <div class="col-sm-12">
                    <form class="text-center w-100" id="recaptcha-form" action="<?= getUrl('register.php') ?>" method="post">
                        <input type="hidden" name="token" value="<?=csrfGetToken()?>"/>
                        <div class="form-group form-row">
                            <label for="name" class="col-sm-3 text-left">Naam <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control col-sm-9"
                                   placeholder="Naam" value="<?= $name ?>" required>
                        </div>

                        <div class="form-group form-row">
                            <label for="email" class="col-sm-3 text-left">Email <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" class="form-control col-sm-9"
                                   placeholder="Email" value="<?= $email ?>" required>
                        </div>

                        <div class="form-group form-row">
                            <label for="postalcode" class="col-sm-3 text-left">Postcode <span class="text-danger">*</span></label>
                            <input type="text" maxlength="6" id="postalcode" name="postalcode" class="form-control col-sm-9"
                                   placeholder="Postcode" value="<?= $postalCode ?>" required>
                        </div>

                        <div class="form-group form-row">
                            <label for="address" class="col-sm-3 text-left">Adres <span class="text-danger">*</span></label>
                            <input type="text" id="address" name="address" class="form-control col-sm-9"
                                   placeholder="Adres" value="<?= $address ?>" required>
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
                            <label for="password" class="col-sm-3 text-left">Wachtwoord <span class="text-danger">*</span></label>
                            <input type="password" id="password" name="password" class="form-control col-sm-9"
                                   placeholder="Wachtwoord" required>
                        </div>

                        <div class="form-group form-row">
                            <label for="password2" class="col-sm-3 text-left">Bevestig wachtwoord <span class="text-danger">*</span></label>
                            <input type="password" id="password2" name="password2" class="form-control col-sm-9"
                                   placeholder="Bevestig wachtwoord" required>

                            <div class="col-sm-3"></div>
                            <div class="col-sm-9 text-left mt-2" id="divCheckPasswordMatch"></div>
                        </div>

                        <div class="form-group">
                            <a href="<?= getUrl('login.php') ?>" class="btn btn-danger my-4 float-left">
                                Terug
                            </a>

                            <button class="g-recaptcha btn btn-success float-right my-4" id="registerSubmit" type="submit" name="register"
                                    data-sitekey="<?= configGet('recaptcha_site_key') ?>" data-callback='onSubmit'>
                                Registreren
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
require_once __DIR__.'/../Src/footer.php';
?>