<?php require_once "config/init.php"; ?>

<?php include "istatistik_SQL_sorgular.php"; ?>

<?php $head_title="İstatistik Raporu"; include "head.php"; ?>
<body class="bg-light">
<?php include "navbar.php"; ?>
<div class="container mt-5">

<a href="<?= route('istatistik_pdf'); ?>?start=<?= $start ?>&end=<?= $end ?>"
   class="btn btn-outline-danger mb-3">
    📄 PDF Olarak İndir
</a>
    <!-- Tarih Aralığı Filtre -->
    <form method="get" class="row g-2 mb-4">
        <div class="col-md-3">
            <label>Başlangıç Tarihi</label>
            <input type="date" name="start" class="form-control" value="<?= htmlspecialchars($start) ?>">
        </div>
        <div class="col-md-3">
            <label>Bitiş Tarihi</label>
            <input type="date" name="end" class="form-control" value="<?= htmlspecialchars($end) ?>">
        </div>
        <div class="col-md-2 align-self-end">
            <button class="btn btn-primary w-100">Filtrele</button>
        </div>
    </form>

    <!-- Özet Kartlar -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-info h-100">
                <div class="card-body">
                    <h5 class="card-title">Ulaşılan Hasta</h5>
                    <h6>Hizmet Başlatıldı veya Hizmet Sonlandırıldı</h6>
                    <p class="card-text display-6"><?= $ulasilan_toplam ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success h-100">
                <div class="card-body">
                    <h5 class="card-title">Toplam Hizmet</h5>
                    <h6>Bütün Hizmetler</h6>
                    <p class="card-text display-6"><?= $hizmetler_toplam ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-warning h-100">
                <div class="card-body">
                    <h5 class="card-title">Ay İçinde Başvuran</h5>
                    <h6>Belirlenen tarih aralığında başvuru yapan toplam hasta sayısı</h6>
                    <p class="card-text display-6"><?= $basvuran['toplam'] ?? 0 ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-danger h-100">
                <div class="card-body">
                    <h5 class="card-title">Ay İçinde Sonlandırılan</h5>
                    <h6>Ay içinde sonlandırılan hasta sayısı</h6>
                    <p class="card-text display-6"><?= $ay_sonlandirma_toplam ?></p>
                </div>
            </div>
        </div>
    </div>
        
<?php include "istatistik_kartlar.php"; ?>

</div>

<?php include "footer.php"; ?>
