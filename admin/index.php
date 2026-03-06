<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

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
    <title>Admin Dashboard - MBG SMKN 2 Bondowoso</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');
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
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                        </svg>
                    </button>
                    <a href="#" class="flex ms-2 md:me-24">
                        <span class="self-center text-xl font-black sm:text-2xl whitespace-nowrap text-blue-600 uppercase tracking-tighter">MBG PANEL</span>
                    </a>
                </div>
                <div class="flex items-center">
                    <a href="?logout=1" class="text-sm font-semibold text-red-600 hover:text-red-700 flex items-center bg-red-50 px-4 py-2 rounded-xl transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        LOGOUT
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-100 sm:translate-x-0 shadow-sm" aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="index.php" class="flex items-center p-3 text-gray-900 rounded-xl hover:bg-gray-50 group sidebar-item-active">
                        <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                            <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                            <path d="M12.5 0c-.157 0-.311.01-.462.03a1 1 0 0 0-.89.89 8 8 0 0 0 8.939 8.939.999.999 0 0 0 .89-.89 1 1 0 0 0-.03-.462A7.502 7.502 0 0 0 12.5 0Z"/>
                        </svg>
                        <span class="ms-3 font-bold">Daftar Kelas</span>
                    </a>
                </li>
                <li>
                    <a href="reports.php" class="flex items-center p-3 text-gray-600 rounded-xl hover:bg-gray-50 group">
                        <svg class="flex-shrink-0 w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m17.418 3.623-.018-.008a6.713 6.713 0 0 0-2.4-.569V2h1a1 1 0 1 0 0-2h-2a1 1 0 0 0-1 1v2H9.89A6.977 6.977 0 0 1 12 8v5h-2V8A5 5 0 1 0 0 8v6a1 1 0 0 0 1 1h8v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4h6a1 1 0 0 0 1-1V8a5.006 5.006 0 0 0-4.582-4.377ZM6 12H4a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2Z"/>
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap font-semibold">Laporan</span>
                    </a>
                </li>
                <li>
                    <a href="complaints.php" class="flex items-center p-3 text-gray-600 rounded-xl hover:bg-gray-50 group">
                        <svg class="flex-shrink-0 w-5 h-5 transition duration-75 text-gray-500 group-hover:text-gray-900" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path></svg>
                        <span class="flex-1 ms-3 whitespace-nowrap font-semibold">Keluhan</span>
                    </a>
                </li>
                <li>
                    <a href="../index.php" target="_blank" class="flex items-center p-3 text-gray-600 rounded-xl hover:bg-gray-50 group">
                        <svg class="flex-shrink-0 w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.96 2.96 0 0 0 .13 5H5Z"/>
                            <path d="M6.737 11.061a2.961 2.961 0 0 1 .81-1.515l6.117-6.116A4.839 4.839 0 0 1 16 2.141V2a1 1 0 0 0-1-1H8.224v4a1 1 0 0 1-1 1H3V17a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8.342a4.835 4.835 0 0 1-2.565 2.22l-6.117 6.117a2.961 2.961 0 0 1-1.515.81L9.047 20l1.111-2.939Z"/>
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap font-semibold">Lihat Beranda</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <div class="p-4 sm:ml-64">
        <div class="p-4 mt-14">

            <?php if (isset($_GET['added']) || isset($_GET['updated']) || isset($_GET['deleted'])): ?>
            <div id="alert-success" class="flex items-center p-4 mb-6 text-green-800 rounded-2xl bg-green-50 border border-green-100" role="alert">
                <svg class="flex-shrink-0 w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/></svg>
                <div class="ms-3 text-sm font-bold">Data berhasil diperbarui!</div>
                <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#alert-success"><span class="sr-only">Close</span><svg class="w-3 h-3" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg></button>
            </div>
            <?php endif; ?>

            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight">Daftar Kelas</h1>
                    <p class="text-sm text-gray-500 font-medium uppercase tracking-widest mt-1">Manajemen Data Siswa & Wali Kelas</p>
                </div>
                <button data-modal-target="add-modal" data-modal-toggle="add-modal" class="flex items-center text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-bold rounded-2xl text-sm px-6 py-4 shadow-lg shadow-blue-200 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    TAMBAH KELAS
                </button>
            </div>

            <div class="table-container bg-white">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-50/50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-5 font-bold">No</th>
                            <th class="px-6 py-5 font-bold">Nama Kelas</th>
                            <th class="px-6 py-5 font-bold">Wali Kelas</th>
                            <th class="px-6 py-5 text-center font-bold">Siswa</th>
                            <th class="px-6 py-5 font-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php foreach ($classes as $i => $class): ?>
                        <tr class="bg-white hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-5 font-bold text-gray-400"><?= $i + 1 ?></td>
                            <td class="px-6 py-5 font-black text-gray-900"><?= htmlspecialchars($class['name']) ?></td>
                            <td class="px-6 py-5 font-medium text-gray-600"><?= htmlspecialchars($class['homeroom_teacher']) ?></td>
                            <td class="px-6 py-5 text-center">
                                <span class="bg-blue-50 text-blue-700 text-xs font-black px-3 py-1.5 rounded-full border border-blue-100"><?= $class['total_students'] ?></span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <a href="edit_class.php?id=<?= $class['id'] ?>" class="font-bold text-blue-600 hover:text-blue-700 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        Edit
                                    </a>
                                    <form method="POST" onsubmit="return confirm('Hapus kelas ini?')" class="inline">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <input type="hidden" name="id" value="<?= $class['id'] ?>">
                                        <button type="submit" name="delete_class" class="font-bold text-red-500 hover:text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            Hapus
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

    <!-- Main modal -->
    <div id="add-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
                <div class="flex items-center justify-between p-6 border-b border-gray-50">
                    <h3 class="text-xl font-black text-gray-900 tracking-tight uppercase">Tambah Kelas Baru</h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-100 hover:text-gray-900 rounded-xl text-sm w-10 h-10 ms-auto inline-flex justify-center items-center" data-modal-toggle="add-modal">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    </button>
                </div>
                <form class="p-6 space-y-5" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <div>
                        <label class="block mb-2 text-sm font-bold text-gray-700">Nama Kelas</label>
                        <input type="text" name="name" required class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-4 transition-all" placeholder="Misal: XII RPL 1">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-bold text-gray-700">Wali Kelas</label>
                        <input type="text" name="homeroom_teacher" required class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-4 transition-all" placeholder="Nama Lengkap">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-bold text-gray-700">Jumlah Siswa</label>
                        <input type="number" name="total_students" required class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-4 transition-all" placeholder="0">
                    </div>
                    <button type="submit" name="add_class" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-black rounded-xl text-sm px-5 py-4 text-center shadow-lg shadow-blue-200 transition-all">
                        SIMPAN KELAS
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
</body>
</html>
