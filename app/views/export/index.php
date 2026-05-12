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

<div class="p-8" x-data="exportPage()">

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold">Export Data</h1>
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
                    <div class="relative">
                        <i class='bx bx-calendar absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg'></i>
                        <input type="date" name="date_from" required
                               value="<?= htmlspecialchars($date_from ?: $today); ?>"
                               max="<?= $today; ?>"
                               class="w-full appearance-none bg-gray-50 border border-gray-200 text-gray-700 text-sm font-bold rounded-2xl pl-11 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#5B5FEF]/30 focus:border-[#5B5FEF] transition-all cursor-pointer">
                    </div>
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Tanggal Akhir</label>
                    <div class="relative">
                        <i class='bx bx-calendar-check absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg'></i>
                        <input type="date" name="date_to" required
                               value="<?= htmlspecialchars($date_to ?: $today); ?>"
                               max="<?= $today; ?>"
                               class="w-full appearance-none bg-gray-50 border border-gray-200 text-gray-700 text-sm font-bold rounded-2xl pl-11 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#5B5FEF]/30 focus:border-[#5B5FEF] transition-all cursor-pointer">
                    </div>
                </div>
            </div>

            <!-- Row 2: Field Checkboxes -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">Pilih Kolom Data</label>
                    <div class="flex gap-2">
                        <button type="button" onclick="checkAll(true)"
                                class="text-xs font-bold text-[#5B5FEF] hover:underline">Pilih Semua</button>
                        <span class="text-gray-300">|</span>
                        <button type="button" onclick="checkAll(false)"
                                class="text-xs font-bold text-gray-400 hover:underline">Hapus Semua</button>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-4">
                    <?php foreach ($fieldMap as $key => $info) : ?>
                    <label class="relative cursor-pointer group">
                        <input type="checkbox" name="fields[]" value="<?= $key; ?>"
                               class="peer hidden"
                               <?= (!empty($selectedFields) && in_array($key, $selectedFields)) || empty($selectedFields) ? 'checked' : ''; ?>>
                        <div class="peer-checked:border-[<?= $info['color']; ?>] peer-checked:bg-[<?= $info['color']; ?>]/5 
                                    border-2 border-gray-100 rounded-2xl p-4 transition-all
                                    group-hover:border-gray-200 group-hover:shadow-sm">
                            <div class="w-10 h-10 rounded-xl mb-3 flex items-center justify-center text-white text-xl"
                                 style="background-color: <?= $info['color']; ?>">
                                <i class='bx <?= $info['icon']; ?>'></i>
                            </div>
                            <p class="text-sm font-bold text-gray-700"><?= $info['label']; ?></p>
                            <p class="text-xs text-gray-400 mt-0.5"><?= $info['unit']; ?></p>
                        </div>
                        <!-- Checkmark badge -->
                        <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-gray-200 
                                    peer-checked:border-transparent peer-checked:bg-[<?= $info['color']; ?>]
                                    flex items-center justify-center transition-all">
                            <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Generate Button -->
            <div class="flex justify-end">
                <button type="submit"
                        class="flex items-center gap-3 px-8 py-3.5 bg-[#5B5FEF] text-white font-bold rounded-2xl shadow-lg shadow-indigo-200 hover:bg-indigo-600 active:scale-95 transition-all text-sm">
                    <i class='bx bx-table text-xl'></i>
                    Generate Preview
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
            <div class="flex items-center gap-2 shrink-0">
                <button onclick="window.print()"
                        class="flex items-center gap-2 px-4 py-2 bg-gray-50 border border-gray-200 text-gray-600 text-sm font-bold rounded-2xl hover:bg-white hover:border-gray-300 transition-all">
                    <i class='bx bx-printer text-lg'></i>
                    <span>Cetak</span>
                </button>
                <button onclick="downloadPDF()"
                        class="flex items-center gap-2 px-4 py-2 bg-red-50 border border-red-100 text-red-500 text-sm font-bold rounded-2xl hover:bg-red-100 transition-all">
                    <i class='bx bxs-file-pdf text-lg'></i>
                    <span>PDF</span>
                </button>
                <form method="POST" action="<?= BASEURL; ?>/export/generate" class="inline">
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
                    <?php foreach ($logs as $i => $row) : ?>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-3.5 text-gray-300 font-bold text-xs"><?= $i + 1; ?></td>
                        <td class="px-6 py-3.5 font-medium text-gray-600 whitespace-nowrap">
                            <?= date('d/m/Y', strtotime($row['created_at'])); ?>
                            <span class="text-gray-400 font-normal ml-1"><?= date('H:i:s', strtotime($row['created_at'])); ?></span>
                        </td>
                        <?php foreach ($selectedFields as $f) : if (!isset($fieldMap[$f])) continue; ?>
                        <td class="px-6 py-3.5 text-right font-bold" style="color: <?= $fieldMap[$f]['color']; ?>">
                            <?= number_format($row[$f] ?? 0, 2); ?>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php elseif (isset($data['device'])) : ?>
    <!-- Empty State -->
    <div class="bg-white rounded-[32px] p-12 shadow-sm border border-gray-100 text-center">
        <div class="w-20 h-20 rounded-3xl bg-gray-50 flex items-center justify-center mx-auto mb-4 text-4xl text-gray-300">
            <i class='bx bx-data'></i>
        </div>
        <h3 class="text-xl font-bold text-gray-700 mb-2">Tidak Ada Data</h3>
        <p class="text-gray-400 text-sm">Tidak ditemukan data sensor pada rentang tanggal yang dipilih.</p>
    </div>
    <?php endif; ?>

</div>

<style>
@media print {
    aside, nav, button, form, h1, .text-gray-500 { display: none !important; }
    body { background: white !important; }
    #printArea { border: none !important; box-shadow: none !important; }
}
</style>

<!-- jsPDF + AutoTable -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

<script>
function checkAll(state) {
    document.querySelectorAll('input[name="fields[]"]').forEach(cb => cb.checked = state);
}

function downloadPDF() {
    const { jsPDF } = window.jspdf;
    
    // --- Determine Dynamic Orientation ---
    const table = document.querySelector('#previewTable');
    const heads = [];
    table.querySelectorAll('thead tr th').forEach(th => heads.push(th.innerText.trim()));
    
    // Check only metric data columns (exclude No and Waktu)
    const metricCount = heads.length - 2;
    const orientation = metricCount >= 4 ? 'landscape' : 'portrait';
    const doc = new jsPDF({ orientation: orientation, unit: 'mm', format: 'a4' });

    const W = orientation === 'landscape' ? 297 : 210;
    const H = orientation === 'landscape' ? 210 : 297;
    
    const deviceName = document.querySelector('#pdfDeviceName')?.textContent?.trim() || '-';
    const dateRange  = document.querySelector('#pdfDateRange')?.textContent?.trim()  || '-';
    const rowCount   = document.querySelectorAll('#previewTable tbody tr').length;
    const genDate    = new Date().toLocaleDateString('id-ID', { day:'2-digit', month:'long', year:'numeric', hour:'2-digit', minute:'2-digit' });

    // ── 1. Dark navy header block ──────────────────────────────────
    doc.setFillColor(15, 23, 42);        // slate-900
    doc.rect(0, 0, W, 42, 'F');

    // Accent stripe
    doc.setFillColor(91, 95, 239);       // indigo
    doc.rect(0, 42, W, 3, 'F');

    // SIMONEL wordmark
    doc.setTextColor(255, 255, 255);
    doc.setFontSize(22);
    doc.setFont('helvetica', 'bold');
    doc.text('SIMONEL', 14, 20);

    // Tagline
    doc.setFontSize(8.5);
    doc.setFont('helvetica', 'normal');
    doc.setTextColor(148, 163, 184);     // slate-400
    doc.text('Sistem Monitoring Energi Listrik', 14, 28);

    // Report label (top-right)
    doc.setFontSize(8);
    doc.setTextColor(99, 102, 241);      // indigo-400
    doc.setFont('helvetica', 'bold');
    doc.text('LAPORAN DATA SENSOR', W - 14, 18, { align: 'right' });
    doc.setFont('helvetica', 'normal');
    doc.setTextColor(148, 163, 184);
    doc.setFontSize(7.5);
    doc.text(genDate, W - 14, 25, { align: 'right' });

    // ── 2. Info card ───────────────────────────────────────────────
    doc.setFillColor(248, 250, 252);     // slate-50
    doc.setDrawColor(226, 232, 240);     // slate-200
    doc.roundedRect(14, 50, W - 28, 30, 3, 3, 'FD');

    // Left column labels
    doc.setFontSize(7);
    doc.setTextColor(148, 163, 184);
    doc.setFont('helvetica', 'bold');
    doc.text('PERANGKAT', 20, 58);
    doc.text('PERIODE', 20, 68);
    doc.text('TOTAL DATA', 20, 78);

    // Right column values
    doc.setFontSize(8.5);
    doc.setTextColor(15, 23, 42);
    doc.setFont('helvetica', 'bold');
    doc.text(deviceName, 58, 58);

    doc.setFont('helvetica', 'normal');
    doc.setTextColor(51, 65, 85);
    doc.text(dateRange, 58, 68);
    doc.text(rowCount + ' baris', 58, 78);

    // Vertical divider
    doc.setDrawColor(226, 232, 240);
    doc.line(52, 53, 52, 83);

    // ── 3. Read table data ─────────────────────────────────────────
    const rows  = [];
    table.querySelectorAll('tbody tr').forEach(tr => {
        const row = [];
        tr.querySelectorAll('td').forEach(td => row.push(td.innerText.trim()));
        rows.push(row);
    });

    // ── 4. Table ───────────────────────────────────────────────────
    doc.autoTable({
        head: [heads],
        body: rows,
        startY: 86,
        styles: {
            font: 'helvetica',
            fontSize: 8,
            cellPadding: { top: 4, bottom: 4, left: 5, right: 5 },
            textColor: [51, 65, 85],
            lineColor: [241, 245, 249],
            lineWidth: 0.3,
        },
        headStyles: {
            fillColor: [15, 23, 42],
            textColor: [255, 255, 255],
            fontStyle: 'bold',
            fontSize: 7.5,
            halign: 'center',
            cellPadding: { top: 1.0, bottom: 1.0, left: 5, right: 5 },
        },
        alternateRowStyles: {
            fillColor: [248, 250, 252],
        },
        columnStyles: {
            0: { halign: 'center', cellWidth: 20.16, textColor: [148, 163, 184] },
            1: { halign: 'left',   cellWidth: orientation === 'landscape' ? 40 : 32 },
        },
        didDrawCell(data) {
            // Body data alignment and styling
            if (data.section === 'body' && data.column.index >= 2) {
                data.cell.styles.textColor = [91, 95, 239];
                data.cell.styles.fontStyle = 'bold';
                data.cell.styles.halign    = 'right';
            }
        },
        margin: { left: 14, right: 14 },
        tableLineColor: [226, 232, 240],
        tableLineWidth: 0.3,
    });

    // ── 5. Footer per page ─────────────────────────────────────────
    const pageCount = doc.internal.getNumberOfPages();
    for (let i = 1; i <= pageCount; i++) {
        doc.setPage(i);
        const yF = H - 10;

        // Footer top line
        doc.setDrawColor(226, 232, 240);
        doc.line(14, yF - 4, W - 14, yF - 4);

        doc.setFontSize(7.5);
        doc.setFont('helvetica', 'normal');
        doc.setTextColor(148, 163, 184);
        doc.text('SIMONEL \u2014 Sistem Monitoring Energi Listrik', 14, yF);
        doc.setFont('helvetica', 'bold');
        doc.setTextColor(91, 95, 239);
        doc.text(`${i} / ${pageCount}`, W - 14, yF, { align: 'right' });
    }

    const safeDateRange = dateRange.replace(/\s+/g, '_').replace(/[^a-z0-9_\-]/gi, '');
    doc.save(`SIMONEL_Export_${safeDateRange}.pdf`);
}

</script>
