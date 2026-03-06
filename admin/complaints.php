<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

// Handle Delete Complaint
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_complaint'])) {
    verify_csrf();
    $stmt = $db->prepare("SELECT image_path FROM complaints WHERE id = ?");
    $stmt->execute([$_POST['id']]);
    $complaint = $stmt->fetch();
    if ($complaint && $complaint['image_path'] && file_exists('../' . $complaint['image_path'])) {
        unlink('../' . $complaint['image_path']);
    }

    $stmt = $db->prepare("DELETE FROM complaints WHERE id = ?");
    $stmt->execute([$_POST['id']]);
    header("Location: complaints.php?deleted=1");
    exit();
}

$query = "SELECT comp.*, c.name as class_name
          FROM complaints comp
          LEFT JOIN classes c ON comp.class_id = c.id
          ORDER BY comp.created_at DESC";
$complaints = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keluhan - MBG Admin</title>
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
                <li><a href="reports.php" class="flex items-center p-3 text-gray-600 rounded-xl hover:bg-gray-50 font-semibold">Laporan</a></li>
                <li><a href="complaints.php" class="flex items-center p-3 text-gray-900 rounded-xl hover:bg-gray-50 sidebar-item-active font-bold">Keluhan</a></li>
                <li><a href="../index.php" target="_blank" class="flex items-center p-3 text-gray-600 rounded-xl hover:bg-gray-50 font-semibold">Lihat Beranda</a></li>
            </ul>
        </div>
    </aside>

    <div class="p-4 sm:ml-64">
        <div class="p-4 mt-14">

            <?php if (isset($_GET['deleted'])): ?>
            <div id="alert-success" class="flex items-center p-4 mb-6 text-green-800 rounded-2xl bg-green-50 border border-green-100" role="alert">
                <div class="ms-3 text-sm font-bold">Keluhan berhasil dihapus!</div>
                <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#alert-success"><svg class="w-3 h-3" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg></button>
            </div>
            <?php endif; ?>

            <div class="mb-8">
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Keluhan & Masukan</h1>
                <p class="text-sm text-gray-500 font-medium uppercase tracking-widest mt-1">Daftar Aspirasi Civitas SMKN 2 Bondowoso</p>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <?php foreach ($complaints as $complaint): ?>
                <div class="bg-white p-6 rounded-[24px] shadow-sm border border-gray-100 flex flex-col md:flex-row gap-6">
                    <?php if ($complaint['image_path']): ?>
                    <div class="w-full md:w-48 h-48 flex-shrink-0">
                        <img src="../<?= $complaint['image_path'] ?>" alt="Attachment" class="w-full h-full object-cover rounded-2xl border border-gray-100">
                    </div>
                    <?php endif; ?>
                    <div class="flex-grow">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="bg-blue-50 text-blue-700 text-[9px] font-black px-2.5 py-1 rounded-lg uppercase tracking-widest border border-blue-100"><?= $complaint['role'] ?></span>
                                <h3 class="text-lg font-black text-slate-900 mt-2"><?= htmlspecialchars($complaint['name']) ?></h3>
                                <?php if ($complaint['class_name']): ?>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Kelas: <?= htmlspecialchars($complaint['class_name']) ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest"><?= date('d M Y H:i', strtotime($complaint['created_at'])) ?></span>
                            </div>
                        </div>
                        <p class="text-sm text-slate-600 leading-relaxed bg-slate-50/50 p-4 rounded-xl italic">
                            "<?= nl2br(htmlspecialchars($complaint['description'])) ?>"
                        </p>
                        <div class="mt-4 flex justify-end">
                            <form method="POST" onsubmit="return confirm('Hapus keluhan ini?')" class="inline">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <input type="hidden" name="id" value="<?= $complaint['id'] ?>">
                                <button type="submit" name="delete_complaint" class="text-rose-600 hover:text-rose-700 font-bold text-xs uppercase tracking-widest bg-rose-50 px-4 py-2 rounded-lg transition-all">Hapus Keluhan</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

                <?php if (empty($complaints)): ?>
                <div class="bg-white p-20 rounded-[32px] border border-dashed border-gray-200 text-center">
                    <p class="text-slate-300 font-black uppercase tracking-widest text-xs">Belum ada keluhan masuk</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
</body>
</html>
