<?php


// ====== SAYFALAMA VE ARAMA ======
$limit = 100; // sayfa başına gösterilecek hasta sayısı
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Arama sorgusu
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$where = "";
$params = [];
$types = "";

if ($search !== "") {
    $where = "WHERE ad_soyad LIKE ? OR tc LIKE ? OR hizmet_durum LIKE ?";
    $params = ["%$search%", "%$search%", "%$search%"];
    $types = "sss";
}

// ====== SIRALAMA ======
$sortableColumns = ['id','ad_soyad', 'tc', 'hizmet_durum', 'hiz_baslama_tarihi'];
$sortColumn = isset($_GET['sort']) && in_array($_GET['sort'], $sortableColumns) ? $_GET['sort'] : 'id';
$sortOrder = isset($_GET['order']) && in_array(strtoupper($_GET['order']), ['ASC','DESC']) ? strtoupper($_GET['order']) : 'DESC';
$nextOrder = $sortOrder === 'ASC' ? 'DESC' : 'ASC'; // tıklayınca tersine dön

// Toplam kayıt sayısı
$totalQuery = "SELECT COUNT(*) AS total FROM hasta_kayit $where";
$total = $db->fetch($totalQuery, $types, $params)['total'];
$totalPages = ceil($total / $limit);

// Hasta kayıtları
$query = "SELECT * FROM hasta_kayit $where ORDER BY $sortColumn $sortOrder LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$types .= "ii";

$hastalar = $db->fetchAll($query, $types, $params);

// ====== HELPER FONKSİYONLAR ======
function badgeClass($durum) {
    return match($durum) {
        'Hasta Kayıt' => 'bg-primary',
        'Hizmet Başlatıldı' => 'bg-info',
        'Hizmet Sonlandırıldı' => 'bg-success',
        'Hizmet Verilmedi' => 'bg-danger',
        'Hizmete Uygun Değil' => 'bg-secondary',
        'Aile Hekimine Sevk' => 'bg-warning text-dark',
        default => 'bg-light text-dark'
    };
}

function hizmetDurumText($durum) {
    return match ($durum) {
        'Hasta Kayıt' => __("status_registered"),
        'Hizmet Başlatıldı' => __("status_started"),
        'Hizmet Sonlandırıldı' => __("status_finished"),
        'Hizmet Verilmedi' => __("status_not_given"),
        'Hizmete Uygun Değil' => __("status_not_eligible"),
        'Aile Hekimine Sevk' => __("status_family_doctor"),
        default => $durum
    };
}

// Sıralama linki oluştur
function sortLink($column, $label, $currentSort, $currentOrder, $search, $page) {
    $nextOrder = ($currentSort === $column && $currentOrder === 'ASC') ? 'DESC' : 'ASC';

    $url = "?search=" . urlencode($search)
         . "&sort=$column"
         . "&order=$nextOrder"
         . "&page=$page";

    $arrow = '';
    if ($currentSort === $column) {
        $arrow = $currentOrder === 'ASC' ? ' ↑' : ' ↓';
    }

    return "<a href='$url' class='text-white text-decoration-none'>$label$arrow</a>";
}

?>

<?php 
$head_title = __("patient_list_title");
include "head.php"; 
?>

<body class="bg-light">
<?php include "navbar.php"; ?>

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
            <h5 class="mb-0"><?= __("patient_list_title") ?></h5>

            <div class="d-flex gap-2">

<form method="GET" class="d-flex gap-2" action="<?= route('hasta_listesi') ?>">
    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control form-control-sm" placeholder="<?= __("search_patient") ?>">
    <button class="btn btn-sm btn-primary"><?= __("search") ?></button>
</form>


                <a href="<?= route('hasta_ekle') ?>" class="btn btn-sm btn-success">
                    + <?= __("new_record") ?>
                </a>

                <a href="<?= route('hasta_listesi_excel') ?>?search=<?= urlencode($search) ?>&sort=<?= $sortColumn ?>&order=<?= $sortOrder ?>" class="btn btn-sm btn-outline-success">
                <i class="bi bi-file-earmark-excel"></i> Excel
                </a>

            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table table-striped table-hover mb-0 align-middle">
                <thead class="table-dark">
                <tr>
<th><?= sortLink('ad_soyad', __("name_surname"), $sortColumn, $sortOrder, $search, $page) ?></th>
<th><?= sortLink('tc', __("identity_no"), $sortColumn, $sortOrder, $search, $page) ?></th>
<th><?= sortLink('hizmet_durum', __("service_status"), $sortColumn, $sortOrder, $search, $page) ?></th>
<th><?= sortLink('hiz_baslama_tarihi', __("service_start_date"), $sortColumn, $sortOrder, $search, $page) ?></th>

                    <th width="180"><?= __("actions") ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if (count($hastalar) === 0): ?>
                    <tr>
                        <td colspan="5" class="text-center"><?= __("no_records_found") ?></td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($hastalar as $h): ?>
                        <tr>
                            <td><?= htmlspecialchars($h["ad_soyad"]) ?></td>
                            <td><?= $h["tc"] ?></td>
                            <td>
                                <span class="badge <?= badgeClass($h['hizmet_durum']) ?>">
                                    <?= hizmetDurumText($h['hizmet_durum']) ?>
                                </span>
                            </td>
                            <td>
                                <?php
                                
                                setlocale(LC_TIME, $lang  === 'en' ? 'en_US.UTF-8' : 'tr_TR.UTF-8');
                                if (!empty($h["hiz_baslama_tarihi"])) {
                                    echo date('d-m-Y', strtotime($h["hiz_baslama_tarihi"]));
                                } else {
                                    echo '<span class="text-muted">Hizmet Başlamadı</span>';
                                }
                                ?>
                            </td>
                            <td class="text-nowrap">
                                <a href="<?= route('hasta_duzenle') . '/' . $h["id"] ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <a href="<?= route('hasta_sil') . '/' . $h['id'] ?>"
                                class="btn btn-sm btn-danger"
                                title="<?= __("delete") ?>"
                                onclick="return confirm('<?= __("delete_confirm") ?>')">
                                    <i class="bi bi-trash"></i>
                                </a>


                                <a href="<?= route('hasta_goruntule') . '/' . $h['id'] ?>" class="btn btn-sm btn-info" title="<?= __("view") ?>">
                                    <i class="bi bi-eye"></i>
                                </a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
            </div>
        </div>

        <!-- Sayfalama -->
        <?php if ($totalPages > 1): ?>
        <div class="card-footer">
            <nav>
                <ul class="pagination mb-0">
                    <?php for ($i=1; $i<=$totalPages; $i++): ?>
                        <li class="page-item <?= $i==$page ? 'active' : '' ?>">
                            <a class="page-link" href="?search=<?= urlencode($search) ?>&sort=<?= $sortColumn ?>&order=<?= $sortOrder ?>&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
        <?php endif; ?>
    </div>
</div>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<?php include "footer.php"; ?>
