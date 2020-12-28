<?php
$errors = getUserErrors();
$errorMessage = '';
foreach ($errors as $error) {
    $errorMessage .= "{$error} <br>";
}

$messages = getUserMessages();
$successMessage = '';
foreach ($messages as $message) {
    $successMessage .= "{$message} <br>";
}

if ($errorMessage !== '') : ?>
    <div class="alert alert-danger w-50 ml-auto mr-auto mt-2 mb-2" role="alert">
        <?= $errorMessage ?>
    </div>
<?php endif; ?>

<?php if ($successMessage !== '') : ?>
    <div class="alert alert-success w-50 ml-auto mr-auto mt-2 mb-2" role="alert">
        <?= $successMessage ?>
    </div>
<?php endif; ?>
