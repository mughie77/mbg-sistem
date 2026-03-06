<?php
function render_sidebar($active_page) {
    $pages = [
        'index.php' => ['label' => 'Daftar Kelas', 'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16', 'color' => 'blue'],
        'reports.php' => ['label' => 'Laporan', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'color' => 'emerald'],
        'complaints.php' => ['label' => 'Keluhan', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', 'color' => 'orange'],
        '../index.php' => ['label' => 'Lihat Beranda', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'color' => 'purple']
    ];
    ?>
    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-100 sm:translate-x-0 shadow-sm">
        <div class="h-full px-4 pb-4 overflow-y-auto bg-white">
            <ul class="space-y-2 font-medium">
                <?php foreach ($pages as $url => $data): ?>
                    <?php
                    $isActive = ($active_page === $url);
                    $colorClass = "text-{$data['color']}-600";
                    $bgClass = $isActive ? "bg-{$data['color']}-50 shadow-sm border border-{$data['color']}-100" : "hover:bg-slate-50";
                    $textClass = $isActive ? "text-{$data['color']}-700 font-black" : "text-slate-500 font-semibold";
                    ?>
                    <li>
                        <a href="<?= $url ?>" class="flex items-center p-3.5 rounded-2xl transition-all duration-200 group <?= $bgClass ?> <?= $textClass ?>">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center mr-3 transition-colors <?= $isActive ? "bg-{$data['color']}-100" : "bg-slate-50 group-hover:bg-white" ?>">
                                <svg class="w-5 h-5 <?= $colorClass ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="<?= $data['icon'] ?>"></path></svg>
                            </div>
                            <span class="tracking-tight"><?= $data['label'] ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="mt-10 pt-6 border-t border-slate-50">
                <a href="?logout=1" class="flex items-center p-3.5 rounded-2xl text-rose-600 hover:bg-rose-50 transition-all font-bold group">
                    <div class="w-10 h-10 bg-rose-50 group-hover:bg-rose-100 rounded-xl flex items-center justify-center mr-3 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </div>
                    LOGOUT
                </a>
            </div>
        </div>
    </aside>
    <?php
}

function render_navbar() {
    ?>
    <nav class="fixed top-0 z-50 w-full bg-white/80 backdrop-blur-md border-b border-slate-100 shadow-sm">
        <div class="px-4 py-3.5 lg:px-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" class="inline-flex items-center p-2 text-slate-500 rounded-xl sm:hidden hover:bg-slate-100 mr-2">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"></path></svg>
                    </button>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200">
                            <span class="text-white font-black text-xl tracking-tighter">M</span>
                        </div>
                        <span class="text-xl font-black text-slate-900 tracking-tighter uppercase hidden sm:block">MBG <span class="text-blue-600">PANEL</span></span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="hidden md:flex flex-col items-end">
                        <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest leading-none mb-1">Administrator</span>
                        <span class="text-xs font-bold text-slate-400">SMKN 2 Bondowoso</span>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-slate-100 border-2 border-white shadow-sm overflow-hidden flex items-center justify-center">
                        <svg class="w-6 h-6 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path></svg>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <?php
}
