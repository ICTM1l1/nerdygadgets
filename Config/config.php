<?php

$config = [];

$config['base_url'] = 'http://localhost/nerdygadgets';

$config['database_server'] = 'mysql:host=localhost';
$config['database_name'] = 'nerdygadgets';
$config['database_user'] = 'root';
$config['database_password'] = '';
$config['database_port'] = 3306;
$config['database_charset'] = 'utf8';

$config['recaptcha_site_key'] = '6Le8D_4ZAAAAAJRzhjj6G26egaHx_LbIpwk84eXH';
$config['recaptcha_private_key'] = '6Le8D_4ZAAAAANaYShMzWw3K7t_h4zP3CbfXm13O';

// If we are on the production site, turn this off.
$config['debug'] = true;

return $config;
