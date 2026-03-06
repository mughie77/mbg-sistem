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

// Fetch class data for AJAX
if (isset($_GET['get_class_info'])) {
    $stmt = $db->prepare("SELECT * FROM classes WHERE id = ?");
    $stmt->execute([$_GET['get_class_info']]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    exit;
}

$today = date('Y-m-d');
$message = '';

// Handle Submit Report
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_report'])) {
    $class_id = $_POST['class_id'];
    $present = (int)$_POST['students_present'];

    $stmt_class = $db->prepare("SELECT total_students FROM classes WHERE id = ?");
    $stmt_class->execute([$class_id]);
    $class_data = $stmt_class->fetch();

    if (!$class_data) {
        $message = ['type' => 'error', 'text' => 'Kelas tidak valid.'];
    } elseif ($present < 0 || $present > $class_data['total_students']) {
        $message = ['type' => 'error', 'text' => 'Jumlah siswa hadir tidak valid.'];
    } else {
        $check = $db->prepare("SELECT id FROM reports WHERE class_id = ? AND report_date = ?");
        $check->execute([$class_id, $today]);

        if ($check->fetch()) {
            $message = ['type' => 'error', 'text' => 'Kelas ini sudah mengirim laporan hari ini.'];
        } else {
            $stmt = $db->prepare("INSERT INTO reports (class_id, report_date, students_present) VALUES (?, ?, ?)");
            $stmt->execute([$class_id, $today, $present]);
            $message = ['type' => 'success', 'text' => 'Laporan berhasil dikirim!'];
        }
    }
}

// Get classes that haven't submitted today
$stmt_avail = $db->prepare("SELECT * FROM classes WHERE id NOT IN (SELECT class_id FROM reports WHERE report_date = ?) ORDER BY name ASC");
$stmt_avail->execute([$today]);
$available_classes = $stmt_avail->fetchAll(PDO::FETCH_ASSOC);

// Get today's reports
$stmt_today = $db->prepare("SELECT r.*, c.name, c.homeroom_teacher, c.total_students
                FROM reports r
                JOIN classes c ON r.class_id = c.id
                WHERE r.report_date = ?
                ORDER BY r.created_at DESC");
$stmt_today->execute([$today]);
$today_reports = $stmt_today->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan MBG - SMKN 2 Bondowoso</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .lux-gradient { background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #3b82f6 100%); }
        .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08); }
        .animate-fade-in { animation: fadeIn 0.6s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="antialiased text-slate-900">

    <!-- Premium Header -->
    <header class="lux-gradient text-white pt-16 pb-28 px-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white"></path>
            </svg>
        </div>
        <div class="max-w-4xl mx-auto text-center relative z-10">
            <span class="inline-block px-4 py-1.5 bg-blue-500/20 backdrop-blur-md rounded-full text-xs font-black tracking-[0.2em] uppercase mb-6 border border-white/10 animate-fade-in">
                Reporting System 2026
            </span>
            <h1 class="text-4xl md:text-6xl font-black mb-4 tracking-tighter leading-tight animate-fade-in" style="animation-delay: 0.1s">
                LAPORAN JUMLAH <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-white">MBG</span>
            </h1>
            <p class="text-blue-100/80 text-lg md:text-xl font-medium tracking-wide animate-fade-in" style="animation-delay: 0.2s">
                SMK Negeri 2 Bondowoso
            </p>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-4 -mt-16 pb-24 relative z-20">

        <div class="flex justify-center mb-8 animate-fade-in" style="animation-delay: 0.3s">
            <div class="bg-white px-6 py-3 rounded-2xl shadow-xl shadow-blue-900/5 border border-blue-50 flex items-center gap-3">
                <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                <span class="text-sm font-bold text-slate-600 uppercase tracking-widest">
                    <?= getIndonesianDate($today) ?>
                </span>
            </div>
        </div>

        <?php if ($message): ?>
        <div id="alert-message" class="animate-fade-in flex items-center p-5 mb-8 text-<?= $message['type'] == 'success' ? 'emerald' : 'rose' ?>-900 rounded-3xl bg-<?= $message['type'] == 'success' ? 'emerald' : 'rose' ?>-50 border-2 border-<?= $message['type'] == 'success' ? 'emerald' : 'rose' ?>-100 shadow-lg" role="alert">
            <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-<?= $message['type'] == 'success' ? 'emerald' : 'rose' ?>-500/10 flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-<?= $message['type'] == 'success' ? 'emerald' : 'rose' ?>-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $message['type'] == 'success' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12' ?>"></path></svg>
            </div>
            <div class="text-base font-bold"><?= $message['text'] ?></div>
            <button type="button" class="ms-auto p-2 text-<?= $message['type'] == 'success' ? 'emerald' : 'rose' ?>-400 hover:bg-<?= $message['type'] == 'success' ? 'emerald' : 'rose' ?>-100 rounded-xl transition-all" data-dismiss-target="#alert-message">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <?php endif; ?>

        <!-- Form Section -->
        <div class="glass-card rounded-[40px] p-8 md:p-12 mb-12 animate-fade-in" style="animation-delay: 0.4s">
            <div class="flex items-center gap-5 mb-10">
                <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-200">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Formulir Laporan</h2>
                    <p class="text-slate-400 font-medium">Input kehadiran penerima MBG harian</p>
                </div>
            </div>

            <form method="POST" class="space-y-8">
                <div>
                    <label for="class_id" class="block mb-3 text-sm font-black text-slate-700 uppercase tracking-widest">Pilih Kelas</label>
                    <select name="class_id" id="class_id" required class="bg-slate-50 border-2 border-slate-100 text-slate-900 text-lg rounded-2xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 block w-full p-5 transition-all outline-none">
                        <option value="">-- Cari Kelas Anda --</option>
                        <?php foreach ($available_classes as $class): ?>
                            <option value="<?= $class['id'] ?>"><?= htmlspecialchars($class['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-slate-50/80 p-6 rounded-3xl border border-slate-100 group transition-all hover:bg-white hover:shadow-xl hover:shadow-slate-200/50">
                        <span class="block mb-2 text-[10px] font-black text-blue-600 uppercase tracking-[0.2em]">Wali Kelas</span>
                        <div id="homeroom_teacher" class="text-slate-900 font-extrabold text-lg">-</div>
                    </div>
                    <div class="bg-slate-50/80 p-6 rounded-3xl border border-slate-100 group transition-all hover:bg-white hover:shadow-xl hover:shadow-slate-200/50">
                        <span class="block mb-2 text-[10px] font-black text-blue-600 uppercase tracking-[0.2em]">Kapasitas Siswa</span>
                        <div id="total_students_display" class="text-slate-900 font-extrabold text-lg">-</div>
                    </div>
                </div>

                <div class="relative group">
                    <label for="students_present" class="block mb-3 text-sm font-black text-slate-700 uppercase tracking-widest">
                        Jumlah Siswa Hadir Penerima MBG <br class="md:hidden">
                        <span class="text-blue-600">(<?= getIndonesianDate($today) ?>)</span>
                    </label>
                    <div class="relative">
                        <input type="number" name="students_present" id="students_present" required min="0" class="bg-slate-50 border-2 border-slate-100 text-slate-900 text-2xl font-black rounded-3xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 block w-full p-6 transition-all outline-none" placeholder="0">
                        <div class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 font-black text-sm uppercase tracking-widest hidden sm:block">Siswa</div>
                    </div>
                </div>

                <button type="submit" name="submit_report" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 font-black rounded-3xl text-xl px-5 py-6 text-center transition-all transform hover:scale-[1.02] active:scale-[0.98] shadow-2xl shadow-blue-400/30 uppercase tracking-widest">
                    Kirim Laporan Sekarang
                </button>
            </form>
        </div>

        <!-- Real-time Feed -->
        <div class="flex items-center justify-between mb-8 px-2 animate-fade-in" style="animation-delay: 0.5s">
            <h3 class="text-xl font-black text-slate-900 tracking-tight uppercase">Data Terkirim Hari Ini</h3>
            <span class="bg-blue-600 text-white text-[10px] font-black px-4 py-1.5 rounded-full shadow-lg shadow-blue-200"><?= count($today_reports) ?> KELAS</span>
        </div>

        <div class="grid grid-cols-1 gap-4 animate-fade-in" style="animation-delay: 0.6s">
            <?php foreach ($today_reports as $report): ?>
            <div class="bg-white p-6 rounded-[32px] shadow-xl shadow-slate-200/50 border border-slate-50 flex items-center justify-between hover:shadow-2xl transition-all group">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 transition-colors group-hover:bg-emerald-600 group-hover:text-white">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-black text-slate-900 text-xl leading-none mb-2"><?= htmlspecialchars($report['name']) ?></h4>
                        <div class="flex items-center text-xs font-bold text-slate-400 uppercase tracking-widest">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <?= htmlspecialchars($report['homeroom_teacher']) ?>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-black text-blue-600"><?= $report['students_present'] ?><span class="text-slate-200 font-light mx-2 text-xl">/</span><span class="text-slate-300 text-lg font-bold"><?= $report['total_students'] ?></span></div>
                    <div class="text-[9px] text-slate-400 font-black uppercase tracking-[0.2em] mt-1 flex items-center justify-end">
                        <svg class="w-2.5 h-2.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        TERCATAT <?= date('H:i', strtotime($report['created_at'])) ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <?php if (empty($today_reports)): ?>
            <div class="bg-slate-100/30 border-4 border-dashed border-slate-100 rounded-[40px] p-20 text-center">
                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <p class="text-slate-400 font-bold uppercase tracking-widest text-sm">Belum ada laporan yang masuk</p>
            </div>
            <?php endif; ?>
        </div>

        <div class="mt-20 text-center">
            <a href="admin/index.php" class="inline-flex items-center gap-2 bg-slate-900 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-[0.3em] shadow-xl shadow-slate-900/20 transition-all hover:bg-blue-600 hover:-translate-y-1">
                Dashboard Administrasi
            </a>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    <script>
        document.getElementById('class_id').addEventListener('change', function() {
            const classId = this.value;
            const teacherEl = document.getElementById('homeroom_teacher');
            const totalEl = document.getElementById('total_students_display');
            const inputEl = document.getElementById('students_present');

            if (classId) {
                teacherEl.innerHTML = '<span class="text-slate-300">Memuat...</span>';
                totalEl.innerHTML = '<span class="text-slate-300">...</span>';

                fetch(`?get_class_info=${classId}`)
                    .then(response => response.json())
                    .then(data => {
                        teacherEl.textContent = data.homeroom_teacher;
                        totalEl.textContent = data.total_students + ' Siswa';
                        inputEl.max = data.total_students;
                        inputEl.value = data.total_students;
                    });
            } else {
                teacherEl.textContent = '-';
                totalEl.textContent = '-';
                inputEl.value = '';
            }
        });
    </script>
</body>
</html>
