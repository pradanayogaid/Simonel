<div class="p-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold">Selamat Datang, <?= explode(' ', htmlspecialchars($data['user']['name']))[0]; ?>!</h1>
            <p class="text-gray-500">Berikut adalah ringkasan sistem energi Anda hari ini.</p>
        </div>
        <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-full shadow-sm border border-gray-100 text-sm font-bold text-gray-500">
            <i class='bx bx-calendar text-[#5B5FEF] text-lg'></i>
            <span><?= date('d M Y'); ?></span>
        </div>
    </div>
    
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Total Devices -->
        <div class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 group hover:shadow-md transition-all">
            <div class="flex justify-between items-center mb-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-[#5B5FEF] flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <i class='bx bxs-devices'></i>
                </div>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Perangkat</span>
            </div>
            <div>
                <h3 class="text-3xl font-black text-gray-800"><?= $data['stats']['total_devices']; ?></h3>
                <p class="text-xs text-gray-400 mt-1">Perangkat Terdaftar</p>
            </div>
        </div>

        <!-- Online Devices -->
        <div class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 group hover:shadow-md transition-all">
            <div class="flex justify-between items-center mb-4">
                <div class="w-12 h-12 rounded-2xl bg-green-50 text-green-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <i class='bx bx-wifi'></i>
                </div>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status Online</span>
            </div>
            <div>
                <h3 class="text-3xl font-black text-gray-800"><?= $data['stats']['online_devices']; ?></h3>
                <p class="text-xs text-green-500 mt-1 font-bold flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                    Perangkat Aktif
                </p>
            </div>
        </div>

        <!-- Total Power -->
        <div class="bg-[#5B5FEF] rounded-[32px] p-6 shadow-lg shadow-indigo-100 relative overflow-hidden group">
            <div class="relative z-10">
                <div class="flex justify-between items-center mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-white/20 text-white flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        <i class='bx bxs-bolt'></i>
                    </div>
                    <span class="text-[10px] font-bold text-indigo-100 uppercase tracking-widest">Daya Total</span>
                </div>
                <div>
                    <div class="flex items-baseline gap-1">
                        <h3 class="text-3xl font-black text-white"><?= number_format($data['stats']['total_power'], 1); ?></h3>
                        <span class="text-indigo-100 font-bold text-sm">W</span>
                    </div>
                    <p class="text-xs text-indigo-100 mt-1">Beban Saat Ini</p>
                </div>
            </div>
            <i class='bx bxs-zap absolute -bottom-4 -right-4 text-7xl text-white/10'></i>
        </div>

        <!-- Energy Today -->
        <div class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 group hover:shadow-md transition-all">
            <div class="flex justify-between items-center mb-4">
                <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <i class='bx bxs-leaf'></i>
                </div>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Energi Hari Ini</span>
            </div>
            <div>
                <div class="flex items-baseline gap-1">
                    <h3 class="text-3xl font-black text-gray-800"><?= number_format($data['stats']['total_energy_today'], 2); ?></h3>
                    <span class="text-gray-400 font-bold text-sm">kWh</span>
                </div>
                <p class="text-xs text-gray-400 mt-1">Konsumsi Akumulatif</p>
            </div>
        </div>
    </div>

    <!-- Info Section -->
    <div class="bg-white rounded-[40px] p-8 shadow-sm border border-gray-50 flex flex-col md:flex-row items-center gap-8">
        <div class="w-24 h-24 rounded-3xl bg-indigo-50 flex items-center justify-center text-4xl text-[#5B5FEF] shrink-0">
            <i class='bx bxs-rocket'></i>
        </div>
        <div class="flex-1 text-center md:text-left">
            <h2 class="text-xl font-bold mb-2">Phase 1 Berhasil Diluncurkan!</h2>
            <p class="text-gray-500 leading-relaxed">Sistem Autentikasi, Manajemen Perangkat, dan API Receiver telah aktif sepenuhnya. Anda sekarang dapat mulai memantau data secara realtime melalui menu Realtime.</p>
            <div class="mt-4 flex flex-wrap justify-center md:justify-start gap-3">
                <a href="<?= BASEURL; ?>/realtime" class="px-6 py-2.5 bg-[#5B5FEF] text-white rounded-full font-bold text-sm hover:shadow-lg transition-all">Mulai Monitoring</a>
                <a href="<?= BASEURL; ?>/device" class="px-6 py-2.5 bg-gray-50 text-gray-600 rounded-full font-bold text-sm hover:bg-gray-100 transition-all">Kelola Perangkat</a>
            </div>
        </div>
    </div>
</div>
