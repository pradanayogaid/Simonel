<div class="p-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold">Riwayat Data</h1>
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
                    <?php if (empty($data['logs'])) : ?>
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-400">
                            <i class='bx bx-data text-4xl mb-2'></i>
                            <p>Belum ada data log yang tercatat.</p>
                        </td>
                    </tr>
                    <?php else : ?>
                        <?php foreach ($data['logs'] as $log) : ?>
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-700"><?= date('H:i:s', strtotime($log['created_at'])); ?></div>
                                <div class="text-[10px] text-gray-400 font-medium uppercase"><?= date('d M Y', strtotime($log['created_at'])); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-[#5B5FEF] flex items-center justify-center">
                                        <i class='bx bxs-microchip'></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-700"><?= htmlspecialchars($log['device_name']); ?></div>
                                        <div class="text-[10px] text-gray-400 font-bold"><?= htmlspecialchars($log['device_id']); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-700"><?= number_format($log['voltage'], 1); ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-700"><?= number_format($log['current'], 2); ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-[#5B5FEF]"><?= number_format($log['daya_nyata'], 1); ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-indigo-400"><?= number_format($log['daya_semu'], 1); ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-500"><?= number_format($log['daya_reaktif'], 1); ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if (!empty($data['logs'])) : ?>
        <div class="px-6 py-5 bg-gray-50/50 border-t border-gray-50 flex justify-between items-center">
            <p class="text-xs text-gray-400 font-medium italic">Menampilkan 100 data terbaru</p>
            <div class="flex gap-2">
                <button class="p-2 px-4 bg-white rounded-xl border border-gray-100 text-xs font-bold text-gray-400 cursor-not-allowed">Sebelumnya</button>
                <button class="p-2 px-4 bg-white rounded-xl border border-gray-100 text-xs font-bold text-gray-400 cursor-not-allowed">Selanjutnya</button>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
