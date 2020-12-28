<?php
require_once __DIR__ . '/../Src/header.php';

csrfValidate(getCurrentUrl());

$loggedIn = (bool) sessionGet('LoggedIn', false);
if ($loggedIn) {
    redirect(getUrl('index.php'));
}

$password = getFormDataPost('password');
$email = getFormDataPost('email');

if (!empty($_POST)) {
    $valuesValid = true;
    if (empty($password) || empty($email)) {
        addUserError('Niet alle verplichte velden met een * zijn ingevuld.');
        $valuesValid = false;
    }

    $account = getPeopleByEmail($email);
    $accountPassword = $account['HashedPassword'] ?? '';
    if ($valuesValid && (empty($account) || !password_verify($password, $accountPassword))) {
        addUserError('Email of wachtwoord fout.');
        $valuesValid = false;
    }

    $accountIsPermittedToLogon = $account['IsPermittedToLogon'] ?? 0;
    if ($valuesValid && $accountIsPermittedToLogon === 0) {
        addUserError('Je account is geblokkeerd.');
        $valuesValid = false;
    }

    if ($valuesValid) {
        if (validateRecaptcha()) {
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
                    <form class="text-center w-100" id="recaptcha-form" action="<?= getUrl('login.php') ?>" method="post">
                        <input type="hidden" name="token" value="<?=csrfGetToken()?>"/>
                        <h1 class="mb-lg-5">Inloggen</h1>
                        <div class="form-group form-row">
                            <label for="email" class="col-sm-3 text-left">Email <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" class="form-control col-sm-9" required
                                   placeholder="Email" value="<?= $email ?>">
                        </div>

                        <div class="form-group form-row">
                            <label for="login_password" class="col-sm-3 text-left">Wachtwoord <span class="text-danger">*</span></label>
                            <input type="password" id="login_password" name="password" class="form-control col-sm-9"
                                   placeholder="Wachtwoord" required>
                        </div>

                        <div class="d-flex justify-content-center links">
                            Geen account?&#8287
                            <a href="<?= getUrl('register.php') ?>">Maak een account</a>
                        </div>
                        <div class="form-group">
                            <button class="g-recaptcha btn btn-success my-4" type="submit" name="login"
                                    data-sitekey="<?= configGet('recaptcha_site_key') ?>" data-callback='onSubmit'>
                                Inloggen
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