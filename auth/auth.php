<?php
function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        $routes = include __DIR__ . '/routes.php';
        header("Location:" . route('login') );
        exit;
    }
}

function getUsername() {
    return isset($_SESSION['username']) ? $_SESSION['username'] : '';
}

