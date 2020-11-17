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
 * Gets a specific piece of data from the session.
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
 * Saves a specific piece of data into the session.
 *
 * @param string $key
 *   The key to search for.
 * @param string $value
 *   The default value if the key does not exist.
 * @param bool $overwrite
 *   May the value of the key be overwritten?
 */
function session_save(string $key, $value = '', bool $overwrite = false) {
    if ($overwrite) {
        $_SESSION[$key] = $value;
    }

    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = $value;
    }
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
 * Gets the url.
 *
 * @param string $url
 *   The url.
 *
 * @return string
 *   The url.
 */
function get_url(string $url) {
    $base_url = get_base_url();

    return "{$base_url}/{$url}";
}

/**
 * Gets the current url.
 *
 * @return string
 *   The current url.
 */
function get_current_url() {
    return htmlspecialchars($_SERVER['PHP_SELF']);
}

/**
 * Redirects to an url.
 *
 * @param string $url
 *   The url.
 */
function redirect(string $url) {
    if (headers_sent()) {
        print("<meta http-equiv='Refresh' content=\"0; url='$url'\" />");
        exit();
    }

    header('Location: ' . $url);
    exit();
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