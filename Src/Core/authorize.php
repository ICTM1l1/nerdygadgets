<?php

/**
 * Authorizes an user.
 */
function authorizeUser() {
    $loggedIn = (bool) sessionGet('LoggedIn', false);
    if (!$loggedIn) {
        redirect(get_url('login.php'));
    }
}

/**
 * Authorizes an admin user.
 */
function authorizeAdmin() {
    authorizeUser();
    $personID = sessionGet('personID', 0);
    $account = getPeople($personID);
    
    // If the user is an employee, he is automatically an admin.
    return (bool) ($account['IsEmployee'] ?? 0);
}
