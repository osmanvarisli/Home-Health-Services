<?php
require_once "config/init.php";      // session başlatır
require_once "auth/auth.php";      // checkLogin()
require_once "config/Database.php";

checkLogin(); // 🔐 GİRİŞ ZORUNLU

$db = new Database();

/* Tabloları al */
$tablesRaw = $db->fetchAll("SHOW TABLES");
$tables = array_map(fn($t) => array_values($t)[0], $tablesRaw);

$backup = "-- VERİTABANI YEDEĞİ\n";
$backup .= "-- Tarih: " . date("Y-m-d H:i:s") . "\n\n";

foreach ($tables as $table) {

    // Tablo yapısı
    $create = $db->fetch("SHOW CREATE TABLE `$table`");
    $backup .= "DROP TABLE IF EXISTS `$table`;\n";
    $backup .= $create['Create Table'] . ";\n\n";

    // Veriler
    $rows = $db->fetchAll("SELECT * FROM `$table`");

    foreach ($rows as $row) {

        $columns = array_map(fn($c) => "`$c`", array_keys($row));

        $values = array_map(function ($v) {
            if ($v === null) return "NULL";
            return "'" . str_replace(
                ["\\", "'", "\n", "\r"],
                ["\\\\", "\\'", "\\n", "\\r"],
                $v
            ) . "'";
        }, array_values($row));

        $backup .= "INSERT INTO `$table` (" . implode(",", $columns) . ")
                    VALUES (" . implode(",", $values) . ");\n";
    }

    $backup .= "\n";
}

/* Dosya indir */
$fileName = "db_backup_" . date("Ymd_His") . ".sql";
header("Content-Type: application/sql");
header("Content-Disposition: attachment; filename=\"$fileName\"");
echo $backup;
exit;
