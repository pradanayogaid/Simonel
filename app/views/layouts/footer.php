<?php if (isset($_SESSION['user'])) : ?>
    <!-- Footer -->
    <footer class="mt-auto py-8 px-10">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 pt-8 border-t border-gray-100">
            <div class="flex items-center gap-2 text-[#5B5FEF]">
                <i class='bx bxs-zap text-xl'></i>
                <span class="font-bold tracking-tight">SIMONEL <span class="text-gray-300 font-medium">v1.0</span></span>
            </div>
            <p class="text-gray-400 text-sm font-medium">
                &copy; <?= date('Y'); ?> Monitoring Listrik Terpadu. Built for Excellence.
            </p>
        </div>
    </footer>
    </main>
<?php endif; ?>

    <!-- Toast Notification Container -->
    <div x-data="{ 
            show: false, 
            message: '', 
            type: 'info',
            showToast(msg, t = 'info') {
                this.message = msg;
                this.type = t;
                this.show = true;
                setTimeout(() => { this.show = false; }, 3000);
            }
         }"
         @toast.window="showToast($event.detail.message, $event.detail.type)"
         class="fixed bottom-10 right-10 z-[100]">
        
        <div x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-10"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-10"
             :class="{
                'bg-gray-900': type === 'info',
                'bg-green-500': type === 'success',
                'bg-red-500': type === 'error'
             }"
             class="flex items-center gap-3 px-6 py-4 rounded-2xl shadow-2xl text-white min-w-[300px]">
            
            <i class='bx text-2xl' :class="{
                'bx-info-circle': type === 'info',
                'bx-check-circle': type === 'success',
                'bx-error-circle': type === 'error'
            }"></i>
            <span x-text="message" class="font-semibold"></span>
        </div>
    </div>

    <script>
        function showToast(message, type = 'info') {
            window.dispatchEvent(new CustomEvent('toast', { 
                detail: { message, type } 
            }));
        }

        // Auto-show session messages as toasts
        <?php if (!empty($_SESSION['success'])) : ?>
            showToast("<?= $_SESSION['success']; ?>", "success");
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error'])) : ?>
            showToast("<?= $_SESSION['error']; ?>", "error");
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
