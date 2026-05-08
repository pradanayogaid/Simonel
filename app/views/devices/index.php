<div class="p-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold">Device Management</h1>
            <p class="text-gray-500">Manage your IoT devices and sensors</p>
        </div>
        <a href="<?= BASEURL; ?>/device/add" class="bg-[#5B5FEF] text-white px-6 py-2 rounded-full font-bold hover:bg-[#4a4ed8] transition-colors flex items-center gap-2">
            <i class='bx bx-plus'></i> Add Device
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm overflow-hidden border border-gray-100">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 font-semibold text-gray-700">Device Name</th>
                    <th class="px-6 py-4 font-semibold text-gray-700">Code</th>
                    <th class="px-6 py-4 font-semibold text-gray-700">Location</th>
                    <th class="px-6 py-4 font-semibold text-gray-700">Status</th>
                    <th class="px-6 py-4 font-semibold text-gray-700">API Key</th>
                    <th class="px-6 py-4 font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($data['devices'])) : ?>
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">No devices found. Click "Add Device" to get started.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($data['devices'] as $device) : ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900"><?= htmlspecialchars($device['device_name']); ?></td>
                            <td class="px-6 py-4 text-gray-600"><code><?= htmlspecialchars($device['device_code']); ?></code></td>
                            <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($device['location']); ?></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold <?= $device['status'] == 'ONLINE' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500'; ?>">
                                    <?= $device['status']; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-mono text-gray-400">••••<?= substr($device['api_key'], -4); ?></span>
                                    <button onclick="navigator.clipboard.writeText('<?= $device['api_key']; ?>'); showToast('API Key copied to clipboard!', 'success')" class="text-gray-400 hover:text-[#5B5FEF] transition-colors" title="Copy API Key">
                                        <i class='bx bx-copy'></i>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <a href="<?= BASEURL; ?>/device/edit/<?= $device['id']; ?>" class="text-blue-500 hover:text-blue-700">
                                        <i class='bx bx-edit-alt text-xl'></i>
                                    </a>
                                    <a href="<?= BASEURL; ?>/device/delete/<?= $device['id']; ?>" onclick="return confirm('Are you sure you want to delete this device?')" class="text-red-500 hover:text-red-700">
                                        <i class='bx bx-trash text-xl'></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
