<?php
require_once __DIR__ . '/../src/header.php';

restartSession();
reset_cart();

add_user_message('Je bent succesvol uitgelogd.');
redirect('login.php');

require_once __DIR__ . '/../src/footer.php';