<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once 'layout.php';

// Handle Delete Complaint
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_complaint'])) {
    verify_csrf();
    $stmt = $db->prepare("SELECT image_path FROM complaints WHERE id = ?");
    $stmt->execute([$_POST['id']]);
    $complaint = $stmt->fetch();
    if ($complaint && $complaint['image_path'] && file_exists('../' . $complaint['image_path'])) {
        unlink('../' . $complaint['image_path']);
    }

    $stmt = $db->prepare("DELETE FROM complaints WHERE id = ?");
    $stmt->execute([$_POST['id']]);
    header("Location: complaints.php?deleted=1");
    exit();
}

$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

$query = "SELECT comp.*, c.name as class_name
          FROM complaints comp
          LEFT JOIN classes c ON comp.class_id = c.id";

$params = [];
if ($start_date && $end_date) {
    $query .= " WHERE DATE(comp.created_at) BETWEEN ? AND ?";
    $params = [$start_date, $end_date];
}

$query .= " ORDER BY comp.created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute($params);
$complaints = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keluhan - MBG Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .img-zoom { transition: transform 0.3s ease; cursor: zoom-in; }
        .img-zoom:hover { transform: scale(1.05); }
    </style>
</head>
<body class="bg-slate-50 antialiased">

    <?php render_navbar(); ?>
    <?php render_sidebar('complaints.php'); ?>

    <div class="p-4 sm:ml-64">
        <div class="p-4 mt-16 lg:p-10">

            <?php if (isset($_GET['deleted'])): ?>
            <div id="alert-success" class="flex items-center p-5 mb-8 text-green-800 rounded-3xl bg-green-50 border border-green-100 shadow-sm animate-fade-in" role="alert">
                <div class="w-10 h-10 bg-green-100 rounded-2xl flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div class="text-sm font-bold uppercase tracking-widest">Keluhan berhasil dihapus!</div>
                <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-xl p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 transition-colors" data-dismiss-target="#alert-success"><svg class="w-3 h-3" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg></button>
            </div>
            <?php endif; ?>

            <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-6">
                <div>
                    <h1 class="text-3xl lg:text-4xl font-black text-slate-900 tracking-tight leading-none mb-3">Keluhan & Masukan</h1>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-black text-orange-600 uppercase tracking-[0.2em] bg-orange-50 px-3 py-1.5 rounded-lg border border-orange-100">Daftar Aspirasi</span>
                        <span class="text-xs font-bold text-slate-400">SMKN 2 Bondowoso</span>
                    </div>
                </div>
                <a href="export_complaints.php?start_date=<?= $start_date ?>&end_date=<?= $end_date ?>" target="_blank" class="flex items-center text-white bg-emerald-600 hover:bg-emerald-700 font-black rounded-2xl text-[10px] px-8 py-4.5 tracking-widest uppercase shadow-lg shadow-emerald-100 transition-all">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Export PDF
                </a>
            </div>

            <!-- Filter Section -->
            <div class="bg-white p-8 rounded-[32px] border border-slate-100 mb-10 shadow-sm shadow-slate-200/50">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                    <div>
                        <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Mulai Tanggal</label>
                        <input type="date" name="start_date" value="<?= $start_date ?>" class="bg-slate-50 border-2 border-slate-50 text-slate-900 text-sm font-bold rounded-2xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 block w-full p-4 transition-all outline-none">
                    </div>
                    <div>
                        <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="<?= $end_date ?>" class="bg-slate-50 border-2 border-slate-50 text-slate-900 text-sm font-bold rounded-2xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 block w-full p-4 transition-all outline-none">
                    </div>
                    <div class="flex gap-3">
                        <button type="submit" class="flex-grow text-white bg-blue-600 hover:bg-blue-700 font-black rounded-2xl text-[10px] px-5 py-4.5 tracking-widest transition-all">FILTER</button>
                        <a href="complaints.php" class="bg-slate-100 text-slate-500 font-black rounded-2xl text-[10px] px-5 py-4.5 tracking-widest transition-all text-center">RESET</a>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 gap-8">
                <?php foreach ($complaints as $complaint): ?>
                <div class="bg-white p-8 rounded-[32px] shadow-sm shadow-slate-200/50 border border-slate-100 flex flex-col md:flex-row gap-8 hover:shadow-xl transition-all group">
                    <?php if ($complaint['image_path']): ?>
                    <div class="w-full md:w-56 h-56 flex-shrink-0 relative overflow-hidden rounded-3xl border border-slate-50 shadow-inner">
                        <img src="../<?= $complaint['image_path'] ?>" alt="Attachment"
                             class="w-full h-full object-cover img-zoom"
                             onclick="showOverlay('../<?= $complaint['image_path'] ?>')">
                    </div>
                    <?php endif; ?>
                    <div class="flex-grow flex flex-col">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <span class="bg-blue-50 text-blue-700 text-[9px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest border border-blue-100"><?= $complaint['role'] ?></span>
                                <h3 class="text-xl font-black text-slate-900 mt-3 tracking-tight"><?= htmlspecialchars($complaint['name']) ?></h3>
                                <?php if ($complaint['class_name']): ?>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1">Kelas: <?= htmlspecialchars($complaint['class_name']) ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest"><?= date('d M Y H:i', strtotime($complaint['created_at'])) ?></span>
                            </div>
                        </div>
                        <div class="flex-grow bg-slate-50/50 p-6 rounded-2xl italic text-slate-600 text-sm leading-relaxed border border-slate-50 group-hover:bg-white transition-colors group-hover:border-slate-100">
                            "<?= nl2br(htmlspecialchars($complaint['description'])) ?>"
                        </div>
                        <div class="mt-6 flex justify-end">
                            <form method="POST" onsubmit="return confirm('Hapus keluhan ini?')" class="inline">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <input type="hidden" name="id" value="<?= $complaint['id'] ?>">
                                <button type="submit" name="delete_complaint" class="text-rose-600 hover:text-white hover:bg-rose-600 font-black text-[9px] uppercase tracking-widest bg-rose-50 px-5 py-3 rounded-xl transition-all border border-rose-100 group-hover:shadow-lg group-hover:shadow-rose-100">Hapus Keluhan</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

                <?php if (empty($complaints)): ?>
                <div class="bg-white p-24 rounded-[40px] border border-dashed border-slate-200 text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    </div>
                    <p class="text-slate-300 font-black uppercase tracking-[0.3em] text-xs">Belum ada keluhan masuk</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Overlay Modal -->
    <div id="imageOverlay" class="fixed inset-0 z-[60] hidden bg-slate-900/90 backdrop-blur-md flex items-center justify-center p-4" onclick="hideOverlay()">
        <button class="absolute top-8 right-8 text-white hover:text-blue-400 transition-colors">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <img id="overlayImg" src="" class="max-w-full max-h-[90vh] rounded-[32px] shadow-2xl transition-all duration-300" onclick="event.stopPropagation()">
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    <script>
        function showOverlay(src) {
            const overlay = document.getElementById('imageOverlay');
            const img = document.getElementById('overlayImg');
            img.src = src;
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            img.classList.add('scale-100');
            img.classList.remove('scale-95');
        }

        function hideOverlay() {
            const overlay = document.getElementById('imageOverlay');
            const img = document.getElementById('overlayImg');
            img.classList.remove('scale-100');
            img.classList.add('scale-95');
            setTimeout(() => {
                overlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 200);
        }
    </script>
</body>
</html>
