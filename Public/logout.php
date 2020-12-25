<?php
require_once __DIR__ . '/../src/header.php';

restartSession();
resetCart();

addUserMessage('Je bent succesvol uitgelogd.');
redirect('login.php');

require_once __DIR__ . '/../src/footer.php';