<?php

/**
 * Authorizes an user.
 */
function authorizeUser() {
    $loggedIn = (bool) session_get('LoggedIn', false);
    if (!$loggedIn) {
        redirect(get_url("login.php"));
    }
}