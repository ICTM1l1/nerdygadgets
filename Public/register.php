<?php
require_once __DIR__ . "/../Src/header.php";

$name = get_form_data_post('name');
$password = get_form_data_post('password');
$password2 = get_form_data_post('password2');
$email = get_form_data_post('email');
$postalCode = get_form_data_post('postalcode');
$address = get_form_data_post('address');
$city = get_form_data_post('city');
$phoneNumber = get_form_data_post('phonenumber');

if (isset($_POST['register'])) {
    if (empty($name) || empty($password) || empty($password2) || empty($email) || empty($postalCode)  || empty($city) || empty($phoneNumber)) {
        add_user_error('Niet alle verplichte velden met een * zijn ingevuld.');
        redirect(get_url("register.php"));
    }

    if (!($password === $password2)) {
        add_user_error('Wachtwoorden komen niet overeen.');
        redirect(get_url("register.php"));
    }

    if (!(preg_match('@[A-Z]@', $password) && preg_match('@[a-z]@', $password) && preg_match('@[0-9]@', $password) && strlen($password) > 8)) {
        add_user_error('Wachtwoord niet sterk genoeg.');
        redirect(get_url("register.php"));
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        add_user_error('Ongeldig email address.');
        redirect(get_url("register.php"));
    }

    $foundPeople = getPeopleByEmail($email);
    if (!empty($foundPeople)) {
        add_user_error('Email wordt al gebruikt.');
        redirect(get_url("register.php"));
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $personID = createPeople($name, $email, $hashedPassword, $phoneNumber);
    createCustomer($name, $phoneNumber, $address, $postalCode, $city, $personID);

    add_user_message('Account is succesvol aangemaakt.');
    redirect(get_url("login.php"));
}
?>

    <div class="container-fluid">
        <div class="products-overview w-50 ml-auto mr-auto mt-5 mb-5">
            <div class="row">
                <div class="col-sm-12">
                    <form class="text-center w-100" action="<?= get_url('register.php') ?>" method="post">
                        <div class="form-group form-row">
                            <label for="name" class="col-sm-3 text-left">Naam <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control col-sm-9"
                                   placeholder="Naam" value="<?= $name ?>" required>
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

                        <div class="form-group">
                            <a href="<?= get_url('login.php') ?>" class="btn btn-danger my-4 float-left">
                                Inloggen
                            </a>

                            <button class="btn btn-success float-right my-4" type="submit" name="register">
                                Registreren
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
require_once __DIR__."/../Src/footer.php";
?>