<div class="p-4 sm:p-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold">Selamat Datang, <?= explode(' ', htmlspecialchars($data['user']['name']))[0]; ?>!</h1>
            <p class="text-gray-500 text-sm sm:text-base">
                Berikut adalah ringkasan sistem energi Anda hari ini, 
                <span class="font-bold text-[#5B5FEF] sm:text-gray-500 sm:font-normal"><?= date('d M Y'); ?></span>.
            </p>
        </div>
        <div class="hidden md:flex items-center gap-2 px-4 py-2 bg-white rounded-full shadow-sm border border-gray-100 text-sm font-bold text-gray-500">
            <i class='bx bx-calendar text-[#5B5FEF] text-lg'></i>
            <span><?= date('d M Y'); ?></span>
        </div>
    </div>
    
    <!-- Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 mb-10">
        <!-- Total Devices -->
        <div class="bg-white rounded-[32px] p-5 sm:p-6 shadow-sm border border-gray-100 group hover:shadow-md transition-all flex flex-col items-center sm:items-start text-center sm:text-left">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center w-full mb-3 sm:mb-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-indigo-50 text-[#5B5FEF] flex items-center justify-center text-xl sm:text-2xl group-hover:scale-110 transition-transform mx-auto sm:mx-0 mb-2 sm:mb-0">
                    <i class='bx bxs-devices'></i>
                </div>
                <span class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Perangkat</span>
            </div>
            <div>
                <h3 class="text-xl sm:text-3xl font-black text-gray-800"><?= $data['stats']['total_devices']; ?></h3>
                <p class="text-[10px] sm:text-xs text-gray-400 mt-1">Terdaftar</p>
            </div>
        </div>

        <!-- Online Devices -->
        <div class="bg-white rounded-[32px] p-5 sm:p-6 shadow-sm border border-gray-100 group hover:shadow-md transition-all flex flex-col items-center sm:items-start text-center sm:text-left">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center w-full mb-3 sm:mb-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-green-50 text-green-500 flex items-center justify-center text-xl sm:text-2xl group-hover:scale-110 transition-transform mx-auto sm:mx-0 mb-2 sm:mb-0">
                    <i class='bx bx-wifi'></i>
                </div>
                <span class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status Online</span>
            </div>
            <div>
                <h3 class="text-xl sm:text-3xl font-black text-gray-800"><?= $data['stats']['online_devices']; ?></h3>
                <p class="text-[10px] sm:text-xs text-green-500 mt-1 font-bold flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                    Aktif
                </p>
            </div>
        </div>

        <!-- Total Power (Daya Nyata) -->
        <div class="col-span-2 md:col-span-1 bg-[#5B5FEF] rounded-[32px] p-6 shadow-lg shadow-indigo-100 relative overflow-hidden group">
            <div class="relative z-10">
                <div class="flex justify-between items-center mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-white/20 text-white flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        <i class='bx bxs-bolt'></i>
                    </div>
                    <span class="text-[10px] font-bold text-indigo-100 uppercase tracking-widest">Daya Nyata Total</span>
                </div>
                <div>
                    <div class="flex items-baseline gap-1">
                        <h3 class="text-3xl font-black text-white"><?= number_format($data['stats']['total_power'], 1); ?></h3>
                        <span class="text-indigo-100 font-bold text-sm">W</span>
                    </div>
                    <p class="text-xs text-indigo-100 mt-1">Beban Aktif Saat Ini</p>
                </div>
            </div>
            <i class='bx bxs-zap absolute -bottom-4 -right-4 text-7xl text-white/10'></i>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <!-- Energy Trend Chart -->
        <div class="lg:col-span-2 bg-white rounded-[40px] p-6 sm:p-8 shadow-sm border border-gray-50">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-3">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Tren Konsumsi Energi</h2>
                    <p class="text-sm text-gray-400">7 hari terakhir (kWh)</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-[#5B5FEF]"></span>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Konsumsi</span>
                </div>
            </div>
            <div class="h-[300px] w-full">
                <canvas id="weeklyChart"></canvas>
            </div>
        </div>

        <!-- Quick Stats / Cost Estimation -->
        <div class="bg-white rounded-[40px] p-8 shadow-sm border border-gray-50 flex flex-col">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Estimasi Hari Ini</h2>
            <div class="space-y-6 flex-1">
                <div class="p-5 bg-gray-50 rounded-3xl">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Daya Semu Total</p>
                    <div class="flex items-baseline gap-1">
                        <h4 class="text-2xl font-black text-gray-800"><?= number_format($data['stats']['total_power_apparent'] ?? 0, 1); ?></h4>
                        <span class="text-sm font-bold text-gray-400">VA</span>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">Total beban semu sistem</p>
                </div>
                
                <div class="p-5 bg-indigo-50/50 rounded-3xl">
                    <p class="text-xs font-bold text-[#5B5FEF] uppercase tracking-widest mb-1">Efisiensi Sistem</p>
                    <div class="flex items-center gap-3">
                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-[#5B5FEF]" style="width: <?= $data['stats']['system_efficiency']; ?>%"></div>
                        </div>
                        <span class="text-sm font-black text-[#5B5FEF]"><?= $data['stats']['system_efficiency']; ?>%</span>
                    </div>
                </div>

                <div class="p-5 border border-dashed border-gray-200 rounded-3xl">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Beban Puncak Hari Ini</p>
                    <div class="flex items-baseline gap-1">
                        <h4 class="text-xl font-black text-gray-700"><?= number_format($data['stats']['daily_peak_power'], 1); ?></h4>
                        <span class="text-sm font-bold text-gray-400">W</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Device Status List -->
    <div class="bg-white rounded-[40px] shadow-sm border border-gray-50 overflow-hidden mb-10">
        <div class="p-8 border-b border-gray-50 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800">Status Perangkat</h2>
            <?php if ($_SESSION['user']['role'] === 'admin') : ?>
            <a href="<?= BASEURL; ?>/device" class="text-sm font-bold text-[#5B5FEF] hover:underline">Lihat Semua</a>
            <?php endif; ?>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Perangkat</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Lokasi</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Beban (W)</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Update Terakhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php foreach ($data['devices'] as $device) : ?>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl <?= $device['status'] == 'ONLINE' ? 'bg-indigo-50 text-[#5B5FEF]' : 'bg-gray-100 text-gray-400'; ?> flex items-center justify-center text-xl">
                                    <i class='bx bxs-microchip'></i>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-700"><?= htmlspecialchars($device['device_name']); ?></div>
                                    <div class="text-[10px] text-gray-400 font-bold uppercase"><?= htmlspecialchars($device['device_code']); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-1 text-sm text-gray-500 font-medium">
                                <i class='bx bx-map-pin text-gray-300'></i>
                                <?= htmlspecialchars($device['location']); ?>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black <?= $device['status'] == 'ONLINE' ? 'bg-green-50 text-green-500' : 'bg-gray-100 text-gray-400'; ?>">
                                <span class="w-1.5 h-1.5 rounded-full <?= $device['status'] == 'ONLINE' ? 'bg-green-500 animate-pulse' : 'bg-gray-400'; ?>"></span>
                                <?= $device['status']; ?>
                            </span>
                        </td>
                        <td class="px-8 py-5 text-sm font-bold text-gray-700">
                            <?= $device['daya_nyata'] ? number_format($device['daya_nyata'], 1) . ' W' : '-'; ?>
                        </td>
                        <td class="px-8 py-5 text-xs text-gray-400 font-medium">
                            <?= $device['last_data'] ? date('H:i, d M', strtotime($device['last_data'])) : 'No Data'; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('weeklyChart').getContext('2d');
        
        // Data dari PHP
        const weeklyData = <?= json_encode($data['weekly_consumption']); ?>;
        const labels = weeklyData.map(item => {
            const date = new Date(item.date);
            return date.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric' });
        });
        const values = weeklyData.map(item => item.total_energy);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Konsumsi (kWh)',
                    data: values,
                    backgroundColor: '#5B5FEF',
                    borderRadius: 12,
                    hoverBackgroundColor: '#4A4ED9',
                    barThickness: 32
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1E1E2D',
                        padding: 12,
                        titleFont: { size: 12, family: "'Outfit', sans-serif" },
                        bodyFont: { size: 14, weight: 'bold', family: "'Outfit', sans-serif" },
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y.toFixed(2) + ' kWh';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f3f4f6', drawBorder: false },
                        ticks: { 
                            font: { family: "'Outfit', sans-serif" },
                            color: '#9ca3af',
                            callback: function(value) { return value + ' kWh'; }
                        }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { font: { family: "'Outfit', sans-serif" }, color: '#9ca3af' }
                    }
                }
            }
        });
    });
</script>
