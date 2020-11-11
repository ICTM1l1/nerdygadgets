<?php

$config = [];

$config['server'] = 'mysql:host=localhost';
$config['database'] = 'nerdygadgets';
$config['user'] = 'root';
$config['password'] = '';
$config['port'] = 3306;
$config['charset'] = 'utf8';

// If we are on the production site, turn this off.
$config['debug'] = true;

return $config;