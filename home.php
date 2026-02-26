<?php
require_once "config/init.php";   // session + dil
require_once "config/Database.php";

$db = new Database();

$head_title = __("home_title");
include "head.php";

/* =========================
   RANDEVU KONTROLÜ
========================= */
$today = date('Y-m-d');

$sql = "
    SELECT COUNT(*) AS toplam
    FROM hasta_ziyaretleri
    WHERE ziyaret_tipi = 'Randevu'
    AND ziyaret_tarihi >= ?
";

$randevuSayisi = $db->fetch($sql, "s", [$today])['toplam'];

?>

<body class="bg-light">

<?php include "navbar.php"; ?>

<div class="container mt-4">

    <!-- Karşılama mesajı -->
    <div class="alert alert-success text-center fs-5">
        <?= __("welcome") ?>
        <strong><?= htmlspecialchars($_SESSION["username"]); ?></strong> 👋
    </div>

    <!-- Randevu Uyarısı -->
    <?php if ($randevuSayisi > 0): ?>
        <div class="alert alert-warning text-center fs-6">
            ⚠️ <strong><?= $randevuSayisi ?></strong>
            <?= __("upcoming_appointments") ?>
            <a href="<?= route('hasta_ziyaret_rapor') ?>" class="alert-link">
                <?= __("view") ?>
            </a>
        </div>
    <?php endif; ?>


    <!-- Dashboard grid -->
    <div class="row g-4 mt-3">

        <!-- Hasta Modülü -->
        <div class="col-md-4">
            <div class="card shadow-sm border-primary h-100">
                <div class="card-body text-center">
                    <h5 class="card-title"><?= __("patient_module") ?></h5>
                    <p class="card-text"><?= __("patient_module_desc") ?></p>
                    <a href="<?= route('hasta_listesi') ?>" class="btn btn-primary m-1">
                        <?= __("patient_list") ?>
                    </a>
                    <a href="<?= route('hasta_ekle') ?>" class="btn btn-success m-1">
                        <?= __("add_patient") ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- Kullanıcı Modülü -->
        <div class="col-md-4">
            <div class="card shadow-sm border-warning h-100">
                <div class="card-body text-center">
                    <h5 class="card-title"><?= __("user_module") ?></h5>
                    <p class="card-text"><?= __("user_module_desc") ?></p>
                    <a href="<?= route('kullanici_listesi') ?>" class="btn btn-warning text-dark m-1">
                        <?= __("add_user") ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- Raporlar -->
        <div class="col-md-4">
            <div class="card shadow-sm border-info h-100">
                <div class="card-body text-center">
                    <h5 class="card-title"><?= __("reports") ?></h5>
                    <p class="card-text"><?= __("reports_desc") ?></p>
                    <a href="<?= route('hasta_ziyaret_rapor') ?>" class="btn btn-info text-white m-1">
                        <?= __("visit_report") ?>
                    </a>
                    <a href="<?= route('istatistikler') ?>"
                       class="btn btn-info text-white m-1">
                        <?= __("statistics_data") ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- Cihazlar -->
        <div class="col-md-6">
            <div class="card shadow-sm border-success h-100">
                <div class="card-body text-center">
                    <h5 class="card-title"><?= __("devices") ?></h5>
                    <p class="card-text"><?= __("devices_desc") ?></p>
                    <a href="<?= route('cihaz_liste') ?>" class="btn btn-success m-1">
                        <?= __("devices") ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- Hastalıklar -->
        <div class="col-md-6">
            <div class="card shadow-sm border-danger h-100">
                <div class="card-body text-center">
                    <h5 class="card-title"><?= __("diseases") ?></h5>
                    <p class="card-text"><?= __("diseases_desc") ?></p>
                    <a href="<?= route('hastalik_liste') ?>" class="btn btn-danger m-1">
                        <?= __("diseases") ?>
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- Çıkış -->
    <div class="text-center mt-4">
        <a href="<?= route('logout') ?>" class="btn btn-outline-danger btn-lg">
            <?= __("logout") ?>
        </a>
    </div>

</div>

<?php include "footer.php"; ?>


