<div class="p-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <a href="<?= BASEURL; ?>/user" class="text-[#5B5FEF] font-bold flex items-center gap-2 mb-4 hover:gap-3 transition-all">
                <i class='bx bx-left-arrow-alt text-2xl'></i> Kembali
            </a>
            <h1 class="text-3xl font-bold">Edit User</h1>
            <p class="text-gray-500">Perbarui informasi akun <?= htmlspecialchars($data['target_user']['name']); ?></p>
        </div>

        <div class="bg-white rounded-[40px] p-8 shadow-sm border border-gray-50">
            <form action="<?= BASEURL; ?>/user/edit/<?= $data['target_user']['id']; ?>" method="POST" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-2">Nama Lengkap</label>
                        <input type="text" name="name" required value="<?= htmlspecialchars($data['target_user']['name']); ?>" 
                               class="w-full px-6 py-4 bg-gray-50 rounded-2xl border border-transparent focus:bg-white focus:border-[#5B5FEF] focus:outline-none transition-all">
                    </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-2">Alamat Email</label>
                    <input type="email" name="email" required value="<?= htmlspecialchars($data['target_user']['email']); ?>" 
                           class="w-full px-6 py-4 bg-gray-50 rounded-2xl border border-transparent focus:bg-white focus:border-[#5B5FEF] focus:outline-none transition-all">
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-2">Role / Hak Akses</label>
                    <select name="role" <?= $_SESSION['user']['role'] !== 'admin' ? 'disabled' : ''; ?>
                            class="w-full px-6 py-4 bg-gray-50 rounded-2xl border border-transparent focus:bg-white focus:border-[#5B5FEF] focus:outline-none transition-all appearance-none cursor-pointer">
                        <option value="user" <?= $data['target_user']['role'] === 'user' ? 'selected' : ''; ?>>Standard User</option>
                        <option value="admin" <?= $data['target_user']['role'] === 'admin' ? 'selected' : ''; ?>>Administrator</option>
                    </select>
                    <?php if($_SESSION['user']['role'] !== 'admin'): ?>
                        <p class="text-[10px] text-red-400 ml-2 italic">* Hanya Administrator yang dapat mengubah role.</p>
                        <input type="hidden" name="role" value="<?= $data['target_user']['role']; ?>">
                    <?php endif; ?>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-2">Ganti Password (Opsional)</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah" 
                           class="w-full px-6 py-4 bg-gray-50 rounded-2xl border border-transparent focus:bg-white focus:border-[#5B5FEF] focus:outline-none transition-all">
                    <p class="text-[10px] text-gray-400 ml-2">Keamanan: Biarkan kosong jika tidak ada perubahan password.</p>
                </div>

                <div class="pt-4 flex gap-4">
                    <button type="submit" class="flex-1 py-4 bg-[#5B5FEF] text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:scale-[1.02] active:scale-95 transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
