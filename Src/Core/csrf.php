<?php

/**
 * Generate a semi-random private token to use as key for CSRF token.
 *
 * @param int $size
 *   Token length in bytes.
 * @return string
 *   String representation of the generated token.
 * @throws Exception
 *   Exception is thrown when no randomness source can be found.
 */
function csrf_get_token_private(int $size=32){
    return bin2hex(random_bytes($size));
}

/**
 * Generate CSRF token from current page and pseudo-random key.
 *
 * @return string
 *   Returns string representation of computed token. Empty string if
 *   something has gone wrong.
 * @throws Exception
 *   Thrown when there is no adequate randomness source for the
 *   pseudo-random token.
 */
function csrf_get_token(){
    if(session_status() != PHP_SESSION_ACTIVE){
        return "";
    }
    $px = $_SESSION["pexpiry"] ?? '';
    $overwrite = false;
    if($px != '' && time() >= $px){
        $overwrite = true;
    }
    session_save("ptoken", csrf_get_token_private(), $overwrite);
    session_save("pexpiry", time() + 3600, $overwrite);
    return hash_hmac("sha256", $_SERVER["SCRIPT_NAME"], $_SESSION["ptoken"]) ?? "";
}

/**
 * Check if the sent token matches the presently valid one.
 * Redirects and adds user error if token not valid.
 *
 * @param string $destination
 *   Page to redirect to if tokens do not match. Does not redirect on
 *   empty string.
 * @return bool
 *   Returns true of tokens match. Only returns false when there is
 *   no redirect (empty redirect string) or if the request was not POST.
 * @throws Exception
 *   Thrown when there is no adequate randomness source for private key.
 */
function csrf_validate($destination = ''){
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        if(hash_equals(csrf_get_token(), $_POST["token"] ?? "")){
            return true;
        }
        add_user_error("Er is iets fout gegaan. Probeer het opnieuw.");
        if($destination != ''){
            redirect($destination);
        }
    }
    return false;
}