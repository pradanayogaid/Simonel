<div class="p-8">
    <div class="mb-6">
        <a href="<?= BASEURL; ?>/device" class="text-[#5B5FEF] hover:underline flex items-center gap-1 mb-2">
            <i class='bx bx-left-arrow-alt'></i> Back to Devices
        </a>
        <h1 class="text-3xl font-bold">Add New Device</h1>
    </div>

    <div class="max-w-2xl bg-white rounded-3xl shadow-sm p-8 border border-gray-100">
        <form action="<?= BASEURL; ?>/device/add" method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="device_code" class="block text-sm font-medium text-gray-700 mb-2">Device Code (Unique ID)</label>
                    <input type="text" name="device_code" id="device_code" class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-[#5B5FEF] focus:border-[#5B5FEF]" placeholder="e.g. PANEL_01" required>
                </div>
                <div>
                    <label for="device_name" class="block text-sm font-medium text-gray-700 mb-2">Device Name</label>
                    <input type="text" name="device_name" id="device_name" class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-[#5B5FEF] focus:border-[#5B5FEF]" placeholder="e.g. Main Power Panel" required>
                </div>
            </div>

            <div class="mb-6">
                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                <input type="text" name="location" id="location" class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-[#5B5FEF] focus:border-[#5B5FEF]" placeholder="e.g. Floor 1 - Server Room" required>
            </div>

            <div class="flex flex-col sm:flex-row-reverse justify-end gap-4 sm:gap-3 mt-8">
                <button type="submit" class="w-full sm:w-auto px-8 py-3 rounded-2xl bg-[#5B5FEF] text-white font-bold hover:bg-[#4a4ed8] shadow-lg shadow-indigo-100 transition-all">Create Device</button>
                <a href="<?= BASEURL; ?>/device" class="w-full sm:w-auto px-6 py-3 rounded-2xl text-gray-400 hover:text-gray-600 font-bold text-center transition-colors">Cancel</a>
            </div>
        </form>
    </div>
</div>
