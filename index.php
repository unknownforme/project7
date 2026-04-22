<?php 
require_once __DIR__ . "/database/DB.php";
require_once __DIR__ . "/functions/error.php";
require __DIR__ . '/vendor/autoload.php';

$db_obj = new DB();
$auth = new \Delight\Auth\Auth($db_obj->getDbconn());

foreach (glob("controllers/*.php") as $filename) {
    require_once $filename;
}
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);    
$divided_url = explode("/", trim($url, "/"));
$path = $divided_url[2] ?? "home";
$additional_info = !empty($divided_url[3]) ? $divided_url[3] : NULL;


$dir_backs = "";
// dependent on how deep your project folder is, change the value of $x if you get too far back/ahead
for ($x = 4; $x <= count($divided_url); $x++) {
    $dir_backs .= "../"; 
}

$allowed_paths = [
    "public" => [
        "login",
        "home",
    ],
    "private" => [
        "overview",
        "account",
        "cells",
        "prisoners",
        "history",
        "users",
        "editroles",
        "logout",
        "editprisoner",
        "register",
        "addprisoner",
        "updateaccount",
        "addarrest",
    ],
];

if (!in_array($path, $allowed_paths["public"], true) && !in_array($path, $allowed_paths["private"], true)) {
    // tells the browser it cant find the page if the path wasnt found
    http_response_code(404);
    exit;
}


if ($auth->isLoggedIn()) {
    require_once "parts/private_header.php";    
} else {
    require_once "parts/public_header.php";
}

if (in_array($path, $allowed_paths["public"], true)) {
    
    //this is publicly reachable
    $controller = new publicController($dir_backs);
    if ($path === "login") {
        $controller->login($auth);
    } else {
        $controller->$path();
    }

} elseif (in_array($path, $allowed_paths["private"], true)) {
    
    //this is only reachable when logged in 
    if (!$auth->isLoggedIn()) {
        header("location:" . $dir_backs . "home");
        exit;
    }
    
    $controller = new privateController($auth, $path, $db_obj, $dir_backs);
    if (!isset($additional_info)) {
        $controller->$path();
    } else {
        $controller->$path($additional_info);
    }

}

$error_id = intval(filter_input(INPUT_GET, "error", FILTER_SANITIZE_SPECIAL_CHARS)) ?? null;
$outside_element = error($error_id);
require_once "parts/bottom.php";
