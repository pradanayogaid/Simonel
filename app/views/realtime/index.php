<div class="p-8" x-data="realtimeMonitor()">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold">Pemantauan Realtime</h1>
            <p class="text-gray-500">Siaran data langsung dari perangkat IoT Anda</p>
        </div>
        
        <?php if (!empty($data['devices'])) : ?>
        <div class="relative" x-data="{ open: false }">
            <!-- Custom Dropdown Trigger -->
            <div @click="open = !open" class="flex items-center gap-4 bg-white p-3 px-5 rounded-2xl shadow-sm border border-gray-100 cursor-pointer hover:border-[#5B5FEF] transition-all min-w-[240px]">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-[#5B5FEF] shrink-0">
                    <i class='bx bxs-microchip text-xl'></i>
                </div>
                <div class="flex-1 overflow-hidden">
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
                    <div class="flex-1 overflow-hidden">
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
    <div class="flex items-center gap-4 mb-6">
        <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-full shadow-sm border border-gray-100 text-sm font-bold" :class="isConnected ? 'text-green-500' : 'text-red-500'">
            <div class="w-2.5 h-2.5 rounded-full" :class="isConnected ? 'bg-green-500 animate-pulse' : 'bg-red-500'"></div>
            <span x-text="isConnected ? 'ONLINE' : 'OFFLINE'"></span>
        </div>
        <div class="text-sm text-gray-400 font-medium">
            Pembaruan terakhir: <span x-text="lastUpdate">Menunggu data...</span>
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
                        <h3 class="text-4xl font-black text-gray-800" x-text="sensorData.apparent_power">0.0</h3>
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
                    <p class="text-sm font-bold text-gray-700 mt-1"><span x-text="sensorStats.apparent_power?.avg || '0.0'"></span></p>
                </div>
                <div class="text-center border-l border-r border-gray-50">
                    <p class="text-[10px] text-gray-400 font-bold uppercase">Min</p>
                    <p class="text-sm font-bold text-gray-700 mt-1"><span x-text="sensorStats.apparent_power?.min || '0.0'"></span></p>
                </div>
                <div class="text-center">
                    <p class="text-[10px] text-gray-400 font-bold uppercase">Max</p>
                    <p class="text-sm font-bold text-gray-700 mt-1"><span x-text="sensorStats.apparent_power?.max || '0.0'"></span></p>
                </div>
            </div>
        </div>

        <!-- Daya Reaktif (Reactive Power) -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative group hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-gray-400 text-sm font-bold uppercase tracking-wider mb-1">Daya Reaktif</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-4xl font-black text-gray-800" x-text="sensorData.reactive_power">0.0</h3>
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
                    <p class="text-sm font-bold text-gray-700 mt-1"><span x-text="sensorStats.reactive_power?.avg || '0.0'"></span></p>
                </div>
                <div class="text-center border-l border-r border-gray-50">
                    <p class="text-[10px] text-gray-400 font-bold uppercase">Min</p>
                    <p class="text-sm font-bold text-gray-700 mt-1"><span x-text="sensorStats.reactive_power?.min || '0.0'"></span></p>
                </div>
                <div class="text-center">
                    <p class="text-[10px] text-gray-400 font-bold uppercase">Max</p>
                    <p class="text-sm font-bold text-gray-700 mt-1"><span x-text="sensorStats.reactive_power?.max || '0.0'"></span></p>
                </div>
            </div>
        </div>

        <!-- Daya Nyata (Active Power) -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative group hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-gray-400 text-sm font-bold uppercase tracking-wider mb-1">Daya Nyata</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-4xl font-black text-gray-800" x-text="sensorData.power">0.0</h3>
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
                    <p class="text-sm font-bold text-gray-700 mt-1"><span x-text="sensorStats.power?.avg || '0.0'"></span></p>
                </div>
                <div class="text-center border-l border-r border-gray-50">
                    <p class="text-[10px] text-gray-400 font-bold uppercase">Min</p>
                    <p class="text-sm font-bold text-gray-700 mt-1"><span x-text="sensorStats.power?.min || '0.0'"></span></p>
                </div>
                <div class="text-center">
                    <p class="text-[10px] text-gray-400 font-bold uppercase">Max</p>
                    <p class="text-sm font-bold text-gray-700 mt-1"><span x-text="sensorStats.power?.max || '0.0'"></span></p>
                </div>
            </div>
        </div>

    </div>

    <!-- Power Chart -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 mt-8 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">Tren Penggunaan Daya Nyata</h2>
            <div class="flex gap-2">
                <span class="px-3 py-1 bg-indigo-50 text-[#5B5FEF] text-xs font-bold rounded-lg">Realtime</span>
            </div>
        </div>
        <div class="relative h-72 w-full">
            <canvas id="powerChart"></canvas>
        </div>
    </div>

    <script>
        function realtimeMonitor() {
            return {
                apiKey: '<?= $data["selected_device"]["api_key"]; ?>',
                apiUrl: '<?= BASEURL; ?>/api/fetch',
                isConnected: false,
                lastUpdate: '-',
                pollingInterval: null,
                chartInstance: null,
                sensorData: {
                    voltage: 0,
                    current: 0,
                    power: 0,
                    apparent_power: 0,
                    reactive_power: 0
                },
                sensorStats: {
                    voltage: { avg: 0, min: 0, max: 0 },
                    current: { avg: 0, min: 0, max: 0 },
                    power: { avg: 0, min: 0, max: 0 },
                    apparent_power: { avg: 0, min: 0, max: 0 },
                    reactive_power: { avg: 0, min: 0, max: 0 }
                },
                init() {
                    this.initChart();
                    this.fetchData();
                    // Poll every 2 seconds
                    this.pollingInterval = setInterval(() => {
                        this.fetchData();
                    }, 2000);
                },
                initChart() {
                    const ctx = document.getElementById('powerChart').getContext('2d');
                    
                    // Create gradient
                    let gradient = ctx.createLinearGradient(0, 0, 0, 300);
                    gradient.addColorStop(0, 'rgba(91, 95, 239, 0.2)');
                    gradient.addColorStop(1, 'rgba(91, 95, 239, 0)');

                    this.chartInstance = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: [],
                            datasets: [{
                                label: 'Active Power (W)',
                                data: [],
                                borderColor: '#5B5FEF',
                                backgroundColor: gradient,
                                borderWidth: 3,
                                pointBackgroundColor: '#fff',
                                pointBorderColor: '#5B5FEF',
                                pointBorderWidth: 2,
                                pointRadius: 4,
                                pointHoverRadius: 6,
                                fill: true,
                                tension: 0.4 // Smooth curves
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
                                            return context.parsed.y + ' W';
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
                            animation: { duration: 0 } // Disable animation for snappy realtime updates
                        }
                    });
                },
                updateChart(powerValue, timestamp) {
                    if (!this.chartInstance) return;
                    
                    const timeLabel = new Date(timestamp).toLocaleTimeString([], { hour12: false });
                    const data = this.chartInstance.data.datasets[0].data;
                    const labels = this.chartInstance.data.labels;
                    
                    // Only push if time is different (avoid duplicating same data if polling is faster than data arrival)
                    if (labels.length === 0 || labels[labels.length - 1] !== timeLabel) {
                        labels.push(timeLabel);
                        data.push(powerValue);
                        
                        // Keep last 15 data points
                        if (labels.length > 15) {
                            labels.shift();
                            data.shift();
                        }
                        this.chartInstance.update();
                    }
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
                            this.isConnected = true;
                            
                            // Format timestamp nicely
                            const date = new Date(result.data.last_update);
                            this.lastUpdate = date.toLocaleTimeString([], { hour12: false }) + ' (' + date.toLocaleDateString() + ')';
                            
                            // Update Chart
                            this.updateChart(result.data.power, result.data.last_update);
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
