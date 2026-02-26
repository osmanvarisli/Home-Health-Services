<?php
require_once "config/Database.php";
$db = new Database();

// EKLE
if (isset($_POST["hastalik_adi"]) && !isset($_POST["action"])) {
    $db->execute(
        "INSERT INTO hastalik_tanimlari (hastalik_adi, hastalik_grubu) VALUES (?, ?)",
        "ss",
        [$_POST["hastalik_adi"], $_POST["hastalik_grubu"]]
    );
}

// GÜNCELLE
if (isset($_POST["action"]) && $_POST["action"] === "update") {
    $db->execute(
        "UPDATE hastalik_tanimlari SET hastalik_adi=?, hastalik_grubu=? WHERE id=?",
        "ssi",
        [$_POST["hastalik_adi"], $_POST["hastalik_grubu"], $_POST["id"]]
    );
}

// SİL
if (isset($_POST["action"]) && $_POST["action"] === "delete") {
    $db->execute(
        "DELETE FROM hastalik_tanimlari WHERE id=?",
        "i",
        [$_POST["id"]]
    );
}
