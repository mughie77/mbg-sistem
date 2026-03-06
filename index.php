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
    <title>Dashboard MBG - SMKN 2 Bondowoso</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #fcfdfe;
            background-image: radial-gradient(#e2e8f0 0.5px, transparent 0.5px);
            background-size: 20px 20px;
        }
        .card-container {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-container:hover {
            transform: translateY(-8px);
        }
        .icon-circle {
            width: 90px;
            height: 90px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
        }
        .animate-fade-in { animation: fadeIn 0.8s ease-out forwards; opacity: 0; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="antialiased text-slate-900 min-h-screen flex flex-col items-center justify-center py-12 px-4">

    <div class="max-w-6xl w-full">
        <!-- Header -->
        <div class="text-center mb-16 animate-fade-in">
            <h1 class="text-4xl md:text-5xl font-black tracking-tight text-slate-900 mb-4">
                DASHBOARD <span class="text-blue-600">MBG</span>
            </h1>
            <div class="flex flex-col items-center gap-2">
                <p class="text-slate-500 font-bold uppercase tracking-[0.2em] text-xs">SMK Negeri 2 Bondowoso</p>
                <div class="h-1 w-12 bg-blue-600 rounded-full mt-2"></div>
            </div>
        </div>

        <!-- Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

            <!-- Card 1: Laporan -->
            <a href="input_penerima.php" class="card-container block animate-fade-in" style="animation-delay: 0.1s">
                <div class="bg-white rounded-[24px] overflow-hidden shadow-xl shadow-slate-200/60 border border-slate-100 flex flex-col h-full">
                    <div class="h-32 bg-gradient-to-br from-emerald-400 to-cyan-400 relative">
                        <div class="icon-circle">
                            <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                    </div>
                    <div class="pt-16 pb-10 px-6 text-center flex-grow">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Input Data</p>
                        <h3 class="text-lg font-black text-emerald-600 leading-tight uppercase tracking-tight">Laporan<br>Penerimaan</h3>
                    </div>
                </div>
            </a>

            <!-- Card 2: Status -->
            <a href="penerimaan_hari_ini.php" class="card-container block animate-fade-in" style="animation-delay: 0.2s">
                <div class="bg-white rounded-[24px] overflow-hidden shadow-xl shadow-slate-200/60 border border-slate-100 flex flex-col h-full">
                    <div class="h-32 bg-gradient-to-br from-blue-400 to-indigo-500 relative">
                        <div class="icon-circle">
                            <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                    </div>
                    <div class="pt-16 pb-10 px-6 text-center flex-grow">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Monitoring</p>
                        <h3 class="text-lg font-black text-blue-600 leading-tight uppercase tracking-tight">Status<br>Hari Ini</h3>
                    </div>
                </div>
            </a>

            <!-- Card 3: Keluhan -->
            <a href="keluhan.php" class="card-container block animate-fade-in" style="animation-delay: 0.3s">
                <div class="bg-white rounded-[24px] overflow-hidden shadow-xl shadow-slate-200/60 border border-slate-100 flex flex-col h-full">
                    <div class="h-32 bg-gradient-to-br from-orange-400 to-rose-500 relative">
                        <div class="icon-circle">
                            <svg class="w-10 h-10 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                    </div>
                    <div class="pt-16 pb-10 px-6 text-center flex-grow">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Aspirasi</p>
                        <h3 class="text-lg font-black text-orange-600 leading-tight uppercase tracking-tight">Keluhan<br>Siswa</h3>
                    </div>
                </div>
            </a>

            <!-- Card 4: Admin -->
            <a href="admin/index.php" class="card-container block animate-fade-in" style="animation-delay: 0.4s">
                <div class="bg-white rounded-[24px] overflow-hidden shadow-xl shadow-slate-200/60 border border-slate-100 flex flex-col h-full">
                    <div class="h-32 bg-gradient-to-br from-violet-400 to-purple-600 relative">
                        <div class="icon-circle">
                            <svg class="w-10 h-10 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                        </div>
                    </div>
                    <div class="pt-16 pb-10 px-6 text-center flex-grow">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Management</p>
                        <h3 class="text-lg font-black text-violet-600 leading-tight uppercase tracking-tight">Panel<br>Admin</h3>
                    </div>
                </div>
            </a>

        </div>

        <!-- Footer Info -->
        <div class="mt-20 text-center animate-fade-in" style="animation-delay: 0.6s">
            <div class="inline-flex items-center gap-3 bg-white px-6 py-3 rounded-2xl shadow-sm border border-slate-100">
                <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                <span class="text-[11px] font-black text-slate-500 uppercase tracking-widest"><?= getIndonesianDate($today) ?></span>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
</body>
</html>
