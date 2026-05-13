<div class="p-4 sm:p-8" x-data="{ apiGuideOpen: false, addDeviceOpen: false }">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
        <div class="flex items-center gap-4">
            <div class="hidden sm:flex w-12 h-12 sm:w-14 sm:h-14 rounded-[18px] sm:rounded-[22px] bg-white shadow-sm items-center justify-center text-[#5B5FEF] text-xl sm:text-2xl border border-gray-50">
                <i class='bx bxs-devices'></i>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-800">Manajemen Perangkat</h1>
                <p class="text-gray-400 font-medium mt-0.5">Kelola dan pantau seluruh node sensor SIMONEL</p>
            </div>
        </div>
        <div class="flex gap-3 w-full md:w-auto">
            <button @click="apiGuideOpen = true" class="flex-1 md:flex-none px-4 sm:px-6 py-4 bg-white text-gray-700 border border-gray-100 rounded-[22px] font-bold shadow-sm hover:shadow-md hover:text-[#5B5FEF] transition-all flex items-center justify-center gap-2">
                <i class='bx bxs-bulb text-xl'></i> <span class="hidden sm:inline">Panduan API</span>
            </button>
            <button @click="addDeviceOpen = true" class="flex-[2] md:flex-none px-6 py-4 bg-[#5B5FEF] text-white rounded-[22px] font-bold shadow-lg shadow-indigo-100 hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-2">
                <i class='bx bx-plus text-xl'></i> 
                <span class="sm:hidden">Add</span>
                <span class="hidden sm:inline">Tambah Perangkat</span>
            </button>
        </div>
    </div>

    <!-- API Guide Modal (Existing) -->
    <div x-show="apiGuideOpen" ...>
        <!-- ... content remains ... -->
    </div>

    <!-- Add Device Modal -->
    <div x-show="addDeviceOpen" 
         class="fixed inset-0 z-[100] overflow-y-auto"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" @click="addDeviceOpen = false"></div>

            <div class="inline-block w-full max-w-xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-[32px] sm:rounded-[40px]">
                <div class="p-6 md:p-10 relative">
                    <button @click="addDeviceOpen = false" class="absolute top-6 right-6 md:top-8 md:right-8 text-gray-400 hover:text-gray-600 transition-colors z-10">
                        <i class='bx bx-x text-2xl md:text-3xl'></i>
                    </button>

                    <div class="mb-8">
                        <span class="px-3 py-1 bg-indigo-50 text-[#5B5FEF] text-[9px] sm:text-[10px] font-black rounded-full uppercase tracking-widest mb-3 inline-block">New Connection</span>
                        <h2 class="text-2xl md:text-3xl font-black text-gray-800 leading-tight">Tambah Perangkat</h2>
                        <p class="text-gray-500 mt-2 text-sm">Daftarkan node sensor baru ke dalam sistem SIMONEL.</p>
                    </div>

                    <form action="<?= BASEURL; ?>/device/add" method="POST">
                        <div class="space-y-5">
                            <div>
                                <label for="device_code" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Device Code (Unique ID)</label>
                                <input type="text" name="device_code" id="device_code" class="block w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-[#5B5FEF]/20 focus:border-[#5B5FEF] transition-all font-bold text-gray-700" placeholder="e.g. PANEL_01" required>
                            </div>
                            <div>
                                <label for="device_name" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Device Name</label>
                                <input type="text" name="device_name" id="device_name" class="block w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-[#5B5FEF]/20 focus:border-[#5B5FEF] transition-all font-bold text-gray-700" placeholder="e.g. Main Power Panel" required>
                            </div>
                            <div>
                                <label for="location" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Location</label>
                                <input type="text" name="location" id="location" class="block w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-[#5B5FEF]/20 focus:border-[#5B5FEF] transition-all font-bold text-gray-700" placeholder="e.g. Floor 1 - Server Room" required>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row-reverse justify-end gap-4 sm:gap-3 mt-10">
                            <button type="submit" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-[#5B5FEF] text-white font-bold hover:bg-[#4a4ed8] shadow-lg shadow-indigo-100 transition-all">Create Device</button>
                            <button type="button" @click="addDeviceOpen = false" class="w-full sm:w-auto px-6 py-4 rounded-2xl text-gray-400 hover:text-gray-600 font-bold text-center transition-colors">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Devices Table Container -->
    <div class="bg-white rounded-[40px] shadow-sm border border-gray-50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Detail Perangkat</th>
                        <th class="px-8 py-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Lokasi</th>
                        <th class="px-8 py-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">API Key Security</th>
                        <th class="px-8 py-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if (empty($data['devices'])) : ?>
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 rounded-full bg-gray-50 flex items-center justify-center text-gray-300 text-4xl mb-4">
                                        <i class='bx bx-ghost'></i>
                                    </div>
                                    <h3 class="text-gray-400 font-bold">Belum ada perangkat</h3>
                                    <p class="text-gray-300 text-sm">Tambahkan perangkat pertama untuk mulai monitoring</p>
                                </div>
                            </td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($data['devices'] as $device) : ?>
                            <tr class="hover:bg-gray-50/30 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-[#5B5FEF] flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                                            <i class='bx bxs-chip'></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-700"><?= htmlspecialchars($device['device_name']); ?></div>
                                            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5"><?= htmlspecialchars($device['device_code']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-1.5 text-sm text-gray-500 font-medium">
                                        <i class='bx bx-map-pin text-gray-300 text-lg'></i>
                                        <?= htmlspecialchars($device['location']); ?>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black <?= $device['status'] == 'ONLINE' ? 'bg-green-50 text-green-500' : 'bg-gray-100 text-gray-400'; ?>">
                                        <span class="w-1.5 h-1.5 rounded-full <?= $device['status'] == 'ONLINE' ? 'bg-green-500 animate-pulse' : 'bg-gray-400'; ?>"></span>
                                        <?= $device['status']; ?>
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <code class="text-[11px] bg-gray-50 px-2 py-1 rounded text-gray-400 font-mono">••••<?= substr($device['api_key'], -6); ?></code>
                                        <button onclick="copyToClipboard('<?= $device['api_key']; ?>', 'API Key berhasil disalin!')" class="w-8 h-8 rounded-lg border border-gray-100 flex items-center justify-center text-gray-400 hover:text-[#5B5FEF] hover:border-[#5B5FEF] transition-all" title="Copy API Key">
                                            <i class='bx bx-copy'></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="<?= BASEURL; ?>/device/edit/<?= $device['id']; ?>" class="w-9 h-9 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center hover:bg-blue-500 hover:text-white transition-all">
                                            <i class='bx bx-edit-alt text-lg'></i>
                                        </a>
                                        <button onclick="confirmDeleteDevice('<?= BASEURL; ?>/device/delete/<?= $device['id']; ?>', '<?= $device['device_name']; ?>')" class="w-9 h-9 rounded-xl bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all">
                                            <i class='bx bx-trash text-lg'></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- API Guide Modal -->
    <div x-show="apiGuideOpen" 
         class="fixed inset-0 z-[100] overflow-y-auto"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" @click="apiGuideOpen = false"></div>

            <div class="inline-block w-full max-w-3xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-[32px] sm:rounded-[40px]">
                <div class="p-6 md:p-12 relative">
                    <button @click="apiGuideOpen = false" class="absolute top-6 right-6 md:top-8 md:right-8 text-gray-400 hover:text-gray-600 transition-colors z-10">
                        <i class='bx bx-x text-2xl md:text-3xl'></i>
                    </button>

                    <div class="mb-8 md:mb-10">
                        <span class="px-3 py-1 bg-indigo-50 text-[#5B5FEF] text-[9px] sm:text-[10px] font-black rounded-full uppercase tracking-widest mb-3 md:mb-4 inline-block">Developer Guide</span>
                        <h2 class="text-2xl md:text-3xl font-black text-gray-800 leading-tight">Integrasi API Sensor</h2>
                        <p class="text-gray-500 mt-2 text-sm md:text-base">Gunakan panduan ini untuk mengirimkan data dari perangkat IoT Anda.</p>
                    </div>

                    <div class="space-y-6 md:space-y-8">
                        <!-- Endpoint Section -->
                        <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 items-start">
                            <div class="hidden sm:flex w-10 h-10 md:w-12 md:h-12 rounded-xl md:rounded-2xl bg-gray-50 items-center justify-center text-gray-400 text-xl shrink-0">
                                <i class='bx bx-link'></i>
                            </div>
                            <div class="flex-1 w-full">
                                <h4 class="text-[10px] md:text-sm font-bold text-gray-800 uppercase tracking-widest mb-2">API Endpoint</h4>
                                <div class="flex items-center gap-2 p-3 md:p-4 bg-gray-50 rounded-xl md:rounded-2xl border border-gray-100 overflow-hidden">
                                    <code class="text-xs md:text-sm font-mono text-[#5B5FEF] flex-1 truncate"><?= BASEURL; ?>/api/send</code>
                                    <button onclick="copyToClipboard('<?= BASEURL; ?>/api/send', 'URL Endpoint berhasil disalin!')" class="text-gray-400 hover:text-[#5B5FEF] transition-all shrink-0">
                                        <i class='bx bx-copy text-xl'></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Method Section -->
                        <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 items-start">
                            <div class="hidden sm:flex w-10 h-10 md:w-12 md:h-12 rounded-xl md:rounded-2xl bg-gray-50 items-center justify-center text-gray-400 text-xl shrink-0">
                                <i class='bx bx-paper-plane'></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-[10px] md:text-sm font-bold text-gray-800 uppercase tracking-widest mb-2">HTTP Method</h4>
                                <span class="px-4 py-2 bg-green-50 text-green-600 rounded-xl text-xs font-black">POST</span>
                            </div>
                        </div>

                        <!-- JSON Payload -->
                        <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 items-start">
                            <div class="hidden sm:flex w-10 h-10 md:w-12 md:h-12 rounded-xl md:rounded-2xl bg-gray-50 items-center justify-center text-gray-400 text-xl shrink-0">
                                <i class='bx bx-code-alt'></i>
                            </div>
                            <div class="flex-1 w-full">
                                <h4 class="text-[10px] md:text-sm font-bold text-gray-800 uppercase tracking-widest mb-2">Format Data (JSON Payload)</h4>
                                <div class="relative group">
                                    <pre id="jsonPayload" class="bg-[#1E1E2D] text-indigo-200 p-4 md:p-6 rounded-2xl md:rounded-[32px] text-[10px] md:text-xs font-mono overflow-x-auto leading-relaxed">
{
  "api_key": "YOUR_API_KEY",
  "voltage": 220.5,
  "current": 1.25,
  "daya_nyata": 275.6,
  "daya_semu": 310.2,
  "daya_reaktif": 140.5
}</pre>
                                    <button onclick="copyToClipboard(document.getElementById('jsonPayload').innerText, 'Contoh JSON disalin!')" 
                                            class="absolute top-3 right-3 md:top-4 md:right-4 bg-white/10 hover:bg-white/20 text-white p-2 rounded-xl backdrop-blur-sm transition-all opacity-100 sm:opacity-0 sm:group-hover:opacity-100">
                                        <i class='bx bx-copy text-lg'></i>
                                    </button>
                                </div>
                                <p class="text-[9px] md:text-[10px] text-gray-400 mt-2 italic">*Keterangan: Gunakan parameter di atas sesuai dengan pembacaan sensor Anda.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 md:mt-12 pt-6 md:pt-8 border-t border-gray-50 flex justify-end">
                        <button @click="apiGuideOpen = false" class="w-full sm:w-auto px-8 py-4 bg-gray-800 text-white rounded-2xl md:rounded-[22px] font-bold shadow-lg shadow-gray-200 hover:bg-gray-700 transition-all">
                            Saya Mengerti
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text, message) {
    navigator.clipboard.writeText(text).then(() => {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: message,
            timer: 2000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    });
}

function confirmDeleteDevice(url, name) {
    Swal.fire({
        title: 'Hapus Perangkat?',
        text: "Seluruh riwayat data untuk " + name + " akan ikut terhapus!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#f3f4f6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        customClass: {
            confirmButton: 'rounded-2xl px-6 py-3 font-bold',
            cancelButton: 'rounded-2xl px-6 py-3 font-bold text-gray-500'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    })
}
</script>
