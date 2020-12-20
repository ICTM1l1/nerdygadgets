<?php
$errors = getUserErrors();
$error_message = '';
foreach ($errors as $error) {
    $error_message .= "{$error} <br>";
}

$messages = get_user_messages();
$success_message = '';
foreach ($messages as $message) {
    $success_message .= "{$message} <br>";
}

if ($error_message !== '') : ?>
    <div class="alert alert-danger w-50 ml-auto mr-auto mt-2 mb-2" role="alert">
        <?= $error_message ?>
    </div>
<?php endif; ?>

<?php if ($success_message !== '') : ?>
    <div class="alert alert-success w-50 ml-auto mr-auto mt-2 mb-2" role="alert">
        <?= $success_message ?>
    </div>
<?php endif; ?>
