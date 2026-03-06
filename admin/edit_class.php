<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

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
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">

    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <a href="index.php" class="flex items-center text-xl font-bold tracking-tight text-gray-900">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    EDIT KELAS
                </a>
            </div>
        </div>
    </nav>

    <div class="max-w-2xl mx-auto mt-24 px-4">
        <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
            <form method="POST" class="space-y-6">
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700">Nama Kelas</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($class['name']) ?>" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-4">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700">Wali Kelas</label>
                    <input type="text" name="homeroom_teacher" value="<?= htmlspecialchars($class['homeroom_teacher']) ?>" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-4">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700">Jumlah Siswa</label>
                    <input type="number" name="total_students" value="<?= $class['total_students'] ?>" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-4">
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="submit" name="update_class" class="flex-1 text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-bold rounded-xl text-sm px-5 py-4 text-center transition-all">
                        SIMPAN PERUBAHAN
                    </button>
                    <a href="index.php" class="flex-1 text-gray-700 bg-gray-100 hover:bg-gray-200 focus:ring-4 focus:ring-gray-300 font-bold rounded-xl text-sm px-5 py-4 text-center transition-all">
                        BATAL
                    </a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
