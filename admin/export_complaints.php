<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

$query = "SELECT comp.*, c.name as class_name
          FROM complaints comp
          LEFT JOIN classes c ON comp.class_id = c.id";

$params = [];
if ($start_date && $end_date) {
    $query .= " WHERE DATE(comp.created_at) BETWEEN ? AND ?";
    $params = [$start_date, $end_date];
}

$query .= " ORDER BY comp.created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute($params);
$complaints = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Export Keluhan MBG</title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .footer { text-align: right; margin-top: 50px; }
        @media print {
            .no-print { display: none; }
            body { padding: 20px; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="background: #fdf6e3; padding: 15px; margin-bottom: 20px; border: 1px solid #eee;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; background: #2563eb; color: white; border: none; border-radius: 5px; font-weight: bold;">CETAK / EXPORT PDF</button>
        <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer; background: #64748b; color: white; border: none; border-radius: 5px; font-weight: bold; margin-left: 10px;">TUTUP</button>
    </div>

    <div class="header">
        <h1>DAFTAR KELUHAN LAYANAN MBG</h1>
        <p>SMK NEGERI 2 BONDOWOSO</p>
        <?php if($start_date && $end_date): ?>
            <p>Periode: <?= $start_date ?> s/d <?= $end_date ?></p>
        <?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="15%">Nama / Peran</th>
                <th width="10%">Kelas</th>
                <th>Isi Keluhan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($complaints as $i => $comp): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= date('d/m/Y H:i', strtotime($comp['created_at'])) ?></td>
                <td>
                    <strong><?= htmlspecialchars($comp['name']) ?></strong><br>
                    <small>(<?= htmlspecialchars($comp['role']) ?>)</small>
                </td>
                <td><?= htmlspecialchars($comp['class_name'] ?? '-') ?></td>
                <td><?= nl2br(htmlspecialchars($comp['description'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Bondowoso, <?= date('d F Y') ?></p>
        <br><br><br>
        <p><strong>Admin MBG System</strong></p>
    </div>

    <script>
        // Auto trigger print if wanted
        // window.print();
    </script>
</body>
</html>
