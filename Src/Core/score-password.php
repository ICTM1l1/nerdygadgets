<?php

/**
 * Check string repetitions.
 *
 * @param int $rep
 *   Amount of character repetitions to check.
 * @param string $password
 *   String to check repetitions in.
 * @return string
 *   String containing character repetitions.*/
function checkRepetition(int $rep, string $password){
    $split = str_split($password, $rep);
    $accumulator = array();

    foreach($split as $s){
        if(end($accumulator) !== $s){
            $accumulator[] = $s;
        }
    }
    return implode("", $accumulator);
}

/**
 * Function that scores password according to the same logic as the JavaScript
 * library on our front end. As such, the scoring logic has been duplicated
 * from here: https://github.com/elboletaire/password-strength-meter.
 *
 * Score the password as per the scoring mechanism used in the JS script.
 *
 * @param string $password
 *   Password string to check.
 * @param int $minlen
 *   Minimum password length.
 * @return int
 *   The score calculated for the password.
 */
function scorePassword(string $password, int $minlen){
    if (strlen($password) < $minlen) {
        return -1;
    }

    $score = 0;

    $score += strlen($password) * 4;
    for ($x = 1; $x <= 4; $x++) {
        $score += strlen(checkRepetition($x, $password)) - strlen($password);
    }

    // Has three numbers.
    if (preg_match('/.*(.*[0-9].*[0-9].*[0-9].*)/', $password)) {
        $score += 5;
    }
    // Has at leas two symbols.
    if (preg_match('/(.*[!,@,#,$,%,^,&,*,?,_,~].*[!,@,#,$,%,^,&,*,?,_,~])/', $password)) {
        $score += 5;
    }
    // Has upper and lower case characters.
    if (preg_match('/([a-z].*[A-Z])|([A-Z].*[a-z])/', $password)) {
        $score += 10;
    }
    // Has number and chars.
    if (preg_match('/([0-9].*[a-zA-Z])|([a-zA-Z].*[0-9])/', $password)) {
        $score += 15;
    }
    // Has number and symbol.
    if (preg_match('/([0-9].*[!@#$%^&*?_~])|([!@#$%^&*?_~].*[0-9])/', $password)) {
        $score += 15;
    }
    // Has char and symbol.
    if (preg_match('/([a-zA-Z].*[!@#$%^&*?_~])|([!@#$%^&*?_~].*[a-zA-Z])/', $password)) {
        $score += 15;
    }
    // Is only numbers and letters.
    if (preg_match('/^\w+$|^\d+$/', $password)) {
        $score -= 10;
    }
    if ($score > 100) {
        $score = 100;
    }
    if ($score < 0) {
        $score = 0;
    }

    return $score;
}