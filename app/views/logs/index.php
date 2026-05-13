<div class="p-4 sm:p-8" x-data="logsPage()">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold">Riwayat Data</h1>
            <p class="text-gray-500">Log aktivitas sensor dari seluruh perangkat</p>
        </div>
        <div class="flex gap-2">
            <button onclick="window.location.href='<?= BASEURL; ?>/log'" class="p-3 bg-white rounded-2xl shadow-sm border border-gray-100 text-gray-500 hover:text-[#5B5FEF] transition-all">
                <i class='bx bx-refresh text-xl'></i>
            </button>
            <form action="<?= BASEURL; ?>/log" method="GET" class="relative">
                <input type="text" name="search" placeholder="Cari log..." value="<?= $data['search'] ?? ''; ?>" class="pl-10 pr-4 py-3 bg-white rounded-2xl shadow-sm border border-gray-100 focus:outline-none focus:border-[#5B5FEF] transition-all">
                <i class='bx bx-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400'></i>
            </form>
        </div>
    </div>

    <!-- Logs Table Container -->
    <div class="bg-white rounded-[40px] shadow-sm border border-gray-50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Waktu</th>
                        <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Perangkat</th>
                        <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tegangan (V)</th>
                        <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Arus (A)</th>
                        <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Daya Nyata (W)</th>
                        <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Daya Semu (VA)</th>
                        <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Daya Reaktif (VAR)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <template x-if="logs.length === 0">
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-400">
                                <i class='bx bx-data text-4xl mb-2'></i>
                                <p>Belum ada data log yang tercatat.</p>
                            </td>
                        </tr>
                    </template>
                    <template x-for="(log, index) in paginatedLogs" :key="index">
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-700" x-text="formatDateTime(log.created_at).time"></div>
                                <div class="text-[10px] text-gray-400 font-medium uppercase" x-text="formatDateTime(log.created_at).date"></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-[#5B5FEF] flex items-center justify-center">
                                        <i class='bx bxs-microchip'></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-700" x-text="log.device_name"></div>
                                        <div class="text-[10px] text-gray-400 font-bold" x-text="log.device_id"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-700" x-text="Number(log.voltage || 0).toFixed(1)"></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-700" x-text="Number(log.current || 0).toFixed(2)"></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-[#5B5FEF]" x-text="Number(log.daya_nyata || 0).toFixed(1)"></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-indigo-400" x-text="Number(log.daya_semu || 0).toFixed(1)"></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-500" x-text="Number(log.daya_reaktif || 0).toFixed(1)"></span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Controls -->
        <div class="p-4 sm:p-6 bg-gray-50/50 border-t border-gray-50 flex flex-col items-center gap-4" x-show="logs.length > 0">
            <div class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-widest text-center">
                Menampilkan <span class="text-gray-700" x-text="startRange"></span> - <span class="text-gray-700" x-text="endRange"></span> 
                dari <span class="text-[#5B5FEF]" x-text="totalLogs"></span> data
            </div>
            
            <div class="flex items-center justify-center gap-1 sm:gap-2 w-full max-w-sm sm:max-w-none">
                <button @click="currentPage = 1" :disabled="currentPage === 1"
                        class="w-10 h-10 flex items-center justify-center bg-white rounded-xl border border-gray-100 text-gray-400 disabled:opacity-30 transition-all shrink-0">
                    <i class='bx bx-chevrons-left text-lg'></i>
                </button>
                
                <button @click="currentPage--" :disabled="currentPage === 1"
                        class="flex-1 sm:flex-none h-10 px-2 sm:px-4 flex items-center justify-center bg-white rounded-xl border border-gray-100 text-gray-400 disabled:opacity-30 transition-all">
                    <i class='bx bx-chevron-left text-xl sm:hidden'></i>
                    <span class="hidden sm:inline text-xs font-bold">Sebelumnya</span>
                </button>
                
                <div class="sm:hidden flex-1 text-center font-black text-sm text-gray-700 px-2">
                    <span x-text="currentPage"></span> <span class="text-gray-300 font-normal">/</span> <span x-text="totalPages"></span>
                </div>

                <div class="hidden sm:flex items-center gap-1 px-2">
                    <template x-for="p in visiblePages" :key="p">
                        <button @click="currentPage = p"
                                :class="currentPage === p ? 'bg-[#5B5FEF] text-white shadow-md shadow-indigo-100' : 'bg-white text-gray-400'"
                                class="w-9 h-9 rounded-xl text-xs font-bold transition-all" x-text="p">
                        </button>
                    </template>
                </div>

                <button @click="currentPage++" :disabled="currentPage === totalPages"
                        class="flex-1 sm:flex-none h-10 px-2 sm:px-4 flex items-center justify-center bg-white rounded-xl border border-gray-100 text-gray-400 disabled:opacity-30 transition-all">
                    <i class='bx bx-chevron-right text-xl sm:hidden'></i>
                    <span class="hidden sm:inline text-xs font-bold">Selanjutnya</span>
                </button>
                
                <button @click="currentPage = totalPages" :disabled="currentPage === totalPages"
                        class="w-10 h-10 flex items-center justify-center bg-white rounded-xl border border-gray-100 text-gray-400 disabled:opacity-30 transition-all shrink-0">
                    <i class='bx bx-chevrons-right text-lg'></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function logsPage() {
    return {
        logs: <?= json_encode($data['logs']); ?>,
        currentPage: 1,
        pageSize: 100,
        get totalLogs() { return this.logs.length; },
        get totalPages() { return Math.ceil(this.totalLogs / this.pageSize); },
        get startRange() { return this.logs.length ? (this.currentPage - 1) * this.pageSize + 1 : 0; },
        get endRange() { return Math.min(this.currentPage * this.pageSize, this.totalLogs); },
        get paginatedLogs() {
            const start = (this.currentPage - 1) * this.pageSize;
            return this.logs.slice(start, start + this.pageSize);
        },
        get visiblePages() {
            const pages = [];
            const delta = 2;
            const left = this.currentPage - delta;
            const right = this.currentPage + delta + 1;
            for (let i = 1; i <= this.totalPages; i++) {
                if (i === 1 || i === this.totalPages || (i >= left && i < right)) pages.push(i);
            }
            return pages;
        },
        formatDateTime(ts) {
            const d = new Date(ts);
            const date = d.getDate().toString().padStart(2, '0') + ' ' + 
                         ["JAN", "FEB", "MAR", "APR", "MEI", "JUN", "JUL", "AGU", "SEP", "OKT", "NOV", "DES"][d.getMonth()] + ' ' + 
                         d.getFullYear();
            const time = d.getHours().toString().padStart(2, '0') + ':' + 
                         d.getMinutes().toString().padStart(2, '0') + ':' + 
                         d.getSeconds().toString().padStart(2, '0');
            return { date, time };
        }
    }
}
</script>
