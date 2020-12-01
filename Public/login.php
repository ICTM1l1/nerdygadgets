<?php
require_once __DIR__ . "/../Src/header.php";

$loggedIn = (bool) session_get('LoggedIn', false);
if ($loggedIn) {
    redirect(get_url("index.php"));
}

$password = get_form_data_post('password');
$email = get_form_data_post('email');

if (isset($_POST['login'])) {
    if (empty($password) || empty($email)) {
        add_user_error('Niet all verplichte velden met een * zijn ingevuld.');
        redirect(get_url("login.php"));
    }

    $account = getPeopleByEmail($email);
    $account_password = $account['HashedPassword'] ?? '';
    if (empty($account) || !password_verify($password, $account_password)) {
        add_user_error('E-Mail of wachtwoord fout.');
        redirect(get_url("login.php"));
    }

    $accountIsPermittedToLogon = $account["IsPermittedToLogon"] ?? 0;
    if ($accountIsPermittedToLogon === 0) {
        add_user_error('Permission denied.');
        redirect(get_url("login.php"));
    }

    session_save('LoggedIn', true, true);
    session_save('personID', $account['PersonID'] ?? 0, true);
    redirect(get_url("account.php"));
}
?>

    <div class="container-fluid">
        <div class="products-overview w-50 ml-auto mr-auto mt-5 mb-5">
            <div class="row">
                <div class="col-sm-12">
                    <form class="text-center w-100" action="<?= get_url('login.php') ?>" method="post">
                        <h1 class="mb-lg-5">Inloggen</h1>
                        <div class="form-group form-row">
                            <label for="email" class="col-sm-3 text-left">E-Mail <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" class="form-control col-sm-9" required
                                   placeholder="E-Mail" value="<?= $email ?>">
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
                            <button class="btn btn-success my-4" type="submit" name="login">
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