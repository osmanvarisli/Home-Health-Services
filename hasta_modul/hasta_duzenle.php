<?php
require_once "config/init.php";   // session + dil
require_once "config/Database.php";

$db = new Database();

// ID kontrolü
$id = $_POST['id'] ?? $_GET['id'] ?? 0;
$id = (int)$id;
if ($id <= 0) {
    die("Geçersiz ID");
}

// Hasta bilgilerini çek
$hasta = $db->fetch("SELECT * FROM hasta_kayit WHERE id=?", "i", [$id]);
if (!$hasta) {
    die("Kayıt bulunamadı");
}

// Form gönderildiğinde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db->execute(
        "UPDATE hasta_kayit SET
            ad_soyad=?,
            tc=?,
            cinsiyet=?,
            dogum_tarihi=?,
            basvuru_tarihi=?,
            hiz_baslama_tarihi=?,
            hiz_son_tarihi=?,
            olum_tarihi=?,
            cep_tel=?,
            yatalak=?,
            beyan_ilce=?,
            adres=?,
            hizmet_durum=?,
            sonuc_durum=?
        WHERE id=?",
        "ssssssssisssssi",
        [
            $_POST["ad_soyad"] ?? '',
            $_POST["tc"] ?? '',
            $_POST["cinsiyet"] ?? '',
            $_POST["dogum_tarihi"] ?? null,
            $_POST["basvuru_tarihi"] ?? null,
            $_POST["hiz_baslama_tarihi"] ?? null,
            $_POST["hiz_son_tarihi"] ?? null,
            $_POST["olum_tarihi"] ?? null,
            $_POST["cep_tel"] ?? '',
            isset($_POST["yatalak"]) ? 1 : 0,
            $_POST["beyan_ilce"] ?? '',
            $_POST["adres"] ?? '',
            $_POST["hizmet_durum"] ?? '',
            $_POST["sonuc_durum"] ?? '',
            $id
        ]
    );

    header("Location: " . route('hasta_listesi'));
    exit;
}

// İlçeler
$ilceler = require "config/ilceler.php";

// Sayfa başlığı
$head_title = __("edit_patient");
include "head.php";
?>

<body class="bg-light">
<?php include "navbar.php"; ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-warning fw-bold">
            <?= __("edit_patient_info") ?>
        </div>

        <div class="card-body">
            <form method="post" class="row g-3">

                <input type="hidden" name="id" value="<?= $hasta['id'] ?>">

                <div class="col-md-6">
                    <label class="form-label"><?= __("name_surname") ?></label>
                    <input type="text" class="form-control" name="ad_soyad" value="<?= htmlspecialchars($hasta["ad_soyad"]) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">TC</label>
                    <input type="text" maxlength="11" class="form-control" name="tc" value="<?= htmlspecialchars($hasta["tc"]) ?>" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label"><?= __("service_no") ?></label>
                    <input type="text" class="form-control fw-bold text-primary" value="<?= $hasta["id"] ?>" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label"><?= __("gender") ?></label>
                    <select name="cinsiyet" class="form-select" required>
                        <option value="E" <?= $hasta["cinsiyet"] === "E" ? "selected" : "" ?>><?= __("male") ?></option>
                        <option value="K" <?= $hasta["cinsiyet"] === "K" ? "selected" : "" ?>><?= __("female") ?></option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label"><?= __("birth_date") ?></label>
                    <input type="date" class="form-control" name="dogum_tarihi" value="<?= $hasta["dogum_tarihi"] ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label"><?= __("application_date") ?></label>
                    <input type="date" class="form-control" name="basvuru_tarihi" value="<?= $hasta["basvuru_tarihi"] ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label"><?= __("service_start_date") ?></label>
                    <input type="date" class="form-control" name="hiz_baslama_tarihi" value="<?= $hasta["hiz_baslama_tarihi"] ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label"><?= __("service_end_date") ?></label>
                    <input type="date" class="form-control" name="hiz_son_tarihi" value="<?= $hasta["hiz_son_tarihi"] ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label"><?= __("death_date") ?></label>
                    <input type="date" class="form-control" name="olum_tarihi" value="<?= $hasta["olum_tarihi"] ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label"><?= __("mobile_phone") ?></label>
                    <input type="text" class="form-control" name="cep_tel" value="<?= htmlspecialchars($hasta["cep_tel"]) ?>">
                </div>

                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="yatalak" <?= $hasta["yatalak"] ? "checked" : "" ?>>
                        <label class="form-check-label"><?= __("bedridden") ?></label>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label"><?= __("declared_district") ?></label>
                    <select name="beyan_ilce" class="form-select" required>
                        <option value=""><?= __("select") ?></option>
                        <?php foreach ($ilceler as $ilce): ?>
                            <option value="<?= htmlspecialchars($ilce) ?>" <?= $hasta["beyan_ilce"] === $ilce ? "selected" : "" ?>>
                                <?= htmlspecialchars($ilce) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label"><?= __("service_status") ?></label>
                    <?php
                    $durumlar = [
                        "status_registered",
                        "status_started",
                        "status_finished",
                        "status_not_given",
                        "status_not_eligible",
                        "status_family_doctor"
                    ];
                    ?>
                    <select name="hizmet_durum" class="form-select" required>
                        <?php foreach ($durumlar as $key): ?>
                            <option value="<?= __($key) ?>" <?= $hasta["hizmet_durum"] == __($key) ? "selected" : "" ?>>
                                <?= __($key) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label"><?= __("result_status") ?></label>
                    <select name="sonuc_durum" id="sonuc_durum" class="form-select" required></select>
                </div>

                <div class="col-12">
                    <label class="form-label"><?= __("address") ?></label>
                    <textarea class="form-control" name="adres" rows="3"><?= htmlspecialchars($hasta["adres"]) ?></textarea>
                </div>

                <div class="col-12 text-end">
                    <button class="btn btn-success px-4"><?= __("update") ?></button>
                    <a href="<?= route('hasta_listesi') ?>" class="btn btn-secondary"><?= __("cancel") ?></a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const hizmetDurumSelect = document.querySelector('[name="hizmet_durum"]');
const sonucDurumSelect = document.getElementById('sonuc_durum');
const mevcutSonuc = <?= json_encode($hasta["sonuc_durum"]) ?>;

const sonucMap = {
    "Hasta Kayıt": ["Hasta Kayıt"],
    "Hizmet Başlatıldı": ["Hizmet Başlatma"],
    "Hizmet Sonlandırıldı": [
        "İyileşme",
        "Tedavi ESH Pers. Gerektirmiyor",
        "Sonlandırmanın Talep Edilmesi",
        "Tedaviyi Red Etme",
        "Tedaviye Yanıt Alamama",
        "İkamet Değişikliği",
        "Vefat",
        "Diğer ESH Birimine Devredilen"
    ],
    "Hizmet Verilmedi": [
        "Tedaviyi Red Etme",
        "İkamet Değişikliği",
        "Vefat",
        "Diğer ESH Birimine Devredilen",
        "Hatalı Kayıt"
    ],
    "Hizmete Uygun Değil": ["Hasta Kayıt"],
    "Aile Hekimine Sevk": ["Hasta Kayıt"]
};

function doldur(durum) {
    sonucDurumSelect.innerHTML = "";
    (sonucMap[durum] || []).forEach(item => {
        const opt = document.createElement("option");
        opt.value = item;
        opt.textContent = item;
        if (item === mevcutSonuc) opt.selected = true;
        sonucDurumSelect.appendChild(opt);
    });
}

hizmetDurumSelect.addEventListener("change", () => doldur(hizmetDurumSelect.value));
doldur(hizmetDurumSelect.value);
</script>

<?php include "footer.php"; ?>
