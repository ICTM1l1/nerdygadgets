<?php

$debug = (bool) config_get('debug', false);

ini_set('display_errors', $debug ? '1' : '0');
ini_set('display_startup_errors', $debug ? '1' : '0');
error_reporting($debug ? E_ALL : -1);

// Enables custom error handling.
set_exception_handler('errorException');
set_error_handler('errorHandler');

/**
 * Adds custom error handling for errors.
 *
 * @param $errno
 *   Number of the error.
 * @param $errstr
 *   Error message.
 * @param $errfile
 *   File of the error.
 * @param $errline
 *   Line of the error.
 */
function errorHandler($errno, $errstr, $errfile, $errline) {
    // Removes all previous printed items
    ob_end_clean();

    $debug = (bool) config_get('debug', false);
    if (!$debug) {
        require_once __DIR__ . '/../../Public/Errors/500.php';
        die();
    }

    $message = '<div class="row"><div class="col-sm-12 mt-2 text-center">';
    $message .= "<b>Error:</b> [$errno] $errstr<br>";
    $message .= "Error on line $errline in $errfile<br>";
    $message .= '</div></div>';

    echo $message;
    die();
}

/**
 * Adds custom error handling for exceptions.
 *
 * @param $exception
 *   The exception.
 */
function errorException($exception) {
    // Removes all previous printed items
    ob_end_clean();

    $debug = (bool) config_get('debug', false);
    if (!$debug) {
        require_once __DIR__ . '/../../Public/Errors/500.php';
        die();
    }

    $message = '<div class="row"><div class="col-sm-12 mt-2 text-center">';
    $message .= '<b>Exception:</b>' . $exception->getMessage() . '<br>';
    $message .= '</div></div>';

    echo $message;
    die();
}