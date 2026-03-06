<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once 'layout.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

$stmt = $db->prepare("SELECT * FROM classes WHERE id = ?");
$stmt->execute([$id]);
$class = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$class) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_class'])) {
    verify_csrf();
    $stmt = $db->prepare("UPDATE classes SET name = ?, homeroom_teacher = ?, total_students = ? WHERE id = ?");
    $stmt->execute([$_POST['name'], $_POST['homeroom_teacher'], $_POST['total_students'], $id]);
    header('Location: index.php?updated=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kelas - MBG Admin</title>
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
    <?php render_sidebar('index.php'); ?>

    <div class="p-4 sm:ml-64">
        <div class="p-4 mt-16 lg:p-10">

            <div class="mb-10 flex items-center gap-4">
                <a href="index.php" class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-slate-400 hover:text-blue-600 hover:shadow-lg transition-all border border-slate-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none mb-2">Edit Kelas</h1>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Update data informasi kelas</p>
                </div>
            </div>

            <div class="max-w-2xl bg-white rounded-[40px] shadow-sm shadow-slate-200/50 p-10 border border-slate-100">
                <form method="POST" class="space-y-8">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <div class="grid grid-cols-1 gap-8">
                        <div>
                            <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Kelas</label>
                            <input type="text" name="name" value="<?= htmlspecialchars($class['name']) ?>" required class="bg-slate-50 border-2 border-slate-50 text-slate-900 text-sm font-bold rounded-2xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 block w-full p-4.5 transition-all outline-none">
                        </div>
                        <div>
                            <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Wali Kelas</label>
                            <input type="text" name="homeroom_teacher" value="<?= htmlspecialchars($class['homeroom_teacher']) ?>" required class="bg-slate-50 border-2 border-slate-50 text-slate-900 text-sm font-bold rounded-2xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 block w-full p-4.5 transition-all outline-none">
                        </div>
                        <div>
                            <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Jumlah Siswa</label>
                            <input type="number" name="total_students" value="<?= $class['total_students'] ?>" required class="bg-slate-50 border-2 border-slate-50 text-slate-900 text-sm font-bold rounded-2xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 block w-full p-4.5 transition-all outline-none">
                        </div>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="submit" name="update_class" class="btn-gradient flex-1 text-white font-black rounded-2xl text-[10px] px-8 py-5 tracking-widest uppercase transition-all shadow-lg shadow-blue-100">
                            Simpan Perubahan
                        </button>
                        <a href="index.php" class="flex-1 bg-slate-100 text-slate-500 font-black rounded-2xl text-[10px] px-8 py-5 tracking-widest uppercase transition-all text-center">
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
