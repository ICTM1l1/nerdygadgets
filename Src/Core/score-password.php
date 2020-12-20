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
function check_repetition(int $rep, string $password){
    $result = '';
    //$repeated = false;
    $l = strlen($password);

    for($i = 0; $i < $l; $i++){
        $repeated = true;
        $j = 0;
        for(; $j < $rep && ($j + $i + $rep) < $l; $j++){
            $repeated = $repeated && ($password[$j + $i] === $password[$j + $i + $rep]);
        }
        if($j < $rep){
            $repeated = false;
        }
        if($repeated){
            $i += $rep - 1;
            $repeated = false;
        }
        else {
            $result .= $password[$i];
        }
    }
    return $result;
}

/**
 * Function that scores password according to the same logic as the JavaScript
 * library on our front end. As such, the scoring logic has been duplicated
 * from here: https://github.com/elboletaire/password-strength-meter
 *
 * @param string $password
 *   Password string to check.
 * @param int $minlen
 *   Minimum password length.
 * @return int
 *   The score calculated for the password.
 */
function score_password(string $password, int $minlen){
    /*Score the password as per the scoring mechanism used in the JS script*/

    if(strlen($password) < $minlen){
        return -1;
    }

    $score = 0;

    $score += strlen($password) * 4;
    for($x = 1; $x <= 4; $x++){
        $score += strlen(check_repetition($x, $password)) - strlen($password);
    }

    //has three numbers
    if(preg_match('/.*(.*[0-9].*[0-9].*[0-9].*)/', $password)){
        $score += 5;
    }
    //has at leas two symbols
    if(preg_match('/(.*[!,@,#,$,%,^,&,*,?,_,~].*[!,@,#,$,%,^,&,*,?,_,~])/', $password)){
        $score += 5;
    }
    //has upper and lower case characters
    if(preg_match('/([a-z].*[A-Z])|([A-Z].*[a-z])/', $password)){
        $score += 10;
    }
    //has number and chars
    if(preg_match('/([0-9].*[a-zA-Z])|([a-zA-Z].*[0-9])/', $password)){
        $score += 15;
    }
    //has number and symbol
    if(preg_match('/([0-9].*[!@#$%^&*?_~])|([!@#$%^&*?_~].*[0-9])/', $password)){
        $score += 15;
    }
    //has char and symbol
    if(preg_match('/([a-zA-Z].*[!@#$%^&*?_~])|([!@#$%^&*?_~].*[a-zA-Z])/', $password)){
        $score += 15;
    }
    //is only numbers and letters
    if(preg_match('/^\w+$|^\d+$/', $password)){
        $score -= 10;
    }
    if($score > 100){
        $score = 100;
    }
    if($score < 0){
        $score = 0;
    }
    return $score;
}