<?php
require_once "config/init.php";   // session + dil
require_once "config/Database.php";
$db = new Database();

$hasta_id = (int)($_GET["id"] ?? 0);

/* Hasta */
$hasta = $db->fetch("SELECT * FROM hasta_kayit WHERE id=?", "i", [$hasta_id]);
if (!$hasta) die("Hasta bulunamadı");

/* ================= AJAX İŞLEMLERİ ================= */
header('Content-Type: text/html; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST["ziyaret_ekle"])) {
        $db->execute(
            "INSERT INTO hasta_ziyaretleri (hasta_id, ziyaret_tarihi, ziyaret_tipi, aciklama)
             VALUES (?,?,?,?)",
            "isss",
            [$hasta_id, $_POST["ziyaret_tarihi"], $_POST["ziyaret_tipi"], $_POST["aciklama"]]
        );
        $id = $db->getInsertId();
        $z = $db->fetch("SELECT * FROM hasta_ziyaretleri WHERE id=?", "i", [$id]);
        echo json_encode(['status'=>'success','data'=>$z,'type'=>'ziyaret']); exit;
    }

    if (isset($_POST["hastalik_ekle"])) {
        $db->execute(
            "INSERT IGNORE INTO hasta_hastaliklar (hasta_id, hastalik_id) VALUES (?,?)",
            "ii",
            [$hasta_id, $_POST["hastalik_id"]]
        );
        $h = $db->fetch("SELECT hh.id, ht.hastalik_adi, ht.hastalik_grubu
                         FROM hasta_hastaliklar hh
                         JOIN hastalik_tanimlari ht ON ht.id = hh.hastalik_id
                         WHERE hh.hasta_id=? AND hh.hastalik_id=?",
                        "ii", [$hasta_id, $_POST["hastalik_id"]]);
        echo json_encode(['status'=>'success','data'=>$h,'type'=>'hastalik']); exit;
    }

    if (isset($_POST["cihaz_ekle"])) {
        $db->execute(
            "INSERT IGNORE INTO hasta_ciihazlar (hasta_id, cihaz_id) VALUES (?,?)",
            "ii",
            [$hasta_id, $_POST["cihaz_id"]]
        );
        $c = $db->fetch("SELECT hc.id, ct.cihaz_adi
                         FROM hasta_ciihazlar hc
                         JOIN cihaz_tanimlari ct ON ct.id = hc.cihaz_id
                         WHERE hc.hasta_id=? AND hc.cihaz_id=?",
                        "ii", [$hasta_id, $_POST["cihaz_id"]]);
        echo json_encode(['status'=>'success','data'=>$c,'type'=>'cihaz']); exit;
    }

    if (isset($_POST["sil"])) {
        $tip = $_POST['tip'];
        $id = (int)$_POST['id'];
        if ($tip=="ziyaret") $db->execute("DELETE FROM hasta_ziyaretleri WHERE id=? AND hasta_id=?", "ii", [$id,$hasta_id]);
        if ($tip=="hastalik") $db->execute("DELETE FROM hasta_hastaliklar WHERE id=?", "i", [$id]);
        if ($tip=="cihaz") $db->execute("DELETE FROM hasta_ciihazlar WHERE id=?", "i", [$id]);
        echo json_encode(['status'=>'success']); exit;
    }
}

/* ================= VERİLER ================= */
$ziyaretler = $db->fetchAll(
    "SELECT * FROM hasta_ziyaretleri WHERE hasta_id=? ORDER BY ziyaret_tarihi DESC",
    "i", [$hasta_id]
);

$hastaHastaliklar = $db->fetchAll(
    "SELECT hh.id, ht.hastalik_adi, ht.hastalik_grubu
     FROM hasta_hastaliklar hh
     JOIN hastalik_tanimlari ht ON ht.id = hh.hastalik_id
     WHERE hh.hasta_id=?",
    "i", [$hasta_id]
);

$hastaliklar = $db->fetchAll(
    "SELECT * FROM hastalik_tanimlari ORDER BY hastalik_grubu, hastalik_adi"
);

$hastaCihazlar = $db->fetchAll(
    "SELECT hc.id, ct.cihaz_adi
     FROM hasta_ciihazlar hc
     JOIN cihaz_tanimlari ct ON ct.id = hc.cihaz_id
     WHERE hc.hasta_id=?",
    "i", [$hasta_id]
);

$cihazlar = $db->fetchAll("SELECT * FROM cihaz_tanimlari ORDER BY cihaz_adi");
?>


<?php 
    $head_title="Hasta Detay";
    include "head.php"; 
?>


<body class="bg-light">
  
<?php include "navbar.php"; ?>

<div class="container mt-5">

<!-- ================= HASTA BİLGİLERİ ================= -->
<div class="card shadow mb-4">

<div class="card-header bg-info text-white fw-bold">
  Hasta Bilgileri 
  <a href="<?= route('hasta_duzenle') . '/' . $hasta_id ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> </a>
</div>

<div class="card-body">
<div class="row g-3">
    <div class="col-md-6"><small class="text-muted">Ad Soyad</small><div class="info-box"><?= htmlspecialchars($hasta["ad_soyad"]) ?></div></div>
    <div class="col-md-6"><small class="text-muted">TC</small><div class="info-box"><?= $hasta["tc"] ?></div></div>
    <div class="col-md-4"><small class="text-muted">Cinsiyet</small><div class="info-box"><?= $hasta["cinsiyet"] ?></div></div>
    <div class="col-md-4"><small class="text-muted">Doğum Tarihi</small><div class="info-box"><?= date("d.m.Y", strtotime($hasta["dogum_tarihi"])) ?></div></div>
    <div class="col-md-4"><small class="text-muted">Yatalak</small>
        <div class="info-box <?= $hasta["yatalak"] ? 'bg-danger-subtle' : 'bg-success-subtle' ?>"><?= $hasta["yatalak"] ? "Evet" : "Hayır" ?></div>
    </div>
    <div class="col-md-6"><small class="text-muted">İlçe</small><div class="info-box"><?= $hasta["beyan_ilce"] ?></div></div>
    <div class="col-md-6"><small class="text-muted">Hizmet Durumu</small><div class="info-box bg-secondary-subtle"><?= $hasta["hizmet_durum"] ?></div></div>
</div>
</div>
</div>

<!-- ================= TABLAR ================= -->
<div class="card shadow">
<div class="card-header p-0">
<ul class="nav nav-tabs card-header-tabs" style="margin: 0px;">
<li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#ziyaretler">Ziyaretler</button></li>
<li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#hastaliklar">Hastalıklar</button></li>
<li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#cihazlar">Cihazlar</button></li>
</ul>
</div>

<div class="card-body tab-content">

<!-- ================= ZİYARETLER ================= -->
<div class="tab-pane fade show active" id="ziyaretler">
<button class="btn btn-sm btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#ziyaretModal">+ Ziyaret Ekle</button>
<table class="table table-striped" id="ziyaretTable">
<thead class="table-dark"><tr><th>Tarih</th><th>Tür</th><th>Açıklama</th><th></th></tr></thead>
<tbody>
<?php if(!$ziyaretler) echo '<tr><td colspan="4" class="text-center text-muted">Ziyaret yok</td></tr>'; ?>
<?php foreach ($ziyaretler as $z): ?>
<tr data-id="<?= $z["id"] ?>">
<td><?= date("d.m.Y", strtotime($z["ziyaret_tarihi"])) ?></td>
<td><?= $z["ziyaret_tipi"] ?></td>
<td><?= htmlspecialchars($z["aciklama"]) ?></td>
<td><button class="btn btn-sm btn-danger silBtn" data-tip="ziyaret" data-id="<?= $z["id"] ?>">Sil</button></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

<!-- ================= HASTALIKLAR ================= -->
<div class="tab-pane fade" id="hastaliklar">
<button class="btn btn-sm btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#hastalikModal">+ Hastalık Ekle</button>
<table class="table table-striped" id="hastalikTable">
<thead class="table-dark"><tr><th>Hastalık</th><th>Grup</th><th></th></tr></thead>
<tbody>
<?php if(!$hastaHastaliklar) echo '<tr><td colspan="3" class="text-center text-muted">Hastalık eklenmemiş</td></tr>'; ?>
<?php foreach ($hastaHastaliklar as $h): ?>
<tr data-id="<?= $h["id"] ?>">
<td><?= $h["hastalik_adi"] ?></td>
<td><?= $h["hastalik_grubu"] ?></td>
<td><button class="btn btn-sm btn-danger silBtn" data-tip="hastalik" data-id="<?= $h["id"] ?>">Sil</button></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

<!-- ================= CİHAZLAR ================= -->
<div class="tab-pane fade" id="cihazlar">
<button class="btn btn-sm btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#cihazModal">+ Cihaz Ekle</button>
<table class="table table-striped" id="cihazTable">
<thead class="table-dark"><tr><th>Cihaz</th><th></th></tr></thead>
<tbody>
<?php if(!$hastaCihazlar) echo '<tr><td colspan="2" class="text-center text-muted">Cihaz eklenmemiş</td></tr>'; ?>
<?php foreach ($hastaCihazlar as $c): ?>
<tr data-id="<?= $c["id"] ?>">
<td><?= $c["cihaz_adi"] ?></td>
<td><button class="btn btn-sm btn-danger silBtn" data-tip="cihaz" data-id="<?= $c["id"] ?>">Sil</button></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

</div>
</div>

</div>

<!-- ================= MODALLAR ================= -->

<!-- ZİYARET MODAL -->
<div class="modal fade" id="ziyaretModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form class="ajaxForm">
        <div class="modal-header">
          <h5 class="modal-title">Ziyaret Ekle</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="ziyaret_ekle" value="1">
          <div class="row g-3">
            <div class="col-md-4">
              <label>Ziyaret Tarihi</label>
              <input type="date" name="ziyaret_tarihi" class="form-control" required>
            </div>
            <div class="col-md-4">
              <label>Tür</label>
              <select name="ziyaret_tipi" class="form-select">
                <option value="Randevu">Randevu</option>
                <option value="Ziyaret">Ziyaret</option>
              </select>
            </div>
            <div class="col-12">
              <label>Açıklama</label>
              <textarea name="aciklama" class="form-control" rows="3"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
          <button class="btn btn-success">Kaydet</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- HASTALIK MODAL -->
<div class="modal fade" id="hastalikModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Hastalık Ekle</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" style="max-height:70vh;overflow-y:auto;">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th>Hastalık</th><th>Grup</th><th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($hastaliklar as $h): ?>
              <tr>
                <td><?= $h["hastalik_adi"] ?></td>
                <td><?= $h["hastalik_grubu"] ?></td>
                <td>
                  <form class="ajaxForm">
                    <input type="hidden" name="hastalik_ekle" value="1">
                    <input type="hidden" name="hastalik_id" value="<?= $h["id"] ?>">
                    <button class="btn btn-sm btn-success">Ekle</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- CİHAZ MODAL -->
<div class="modal fade" id="cihazModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cihaz Ekle</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" style="max-height:70vh;overflow-y:auto;">
        <table class="table table-hover">
          <thead class="table-light">
            <tr><th>Cihaz</th><th></th></tr>
          </thead>
          <tbody>
            <?php foreach($cihazlar as $c): ?>
              <tr>
                <td><?= $c["cihaz_adi"] ?></td>
                <td>
                  <form class="ajaxForm">
                    <input type="hidden" name="cihaz_ekle" value="1">
                    <input type="hidden" name="cihaz_id" value="<?= $c["id"] ?>">
                    <button class="btn btn-sm btn-success">Ekle</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


 <?php //include "modallar.php"; // buraya ziyaret/hastalik/cihaz modallarını koyabilirsiniz ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// AJAX form submit
document.querySelectorAll('form.ajaxForm').forEach(form=>{
    form.addEventListener('submit', e=>{
        e.preventDefault();
        const data = new FormData(form);
        //fetch('hasta_goruntule.php?id=<?= $hasta_id ?>',{method:'POST',body:data})
        fetch('<?= route('hasta_goruntule').'/'. $hasta_id ?>',{method:'POST',body:data})
        
        .then(res=>res.json())
        .then(res=>{
            if(res.status==='success'){
                let table;
                if(res.type==='ziyaret') table=document.querySelector('#ziyaretTable tbody');
                if(res.type==='hastalik') table=document.querySelector('#hastalikTable tbody');
                if(res.type==='cihaz') table=document.querySelector('#cihazTable tbody');
                const tr=document.createElement('tr');
                tr.dataset.id=res.data.id;
                if(res.type==='ziyaret'){
                    const tarih = new Date(res.data.ziyaret_tarihi).toLocaleDateString('tr-TR');
                    tr.innerHTML=`<td>${tarih}</td><td>${res.data.ziyaret_tipi}</td><td>${res.data.aciklama}</td>
                    <td><button class="btn btn-sm btn-danger silBtn" data-tip="ziyaret" data-id="${res.data.id}">Sil</button></td>`;
                }
                if(res.type==='hastalik'){
                    tr.innerHTML=`<td>${res.data.hastalik_adi}</td><td>${res.data.hastalik_grubu}</td>
                    <td><button class="btn btn-sm btn-danger silBtn" data-tip="hastalik" data-id="${res.data.id}">Sil</button></td>`;
                }
                if(res.type==='cihaz'){
                    tr.innerHTML=`<td>${res.data.cihaz_adi}</td>
                    <td><button class="btn btn-sm btn-danger silBtn" data-tip="cihaz" data-id="${res.data.id}">Sil</button></td>`;
                }
                table.appendChild(tr);

                 // ❗ SADECE ZİYARET MODAL KAPANSIN
                if(res.type==='ziyaret'){
                    const modalElement = document.getElementById('ziyaretModal');
                    const modal = bootstrap.Modal.getInstance(modalElement)
                              || new bootstrap.Modal(modalElement);
                    modal.hide();
                }
            }
        });
    });
});

// Silme işlemi AJAX
document.addEventListener('click', e=>{
    if(e.target.classList.contains('silBtn')){
        const id=e.target.dataset.id;
        const tip=e.target.dataset.tip;
        if(confirm("Silinsin mi?")){
            fetch('<?= route('hasta_goruntule').'/'. $hasta_id ?>',{
                method:'POST',
                body:new URLSearchParams({sil:1,id:id,tip:tip})
            }).then(res=>res.json()).then(res=>{
                if(res.status==='success') e.target.closest('tr').remove();
            });
        }
    }
});
</script>
</body>
</html>
