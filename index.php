<?php
require_once 'includes/db.php';

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

    // Server-side validation
    $stmt_class = $db->prepare("SELECT total_students FROM classes WHERE id = ?");
    $stmt_class->execute([$class_id]);
    $class_data = $stmt_class->fetch();

    if (!$class_data) {
        $message = ['type' => 'error', 'text' => 'Kelas tidak valid.'];
    } elseif ($present < 0 || $present > $class_data['total_students']) {
        $message = ['type' => 'error', 'text' => 'Jumlah siswa hadir tidak valid.'];
    } else {
        // Check if already submitted today
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

// Get classes that haven't submitted today (using prepared statement for safety)
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
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">

    <!-- Header -->
    <header class="gradient-bg text-white py-12 px-4 shadow-lg mb-8">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-3xl md:text-4xl font-extrabold mb-2 tracking-tight">LAPORAN JUMLAH MBG</h1>
            <p class="text-blue-100 text-lg">SMK Negeri 2 Bondowoso</p>
            <div class="inline-block mt-4 px-4 py-1 bg-white/20 backdrop-blur-md rounded-full text-sm font-medium">
                <?= date('l, d F Y') ?>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 pb-20">

        <?php if ($message): ?>
        <div id="alert-message" class="flex items-center p-4 mb-6 text-<?= $message['type'] == 'success' ? 'green' : 'red' ?>-800 rounded-xl bg-<?= $message['type'] == 'success' ? 'green' : 'red' ?>-50 border border-<?= $message['type'] == 'success' ? 'green' : 'red' ?>-200" role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <div class="ms-3 text-sm font-bold">
                <?= $message['text'] ?>
            </div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-<?= $message['type'] == 'success' ? 'green' : 'red' ?>-50 text-<?= $message['type'] == 'success' ? 'green' : 'red' ?>-500 rounded-lg focus:ring-2 focus:ring-<?= $message['type'] == 'success' ? 'green' : 'red' ?>-400 p-1.5 hover:bg-<?= $message['type'] == 'success' ? 'green' : 'red' ?>-200 inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#alert-message" aria-label="Close">
              <span class="sr-only">Close</span>
              <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
              </svg>
            </button>
        </div>
        <?php endif; ?>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 mb-10">
            <div class="p-6 md:p-8">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">Form Laporan Harian</h2>
                </div>

                <form method="POST" class="space-y-6">
                    <div>
                        <label for="class_id" class="block mb-2 text-sm font-semibold text-gray-700">Pilih Kelas</label>
                        <select name="class_id" id="class_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-4 transition-all hover:bg-white">
                            <option value="">-- Pilih Kelas Anda --</option>
                            <?php foreach ($available_classes as $class): ?>
                                <option value="<?= $class['id'] ?>"><?= htmlspecialchars($class['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                            <label class="block mb-1 text-xs font-bold text-blue-600 uppercase tracking-wider">Wali Kelas</label>
                            <div id="homeroom_teacher" class="text-gray-900 font-medium py-1">-</div>
                        </div>
                        <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                            <label class="block mb-1 text-xs font-bold text-blue-600 uppercase tracking-wider">Total Siswa</label>
                            <div id="total_students_display" class="text-gray-900 font-medium py-1">-</div>
                        </div>
                    </div>

                    <div>
                        <label for="students_present" class="block mb-2 text-sm font-semibold text-gray-700">Jumlah Siswa Hadir Makan</label>
                        <input type="number" name="students_present" id="students_present" required min="0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-4 transition-all hover:bg-white" placeholder="Masukkan angka">
                    </div>

                    <button type="submit" name="submit_report" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-bold rounded-xl text-lg px-5 py-4 text-center transition-all transform hover:scale-[1.01] active:scale-[0.99] shadow-lg shadow-blue-200">
                        KIRIM LAPORAN
                    </button>
                </form>
            </div>
        </div>

        <!-- Today's Submissions -->
        <div class="mb-6 flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-800">Sudah Lapor Hari Ini</h3>
            <span class="bg-gray-200 text-gray-700 text-xs font-bold px-3 py-1 rounded-full"><?= count($today_reports) ?> Kelas</span>
        </div>

        <div class="space-y-4">
            <?php foreach ($today_reports as $report): ?>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900"><?= htmlspecialchars($report['name']) ?></h4>
                        <p class="text-sm text-gray-500"><?= htmlspecialchars($report['homeroom_teacher']) ?></p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-lg font-extrabold text-blue-600"><?= $report['students_present'] ?><span class="text-gray-300 font-normal mx-1">/</span><span class="text-gray-400 text-sm font-medium"><?= $report['total_students'] ?></span></div>
                    <div class="text-[10px] text-gray-400 font-medium uppercase tracking-widest mt-1"><?= date('H:i', strtotime($report['created_at'])) ?> WIB</div>
                </div>
            </div>
            <?php endforeach; ?>

            <?php if (empty($today_reports)): ?>
            <div class="bg-gray-100/50 border-2 border-dashed border-gray-200 rounded-2xl p-10 text-center">
                <p class="text-gray-400 font-medium italic">Belum ada laporan masuk hari ini.</p>
            </div>
            <?php endif; ?>
        </div>

        <div class="mt-12 text-center">
            <a href="admin/index.php" class="text-sm text-gray-400 hover:text-blue-600 transition-colors font-medium">Dashboard Admin</a>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    <script>
        document.getElementById('class_id').addEventListener('change', function() {
            const classId = this.value;
            if (classId) {
                fetch(`?get_class_info=${classId}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('homeroom_teacher').textContent = data.homeroom_teacher;
                        document.getElementById('total_students_display').textContent = data.total_students + ' Siswa';
                        document.getElementById('students_present').max = data.total_students;
                        document.getElementById('students_present').value = data.total_students;
                    });
            } else {
                document.getElementById('homeroom_teacher').textContent = '-';
                document.getElementById('total_students_display').textContent = '-';
            }
        });
    </script>
</body>
</html>
