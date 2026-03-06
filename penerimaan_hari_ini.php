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

// Get all classes and their report status for today
$query = "SELECT c.*, r.students_present, r.created_at
          FROM classes c
          LEFT JOIN reports r ON c.id = r.class_id AND r.report_date = ?
          ORDER BY c.name ASC";
$stmt = $db->prepare($query);
$stmt->execute([$today]);
$classes_status = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_submitted = 0;
foreach($classes_status as $row) {
    if ($row['students_present'] !== null) $total_submitted++;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penerimaan Hari Ini - SMKN 2 Bondowoso</title>
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
                PENERIMAAN <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-white">HARI INI</span>
            </h1>
            <p class="text-blue-100/80 text-sm md:text-base font-medium tracking-wide animate-fade-in">
                Status Kehadiran Penerima MBG SMK Negeri 2 Bondowoso
            </p>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 -mt-12 pb-20 relative z-20">

        <div class="flex justify-between items-center mb-8 animate-fade-in">
            <div class="bg-white px-4 py-2 rounded-xl shadow-lg shadow-blue-900/5 border border-blue-50 flex items-center gap-2">
                <div class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></div>
                <span class="text-[11px] font-black text-slate-600 uppercase tracking-widest">
                    <?= getIndonesianDate($today) ?>
                </span>
            </div>
            <div class="bg-blue-600 text-white px-4 py-2 rounded-xl shadow-lg shadow-blue-200 text-[10px] font-black uppercase tracking-widest">
                <?= $total_submitted ?> / <?= count($classes_status) ?> Terisi
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 animate-fade-in">
            <?php foreach ($classes_status as $row): ?>
            <div class="bg-white p-5 rounded-[28px] shadow-lg shadow-slate-200/40 border border-slate-50 flex items-center justify-between hover:shadow-xl transition-all group">
                <div class="flex items-center gap-4">
                    <?php if ($row['students_present'] !== null): ?>
                        <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    <?php else: ?>
                        <div class="w-12 h-12 bg-rose-50 rounded-xl flex items-center justify-center text-rose-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </div>
                    <?php endif; ?>

                    <div>
                        <h4 class="font-black text-slate-900 text-base leading-none mb-1.5"><?= htmlspecialchars($row['name']) ?></h4>
                        <div class="flex items-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            <?= htmlspecialchars($row['homeroom_teacher']) ?>
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <?php if ($row['students_present'] !== null): ?>
                        <div class="text-xl font-black text-emerald-600"><?= $row['students_present'] ?><span class="text-slate-200 font-light mx-1 text-sm">/</span><span class="text-slate-300 text-xs font-bold"><?= $row['total_students'] ?></span></div>
                        <div class="text-[8px] text-slate-400 font-black uppercase tracking-[0.2em] mt-1">Siswa Hadir</div>
                    <?php else: ?>
                        <div class="bg-rose-600 text-white px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest">
                            Belum isi data
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-16 text-center flex flex-col items-center gap-4">
            <a href="index.php" class="inline-flex items-center gap-2 bg-white text-slate-900 border border-slate-200 px-6 py-3.5 rounded-xl font-black text-[9px] uppercase tracking-[0.3em] shadow-xl shadow-slate-900/5 transition-all hover:bg-slate-50 hover:-translate-y-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Dashboard
            </a>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
</body>
</html>
