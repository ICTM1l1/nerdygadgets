<?php
require_once __DIR__ . "/../src/header.php";

session_key_unset('LoggedIn');
session_key_unset('personID');

add_user_message('Je bent succesvol uitgelogd.');
redirect('login.php');

require_once __DIR__ . "/../src/footer.php";
?>