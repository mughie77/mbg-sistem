<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: reports.php');
    exit;
}

$stmt = $db->prepare("SELECT r.*, c.name, c.total_students FROM reports r JOIN classes c ON r.class_id = c.id WHERE r.id = ?");
$stmt->execute([$id]);
$report = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$report) {
    header('Location: reports.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_report'])) {
    $present = (int)$_POST['students_present'];
    if ($present >= 0 && $present <= $report['total_students']) {
        $stmt = $db->prepare("UPDATE reports SET students_present = ? WHERE id = ?");
        $stmt->execute([$present, $id]);
        header('Location: reports.php?updated=1');
        exit;
    } else {
        $error = "Jumlah siswa tidak valid (0 - {$report['total_students']})";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Laporan - MBG Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    </style>
</head>
<body class="bg-gray-50">

    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <a href="reports.php" class="flex items-center text-xl font-bold tracking-tight text-gray-900 uppercase">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Edit Laporan
                </a>
            </div>
        </div>
    </nav>

    <div class="max-w-2xl mx-auto mt-24 px-4">
        <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
            <div class="mb-6">
                <h2 class="text-2xl font-black text-gray-900"><?= htmlspecialchars($report['name']) ?></h2>
                <p class="text-sm text-gray-400 font-bold uppercase tracking-widest"><?= date('d F Y', strtotime($report['report_date'])) ?></p>
            </div>

            <?php if (isset($error)): ?>
            <div class="p-4 mb-6 text-sm text-red-700 bg-red-50 rounded-xl border border-red-100 font-bold"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div>
                    <label class="block mb-2 text-sm font-bold text-gray-700 uppercase tracking-widest">Jumlah Siswa Hadir Penerima MBG</label>
                    <div class="relative">
                        <input type="number" name="students_present" value="<?= $report['students_present'] ?>" required min="0" max="<?= $report['total_students'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-2xl font-black rounded-2xl focus:ring-blue-500 focus:border-blue-500 block w-full p-5 transition-all">
                        <div class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-300 font-bold">/ <?= $report['total_students'] ?> Siswa</div>
                    </div>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="submit" name="update_report" class="flex-1 text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-bold rounded-2xl text-sm px-5 py-5 text-center transition-all transform hover:scale-[1.01] active:scale-[0.99] uppercase tracking-widest shadow-lg shadow-blue-200">
                        Simpan Perubahan
                    </button>
                    <a href="reports.php" class="flex-1 text-gray-700 bg-gray-100 hover:bg-gray-200 font-bold rounded-2xl text-sm px-5 py-5 text-center transition-all uppercase tracking-widest">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
