<?php

$config = require __DIR__ . '/../../Config/config.php';

/**
 * Gets a specific piece of data from the submitted form data.
 *
 * @param string $key
 *   The key to search for.
 * @param mixed $default
 *   The default value if the key does not exist.
 *
 * @return mixed|string
 *   The data from the submitted form data.
 */
function getFormDataGet(string $key, $default = '') {
    return requestFromSuperGlobals($_GET, $key, $default);
}

/**
 * Gets a specific piece of data from the submitted form data.
 *
 * @param string $key
 *   The key to search for.
 * @param mixed $default
 *   The default value if the key does not exist.
 *
 * @return mixed|string
 *   The data from the submitted form data.
 */
function getFormDataPost(string $key, $default = '') {
    return requestFromSuperGlobals($_POST, $key, $default);
}

/**
 * Gets a specific piece of data from the session.
 *
 * @param string $key
 *   The key to search for.
 * @param mixed $default
 *   The default value if the key does not exist.
 *
 * @return mixed|string
 *   The data from the submitted form data.
 */
function sessionGet(string $key, $default = '') {
    return requestFromSuperGlobals($_SESSION, $key, $default);
}

/**
 * Requests a value from a super global.
 *
 * @param array $global
 *   The super global, such as GET or POST.
 * @param string $key
 *   The key to search for.
 * @param mixed $default
 *   The default value.
 *
 * @return string
 *   The sanitized string.
 */
function requestFromSuperGlobals(array $global, string $key, $default = '') {
    $value = $global[$key] ?? $default;
    if (is_string($value)) {
        return preventXSS($value);
    }

    return $value;
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
function sessionSave(string $key, $value = '', bool $overwrite = false) {
    if ($overwrite) {
        $_SESSION[$key] = $value;
    }

    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = $value;
    }
}

/**
 * Adds an user error.
 *
 * @param string $value
 *   The value.
 */
function addUserError(string $value) {
    $_SESSION['errors'][] = $value;
}

/**
 * Gets the user errors.
 *
 * @return array
 *   The found user errors.
 */
function getUserErrors() {
    $errors = sessionGet('errors', []);
    sessionKeyUnset('errors');

    return $errors;
}

/**
 * Adds an user message.
 *
 * @param string $message
 *   The message.
 */
function addUserMessage(string $message) {
    $_SESSION['messages'][] = $message;
}

/**
 * Gets the user messages.
 *
 * @return array
 *   The found user message.
 */
function getUserMessages() {
    $messages = sessionGet('messages', []);
    sessionKeyUnset('messages');

    return $messages;
}

/**
 * Unsets a specific piece of data from the session.
 *
 * @param string $key
 *   The key to be destroyed.
 */
function sessionKeyUnset(string $key) {
    if (isset($_SESSION[$key])) {
        unset($_SESSION[$key]);
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
function configGet(string $key, $default = '') {
    global $config;

    return $config[$key] ?? $default;
}

/**
 * Gets the base url.
 *
 * @return string
 *   The base url.
 */
function getBaseUrl() {
    return configGet('base_url');
}

/**
 * Gets the asset url.
 *
 * @param string $assetUrl
 *   The path to the asset.
 *
 * @return string
 *   The asset url.
 */
function getAssetUrl(string $assetUrl) {
    $baseUrl = getBaseUrl();

    return "{$baseUrl}/Assets/{$assetUrl}";
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
function getUrl(string $url) {
    $baseUrl = getBaseUrl();

    return "{$baseUrl}/{$url}";
}

/**
 * Gets the current url.
 *
 * @return string
 *   The current url.
 */
function getCurrentUrl() {
    $prefix = 'http://';
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        $prefix = 'https://';
    }

    return $prefix . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
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
 * Determines if the date is valid.
 *
 * @param string $date
 *   The date.
 * @param string $format
 *   The format of the date.
 *
 * @return bool
 *   Whether the date is valid or not.
 */
function isValidDate(string $date, string $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);

    return $d && $d->format($format) === $date;
}

/**
 * Determines if the date is valid.
 *
 * @param string $date
 *   The date.
 * @param string $format
 *   The format of the date.
 *
 * @return bool
 *   Whether the date is valid or not.
 */
function isValidDateTime(string $date, string $format = 'Y-m-d H:i:s') {
    $d = DateTime::createFromFormat($format, $date);

    return $d && $d->format($format) === $date;
}

/**
 * Formats a given date.
 *
 * @param string $date
 *   The date.
 *
 * @return string
 *   The formatted date.
 */
function dateFormatFull(string $date) {
    if (!isValidDate($date)) {
        return '';
    }

    setlocale(LC_TIME, 'nl_NL');

    return strtolower(strftime('%d %B %Y', strtotime($date)));
}

/**
 * Formats a given date.
 *
 * @param string $date
 *   The date.
 *
 * @return string
 *   The formatted date.
 */
function dateTimeFormatShort(string $date) {
    if (!isValidDateTime($date)) {
        return '';
    }

    setlocale(LC_TIME, 'nl_NL');

    return strtolower(strftime('%d-%m-%Y', strtotime($date)));
}

/**
 * Formats a given date.
 *
 * @param string $date
 *   The date.
 *
 * @return string
 *   The formatted date.
 */
function dateFormatShort(string $date) {
    if (!isValidDate($date)) {
        return '';
    }

    setlocale(LC_TIME, 'nl_NL');

    return strtolower(strftime('%d-%m-%Y', strtotime($date)));
}

/**
 * Dump and dies the script in order to debug a variable value.
 *
 * @param mixed ...$variables
 *   The values to be dumped.
 */
function dd(...$variables) {
    // Removes all previous printed items
    if (ob_get_contents()){
        ob_end_clean();
    }

    var_dump($variables);
    die();
}

/**
 * Replaces double quotes for white spaces.
 *
 * @param string $string
 *   The string.
 *
 * @return string
 *   The string without double quotes.
 */
function replaceDoubleQuotesForWhiteSpaces(string $string) {
    return str_replace('"', "", $string);
}

/**
 * Replaces quotes for white spaces.
 *
 * @param string $string
 *   The string.
 *
 * @return string
 *   The string without quotes.
 */
function replaceQuotesForWhiteSpaces(string $string) {
    return replaceDoubleQuotesForWhiteSpaces(str_replace("'", "", $string));
}

/**
 * Formats a price.
 *
 * @param float $price
 *   The price.
 *
 * @return string
 *   The formatted price.
 */
function priceFormat(float $price) {
    return number_format($price, 2, ",", ".");
}