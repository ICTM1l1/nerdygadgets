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