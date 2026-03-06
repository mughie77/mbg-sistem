<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

// Handle Delete Report
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_report'])) {
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
    <title>Laporan - MBG Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .sidebar-item-active { background-color: #eff6ff; color: #1d4ed8; border-right: 4px solid #1d4ed8; }
        .table-container { border-radius: 24px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); }
    </style>
</head>
<body class="bg-slate-50">

    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-100 shadow-sm">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path></svg>
                    </button>
                    <a href="#" class="flex ms-2 md:me-24">
                        <span class="self-center text-xl font-black sm:text-2xl text-blue-600 uppercase tracking-tighter">MBG PANEL</span>
                    </a>
                </div>
                <div class="flex items-center">
                    <a href="index.php?logout=1" class="text-sm font-semibold text-red-600 hover:text-red-700 flex items-center bg-red-50 px-4 py-2 rounded-xl transition-all">LOGOUT</a>
                </div>
            </div>
        </div>
    </nav>

    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-100 sm:translate-x-0 shadow-sm">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
            <ul class="space-y-2 font-medium">
                <li><a href="index.php" class="flex items-center p-3 text-gray-600 rounded-xl hover:bg-gray-50 font-semibold">Daftar Kelas</a></li>
                <li><a href="reports.php" class="flex items-center p-3 text-gray-900 rounded-xl hover:bg-gray-50 sidebar-item-active font-bold">Laporan</a></li>
                <li><a href="../index.php" target="_blank" class="flex items-center p-3 text-gray-600 rounded-xl hover:bg-gray-50 font-semibold">Lihat Beranda</a></li>
            </ul>
        </div>
    </aside>

    <div class="p-4 sm:ml-64">
        <div class="p-4 mt-14">

            <?php if (isset($_GET['deleted']) || isset($_GET['updated'])): ?>
            <div id="alert-success" class="flex items-center p-4 mb-6 text-green-800 rounded-2xl bg-green-50 border border-green-100" role="alert">
                <div class="ms-3 text-sm font-bold">Laporan berhasil diperbarui!</div>
                <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#alert-success"><svg class="w-3 h-3" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg></button>
            </div>
            <?php endif; ?>

            <div class="mb-8">
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Semua Laporan</h1>
                <p class="text-sm text-gray-500 font-medium uppercase tracking-widest mt-1">Histori Laporan MBG</p>
            </div>

            <div class="table-container bg-white">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-50/50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-5 font-bold">Tanggal</th>
                            <th class="px-6 py-5 font-bold">Kelas</th>
                            <th class="px-6 py-5 font-bold">Wali Kelas</th>
                            <th class="px-6 py-5 text-center font-bold">Hadir</th>
                            <th class="px-6 py-5 text-center font-bold">Total</th>
                            <th class="px-6 py-5 font-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php foreach ($reports as $report): ?>
                        <tr class="bg-white hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-5 font-bold text-gray-900"><?= date('d/m/Y', strtotime($report['report_date'])) ?></td>
                            <td class="px-6 py-5 font-black text-gray-900"><?= htmlspecialchars($report['name']) ?></td>
                            <td class="px-6 py-5 font-medium text-gray-600"><?= htmlspecialchars($report['homeroom_teacher']) ?></td>
                            <td class="px-6 py-5 text-center">
                                <span class="bg-emerald-50 text-emerald-700 text-xs font-black px-3 py-1.5 rounded-full border border-emerald-100"><?= $report['students_present'] ?></span>
                            </td>
                            <td class="px-6 py-5 text-center font-bold text-gray-400"><?= $report['total_students'] ?></td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <a href="edit_report.php?id=<?= $report['id'] ?>" class="text-blue-600 hover:text-blue-700 font-bold">Edit</a>
                                    <form method="POST" onsubmit="return confirm('Hapus laporan ini?')" class="inline">
                                        <input type="hidden" name="id" value="<?= $report['id'] ?>">
                                        <button type="submit" name="delete_report" class="text-red-500 hover:text-red-600 font-bold">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($reports)): ?>
                        <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400 italic font-medium">Belum ada laporan yang disubmit.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
</body>
</html>
