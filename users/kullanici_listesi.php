<?php

require_once "config/Csrf.php";

$db = new Database();
$csrfToken = Csrf::generate();

$users = $db->fetchAll("SELECT id, username, created_at FROM users ORDER BY id DESC");
?>
<!doctype html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Kullanıcı Yönetimi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include "navbar.php"; ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header fw-bold">Kullanıcı Listesi</div>
        <div class="card-body">

            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kullaniciEkleModal">
                    + Kullanıcı Ekle
                </button>
            </div>


            <table class="table table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Kullanıcı Adı</th>
                        <th>Kayıt Tarihi</th>
                        <th width="220"></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($users as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['username']) ?></td>
                        <td><?= date('d.m.Y', strtotime($u['created_at'])) ?></td>
                        <td>

                            <!-- ŞİFRE DEĞİŞTİR -->
                            <button
                                class="btn btn-warning btn-sm sifreModalBtn"
                                data-id="<?= $u['id'] ?>"
                                data-username="<?= htmlspecialchars($u['username']) ?>">
                                Şifre Değiştir
                            </button>

                            <!-- SİL -->
                            <form method="post" action="<?= route('kullanici_sil'); ?>" class="d-inline">
                                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Kullanıcı silinsin mi?')">
                                    Sil
                                </button>
                            </form>

                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<!-- ================= ŞİFRE DEĞİŞTİR MODAL ================= -->
<div class="modal fade" id="sifreModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="post" action="<?= route('sifre_degistir'); ?>">
        <div class="modal-header">
          <h5 class="modal-title">Şifre Değiştir</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
          <input type="hidden" name="action" value="change_password">
          <input type="hidden" name="user_id" id="modal_user_id">

          <div class="mb-2">
            <label class="form-label">Kullanıcı</label>
            <input type="text" id="modal_username" class="form-control" disabled>
          </div>

          <div class="mb-2">
            <label class="form-label">Yeni Şifre</label>
            <input type="password" name="new_password" class="form-control" required minlength="6">
          </div>

          <div class="mb-2">
            <label class="form-label">Yeni Şifre (Tekrar)</label>
            <input type="password" name="new_password_confirm" class="form-control" required minlength="6">
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
          <button class="btn btn-success">Kaydet</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ================= KULLANICI EKLE MODAL ================= -->
<div class="modal fade" id="kullaniciEkleModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="post" action="<?= route('kullanici_ekle'); ?>">
        <div class="modal-header">
          <h5 class="modal-title">Yeni Kullanıcı Ekle</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
          <input type="hidden" name="action" value="add_user">

          <div class="mb-2">
            <label class="form-label">Kullanıcı Adı</label>
            <input type="text" name="username" class="form-control" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Şifre</label>
            <input type="password" name="password" class="form-control" required minlength="6">
          </div>

          <div class="mb-2">
            <label class="form-label">Şifre (Tekrar)</label>
            <input type="password" name="password_confirm" class="form-control" required minlength="6">
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
          <button class="btn btn-success">Ekle</button>
        </div>
      </form>
    </div>
  </div>
</div>


<script>
document.querySelectorAll('.sifreModalBtn').forEach(btn=>{
    btn.addEventListener('click', ()=>{
        document.getElementById('modal_user_id').value = btn.dataset.id;
        document.getElementById('modal_username').value = btn.dataset.username;

        const modal = new bootstrap.Modal(document.getElementById('sifreModal'));
        modal.show();
    });
});
</script>

<?php include "footer.php"; ?>