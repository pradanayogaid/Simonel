<?php
$fieldMap = [
    'voltage'      => ['label' => 'Tegangan', 'unit' => 'V AC',  'icon' => 'bx-bolt-circle',    'color' => '#5B5FEF'],
    'current'      => ['label' => 'Arus',     'unit' => 'A',     'icon' => 'bx-trending-up',    'color' => '#F59E0B'],
    'daya_nyata'   => ['label' => 'Daya Nyata',  'unit' => 'W',  'icon' => 'bx-chip',           'color' => '#10B981'],
    'daya_semu'    => ['label' => 'Daya Semu',   'unit' => 'VA', 'icon' => 'bx-radio-circle',   'color' => '#8B5CF6'],
    'daya_reaktif' => ['label' => 'Daya Reaktif','unit' => 'VAR','icon' => 'bx-repost',         'color' => '#06B6D4'],
];

$selectedFields = $data['fields'] ?? [];
$logs           = $data['logs']   ?? [];
$device         = $data['device'] ?? null;
$date_from      = $data['date_from'] ?? '';
$date_to        = $data['date_to']   ?? '';
$today          = date('Y-m-d');
?>

<div class="p-4 sm:p-8" x-data="exportPage()">

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Pilih Data</h1>
            <p class="text-gray-500">Unduh data sensor dalam rentang waktu tertentu</p>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100 mb-8">
        <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
            <i class='bx bx-filter-alt text-[#5B5FEF] text-2xl'></i>
            Filter & Pilih Data
        </h2>

        <form method="POST" action="<?= BASEURL; ?>/export/generate" id="exportForm">
            <?= csrf_field(); ?>
            <input type="hidden" name="format" value="preview">

            <!-- Row 1: Device + Date Range -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Device Selector -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Perangkat</label>
                    <div class="relative">
                        <i class='bx bxs-microchip absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg'></i>
                        <select name="device_id" required
                                class="w-full appearance-none bg-gray-50 border border-gray-200 text-gray-700 text-sm font-bold rounded-2xl pl-11 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#5B5FEF]/30 focus:border-[#5B5FEF] transition-all">
                            <option value="">-- Pilih Perangkat --</option>
                            <?php foreach ($data['devices'] as $dev) : ?>
                            <option value="<?= $dev['id']; ?>" <?= (isset($device) && $device['id'] == $dev['id']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($dev['device_name']); ?> (<?= $dev['device_code']; ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Date From -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Tanggal Mulai</label>
                    <div class="relative h-11 sm:h-12 group">
                        <div class="absolute inset-0 bg-gray-50 border border-gray-200 rounded-2xl flex items-center px-4 gap-3 pointer-events-none group-focus-within:ring-2 group-focus-within:ring-[#5B5FEF]/30 group-focus-within:border-[#5B5FEF] transition-all">
                            <i class='bx bx-calendar text-[#5B5FEF] text-xl'></i>
                            <span class="text-sm font-bold text-gray-700" x-text="formatDate(dateFrom)"></span>
                        </div>
                        <input type="date" name="date_from" required
                               x-model="dateFrom"
                               max="<?= $today; ?>"
                               class="absolute inset-0 opacity-0 cursor-pointer w-full h-full [&::-webkit-calendar-picker-indicator]:block [&::-webkit-calendar-picker-indicator]:absolute [&::-webkit-calendar-picker-indicator]:inset-0 [&::-webkit-calendar-picker-indicator]:w-full [&::-webkit-calendar-picker-indicator]:h-full [&::-webkit-calendar-picker-indicator]:cursor-pointer [&::-webkit-calendar-picker-indicator]:opacity-0">
                    </div>
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Tanggal Akhir</label>
                    <div class="relative h-11 sm:h-12 group">
                        <div class="absolute inset-0 bg-gray-50 border border-gray-200 rounded-2xl flex items-center px-4 gap-3 pointer-events-none group-focus-within:ring-2 group-focus-within:ring-[#5B5FEF]/30 group-focus-within:border-[#5B5FEF] transition-all">
                            <i class='bx bx-calendar-check text-[#5B5FEF] text-xl'></i>
                            <span class="text-sm font-bold text-gray-700" x-text="formatDate(dateTo)"></span>
                        </div>
                        <input type="date" name="date_to" required
                               x-model="dateTo"
                               max="<?= $today; ?>"
                               class="absolute inset-0 opacity-0 cursor-pointer w-full h-full [&::-webkit-calendar-picker-indicator]:block [&::-webkit-calendar-picker-indicator]:absolute [&::-webkit-calendar-picker-indicator]:inset-0 [&::-webkit-calendar-picker-indicator]:w-full [&::-webkit-calendar-picker-indicator]:h-full [&::-webkit-calendar-picker-indicator]:cursor-pointer [&::-webkit-calendar-picker-indicator]:opacity-0">
                    </div>
                </div>
            </div>

            <!-- Row 2: Field Checkboxes -->
            <div class="mb-8" x-data="{ allSelected: <?= empty($selectedFields) || count($selectedFields) == count($fieldMap) ? 'true' : 'false' ?> }">
                <div class="flex items-center justify-between mb-4">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">Pilih Data</label>
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <span class="text-[10px] font-bold text-gray-400 group-hover:text-[#5B5FEF] transition-colors">Pilih Semua</span>
                        <div class="relative inline-flex items-center">
                            <input type="checkbox" x-model="allSelected" 
                                   @change="document.querySelectorAll('input[name=\'fields[]\']').forEach(cb => cb.checked = allSelected)"
                                   class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#5B5FEF]"></div>
                        </div>
                    </label>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-4">
                    <?php foreach ($fieldMap as $key => $info) : ?>
                    <label class="relative cursor-pointer group">
                        <input type="checkbox" name="fields[]" value="<?= $key; ?>"
                               @change="allSelected = (document.querySelectorAll('input[name=\'fields[]\']:checked').length === <?= count($fieldMap) ?>)"
                               class="peer hidden"
                               <?= (!empty($selectedFields) && in_array($key, $selectedFields)) || empty($selectedFields) ? 'checked' : ''; ?>>
                        <div class="peer-checked:border-[<?= $info['color']; ?>] peer-checked:bg-[<?= $info['color']; ?>]/5 border-2 border-gray-100 rounded-2xl p-4 transition-all group-hover:border-gray-200 group-hover:shadow-sm">
                            <div class="w-10 h-10 rounded-xl mb-3 flex items-center justify-center text-white text-xl"
                                 style="background-color: <?= $info['color']; ?>">
                                <i class='bx <?= $info['icon']; ?>'></i>
                            </div>
                            <p class="text-sm font-bold text-gray-700"><?= $info['label']; ?></p>
                            <p class="text-xs text-gray-400 mt-0.5"><?= $info['unit']; ?></p>
                        </div>
                        <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-gray-200 peer-checked:border-transparent peer-checked:bg-[<?= $info['color']; ?>] flex items-center justify-center transition-all">
                            <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Generate Button -->
            <div class="mt-8">
                <button type="submit"
                        class="w-full flex items-center justify-center gap-3 px-8 py-4 bg-[#5B5FEF] text-white font-bold rounded-2xl shadow-lg shadow-indigo-100 hover:bg-indigo-600 active:scale-95 transition-all text-base sm:text-lg">
                    <i class='bx bx-table text-2xl'></i>
                    <span>Tampilkan Data Tabel</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Results Preview -->
    <?php if (!empty($logs)) : ?>
    <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden" id="printArea">

        <!-- Result Header -->
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Hasil Preview</h2>
                <p class="text-sm text-gray-400 mt-0.5">
                    <span id="pdfDeviceName" class="font-bold text-gray-600"><?= htmlspecialchars($device['device_name']); ?></span>
                    &nbsp;·&nbsp;
                    <span id="pdfDateRange"><?= date('d M Y', strtotime($date_from)); ?> – <?= date('d M Y', strtotime($date_to)); ?></span>
                    &nbsp;·&nbsp;
                    <span class="font-bold text-[#5B5FEF]"><?= count($logs); ?> baris data</span>
                </p>
            </div>
            <!-- Action Buttons -->
            <div class="flex items-center gap-2 shrink-0 no-print">
                <button onclick="downloadPDF(this)" id="btnPdf"
                        class="flex items-center gap-2 px-4 py-2 bg-red-50 border border-red-100 text-red-500 text-sm font-bold rounded-2xl hover:bg-red-100 transition-all disabled:opacity-50 disabled:cursor-wait">
                    <i class='bx bxs-file-pdf text-lg' id="iconPdf"></i>
                    <span id="textPdf">PDF</span>
                </button>
                <form method="POST" action="<?= BASEURL; ?>/export/generate" class="inline">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="device_id" value="<?= htmlspecialchars($device['id']); ?>">
                    <input type="hidden" name="date_from" value="<?= htmlspecialchars($date_from); ?>">
                    <input type="hidden" name="date_to"   value="<?= htmlspecialchars($date_to); ?>">
                    <input type="hidden" name="format"    value="csv">
                    <?php foreach ($selectedFields as $f) : ?>
                    <input type="hidden" name="fields[]" value="<?= htmlspecialchars($f); ?>">
                    <?php endforeach; ?>
                    <button type="submit"
                            class="flex items-center gap-2 px-4 py-2 bg-[#5B5FEF] text-white text-sm font-bold rounded-2xl shadow-md shadow-indigo-200 hover:bg-indigo-600 transition-all">
                        <i class='bx bxs-download text-lg'></i>
                        <span>CSV</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table id="previewTable" class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-400 text-[11px] font-bold uppercase tracking-widest">
                        <th class="px-6 py-4 text-left whitespace-nowrap">No</th>
                        <th class="px-6 py-4 text-left whitespace-nowrap">Waktu</th>
                        <?php foreach ($selectedFields as $f) : if (!isset($fieldMap[$f])) continue; ?>
                        <th class="px-6 py-4 text-right whitespace-nowrap" style="color: <?= $fieldMap[$f]['color']; ?>">
                            <?= $fieldMap[$f]['label']; ?> (<?= $fieldMap[$f]['unit']; ?>)
                        </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <template x-for="(row, index) in paginatedLogs" :key="index">
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-3.5 text-gray-300 font-bold text-xs" x-text="((currentPage - 1) * pageSize) + index + 1"></td>
                            <td class="px-6 py-3.5 font-medium text-gray-600 whitespace-nowrap">
                                <span x-text="formatDateTime(row.created_at).date"></span>
                                <span class="text-gray-400 font-normal ml-1" x-text="formatDateTime(row.created_at).time"></span>
                            </td>
                            <?php foreach ($selectedFields as $f) : if (!isset($fieldMap[$f])) continue; ?>
                            <td class="px-6 py-3.5 text-right font-bold text-[<?= $fieldMap[$f]['color']; ?>]" x-text="Number(row.<?= $f; ?> || 0).toFixed(2)"></td>
                            <?php endforeach; ?>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Pagination Controls -->
        <div class="p-4 sm:p-6 border-t border-gray-50 flex flex-col items-center gap-4 no-print">
            <div class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-widest text-center">
                Menampilkan <span class="text-gray-700" x-text="startRange"></span> - <span class="text-gray-700" x-text="endRange"></span> 
                dari <span class="text-[#5B5FEF]" x-text="totalLogs"></span> data
            </div>
            <div class="flex items-center justify-center gap-1 sm:gap-2 w-full max-w-sm sm:max-w-none">
                <button @click="currentPage = 1" :disabled="currentPage === 1" class="w-10 h-10 flex items-center justify-center bg-gray-50 rounded-xl border border-gray-100 text-gray-400 disabled:opacity-30 transition-all shrink-0"><i class='bx bx-chevrons-left text-lg'></i></button>
                <button @click="currentPage--" :disabled="currentPage === 1" class="flex-1 sm:flex-none h-10 px-2 sm:px-4 flex items-center justify-center bg-gray-50 rounded-xl border border-gray-100 text-gray-400 disabled:opacity-30 transition-all"><i class='bx bx-chevron-left text-xl sm:hidden'></i><span class="hidden sm:inline text-xs font-bold">Sebelumnya</span></button>
                <div class="sm:hidden flex-1 text-center font-black text-sm text-gray-700 px-2"><span x-text="currentPage"></span> <span class="text-gray-300 font-normal">/</span> <span x-text="totalPages"></span></div>
                <div class="hidden sm:flex items-center gap-1 px-2">
                    <template x-for="p in visiblePages" :key="p">
                        <button @click="currentPage = p" :class="currentPage === p ? 'bg-[#5B5FEF] text-white' : 'bg-white text-gray-400'" class="w-9 h-9 rounded-xl text-xs font-bold transition-all" x-text="p"></button>
                    </template>
                </div>
                <button @click="currentPage++" :disabled="currentPage === totalPages" class="flex-1 sm:flex-none h-10 px-2 sm:px-4 flex items-center justify-center bg-gray-50 rounded-xl border border-gray-100 text-gray-400 disabled:opacity-30 transition-all"><i class='bx bx-chevron-right text-xl sm:hidden'></i><span class="hidden sm:inline text-xs font-bold">Selanjutnya</span></button>
                <button @click="currentPage = totalPages" :disabled="currentPage === totalPages" class="w-10 h-10 flex items-center justify-center bg-gray-50 rounded-xl border border-gray-100 text-gray-400 disabled:opacity-30 transition-all shrink-0"><i class='bx bx-chevrons-right text-lg'></i></button>
            </div>
        </div>
    </div>
    <?php elseif (isset($data['device'])) : ?>
    <div class="bg-white rounded-[32px] p-12 shadow-sm border border-gray-100 text-center">
        <div class="w-20 h-20 rounded-3xl bg-gray-50 flex items-center justify-center mx-auto mb-4 text-4xl text-gray-300"><i class='bx bx-data'></i></div>
        <h3 class="text-xl font-bold text-gray-700 mb-2">Tidak Ada Data</h3>
        <p class="text-gray-400 text-sm">Tidak ditemukan data sensor pada rentang tanggal yang dipilih.</p>
    </div>
    <?php endif; ?>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    #printArea, #printArea * { visibility: visible; }
    #printArea { position: absolute; left: 0; top: 0; width: 100%; margin: 0; padding: 0; border: none !important; box-shadow: none !important; }
    .no-print { display: none !important; }
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

<script>
function exportPage() {
    return {
        logs: <?= json_encode($logs); ?>,
        dateFrom: '<?= $date_from ?: $today ?>',
        dateTo: '<?= $date_to ?: $today ?>',
        allSelected: <?= empty($selectedFields) || count($selectedFields) == count($fieldMap) ? 'true' : 'false' ?>,
        currentPage: 1,
        pageSize: 20,
        get totalLogs() { return this.logs.length; },
        get totalPages() { return Math.ceil(this.totalLogs / this.pageSize); },
        get startRange() { return (this.currentPage - 1) * this.pageSize + 1; },
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
        formatDate(dateStr) {
            if (!dateStr) return '--/--/----';
            const [y, m, d] = dateStr.split('-');
            return `${d}/${m}/${y}`;
        },
        formatDateTime(ts) {
            const d = new Date(ts);
            const date = d.getDate().toString().padStart(2, '0') + '/' + (d.getMonth() + 1).toString().padStart(2, '0') + '/' + d.getFullYear();
            const time = d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0') + ':' + d.getSeconds().toString().padStart(2, '0');
            return { date, time };
        }
    }
}

function downloadPDF(btn) {
    const textBtn = document.getElementById('textPdf');
    const iconBtn = document.getElementById('iconPdf');
    btn.disabled = true;
    textBtn.innerText = 'Memproses...';
    iconBtn.className = 'bx bx-loader-alt bx-spin text-lg';

    setTimeout(() => {
        try {
            const { jsPDF } = window.jspdf;
            const exportEl = document.querySelector('[x-data="exportPage()"]');
            const alpineData = exportEl.__x ? exportEl.__x.$data : null;
            
            // Fallback for Alpine v3 if __x is not available
            const allLogs = (alpineData ? alpineData.logs : window.Alpine ? Alpine.$data(exportEl).logs : []) || [];
            
            if (allLogs.length === 0) {
                alert('Tidak ada data untuk diunduh.');
                return;
            }

            const selectedFields = <?= json_encode($selectedFields); ?>;
            const fieldMap = <?= json_encode($fieldMap); ?>;
            const heads = ['No', 'Waktu'];
            selectedFields.forEach(f => { if(fieldMap[f]) heads.push(`${fieldMap[f].label} (${fieldMap[f].unit})`); });
            
            // Force Portrait Orientation
            const orientation = 'portrait';
            const doc = new jsPDF({ orientation: orientation, unit: 'mm', format: 'a4' });
            
            const W = 210; // A4 Portrait Width
            const H = 297; // A4 Portrait Height
            const availableWidth = W - 28; // 14mm margins

            const deviceName = document.querySelector('#pdfDeviceName')?.textContent?.trim() || '-';
            const dateRange  = document.querySelector('#pdfDateRange')?.textContent?.trim()  || '-';
            const genDate    = new Date().toLocaleDateString('id-ID', { day:'2-digit', month:'long', year:'numeric', hour:'2-digit', minute:'2-digit' });

            // ── 1. Header ──────────────────────────────────────────────
            doc.setFillColor(15, 23, 42); doc.rect(0, 0, W, 42, 'F');
            doc.setFillColor(91, 95, 239); doc.rect(0, 42, W, 3, 'F');
            doc.setTextColor(255, 255, 255); doc.setFontSize(22); doc.setFont('helvetica', 'bold');
            doc.text('SIMONEL', 14, 20);
            doc.setFontSize(8.5); doc.setFont('helvetica', 'normal'); doc.setTextColor(148, 163, 184);
            doc.text('Sistem Monitoring Energi Listrik', 14, 28);
            doc.setFontSize(8); doc.setTextColor(99, 102, 241); doc.setFont('helvetica', 'bold');
            doc.text('LAPORAN DATA SENSOR', W - 14, 18, { align: 'right' });
            doc.text(genDate, W - 14, 25, { align: 'right' });

            // ── 2. Info card ───────────────────────────────────────────
            doc.setFillColor(248, 250, 252); doc.setDrawColor(226, 232, 240);
            doc.roundedRect(14, 50, W - 28, 30, 3, 3, 'FD');
            doc.setFontSize(7); doc.setTextColor(148, 163, 184);
            doc.text('PERANGKAT', 20, 58); doc.text('PERIODE', 20, 68); doc.text('TOTAL DATA', 20, 78);
            doc.setFontSize(8.5); doc.setTextColor(15, 23, 42); doc.setFont('helvetica', 'bold');
            doc.text(deviceName, 58, 58);
            doc.setFont('helvetica', 'normal'); doc.text(dateRange, 58, 68);
            doc.text(allLogs.length + ' baris', 58, 78);

            const rows = allLogs.map((row, i) => {
                const d = new Date(row.created_at);
                const ds = d.getDate().toString().padStart(2, '0') + '/' + (d.getMonth() + 1).toString().padStart(2, '0') + '/' + d.getFullYear();
                const ts = d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0');
                const line = [i + 1, `${ds} ${ts}`];
                selectedFields.forEach(f => line.push(Number(row[f] || 0).toFixed(2)));
                return line;
            });

            // Compact widths for Portrait
            const noWidth = 10;
            const timeWidth = 32;
            const metricWidth = (availableWidth - noWidth - timeWidth) / selectedFields.length;

            const colStyles = {
                0: { halign: 'center', cellWidth: noWidth },
                1: { cellWidth: timeWidth }
            };
            for(let i=2; i < heads.length; i++) {
                colStyles[i] = { halign: 'right', cellWidth: metricWidth };
            }

            doc.autoTable({
                head: [heads],
                body: rows,
                startY: 86,
                styles: { 
                    font: 'helvetica',
                    fontSize: 6.5, // Slightly smaller to ensure fit
                    cellPadding: 2,
                    overflow: 'linebreak'
                },
                headStyles: { 
                    fillColor: [15, 23, 42], 
                    halign: 'center',
                    valign: 'middle'
                },
                columnStyles: colStyles,
                didDrawCell(data) {
                    if (data.section === 'body' && data.column.index >= 2) {
                        data.cell.styles.fontStyle = 'bold';
                        data.cell.styles.textColor = [91, 95, 239];
                    }
                },
                margin: { left: 14, right: 14 }
            });

            const pageCount = doc.internal.getNumberOfPages();
            for (let i = 1; i <= pageCount; i++) {
                doc.setPage(i);
                doc.setFontSize(7); doc.setTextColor(148, 163, 184);
                doc.text(`SIMONEL — Halaman ${i} dari ${pageCount}`, 14, H - 10);
            }

            doc.save(`SIMONEL_Report_${deviceName.replace(/\s+/g,'_')}.pdf`);
        } catch (e) {
            console.error(e);
            alert('Terjadi kesalahan saat membuat PDF.');
        } finally {
            btn.disabled = false;
            textBtn.innerText = 'PDF';
            iconBtn.className = 'bx bxs-file-pdf text-lg';
        }
    }, 200);
}
</script>
