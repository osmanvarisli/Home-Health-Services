<!-- navbar.php -->
<?php
$username = getUsername();

?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <!-- Logo / Marka -->
        <a class="navbar-brand fw-bold" href="<?= route('home') ?>">
            <i class="bi bi-house-heart"></i> <?= __("brand") ?>
        </a>

        <!-- Mobil toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menü -->
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?= route('home') ?>">
                        <i class="bi bi-speedometer2"></i> <?= __("home") ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= route('hasta_listesi') ?>">
                        <i class="bi bi-people"></i> <?= __("patient_list") ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= route('istatistikler') ?>">
                        <i class="bi bi-file-earmark-text"></i> <?= __("statistics") ?>
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center">

                <!-- Dil Seçimi -->
                <li class="nav-item dropdown me-3">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-translate"></i> <?= __("language") ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="?lang=tr">🇹🇷 Türkçe</a></li>
                        <li><a class="dropdown-item" href="?lang=en">🇬🇧 English</a></li>
                    </ul>
                </li>

                <!-- Kullanıcı Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($username) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= route('kullanici_listesi') ?>"><i class="bi bi-person-plus"></i> <?= __("add_user") ?></a></li>
                        <li><a class="dropdown-item" href="<?= route('cihaz_liste') ?>"><i class="bi bi-cpu"></i> <?= __("devices") ?></a></li>
                        <li><a class="dropdown-item" href="<?= route('hastalik_liste') ?>"><i class="bi bi-heart-pulse"></i> <?= __("diseases") ?></a></li>
                        <li><a class="dropdown-item" href="<?= route('db_yedekle') ?>"><i class="bi bi-database-down"></i> <?= __("db_backup") ?></a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= route('logout') ?>"><i class="bi bi-box-arrow-right"></i> <?= __("logout") ?></a></li>
                    </ul>
                </li>
            </ul>

        </div>
    </div>
</nav>

<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
