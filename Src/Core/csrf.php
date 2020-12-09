<?php
function csrf_token_private($size=32){
    return bin2hex(random_bytes($size));
}

function csrf_token(){
    if(session_status() != PHP_SESSION_ACTIVE){
        return false;
    }
    session_save("ptoken", csrf_token_private());
    return hash_hmac("sha256", $_SERVER["SCRIPT_NAME"], $_SESSION["ptoken"]);
}