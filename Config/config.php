<?php

$config = [];

$config['base_url'] = 'http://localhost/nerdygadgets';
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $config['base_url'] = 'https://localhost/nerdygadgets';
}

$config['database_server'] = 'mysql:host=localhost';
$config['database_name'] = 'nerdygadgets';
$config['database_port'] = 3306;
$config['database_charset'] = 'utf8';

// User with read privileges.
$config['database_user_read'] = 'nerdygadgets_read';
$config['database_password_read'] = '^jnx$PK&hHg3Cz6y#V#S';
// User with create privileges.
$config['database_user_create'] = 'nerdygadgets_create';
$config['database_password_create'] = '9xGK^uV9q9RF*Zkx6t%D';
// User with update privileges.
$config['database_user_update'] = 'nerdygadgets_update';
$config['database_password_update'] = 'hiU1!L01685I%!nyyvyQ';
// User with create and update privileges.
$config['database_user_create_or_update'] = 'nerdygadgets_create_or_update';
$config['database_password_create_or_update'] = 'mkotQ1Osqa231Bp%2&rL';
// User with delete privileges.
$config['database_user_delete'] = 'nerdygadgets_delete';
$config['database_password_delete'] = 'KKP7Ylcw$A0t1Kx95D2c';

$config['recaptcha_site_key'] = '6Le8D_4ZAAAAAJRzhjj6G26egaHx_LbIpwk84eXH';
$config['recaptcha_private_key'] = '6Le8D_4ZAAAAANaYShMzWw3K7t_h4zP3CbfXm13O';

// If we are on the production site, turn this off.
$config['debug'] = true;

$config['session_lifetime'] = 3600;
$config['csrf_token_lifetime'] = 3600;

return $config;
