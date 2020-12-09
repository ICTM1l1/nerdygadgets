<?php
function csrf_token_private($size=32){
    return bin2hex(random_bytes($size));
}

function csrf_token(){
    if(session_status() != PHP_SESSION_ACTIVE){
        return false;
    }
    $px = $_SESSION["pexpiry"] ?? '';
    $pt = $_SESSION["ptoken"] ?? '';
    $overwrite = false;
    if($px != '' && time() >= $px){
        $overwrite = true;
    }
    session_save("ptoken", csrf_token_private(), $overwrite);
    session_save("pexpiry", time() + 3600, $overwrite);
    return hash_hmac("sha256", $_SERVER["SCRIPT_NAME"], $_SESSION["ptoken"]);
}

function csrf_validate($token, $destination){
    if(hash_equals(csrf_token(), $token)){
        return true;
    }
    add_user_error("Er is iets fout gegaan. Probeer het opnieuw.");
    if($destination != ''){
        redirect($destination);
    }
    return false;
}