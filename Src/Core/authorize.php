<?php

/**
 * Authorizes an user.
 */
function authorizeUser() {
    $loggedIn = (bool) sessionGet('LoggedIn', false);
    if (!$loggedIn) {
        redirect(getUrl('login.php'));
    }
}

/**
 * Authorizes an admin user.
 */
function authorizeAdmin() {
    authorizeUser();
    $personId = sessionGet('personID', 0);
    $account = getPeople($personId);
    
    // If the user is an employee, he is automatically an admin.
    return (bool) ($account['IsEmployee'] ?? 0);
}
