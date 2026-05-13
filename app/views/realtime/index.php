<div class="p-8" x-data="realtimeMonitor()">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold">Pemantauan Realtime</h1>
            <p class="text-gray-500">Siaran data langsung dari perangkat IoT Anda</p>
        </div>
        
        <?php if (!empty($data['devices'])) : ?>
        <div class="relative w-full md:w-auto" x-data="{ open: false }">
            <!-- Custom Dropdown Trigger -->
            <div @click="open = !open" class="flex items-center gap-4 bg-white p-3 px-5 rounded-2xl shadow-sm border border-gray-100 cursor-pointer hover:border-[#5B5FEF] transition-all w-full md:min-w-[240px]">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-[#5B5FEF] shrink-0">
                    <i class='bx bxs-microchip text-xl'></i>
                </div>
                <div class="flex-1 overflow-hidden text-left">
                    <div class="font-bold text-gray-800 truncate leading-tight"><?= htmlspecialchars($data['selected_device']['device_name']); ?></div>
                    <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5"><?= htmlspecialchars($data['selected_device']['device_code']); ?></div>
                </div>
                <i class='bx bx-chevron-down text-gray-400 transition-transform duration-200' :class="open ? 'rotate-180' : ''"></i>
            </div>

            <!-- Dropdown Menu -->
            <div x-show="open" 
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="absolute right-0 top-full mt-2 w-full bg-white rounded-3xl shadow-xl border border-gray-100 z-50 overflow-hidden p-2">
                
                <?php foreach ($data['devices'] as $device) : ?>
                <div @click="changeDevice('<?= $device['id']; ?>')" 
                     class="flex items-center gap-4 p-3 rounded-2xl cursor-pointer hover:bg-gray-50 transition-colors <?= ($data['selected_device'] && $data['selected_device']['id'] == $device['id']) ? 'bg-indigo-50/50' : ''; ?>">
                    <div class="w-10 h-10 rounded-xl <?= ($data['selected_device'] && $data['selected_device']['id'] == $device['id']) ? 'bg-[#5B5FEF] text-white' : 'bg-gray-100 text-gray-400'; ?> flex items-center justify-center shrink-0">
                        <i class='bx bxs-microchip text-xl'></i>
                    </div>
                    <div class="flex-1 overflow-hidden text-left">
                        <div class="font-bold text-gray-700 truncate leading-tight"><?= htmlspecialchars($device['device_name']); ?></div>
                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5"><?= htmlspecialchars($device['device_code']); ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <?php if (empty($data['selected_device'])) : ?>
        <div class="bg-yellow-50 text-yellow-600 p-6 rounded-3xl text-center border border-yellow-100">
            <i class='bx bx-error-circle text-4xl mb-2'></i>
            <h2 class="text-xl font-bold">Tidak Ada Perangkat</h2>
            <p class="mt-1">Silakan tambahkan perangkat di menu Manajemen Perangkat terlebih dahulu.</p>
            <a href="<?= BASEURL; ?>/device/add" class="inline-block mt-4 bg-yellow-500 text-white px-6 py-2 rounded-full font-bold hover:bg-yellow-600">Tambah Perangkat</a>
        </div>
    <?php else : ?>

    <!-- Status & Connection info -->
    <div class="flex items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-white rounded-full shadow-sm border border-gray-100 text-sm font-bold" :class="isConnected ? 'text-green-500' : 'text-red-500'">
                <div class="w-2.5 h-2.5 rounded-full" :class="isConnected ? 'bg-green-500 animate-pulse' : 'bg-red-500'"></div>
                <span class="hidden sm:inline" x-text="isConnected ? 'ONLINE' : 'OFFLINE'"></span>
            </div>
            <div class="hidden md:flex items-center gap-2 text-xs sm:text-sm text-gray-400 font-medium">
                <i class='bx bx-time-five text-[#5B5FEF] text-lg lg:hidden' x-show="lastUpdate !== 'Tidak ada data dalam 24 jam terakhir'"></i>
                <span class="hidden md:inline">Updated :</span>
                <span x-text="lastUpdate" 
                      :class="lastUpdate === 'Tidak ada data dalam 24 jam terakhir' ? 'hidden md:inline' : 'inline'"
                      class="font-bold sm:font-medium">Menunggu...</span>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <div class="relative transition-all duration-300 flex items-center" 
                 :class="selectedDate ? 'w-auto' : 'w-11 lg:w-11'">
                
                <!-- Unified Custom Icon UI (Visible on all devices) -->
                <div class="h-11 flex items-center bg-white border border-gray-200 rounded-2xl shadow-sm px-3 gap-2 overflow-hidden transition-all duration-300 shrink-0">
                    <i class='bx bx-calendar text-[#5B5FEF] text-xl'></i>
                    <span x-show="selectedDate" x-transition class="text-xs lg:text-sm font-bold text-gray-700 whitespace-nowrap" x-text="formatDateLabel(selectedDate)"></span>
                </div>

                <!-- The Actual Date Input (Hidden but fills the area to catch clicks) -->
                <input type="date"
                       id="chartDatePicker"
                       x-model="selectedDate"
                       @change="onDateChange()"
                       :max="todayDate"
                       class="absolute inset-0 opacity-0 cursor-pointer w-full h-full [&::-webkit-calendar-picker-indicator]:block [&::-webkit-calendar-picker-indicator]:absolute [&::-webkit-calendar-picker-indicator]:inset-0 [&::-webkit-calendar-picker-indicator]:w-full [&::-webkit-calendar-picker-indicator]:h-full [&::-webkit-calendar-picker-indicator]:cursor-pointer [&::-webkit-calendar-picker-indicator]:opacity-0">
            </div>
            <button @click="resetToLive()"
                    x-show="selectedDate"
                    class="flex items-center gap-1.5 px-3 sm:px-4 py-2 bg-indigo-50 text-[#5B5FEF] text-sm font-bold rounded-2xl hover:bg-indigo-100 transition-all">
                <i class='bx bx-revision'></i>
                <span class="hidden sm:inline">Live</span>
            </button>
        </div>
    </div>

    <!-- Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
        
        <!-- Tegangan (Voltage) -->
        <div class="bg-[#5B5FEF] rounded-3xl p-6 shadow-lg relative group hover:shadow-xl transition-shadow text-white overflow-hidden xl:col-span-2">
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div>
                    <p class="text-indigo-200 text-sm font-bold uppercase tracking-wider mb-1">Tegangan</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-4xl font-black" x-text="sensorData.voltage">0.0</h3>
                        <span class="text-indigo-200 font-bold">V AC</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <i class='bx bxs-bolt'></i>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-2 mt-6 pt-4 border-t border-indigo-400/30 relative z-10">
                <div class="text-center">
                    <p class="text-[10px] text-indigo-200 font-bold uppercase">AVG</p>
                    <p class="text-sm font-bold mt-1"><span x-text="sensorStats.voltage?.avg || '0.0'"></span></p>
                </div>
                <div class="text-center border-l border-r border-indigo-400/30">
                    <p class="text-[10px] text-indigo-200 font-bold uppercase">Min</p>
                    <p class="text-sm font-bold mt-1"><span x-text="sensorStats.voltage?.min || '0.0'"></span></p>
                </div>
                <div class="text-center">
                    <p class="text-[10px] text-indigo-200 font-bold uppercase">Max</p>
                    <p class="text-sm font-bold mt-1"><span x-text="sensorStats.voltage?.max || '0.0'"></span></p>
                </div>
            </div>
            <i class='bx bxs-zap absolute -bottom-6 -right-6 text-8xl opacity-10'></i>
        </div>

        <!-- Arus (Current) -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative group hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-gray-400 text-sm font-bold uppercase tracking-wider mb-1">Arus</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-4xl font-black text-gray-800" x-text="sensorData.current">0.00</h3>
                        <span class="text-gray-500 font-bold">A</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <i class='bx bx-water'></i>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-2 mt-6 pt-4 border-t border-gray-50">
                <div class="text-center">
                    <p class="text-[10px] text-gray-400 font-bold uppercase">AVG</p>
                    <p class="text-sm font-bold text-gray-700 mt-1"><span x-text="sensorStats.current?.avg || '0.00'"></span></p>
                </div>
                <div class="text-center border-l border-r border-gray-50">
                    <p class="text-[10px] text-gray-400 font-bold uppercase">Min</p>
                    <p class="text-sm font-bold text-gray-700 mt-1"><span x-text="sensorStats.current?.min || '0.00'"></span></p>
                </div>
                <div class="text-center">
                    <p class="text-[10px] text-gray-400 font-bold uppercase">Max</p>
                    <p class="text-sm font-bold text-gray-700 mt-1"><span x-text="sensorStats.current?.max || '0.00'"></span></p>
                </div>
            </div>
        </div>

        <!-- Daya Semu (Apparent Power) -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative group hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-gray-400 text-sm font-bold uppercase tracking-wider mb-1">Daya Semu</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-4xl font-black text-gray-800" x-text="sensorData.daya_semu">0.0</h3>
                        <span class="text-gray-500 font-bold">VA</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <i class='bx bx-hive'></i>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-2 mt-6 pt-4 border-t border-gray-50">
                <div class="text-center">
                    <p class="text-[10px] text-gray-400 font-bold uppercase">AVG</p>
                    <p class="text-sm font-bold text-gray-700 mt-1"><span x-text="sensorStats.daya_semu?.avg || '0.0'"></span></p>
                </div>
                <div class="text-center border-l border-r border-gray-50">
                    <p class="text-[10px] text-gray-400 font-bold uppercase">Min</p>
                    <p class="text-sm font-bold text-gray-700 mt-1"><span x-text="sensorStats.daya_semu?.min || '0.0'"></span></p>
                </div>
                <div class="text-center">
                    <p class="text-[10px] text-gray-400 font-bold uppercase">Max</p>
                    <p class="text-sm font-bold text-gray-700 mt-1"><span x-text="sensorStats.daya_semu?.max || '0.0'"></span></p>
                </div>
            </div>
        </div>

        <!-- Daya Reaktif (Reactive Power) -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative group hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-gray-400 text-sm font-bold uppercase tracking-wider mb-1">Daya Reaktif</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-4xl font-black text-gray-800" x-text="sensorData.daya_reaktif">0.0</h3>
                        <span class="text-gray-500 font-bold">VAR</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <i class='bx bx-pulse'></i>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-2 mt-6 pt-4 border-t border-gray-50">
                <div class="text-center">
                    <p class="text-[10px] text-gray-400 font-bold uppercase">AVG</p>
                    <p class="text-sm font-bold text-gray-700 mt-1"><span x-text="sensorStats.daya_reaktif?.avg || '0.0'"></span></p>
                </div>
                <div class="text-center border-l border-r border-gray-50">
                    <p class="text-[10px] text-gray-400 font-bold uppercase">Min</p>
                    <p class="text-sm font-bold text-gray-700 mt-1"><span x-text="sensorStats.daya_reaktif?.min || '0.0'"></span></p>
                </div>
                <div class="text-center">
                    <p class="text-[10px] text-gray-400 font-bold uppercase">Max</p>
                    <p class="text-sm font-bold text-gray-700 mt-1"><span x-text="sensorStats.daya_reaktif?.max || '0.0'"></span></p>
                </div>
            </div>
        </div>

        <!-- Daya Nyata (Active Power) -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative group hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-gray-400 text-sm font-bold uppercase tracking-wider mb-1">Daya Nyata</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-4xl font-black text-gray-800" x-text="sensorData.daya_nyata">0.0</h3>
                        <span class="text-gray-500 font-bold">W</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-[#5B5FEF] flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <i class='bx bxs-tachometer'></i>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-2 mt-6 pt-4 border-t border-gray-50">
                <div class="text-center">
                    <p class="text-[10px] text-gray-400 font-bold uppercase">AVG</p>
                    <p class="text-sm font-bold text-gray-700 mt-1"><span x-text="sensorStats.daya_nyata?.avg || '0.0'"></span></p>
                </div>
                <div class="text-center border-l border-r border-gray-50">
                    <p class="text-[10px] text-gray-400 font-bold uppercase">Min</p>
                    <p class="text-sm font-bold text-gray-700 mt-1"><span x-text="sensorStats.daya_nyata?.min || '0.0'"></span></p>
                </div>
                <div class="text-center">
                    <p class="text-[10px] text-gray-400 font-bold uppercase">Max</p>
                    <p class="text-sm font-bold text-gray-700 mt-1"><span x-text="sensorStats.daya_nyata?.max || '0.0'"></span></p>
                </div>
            </div>
        </div>

    </div>


    <!-- Realtime Charts Grid -->
    <div class="grid grid-cols-1 gap-6 mb-10">
        <?php 
        $charts = [
            ['id' => 'voltage', 'title' => 'Tegangan (Voltage)', 'unit' => 'V AC', 'color' => '#5B5FEF'],
            ['id' => 'current', 'title' => 'Arus (Current)', 'unit' => 'A', 'color' => '#F59E0B'],
            ['id' => 'daya_nyata', 'title' => 'Daya Nyata (Active Power)', 'unit' => 'W', 'color' => '#10B981'],
            ['id' => 'daya_semu', 'title' => 'Daya Semu (Apparent Power)', 'unit' => 'VA', 'color' => '#8B5CF6'],
            ['id' => 'daya_reaktif', 'title' => 'Daya Reaktif (Reactive Power)', 'unit' => 'VAR', 'color' => '#06B6D4'],
        ];
        foreach ($charts as $c) : ?>
        <div x-data="{ open: true }" class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 group transition-all">
            <div @click="open = !open" class="flex justify-between items-center cursor-pointer">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white" style="background-color: <?= $c['color']; ?>">
                        <i class='bx bx-line-chart text-xl'></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">
                        <span class="md:hidden">Tren <?= preg_replace('/\s*\(.*?\)/', '', $c['title']); ?></span>
                        <span class="hidden md:inline">Tren <?= $c['title']; ?></span>
                    </h2>
                </div>
                <div class="flex items-center gap-3">
                    <span class="hidden md:inline-block px-3 py-1 bg-gray-50 text-gray-400 text-[10px] font-bold rounded-lg uppercase tracking-widest">Realtime</span>
                    <i class='bx text-2xl text-gray-300 transition-transform duration-300' :class="open ? 'bx-chevron-up' : 'bx-chevron-down'"></i>
                </div>
            </div>
            
            <div :style="open ? 'max-height: 400px; overflow: visible;' : 'max-height: 0; overflow: hidden;'"
                 style="transition: max-height 0.3s ease; max-height: 400px; overflow: visible;"
                 class="mt-6">
                <div class="relative h-72 w-full">
                    <canvas id="<?= $c['id']; ?>Chart"></canvas>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <script>
        // Store charts outside Alpine to prevent reactivity recursion
        window._chartInstances = {};

        function realtimeMonitor() {
            return {
                apiKey: '<?= $data["selected_device"]["api_key"]; ?>',
                apiUrl: '<?= BASEURL; ?>/api/fetch',
                isConnected: false,
                lastUpdate: '-',
                pollingInterval: null,
                sensorData: {
                    voltage: 0,
                    current: 0,
                    daya_nyata: 0,
                    daya_semu: 0,
                    daya_reaktif: 0
                },
                sensorStats: {
                    voltage: { avg: 0, min: 0, max: 0 },
                    current: { avg: 0, min: 0, max: 0 },
                    daya_nyata: { avg: 0, min: 0, max: 0 },
                    daya_semu: { avg: 0, min: 0, max: 0 },
                    daya_reaktif: { avg: 0, min: 0, max: 0 }
                },
                selectedDate: '',
                todayDate: new Date().toISOString().split('T')[0],
                init() {
                    this.$nextTick(() => {
                        this.initCharts();
                        this.loadHistory();
                        this.fetchData();
                        // Poll every 5 seconds
                        this.pollingInterval = setInterval(() => {
                            this.fetchData();
                        }, 5000);
                    });
                },
                async loadHistory(date = null) {
                    try {
                        const dateParam = date ? `&date=${date}` : '';
                        const response = await fetch(`<?= BASEURL; ?>/api/history?api_key=${this.apiKey}${dateParam}`);
                        const result = await response.json();
                        if (result.status !== 'success' || !result.labels || result.labels.length === 0) {
                            // Clear charts when no data
                            ['voltage', 'current', 'daya_nyata', 'daya_semu', 'daya_reaktif'].forEach(metric => {
                                const chart = window._chartInstances[metric];
                                if (!chart) return;
                                chart.data.labels = [];
                                chart.data.datasets[0].data = [];
                                chart.update();
                            });
                            // Also zero out cards
                            this.sensorData = { voltage: 0, current: 0, daya_nyata: 0, daya_semu: 0, daya_reaktif: 0 };
                            this.sensorStats = {
                                voltage: { avg: 0, min: 0, max: 0 },
                                current: { avg: 0, min: 0, max: 0 },
                                daya_nyata: { avg: 0, min: 0, max: 0 },
                                daya_semu: { avg: 0, min: 0, max: 0 },
                                daya_reaktif: { avg: 0, min: 0, max: 0 }
                            };
                            return;
                        }

                        const metrics = ['voltage', 'current', 'daya_nyata', 'daya_semu', 'daya_reaktif'];
                        const labels = result.labels.map(ts => {
                            const d = new Date(ts);
                            return d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0');
                        });

                        metrics.forEach(metric => {
                            const chart = window._chartInstances[metric];
                            if (!chart) return;
                            chart.data.labels = [...labels];
                            chart.data.datasets[0].data = [...result[metric]];
                            chart.update();
                        });

                        // Update metric cards from history response
                        if (result.latest) {
                            this.sensorData = result.latest;
                        }
                        if (result.stats) {
                            this.sensorStats = result.stats;
                        }
                    } catch (e) {
                        console.warn('History load failed:', e);
                    }
                },
                onDateChange() {
                    if (this.pollingInterval) {
                        clearInterval(this.pollingInterval);
                        this.pollingInterval = null;
                    }
                    this.loadHistory(this.selectedDate);
                },
                resetToLive() {
                    this.selectedDate = '';
                    this.loadHistory();
                    if (!this.pollingInterval) {
                        this.pollingInterval = setInterval(() => {
                            this.fetchData();
                        }, 5000);
                    }
                },
                formatDateLabel(dateStr) {
                    if (!dateStr) return '';
                    const d = new Date(dateStr + 'T00:00:00');
                    const bulanNames = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
                    return `${d.getDate()} ${bulanNames[d.getMonth()]} ${d.getFullYear()}`;
                },
                initCharts() {
                    const configs = [
                        { id: 'voltage', label: 'Voltage', unit: 'V AC', color: '#5B5FEF' },
                        { id: 'current', label: 'Current', unit: 'A', color: '#F59E0B' },
                        { id: 'daya_nyata', label: 'Active Power', unit: 'W', color: '#10B981' },
                        { id: 'daya_semu', label: 'Apparent Power', unit: 'VA', color: '#8B5CF6' },
                        { id: 'daya_reaktif', label: 'Reactive Power', unit: 'VAR', color: '#06B6D4' }
                    ];

                    configs.forEach(config => {
                        const el = document.getElementById(`${config.id}Chart`);
                        if (!el) return;
                        
                        const ctx = el.getContext('2d');
                        
                        let gradient = ctx.createLinearGradient(0, 0, 0, 300);
                        gradient.addColorStop(0, `${config.color}33`); // 20% opacity
                        gradient.addColorStop(1, `${config.color}00`); // 0% opacity

                        window._chartInstances[config.id] = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: [],
                                datasets: [{
                                    label: `${config.label} (${config.unit})`,
                                    data: [],
                                    borderColor: config.color,
                                    backgroundColor: gradient,
                                    borderWidth: 2.5,
                                    pointRadius: 0,
                                    pointHoverRadius: 0,
                                    fill: true,
                                    tension: 0.4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: 'rgba(0,0,0,0.8)',
                                        padding: 12,
                                        titleFont: { size: 13, family: "'Inter', sans-serif" },
                                        bodyFont: { size: 14, weight: 'bold', family: "'Inter', sans-serif" },
                                        displayColors: false,
                                        callbacks: {
                                            label: function(context) {
                                                return context.parsed.y + ' ' + config.unit;
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        grid: { color: '#f3f4f6', drawBorder: false },
                                        ticks: { font: { family: "'Inter', sans-serif" }, color: '#9ca3af' }
                                    },
                                    x: {
                                        grid: { display: false, drawBorder: false },
                                        ticks: { font: { family: "'Inter', sans-serif" }, color: '#9ca3af' }
                                    }
                                },
                                animation: { duration: 0 }
                            }
                        });
                    });
                },
                updateAllCharts(data, timestamp) {
                    const d = new Date(timestamp);
                    const timeLabel = d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0');
                    const metrics = ['voltage', 'current', 'daya_nyata', 'daya_semu', 'daya_reaktif'];
                    
                    metrics.forEach(metric => {
                        const chart = window._chartInstances[metric];
                        if (!chart) return;

                        const chartData = chart.data.datasets[0].data;
                        const labels = chart.data.labels;
                        
                        if (labels.length === 0 || labels[labels.length - 1] !== timeLabel) {
                            labels.push(timeLabel);
                            chartData.push(data[metric]);
                            
                            if (labels.length > 20) {
                                labels.shift();
                                chartData.shift();
                            }
                            chart.update();
                        }
                    });
                },
                async fetchData() {
                    try {
                        const response = await fetch(`${this.apiUrl}?api_key=${this.apiKey}`);
                        const result = await response.json();

                        if (result.status === 'success' && result.data) {
                            this.sensorData = result.data;
                            if (result.stats) {
                                this.sensorStats = result.stats;
                            }
                            // Status is calculated from server side threshold
                            this.isConnected = (result.device_status === 'ONLINE');
                            
                            // Format timestamp nicely
                            if (result.data.last_update) {
                                const date = new Date(result.data.last_update);
                                const jam = date.getHours().toString().padStart(2, '0');
                                const menit = date.getMinutes().toString().padStart(2, '0');
                                
                                // Deteksi Label Zona Waktu Indonesia
                                const offset = -date.getTimezoneOffset() / 60;
                                let tzLabel = '';
                                if (offset === 7) tzLabel = 'WIB';
                                else if (offset === 8) tzLabel = 'WITA';
                                else if (offset === 9) tzLabel = 'WIT';
                                else tzLabel = 'GMT' + (offset >= 0 ? '+' : '') + offset;

                                const hari = date.getDate();
                                const bulanNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                                const bulan = bulanNames[date.getMonth()];
                                const tahun = date.getFullYear();

                                this.lastUpdate = `${jam}.${menit} ${tzLabel} - ${hari} ${bulan} ${tahun}`;
                            } else {
                                this.lastUpdate = 'Tidak ada data dalam 24 jam terakhir';
                            }
                            
                            // Update Charts
                            this.updateAllCharts(result.data, result.data.last_update);
                        } else {
                            this.isConnected = false;
                        }
                    } catch (error) {
                        console.error("Fetch error:", error);
                        this.isConnected = false;
                    }
                },
                changeDevice(deviceId) {
                    window.location.href = '<?= BASEURL; ?>/realtime?device=' + deviceId;
                }
            }
        }
    </script>
    <?php endif; ?>
</div>
