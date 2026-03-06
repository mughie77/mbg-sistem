<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - MBG SMKN 2 Bondowoso</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900">

    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                        </svg>
                    </button>
                    <a href="#" class="flex ms-2 md:me-24">
                        <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white uppercase tracking-tight">MBG Admin</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="index.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                            <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                            <path d="M12.5 0c-.157 0-.311.01-.462.03a1 1 0 0 0-.89.89 8 8 0 0 0 8.939 8.939.999.999 0 0 0 .89-.89 1 1 0 0 0-.03-.462A7.502 7.502 0 0 0 12.5 0Z"/>
                        </svg>
                        <span class="ms-3">Daftar Kelas</span>
                    </a>
                </li>
                <li>
                    <a href="reports.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group bg-gray-100">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m17.418 3.623-.018-.008a6.713 6.713 0 0 0-2.4-.569V2h1a1 1 0 1 0 0-2h-2a1 1 0 0 0-1 1v2H9.89A6.977 6.977 0 0 1 12 8v5h-2V8A5 5 0 1 0 0 8v6a1 1 0 0 0 1 1h8v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4h6a1 1 0 0 0 1-1V8a5.006 5.006 0 0 0-4.582-4.377ZM6 12H4a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2Z"/>
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Laporan</span>
                    </a>
                </li>
                <li>
                    <a href="../index.php" target="_blank" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.96 2.96 0 0 0 .13 5H5Z"/>
                            <path d="M6.737 11.061a2.961 2.961 0 0 1 .81-1.515l6.117-6.116A4.839 4.839 0 0 1 16 2.141V2a1 1 0 0 0-1-1H8.224v4a1 1 0 0 1-1 1H3V17a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8.342a4.835 4.835 0 0 1-2.565 2.22l-6.117 6.117a2.961 2.961 0 0 1-1.515.81L9.047 20l1.111-2.939Z"/>
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Halaman Depan</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <div class="p-4 sm:ml-64">
        <div class="p-4 mt-14">

            <?php
            require_once '../includes/db.php';

            $query = "SELECT r.*, c.name, c.homeroom_teacher, c.total_students
                      FROM reports r
                      JOIN classes c ON r.class_id = c.id
                      ORDER BY r.report_date DESC, r.created_at DESC";
            $reports = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Semua Laporan MBG</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Daftar semua laporan yang telah disubmit oleh setiap kelas.</p>
            </div>

            <div class="relative overflow-x-auto shadow-md sm:rounded-xl">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Tanggal</th>
                            <th scope="col" class="px-6 py-3">Kelas</th>
                            <th scope="col" class="px-6 py-3">Wali Kelas</th>
                            <th scope="col" class="px-6 py-3 text-center">Hadir</th>
                            <th scope="col" class="px-6 py-3 text-center">Total</th>
                            <th scope="col" class="px-6 py-3">Waktu Submit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reports as $report): ?>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white"><?= date('d/m/Y', strtotime($report['report_date'])) ?></td>
                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($report['name']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($report['homeroom_teacher']) ?></td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300"><?= $report['students_present'] ?></span>
                            </td>
                            <td class="px-6 py-4 text-center"><?= $report['total_students'] ?></td>
                            <td class="px-6 py-4 text-xs"><?= $report['created_at'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($reports)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 italic">Belum ada laporan yang disubmit.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
</body>
</html>
