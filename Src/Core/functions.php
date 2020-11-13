<?php

$config = require __DIR__ . '/../../Config/config.php';

/**
 * Gets a specific piece of data from the submitted form data.
 *
 * @param string $key
 *   The key to search for.
 * @param string $default
 *   The default value if the key does not exist.
 *
 * @return mixed|string
 *   The data from the submitted form data.
 */
function get_form_data_get(string $key, $default = '') {
    return $_GET[$key] ?? $default;
}

/**
 * Gets a specific piece of data from the submitted form data.
 *
 * @param string $key
 *   The key to search for.
 * @param string $default
 *   The default value if the key does not exist.
 *
 * @return mixed|string
 *   The data from the submitted form data.
 */
function get_form_data_post(string $key, $default = '') {
    return $_POST[$key] ?? $default;
}

/**
 * Gets a specific piece of data from the submitted form data.
 *
 * @param string $key
 *   The key to search for.
 * @param string $default
 *   The default value if the key does not exist.
 *
 * @return mixed|string
 *   The data from the submitted form data.
 */
function session_get(string $key, $default = '') {
    return $_SESSION[$key] ?? $default;
}

/**
 * Gets a specific piece of data from the config data.
 *
 * @param string $key
 *   The key to search for.
 * @param string $default
 *   The default value if the key does not exist.
 *
 * @return mixed|string
 *   The data from the config data.
 */
function config_get(string $key, $default = '') {
    global $config;

    return $config[$key] ?? $default;
}

/**
 * Dump and dies the script in order to debug a variable value.
 *
 * @param mixed ...$variables
 *   The values to be dumped.
 */
function dd(...$variables) {
    // Removes all previous printed items
    ob_end_clean();

    var_dump($variables);
    die();
}