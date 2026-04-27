<?php 

function error($error_id = null) {
    if (empty($error_id)) {return;}
    switch ($error_id) {
        case 1:
            $error_message = "wachtwoord voldeed niet aan de eisen";
        break;
        case 2:
            $error_message = "wachtwoord verkeerd of mail niet verbonden met een account";
        break;
        default:
            $error_message = "unknown error";
        break;

    }
    $html = '<div id="error">error!<hr id="error_separator">' . $error_message . '</div>';
    return $html;
}