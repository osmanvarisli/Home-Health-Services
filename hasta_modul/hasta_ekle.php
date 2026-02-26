<?php

$db = new Database();

if ($_POST) {
    $db->execute(
        "INSERT INTO hasta_kayit 
        (ad_soyad, tc, cinsiyet, dogum_tarihi, basvuru_tarihi,hiz_baslama_tarihi,hiz_son_tarihi,olum_tarihi,cep_tel, yatalak, beyan_ilce, adres, hizmet_durum, sonuc_durum)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
        "ssssssssisssss",
        [
            $_POST["ad_soyad"],
            $_POST["tc"],
            $_POST["cinsiyet"],
            $_POST["dogum_tarihi"],
            $_POST["basvuru_tarihi"],
            $_POST["hiz_baslama_tarihi"],
            $_POST["hiz_son_tarihi"],
            $_POST["olum_tarihi"],
            $_POST["cep_tel"],
            isset($_POST["yatalak"]) ? 1 : 0,
            $_POST["beyan_ilce"],
            $_POST["adres"],
            $_POST["hizmet_durum"],
            $_POST["sonuc_durum"]
        ]
    );

    // Router uyumlu redirect
    header("Location: " . route('hasta_listesi'));
    exit;
}

$nextIdRow = $db->fetch("
    SELECT AUTO_INCREMENT
    FROM information_schema.TABLES
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'hasta_kayit'
");
$muhtemelId = $nextIdRow['AUTO_INCREMENT'] ?? null;
$ilceler = require "config/ilceler.php";
$head_title = __("add_patient");
include "head.php"; 
?>

<body class="bg-light">

<?php include "navbar.php"; ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <?= __("patient_registration_form") ?>
        </div>
        <div class="card-body">
            <form method="post" class="row g-3">

                <!-- Ad Soyad -->
                <div class="col-md-6">
                    <label class="form-label"><?= __("name_surname") ?></label>
                    <input class="form-control" name="ad_soyad" required>
                </div>

                <!-- TC -->
                <div class="col-md-6">
                    <label class="form-label">TC</label>
                    <input class="form-control" name="tc" maxlength="11" required>
                </div>

                <!-- ID -->
                <div class="col-md-4">
                    <label class="form-label"><?= __("service_no") ?></label>
                    <input class="form-control fw-bold text-primary" value="<?= $muhtemelId ?>" readonly>
                </div>

                <!-- Cinsiyet -->
                <div class="col-md-4">
                    <label class="form-label"><?= __("gender") ?></label>
                    <select name="cinsiyet" class="form-select">
                        <option value="E"><?= __("male") ?></option>
                        <option value="K"><?= __("female") ?></option>
                    </select>
                </div>

                <!-- Doğum Tarihi -->
                <div class="col-md-4">
                    <label class="form-label"><?= __("birth_date") ?></label>
                    <input type="date" class="form-control" name="dogum_tarihi">
                </div>

                <!-- Tarihler -->
                <div class="col-md-3">
                    <label class="form-label"><?= __("application_date") ?></label>
                    <input type="date" class="form-control" name="basvuru_tarihi">
                </div>
                <div class="col-md-3">
                    <label class="form-label"><?= __("service_start_date") ?></label>
                    <input type="date" class="form-control" name="hiz_baslama_tarihi">
                </div>
                <div class="col-md-3">
                    <label class="form-label"><?= __("service_end_date") ?></label>
                    <input type="date" class="form-control" name="hiz_son_tarihi">
                </div>
                <div class="col-md-3">
                    <label class="form-label"><?= __("death_date") ?></label>
                    <input type="date" class="form-control" name="olum_tarihi">
                </div>

                <!-- Telefon ve Yatalak -->
                <div class="col-md-6">
                    <label class="form-label"><?= __("mobile_phone") ?></label>
                    <input class="form-control" name="cep_tel">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="yatalak">
                        <label class="form-check-label"><?= __("bedridden") ?></label>
                    </div>
                </div>

                <!-- İlçe -->
                <div class="col-md-6">
                    <label class="form-label"><?= __("declared_district") ?></label>
                    <select name="beyan_ilce" class="form-select" required>
                        <option value=""><?= __("select") ?></option>
                        <?php foreach ($ilceler as $ilce): ?>
                            <option value="<?= $ilce ?>"><?= $ilce ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Hizmet Durum -->
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
                    <select name="hizmet_durum" class="form-select">
                        <?php foreach ($durumlar as $key): ?>
                            <option value="<?= __($key) ?>"><?= __($key) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Sonuç Durum -->
                <div class="col-md-6">
                    <label class="form-label"><?= __("result_status") ?></label>
                    <select name="sonuc_durum" id="sonuc_durum" class="form-select" required>
                        <option value=""><?= __("select_service_status_first") ?></option>
                    </select>
                </div>

                <!-- Adres -->
                <div class="col-12">
                    <label class="form-label"><?= __("address") ?></label>
                    <textarea class="form-control" name="adres" rows="3"></textarea>
                </div>

                <!-- Butonlar -->
                <div class="col-12 text-end">
                    <button class="btn btn-success px-4"><?= __("save") ?></button>
                    <a href="<?= route('hasta_listesi') ?>" class="btn btn-secondary"><?= __("back_to_list") ?></a>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
const hizmetDurumSelect = document.querySelector('[name="hizmet_durum"]');
const sonucDurumSelect  = document.getElementById('sonuc_durum');

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
    "Hizmet Verilmedi": ["Tedaviyi Red Etme","İkamet Değişikliği","Vefat","Diğer ESH Birimine Devredilen","Hatalı Kayıt"],
    "Hizmete Uygun Değil": ["Hasta Kayıt"],
    "Aile Hekimine Sevk": ["Hasta Kayıt"]
};

hizmetDurumSelect.addEventListener("change", function () {
    const selected = this.value;
    sonucDurumSelect.innerHTML = "";

    if (!sonucMap[selected]) {
        sonucDurumSelect.innerHTML = `<option value="">Seçim Yok</option>`;
        return;
    }

    sonucMap[selected].forEach(item => {
        const opt = document.createElement("option");
        opt.value = item;
        opt.textContent = item;
        sonucDurumSelect.appendChild(opt);
    });
});
</script>

<?php include "footer.php"; ?>
