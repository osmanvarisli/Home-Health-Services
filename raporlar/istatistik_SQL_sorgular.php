<?php

$db = new Database();

$start = $_GET['start'] ?? '';
$end   = $_GET['end'] ?? '';

/* ================== Ulaşılan Hasta Sayısı (Cinsiyet) ================== */
$u_params = [];
$u_types  = "";
$u_where = " WHERE 1=1 ";

if ($start) {
    $u_where .= " AND h.hiz_baslama_tarihi >= ? ";
    $u_params[] = $start;
    $u_types .= "s";
}
if ($end) {
    $u_where .= " AND h.hiz_baslama_tarihi <= ? ";
    $u_params[] = $end;
    $u_types .= "s";
}

$ulasilan = $db->fetchAll("
    SELECT cinsiyet, COUNT(*) as toplam
    FROM hasta_kayit h
    $u_where
    AND hizmet_durum IN ('Hizmet Başlatıldı','Hizmet Sonlandırıldı')
    GROUP BY cinsiyet
", $u_types, $u_params);

$ulasilan_toplam = array_sum(array_column($ulasilan,'toplam'));

/* ================== Toplam Hizmet Sayıları ================== */
$hizmetler = $db->fetchAll("
    SELECT hizmet_durum, COUNT(*) as toplam
    FROM hasta_kayit h
    $u_where
    GROUP BY hizmet_durum
", $u_types, $u_params);

$hizmetler_toplam = array_sum(array_column($hizmetler,'toplam'));

/* ================== Hizmet Sonlandırma Nedenleri ================== */
$sn_params = $u_params;
$sn_types  = $u_types;

$sonNeden = $db->fetchAll("
    SELECT sonuc_durum, COUNT(*) as toplam
    FROM hasta_kayit h
    $u_where
    AND hizmet_durum='Hizmet Sonlandırıldı'
    GROUP BY sonuc_durum
", $sn_types, $sn_params);

$sonNeden_toplam = array_sum(array_column($sonNeden,'toplam'));

/* ================== Hastalık Grupları (Aktif Hastalar) ================== */
$hast_params = [];
$hast_types  = "";
$hast_where = " WHERE h.hizmet_durum != 'Hizmet Sonlandırıldı' ";
if ($start) {
    $hast_where .= " AND h.hiz_baslama_tarihi >= ? ";
    $hast_params[] = $start;
    $hast_types .= "s";
}
if ($end) {
    $hast_where .= " AND h.hiz_baslama_tarihi <= ? ";
    $hast_params[] = $end;
    $hast_types .= "s";
}

$hastaliklar = $db->fetchAll("
    SELECT ht.hastalik_grubu, ht.hastalik_adi, COUNT(*) as toplam
    FROM hasta_hastaliklar hh
    JOIN hastalik_tanimlari ht ON ht.id = hh.hastalik_id
    JOIN hasta_kayit h ON h.id = hh.hasta_id
    $hast_where
    GROUP BY ht.hastalik_grubu, ht.hastalik_adi
    ORDER BY ht.hastalik_grubu, ht.hastalik_adi
", $hast_types, $hast_params);

/* ================== Ay İçinde Başvuran Hasta Sayısı ================== */
$basv_params = [];
$basv_types  = "";
$basv_where = " WHERE 1=1 ";

if($start){
    $basv_where .= " AND basvuru_tarihi >= ? ";
    $basv_params[] = $start;
    $basv_types .= "s";
}
if($end){
    $basv_where .= " AND basvuru_tarihi <= ? ";
    $basv_params[] = $end;
    $basv_types .= "s";
}

$basvuran = $db->fetch("
    SELECT COUNT(*) as toplam
    FROM hasta_kayit h
    $basv_where
", $basv_types, $basv_params);

/* ================== Ay İçinde Sonlandırılan Hasta Sayısı ve Nedenleri ================== */
$ay_params = [];
$ay_types  = "";
$ay_where = " WHERE hizmet_durum='Hizmet Sonlandırıldı' ";

if($start){
    $ay_where .= " AND hiz_son_tarihi >= ? ";
    $ay_params[] = $start;
    $ay_types .= "s";
}
if($end){
    $ay_where .= " AND hiz_son_tarihi <= ? ";
    $ay_params[] = $end;
    $ay_types .= "s";
}

$ay_sonlandirma = $db->fetchAll("
    SELECT sonuc_durum, COUNT(*) as toplam
    FROM hasta_kayit h
    $ay_where
    GROUP BY sonuc_durum
", $ay_types, $ay_params);

$ay_sonlandirma_toplam = array_sum(array_column($ay_sonlandirma,'toplam'));

?>