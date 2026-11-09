@extends('layouts.app')

@section('title', 'SmartCafe - Sales Calendar')
@section('page_title', 'Sales Calendar')

@section('content')
<!-- Calendar Container -->
<div class="flex flex-col xl:flex-row gap-6">

    <!-- Left: Calendar Grid Card -->
    <div class="flex-1 bg-white dark:bg-slate-900 border border-slate-200/50 dark:border-slate-800/50 rounded-[2rem] p-6 lg:p-8 shadow-sm">
        
        <!-- Calendar Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8">
            <div>
                <h3 class="text-lg font-bold text-slate-950 dark:text-white" id="calendar-month-year">June 2026</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">Visualizing revenue performance across days</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                @if(strtolower(session('role')) === 'manager' || strtolower(session('role')) === 'assistant')
                <button onclick="openTargetModal()" class="px-4 py-2.5 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/40 dark:hover:bg-indigo-950/80 text-xs font-bold text-indigo-600 dark:text-indigo-400 rounded-2xl shadow-sm hover:scale-105 active:scale-95 transition-all cursor-pointer flex items-center gap-2 border border-indigo-200/20 dark:border-indigo-800/20">
                    <i class="fas fa-bullseye text-sm"></i>
                    <span>Set Daily Target</span>
                </button>
                @endif

                <!-- Month Toggler Controls -->
                <div class="flex items-center gap-2 bg-slate-100/70 dark:bg-slate-950/40 p-1.5 rounded-2xl border border-slate-200/50 dark:border-slate-800/30 w-fit shrink-0">
                    <button onclick="changeMonth(-1)" class="w-9 h-9 rounded-xl flex items-center justify-center text-slate-600 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-900 transition-all shadow-sm hover:scale-105 active:scale-95 cursor-pointer">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </button>
                    <button onclick="goToCurrentMonth()" class="px-4 py-1.5 bg-white dark:bg-slate-900 text-xs font-bold text-indigo-600 dark:text-indigo-400 rounded-xl shadow-sm hover:scale-105 active:scale-95 transition-all cursor-pointer">
                        Today
                    </button>
                    <button onclick="changeMonth(1)" class="w-9 h-9 rounded-xl flex items-center justify-center text-slate-600 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-900 transition-all shadow-sm hover:scale-105 active:scale-95 cursor-pointer">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Weekly Headers -->
        <div class="grid grid-cols-7 gap-2 text-center text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-3 select-none">
            <div>Sun</div>
            <div>Mon</div>
            <div>Tue</div>
            <div>Wed</div>
            <div>Thu</div>
            <div>Fri</div>
            <div>Sat</div>
        </div>

        <!-- Calendar Days Grid -->
        <div id="calendar-grid" class="grid grid-cols-7 gap-2.5">
            <!-- Rendered dynamically via js -->
        </div>
    </div>

    <!-- Right: Day Details Sidebar Card -->
    <div class="w-full xl:w-96 shrink-0 flex flex-col gap-6">
        
        <!-- Active Selected Day Overview -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/50 dark:border-slate-800/50 rounded-[2rem] p-6 shadow-sm flex flex-col overflow-hidden relative">
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl"></div>
            
            <div class="relative z-10 space-y-5">
                <div>
                    <span class="text-slate-400 dark:text-slate-500 text-xs font-semibold uppercase tracking-wider">Selected Date</span>
                    <h4 class="text-xl font-bold text-slate-950 dark:text-white mt-1" id="details-date">June 13, 2026</h4>
                </div>

                <div class="border-b border-slate-100 dark:border-slate-850"></div>

                <!-- Stats summary columns -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-slate-50 dark:bg-slate-950/40 border border-slate-200/30 dark:border-slate-800/20 rounded-2xl p-4 space-y-1">
                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-semibold uppercase tracking-wider">Total Sales</span>
                        <h3 class="text-lg font-black text-indigo-600 dark:text-indigo-400" id="details-sales">$0.00</h3>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-950/40 border border-slate-200/30 dark:border-slate-800/20 rounded-2xl p-4 space-y-1">
                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-semibold uppercase tracking-wider">Total Orders</span>
                        <h3 class="text-lg font-black text-slate-800 dark:text-slate-200" id="details-orders">0</h3>
                    </div>
                </div>

                <div class="border-b border-slate-100 dark:border-slate-850"></div>

                <!-- Daily Target Progress -->
                <div class="space-y-2 select-none">
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span class="text-slate-500 dark:text-slate-400">Day Target Achievement</span>
                        <span class="text-indigo-600 dark:text-indigo-400" id="sidebar-target-pct">0%</span>
                    </div>
                    <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-950 rounded-full overflow-hidden">
                        <div id="sidebar-target-bar" class="h-full bg-gradient-to-r from-indigo-500 to-emerald-500 transition-all duration-350" style="width: 0%"></div>
                    </div>
                    <div class="flex items-center justify-between text-[9px] font-semibold text-slate-400 dark:text-slate-500">
                        <span>Progress: <span id="sidebar-target-progress">$0.00</span></span>
                        <span>Goal: $<span id="sidebar-target-goal-val">{{ number_format($dailyTarget ?? 500.00, 2) }}</span></span>
                    </div>
                </div>

                <div class="border-b border-slate-100 dark:border-slate-850"></div>

                <!-- Action Button to Invoices -->
                <div class="pt-1">
                    <a href="{{ route('invoice.show') }}" class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/30 transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer text-sm">
                        <i class="fas fa-receipt"></i>
                        <span>View Invoices List</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Sales performance stats panel -->
        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 text-white rounded-[2rem] p-6 shadow-md shadow-indigo-500/10">
            <div class="space-y-4">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white"><i class="fas fa-chart-line"></i></div>
                <div>
                    <h4 class="font-bold text-base">Monthly Insights</h4>
                    <p class="text-white/70 text-xs mt-1 leading-relaxed">Quickly view high performance dates. Green dates indicate days with transactions. Clicking on any day displays details on this side panel.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Set Daily Target Modal -->
<div id="target-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden p-4">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-slate-950/40 backdrop-blur-sm" onclick="closeTargetModal()"></div>
    
    <!-- Modal Body -->
    <div class="relative w-full max-w-md bg-white dark:bg-slate-900 border border-slate-200/50 dark:border-slate-800/50 rounded-[2.5rem] p-8 shadow-2xl animate-fade-in text-left">
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl"></div>
        
        <div class="relative z-10 space-y-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-600/10 text-indigo-600 dark:text-indigo-400 rounded-xl flex items-center justify-center">
                    <i class="fas fa-bullseye text-lg animate-pulse"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-950 dark:text-white">Set Daily Sales Target</h3>
                    <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">Configure store performance baseline</p>
                </div>
            </div>

            <form action="{{ route('settings.update') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="key" value="daily_sales_target">
                
                <div class="space-y-2">
                    <label for="target_value" class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Target Amount (USD)</label>
                    <div class="flex items-center bg-slate-50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-2xl focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-500/20 transition-all duration-200 overflow-hidden">
                        <span class="pl-4 text-slate-400 dark:text-slate-500 font-bold">$</span>
                        <input type="number" step="0.01" id="target_value" name="value" value="{{ $dailyTarget ?? 500.00 }}" required min="0"
                            class="w-full py-3.5 px-3 bg-transparent border-none outline-none text-slate-900 dark:text-white font-bold placeholder:text-slate-400 rounded-2xl" />
                    </div>
                </div>

                <!-- Quick Presets -->
                <div class="space-y-2">
                    <span class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider ml-1">Quick Presets</span>
                    <div class="grid grid-cols-4 gap-2">
                        <button type="button" onclick="setPresetValue(250)" class="py-2.5 bg-slate-50 hover:bg-slate-100 dark:bg-slate-950/20 dark:hover:bg-slate-950/40 border border-slate-200/50 dark:border-slate-800/30 text-xs font-bold text-slate-700 dark:text-slate-300 rounded-xl transition-all cursor-pointer">$250</button>
                        <button type="button" onclick="setPresetValue(500)" class="py-2.5 bg-slate-50 hover:bg-slate-100 dark:bg-slate-950/20 dark:hover:bg-slate-950/40 border border-slate-200/50 dark:border-slate-800/30 text-xs font-bold text-slate-700 dark:text-slate-300 rounded-xl transition-all cursor-pointer">$500</button>
                        <button type="button" onclick="setPresetValue(750)" class="py-2.5 bg-slate-50 hover:bg-slate-100 dark:bg-slate-950/20 dark:hover:bg-slate-950/40 border border-slate-200/50 dark:border-slate-800/30 text-xs font-bold text-slate-700 dark:text-slate-300 rounded-xl transition-all cursor-pointer">$750</button>
                        <button type="button" onclick="setPresetValue(1000)" class="py-2.5 bg-slate-50 hover:bg-slate-100 dark:bg-slate-950/20 dark:hover:bg-slate-950/40 border border-slate-200/50 dark:border-slate-800/30 text-xs font-bold text-slate-700 dark:text-slate-300 rounded-xl transition-all cursor-pointer">$1000</button>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 pt-2">
                    <button type="button" onclick="closeTargetModal()" class="flex-1 py-3 bg-slate-50 hover:bg-slate-100 dark:bg-slate-950/25 dark:hover:bg-slate-950/50 border border-slate-200/40 dark:border-slate-800/30 text-xs font-bold text-slate-500 dark:text-slate-400 rounded-xl transition-all cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-lg shadow-indigo-500/20 transition-all cursor-pointer">
                        Save Target
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Sales Data passed from backend controller
    const salesData = {!! json_encode($sales) !!};
    const dailyTarget = {{ $dailyTarget ?? 500.00 }};
    
    // Calendar state
    let currentYear = new Date().getFullYear();
    let currentMonth = new Date().getMonth(); // 0-11
    
    const monthNames = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];
    
    // Render the calendar days grid
    function renderCalendar(year, month) {
        const grid = document.getElementById('calendar-grid');
        const monthYearLabel = document.getElementById('calendar-month-year');
        
        grid.innerHTML = '';
        monthYearLabel.innerText = `${monthNames[month]} ${year}`;
        
        // Find starting day and total days of the month
        const firstDayIndex = new Date(year, month, 1).getDay(); // 0 (Sun) - 6 (Sat)
        const totalDays = new Date(year, month + 1, 0).getDate();
        
        // Find total days of the previous month
        const prevTotalDays = new Date(year, month, 0).getDate();
        
        // Render Empty days from previous month
        for (let i = firstDayIndex; i > 0; i--) {
            const dayCell = document.createElement('div');
            dayCell.className = "min-h-[90px] p-2 bg-slate-50/50 dark:bg-slate-900/10 border border-slate-100/50 dark:border-slate-800/10 rounded-2xl text-slate-300 dark:text-slate-700 font-semibold text-xs opacity-50 flex flex-col justify-between select-none cursor-not-allowed";
            dayCell.innerHTML = `<span>${prevTotalDays - i + 1}</span>`;
            grid.appendChild(dayCell);
        }
        
        // Today's date components
        const today = new Date();
        const isCurrentMonthYear = today.getFullYear() === year && today.getMonth() === month;
        
        // Render current month days
        for (let day = 1; day <= totalDays; day++) {
            const dayCell = document.createElement('div');
            
            // Format date key: YYYY-MM-DD
            const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const isToday = isCurrentMonthYear && today.getDate() === day;
            
            // Styles for the day cells
            let cellClass = "min-h-[90px] p-3.5 bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/50 rounded-2xl font-bold text-xs flex flex-col justify-between transition-all duration-200 hover:shadow-md hover:scale-[1.03] cursor-pointer relative";
            if (isToday) {
                cellClass += " ring-2 ring-indigo-500/80 bg-indigo-50/10 dark:bg-indigo-950/20";
            }
            
            dayCell.className = cellClass;
            
            // Generate HTML contents for days
            let dayHtml = `
                <div class="flex items-center justify-between">
                    <span class="${isToday ? 'text-indigo-600 dark:text-indigo-400 font-black' : 'text-slate-700 dark:text-slate-300'}">${day}</span>
                    ${isToday ? '<span class="w-1.5 h-1.5 rounded-full bg-indigo-600 dark:bg-indigo-400"></span>' : ''}
                </div>
            `;
            
            // Check if there are sales on this date
            const daySale = salesData[dateStr];
            if (daySale) {
                const isTargetMet = parseFloat(daySale.total) >= dailyTarget;
                dayHtml += `
                    <div class="mt-2 text-left space-y-1">
                        <span class="flex items-center gap-1 text-[10px] sm:text-[11px] font-black ${isTargetMet ? 'text-emerald-600 dark:text-emerald-400' : 'text-indigo-600 dark:text-indigo-400'}">
                            $${parseFloat(daySale.total).toFixed(2)}
                            ${isTargetMet ? '<i class="fas fa-circle-check text-[9px] text-emerald-500 animate-pulse"></i>' : ''}
                        </span>
                        <span class="block text-[9px] font-semibold text-slate-400 dark:text-slate-500">${daySale.count} orders</span>
                    </div>
                `;
                dayCell.classList.add('hover:border-emerald-500/40', 'dark:hover:border-emerald-500/30');
            } else {
                dayHtml += `
                    <div class="mt-2 text-left opacity-0">
                        <span class="block text-[10px]">$0.00</span>
                    </div>
                `;
            }
            
            dayCell.innerHTML = dayHtml;
            dayCell.onclick = (event) => selectDay(dateStr, day, month, year, daySale, event);
            grid.appendChild(dayCell);
        }
        
        // Calculate remaining spaces to fill grid (next month days)
        const gridCellsCount = firstDayIndex + totalDays;
        const remainingCells = 42 - gridCellsCount; // standard 6-week layout grid
        
        for (let i = 1; i <= remainingCells; i++) {
            const dayCell = document.createElement('div');
            dayCell.className = "min-h-[90px] p-2 bg-slate-50/50 dark:bg-slate-900/10 border border-slate-100/50 dark:border-slate-800/10 rounded-2xl text-slate-300 dark:text-slate-700 font-semibold text-xs opacity-50 flex flex-col justify-between select-none cursor-not-allowed";
            dayCell.innerHTML = `<span>${i}</span>`;
            grid.appendChild(dayCell);
        }
    }
    
    // Select and display details for a day
    function selectDay(dateStr, day, month, year, saleInfo, event) {
        // Formatted date string for header
        const dateObj = new Date(year, month, day);
        const options = { weekday: 'short', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('details-date').innerText = dateObj.toLocaleDateString('en-US', options);
        
        // Update stats
        const salesLabel = document.getElementById('details-sales');
        const ordersLabel = document.getElementById('details-orders');
        
        const targetProgress = document.getElementById('sidebar-target-progress');
        const targetPct = document.getElementById('sidebar-target-pct');
        const targetBar = document.getElementById('sidebar-target-bar');
        
        let totalSales = 0;
        
        if (saleInfo) {
            totalSales = parseFloat(saleInfo.total);
            salesLabel.innerText = `$${totalSales.toFixed(2)}`;
            ordersLabel.innerText = saleInfo.count;
            salesLabel.className = "text-lg font-black text-emerald-600 dark:text-emerald-400";
        } else {
            salesLabel.innerText = "$0.00";
            ordersLabel.innerText = "0";
            salesLabel.className = "text-lg font-black text-slate-400 dark:text-slate-500";
        }
        
        // Update target progress bar widgets
        if (targetProgress && targetPct && targetBar) {
            targetProgress.innerText = `$${totalSales.toFixed(2)}`;
            const pct = Math.min(Math.round((totalSales / dailyTarget) * 100), 100);
            targetPct.innerText = `${pct}%`;
            targetBar.style.width = `${pct}%`;
            
            if (totalSales >= dailyTarget) {
                targetBar.classList.remove('from-indigo-500', 'to-emerald-500');
                targetBar.classList.add('bg-emerald-500', 'animate-pulse');
            } else {
                targetBar.classList.add('from-indigo-500', 'to-emerald-500');
                targetBar.classList.remove('bg-emerald-500', 'animate-pulse');
            }
        }
        
        // Remove selection highlights, set border highlights
        document.querySelectorAll('#calendar-grid > div').forEach(cell => {
            cell.classList.remove('border-indigo-500', 'dark:border-indigo-400', 'ring-2', 'ring-indigo-500/20');
        });
        
        // Add highlight to clicked element if click event exists
        if (event && event.currentTarget) {
            event.currentTarget.classList.add('border-indigo-500', 'dark:border-indigo-400', 'ring-2', 'ring-indigo-500/20');
        }
    }
    
    // Month navigation switcher
    function changeMonth(direction) {
        currentMonth += direction;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear -= 1;
        } else if (currentMonth > 11) {
            currentMonth = 0;
            currentYear += 1;
        }
        renderCalendar(currentYear, currentMonth);
        resetSidebar();
    }
    
    function goToCurrentMonth() {
        currentYear = new Date().getFullYear();
        currentMonth = new Date().getMonth();
        renderCalendar(currentYear, currentMonth);
        resetSidebar();
    }
    
    // Clear sidebar highlights
    function resetSidebar() {
        const today = new Date();
        const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
        
        const saleInfo = salesData[dateStr];
        selectDay(dateStr, today.getDate(), currentMonth, currentYear, saleInfo);
    }
    
    // Initialize Calendar layout
    document.addEventListener("DOMContentLoaded", () => {
        goToCurrentMonth();
    });

    // Modal Control Functions
    function openTargetModal() {
        document.getElementById('target-modal').classList.remove('hidden');
    }
    
    function closeTargetModal() {
        document.getElementById('target-modal').classList.add('hidden');
    }
    
    function setPresetValue(value) {
        document.getElementById('target_value').value = value.toFixed(2);
    }
</script>
@endsection
