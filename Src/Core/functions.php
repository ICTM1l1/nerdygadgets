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
 * Gets the base url.
 *
 * @return string
 *   The base url.
 */
function get_base_url() {
    return config_get('base_url');
}

/**
 * Gets the asset url.
 *
 * @param string $asset_url
 *   The path to the asset.
 *
 * @return string
 *   The asset url.
 */
function get_asset_url(string $asset_url) {
    $base_url = get_base_url();

    return "{$base_url}/Assets/{$asset_url}";
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