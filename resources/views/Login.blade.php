<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>POS System - Admin Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: radial-gradient(circle at top right, #4f46e5, transparent), 
                        radial-gradient(circle at bottom left, #7c3aed, transparent);
            background-color: #0f172a;
        }
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        body.light-mode {
            background: radial-gradient(circle at top right, #e0e7ff, transparent), 
                        radial-gradient(circle at bottom left, #f3e8ff, transparent);
            background-color: #f8fafc;
        }
        body.light-mode .glass {
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        }
        .input-focus:focus-within {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }
    </style>
</head>
<body class="h-full flex items-center justify-center p-6 transition-colors duration-500">

    <div class="fixed top-6 right-6">
        <button onclick="toggleMode()" class="glass p-3 rounded-2xl cursor-pointer hover:scale-110 transition-transform duration-200">
            <i id="theme-icon" class="fas fa-moon text-indigo-500 dark:text-indigo-400 text-xl"></i>
        </button>
    </div>

    <div class="w-full max-w-md">
        <div class="glass p-10 rounded-[2.5rem] relative overflow-hidden">
            <!-- Decorative blur shapes -->
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-indigo-500/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-purple-500/20 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <div class="flex flex-col items-center mb-10">
                    <div class="w-20 h-20 bg-indigo-600 rounded-3xl flex items-center justify-center shadow-lg shadow-indigo-500/30 mb-6 rotate-3">
                        <i class="fas fa-mug-hot text-white text-3xl -rotate-3"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">Welcome Back</h1>
                    <p class="text-slate-500 dark:text-slate-400 text-center">Login to manage your POS system</p>
                </div>

                <form action="{{ route('login.post') }}" method="post" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label for="email" class="text-sm font-medium text-slate-700 dark:text-slate-300 ml-1">Email Address</label>
                        <div class="input-focus flex items-center bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl transition-all duration-200">
                            <i class="fas fa-envelope text-slate-400 ml-4"></i>
                            <input type="email" id="email" name="email" placeholder="name@company.com" required 
                                class="w-full py-4 px-4 bg-transparent border-none outline-none text-slate-900 dark:text-white placeholder:text-slate-400 rounded-2xl" />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center ml-1">
                            <label for="password" class="text-sm font-medium text-slate-700 dark:text-slate-300">Password</label>
                            <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors">Forgot?</a>
                        </div>
                        <div class="input-focus flex items-center bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl transition-all duration-200">
                            <i class="fas fa-lock text-slate-400 ml-4"></i>
                            <input type="password" id="password" name="password" placeholder="••••••••" required 
                                class="w-full py-4 px-4 bg-transparent border-none outline-none text-slate-900 dark:text-white placeholder:text-slate-400 rounded-2xl" />
                            <button type="button" onclick="togglePassword()" class="mr-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                                <i id="password-toggle-icon" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 ml-1">
                        <input type="checkbox" id="remember" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800">
                        <label for="remember" class="text-sm text-slate-600 dark:text-slate-400 cursor-pointer">Remember me</label>
                    </div>

                    <button type="submit" 
                        class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-2xl shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 active:scale-95">
                        Log In
                    </button>

                    <button type="button" onclick="quickLogin()"
                        class="w-full py-3.5 bg-slate-100 dark:bg-slate-800/30 hover:bg-slate-200 dark:hover:bg-slate-800/60 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700/50 font-semibold rounded-2xl transition-all duration-200 active:scale-95 flex items-center justify-center gap-2 cursor-pointer mt-3 text-sm">
                        <i class="fas fa-bolt text-amber-500"></i> Quick Dev Login
                    </button>
                </form>

                <div class="mt-8 text-center pt-8 border-t border-slate-200 dark:border-slate-700/50">
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Protected by secure 256-bit encryption
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById("password");
            const icon = document.getElementById("password-toggle-icon");
            if (password.type === "password") {
                password.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                password.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }

        function quickLogin() {
            document.getElementById("email").value = "satchhunseak123@gmail.com";
            document.getElementById("password").value = "123";
            document.querySelector("form").submit();
        }

        function toggleMode() {
            const isDark = document.documentElement.classList.toggle("dark");
            if (isDark) {
                document.body.classList.remove("light-mode");
                document.getElementById('theme-icon').className = 'fas fa-sun text-amber-400 text-xl';
                localStorage.setItem("mode", "dark");
            } else {
                document.body.classList.add("light-mode");
                document.getElementById('theme-icon').className = 'fas fa-moon text-indigo-500 text-xl';
                localStorage.setItem("mode", "light");
            }
        }

        // Initialize theme
        const storedMode = localStorage.getItem("mode");
        if (storedMode === "light") {
            document.documentElement.classList.remove("dark");
            document.body.classList.add("light-mode");
            document.getElementById('theme-icon').className = 'fas fa-moon text-indigo-500 text-xl';
        } else {
            document.documentElement.classList.add("dark");
            document.body.classList.remove("light-mode");
            document.getElementById('theme-icon').className = 'fas fa-sun text-amber-400 text-xl';
        }
    </script>
</body>
</html>

