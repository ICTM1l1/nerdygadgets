<?php
require_once __DIR__ . "/../Src/header.php";

csrf_validate(get_current_url());

$name = get_form_data_post('name');
$email = get_form_data_post('email');
$subject = get_form_data_post('subject');
$message = get_form_data_post('message');

if (!empty($_POST)) {
    $values_valid = true;
    if(empty($email) || empty($name) || empty($subject) || empty($message)) {
        add_user_error('Niet alle verplichte velden met een * zijn ingevuld.');
        $values_valid = false;
    }

    if ($values_valid && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        add_user_error('Ongeldige email opgegeven.');
        $values_valid = false;
    }

    if(strlen($message) > 100) {
        add_user_error('Uw opgegeven naam is langer dan toegestaan. (Max: 100 tekens)');
        $values_valid = false;
    }

    if(strlen($email) > 100) {
        add_user_error('Uw opgegeven email adres is langer dan toegestaan. (Max: 100 tekens)');
        $values_valid = false;
    }

    if(strlen($subject) > 100) {
        add_user_error('Uw opgegeven onderwerp is langer dan toegestaan (Max: 100 tekens).');
        $values_valid = false;
    }

    if(strlen($message) > 2000) {
        add_user_error('Uw bericht is langer dan toegestaan.');
        $values_valid = false;
    }

    if ($values_valid) {
        if (validateRecaptcha()) {
            createContactRequest($name, $email, $subject, $message);
            add_user_message('Uw bericht is verstuurd.');
            redirect(get_current_url());
        }

        add_user_error('Recaptcha is niet goed uitgevoerd. Probeer het opnieuw.');
    }
}

include __DIR__ . '/../Src/Html/alert.php';
?>

    <div class="container-fluid">
        <div class="w-50 ml-auto mr-auto mt-5 mb-5">
            <h1 class="mb-5">Contact opnemen</h1>

            <div class="row">
                <div class="col-sm-12">
                    <form class="text-center w-100" action="<?= get_url('contact.php') ?>" id="recaptcha-form" method="post">
                        <input type="hidden" name="token" value="<?=csrf_get_token()?>"/>
                        <div class="form-group form-row">
                            <label for="name" class="col-sm-3 text-left">
                                Naam
                                <span class="text-danger">*</span><br>
                                <span id="char-count" class="text-danger"></span>
                            </label>
                            <input type="text" id="name" name="name" class="form-control col-sm-9 count-characters-100" maxlength="100"
                                   placeholder="Naam" value="<?= $name ?>" required>
                        </div>

                        <div class="form-group form-row">
                            <label for="email" class="col-sm-3 text-left">
                                Email
                                <span class="text-danger">*</span><br>
                                <span id="char-count" class="text-danger"></span>
                            </label>
                            <input type="email" id="email" name="email" class="form-control col-sm-9 count-characters-100"
                                   maxlength="100" placeholder="Email" value="<?= $email ?>" required>
                        </div>

                        <div class="form-group form-row">
                            <label for="subject" class="col-sm-3 text-left">
                                Onderwerp
                                <span class="text-danger">*</span><br>
                                <span id="char-count" class="text-danger"></span>
                            </label>
                            <input type="text" id="subject" name="subject" class="form-control col-sm-9 count-characters-100"
                                   maxlength="100" autocomplete="off" required placeholder="Onderwerp"
                                   value="<?= $subject ?>">
                        </div>

                        <div class="form-group form-row">
                            <label for="message" class="col-sm-3 text-left">
                                Uw bericht
                                <span class="text-danger">*</span><br>
                                <span id="char-count" class="text-danger"></span>
                            </label>
                            <textarea id="message" name="message" class="form-control col-sm-9 count-characters-2000"
                                      rows="10" maxlength="2000" autocomplete="off" required
                                      placeholder="Uw bericht (maximaal 2000 karakters)"><?= $message ?></textarea>
                        </div>

                        <div class="form-group">
                            <button class="g-recaptcha btn btn-success float-right my-4" type="submit" name="contact"
                                    data-sitekey="<?= config_get('recaptcha_site_key') ?>" data-callback='onSubmit'>
                                Indienen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
require_once __DIR__ . '/../Src/footer.php';