<?php

$config = [];

$config['base_url'] = 'http://localhost/nerdygadgets';

$config['database_server'] = 'mysql:host=localhost';
$config['database_name'] = 'nerdygadgets';
$config['database_user'] = 'root';
$config['database_password'] = '';
$config['database_port'] = 4444;
$config['database_charset'] = 'utf8';

// If we are on the production site, turn this off.
$config['debug'] = true;

return $config;
