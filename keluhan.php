<?php
require_once 'includes/db.php';

// Indonesian Date Localization
function getIndonesianDate($date) {
    $days = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
    $months = ['January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April', 'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'];

    $dayName = $days[date('l', strtotime($date))];
    $monthName = $months[date('F', strtotime($date))];
    $dayNum = date('d', strtotime($date));
    $year = date('Y', strtotime($date));

    return "$dayName, $dayNum $monthName $year";
}

$today = date('Y-m-d');
$message = '';

// Handle Submit Complaint
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_complaint'])) {
    verify_csrf();
    $name = $_POST['name'];
    $role = $_POST['role'];
    $class_id = ($role === 'Siswa') ? $_POST['class_id'] : null;
    $description = $_POST['description'];

    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
        $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));

        if (in_array($file_extension, $allowed_extensions)) {
            $target_dir = "uploads/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $new_filename = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = $target_file;
            }
        } else {
            $message = ['type' => 'error', 'text' => 'Format file tidak didukung. Hanya JPG, PNG, dan WEBP yang diperbolehkan.'];
        }
    }

    $stmt = $db->prepare("INSERT INTO complaints (name, role, class_id, image_path, description) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$name, $role, $class_id, $image_path, $description])) {
        $message = ['type' => 'success', 'text' => 'Keluhan berhasil dikirim. Terima kasih atas masukannya.'];
    } else {
        $message = ['type' => 'error', 'text' => 'Gagal mengirim keluhan. Silakan coba lagi.'];
    }
}

$classes = $db->query("SELECT * FROM classes ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keluhan MBG - SMKN 2 Bondowoso</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; font-size: 0.95rem; }
        .lux-gradient { background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #3b82f6 100%); }
        .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08); }
        .animate-fade-in { animation: fadeIn 0.6s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="antialiased text-slate-900">

    <!-- Premium Header -->
    <header class="lux-gradient text-white pt-10 pb-20 px-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full opacity-5">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white"></path>
            </svg>
        </div>
        <div class="max-w-4xl mx-auto text-center relative z-10">
            <h1 class="text-3xl md:text-5xl font-black mb-3 tracking-tighter leading-tight animate-fade-in">
                FORMULIR <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-white">KELUHAN</span>
            </h1>
            <p class="text-blue-100/80 text-sm md:text-base font-medium tracking-wide animate-fade-in">
                Sampaikan kritik, saran, atau kendala mengenai layanan MBG
            </p>
        </div>
    </header>

    <main class="max-w-2xl mx-auto px-4 -mt-12 pb-20 relative z-20">

        <div class="flex justify-center mb-6 animate-fade-in">
            <div class="bg-white px-4 py-2 rounded-xl shadow-lg shadow-blue-900/5 border border-blue-50 flex items-center gap-2">
                <div class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></div>
                <span class="text-[11px] font-black text-slate-600 uppercase tracking-widest">
                    <?= getIndonesianDate($today) ?>
                </span>
            </div>
        </div>

        <?php if ($message): ?>
        <div id="alert-message" class="animate-fade-in flex items-center p-4 mb-6 text-<?= $message['type'] == 'success' ? 'emerald' : 'rose' ?>-900 rounded-2xl bg-<?= $message['type'] == 'success' ? 'emerald' : 'rose' ?>-50 border border-<?= $message['type'] == 'success' ? 'emerald' : 'rose' ?>-100 shadow-sm" role="alert">
            <div class="text-sm font-bold"><?= $message['text'] ?></div>
            <button type="button" class="ms-auto p-1.5 text-<?= $message['type'] == 'success' ? 'emerald' : 'rose' ?>-400 hover:bg-<?= $message['type'] == 'success' ? 'emerald' : 'rose' ?>-100 rounded-lg transition-all" data-dismiss-target="#alert-message">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <?php endif; ?>

        <!-- Form Section -->
        <div class="glass-card rounded-[32px] p-6 md:p-10 mb-10 animate-fade-in">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 bg-rose-500 rounded-xl flex items-center justify-center shadow-lg shadow-rose-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">Kirim Masukan Anda</h2>
                    <p class="text-xs text-slate-400 font-medium">Bantu kami meningkatkan kualitas layanan</p>
                </div>
            </div>

            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block mb-2 text-[10px] font-black text-slate-700 uppercase tracking-widest">Nama Lengkap</label>
                        <input type="text" name="name" id="name" required class="bg-slate-50 border border-slate-200 text-sm rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 block w-full p-4 transition-all outline-none" placeholder="Masukkan nama...">
                    </div>
                    <div>
                        <label for="role" class="block mb-2 text-[10px] font-black text-slate-700 uppercase tracking-widest">Peran</label>
                        <select name="role" id="role" required class="bg-slate-50 border border-slate-200 text-sm rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 block w-full p-4 transition-all outline-none">
                            <option value="Siswa">Siswa</option>
                            <option value="Guru">Guru</option>
                            <option value="Tendik">Tendik</option>
                        </select>
                    </div>
                </div>

                <div id="class_selection">
                    <label for="class_id" class="block mb-2 text-[10px] font-black text-slate-700 uppercase tracking-widest">Pilih Kelas</label>
                    <select name="class_id" id="class_id" class="bg-slate-50 border border-slate-200 text-sm rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 block w-full p-4 transition-all outline-none">
                        <option value="">-- Pilih Kelas --</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?= $class['id'] ?>"><?= htmlspecialchars($class['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="image" class="block mb-2 text-[10px] font-black text-slate-700 uppercase tracking-widest">Lampiran Foto (Kamera/File)</label>
                    <input type="file" name="image" id="image" accept="image/*" capture="camera" class="block w-full text-sm text-slate-500 file:mr-4 file:py-3.5 file:px-6 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition-all border border-slate-200 rounded-xl bg-slate-50 p-1">
                </div>

                <div>
                    <label for="description" class="block mb-2 text-[10px] font-black text-slate-700 uppercase tracking-widest">Detail Keluhan / Masukan</label>
                    <textarea name="description" id="description" rows="4" required class="bg-slate-50 border border-slate-200 text-sm rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 block w-full p-4 transition-all outline-none" placeholder="Tuliskan keluhan atau masukan Anda di sini..."></textarea>
                </div>

                <button type="submit" name="submit_complaint" class="w-full text-white bg-rose-600 hover:bg-rose-700 focus:ring-4 focus:ring-rose-200 font-black rounded-2xl text-sm px-5 py-5 text-center transition-all transform hover:scale-[1.01] active:scale-[0.99] shadow-xl shadow-rose-400/20 uppercase tracking-[0.2em]">
                    Kirim Keluhan
                </button>
            </form>
        </div>

        <div class="mt-16 text-center flex flex-col items-center gap-4">
            <a href="index.php" class="inline-flex items-center gap-2 bg-white text-slate-900 border border-slate-200 px-6 py-3.5 rounded-xl font-black text-[9px] uppercase tracking-[0.3em] shadow-xl shadow-slate-900/5 transition-all hover:bg-slate-50 hover:-translate-y-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Dashboard
            </a>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    <script>
        document.getElementById('role').addEventListener('change', function() {
            const classSelection = document.getElementById('class_selection');
            const classInput = document.getElementById('class_id');
            if (this.value === 'Siswa') {
                classSelection.style.display = 'block';
                classInput.required = true;
            } else {
                classSelection.style.display = 'none';
                classInput.required = false;
                classInput.value = '';
            }
        });
    </script>
</body>
</html>
