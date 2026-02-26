<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title><?= isset($head_title) ? htmlspecialchars($head_title) : "Varsayılan Başlık" ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .info-box {background:#f8f9fa; border:1px solid #dee2e6; border-radius:6px; padding:8px 10px; font-weight:600; margin-top:4px;}
    </style>

        <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .btn-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            width: 100%;
            max-width: 600px;
        }
        .btn-grid .btn {
            padding: 20px;
            font-size: 1.1rem;
        }
    </style>
</head>


