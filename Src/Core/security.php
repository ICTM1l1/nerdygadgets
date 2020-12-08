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
        str_replace(';', '',
            htmlspecialchars($value, ENT_NOQUOTES)
        )
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

    return (bool) ($responseKeys["success"] ?? false);
}