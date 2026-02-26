<?php
require_once "config/init.php";   // session + dil
require_once "config/Database.php";
$db = new Database();

/* Filtreleme */
$filterYear = $_GET['yil'] ?? '';
$filterMonth = $_GET['ay'] ?? '';
$filterTip = $_GET['ziyaret_tipi'] ?? '';

/* SQL ve parametreler */
$sql = "SELECT hz.hasta_id as hasta_id,hz.ziyaret_tarihi, hz.ziyaret_tipi, hz.aciklama, h.ad_soyad, h.tc
        FROM hasta_ziyaretleri hz
        JOIN hasta_kayit h ON h.id = hz.hasta_id
        WHERE 1=1";

$params = "";
$types = "";

/* Yıl filtresi */
if ($filterYear) {
    $sql .= " AND YEAR(hz.ziyaret_tarihi) = ?";
    $params .= $filterYear . ",";
    $types .= "i";
}

/* Ay filtresi */
if ($filterMonth) {
    $sql .= " AND MONTH(hz.ziyaret_tarihi) = ?";
    $params .= $filterMonth . ",";
    $types .= "i";
}

/* Ziyaret tipi filtresi */
if ($filterTip) {
    $sql .= " AND hz.ziyaret_tipi = ?";
    $params .= $filterTip . ",";
    $types .= "s";
}

/* Sondaki virgülü kaldır */
$params = $params ? explode(",", rtrim($params, ",")) : [];

/* Sorguyu çalıştır */
$ziyaretler = $db->fetchAll($sql . " ORDER BY hz.ziyaret_tarihi DESC", $types, $params);
?>


<?php 
    $head_title="Ziyaret Raporu";
    include "head.php"; 
?>


<body class="bg-light">
<?php include "navbar.php"; ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <strong>Ziyaret Raporu</strong>

            </div>
        </div>


        <!-- Filtreleme Formu -->
        <form method="get" class="row g-2 align-items-center mt-3">
            <div class="col-auto">
                <select name="yil" class="form-select form-select-sm">
                    <option value="">Yıl</option>
                    <?php for ($y=2020; $y<=2030; $y++): ?>
                        <option value="<?= $y ?>" <?= $filterYear == $y ? "selected" : "" ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="col-auto">
                <select name="ay" class="form-select form-select-sm">
                    <option value="">Ay</option>
                    <?php for ($m=1; $m<=12; $m++): ?>
                        <option value="<?= $m ?>" <?= $filterMonth == $m ? "selected" : "" ?>><?= $m ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="col-auto">
                <select name="ziyaret_tipi" class="form-select form-select-sm">
                    <option value="">Tür</option>
                    <option value="Randevu" <?= $filterTip=="Randevu" ? "selected" : "" ?>>Randevu</option>
                    <option value="Ziyaret" <?= $filterTip=="Ziyaret" ? "selected" : "" ?>>Ziyaret</option>
                </select>
            </div>

            <div class="col-auto">
                <button class="btn btn-sm btn-primary">Filtrele</button>
            </div>

            <div class="col-auto">
                <a href="ziyaret_rapor.php" class="btn btn-sm btn-secondary">Temizle</a>
            </div>
        </form>


        <!-- Ziyaret Tablosu -->
        <div class="card shadow mt-3">
            <div class="card-body p-0">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark">
                    <tr>
                        <th>Hasta Adı</th>
                        <th>TC</th>
                        <th>Ziyaret Tarih</th>
                        <th>Ziyaret Tipi</th>
                        <th>Açıklama</th>
                        <th>Getir</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!$ziyaretler): ?>
                        <tr><td colspan="5" class="text-center text-muted">Kayıt bulunamadı</td></tr>
                    <?php else: ?>
                        <?php foreach ($ziyaretler as $z): ?>
                            <tr>
                                <td><?= htmlspecialchars($z['ad_soyad']) ?></td>
                                <td><?= $z['tc'] ?></td>
                                <td><?= date("d.m.Y", strtotime($z['ziyaret_tarihi'])) ?></td>
                                <td><?= $z['ziyaret_tipi'] ?></td>
                                <td><?= htmlspecialchars($z['aciklama']) ?></td>
                                <td>
                                    <!-- Hasta Görüntüle Butonu -->
                                    <a href="<?= route('hasta_goruntule') . '/' . $z['hasta_id'] ?>" class="btn btn-sm btn-info">
                                        Görüntüle
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
