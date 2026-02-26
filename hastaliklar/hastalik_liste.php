<?php

$db = new Database();

/* Hastalıkları çek */
$hastaliklar = $db->fetchAll(
    "SELECT id, hastalik_adi, hastalik_grubu FROM hastalik_tanimlari ORDER BY id DESC"
);
?>

<?php 
$head_title="Hastalık Listesi";
include "head.php"; 
?>

<body class="bg-light">
<?php include "navbar.php"; ?>

<div class="container mt-5">
  <div class="card shadow">
    <div class="card-header bg-primary text-white">
      Hastalık Listesi
    </div>
    <div class="card-body p-0">

      <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#hastalikEkleModal">
        + Yeni Hastalık
      </button>

      <table class="table table-bordered table-hover">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Hastalık Adı</th>
            <th>Hastalık Grubu</th>
            <th width="180">İşlemler</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($hastaliklar as $h): ?>
          <tr>
            <td><?= $h["id"] ?></td>
            <td><?= htmlspecialchars($h["hastalik_adi"]) ?></td>
            <td><?= htmlspecialchars($h["hastalik_grubu"]) ?></td>
            <td>
              <button class="btn btn-sm btn-warning hastalikDuzenle"
                      data-id="<?= $h["id"] ?>"
                      data-ad="<?= htmlspecialchars($h["hastalik_adi"]) ?>"
                      data-grup="<?= htmlspecialchars($h["hastalik_grubu"]) ?>">
                Düzenle
              </button>
              <button class="btn btn-sm btn-danger hastalikSil"
                      data-id="<?= $h["id"] ?>">
                Sil
              </button>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>






      <div class="modal fade" id="hastalikEkleModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="hastalikEkleForm">
        <div class="modal-header">
          <h5 class="modal-title">Yeni Hastalık</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input class="form-control mb-2" name="hastalik_adi" placeholder="Hastalık adı" required>
          <input class="form-control" name="hastalik_grubu" placeholder="Hastalık grubu" required>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success">Kaydet</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="hastalikDuzenleModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="hastalikDuzenleForm">
        <input type="hidden" name="id">
        <div class="modal-header">
          <h5 class="modal-title">Hastalık Düzenle</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input class="form-control mb-2" name="hastalik_adi" required>
          <input class="form-control" name="hastalik_grubu" required>
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


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
const ekleModal = new bootstrap.Modal("#hastalikEkleModal");
const duzenleModal = new bootstrap.Modal("#hastalikDuzenleModal");

// EKLE
document.getElementById("hastalikEkleForm").addEventListener("submit", e => {
  e.preventDefault();
  fetch("<?= route('hastalik_crud'); ?>", { method: "POST", body: new FormData(e.target) })
    .then(() => location.reload());
});

// DÜZENLE AÇ
document.querySelectorAll(".hastalikDuzenle").forEach(btn => {
  btn.onclick = () => {
    document.querySelector("#hastalikDuzenleForm [name=id]").value = btn.dataset.id;
    document.querySelector("#hastalikDuzenleForm [name=hastalik_adi]").value = btn.dataset.ad;
    document.querySelector("#hastalikDuzenleForm [name=hastalik_grubu]").value = btn.dataset.grup;
    duzenleModal.show();
  };
});

// GÜNCELLE
document.getElementById("hastalikDuzenleForm").addEventListener("submit", e => {
  e.preventDefault();
  const fd = new FormData(e.target);
  fd.append("action", "update");
  fetch("<?= route('hastalik_crud'); ?>", { method: "POST", body: fd })
    .then(() => location.reload());
});

// SİL
document.querySelectorAll(".hastalikSil").forEach(btn => {
  btn.onclick = () => {
    if (!confirm("Silinsin mi?")) return;
    const fd = new FormData();
    fd.append("action", "delete");
    fd.append("id", btn.dataset.id);
    fetch("<?= route('hastalik_crud'); ?>", { method: "POST", body: fd })
      .then(() => location.reload());
  };
});
</script>



</body>
</html>