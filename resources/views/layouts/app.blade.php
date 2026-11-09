<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SmartCafe POS')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    
    <!-- Vite CSS/JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @yield('styles')
</head>
<body class="h-full bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 font-sans transition-colors duration-300">
    
    <!-- App Container -->
    <div class="flex h-full overflow-hidden">
        
        <!-- Mobile Sidebar Backdrop -->
        <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 z-40 bg-slate-950/40 backdrop-blur-sm hidden lg:hidden transition-opacity duration-300"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 flex flex-col w-72 bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl border-r border-slate-200/50 dark:border-slate-800/50 transform -translate-x-full lg:translate-x-0 lg:static transition-transform duration-300 ease-out">
            <!-- Brand Logo -->
            <div class="flex items-center gap-3 px-8 py-6 border-b border-slate-200/50 dark:border-slate-800/50">
                <div class="w-10 h-10 bg-indigo-600 dark:bg-indigo-500 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30 rotate-3">
                    <i class="fas fa-mug-hot text-white text-lg -rotate-3 animate-pulse"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-600 dark:from-indigo-400 dark:to-purple-400 bg-clip-text text-transparent">SmartCafe</h1>
                    <span class="text-xs text-slate-400 dark:text-slate-500 font-medium tracking-wider uppercase">POS System</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-4 px-5 py-3.5 rounded-2xl transition-all duration-200 group {{ request()->is('/') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100/70 dark:hover:bg-slate-800/50' }}">
                    <i class="fas fa-chart-pie text-lg transition-transform group-hover:scale-110"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                
                <a href="{{ route('pos.show') }}" class="flex items-center gap-4 px-5 py-3.5 rounded-2xl transition-all duration-200 group {{ request()->is('Pos') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100/70 dark:hover:bg-slate-800/50' }}">
                    <i class="fas fa-store text-lg transition-transform group-hover:scale-110"></i>
                    <span class="font-medium">POS Terminal</span>
                </a>

                <a href="{{ route('product.show') }}" class="flex items-center gap-4 px-5 py-3.5 rounded-2xl transition-all duration-200 group {{ request()->is('Product*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100/70 dark:hover:bg-slate-800/50' }}">
                    <i class="fas fa-coffee text-lg transition-transform group-hover:scale-110"></i>
                    <span class="font-medium">Products</span>
                </a>

                <a href="{{ route('invoice.show') }}" class="flex items-center gap-4 px-5 py-3.5 rounded-2xl transition-all duration-200 group {{ request()->is('Invoice*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100/70 dark:hover:bg-slate-800/50' }}">
                    <i class="fas fa-receipt text-lg transition-transform group-hover:scale-110"></i>
                    <span class="font-medium">Invoices</span>
                </a>

                <a href="{{ route('calendar.show') }}" class="flex items-center gap-4 px-5 py-3.5 rounded-2xl transition-all duration-200 group {{ request()->is('Calendar*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100/70 dark:hover:bg-slate-800/50' }}">
                    <i class="fas fa-calendar-alt text-lg transition-transform group-hover:scale-110"></i>
                    <span class="font-medium">Sales Calendar</span>
                </a>

                @if(strtolower(session('role')) === 'manager')
                <a href="{{ route('user.show') }}" class="flex items-center gap-4 px-5 py-3.5 rounded-2xl transition-all duration-200 group {{ request()->is('User*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100/70 dark:hover:bg-slate-800/50' }}">
                    <i class="fas fa-users text-lg transition-transform group-hover:scale-110"></i>
                    <span class="font-medium">Cashiers</span>
                </a>
                @endif
            </nav>

            <!-- Bottom User Info & Logout -->
            <div class="p-6 border-t border-slate-200/50 dark:border-slate-800/50 bg-slate-50/50 dark:bg-slate-900/40">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold shadow-md shadow-indigo-500/20">
                        {{ strtoupper(substr(session('name', 'C'), 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate text-slate-800 dark:text-slate-200">{{ session('name', 'Cashier') }}</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500 truncate font-medium">Logged In</p>
                    </div>
                </div>
                <a href="{{ route('logout') }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/20 dark:hover:bg-rose-950/40 text-rose-600 dark:text-rose-400 font-semibold rounded-2xl transition-all duration-200 hover:shadow-lg hover:shadow-rose-500/10">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Log Out</span>
                </a>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <div class="flex-1 flex flex-col h-full overflow-hidden">
            
            <!-- Topbar Header -->
            <header class="h-20 bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl border-b border-slate-200/50 dark:border-slate-800/50 px-6 lg:px-8 flex items-center justify-between shrink-0">
                <!-- Mobile Open Menu Button -->
                <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-850 cursor-pointer transition-colors duration-200">
                    <i class="fas fa-bars text-xl"></i>
                </button>

                <!-- Page Header Info -->
                <div class="hidden sm:block">
                    <h2 class="text-xl font-bold text-slate-950 dark:text-white">@yield('page_title', 'Dashboard')</h2>
                    <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">SmartPOS Cafe Operations</p>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-4">
                    <!-- Live Clock -->
                    <div id="live-clock" class="hidden md:flex flex-col text-right font-medium text-slate-500 dark:text-slate-400 text-xs py-1.5 px-3 bg-slate-100/50 dark:bg-slate-800/50 rounded-xl">
                        <span class="text-slate-400 dark:text-slate-500" id="clock-date">--/--/----</span>
                        <span class="text-slate-950 dark:text-white font-semibold" id="clock-time">00:00:00 AM</span>
                    </div>

                    <!-- Theme Toggle -->
                    <button onclick="toggleThemeMode()" class="p-3 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/50 dark:border-slate-800/50 text-indigo-600 dark:text-indigo-400 cursor-pointer shadow-sm hover:scale-105 transition-transform duration-200">
                        <i id="header-theme-icon" class="fas fa-moon text-lg"></i>
                    </button>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6 lg:p-8 relative">
                
                <!-- Notification Alerts -->
                @if(session('success'))
                    <div id="alert-success" class="mb-6 flex items-center justify-between p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800/30 rounded-2xl text-emerald-800 dark:text-emerald-300 shadow-md animate-fade-in">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-circle-check text-xl"></i>
                            <span class="font-medium text-sm">{{ session('success') }}</span>
                        </div>
                        <button onclick="dismissAlert('alert-success')" class="text-emerald-400 hover:text-emerald-600 dark:hover:text-emerald-200"><i class="fas fa-times"></i></button>
                    </div>
                @endif

                @if(session('error'))
                    <div id="alert-error" class="mb-6 flex items-center justify-between p-4 bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-800/30 rounded-2xl text-rose-800 dark:text-rose-300 shadow-md animate-fade-in">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-circle-exclamation text-xl"></i>
                            <span class="font-medium text-sm">{{ session('error') }}</span>
                        </div>
                        <button onclick="dismissAlert('alert-error')" class="text-rose-400 hover:text-rose-600 dark:hover:text-rose-200"><i class="fas fa-times"></i></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>

    </div>

    <!-- Scripts -->
    <script>
        // Theme initialization
        const storedThemeMode = localStorage.getItem("mode");
        if (storedThemeMode === "light") {
            document.documentElement.classList.remove("dark");
            document.getElementById('header-theme-icon').className = 'fas fa-moon text-lg';
        } else {
            document.documentElement.classList.add("dark");
            document.getElementById('header-theme-icon').className = 'fas fa-sun text-lg text-amber-400';
        }

        // Toggle Theme
        function toggleThemeMode() {
            const isDark = document.documentElement.classList.toggle("dark");
            if (isDark) {
                document.getElementById('header-theme-icon').className = 'fas fa-sun text-lg text-amber-400';
                localStorage.setItem("mode", "dark");
            } else {
                document.getElementById('header-theme-icon').className = 'fas fa-moon text-lg';
                localStorage.setItem("mode", "light");
            }
        }

        // Sidebar drawer handling
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }

        // Dismiss Alerts
        function dismissAlert(alertId) {
            const alert = document.getElementById(alertId);
            if(alert) {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }
        }

        // Auto-dismiss notification alerts
        setTimeout(() => {
            const sAlert = document.getElementById('alert-success');
            const eAlert = document.getElementById('alert-error');
            if (sAlert) dismissAlert('alert-success');
            if (eAlert) dismissAlert('alert-error');
        }, 5000);

        // Clock script
        function updateClock() {
            const dateElement = document.getElementById('clock-date');
            const timeElement = document.getElementById('clock-time');
            if (!timeElement) return;

            const now = new Date();
            const dateOptions = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };
            dateElement.textContent = now.toLocaleDateString('en-US', dateOptions);

            let hours = now.getHours();
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // convert 0 to 12
            timeElement.textContent = `${String(hours).padStart(2, '0')}:${minutes}:${seconds} ${ampm}`;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
    @yield('scripts')
</body>
</html>
