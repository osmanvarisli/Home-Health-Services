<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['lang'])) {
    $allowedLangs = ['tr', 'en'];
    if (in_array($_GET['lang'], $allowedLangs)) {
        $_SESSION['lang'] = $_GET['lang'];
    }
}

$lang = $_SESSION['lang'] ?? 'tr';

$langFile = dirname(__DIR__) . "/lang/$lang.php";

$translations = [];
if (file_exists($langFile)) {
    $translations = include $langFile;
}

function __($key) {
    global $translations;
    return $translations[$key] ?? $key;
}
