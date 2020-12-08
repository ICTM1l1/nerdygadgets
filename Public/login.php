<?php
require_once __DIR__ . "/../Src/header.php";

$loggedIn = (bool) session_get('LoggedIn', false);
if ($loggedIn) {
    redirect(get_url("index.php"));
}

$password = get_form_data_post('password');
$email = get_form_data_post('email');

if (!empty($_POST)) {
    $valuesValid = true;
    if (empty($password) || empty($email)) {
        add_user_error('Niet alle verplichte velden met een * zijn ingevuld.');
        $valuesValid = false;
    }

    $account = getPeopleByEmail($email);
    $account_password = $account['HashedPassword'] ?? '';
    if (empty($account) || !password_verify($password, $account_password)) {
        add_user_error('Email of wachtwoord fout.');
        $valuesValid = false;
    }

    $accountIsPermittedToLogon = $account["IsPermittedToLogon"] ?? 0;
    if ($accountIsPermittedToLogon === 0) {
        add_user_error('Permission denied.');
        $valuesValid = false;
    }

    if ($valuesValid) {
        if (validateRecaptcha()) {
            session_save('LoggedIn', true, true);
            session_save('personID', $account['PersonID'] ?? 0, true);

            add_user_message('Je bent succesvol ingelogd.');
            redirect(get_url("account.php"));
        }

        add_user_error('Recaptcha is niet goed uitgevoerd. Probeer het opnieuw.');
    }
}
?>
<?php include __DIR__ . '/../Src/Html/alert.php'; ?>

    <div class="container-fluid">
        <div class="products-overview w-50 ml-auto mr-auto mt-5 mb-5">
            <div class="row">
                <div class="col-sm-12">
                    <form class="text-center w-100" id="recaptcha-form" action="<?= get_url('login.php') ?>" method="post">
                        <h1 class="mb-lg-5">Inloggen</h1>
                        <div class="form-group form-row">
                            <label for="email" class="col-sm-3 text-left">Email <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" class="form-control col-sm-9" required
                                   placeholder="Email" value="<?= $email ?>">
                        </div>

                        <div class="form-group form-row">
                            <label for="password" class="col-sm-3 text-left">Wachtwoord <span class="text-danger">*</span></label>
                            <input type="password" id="password" name="password" class="form-control col-sm-9"
                                   placeholder="Wachtwoord" required>
                        </div>

                        <div class="d-flex justify-content-center links">
                            Geen account?&#8287
                            <a href="<?= get_url('register.php') ?>">Maak een account</a>
                        </div>
                        <div class="form-group">
                            <button class="g-recaptcha btn btn-success my-4" type="submit" name="login"
                                    data-sitekey="<?= config_get('recaptcha_site_key') ?>" data-callback='onSubmit'>
                                Inloggen
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