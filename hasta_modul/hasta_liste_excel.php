<?php
require_once "config/init.php";
require_once "config/Database.php";

$db = new Database();

// ---- Arama ----
$search = $_GET['search'] ?? "";
$where = "";
$params = [];
$types = "";

if ($search !== "") {
    $where = "WHERE h.ad_soyad LIKE ? 
              OR h.tc LIKE ? 
              OR h.hizmet_durum LIKE ?";
    $params = ["%$search%", "%$search%", "%$search%"];
    $types = "sss";
}

// ---- Sıralama ----
$sortableColumns = [
'id','ad_soyad','tc','hizmet_no','cinsiyet','dogum_tarihi',
'cep_tel','yatalak','beyan_ilce','adres','hizmet_durum',
'sonuc_durum','olum_tarihi','hiz_son_tarihi',
'hiz_baslama_tarihi','basvuru_tarihi','created_at'
];

$sortColumn = in_array($_GET['sort'] ?? '', $sortableColumns) ? $_GET['sort'] : 'id';
$sortOrder  = in_array(strtoupper($_GET['order'] ?? ''), ['ASC','DESC']) ? strtoupper($_GET['order']) : 'DESC';

// ---- Sorgu (Hastalıklar dahil) ----
$query = "
SELECT 
h.id,
h.ad_soyad,
h.tc,
h.hizmet_no,
h.cinsiyet,
h.dogum_tarihi,
h.cep_tel,
CASE WHEN h.yatalak = 1 THEN 'Yatalak' ELSE 'Değil' END AS yatalak_durum,
h.beyan_ilce,
h.adres,
h.hizmet_durum,
h.sonuc_durum,
h.olum_tarihi,
h.hiz_son_tarihi,
h.hiz_baslama_tarihi,
h.basvuru_tarihi,
h.created_at,
GROUP_CONCAT(ht.hastalik_adi SEPARATOR ', ') AS hastaliklar
FROM hasta_kayit h
LEFT JOIN hasta_hastaliklar hh ON h.id = hh.hasta_id
LEFT JOIN hastalik_tanimlari ht ON hh.hastalik_id = ht.id
$where
GROUP BY h.id
ORDER BY h.$sortColumn $sortOrder
";

$hastalar = $db->fetchAll($query, $types, $params);

// ---- Excel Header ----
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=Hasta_Listesi_" . date('Ymd_His') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "\xEF\xBB\xBF";
?>

<html>
<head>
<meta charset="UTF-8">
<style>
table {
    border-collapse: collapse;
    font-family: Arial, sans-serif;
    font-size: 13px;
}
th {
    background-color: #1F4E78;
    color: white;
    font-weight: bold;
    text-align: center;
    border: 1px solid #000;
    padding: 6px;
}
td {
    border: 1px solid #000;
    padding: 5px;
}
.center { text-align: center; }
</style>
</head>
<body>

<h3>Hasta Listesi</h3>
<p>Toplam Kayıt: <?= count($hastalar) ?></p>

<table>
<tr>
    <th>ID</th>
    <th>Ad Soyad</th>
    <th>TC</th>
    <th>Hizmet No</th>
    <th>Cinsiyet</th>
    <th>Doğum Tarihi</th>
    <th>Cep Tel</th>
    <th>Yatalak</th>
    <th>Hastalıklar</th>
    <th>İlçe</th>
    <th>Adres</th>
    <th>Hizmet Durumu</th>
    <th>Sonuç</th>
    <th>Ölüm Tarihi</th>
    <th>Hizmet Bitiş</th>
    <th>Hizmet Başlangıç</th>
    <th>Başvuru</th>
    <th>Oluşturulma</th>
</tr>

<?php foreach ($hastalar as $h): ?>
<tr>
    <td class="center"><?= $h['id'] ?></td>
    <td><?= htmlspecialchars($h['ad_soyad']) ?></td>
    <td class="center"><?= $h['tc'] ?></td>
    <td><?= $h['hizmet_no'] ?></td>
    <td class="center"><?= $h['cinsiyet'] ?></td>
    <td class="center"><?= $h['dogum_tarihi'] ?></td>
    <td><?= $h['cep_tel'] ?></td>
    <td class="center"><?= $h['yatalak_durum'] ?></td>
    <td><?= $h['hastaliklar'] ?></td>
    <td><?= $h['beyan_ilce'] ?></td>
    <td><?= $h['adres'] ?></td>
    <td><?= $h['hizmet_durum'] ?></td>
    <td><?= $h['sonuc_durum'] ?></td>
    <td class="center"><?= $h['olum_tarihi'] ?></td>
    <td class="center"><?= $h['hiz_son_tarihi'] ?></td>
    <td class="center"><?= $h['hiz_baslama_tarihi'] ?></td>
    <td class="center"><?= $h['basvuru_tarihi'] ?></td>
    <td class="center"><?= $h['created_at'] ?></td>
</tr>
<?php endforeach; ?>

</table>
</body>
</html>
