<?php

$db = new Database();

if (isset($_POST["cihaz_adi"]) && !isset($_POST["action"])) {
    $db->execute(
        "INSERT INTO cihaz_tanimlari (cihaz_adi) VALUES (?)",
        "s",
        [$_POST["cihaz_adi"]]
    );
}

if ($_POST["action"] === "update") {
    $db->execute(
        "UPDATE cihaz_tanimlari SET cihaz_adi=? WHERE id=?",
        "si",
        [$_POST["cihaz_adi"], $_POST["id"]]
    );
}

if ($_POST["action"] === "delete") {
    $db->execute(
        "DELETE FROM cihaz_tanimlari WHERE id=?",
        "i",
        [$_POST["id"]]
    );
}
?>