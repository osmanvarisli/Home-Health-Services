<?php
/*
include "../auth/auth.php";
checkLogin();
*/
require_once "config/Database.php";
$db = new Database();

// ID kontrolü
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Geçersiz istek");
}

$id = (int) $_GET['id'];

// Silme işlemi
$db->execute(
    "DELETE FROM hasta_kayit WHERE id = ?",
    "i",
    [$id]
);

// Listeye geri dön
    header("Location: " . route('hasta_listesi'));
exit;
