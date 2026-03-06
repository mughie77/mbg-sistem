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
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem MBG - SMKN 2 Bondowoso</title>
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
    <header class="lux-gradient text-white pt-10 pb-24 px-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full opacity-5">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white"></path>
            </svg>
        </div>
        <div class="max-w-4xl mx-auto text-center relative z-10">
            <span class="inline-block px-3 py-1 bg-blue-500/20 backdrop-blur-md rounded-full text-[10px] font-black tracking-[0.2em] uppercase mb-4 border border-white/10 animate-fade-in">
                Makan Bergizi Gratis
            </span>
            <h1 class="text-3xl md:text-5xl font-black mb-3 tracking-tighter leading-tight animate-fade-in" style="animation-delay: 0.1s">
                DASHBOARD <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-white">MBG</span>
            </h1>
            <p class="text-blue-100/80 text-sm md:text-base font-medium tracking-wide animate-fade-in" style="animation-delay: 0.2s">
                SMK Negeri 2 Bondowoso
            </p>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 -mt-16 pb-20 relative z-20">

        <div class="flex justify-center mb-8 animate-fade-in" style="animation-delay: 0.3s">
            <div class="bg-white px-4 py-2 rounded-xl shadow-lg shadow-blue-900/5 border border-blue-50 flex items-center gap-2">
                <div class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></div>
                <span class="text-[11px] font-black text-slate-600 uppercase tracking-widest">
                    <?= getIndonesianDate($today) ?>
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Menu 1: Input Data -->
            <a href="input_penerima.php" class="group glass-card rounded-[32px] p-8 flex flex-col items-center text-center transition-all hover:scale-[1.03] hover:shadow-2xl animate-fade-in" style="animation-delay: 0.4s">
                <div class="w-16 h-16 bg-blue-600 rounded-[22px] flex items-center justify-center shadow-xl shadow-blue-200 mb-6 group-hover:rotate-6 transition-transform">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <h2 class="text-xl font-black text-slate-900 tracking-tight mb-2">Input Data Penerima MBG</h2>
                <p class="text-xs text-slate-400 font-medium leading-relaxed">Laporkan jumlah kehadiran siswa penerima MBG harian</p>
                <div class="mt-6 px-4 py-2 bg-slate-50 rounded-full text-[9px] font-black text-blue-600 uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">
                    Buka Formulir
                </div>
            </a>

            <!-- Menu 2: Penerimaan Hari Ini -->
            <a href="penerimaan_hari_ini.php" class="group glass-card rounded-[32px] p-8 flex flex-col items-center text-center transition-all hover:scale-[1.03] hover:shadow-2xl animate-fade-in" style="animation-delay: 0.5s">
                <div class="w-16 h-16 bg-emerald-500 rounded-[22px] flex items-center justify-center shadow-xl shadow-emerald-200 mb-6 group-hover:rotate-6 transition-transform">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h2 class="text-xl font-black text-slate-900 tracking-tight mb-2">Penerimaan MBG Hari Ini</h2>
                <p class="text-xs text-slate-400 font-medium leading-relaxed">Lihat daftar kehadiran siswa per kelas hari ini</p>
                <div class="mt-6 px-4 py-2 bg-slate-50 rounded-full text-[9px] font-black text-emerald-600 uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">
                    Lihat Data
                </div>
            </a>

            <!-- Menu 3: Keluhan MBG -->
            <a href="keluhan.php" class="group glass-card rounded-[32px] p-8 flex flex-col items-center text-center transition-all hover:scale-[1.03] hover:shadow-2xl animate-fade-in" style="animation-delay: 0.6s">
                <div class="w-16 h-16 bg-rose-500 rounded-[22px] flex items-center justify-center shadow-xl shadow-rose-200 mb-6 group-hover:rotate-6 transition-transform">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h2 class="text-xl font-black text-slate-900 tracking-tight mb-2">Keluhan MBG Hari Ini</h2>
                <p class="text-xs text-slate-400 font-medium leading-relaxed">Sampaikan saran, masukan, atau keluhan Anda</p>
                <div class="mt-6 px-4 py-2 bg-slate-50 rounded-full text-[9px] font-black text-rose-600 uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">
                    Kirim Keluhan
                </div>
            </a>

        </div>

        <div class="mt-16 text-center animate-fade-in" style="animation-delay: 0.7s">
            <a href="admin/index.php" class="inline-flex items-center gap-2 bg-slate-900 text-white px-6 py-3.5 rounded-xl font-black text-[9px] uppercase tracking-[0.3em] shadow-xl shadow-slate-900/10 transition-all hover:bg-blue-600 hover:-translate-y-1">
                Administrasi
            </a>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
</body>
</html>
