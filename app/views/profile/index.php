<div class="p-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold">Account Management</h1>
        <p class="text-gray-500">Manage your profile and security settings</p>
    </div>

    <?php if (!empty($data['success'])) : ?>
        <div class="bg-green-50 text-green-600 p-4 rounded-2xl mb-6 text-sm font-medium">
            <i class='bx bx-check-circle mr-1'></i> <?= $data['success']; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($data['error'])) : ?>
        <div class="bg-red-50 text-red-600 p-4 rounded-2xl mb-6 text-sm font-medium">
            <i class='bx bx-error-circle mr-1'></i> <?= $data['error']; ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Settings -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-3xl shadow-sm p-8 border border-gray-100">
                <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                    <i class='bx bx-user-circle text-[#5B5FEF]'></i> Profile Information
                </h2>
                <form action="<?= BASEURL; ?>/profile/update" method="POST">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" name="name" id="name" class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-[#5B5FEF] focus:border-[#5B5FEF]" value="<?= htmlspecialchars($data['user']['name']); ?>" required>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" name="email" id="email" class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-[#5B5FEF] focus:border-[#5B5FEF]" value="<?= htmlspecialchars($data['user']['email']); ?>" required>
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-400 mb-2">Role</label>
                        <input type="text" class="block w-full px-4 py-3 border border-gray-100 rounded-xl bg-gray-50 text-gray-500 cursor-not-allowed uppercase font-bold text-xs" value="<?= $data['user']['role']; ?>" readonly>
                    </div>
                    <button type="submit" class="bg-[#5B5FEF] text-white px-8 py-3 rounded-full font-bold hover:bg-[#4a4ed8] transition-colors">Save Changes</button>
                </form>
            </div>

            <!-- Password Change -->
            <div class="bg-white rounded-3xl shadow-sm p-8 border border-gray-100">
                <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                    <i class='bx bx-lock-alt text-[#5B5FEF]'></i> Change Password
                </h2>
                <form action="<?= BASEURL; ?>/profile/password" method="POST">
                    <div class="mb-6">
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                        <div class="relative" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'" name="current_password" id="current_password" class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-[#5B5FEF] focus:border-[#5B5FEF]" required>
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#5B5FEF]">
                                <i class='bx' :class="show ? 'bx-hide' : 'bx-show'"></i>
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" name="new_password" id="new_password" class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-[#5B5FEF] focus:border-[#5B5FEF]" required>
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#5B5FEF]">
                                    <i class='bx' :class="show ? 'bx-hide' : 'bx-show'"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" name="confirm_password" id="confirm_password" class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-[#5B5FEF] focus:border-[#5B5FEF]" required>
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#5B5FEF]">
                                    <i class='bx' :class="show ? 'bx-hide' : 'bx-show'"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="bg-gray-900 text-white px-8 py-3 rounded-full font-bold hover:bg-black transition-colors">Update Password</button>
                </form>
            </div>
        </div>

        <!-- Account Summary -->
        <div class="space-y-8">
            <div class="bg-[#5B5FEF] rounded-3xl shadow-lg p-8 text-white relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-indigo-100 text-sm mb-1 uppercase tracking-wider font-bold">Logged in as</p>
                    <h3 class="text-2xl font-bold mb-4"><?= htmlspecialchars($data['user']['name']); ?></h3>
                    <div class="space-y-2 text-sm text-indigo-50 mb-8">
                        <p class="flex items-center gap-2"><i class='bx bx-envelope'></i> <?= htmlspecialchars($data['user']['email']); ?></p>
                        <p class="flex items-center gap-2"><i class='bx bx-shield-quarter'></i> <?= strtoupper($data['user']['role']); ?></p>
                        <p class="flex items-center gap-2"><i class='bx bx-calendar'></i> Member since <?= date('M Y', strtotime($data['user']['created_at'])); ?></p>
                    </div>

                    <a href="<?= BASEURL; ?>/auth/logout" class="block w-full text-center py-3 bg-white/10 hover:bg-white/20 border border-white/20 rounded-full font-bold text-white transition-all">
                        <i class='bx bx-log-out mr-1'></i> Sign Out
                    </a>
                </div>
                <!-- Background decoration -->
                <i class='bx bxs-user-circle absolute -bottom-10 -right-10 text-9xl text-white opacity-10'></i>
            </div>
        </div>
    </div>
</div>
