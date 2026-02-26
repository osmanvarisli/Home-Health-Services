<?php
require_once "../config/init.php";   // session + dil
require_once "../config/Csrf.php";

$csrfToken = Csrf::generate();
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include "../navbar.php"; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="text-center mb-3">Kullanıcı Oluştur</h4>

                    <?php if (isset($_SESSION["error"])): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($_SESSION["error"]); unset($_SESSION["error"]); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION["success"])): ?>
                        <div class="alert alert-success">
                            <?= htmlspecialchars($_SESSION["success"]); unset($_SESSION["success"]); ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="auth/register_check.php">
                        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

                        <div class="mb-3">
                            <input type="text" name="username" class="form-control" required placeholder="Kullanıcı Adı">
                        </div>

                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" required placeholder="Şifre">
                        </div>

                        <div class="mb-3">
                            <input type="password" name="password_confirm" class="form-control" required placeholder="Şifre (Tekrar)">
                        </div>

                        <button class="btn btn-success w-100">Kayıt Ol</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
