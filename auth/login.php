<?php
require_once "captcha.php";

/* =========================
   DİL SEÇİMİ
========================= */
$supportedLangs = ["tr", "en"];
$lang = $_GET["lang"] ?? $_SESSION["lang"] ?? "tr";
if (!in_array($lang, $supportedLangs)) $lang = "tr";
$_SESSION["lang"] = $lang;
$text = require "lang/{$lang}.php";

if (isset($_SESSION["user_id"])) {
    header("Location: home");
    exit;
}
?>
<!doctype html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $text["login_title"] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .card {
            border-radius: 1rem;
            overflow: hidden;
        }
        .card-body {
            padding: 2rem;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #2575fc;
        }
        .captcha-box {
            background: #f8f9fa;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            text-align: center;
        }
        .btn-primary {
            background-color: #2575fc;
            border-color: #2575fc;
        }
        .btn-primary:hover {
            background-color: #1b5ed1;
            border-color: #1b5ed1;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-lg">
            <div class="card-body">
                <!-- Dil Seçimi -->
                <div class="mb-3 text-end">
                    <form method="get">
                        <select name="lang" onchange="this.form.submit()" class="form-select form-select-sm w-auto d-inline">
                            <option value="tr" <?= $lang === "tr" ? "selected" : "" ?>>Türkçe</option>
                            <option value="en" <?= $lang === "en" ? "selected" : "" ?>>English</option>
                        </select>
                    </form>
                </div>

                <div class="text-center mb-4">
                    <i class="bi bi-person-circle fs-1 text-primary"></i>
                    <h4 class="mt-2"><?= $text["login_heading"] ?></h4>
                </div>

                <?php if (isset($_SESSION["error"])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_SESSION["error"]); unset($_SESSION["error"]); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= route('login_check') ?>">

                    <?php
                    require_once "config/Csrf.php";
                    $csrfToken = Csrf::generate();
                    ?>
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-person"></i> <?= $text["username"] ?></label>
                        <input type="text" name="username" class="form-control" required placeholder="<?= $text["username"] ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-lock"></i> <?= $text["password"] ?></label>
                        <input type="password" name="password" class="form-control" required placeholder="<?= $text["password"] ?>">
                    </div>

<div class="mb-3">
    <label class="form-label"><i class="bi bi-shield-lock"></i> <?= $text["captcha"] ?></label>

    <!-- Soru -->
    <div class="captcha-box mb-2">
        <?= $_SESSION["captcha_question"] ?? $text["captcha_placeholder"] ?>
    </div>

    <!-- Şıklar yan yana gösteriliyor -->
    <div class="mb-2">
        <?php foreach ($_SESSION["captcha_options"] ?? [] as $option): ?>
            <span class="badge bg-secondary me-2"><?= htmlspecialchars($option) ?></span>
        <?php endforeach; ?>
    </div>

    <!-- Kullanıcı cevabı yazacak -->
    <input type="text" name="captcha" class="form-control" required placeholder="<?= $text["captcha_placeholder"] ?>">
</div>




                    <button class="btn btn-primary w-100 mt-2">
                        <i class="bi bi-box-arrow-in-right"></i> <?= $text["login_button"] ?>
                    </button>
                </form>
            </div>
            <div class="card-footer text-center text-muted small">
                <?= $text["footer"] ?> <?= date("Y") ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
