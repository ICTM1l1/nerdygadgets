<?php

/**
 * Prevents a XSS attack.
 *
 * @param $value
 *   The value.
 *
 * @return string
 *   The stripped string.
 */
function preventXSS(string $value) {
    return replaceQuotesForWhiteSpaces(
        str_replace('\\', '',
            str_replace(';', '',
            htmlspecialchars($value, ENT_NOQUOTES)
        ))
    );
}

/**
 * Validates the recaptcha response.
 *
 * @return false
 *   Whether the recaptcha challenge was successful or not.
 */
function validateRecaptcha() {
    $secretKey = config_get('recaptcha_private_key');
    $captcha = get_form_data_post('g-recaptcha-response');

    $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
    $response = file_get_contents($url);
    $responseKeys = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

    return (bool) ($responseKeys['success'] ?? false);
}

/**
 * Restarts the session.
 */
function restartSession() {
    session_unset();
    session_destroy();
    session_start();
}

/**
 * Sends the security headers.
 */
function securityHeaders() {
    header('X-XSS-Protection: 1; mode=block');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header("Feature-Policy: autoplay 'none'; camera 'none'");
    header("Strict-Transport-Security 'max-age=31536000; includeSubDomains; preload';");
}

/**
 * Secures the session.
 */
function secureSession() {
    // Gets the IP address of the user.
    $remoteAddr = $_SERVER['REMOTE_ADDR'] ?? '';
    // Gets the user agent, browser e.g. Chrome, from the user.
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    if (!isset($_SESSION['canary'])) {
        session_regenerate_id(true);

        $_SESSION['canary'] = [
            'birth' => time(),
            'IP' => $remoteAddr,
            'UA' => $userAgent,
        ];
    }

    if ($_SESSION['canary']['IP'] !== $remoteAddr || $_SESSION['canary']['UA'] !== $userAgent) {
        session_regenerate_id(true);
        restartSession();

        $_SESSION['canary'] = [
            'birth' => time(),
            'IP' => $remoteAddr,
            'UA' => $userAgent,
        ];
    }

    // Regenerate session ID every five minutes:
    if ($_SESSION['canary']['birth'] < time() - 300) {
        session_regenerate_id(true);
        $_SESSION['canary']['birth'] = time();
    }
}
