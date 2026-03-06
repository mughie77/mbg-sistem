<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once 'layout.php';

// Handle Delete Report
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_report'])) {
    verify_csrf();
    $stmt = $db->prepare("DELETE FROM reports WHERE id = ?");
    $stmt->execute([$_POST['id']]);
    header("Location: reports.php?deleted=1");
    exit();
}

$query = "SELECT r.*, c.name, c.homeroom_teacher, c.total_students
          FROM reports r
          JOIN classes c ON r.class_id = c.id
          ORDER BY r.report_date DESC, r.created_at DESC";
$reports = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Laporan - MBG Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .table-container { border-radius: 28px; overflow: hidden; box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.04); }
    </style>
</head>
<body class="bg-slate-50 antialiased">

    <?php render_navbar(); ?>
    <?php render_sidebar('reports.php'); ?>

    <div class="p-4 sm:ml-64">
        <div class="p-4 mt-16 lg:p-10">

            <?php if (isset($_GET['deleted']) || isset($_GET['updated'])): ?>
            <div id="alert-success" class="flex items-center p-5 mb-8 text-green-800 rounded-3xl bg-green-50 border border-green-100 shadow-sm animate-fade-in" role="alert">
                <div class="w-10 h-10 bg-green-100 rounded-2xl flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div class="text-sm font-bold uppercase tracking-widest">Laporan berhasil diperbarui!</div>
                <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-xl p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 transition-colors" data-dismiss-target="#alert-success"><svg class="w-3 h-3" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg></button>
            </div>
            <?php endif; ?>

            <div class="mb-10">
                <h1 class="text-3xl lg:text-4xl font-black text-slate-900 tracking-tight leading-none mb-3">Semua Laporan</h1>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-black text-emerald-600 uppercase tracking-[0.2em] bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100">Histori Laporan</span>
                    <span class="text-xs font-bold text-slate-400">Penerimaan MBG</span>
                </div>
            </div>

            <div class="table-container bg-white border border-slate-50">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-[10px] text-slate-400 uppercase tracking-[0.2em] bg-slate-50/50 border-b border-slate-100">
                            <tr>
                                <th class="px-8 py-6 font-black">Tanggal</th>
                                <th class="px-8 py-6 font-black">Kelas</th>
                                <th class="px-8 py-6 font-black">Wali Kelas</th>
                                <th class="px-8 py-6 text-center font-black">Hadir</th>
                                <th class="px-8 py-6 text-center font-black">Total</th>
                                <th class="px-8 py-6 text-right font-black">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php foreach ($reports as $report): ?>
                            <tr class="bg-white hover:bg-slate-50/50 transition-colors group">
                                <td class="px-8 py-6 font-black text-slate-900"><?= date('d M Y', strtotime($report['report_date'])) ?></td>
                                <td class="px-8 py-6 font-black text-slate-900"><?= htmlspecialchars($report['name']) ?></td>
                                <td class="px-8 py-6 font-bold text-slate-600 tracking-tight italic"><?= htmlspecialchars($report['homeroom_teacher']) ?></td>
                                <td class="px-8 py-6 text-center">
                                    <span class="bg-emerald-50 text-emerald-700 text-[10px] font-black px-4 py-2 rounded-xl border border-emerald-100 group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-sm"><?= $report['students_present'] ?></span>
                                </td>
                                <td class="px-8 py-6 text-center font-black text-slate-300"><?= $report['total_students'] ?></td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="edit_report.php?id=<?= $report['id'] ?>" class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        <form method="POST" onsubmit="return confirm('Hapus laporan ini?')" class="inline">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                            <input type="hidden" name="id" value="<?= $report['id'] ?>">
                                            <button type="submit" name="delete_report" class="w-10 h-10 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($reports)): ?>
                            <tr><td colspan="6" class="px-8 py-20 text-center text-slate-300 font-black uppercase tracking-widest text-xs">Belum ada laporan yang disubmit.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
</body>
</html>
