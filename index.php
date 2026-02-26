<?php
session_start();
require_once "config/init.php";
require_once "config/Database.php";
require_once "auth/auth.php";

$db = new Database();
$routes = include "routes.php";

// BASE_URL ve route() fonksiyonu
define('BASE_URL', '/evdebakim/');

if (!function_exists('route')) {
    function route($name) {
        return BASE_URL . str_replace('_', '-', $name);
    }
}

// URL parçasını al (query string’i atmak için parse_url kullanalım)
$request = trim(str_replace(BASE_URL, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)), '/');

if ($request === '') {
    $request = 'home';
}

$parts = explode('/', $request);
$routeKey = null;
$params = [];


// Route eşleştirme
foreach ($routes as $key => $route) {
    $routeParts = explode('/', str_replace('_', '-', $key)); // örn: hasta_duzenle → hasta-duzenle

    // ID parametreli route
    if (isset($route['params']) && count($route['params']) > 0) {
        $match = true;
        foreach ($routeParts as $i => $part) {
            if (($parts[$i] ?? '') !== str_replace('_', '-', $part)) {
                $match = false;
                break;
            }
        }
        if ($match) {
            $routeKey = $key;
            // Parametreleri al
            foreach ($route['params'] as $i => $paramName) {
                $params[$paramName] = $parts[count($routeParts) + $i] ?? null;
            }
            break;
        }
    } else {
        // Normal route
        if ($request === str_replace('_', '-', $key)) {
            $routeKey = $key;
            break;
        }
    }
}

if (!$routeKey) {
    http_response_code(404);
    echo "Sayfa bulunamadı";
    exit;
}

// Login kontrolü
if (($routes[$routeKey]['login_required'] ?? false)) {
    checkLogin();
}

// Parametreleri $_GET olarak ekle
foreach ($params as $k => $v) {
    $_GET[$k] = $v;
}

// Sayfayı include et
include $routes[$routeKey]['path'];
