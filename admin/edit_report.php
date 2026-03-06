<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once 'layout.php';

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
    verify_csrf();
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .btn-gradient { background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%); transition: all 0.3s; }
        .btn-gradient:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3); }
    </style>
</head>
<body class="bg-slate-50 antialiased">

    <?php render_navbar(); ?>
    <?php render_sidebar('reports.php'); ?>

    <div class="p-4 sm:ml-64">
        <div class="p-4 mt-16 lg:p-10">

            <div class="mb-10 flex items-center gap-4">
                <a href="reports.php" class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-slate-400 hover:text-blue-600 hover:shadow-lg transition-all border border-slate-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none mb-2">Edit Laporan</h1>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Update jumlah kehadiran siswa</p>
                </div>
            </div>

            <div class="max-w-2xl bg-white rounded-[40px] shadow-sm shadow-slate-200/50 p-10 border border-slate-100">
                <div class="mb-10 flex justify-between items-end">
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 tracking-tight"><?= htmlspecialchars($report['name']) ?></h2>
                        <span class="text-xs font-black text-blue-600 uppercase tracking-widest bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100 mt-2 inline-block">ID Laporan: #<?= $report['id'] ?></span>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest leading-none mb-1">Tanggal Laporan</p>
                        <p class="text-sm font-bold text-slate-600"><?= date('d F Y', strtotime($report['report_date'])) ?></p>
                    </div>
                </div>

                <?php if (isset($error)): ?>
                <div class="flex items-center p-5 mb-8 text-rose-800 rounded-3xl bg-rose-50 border border-rose-100 shadow-sm animate-shake" role="alert">
                    <svg class="w-5 h-5 text-rose-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <div class="text-xs font-black uppercase tracking-widest"><?= $error ?></div>
                </div>
                <?php endif; ?>

                <form method="POST" class="space-y-10">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <div>
                        <label class="block mb-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Jumlah Siswa Hadir Penerima MBG</label>
                        <div class="relative group">
                            <input type="number" name="students_present" value="<?= $report['students_present'] ?>" required min="0" max="<?= $report['total_students'] ?>" class="bg-slate-50 border-4 border-slate-50 text-slate-900 text-6xl font-black rounded-[32px] focus:ring-8 focus:ring-blue-100 focus:border-blue-500 block w-full p-10 text-center transition-all outline-none shadow-inner group-hover:bg-white">
                            <div class="absolute right-10 top-1/2 -translate-y-1/2 text-slate-200 font-black text-2xl tracking-tighter">/ <?= $report['total_students'] ?></div>
                        </div>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="submit" name="update_report" class="btn-gradient flex-1 text-white font-black rounded-2xl text-[10px] px-8 py-5 tracking-widest uppercase transition-all shadow-lg shadow-blue-100">
                            Simpan Perubahan
                        </button>
                        <a href="reports.php" class="flex-1 bg-slate-100 text-slate-500 font-black rounded-2xl text-[10px] px-8 py-5 tracking-widest uppercase transition-all text-center">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
</body>
</html>
