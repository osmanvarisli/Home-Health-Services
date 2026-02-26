<?php
require_once "config/dompdf2/autoload.inc.php";

use Dompdf\Dompdf;
use Dompdf\Options;

$db = new Database();

/* === GET parametreleri === */
$start = $_GET['start'] ?? '';
$end   = $_GET['end'] ?? '';

include "istatistik_SQL_sorgular.php"; 

/* ================= HTML ================= */
ob_start();
?>
<!doctype html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<style>
body { font-family: DejaVu Sans, sans-serif; font-size:12px; }
table { width:100%; border-collapse: collapse; margin-bottom:20px; }
th, td { border:1px solid #000; padding:5px; }
th { background:#eee; }
</style>
</head>
<body>

<h2 style="text-align:center;">İstatistik Raporu</h2>

<?php include "istatistik_kartlar.php"; ?>

</body>
</html>
<?php
$html = ob_get_clean();

/* ================= PDF ================= */
$options = new Options();
$options->set('defaultFont', 'DejaVu Sans');
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4');
$dompdf->render();
$dompdf->stream("istatistik_raporu.pdf", ["Attachment" => true]);
exit;
