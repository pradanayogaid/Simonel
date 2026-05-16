<div class="p-8">
    <div class="mb-6">
        <a href="<?= BASEURL; ?>/device" class="text-[#5B5FEF] hover:underline flex items-center gap-1 mb-2">
            <i class='bx bx-left-arrow-alt'></i> Back to Devices
        </a>
        <h1 class="text-3xl font-bold">Edit Device</h1>
    </div>

    <div class="max-w-2xl bg-white rounded-3xl shadow-sm p-8 border border-gray-100">
        <form action="<?= BASEURL; ?>/device/edit/<?= e($data['device']['id']); ?>" method="POST">
            <?= csrf_field(); ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Device Code (Read-only)</label>
                    <input type="text" class="block w-full px-4 py-3 border border-gray-100 rounded-xl bg-gray-50 text-gray-500 cursor-not-allowed" value="<?= htmlspecialchars($data['device']['device_code']); ?>" readonly>
                </div>
                <div>
                    <label for="device_name" class="block text-sm font-medium text-gray-700 mb-2">Device Name</label>
                    <input type="text" name="device_name" id="device_name" class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-[#5B5FEF] focus:border-[#5B5FEF]" value="<?= htmlspecialchars($data['device']['device_name']); ?>" required>
                </div>
            </div>

            <div class="mb-6">
                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                <input type="text" name="location" id="location" class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-[#5B5FEF] focus:border-[#5B5FEF]" value="<?= htmlspecialchars($data['device']['location']); ?>" required>
            </div>

            <div class="mb-6">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="status" class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-[#5B5FEF] focus:border-[#5B5FEF] bg-white">
                    <option value="OFFLINE" <?= $data['device']['status'] == 'OFFLINE' ? 'selected' : ''; ?>>OFFLINE</option>
                    <option value="ONLINE" <?= $data['device']['status'] == 'ONLINE' ? 'selected' : ''; ?>>ONLINE</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">API Key</label>
                <div class="flex gap-2">
                    <input type="text" class="block w-full px-4 py-3 border border-gray-100 rounded-xl bg-gray-50 text-gray-500 font-mono text-sm" value="<?= e($data['device']['api_key']); ?>" readonly>
                    <button type="button" onclick="navigator.clipboard.writeText(<?= js($data['device']['api_key']); ?>); showToast('Copied to clipboard!', 'success')" class="px-4 py-3 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">
                        <i class='bx bx-copy'></i>
                    </button>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="<?= BASEURL; ?>/device" class="px-6 py-2 rounded-full border border-gray-200 text-gray-600 font-medium hover:bg-gray-50 transition-colors">Cancel</a>
                <button type="submit" class="px-6 py-2 rounded-full bg-[#5B5FEF] text-white font-bold hover:bg-[#4a4ed8] transition-colors">Save Changes</button>
            </div>
        </form>
    </div>
</div>
