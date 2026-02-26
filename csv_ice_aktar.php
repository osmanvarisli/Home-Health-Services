<?php
require_once "config/Database.php";
$db = new Database();

$dosya = "hasta_listesi.csv";
if (!file_exists($dosya)) die("Dosya bulunamadı!");

// Dosyayı aç
$handle = fopen($dosya, "r");

// Başlık satırını oku ve UTF-8’e çevir
$baslik = fgetcsv($handle, 0, ";");
if ($baslik) {
    foreach ($baslik as $k => $v) {
        $baslik[$k] = mb_convert_encoding($v, 'UTF-8', 'Windows-1254');
    }
}

// Satır satır oku
while (($row = fgetcsv($handle, 0, ";")) !== false) {
    // UTF-8 dönüştür
    foreach ($row as $k => $v) {
        $row[$k] = mb_convert_encoding($v, 'UTF-8', 'Windows-1254');
    }

    // Satır en az 16 sütun olmalı
    if (count($row) < 16) continue;

    // CSV sütunlarını al
    $no           = $row[0] ?? null;
    $ad_soyad     = $row[1] ?? null;
    $tc           = $row[2] ?? null;
    $dogum_tarihi = $row[3] ?? null;
    $cins         = $row[4] ?? null;
    $hizmet_durum = $row[5] ?? null;
    $hiz_baslama  = $row[6] ?? null;
    $hiz_sonlanma = $row[7] ?? null;
    $sonuc_durum  = $row[9] ?? "Belirtilmemiş";
    $yatalak      = $row[10] ?? null;
    $hastaliklar  = $row[11] ?? null;
    $beyan_ilce   = $row[13] ?? null; // İlçe olduğu gibi alınacak
    $adres        = $row[14] ?? null;
    $cep          = $row[15] ?? null;

    if (empty(trim($ad_soyad))) continue;

    // Tarih formatı
    $dogum_tarihi = !empty($dogum_tarihi) ? date("Y-m-d", strtotime(str_replace('.', '-', $dogum_tarihi))) : null;
    $hiz_baslama  = !empty($hiz_baslama) ? date("Y-m-d", strtotime(str_replace('.', '-', $hiz_baslama))) : null;
    $hiz_sonlanma = !empty($hiz_sonlanma) ? date("Y-m-d", strtotime(str_replace('.', '-', $hiz_sonlanma))) : null;

    $yatalak = (trim($yatalak) === "Yatalak") ? 1 : 0;
    $adres   = !empty(trim($adres)) ? $adres : null;
    $cep     = !empty(trim($cep)) ? $cep : null;

    // Hasta kaydı ekle
    $db->execute(
        "INSERT INTO hasta_kayit
        (hizmet_no, ad_soyad, tc, dogum_tarihi, cinsiyet,
         hizmet_durum, hiz_baslama_tarihi, hiz_son_tarihi,
         sonuc_durum, yatalak, beyan_ilce, adres, cep_tel)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)",
        "ssssssssissss",
        [
            $no,
            $ad_soyad,
            $tc,
            $dogum_tarihi,
            $cins,
            $hizmet_durum,
            $hiz_baslama,
            $hiz_sonlanma,
            $sonuc_durum,
            $yatalak,
            $beyan_ilce, // direkt CSV’den geldiği gibi
            $adres,
            $cep
        ]
    );

    $hastaId = $db->lastInsertId();

    // Hastalıkları ekle
    if (!empty($hastaliklar)) {
        $liste = explode(";", $hastaliklar);
        foreach ($liste as $adi) {
            $adi = trim($adi);
            if ($adi === "") continue;

            $h = $db->fetch(
                "SELECT id FROM hastalik_tanimlari WHERE hastalik_adi=?",
                "s",
                [$adi]
            );
            if ($h) {
                // Daha önce eklenmiş mi kontrol et
                $kontrol = $db->fetch(
                    "SELECT 1 FROM hasta_hastaliklar WHERE hasta_id=? AND hastalik_id=?",
                    "ii",
                    [$hastaId, $h["id"]]
                );
                if (!$kontrol) {
                    $db->execute(
                        "INSERT INTO hasta_hastaliklar (hasta_id, hastalik_id)
                        VALUES (?,?)",
                        "ii",
                        [$hastaId, $h["id"]]
                    );
                }
            }
        }
    }
}

fclose($handle);
echo "İçe aktarma tamamlandı!";
?>
