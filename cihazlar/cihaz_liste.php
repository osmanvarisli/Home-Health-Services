<?php

$db = new Database();
$cihazlar = $db->fetchAll("SELECT id, cihaz_adi FROM cihaz_tanimlari ORDER BY id DESC");
?>

<?php 
    $head_title="Cihaz Listesi";
    include "head.php"; 
?>

<body class="bg-light">

<?php include "navbar.php"; ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            Cihaz Lsitesi
        </div>

        <!-- Tablo -->
        <div class="card-body p-0">

            <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                <th>#</th>
                <th>Cihaz Adı</th>
                <th width="180">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cihazlar as $c): ?>
                <tr>
                <td><?= $c["id"] ?></td>
                <td><?= htmlspecialchars($c["cihaz_adi"]) ?></td>
                <td>
                    <button 
                    class="btn btn-sm btn-warning cihazDuzenle"
                    data-id="<?= $c["id"] ?>"
                    data-ad="<?= htmlspecialchars($c["cihaz_adi"]) ?>">
                    Düzenle
                    </button>
                    <button 
                    class="btn btn-sm btn-danger cihazSil"
                    data-id="<?= $c["id"] ?>">
                    Sil
                    </button>
                </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            </table>

            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#cihazEkleModal">
            + Yeni Cihaz
            </button>

<div class="modal fade" id="cihazEkleModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="cihazEkleForm">
        <div class="modal-header">
          <h5 class="modal-title">Yeni Cihaz</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input class="form-control" name="cihaz_adi" placeholder="Cihaz adı" required>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success">Kaydet</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="cihazDuzenleModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="cihazDuzenleForm">
        <input type="hidden" name="id">
        <div class="modal-header">
          <h5 class="modal-title">Cihaz Düzenle</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input class="form-control" name="cihaz_adi" required>
        </div>
        <div class="modal-footer">
          <button class="btn btn-warning">Güncelle</button>
        </div>
      </form>
    </div>
  </div>
</div>




        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const ekleModal = new bootstrap.Modal("#cihazEkleModal");
const duzenleModal = new bootstrap.Modal("#cihazDuzenleModal");

// EKLE
document.getElementById("cihazEkleForm").addEventListener("submit", e => {
  e.preventDefault();
  fetch("<?= route('cihaz_crud'); ?>", {
    method: "POST",
    body: new FormData(e.target)
  }).then(() => location.reload());
});

// DÜZENLE AÇ
document.querySelectorAll(".cihazDuzenle").forEach(btn => {
  btn.onclick = () => {
    document.querySelector("#cihazDuzenleForm [name=id]").value = btn.dataset.id;
    document.querySelector("#cihazDuzenleForm [name=cihaz_adi]").value = btn.dataset.ad;
    duzenleModal.show();
  };
});

// GÜNCELLE
document.getElementById("cihazDuzenleForm").addEventListener("submit", e => {
  e.preventDefault();
  const fd = new FormData(e.target);
  fd.append("action", "update");
  fetch("<?= route('cihaz_crud'); ?>", { method:"POST", body:fd })
    .then(() => location.reload());
});

// SİL
document.querySelectorAll(".cihazSil").forEach(btn => {
  btn.onclick = () => {
    if (!confirm("Silinsin mi?")) return;
    const fd = new FormData();
    fd.append("action", "delete");
    fd.append("id", btn.dataset.id);
    fetch("<?= route('cihaz_crud'); ?>", { method:"POST", body:fd })
      .then(() => location.reload());
  };
});
</script>



</body>
</html>