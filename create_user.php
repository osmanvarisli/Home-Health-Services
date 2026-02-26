<?php
require_once "config/Database.php";

$db = new Database();

$username = "admin";
$password = password_hash("123456", PASSWORD_DEFAULT);

$db->execute(
    "INSERT INTO users (username, password) VALUES (?, ?)",
    "ss",
    [$username, $password]
);

echo "Kullanıcı oluşturuldu";
