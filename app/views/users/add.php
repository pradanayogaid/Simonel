<div class="p-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <a href="<?= BASEURL; ?>/user" class="text-[#5B5FEF] font-bold flex items-center gap-2 mb-4 hover:gap-3 transition-all">
                <i class='bx bx-left-arrow-alt text-2xl'></i> Kembali
            </a>
            <h1 class="text-3xl font-bold">Tambah User Baru</h1>
            <p class="text-gray-500">Berikan akses sistem ke anggota tim baru</p>
        </div>

        <div class="bg-white rounded-[40px] p-8 shadow-sm border border-gray-50">
            <form action="<?= BASEURL; ?>/user/add" method="POST" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-2">Nama Lengkap</label>
                        <input type="text" name="name" required placeholder="Contoh: Ahmad Dani" 
                               class="w-full px-6 py-4 bg-gray-50 rounded-2xl border border-transparent focus:bg-white focus:border-[#5B5FEF] focus:outline-none transition-all">
                    </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-2">Alamat Email</label>
                    <input type="email" name="email" required placeholder="email@example.com" 
                           class="w-full px-6 py-4 bg-gray-50 rounded-2xl border border-transparent focus:bg-white focus:border-[#5B5FEF] focus:outline-none transition-all">
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-2">Password</label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter" 
                           class="w-full px-6 py-4 bg-gray-50 rounded-2xl border border-transparent focus:bg-white focus:border-[#5B5FEF] focus:outline-none transition-all">
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 bg-[#5B5FEF] text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:scale-[1.02] active:scale-95 transition-all">
                        Simpan User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
