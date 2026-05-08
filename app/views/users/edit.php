<div class="p-8">
    <div class="mb-6">
        <a href="<?= BASEURL; ?>/user" class="text-[#5B5FEF] hover:underline flex items-center gap-1 mb-2">
            <i class='bx bx-left-arrow-alt'></i> Back to Users
        </a>
        <h1 class="text-3xl font-bold">Edit User</h1>
    </div>

    <div class="max-w-2xl bg-white rounded-3xl shadow-sm p-8 border border-gray-100">
        <form action="<?= BASEURL; ?>/user/edit/<?= $data['target_user']['id']; ?>" method="POST">
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                <input type="text" name="name" id="name" class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-[#5B5FEF] focus:border-[#5B5FEF]" value="<?= htmlspecialchars($data['target_user']['name']); ?>" required>
            </div>

            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email" name="email" id="email" class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-[#5B5FEF] focus:border-[#5B5FEF]" value="<?= htmlspecialchars($data['target_user']['email']); ?>" required>
            </div>

            <div class="mb-6">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <select name="role" id="role" class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-[#5B5FEF] focus:border-[#5B5FEF] bg-white">
                    <option value="user" <?= $data['target_user']['role'] == 'user' ? 'selected' : ''; ?>>USER</option>
                    <option value="admin" <?= $data['target_user']['role'] == 'admin' ? 'selected' : ''; ?>>ADMIN</option>
                </select>
            </div>

            <div class="flex justify-end gap-3">
                <a href="<?= BASEURL; ?>/user" class="px-6 py-2 rounded-full border border-gray-200 text-gray-600 font-medium hover:bg-gray-50 transition-colors">Cancel</a>
                <button type="submit" class="px-6 py-2 rounded-full bg-[#5B5FEF] text-white font-bold hover:bg-[#4a4ed8] transition-colors">Save Changes</button>
            </div>
        </form>
    </div>
</div>
