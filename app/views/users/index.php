<div class="p-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold">User Management</h1>
            <p class="text-gray-500">Kelola akses pengguna sistem SIMONEL</p>
        </div>
        <a href="<?= BASEURL; ?>/user/add" class="px-6 py-3 bg-[#5B5FEF] text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:scale-105 transition-all flex items-center gap-2">
            <i class='bx bx-plus'></i> Tambah User
        </a>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-[40px] shadow-sm border border-gray-50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">User</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Email</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Role</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Dibuat</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php foreach ($data['users'] as $user) : ?>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-50 text-[#5B5FEF] flex items-center justify-center font-bold">
                                    <?= e(substr($user['name'], 0, 1)); ?>
                                </div>
                                <div class="text-sm font-bold text-gray-700"><?= htmlspecialchars($user['name']); ?></div>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-sm text-gray-500 font-medium">
                            <?= htmlspecialchars($user['email']); ?>
                        </td>
                        <td class="px-8 py-5">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black <?= $user['role'] == 'admin' ? 'bg-purple-50 text-purple-500' : 'bg-blue-50 text-blue-500'; ?> uppercase">
                                <?= e($user['role']); ?>
                            </span>
                        </td>
                        <td class="px-8 py-5 text-xs text-gray-400 font-medium">
                            <?= date('d M Y', strtotime($user['created_at'])); ?>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex gap-2">
                                <a href="<?= BASEURL; ?>/user/edit/<?= e($user['id']); ?>" class="p-2 text-gray-400 hover:text-[#5B5FEF] transition-colors">
                                    <i class='bx bxs-edit text-xl'></i>
                                </a>
                                <?php if ($user['id'] != $_SESSION['user']['id']) : ?>
                                <button onclick="confirmDelete('<?= BASEURL; ?>/user/delete/<?= e($user['id']); ?>', <?= js($user['name']); ?>)" class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                                    <i class='bx bxs-trash text-xl'></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function confirmDelete(url, name) {
    Swal.fire({
        title: 'Hapus User?',
        text: "Anda akan menghapus akses untuk " + name + ". Tindakan ini tidak dapat dibatalkan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#5B5FEF',
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
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.innerHTML = `<?= csrf_field(); ?>`;
            document.body.appendChild(form);
            form.submit();
        }
    })
}
</script>
