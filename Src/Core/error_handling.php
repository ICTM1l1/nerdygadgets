<?php

$debug = (bool) configGet('debug', false);

ini_set('display_errors', (int) $debug);
ini_set('display_startup_errors', (int) $debug);
error_reporting($debug ? E_ALL : 0);

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
    if(ob_get_contents()){
        ob_end_clean();
    }

    $debug = (bool) configGet('debug', false);
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
 * @param Exception $exception
 *   The exception.
 */
function errorException($exception) {
    // Removes all previous printed items
    if(ob_get_contents()){
        ob_end_clean();
    }

    $debug = (bool) configGet('debug', false);
    if (!$debug) {
        require_once __DIR__ . '/../../Public/Errors/500.php';
        die();
    }

    $message = '<div class="row"><div class="col-sm-12 mt-2 text-center">';
    $message .= "<h2><b>Exception:</b> {$exception->getMessage()} </h2><br>";
    $message .= "On line {$exception->getLine()} from file {$exception->getFile()} <br><hr>";

    // Build error stack trace.
    foreach ($exception->getTrace() as $singleTrace) {
        if (isset($singleTrace['line']) && !empty($singleTrace['line'])) {
            $message .= "On line {$singleTrace['line']} <br>";
        }

        if (isset($singleTrace['file']) && !empty($singleTrace['file'])) {
            $message .= "In file {$singleTrace['file']} <br>";
        }

        if (isset($singleTrace['function']) && !empty($singleTrace['function'])) {
            $message .= "In function {$singleTrace['function']} ";
        }

        if (isset($singleTrace['class']) && !empty($singleTrace['class'])) {
            $message .= "in class {$singleTrace['class']} ";
        }

        $message .= "<br><br>";
    }

    $message .= '</div></div>';

    echo $message;
    die();
}