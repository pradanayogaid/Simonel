<div class="p-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold">User Management</h1>
            <p class="text-gray-500">Manage system users and their roles</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm overflow-hidden border border-gray-100">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 font-semibold text-gray-700">Full Name</th>
                    <th class="px-6 py-4 font-semibold text-gray-700">Email</th>
                    <th class="px-6 py-4 font-semibold text-gray-700">Role</th>
                    <th class="px-6 py-4 font-semibold text-gray-700">Joined</th>
                    <th class="px-6 py-4 font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($data['users'] as $user) : ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900"><?= htmlspecialchars($user['name']); ?></td>
                        <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($user['email']); ?></td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold <?= $user['role'] == 'admin' ? 'bg-indigo-100 text-[#5B5FEF]' : 'bg-gray-100 text-gray-500'; ?>">
                                <?= strtoupper($user['role']); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?= date('d M Y', strtotime($user['created_at'])); ?></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <a href="<?= BASEURL; ?>/user/edit/<?= $user['id']; ?>" class="text-blue-500 hover:text-blue-700">
                                    <i class='bx bx-edit-alt text-xl'></i>
                                </a>
                                <?php if ($user['id'] != $_SESSION['user']['id']) : ?>
                                    <a href="<?= BASEURL; ?>/user/delete/<?= $user['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?')" class="text-red-500 hover:text-red-700">
                                        <i class='bx bx-trash text-xl'></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
