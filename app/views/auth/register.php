<div class="flex items-center justify-center min-h-screen py-10">
    <div class="w-full max-w-md bg-white rounded-3xl shadow-sm p-8 border border-gray-100">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-[#1E1E2D] mb-2">Daftar Akun</h1>
            <p class="text-gray-500">Buat akun baru di SIMONEL</p>
        </div>

        <?php if (!empty($data['error'])) : ?>
            <div class="bg-red-50 text-red-500 text-sm p-4 rounded-xl mb-6">
                <?= htmlspecialchars($data['error']); ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASEURL; ?>/auth/processRegister" method="POST">
            <?= csrf_field(); ?>
            <div class="mb-5">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class='bx bx-user text-gray-400 text-lg'></i>
                    </div>
                    <input type="text" name="name" id="name" class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:ring-[#5B5FEF] focus:border-[#5B5FEF] transition-colors" placeholder="Nama Anda" required>
                </div>
            </div>

            <div class="mb-5">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class='bx bx-envelope text-gray-400 text-lg'></i>
                    </div>
                    <input type="email" name="email" id="email" class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:ring-[#5B5FEF] focus:border-[#5B5FEF] transition-colors" placeholder="email@contoh.com" required>
                </div>
            </div>

            <div class="mb-5">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class='bx bx-lock-alt text-gray-400 text-lg'></i>
                    </div>
                    <input type="password" name="password" id="password" class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:ring-[#5B5FEF] focus:border-[#5B5FEF] transition-colors" placeholder="••••••••" required>
                </div>
            </div>

            <div class="mb-6">
                <label for="password_confirm" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class='bx bx-lock-alt text-gray-400 text-lg'></i>
                    </div>
                    <input type="password" name="password_confirm" id="password_confirm" class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:ring-[#5B5FEF] focus:border-[#5B5FEF] transition-colors" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-full shadow-sm text-sm font-bold text-white bg-gradient-to-r from-indigo-500 to-violet-500 hover:from-indigo-600 hover:to-violet-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                Daftar Sekarang
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            Sudah punya akun? 
            <a href="<?= BASEURL; ?>/auth" class="font-medium text-[#5B5FEF] hover:text-[#7B61FF]">Login di sini</a>
        </p>
    </div>
</div>
