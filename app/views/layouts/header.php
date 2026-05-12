<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title']; ?> - SIMONEL</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .swal2-popup { border-radius: 32px !important; font-family: 'Outfit', sans-serif !important; }
    </style>
</head>
<body class="bg-[#F5F7FB] min-h-screen text-[#1E1E2D] <?= isset($_SESSION['user']) ? 'flex' : ''; ?>" x-data="{ minimized: false }">

<?php if (isset($_SESSION['user'])) : ?>
    <!-- Sidebar -->
    <aside 
        :class="minimized ? 'w-24' : 'w-72'"
        class="bg-white min-h-screen sticky top-0 shadow-sm rounded-r-[40px] flex flex-col p-6 z-50 transition-all duration-300 ease-in-out">
        
        <div class="mb-10 flex items-center justify-between px-2">
            <h1 x-show="!minimized" x-transition class="text-2xl font-bold text-[#5B5FEF] flex items-center gap-2 overflow-hidden whitespace-nowrap">
                <i class='bx bxs-zap'></i> SIMONEL
            </h1>
            <button @click="minimized = !minimized" class="p-2 rounded-xl bg-gray-50 text-gray-400 hover:text-[#5B5FEF] transition-colors">
                <i class='bx' :class="minimized ? 'bx-chevron-right' : 'bx-chevron-left'"></i>
            </button>
        </div>

        <nav class="flex-1 space-y-2">
            <a href="<?= BASEURL; ?>/dashboard" 
               class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all <?= $data['title'] == 'Dashboard' ? 'bg-[#5B5FEF] text-white shadow-lg shadow-indigo-100' : 'text-gray-500 hover:bg-gray-50'; ?>"
               :title="minimized ? 'Dashboard' : ''">
                <i class='bx bxs-dashboard text-xl min-w-[24px]'></i>
                <span x-show="!minimized" x-transition class="font-semibold overflow-hidden whitespace-nowrap">Dashboard</span>
            </a>
            
            <a href="<?= BASEURL; ?>/realtime" 
               class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all <?= $data['title'] == 'Realtime' ? 'bg-[#5B5FEF] text-white shadow-lg shadow-indigo-100' : 'text-gray-500 hover:bg-gray-50'; ?>"
               :title="minimized ? 'Realtime' : ''">
                <i class='bx bxs-bar-chart-alt-2 text-xl min-w-[24px]'></i>
                <span x-show="!minimized" x-transition class="font-semibold overflow-hidden whitespace-nowrap">Realtime</span>
            </a>

            <?php if ($_SESSION['user']['role'] === 'admin') : ?>
            <a href="<?= BASEURL; ?>/device" 
               class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all <?= in_array($data['title'], ['Devices', 'Add Device', 'Edit Device']) ? 'bg-[#5B5FEF] text-white shadow-lg shadow-indigo-100' : 'text-gray-500 hover:bg-gray-50'; ?>"
               :title="minimized ? 'Devices' : ''">
                <i class='bx bxs-devices text-xl min-w-[24px]'></i>
                <span x-show="!minimized" x-transition class="font-semibold overflow-hidden whitespace-nowrap">Devices</span>
            </a>
            <?php endif; ?>

            <a href="<?= BASEURL; ?>/export" 
               class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all <?= $data['title'] == 'Export' ? 'bg-[#5B5FEF] text-white shadow-lg shadow-indigo-100' : 'text-gray-500 hover:bg-gray-50'; ?>"
               :title="minimized ? 'Export' : ''">
                <i class='bx bxs-cloud-download text-xl min-w-[24px]'></i>
                <span x-show="!minimized" x-transition class="font-semibold overflow-hidden whitespace-nowrap">Export</span>
            </a>
            
            <a href="<?= BASEURL; ?>/log" 
               class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all <?= $data['title'] == 'Logs' ? 'bg-[#5B5FEF] text-white shadow-lg shadow-indigo-100' : 'text-gray-500 hover:bg-gray-50'; ?>"
               :title="minimized ? 'Logs' : ''">
                <i class='bx bxs-time-five text-xl min-w-[24px]'></i>
                <span x-show="!minimized" x-transition class="font-semibold overflow-hidden whitespace-nowrap">Logs</span>
            </a>
        </nav>

        <div class="mt-auto pt-10" x-data="{ settingsOpen: false }">
            <!-- Settings Submenu Trigger -->
            <div class="relative">
                <!-- Submenu content (appears upwards) -->
                <div x-show="settingsOpen" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     @click.away="settingsOpen = false"
                     :class="minimized ? 'w-12 left-1/2 -translate-x-1/2' : 'w-full left-0'"
                     class="absolute bottom-full mb-4 bg-gray-50 rounded-3xl p-1.5 space-y-1 shadow-inner border border-gray-100 flex flex-col items-center">
                    
                    <?php if ($_SESSION['user']['role'] === 'admin') : ?>
                    <a href="<?= BASEURL; ?>/user" 
                       class="flex items-center rounded-2xl transition-all <?= in_array($data['title'], ['User Management', 'Add User', 'Edit User']) ? 'bg-white text-[#5B5FEF] shadow-sm' : 'text-gray-500 hover:bg-white hover:text-[#5B5FEF]'; ?>"
                       :class="minimized ? 'p-3 justify-center' : 'px-4 py-3 gap-4 w-full'"
                       :title="minimized ? 'Users' : ''">
                        <i class='bx bxs-group text-xl min-w-[24px] flex items-center justify-center'></i>
                        <span x-show="!minimized" class="font-semibold">Users</span>
                    </a>
                    <?php endif; ?>
                    
                    <a href="<?= BASEURL; ?>/profile" 
                       class="flex items-center rounded-2xl transition-all <?= $data['title'] == 'My Account' ? 'bg-white text-[#5B5FEF] shadow-sm' : 'text-gray-500 hover:bg-white hover:text-[#5B5FEF]'; ?>"
                       :class="minimized ? 'p-3 justify-center' : 'px-4 py-3 gap-4 w-full'"
                       :title="minimized ? 'Account' : ''">
                        <i class='bx bxs-user-circle text-xl min-w-[24px] flex items-center justify-center'></i>
                        <span x-show="!minimized" class="font-semibold">Account</span>
                    </a>
                </div>

                <!-- Main Settings Button -->
                <button @click="settingsOpen = !settingsOpen" 
                        class="w-full flex items-center rounded-2xl transition-all <?= in_array($data['title'], ['User Management', 'Add User', 'Edit User', 'My Account']) ? 'bg-[#5B5FEF] text-white shadow-lg' : 'text-gray-500 hover:bg-gray-50'; ?>"
                        :class="minimized ? 'p-4 justify-center' : 'px-4 py-3 justify-between'">
                    <div class="flex items-center gap-4">
                        <i class='bx bxs-cog text-xl min-w-[24px]'></i>
                        <span x-show="!minimized" class="font-semibold">Settings</span>
                    </div>
                    <i x-show="!minimized" class="bx transition-transform duration-200" :class="settingsOpen ? 'bx-chevron-down' : 'bx-chevron-up'"></i>
                </button>
            </div>
        </div>
    </aside>

    <!-- Main Content wrapper -->
    <main class="flex-1 h-screen overflow-y-auto flex flex-col">
<?php endif; ?>
