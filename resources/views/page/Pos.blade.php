<!DOCTYPE html>
<html lang="en" class="h-full">
<head> 
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>SmartCafe - Cashier Terminal</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    
    <!-- Vite CSS/JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes scan {
            0% { top: 0%; }
            50% { top: 100%; }
            100% { top: 0%; }
        }
    </style>
</head>
<body class="h-full bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 font-sans transition-colors duration-300 flex flex-col overflow-hidden">
    <header class="h-16 shrink-0 bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl border-b border-slate-200/50 dark:border-slate-800/50 px-6 flex items-center justify-between z-10">
        <div class="flex items-center gap-3">
            @if(strtolower(session('role')) === 'manager' || strtolower(session('role')) === 'assistant')
            <a href="{{ route('dashboard') }}" class="w-9 h-9 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:border-indigo-200 dark:hover:border-indigo-900/50 flex items-center justify-center transition-all cursor-pointer hover:scale-105 active:scale-95">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>
            @endif
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-indigo-600 dark:bg-indigo-500 rounded-lg flex items-center justify-center text-white">
                    <i class="fas fa-mug-hot text-sm"></i>
                </div>
                <h1 class="font-bold text-lg text-slate-950 dark:text-white">SmartCafe POS</h1>
            </div>
        </div>

        <!-- System controls -->
        <div class="flex items-center gap-4">
            <div class="hidden sm:flex flex-col text-right font-medium text-xs">
                <span class="text-slate-950 dark:text-white font-semibold">{{ session('name', 'Cashier') }}</span>
                <span class="text-slate-400 dark:text-slate-500">Terminal #01</span>
            </div>
            
            <button onclick="togglePOSTheme()" class="w-9 h-9 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/50 dark:border-slate-800/50 text-indigo-600 dark:text-indigo-400 cursor-pointer shadow-sm hover:scale-105 transition-transform duration-200 flex items-center justify-center">
                <i id="theme-icon" class="fas fa-moon text-sm"></i>
            </button>

            <a href="{{ route('logout') }}" class="w-9 h-9 rounded-xl bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/20 dark:hover:bg-rose-950/40 text-rose-600 dark:text-rose-400 border border-rose-200/20 dark:border-rose-900/30 flex items-center justify-center hover:scale-105 active:scale-95 transition-all duration-200 cursor-pointer shadow-sm" title="Log Out">
                <i class="fas fa-sign-out-alt text-sm"></i>
            </a>
        </div>
    </header>

    <!-- POS Main Grid Layout -->
    <div class="flex-1 flex flex-col md:flex-row overflow-hidden relative">
        
        <!-- Left Side: Product catalog panel -->
        <div id="catalog-panel" class="flex-1 flex flex-col overflow-hidden p-6">
            <!-- Filter Categories -->
            <div class="shrink-0 flex items-center gap-3 overflow-x-auto pb-3 scrollbar-hide no-print">
                <button onclick="filterProducts('All')" id="filter-All" class="filter-card shrink-0 flex items-center gap-2 px-5 py-3 rounded-2xl font-semibold text-sm transition-all duration-200 border cursor-pointer bg-indigo-600 border-indigo-600 text-white shadow-md shadow-indigo-500/20 active">
                    <i class="fas fa-border-all"></i>
                    <span>All Items</span>
                </button>
                <button onclick="filterProducts(1)" id="filter-1" class="filter-card shrink-0 flex items-center gap-2 px-5 py-3 rounded-2xl font-semibold text-sm transition-all duration-200 border cursor-pointer bg-white dark:bg-slate-900 border-slate-200/50 dark:border-slate-800/50 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">
                    <i class="fas fa-snowflake text-cyan-500"></i>
                    <span>Ice Drink</span>
                </button>
                <button onclick="filterProducts(2)" id="filter-2" class="filter-card shrink-0 flex items-center gap-2 px-5 py-3 rounded-2xl font-semibold text-sm transition-all duration-200 border cursor-pointer bg-white dark:bg-slate-900 border-slate-200/50 dark:border-slate-800/50 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">
                    <i class="fas fa-mug-hot text-amber-500"></i>
                    <span>Hot Drink</span>
                </button>
                <button onclick="filterProducts(3)" id="filter-3" class="filter-card shrink-0 flex items-center gap-2 px-5 py-3 rounded-2xl font-semibold text-sm transition-all duration-200 border cursor-pointer bg-white dark:bg-slate-900 border-slate-200/50 dark:border-slate-800/50 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">
                    <i class="fas fa-blender text-purple-500"></i>
                    <span>Frappe</span>
                </button>
                <button onclick="filterProducts(4)" id="filter-4" class="filter-card shrink-0 flex items-center gap-2 px-5 py-3 rounded-2xl font-semibold text-sm transition-all duration-200 border cursor-pointer bg-white dark:bg-slate-900 border-slate-200/50 dark:border-slate-800/50 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">
                    <i class="fas fa-ice-cream text-emerald-500"></i>
                    <span>Toppings</span>
                </button>
            </div>

            <!-- Product Cards Catalog Scroll Grid -->
            <div class="flex-1 overflow-y-auto pr-1">
                <div id="product-list" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 py-2">
                    <!-- Loaded dynamically via js -->
                </div>
            </div>
        </div>

        <!-- Right Side: Interactive Checkout Cart panel -->
        <div id="cart-panel" class="hidden md:flex w-full md:w-[420px] shrink-0 border-t md:border-t-0 md:border-l border-slate-200/50 dark:border-slate-800/50 bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl flex flex-col overflow-hidden relative shadow-2xl">
            <!-- Cart Header -->
            <div class="px-6 py-4 border-b border-slate-200/50 dark:border-slate-800/50 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-2">
                    <i class="fas fa-shopping-cart text-slate-400"></i>
                    <h2 class="font-bold text-base text-slate-950 dark:text-white">Active Order Cart</h2>
                </div>
                <button onclick="clearCart()" class="text-xs font-semibold text-rose-500 hover:text-rose-600 py-1.5 px-3 bg-rose-50 dark:bg-rose-950/20 hover:bg-rose-100 rounded-xl transition-all cursor-pointer">
                    Clear All
                </button>
            </div>

            <!-- Cart list scroll container -->
            <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4" id="cart-items-container">
                <!-- Rendered dynamically via js -->
            </div>

            <!-- Total Pricing & Cash Checkout area -->
            <div class="shrink-0 p-6 border-t border-slate-200/50 dark:border-slate-800/50 bg-slate-50/50 dark:bg-slate-900/40 space-y-4">
                
                <!-- Daily Sales Target Progress -->
                <div class="space-y-1.5 bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200/30 dark:border-slate-800/20 shadow-sm no-print select-none">
                    <div class="flex items-center justify-between text-[11px] font-bold">
                        <span class="text-slate-500 dark:text-slate-400">Daily Sales Target</span>
                        <span class="text-indigo-600 dark:text-indigo-400"><span id="target-progress-pct">0</span>%</span>
                    </div>
                    <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-950 rounded-full overflow-hidden">
                        <div id="target-progress-bar" class="h-full bg-gradient-to-r from-indigo-500 to-emerald-500 transition-all duration-500" style="width: 0%"></div>
                    </div>
                    <div class="flex items-center justify-between text-[9px] font-semibold text-slate-400 dark:text-slate-500">
                        <span>Progress: $<span id="target-sales-current">0.00</span></span>
                        <span>Target: $<span id="target-sales-goal">500.00</span></span>
                    </div>
                </div>

                <!-- Totals Display Grid -->
                <div class="space-y-1">
                    <div class="flex items-center justify-between font-bold text-slate-950 dark:text-white text-lg">
                        <span>Total Due</span>
                        <span>$<span id="total-price">0.00</span></span>
                    </div>
                    <div class="flex items-center justify-between text-xs font-semibold text-slate-400 dark:text-slate-500">
                        <span>In Cambodian Riel</span>
                        <span>៛<span id="total-khr">0</span></span>
                    </div>
                </div>

                <div class="border-b border-slate-200/50 dark:border-slate-850 my-1"></div>

                <!-- Payment Method Selector -->
                <div class="space-y-1.5">
                    <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider ml-1">Payment Method</span>
                    <div class="grid grid-cols-2 gap-2 bg-slate-100 dark:bg-slate-950/40 p-1.5 rounded-2xl border border-slate-200/50 dark:border-slate-800/30">
                        <button onclick="selectPaymentMethod('Cash')" id="pay-method-Cash" class="py-2.5 rounded-xl text-xs font-bold transition-all duration-200 cursor-pointer flex items-center justify-center gap-2 bg-white dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 shadow-sm border border-slate-200/30 dark:border-slate-800/20">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Cash</span>
                        </button>
                        <button onclick="selectPaymentMethod('KHQR')" id="pay-method-KHQR" class="py-2.5 rounded-xl text-xs font-bold transition-all duration-200 cursor-pointer flex items-center justify-center gap-2 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300">
                            <i class="fas fa-qrcode"></i>
                            <span>KHQR</span>
                        </button>
                    </div>
                </div>

                <div class="border-b border-slate-200/50 dark:border-slate-850 my-1"></div>

                <!-- Cash input & quick keys container -->
                <div id="cash-checkout-section" class="space-y-4">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <label id="received-label" for="received-cash" class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider ml-1">Received Amount</label>
                            <button onclick="toggleCurrency()" id="toggle-currency-btn" class="text-[10px] font-bold tracking-wider uppercase bg-indigo-50 dark:bg-indigo-950/20 text-indigo-600 dark:text-indigo-400 py-1 px-2.5 rounded-lg border border-indigo-100 dark:border-indigo-900/30 cursor-pointer">
                                Cash In: USD
                            </button>
                        </div>

                        <!-- Cash Input Field -->
                        <div class="flex items-center bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl focus-within:border-indigo-600 focus-within:ring-4 focus-within:ring-indigo-500/10 transition-all duration-200 shadow-sm">
                            <span id="cash-input-symbol" class="font-bold text-slate-400 ml-4 text-base">$</span>
                            <input 
                                type="number" 
                                step="0.01"
                                id="received-cash" 
                                placeholder="Enter cash payment" 
                                class="w-full py-3.5 px-3 bg-transparent border-none outline-none text-slate-900 dark:text-white placeholder:text-slate-400 font-bold text-base"
                                oninput="calculateChange()"
                            />
                        </div>

                        <!-- Quick Cash helper buttons -->
                        <div class="grid grid-cols-4 gap-2 no-print">
                            <button onclick="quickCash(5)" class="py-2.5 bg-white dark:bg-slate-900 border border-slate-200/50 dark:border-slate-850 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl font-bold text-xs cursor-pointer shadow-sm active:scale-95 transition-all">$5</button>
                            <button onclick="quickCash(10)" class="py-2.5 bg-white dark:bg-slate-900 border border-slate-200/50 dark:border-slate-850 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl font-bold text-xs cursor-pointer shadow-sm active:scale-95 transition-all">$10</button>
                            <button onclick="quickCash(20)" class="py-2.5 bg-white dark:bg-slate-900 border border-slate-200/50 dark:border-slate-850 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl font-bold text-xs cursor-pointer shadow-sm active:scale-95 transition-all">$20</button>
                            <button onclick="quickCash('exact')" class="py-2.5 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/20 dark:hover:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-900/30 rounded-xl font-bold text-xs cursor-pointer shadow-sm active:scale-95 transition-all">Exact</button>
                        </div>
                    </div>

                    <div class="border-b border-slate-200/50 dark:border-slate-850 my-1"></div>

                    <!-- Change Displays -->
                    <div class="space-y-1">
                        <div class="flex items-center justify-between font-bold text-indigo-600 dark:text-indigo-400 text-base">
                            <span>Change Due</span>
                            <span>$<span id="change">0.00</span></span>
                        </div>
                        <div class="flex items-center justify-between text-xs font-semibold text-indigo-500/80 dark:text-indigo-400/80">
                            <span>In Cambodian Riel</span>
                            <span>៛<span id="change-khr">0</span></span>
                        </div>
                    </div>
                </div>

                <!-- KHQR status section -->
                <div id="khqr-status-section" class="hidden space-y-3 py-2 px-1 text-center animate-pulse">
                    <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-950/45 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center mx-auto text-xl border border-indigo-100 dark:border-indigo-900/30">
                        <i class="fas fa-qrcode"></i>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Scan KHQR Payment Gate</span>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">A dynamic QR code will be generated on checkout.</p>
                    </div>
                </div>

                <!-- Pay Button -->
                <button onclick="payNow()" class="w-full flex items-center justify-center gap-2 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-xl shadow-indigo-500/20 hover:shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 cursor-pointer mt-2">
                    <i class="fas fa-circle-check"></i>
                    <span>Checkout & Print</span>
                </button>

            </div>

        </div>

    </div>

    <!-- Mobile Bottom Tab Bar -->
    <div class="md:hidden shrink-0 h-16 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-t border-slate-200/50 dark:border-slate-800/50 flex items-center justify-around z-20 no-print">
        <button onclick="switchTab('catalog')" id="tab-catalog" class="flex flex-col items-center gap-1 text-indigo-600 dark:text-indigo-400 font-bold transition-all cursor-pointer bg-transparent border-none outline-none">
            <i class="fas fa-store text-base"></i>
            <span class="text-[10px]">Catalog</span>
        </button>
        <button onclick="switchTab('cart')" id="tab-cart" class="flex flex-col items-center gap-1 text-slate-400 dark:text-slate-500 font-medium transition-all relative cursor-pointer bg-transparent border-none outline-none">
            <div class="relative">
                <i class="fas fa-shopping-cart text-base"></i>
                <span id="mobile-cart-badge" class="absolute -top-1.5 -right-3.5 bg-rose-500 text-white text-[9px] w-4.5 h-4.5 rounded-full flex items-center justify-center font-bold hidden">0</span>
            </div>
            <span class="text-[10px]">Cart ($<span id="mobile-cart-total">0.00</span>)</span>
        </button>
    </div>

    <!-- Scripts -->
    <script>
        // Theme initialization
        const savedPOSTheme = localStorage.getItem("mode");
        if (savedPOSTheme === "light") {
            document.documentElement.classList.remove("dark");
            document.getElementById('theme-icon').className = 'fas fa-moon text-sm';
        } else {
            document.documentElement.classList.add("dark");
            document.getElementById('theme-icon').className = 'fas fa-sun text-sm text-amber-400';
        }

        let activePaymentMethod = 'Cash'; // Default payment method
        let khqrPollingInterval = null;
        let khqrMD5 = null;
        let qrCurrency = 'USD'; // Default currency for QR Code
        
        let todaySales = {{ $todaySales ?? 0 }};
        const dailyTarget = {{ $dailyTarget ?? 500.00 }};

        function updateSalesTargetProgress() {
            const currentLabel = document.getElementById('target-sales-current');
            const goalLabel = document.getElementById('target-sales-goal');
            const progressPct = document.getElementById('target-progress-pct');
            const progressBar = document.getElementById('target-progress-bar');
            
            if (currentLabel && goalLabel && progressPct && progressBar) {
                currentLabel.innerText = todaySales.toFixed(2);
                goalLabel.innerText = dailyTarget.toFixed(2);
                
                const pct = Math.min(Math.round((todaySales / dailyTarget) * 100), 100);
                progressPct.innerText = pct;
                progressBar.style.width = `${pct}%`;
                
                if (todaySales >= dailyTarget) {
                    progressBar.classList.remove('from-indigo-500', 'to-emerald-500');
                    progressBar.classList.add('bg-emerald-500', 'animate-pulse');
                } else {
                    progressBar.classList.add('from-indigo-500', 'to-emerald-500');
                    progressBar.classList.remove('bg-emerald-500', 'animate-pulse');
                }
            }
        }

        // Toggle POS Theme Mode
        function togglePOSTheme() {
            const isDark = document.documentElement.classList.toggle("dark");
            if (isDark) {
                document.getElementById('theme-icon').className = 'fas fa-sun text-sm text-amber-400';
                localStorage.setItem("mode", "dark");
            } else {
                document.getElementById('theme-icon').className = 'fas fa-moon text-sm';
                localStorage.setItem("mode", "light");
            }
        }

        // Bind products
        const products = {!! json_encode($products) !!};
        let cart = {};
        let currentFilter = 'All';
        let receivedIn = 'USD'; // Input mode currency
        const USD_TO_KHR = 4100;

        // Toggle Payment currency input
        function toggleCurrency() {
            const btn = document.getElementById('toggle-currency-btn');
            const label = document.getElementById('received-label');
            const input = document.getElementById('received-cash');
            const symbol = document.getElementById('cash-input-symbol');

            if (receivedIn === 'USD') {
                receivedIn = 'KHR';
                btn.innerText = 'Cash In: KHR';
                label.innerText = 'Received Cash: KHR (៛)';
                symbol.innerText = '៛';
                input.placeholder = "Enter cash in Riel";
            } else {
                receivedIn = 'USD';
                btn.innerText = 'Cash In: USD';
                label.innerText = 'Received Cash: USD ($)';
                symbol.innerText = '$';
                input.placeholder = "Enter cash in Dollars";
            }
            
            input.value = '';
            calculateChange();
        }

        // Quick cash presets
        function quickCash(amount) {
            const input = document.getElementById('received-cash');
            const totalUSD = parseFloat(document.getElementById('total-price').innerText);
            
            if (amount === 'exact') {
                if (receivedIn === 'USD') {
                    input.value = totalUSD.toFixed(2);
                } else {
                    input.value = Math.round(totalUSD * USD_TO_KHR);
                }
            } else {
                if (receivedIn === 'USD') {
                    input.value = amount;
                } else {
                    input.value = amount * USD_TO_KHR;
                }
            }
            calculateChange();
        }

        // Filter product catalog views
        function filterProducts(catId) {
            currentFilter = catId;
            renderProducts(catId);

            document.querySelectorAll('.filter-card').forEach(btn => {
                btn.classList.remove('bg-indigo-600', 'border-indigo-600', 'text-white', 'shadow-md', 'shadow-indigo-500/20');
                btn.classList.add('bg-white', 'dark:bg-slate-900', 'border-slate-200/50', 'dark:border-slate-800/50', 'text-slate-600', 'dark:text-slate-400');
            });

            const activeBtn = document.getElementById(`filter-${catId.toString()}`);
            if (activeBtn) {
                activeBtn.classList.remove('bg-white', 'dark:bg-slate-900', 'border-slate-200/50', 'dark:border-slate-800/50', 'text-slate-600', 'dark:text-slate-400');
                activeBtn.classList.add('bg-indigo-600', 'border-indigo-600', 'text-white', 'shadow-md', 'shadow-indigo-500/20');
            }
        }

        // Render product grids
        function renderProducts(filter = 'All') {
            const container = document.getElementById('product-list');
            container.innerHTML = '';

            const filtered = filter === 'All' ? products : products.filter(p => p.cat_id == filter);

            filtered.forEach(product => {
                const card = document.createElement('div');
                card.className = "bg-white dark:bg-slate-900 border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-4 shadow-sm hover:shadow-md hover:scale-[1.03] active:scale-95 transition-all duration-200 cursor-pointer flex flex-col group";
                card.onclick = () => addToCart(product);

                // Image Wrapper
                const imgWrap = document.createElement('div');
                imgWrap.className = "relative w-full aspect-square bg-slate-100 dark:bg-slate-800 rounded-2xl overflow-hidden mb-3.5 border border-slate-100 dark:border-slate-850";
                
                const img = document.createElement('img');
                img.src = product.img;
                img.alt = product.name;
                img.className = "w-full h-full object-cover transition-transform duration-300 group-hover:scale-105";
                imgWrap.appendChild(img);

                // Title
                const nameSpan = document.createElement('span');
                nameSpan.className = "font-bold text-sm text-slate-900 dark:text-white truncate block";
                nameSpan.innerText = product.name;

                // Price display
                const priceSpan = document.createElement('span');
                priceSpan.className = "text-xs font-semibold text-indigo-600 dark:text-indigo-400 block mt-1";
                priceSpan.innerText = `$${parseFloat(product.price).toFixed(2)}`;

                card.appendChild(imgWrap);
                card.appendChild(nameSpan);
                card.appendChild(priceSpan);
                container.appendChild(card);
            });
        }

        // Add to POS Cart
        function addToCart(product) {
            if (!cart[product.name]) {
                cart[product.name] = { ...product, qty: 1 };
            } else {
                cart[product.name].qty += 1;
            }
            updateCartDisplay();

            // Visual feedback on mobile cart badge
            const badge = document.getElementById('mobile-cart-badge');
            if (badge) {
                badge.classList.remove('hidden');
                badge.classList.add('scale-125', 'bg-indigo-600');
                badge.classList.remove('bg-rose-500');
                setTimeout(() => {
                    badge.classList.remove('scale-125', 'bg-indigo-600');
                    badge.classList.add('bg-rose-500');
                }, 300);
            }
        }

        // Redraw POS Cart Panel
        function updateCartDisplay() {
            const container = document.getElementById('cart-items-container');
            container.innerHTML = '';
            let total = 0;

            const cartItems = Object.values(cart);
            if (cartItems.length === 0) {
                container.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-48 text-center text-slate-400/80">
                        <i class="fas fa-cart-shopping text-3xl mb-3 opacity-60"></i>
                        <p class="text-sm font-semibold">Your shopping cart is empty</p>
                        <p class="text-xs text-slate-400 mt-1">Select items from catalog to start</p>
                    </div>
                `;
                document.getElementById('total-price').innerText = '0.00';
                document.getElementById('total-khr').innerText = '0';
                calculateChange();
                return;
            }

            cartItems.forEach(item => {
                const itemTotal = item.price * item.qty;
                total += itemTotal;

                const itemRow = document.createElement('div');
                itemRow.className = "flex items-center gap-3 p-3 bg-white dark:bg-slate-900 border border-slate-200/50 dark:border-slate-800/50 rounded-2xl shadow-sm hover:shadow transition-shadow";
                
                itemRow.innerHTML = `
                    <div class="w-12 h-12 rounded-xl overflow-hidden shrink-0 border border-slate-100 dark:border-slate-850">
                        <img src="${item.img}" alt="${item.name}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="font-bold text-xs text-slate-900 dark:text-white truncate block">${item.name}</span>
                        <span class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 block mt-0.5">$${parseFloat(item.price).toFixed(2)} / unit</span>
                    </div>
                    <!-- Stepper Picker controls -->
                    <div class="flex items-center border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shrink-0 bg-slate-50 dark:bg-slate-950/40">
                        <button onclick="stepQty('${item.name}', -1)" class="w-7 h-7 flex items-center justify-center text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-bold transition-colors cursor-pointer">-</button>
                        <span class="w-6 text-center font-bold text-xs text-slate-800 dark:text-slate-200 select-none">${item.qty}</span>
                        <button onclick="stepQty('${item.name}', 1)" class="w-7 h-7 flex items-center justify-center text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-bold transition-colors cursor-pointer">+</button>
                    </div>
                    <!-- Sum & Delete -->
                    <div class="text-right shrink-0 min-w-[50px]">
                        <span class="font-bold text-xs text-slate-900 dark:text-white block">$${itemTotal.toFixed(2)}</span>
                    </div>
                    <button onclick="removeFromCart('${item.name}')" class="w-7 h-7 rounded-lg text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all cursor-pointer flex items-center justify-center text-xs shrink-0">
                        <i class="fas fa-trash-can"></i>
                    </button>
                `;
                
                container.appendChild(itemRow);
            });

            document.getElementById('total-price').innerText = total.toFixed(2);
            document.getElementById('total-khr').innerText = Math.round(total * USD_TO_KHR).toLocaleString();

            // Update mobile elements if they exist
            const mobileCartTotal = document.getElementById('mobile-cart-total');
            const mobileCartBadge = document.getElementById('mobile-cart-badge');
            if (mobileCartTotal && mobileCartBadge) {
                let totalQty = 0;
                cartItems.forEach(item => { totalQty += item.qty; });
                mobileCartTotal.innerText = total.toFixed(2);
                mobileCartBadge.innerText = totalQty;
                if (totalQty > 0) {
                    mobileCartBadge.classList.remove('hidden');
                } else {
                    mobileCartBadge.classList.add('hidden');
                }
            }

            calculateChange();
        }

        // Stepper Quantity Increment / Decrement
        function stepQty(productName, offset) {
            if (cart[productName]) {
                const newQty = cart[productName].qty + offset;
                if (newQty > 0) {
                    cart[productName].qty = newQty;
                    updateCartDisplay();
                } else {
                    removeFromCart(productName);
                }
            }
        }

        // Remove single item from cart
        function removeFromCart(productName) {
            if (cart[productName]) {
                delete cart[productName];
                updateCartDisplay();
            }
        }

        // Clear cart checklist
        function clearCart() {
            if (Object.keys(cart).length === 0) return;
            if (confirm("Are you sure you want to discard the active shopping cart?")) {
                cart = {};
                updateCartDisplay();
                document.getElementById('received-cash').value = '';
                document.getElementById('change').innerText = '0.00';
                document.getElementById('change-khr').innerText = '0';
            }
        }

        // Recalculate POS Checkout Cash change
        function calculateChange() {
            const input = document.getElementById('received-cash');
            let receivedCash = parseFloat(input.value);
            if (isNaN(receivedCash)) receivedCash = 0;

            const totalUSD = parseFloat(document.getElementById('total-price').innerText);
            
            let receivedUSD;
            if (receivedIn === 'USD') {
                receivedUSD = receivedCash;
            } else {
                receivedUSD = receivedCash / USD_TO_KHR;
            }

            const changeUSD = receivedUSD - totalUSD;
            const changeDisplay = changeUSD >= 0 ? changeUSD.toFixed(2) : "0.00";
            const changeKHR = changeUSD >= 0 ? Math.round(changeUSD * USD_TO_KHR).toLocaleString() : "0";

            document.getElementById('change').innerText = changeDisplay;
            document.getElementById('change-khr').innerText = changeKHR;
        }

        // Toggle payment selector methods
        function selectPaymentMethod(method) {
            activePaymentMethod = method;
            const cashBtn = document.getElementById('pay-method-Cash');
            const khqrBtn = document.getElementById('pay-method-KHQR');
            
            if (method === 'Cash') {
                cashBtn.className = "py-2.5 rounded-xl text-xs font-bold transition-all duration-200 cursor-pointer flex items-center justify-center gap-2 bg-white dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 shadow-sm border border-slate-200/30 dark:border-slate-800/20";
                khqrBtn.className = "py-2.5 rounded-xl text-xs font-bold transition-all duration-200 cursor-pointer flex items-center justify-center gap-2 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300";
                document.getElementById('cash-checkout-section').classList.remove('hidden');
                document.getElementById('khqr-status-section').classList.add('hidden');
            } else {
                khqrBtn.className = "py-2.5 rounded-xl text-xs font-bold transition-all duration-200 cursor-pointer flex items-center justify-center gap-2 bg-white dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 shadow-sm border border-slate-200/30 dark:border-slate-800/20";
                cashBtn.className = "py-2.5 rounded-xl text-xs font-bold transition-all duration-200 cursor-pointer flex items-center justify-center gap-2 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300";
                document.getElementById('cash-checkout-section').classList.add('hidden');
                document.getElementById('khqr-status-section').classList.remove('hidden');
            }
        }

        // Checkout Cart and Send Data
        function payNow() {
            if (Object.keys(cart).length === 0) return alert("Checkout aborted: Your cart is empty!");

            if (activePaymentMethod === 'KHQR') {
                startKHQRPayment();
                return;
            }

            const total = parseFloat(document.getElementById('total-price').innerText);
            const inputVal = parseFloat(document.getElementById('received-cash').value);
            if (isNaN(inputVal) || inputVal <= 0) return alert("Checkout aborted: Please enter the cash amount received.");
            
            let receivedCashUSD;
            if (receivedIn === 'USD') {
                receivedCashUSD = inputVal;
            } else {
                receivedCashUSD = inputVal / USD_TO_KHR;
            }

            if (receivedCashUSD < total) {
                return alert("Checkout aborted: Insufficient cash payment received.");
            }

            const changeUSD = receivedCashUSD - total;

            fetch('{{ route('pos.checkout') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    cart: Object.values(cart),
                    received: receivedCashUSD,
                    total: total,
                    change: changeUSD,
                    payment_method: 'Cash'
                }),
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    printReceipt();
                    
                    todaySales += total;
                    updateSalesTargetProgress();
                    
                    cart = {};
                    updateCartDisplay();
                    document.getElementById('received-cash').value = '';
                    document.getElementById('change').innerText = '0.00';
                    document.getElementById('change-khr').innerText = '0';
                } else {
                    alert("Checkout failed: Server responded with error.");
                }
            })
            .catch(err => {
                console.error(err);
                alert("An error occurred during checkout processing.");
            });
        }

        // KHQR Modal checkout methods
        function updateKHQRCardAmount(totalUSD) {
            const cardAmount = document.getElementById('khqr-card-amount');
            const centerLogo = document.getElementById('khqr-center-logo');
            const currencyBtn = document.getElementById('khqr-currency-btn');
            
            if (qrCurrency === 'USD') {
                cardAmount.innerText = `$ ${totalUSD.toFixed(2)}`;
                if (centerLogo) {
                    centerLogo.innerText = '$';
                    centerLogo.className = "absolute inset-0 m-auto w-8.5 h-8.5 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-650 border-2 border-white flex items-center justify-center text-white font-extrabold text-sm shadow-md select-none";
                }
                if (currencyBtn) currencyBtn.innerText = 'Pay In: KHR';
            } else {
                const totalKHR = Math.round(totalUSD * USD_TO_KHR);
                cardAmount.innerText = `៛ ${totalKHR.toLocaleString()}`;
                if (centerLogo) {
                    centerLogo.innerText = '៛';
                    centerLogo.className = "absolute inset-0 m-auto w-8.5 h-8.5 rounded-full bg-gradient-to-tr from-red-600 to-rose-600 border-2 border-white flex items-center justify-center text-white font-extrabold text-sm shadow-md select-none";
                }
                if (currencyBtn) currencyBtn.innerText = 'Pay In: USD';
            }
        }

        function startKHQRPayment() {
            const totalUSD = parseFloat(document.getElementById('total-price').innerText);
            if (totalUSD <= 0) return alert("Checkout aborted: Your cart is empty!");

            updateKHQRCardAmount(totalUSD);
            
            document.getElementById('khqr-spinner').classList.remove('hidden');
            document.getElementById('khqr-image-container').classList.add('hidden');
            document.getElementById('khqr-laser').classList.add('hidden');
            document.getElementById('khqr-success').classList.add('hidden');
            document.getElementById('khqr-status-text').innerText = 'Requesting payment code...';

            // Show Modal
            const modal = document.getElementById('khqrModal');
            const content = modal.querySelector('div');
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
            }, 10);

            generateKHQR();
        }

        function toggleQRCurrency() {
            qrCurrency = qrCurrency === 'USD' ? 'KHR' : 'USD';
            
            const totalUSD = parseFloat(document.getElementById('total-price').innerText);
            updateKHQRCardAmount(totalUSD);
            
            if (khqrPollingInterval) {
                clearInterval(khqrPollingInterval);
                khqrPollingInterval = null;
            }
            
            document.getElementById('khqr-spinner').classList.remove('hidden');
            document.getElementById('khqr-image-container').classList.add('hidden');
            document.getElementById('khqr-laser').classList.add('hidden');
            document.getElementById('khqr-status-text').innerText = 'Regenerating code...';
            
            generateKHQR();
        }

        function generateKHQR() {
            const totalUSD = parseFloat(document.getElementById('total-price').innerText);
            let amount = totalUSD;
            if (qrCurrency === 'KHR') {
                amount = Math.round(totalUSD * USD_TO_KHR);
            }
            
            fetch('/pos/generate-qr', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    amount: amount,
                    currency: qrCurrency,
                    billNumber: 'INV-' + Date.now()
                })
            })
            .then(res => res.json())
            .then(resData => {
                if (resData.success && resData.data) {
                    const data = resData.data;
                    khqrMD5 = data.md5Hash;
                    
                    const img = document.getElementById('khqr-image');
                    img.src = data.qrCodeImage;
                    document.getElementById('khqr-image-container').classList.remove('hidden');
                    
                    document.getElementById('khqr-spinner').classList.add('hidden');
                    document.getElementById('khqr-laser').classList.remove('hidden');
                    document.getElementById('khqr-status-text').innerText = 'Awaiting payment scan...';
                    
                    startPaymentPolling();
                } else {
                    document.getElementById('khqr-status-text').innerText = 'Error generating QR.';
                    alert("Error: Failed to generate KHQR code. " + (resData.message || ''));
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById('khqr-status-text').innerText = 'Connection error.';
                alert("Failed to communicate with QR proxy server.");
            });
        }

        function startPaymentPolling() {
            if (khqrPollingInterval) clearInterval(khqrPollingInterval);
            
            khqrPollingInterval = setInterval(() => {
                if (!khqrMD5) return;
                
                fetch('/pos/check-payment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        md5: khqrMD5
                    })
                })
                .then(res => res.json())
                .then(resData => {
                    if (resData.success && resData.data && resData.data.paid) {
                        clearInterval(khqrPollingInterval);
                        khqrPollingInterval = null;
                        confirmKHQRPayment();
                    }
                })
                .catch(err => console.error("Polling error:", err));
            }, 2000);
        }

        function cancelKHQRPayment() {
            if (khqrPollingInterval) {
                clearInterval(khqrPollingInterval);
                khqrPollingInterval = null;
            }
            khqrMD5 = null;
            
            const modal = document.getElementById('khqrModal');
            const content = modal.querySelector('div');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            setTimeout(() => modal.classList.add('hidden'), 150);
        }

        function confirmKHQRPayment() {
            document.getElementById('khqr-image-container').classList.add('hidden');
            document.getElementById('khqr-laser').classList.add('hidden');
            document.getElementById('khqr-success').classList.remove('hidden');
            document.getElementById('khqr-status-text').innerText = 'Payment Confirmed!';
            
            const total = parseFloat(document.getElementById('total-price').innerText);
            
            fetch('{{ route('pos.checkout') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    cart: Object.values(cart),
                    received: total,
                    total: total,
                    change: 0,
                    payment_method: 'KHQR'
                }),
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    printReceipt();
                    
                    todaySales += total;
                    updateSalesTargetProgress();
                    
                    setTimeout(() => {
                        cancelKHQRPayment();
                        cart = {};
                        updateCartDisplay();
                        document.getElementById('received-cash').value = '';
                        document.getElementById('change').innerText = '0.00';
                        document.getElementById('change-khr').innerText = '0';
                    }, 1500);
                } else {
                    alert("Error: Checkout failed after payment confirmation.");
                }
            })
            .catch(err => {
                console.error(err);
                alert("A critical error occurred while creating invoice.");
            });
        }

        // Custom receipt printing via hidden iframe to bypass popup blockers
        function printReceipt() {
            let subtotal = 0;
            const now = new Date();
            const dateStr = now.getFullYear() + '-' + String(now.getMonth()+1).padStart(2,'0') + '-' + String(now.getDate()).padStart(2,'0') + ' ' + String(now.getHours()).padStart(2,'0') + ':' + String(now.getMinutes()).padStart(2,'0');

            let receiptContent = `
                <html>
                <head>
                    <title>SmartCafe Receipt</title>
                    <style>
                        body { 
                            font-family: 'Courier New', Courier, monospace; 
                            padding: 10px; 
                            font-size: 13px; 
                            color: #000;
                            line-height: 1.4;
                            background: #fff;
                        }
                        .text-center { text-align: center; }
                        .text-right { text-align: right; }
                        .bold { font-weight: bold; }
                        .uppercase { text-transform: uppercase; }
                        .dashed-line { border-bottom: 1px dashed #000; margin: 10px 0; }
                        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                        th, td { padding: 4px 0; font-size: 13px; }
                        .total-row { font-size: 14px; font-weight: bold; }
                    </style>
                </head>
                <body>
                    <div class="text-center">
                        <h2 style="margin: 0; font-size: 20px;" class="bold uppercase">SmartCafe</h2>
                        <span style="font-size: 10px; color: #444;" class="uppercase">Receipt Slip</span>
                    </div>
                    <div class="dashed-line"></div>
                    <div style="font-size: 11px;">
                        <div>Date: ${dateStr}</div>
                        <div>Cashier: ${"{{ session('name', 'Cashier') }}"}</div>
                        <div>Payment: ${activePaymentMethod}</div>
                    </div>
                    <div class="dashed-line"></div>
                    
                    <table>
                        <thead>
                            <tr style="border-bottom: 1px dashed #000;">
                                <th class="bold" style="text-align: left; width: 50%;">Item</th>
                                <th class="bold text-center" style="width: 15%;">Qty</th>
                                <th class="bold text-right" style="width: 35%;">Price</th>
                            </tr>
                        </thead>
                        <tbody>`;

            Object.values(cart).forEach(item => {
                const itemTotal = item.qty * item.price;
                subtotal += itemTotal;
                receiptContent += `
                    <tr>
                        <td style="vertical-align: top;">${item.name}</td>
                        <td class="text-center" style="vertical-align: top;">${item.qty}</td>
                        <td class="text-right" style="vertical-align: top;">$${itemTotal.toFixed(2)}</td>
                    </tr>`;
            });

            let receivedCashUSD;
            let changeUSD;

            if (activePaymentMethod === 'KHQR') {
                receivedCashUSD = subtotal;
                changeUSD = 0;
            } else {
                const inputVal = parseFloat(document.getElementById('received-cash').value || 0);
                if (receivedIn === 'USD') {
                    receivedCashUSD = inputVal;
                } else {
                    receivedCashUSD = inputVal / USD_TO_KHR;
                }
                changeUSD = receivedCashUSD - subtotal;
            }

            receiptContent += `
                        </tbody>
                    </table>
                    <div class="dashed-line"></div>
                    
                    <div style="display: flex; justify-content: space-between;" class="bold total-row">
                        <span>TOTAL USD</span>
                        <span>$${subtotal.toFixed(2)}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 11px;">
                        <span>TOTAL KHR</span>
                        <span>៛${Math.round(subtotal * USD_TO_KHR).toLocaleString()}</span>
                    </div>
                    <div class="dashed-line"></div>
                    
                    <div style="display: flex; justify-content: space-between; font-size: 11px;">
                        <span>Received ${activePaymentMethod === 'KHQR' ? 'KHQR' : 'Cash'}</span>
                        <span>$${receivedCashUSD.toFixed(2)}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;" class="bold">
                        <span>Change Due USD</span>
                        <span>$${(changeUSD > 0 ? changeUSD : 0).toFixed(2)}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 11px;">
                        <span>Change KHR</span>
                        <span>៛${(changeUSD > 0 ? Math.round(changeUSD * USD_TO_KHR) : 0).toLocaleString()}</span>
                    </div>
                    
                    <div class="dashed-line"></div>
                    <div class="text-center" style="margin-top: 15px; font-size: 11px; font-weight: bold;" class="uppercase">
                        Thank you for your purchase!
                    </div>
                </body>
                </html>`;

            const iframe = document.getElementById('receipt-print-iframe');
            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
            iframeDoc.open();
            iframeDoc.write(receiptContent);
            iframeDoc.close();
            
            setTimeout(() => {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            }, 100);
        }

        // Tab switcher for mobile layouts
        let activeTab = 'catalog';
        function switchTab(tab) {
            activeTab = tab;
            const catalogPanel = document.getElementById('catalog-panel');
            const cartPanel = document.getElementById('cart-panel');
            const tabCatalog = document.getElementById('tab-catalog');
            const tabCart = document.getElementById('tab-cart');

            if (tab === 'catalog') {
                catalogPanel.classList.remove('hidden');
                cartPanel.classList.add('hidden');

                tabCatalog.className = "flex flex-col items-center gap-1 text-indigo-600 dark:text-indigo-400 font-bold transition-all cursor-pointer bg-transparent border-none outline-none";
                tabCart.className = "flex flex-col items-center gap-1 text-slate-400 dark:text-slate-500 font-medium transition-all relative cursor-pointer bg-transparent border-none outline-none";
            } else {
                catalogPanel.classList.add('hidden');
                cartPanel.classList.remove('hidden');

                tabCatalog.className = "flex flex-col items-center gap-1 text-slate-400 dark:text-slate-500 font-medium transition-all cursor-pointer bg-transparent border-none outline-none";
                tabCart.className = "flex flex-col items-center gap-1 text-indigo-600 dark:text-indigo-400 font-bold transition-all relative cursor-pointer bg-transparent border-none outline-none";
            }
        }

        // Initialize mobile tab layouts
        function initMobileTabs() {
            if (window.innerWidth < 768) {
                switchTab(activeTab);
            } else {
                const catalogPanel = document.getElementById('catalog-panel');
                const cartPanel = document.getElementById('cart-panel');
                catalogPanel.classList.remove('hidden');
                cartPanel.classList.remove('hidden');
            }
        }

        window.addEventListener('resize', initMobileTabs);

        // Initial load
        document.addEventListener("DOMContentLoaded", () => {
            renderProducts();
            updateCartDisplay();
            initMobileTabs();
            updateSalesTargetProgress();
        });
    </script>

    <!-- Modal: KHQR Payment Gate -->
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-md hidden transition-opacity duration-300" id="khqrModal">
        <div class="bg-white dark:bg-slate-950 w-full max-w-sm rounded-[2.5rem] overflow-hidden shadow-2xl border border-slate-200/50 dark:border-slate-800/50 transform scale-95 transition-transform duration-300 flex flex-col relative">
            
            <!-- Red Header with official KHQR SVG Logo and Bakong Tail clip-path -->
            <div class="bg-[#e21a1a] h-24 select-none relative" style="clip-path: polygon(0 0, 100% 0, 100% 100%, 88% 70%, 0 70%);">
                <!-- Header Content Container (centered within main header area of 70% height) -->
                <div class="h-[67px] flex items-center justify-center w-full">
                    <!-- Official KHQR Vector Logo -->
                    <svg class="h-6.5 text-white fill-current" viewBox="0 0 166 40" xmlns="http://www.w3.org/2000/svg">
                        <path d="M107.798 14.5845V26.9495H95.4329C94.1647 26.9495 93.2136 25.9191 93.2136 24.7302V14.5845C93.2136 13.3163 94.244 12.3651 95.4329 12.3651H105.499C106.847 12.3651 107.798 13.3163 107.798 14.5845Z" fill="white"/>
                        <path d="M165.739 19.578H159.557C159.557 12.2065 153.533 6.18253 146.161 6.18253C140.296 6.18253 135.144 9.98717 133.4 15.5356C133.004 16.8831 132.766 18.2305 132.766 19.578V39.2353H132.607C129.278 39.2353 126.583 36.5404 126.583 33.2113V19.578H126.663C126.663 14.1881 128.882 9.03601 132.845 5.31064C136.491 1.90232 141.247 0 146.241 0C157.02 0 165.739 8.79822 165.739 19.578Z" fill="white"/>
                        <path d="M165.819 39.2353L157.1 39.3146L154.96 37.1745L150.204 32.4187L143.625 25.8398H152.344L165.819 39.2353Z" fill="white"/>
                        <path d="M109.859 33.132H91.232C88.9333 33.132 87.1103 31.309 87.1103 29.0103V10.3042C87.1103 8.00559 88.9333 6.18253 91.232 6.18253H109.859C112.157 6.18253 113.981 8.00559 113.981 10.3042V28.9311L120.163 35.1136V6.02401C120.163 2.69495 117.468 0 114.139 0H86.9517C83.6227 0 80.9277 2.69495 80.9277 6.02401V33.2113C80.9277 36.5404 83.6227 39.2353 86.9517 39.2353H116.041L109.859 33.132Z" fill="white"/>
                        <path d="M34.1625 39.3146H25.4435L7.21295 21.0048V39.3146H0V0H7.21295V17.5172L24.6509 0H33.2906L14.2674 18.8647L34.1625 39.3146Z" fill="white"/>
                        <path d="M66.7397 0H73.7941V39.3146H66.7397V22.1937H46.6068V39.2353H39.5524V0H46.6068V16.4075H66.7397V0Z" fill="white"/>
                    </svg>
                </div>
                <!-- Close Button -->
                <button onclick="cancelKHQRPayment()" class="absolute right-4 top-[18px] w-8 h-8 rounded-full flex items-center justify-center text-white/80 hover:text-white hover:bg-white/10 transition-colors cursor-pointer border-none bg-transparent outline-none">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <!-- Body -->
            <div class="px-6 py-5 flex flex-col items-center justify-center text-center bg-white dark:bg-slate-950">
                <!-- Merchant info card -->
                <div class="w-full flex flex-col items-center justify-center space-y-1 mb-2">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">Merchant Name</span>
                    <h3 class="text-lg font-black text-slate-800 dark:text-slate-200 tracking-tight leading-tight select-none">SmartCafe</h3>
                    <span class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 flex items-center gap-1 select-none">
                        <i class="fas fa-map-marker-alt text-[9px] text-[#e21a1a]"></i> Phnom Penh
                    </span>
                </div>

                <!-- Amount Section -->
                <div class="w-full flex flex-col items-center justify-center space-y-0.5 mt-2.5">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">Payment Amount</span>
                    <h2 class="text-3xl font-black text-slate-950 dark:text-white tracking-tight select-all" id="khqr-card-amount">$0.00</h2>
                </div>
                
                <!-- Dashed Divider -->
                <div class="w-full border-t-2 border-dashed border-slate-200 dark:border-slate-800/80 my-4 select-none"></div>

                <!-- QR Container Frame -->
                <div class="relative p-4 bg-white dark:bg-slate-900 rounded-[1.75rem] flex items-center justify-center overflow-hidden border border-slate-100 dark:border-slate-800/50 shadow-[0_8px_30px_rgb(0,0,0,0.03)] w-58 h-58">
                    <!-- Glowing laser line -->
                    <div id="khqr-laser" class="absolute left-0 right-0 top-0 h-0.5 bg-gradient-to-r from-transparent via-[#e21a1a] to-transparent shadow-[0_0_8px_rgba(226,26,26,0.8)] z-10 hidden animate-[scan_2s_linear_infinite]"></div>

                    <!-- Spinner -->
                    <div id="khqr-spinner" class="flex flex-col items-center gap-2">
                        <i class="fas fa-circle-notch text-2xl text-[#e21a1a] animate-spin"></i>
                        <span class="text-xs text-slate-400 font-medium">Generating QR Code...</span>
                    </div>

                    <!-- QR Image code wrapper with center icon -->
                    <div id="khqr-image-container" class="relative w-50 h-50 hidden animate-fade-in flex items-center justify-center">
                        <img id="khqr-image" class="w-full h-full object-contain select-none" alt="KHQR Payment Code">
                        <!-- Center Logo Badge -->
                        <div class="absolute inset-0 m-auto w-8.5 h-8.5 rounded-full border-2 border-white flex items-center justify-center text-white font-extrabold text-sm shadow-md select-none" id="khqr-center-logo">
                            $
                        </div>
                    </div>

                    <!-- Success Checkmark -->
                    <div id="khqr-success" class="hidden flex-col items-center gap-3 animate-bounce">
                        <div class="w-16 h-16 rounded-full bg-emerald-100 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-3xl shadow-lg shadow-emerald-500/10">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                </div>

                <!-- Status Indicator -->
                <div class="mt-4.5 flex items-center gap-2 text-[10px] py-1.5 px-3.5 bg-slate-50 dark:bg-slate-900 border border-slate-200/30 dark:border-slate-800/20 rounded-xl font-semibold text-slate-500 dark:text-slate-400 select-none">
                    <span class="relative flex h-1.5 w-1.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-indigo-500"></span>
                    </span>
                    <span id="khqr-status-text">Ready for payment scan</span>
                </div>
            </div>

            <!-- Footer Controls -->
            <div class="p-4 bg-slate-50 dark:bg-slate-900 border-t border-slate-200/50 dark:border-slate-800/50 flex gap-2.5 no-print select-none">
                <button onclick="toggleQRCurrency()" id="khqr-currency-btn" class="px-4 py-2.5 bg-white dark:bg-slate-950 border border-slate-200/50 dark:border-slate-850 text-xs font-bold rounded-xl cursor-pointer shadow-sm hover:bg-slate-50 dark:hover:bg-slate-900 text-slate-700 dark:text-slate-300">
                    Pay In: USD
                </button>
                <button onclick="cancelKHQRPayment()" class="flex-1 py-2.5 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/20 dark:hover:bg-rose-950/40 text-rose-600 dark:text-rose-400 font-semibold rounded-xl transition-all cursor-pointer text-center text-xs">
                    Cancel
                </button>
            </div>
        </div>
    </div>
    <!-- Hidden Print Iframe -->
    <iframe id="receipt-print-iframe" style="display:none; width:0; height:0; border:0;"></iframe>
</body>
</html>
