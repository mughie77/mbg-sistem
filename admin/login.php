<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === 'admin' && $password === 'admin123!') {
        $_SESSION['admin_logged_in'] = true;
        header('Location: index.php');
        exit;
    } else {
        $error = 'Username atau password salah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - MBG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #0f172a; }
        .glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-extrabold text-white tracking-tight mb-2">MBG ADMIN</h1>
            <p class="text-slate-400">Silakan login untuk mengelola data</p>
        </div>

        <div class="glass rounded-3xl p-8 shadow-2xl">
            <?php if (isset($error)): ?>
            <div class="p-4 mb-6 text-sm text-red-400 rounded-xl bg-red-900/30 border border-red-800/50" role="alert">
                <?= $error ?>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div>
                    <label class="block mb-2 text-sm font-medium text-slate-300">Username</label>
                    <input type="text" name="username" required class="w-full bg-slate-900/50 border border-slate-700 text-white text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-4 placeholder-slate-500 transition-all" placeholder="admin">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-slate-300">Password</label>
                    <input type="password" name="password" required class="w-full bg-slate-900/50 border border-slate-700 text-white text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-4 placeholder-slate-500 transition-all" placeholder="••••••••">
                </div>
                <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-bold rounded-xl text-sm px-5 py-4 text-center transition-all transform hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-blue-900/20">
                    MASUK KE DASHBOARD
                </button>
            </form>
        </div>

        <div class="text-center mt-8">
            <a href="../index.php" class="text-slate-500 hover:text-white text-sm transition-colors"> Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>
