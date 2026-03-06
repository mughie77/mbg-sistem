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
    <title>Login Admin - MBG Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .lux-bg { background: radial-gradient(circle at top right, #1e293b, #0f172a); }
        .glass-card { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .btn-gradient { background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%); }
    </style>
</head>
<body class="lux-bg min-h-screen flex flex-col items-center justify-center p-6 antialiased">

    <div class="w-full max-w-[440px] animate-fade-in">
        <div class="text-center mb-12">
            <div class="w-20 h-20 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-[24px] flex items-center justify-center shadow-2xl shadow-blue-500/20 mx-auto mb-6 transform hover:rotate-12 transition-transform duration-500">
                <span class="text-white font-black text-4xl tracking-tighter">M</span>
            </div>
            <h1 class="text-3xl font-black text-white tracking-tighter uppercase mb-2">MBG <span class="text-blue-500">PANEL</span></h1>
            <p class="text-slate-500 text-sm font-bold uppercase tracking-[0.2em]">Management Access</p>
        </div>

        <div class="glass-card rounded-[40px] p-10 shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-600 to-indigo-600 opacity-50"></div>

            <?php if (isset($error)): ?>
            <div class="flex items-center p-5 mb-8 text-rose-200 rounded-2xl bg-rose-500/10 border border-rose-500/20 animate-shake" role="alert">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <div class="text-[10px] font-black uppercase tracking-widest"><?= $error ?></div>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-8">
                <div>
                    <label class="block mb-3 text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Username</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-500 text-slate-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <input type="text" name="username" required class="w-full bg-white/5 border-2 border-transparent text-white text-sm font-bold rounded-2xl focus:border-blue-500/50 focus:bg-blue-500/5 block p-4.5 pl-14 transition-all outline-none placeholder-slate-700" placeholder="admin">
                    </div>
                </div>
                <div>
                    <label class="block mb-3 text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-500 text-slate-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input type="password" name="password" required class="w-full bg-white/5 border-2 border-transparent text-white text-sm font-bold rounded-2xl focus:border-blue-500/50 focus:bg-blue-500/5 block p-4.5 pl-14 transition-all outline-none placeholder-slate-700" placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="btn-gradient w-full text-white font-black rounded-2xl text-[10px] px-5 py-5 text-center tracking-widest uppercase shadow-2xl shadow-blue-600/20 transform transition-all hover:scale-[1.02] active:scale-[0.98] hover:shadow-blue-600/40">
                    Masuk Dashboard
                </button>
            </form>
        </div>

        <div class="text-center mt-12">
            <a href="../index.php" class="inline-flex items-center gap-2 text-slate-600 hover:text-blue-400 text-[10px] font-black uppercase tracking-widest transition-colors group">
                <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
