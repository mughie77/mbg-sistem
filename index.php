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

// Get today's reports (Sorted by class name)
$stmt_today = $db->prepare("SELECT r.*, c.name, c.homeroom_teacher, c.total_students
                FROM reports r
                JOIN classes c ON r.class_id = c.id
                WHERE r.report_date = ?
                ORDER BY c.name ASC");
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
            <span class="inline-block px-3 py-1 bg-blue-500/20 backdrop-blur-md rounded-full text-[10px] font-black tracking-[0.2em] uppercase mb-4 border border-white/10 animate-fade-in">
                Reporting System 2026
            </span>
            <h1 class="text-3xl md:text-5xl font-black mb-3 tracking-tighter leading-tight animate-fade-in" style="animation-delay: 0.1s">
                LAPORAN JUMLAH <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-white">MBG</span>
            </h1>
            <p class="text-blue-100/80 text-sm md:text-base font-medium tracking-wide animate-fade-in" style="animation-delay: 0.2s">
                SMK Negeri 2 Bondowoso
            </p>
        </div>
    </header>

    <main class="max-w-2xl mx-auto px-4 -mt-12 pb-20 relative z-20">

        <div class="flex justify-center mb-6 animate-fade-in" style="animation-delay: 0.3s">
            <div class="bg-white px-4 py-2 rounded-xl shadow-lg shadow-blue-900/5 border border-blue-50 flex items-center gap-2">
                <div class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></div>
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
        <div class="glass-card rounded-[32px] p-6 md:p-10 mb-10 animate-fade-in" style="animation-delay: 0.4s">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">Formulir Laporan</h2>
                    <p class="text-xs text-slate-400 font-medium">Input kehadiran penerima MBG harian</p>
                </div>
            </div>

            <form method="POST" class="space-y-6">
                <div>
                    <label for="class_id" class="block mb-2 text-[10px] font-black text-slate-700 uppercase tracking-widest">Pilih Kelas</label>
                    <select name="class_id" id="class_id" required class="bg-slate-50 border border-slate-200 text-sm rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 block w-full p-4 transition-all outline-none">
                        <option value="">-- Cari Kelas Anda --</option>
                        <?php foreach ($available_classes as $class): ?>
                            <option value="<?= $class['id'] ?>"><?= htmlspecialchars($class['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-slate-50/80 p-4 rounded-2xl border border-slate-100">
                        <span class="block mb-1 text-[9px] font-black text-blue-600 uppercase tracking-widest">Wali Kelas</span>
                        <div id="homeroom_teacher" class="text-slate-900 font-bold text-sm">-</div>
                    </div>
                    <div class="bg-slate-50/80 p-4 rounded-2xl border border-slate-100">
                        <span class="block mb-1 text-[9px] font-black text-blue-600 uppercase tracking-widest">Kapasitas</span>
                        <div id="total_students_display" class="text-slate-900 font-bold text-sm">-</div>
                    </div>
                </div>

                <div>
                    <label for="students_present" class="block mb-2 text-[10px] font-black text-slate-700 uppercase tracking-widest">
                        Jumlah Siswa Hadir Penerima MBG <br class="md:hidden">
                        <span class="text-blue-600">(<?= getIndonesianDate($today) ?>)</span>
                    </label>
                    <div class="relative">
                        <input type="number" name="students_present" id="students_present" required min="0" class="bg-slate-50 border border-slate-200 text-slate-900 text-xl font-black rounded-2xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 block w-full p-5 transition-all outline-none" placeholder="0">
                        <div class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 font-black text-[10px] uppercase tracking-widest hidden sm:block">Siswa</div>
                    </div>
                </div>

                <button type="submit" name="submit_report" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 font-black rounded-2xl text-sm px-5 py-5 text-center transition-all transform hover:scale-[1.01] active:scale-[0.99] shadow-xl shadow-blue-400/20 uppercase tracking-[0.2em]">
                    Kirim Laporan
                </button>
            </form>
        </div>

        <!-- Real-time Feed -->
        <div class="flex items-center justify-between mb-6 px-2 animate-fade-in" style="animation-delay: 0.5s">
            <h3 class="text-sm font-black text-slate-900 tracking-tight uppercase">Data Terkirim Hari Ini</h3>
            <span class="bg-blue-600 text-white text-[9px] font-black px-3 py-1 rounded-full shadow-lg shadow-blue-200"><?= count($today_reports) ?> KELAS</span>
        </div>

        <div class="grid grid-cols-1 gap-3 animate-fade-in" style="animation-delay: 0.6s">
            <?php foreach ($today_reports as $report): ?>
            <div class="bg-white p-4 rounded-[24px] shadow-lg shadow-slate-200/40 border border-slate-50 flex items-center justify-between hover:shadow-xl transition-all group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 transition-colors group-hover:bg-emerald-600 group-hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-black text-slate-900 text-base leading-none mb-1.5"><?= htmlspecialchars($report['name']) ?></h4>
                        <div class="flex items-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            <?= htmlspecialchars($report['homeroom_teacher']) ?>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-xl font-black text-blue-600"><?= $report['students_present'] ?><span class="text-slate-200 font-light mx-1 text-sm">/</span><span class="text-slate-300 text-xs font-bold"><?= $report['total_students'] ?></span></div>
                    <div class="text-[8px] text-slate-400 font-black uppercase tracking-[0.2em] mt-1 flex items-center justify-end">
                        <svg class="w-2 h-2 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <?= date('H:i', strtotime($report['created_at'])) ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <?php if (empty($today_reports)): ?>
            <div class="bg-slate-100/30 border-2 border-dashed border-slate-100 rounded-[32px] p-12 text-center">
                <p class="text-slate-300 font-black uppercase tracking-widest text-[10px]">Belum ada data masuk</p>
            </div>
            <?php endif; ?>
        </div>

        <div class="mt-16 text-center">
            <a href="admin/index.php" class="inline-flex items-center gap-2 bg-slate-900 text-white px-6 py-3.5 rounded-xl font-black text-[9px] uppercase tracking-[0.3em] shadow-xl shadow-slate-900/10 transition-all hover:bg-blue-600 hover:-translate-y-1">
                Administrasi
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
                teacherEl.innerHTML = '<span class="text-slate-300">...</span>';
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
