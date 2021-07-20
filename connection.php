<?php

$mysqli = new mysqli("localhost", "root", "", "randis");

$photoDir = 'photos/';
$photoPath = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/".basename(__DIR__)."/".$photoDir;

function pushError(&$errors, $field, $message){
    $error = [
        'field' => $field,
        'message' => $message
    ];
    array_push($errors, $error);
}

function badResponse( $code, $msg, $errors){
   
    $badResponse = [
        'code' => $code,
        'message' => $msg,
        'data' => null,
        'errors' => $errors
    ];
    http_response_code($code);
    die(json_encode($badResponse));
}

function GUID() {
    return bin2hex(openssl_random_pseudo_bytes(16));
}

function generateFileName($fileName){
    $guid = GUID();
    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
    return $guid . '.' . $ext;
}
?>