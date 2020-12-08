<?php
require_once __DIR__ . "/../src/header.php";

session_destroy();
session_start();
reset_cart();

add_user_message('Je bent succesvol uitgelogd.');
redirect('login.php');

require_once __DIR__ . "/../src/footer.php";