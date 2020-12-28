<?php
require_once __DIR__ . "/../Src/header.php";

csrfValidate(getCurrentUrl());

$name = getFormDataPost('name');
$email = getFormDataPost('email');
$subject = getFormDataPost('subject');
$message = getFormDataPost('message');

if (!empty($_POST)) {
    $valuesValid = true;
    if(empty($email) || empty($name) || empty($subject) || empty($message)) {
        addUserError('Niet alle verplichte velden met een * zijn ingevuld.');
        $valuesValid = false;
    }

    if ($valuesValid && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        addUserError('Ongeldige email opgegeven.');
        $valuesValid = false;
    }

    if(strlen($message) > 100) {
        addUserError('Uw opgegeven naam is langer dan toegestaan. (Max: 100 tekens)');
        $valuesValid = false;
    }

    if(strlen($email) > 100) {
        addUserError('Uw opgegeven email adres is langer dan toegestaan. (Max: 100 tekens)');
        $valuesValid = false;
    }

    if(strlen($subject) > 100) {
        addUserError('Uw opgegeven onderwerp is langer dan toegestaan (Max: 100 tekens).');
        $valuesValid = false;
    }

    if(strlen($message) > 2000) {
        addUserError('Uw bericht is langer dan toegestaan.');
        $valuesValid = false;
    }

    if ($valuesValid) {
        if (validateRecaptcha()) {
            createContactRequest($name, $email, $subject, $message);
            addUserMessage('Uw bericht is verstuurd.');
            redirect(getCurrentUrl());
        }

        addUserError('Recaptcha is niet goed uitgevoerd. Probeer het opnieuw.');
    }
}

include __DIR__ . '/../Src/Html/alert.php';
?>

    <div class="container-fluid">
        <div class="w-50 ml-auto mr-auto mt-5 mb-5">
            <h1 class="mb-5">Contact opnemen</h1>

            <div class="row">
                <div class="col-sm-12">
                    <form class="text-center w-100" action="<?= getUrl('contact.php') ?>" id="recaptcha-form" method="post">
                        <input type="hidden" name="token" value="<?=csrfGetToken()?>"/>
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
                                    data-sitekey="<?= configGet('recaptcha_site_key') ?>" data-callback='onSubmit'>
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