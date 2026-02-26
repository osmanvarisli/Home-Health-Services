<?php
session_start();

require_once "../config/Database.php";
require_once "../config/Csrf.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: register.php");
    exit;
}

/* CSRF KONTROL */
if (!Csrf::verify($_POST["csrf_token"] ?? null)) {
    $_SESSION["error"] = "Geçersiz CSRF token";
    header("Location: register.php");
    exit;
}

$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";
$passwordConfirm = $_POST["password_confirm"] ?? "";

if ($username === "" || $password === "" || $passwordConfirm === "") {
    $_SESSION["error"] = "Tüm alanlar zorunlu";
    header("Location: register.php");
    exit;
}

if ($password !== $passwordConfirm) {
    $_SESSION["error"] = "Şifreler uyuşmuyor";
    header("Location: register.php");
    exit;
}

if (strlen($password) < 6) {
    $_SESSION["error"] = "Şifre en az 6 karakter olmalı";
    header("Location: register.php");
    exit;
}

$db = new Database();

/* Kullanıcı var mı */
$exists = $db->fetch(
    "SELECT id FROM users WHERE username = ? LIMIT 1",
    "s",
    [$username]
);

if ($exists) {
    $_SESSION["error"] = "Bu kullanıcı adı zaten kayıtlı";
    header("Location: register.php");
    exit;
}

/* KAYIT */
$db->execute(
    "INSERT INTO users (username, password) VALUES (?, ?)",
    "ss",
    [$username, password_hash($password, PASSWORD_DEFAULT)]
);

Csrf::destroy();

$_SESSION["success"] = "Kayıt başarılı, giriş yapabilirsiniz";
header("Location: register.php");
exit;
