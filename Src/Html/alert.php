<?php
$errors = get_user_errors();
$message = '';
foreach ($errors as $error) {
    $message .= "{$error} <br>";
}

if ($message !== '') : ?>
    <div class="alert alert-danger w-50 ml-auto mr-auto mt-2 mb-2" role="alert">
        <?= $message ?>
    </div>
<?php endif; ?>