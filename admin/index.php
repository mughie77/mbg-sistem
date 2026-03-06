<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once 'layout.php';

// Handle Add Class
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_class'])) {
    verify_csrf();
    $stmt = $db->prepare("INSERT INTO classes (name, homeroom_teacher, total_students) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['name'], $_POST['homeroom_teacher'], $_POST['total_students']]);
    header("Location: index.php?added=1");
    exit();
}

// Handle Delete Class
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_class'])) {
    verify_csrf();
    $stmt = $db->prepare("DELETE FROM classes WHERE id = ?");
    $stmt->execute([$_POST['id']]);
    header("Location: index.php?deleted=1");
    exit();
}

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$classes = $db->query("SELECT * FROM classes ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - MBG SMKN 2 Bondowoso</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .table-container { border-radius: 28px; overflow: hidden; box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.04); }
        .btn-gradient { background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%); transition: all 0.3s; }
        .btn-gradient:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3); }
    </style>
</head>
<body class="bg-slate-50 antialiased">

    <?php render_navbar(); ?>
    <?php render_sidebar('index.php'); ?>

    <div class="p-4 sm:ml-64">
        <div class="p-4 mt-16 lg:p-10">

            <?php if (isset($_GET['added']) || isset($_GET['updated']) || isset($_GET['deleted'])): ?>
            <div id="alert-success" class="flex items-center p-5 mb-8 text-green-800 rounded-3xl bg-green-50 border border-green-100 shadow-sm animate-fade-in" role="alert">
                <div class="w-10 h-10 bg-green-100 rounded-2xl flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div class="text-sm font-bold uppercase tracking-widest">Data berhasil diperbarui!</div>
                <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-xl p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 transition-colors" data-dismiss-target="#alert-success"><svg class="w-3 h-3" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg></button>
            </div>
            <?php endif; ?>

            <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-6">
                <div>
                    <h1 class="text-3xl lg:text-4xl font-black text-slate-900 tracking-tight leading-none mb-3">Daftar Kelas</h1>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-black text-blue-600 uppercase tracking-[0.2em] bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100">Manajemen Data</span>
                        <span class="text-xs font-bold text-slate-400">SMKN 2 Bondowoso</span>
                    </div>
                </div>
                <button data-modal-target="add-modal" data-modal-toggle="add-modal" class="btn-gradient flex items-center text-white font-black rounded-2xl text-xs px-8 py-4.5 tracking-widest uppercase shadow-lg shadow-blue-100 transition-all">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Tambah Kelas
                </button>
            </div>

            <div class="table-container bg-white border border-slate-50">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-[10px] text-slate-400 uppercase tracking-[0.2em] bg-slate-50/50 border-b border-slate-100">
                            <tr>
                                <th class="px-8 py-6 font-black">No</th>
                                <th class="px-8 py-6 font-black">Nama Kelas</th>
                                <th class="px-8 py-6 font-black">Wali Kelas</th>
                                <th class="px-8 py-6 text-center font-black">Siswa</th>
                                <th class="px-8 py-6 text-right font-black">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php foreach ($classes as $i => $class): ?>
                            <tr class="bg-white hover:bg-slate-50/50 transition-colors group">
                                <td class="px-8 py-6 font-bold text-slate-300"><?= $i + 1 ?></td>
                                <td class="px-8 py-6">
                                    <div class="font-black text-slate-900 text-base leading-none mb-1 group-hover:text-blue-600 transition-colors"><?= htmlspecialchars($class['name']) ?></div>
                                    <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest leading-none">ID: <?= $class['id'] ?></span>
                                </td>
                                <td class="px-8 py-6 font-bold text-slate-600 tracking-tight italic"><?= htmlspecialchars($class['homeroom_teacher']) ?></td>
                                <td class="px-8 py-6 text-center">
                                    <span class="bg-blue-50 text-blue-700 text-[10px] font-black px-4 py-2 rounded-xl border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm"><?= $class['total_students'] ?> <span class="text-[8px] ml-0.5 opacity-50 uppercase font-black">Orang</span></span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="edit_class.php?id=<?= $class['id'] ?>" class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        <form method="POST" onsubmit="return confirm('Hapus kelas ini?')" class="inline">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                            <input type="hidden" name="id" value="<?= $class['id'] ?>">
                                            <button type="submit" name="delete_class" class="w-10 h-10 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Add Class Modal -->
    <div id="add-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full p-4">
        <div class="relative w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-[32px] shadow-2xl border border-slate-100 overflow-hidden transform transition-all">
                <div class="flex items-center justify-between p-8 border-b border-slate-50">
                    <div>
                        <h3 class="text-xl font-black text-slate-900 tracking-tighter uppercase leading-none">Tambah Kelas</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1.5">Isi data kelas baru</p>
                    </div>
                    <button type="button" class="text-slate-400 bg-slate-50 hover:bg-slate-100 hover:text-slate-900 rounded-2xl text-sm w-12 h-12 ms-auto inline-flex justify-center items-center transition-all" data-modal-toggle="add-modal">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    </button>
                </div>
                <form class="p-8 space-y-6" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <div>
                        <label class="block mb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Kelas</label>
                        <input type="text" name="name" required class="bg-slate-50 border-2 border-slate-50 text-slate-900 text-sm font-bold rounded-2xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 block w-full p-4.5 transition-all outline-none" placeholder="Misal: XII RPL 1">
                    </div>
                    <div>
                        <label class="block mb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Wali Kelas</label>
                        <input type="text" name="homeroom_teacher" required class="bg-slate-50 border-2 border-slate-50 text-slate-900 text-sm font-bold rounded-2xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 block w-full p-4.5 transition-all outline-none" placeholder="Nama Lengkap">
                    </div>
                    <div>
                        <label class="block mb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Jumlah Siswa</label>
                        <input type="number" name="total_students" required class="bg-slate-50 border-2 border-slate-50 text-slate-900 text-sm font-bold rounded-2xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 block w-full p-4.5 transition-all outline-none" placeholder="0">
                    </div>
                    <button type="submit" name="add_class" class="btn-gradient w-full text-white font-black rounded-2xl text-xs px-5 py-5 text-center tracking-widest uppercase shadow-lg shadow-blue-100 transition-all mt-4">
                        Simpan Kelas
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
</body>
</html>
