<div class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white rounded-3xl shadow-sm p-8 border border-gray-100">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-[#1E1E2D] mb-2">SIMONEL</h1>
            <p class="text-gray-500">Sistem Monitoring Panel</p>
        </div>

        <?php if (!empty($data['error'])) : ?>
            <div class="bg-red-50 text-red-500 text-sm p-4 rounded-xl mb-6">
                <?= htmlspecialchars($data['error']); ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASEURL; ?>/auth/login" method="POST">
            <div class="mb-5">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class='bx bx-envelope text-gray-400 text-lg'></i>
                    </div>
                    <input type="email" name="email" id="email" class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:ring-[#5B5FEF] focus:border-[#5B5FEF] transition-colors" placeholder="admin@simonel.com" required>
                </div>
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <div class="relative" x-data="{ show: false }">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class='bx bx-lock-alt text-gray-400 text-lg'></i>
                    </div>
                    <input :type="show ? 'text' : 'password'" name="password" id="password" class="block w-full pl-10 pr-12 py-3 border border-gray-200 rounded-xl focus:ring-[#5B5FEF] focus:border-[#5B5FEF] transition-colors" placeholder="••••••••" required>
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#5B5FEF]">
                        <i class='bx' :class="show ? 'bx-hide' : 'bx-show'"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <input id="remember_me" name="remember_me" type="checkbox" class="h-4 w-4 text-[#5B5FEF] focus:ring-[#5B5FEF] border-gray-300 rounded">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-700">Remember me</label>
                </div>
            </div>

            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-full shadow-sm text-sm font-bold text-white bg-gradient-to-r from-indigo-500 to-violet-500 hover:from-indigo-600 hover:to-violet-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                Sign In
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            Belum punya akun? 
            <a href="<?= BASEURL; ?>/auth/register" class="font-medium text-[#5B5FEF] hover:text-[#7B61FF]">Daftar di sini</a>
        </p>
    </div>
</div>
