@extends('layouts.app')

@section('title', 'SmartCafe - Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<!-- Dashboard Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Total Sales Widget -->
    <div class="relative overflow-hidden group bg-gradient-to-br from-emerald-500 to-teal-600 rounded-3xl p-6 shadow-md shadow-emerald-500/10 hover:shadow-xl hover:shadow-emerald-500/20 transition-all duration-300 hover:-translate-y-1">
        <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <span class="text-white/80 text-xs font-semibold uppercase tracking-wider">Total Revenue</span>
                <h3 class="text-3xl font-bold text-white mt-1">${{ number_format($total, 2) }}</h3>
                <p class="text-white/60 text-xs mt-2 font-medium">USD Cumulative Sales</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-white">
                <i class="fas fa-wallet text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Orders Widget -->
    <div class="relative overflow-hidden group bg-gradient-to-br from-indigo-500 to-purple-600 rounded-3xl p-6 shadow-md shadow-indigo-500/10 hover:shadow-xl hover:shadow-indigo-500/20 transition-all duration-300 hover:-translate-y-1">
        <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <span class="text-white/80 text-xs font-semibold uppercase tracking-wider">Total Orders</span>
                <h3 class="text-3xl font-bold text-white mt-1">{{ $co }}</h3>
                <p class="text-white/60 text-xs mt-2 font-medium">Processed Receipts</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-white">
                <i class="fas fa-shopping-bag text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Products Widget -->
    <div class="relative overflow-hidden group bg-gradient-to-br from-violet-500 to-fuchsia-600 rounded-3xl p-6 shadow-md shadow-violet-500/10 hover:shadow-xl hover:shadow-violet-500/20 transition-all duration-300 hover:-translate-y-1">
        <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <span class="text-white/80 text-xs font-semibold uppercase tracking-wider">Total Products</span>
                <h3 class="text-3xl font-bold text-white mt-1">{{ $pro }}</h3>
                <p class="text-white/60 text-xs mt-2 font-medium">Active Menu Items</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-white">
                <i class="fas fa-coffee text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Cashiers Widget -->
    <div class="relative overflow-hidden group bg-gradient-to-br from-amber-500 to-orange-600 rounded-3xl p-6 shadow-md shadow-amber-500/10 hover:shadow-xl hover:shadow-amber-500/20 transition-all duration-300 hover:-translate-y-1">
        <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <span class="text-white/80 text-xs font-semibold uppercase tracking-wider">Active Staff</span>
                <h3 class="text-3xl font-bold text-white mt-1">{{ $usercount }}</h3>
                <p class="text-white/60 text-xs mt-2 font-medium">Registered Cashiers</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-white">
                <i class="fas fa-users text-xl"></i>
            </div>
        </div>
    </div>

</div>

<!-- Chart Section -->
<div class="bg-white dark:bg-slate-900 border border-slate-200/50 dark:border-slate-800/50 rounded-[2rem] p-6 lg:p-8 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h3 class="text-lg font-bold text-slate-950 dark:text-white">Sales Performance Overview</h3>
            <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">Tracking store revenue patterns over time</p>
        </div>
        <div class="flex items-center gap-2 text-xs py-1.5 px-3 bg-indigo-50 dark:bg-indigo-950/20 text-indigo-600 dark:text-indigo-400 font-semibold rounded-xl">
            <i class="fas fa-calendar-day"></i>
            <span>Latest 10 Sales Days</span>
        </div>
    </div>
    
    <div class="relative h-96 w-full">
        <canvas id="salesChart"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('salesChart').getContext('2d');
        
        // Define gradients
        const gradientFill = ctx.createLinearGradient(0, 0, 0, 400);
        gradientFill.addColorStop(0, 'rgba(99, 102, 241, 0.4)');
        gradientFill.addColorStop(1, 'rgba(99, 102, 241, 0.01)');
        
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Daily Revenue ($)',
                    data: {!! json_encode($data) !!},
                    backgroundColor: gradientFill,
                    borderColor: 'rgb(99, 102, 241)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: 'rgb(99, 102, 241)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        padding: 12,
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleFont: {
                            family: 'Outfit',
                            size: 13,
                            weight: 'bold'
                        },
                        bodyFont: {
                            family: 'Outfit',
                            size: 13
                        },
                        cornerRadius: 12,
                        displayColors: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: 'Outfit',
                                size: 11
                            },
                            color: 'rgb(148, 163, 184)'
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(148, 163, 184, 0.1)',
                            drawTicks: false
                        },
                        ticks: {
                            font: {
                                family: 'Outfit',
                                size: 11
                            },
                            color: 'rgb(148, 163, 184)',
                            callback: function(value) {
                                return '$' + value;
                            }
                        },
                        border: {
                            dash: [5, 5]
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
