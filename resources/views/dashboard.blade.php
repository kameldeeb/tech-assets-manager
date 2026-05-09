<!DOCTYPE html>
<html lang="en" x-data="darkModeApp()" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enterprise Asset Manager - Hope Center</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Tom Select CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    <!-- Heroicons -->
    <script src="https://unpkg.com/heroicons@2.0.18/24/outline/index.js" type="module"></script>

    <style>
        [x-cloak] { display: none !important; }
        
        .loading-overlay {
            position: fixed;
            inset: 0;
            background: #0f172a; 
            z-index: 9999;
            transition: opacity 0.3s ease-out;
        }
        
        .loaded .loading-overlay {
            opacity: 0;
            pointer-events: none;
        }

        /* Glassmorphism Effect */
        .glass-card {
            backdrop-filter: blur(16px) saturate(180%);
            background-color: rgba(255, 255, 255, 0.75);
            border: 1px solid rgba(255, 255, 255, 0.125);
        }
        .dark .glass-card {
            background-color: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(71, 85, 105, 0.3);
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-900 font-sans antialiased transition-colors duration-300">
    <div class="flex h-screen">
        @include('dashboard.partials._sidebar')

        <!-- Main Content -->
        <main class="flex-1 overflow-auto bg-slate-50 dark:bg-slate-900 transition-colors duration-300">
            <div class="p-8 space-y-8">
                <!-- Header -->
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Dashboard Overview</h1>
                        <p class="mt-2 text-slate-600 dark:text-slate-400">Monitor asset allocation and operational metrics</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-slate-500 dark:text-slate-400">Current Date</p>
                        <p class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ now()->format('F j, Y') }}</p>
                    </div>
                </div>

                <!-- Overview Tab -->
                <div x-show="activeTab === 'overview'" class="space-y-8">
                    @include('dashboard.partials._stats')
                    @include('dashboard.partials._loan_form')
                    @include('dashboard.partials._active_loans')
                </div>

                <!-- Inventory Tab -->
                <div x-show="activeTab === 'inventory'" class="space-y-8">
                    @include('dashboard.partials._inventory')
                </div>

                <!-- Reports Tab -->
                <div x-show="activeTab === 'reports'" class="space-y-8">
                    @include('dashboard.partials._reports')
                </div>
            </div>
        </main>
    </div>

    <script>
        function darkModeApp() {
            return {
                darkMode: localStorage.getItem('darkMode') === 'true',
                activeTab: 'overview',

                init() {
                    this.applyDarkMode();
                    this.initTomSelect();
                    this.renderAssetsByTypeChart();
                    this.renderAssetStatusChart();
                    // Show the content smoothly after everything is initialized
                    setTimeout(() => {
                        const mainContent = document.getElementById('main-content-area');
                        if (mainContent) {
                            mainContent.classList.remove('undom-ready');
                            mainContent.classList.add('dom-ready');
                        }
                    }, 150);
                },

                initTomSelect() {
                    // Initialize Tom Select for employee dropdown
                    const employeeSelect = document.getElementById('employee_id');
                    if (employeeSelect && !employeeSelect.tomselect) {
                        new TomSelect(employeeSelect, {
                            placeholder: 'Search for an employee...',
                            allowEmptyOption: true,
                            maxOptions: null,
                            searchField: ['text'],
                            valueField: 'value',
                            labelField: 'text',
                            options: Array.from(employeeSelect.options).map(option => ({
                                value: option.value,
                                text: option.text
                            }))
                        });
                    }

                    // Initialize Tom Select for asset dropdown
                    const assetSelect = document.getElementById('asset_id');
                    if (assetSelect && !assetSelect.tomselect) {
                        new TomSelect(assetSelect, {
                            placeholder: 'Search for an asset...',
                            allowEmptyOption: true,
                            maxOptions: null,
                            searchField: ['text'],
                            valueField: 'value',
                            labelField: 'text',
                            options: Array.from(assetSelect.options).map(option => ({
                                value: option.value,
                                text: option.text
                            }))
                        });
                    }
                },

                // toggleDarkMode() {
                //     this.darkMode = !this.darkMode;
                //     localStorage.setItem('darkMode', this.darkMode);
                //     this.applyDarkMode();
                //     // Update charts when dark mode changes
                //     setTimeout(() => {
                //         this.updateCharts();
                //     }, 100);
                // },

                applyDarkMode() {
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                },

                updateCharts() {
                    // Re-render charts with new theme
                    const assetsByTypeCtx = document.getElementById('assetsByTypeChart');
                    const assetStatusCtx = document.getElementById('assetStatusChart');

                    if (assetsByTypeCtx && assetStatusCtx) {
                        // Force chart re-render by clearing and re-creating
                        this.renderAssetsByTypeChart();
                        this.renderAssetStatusChart();
                    }
                },

                renderAssetsByTypeChart() {
                    const ctx = document.getElementById('assetsByTypeChart').getContext('2d');
                    const assetTypeData = @json($assetTypeDistribution);
                    const assetTypeLabels = Object.keys(assetTypeData);
                    const assetTypeValues = Object.values(assetTypeData);

                    const isDark = document.documentElement.classList.contains('dark');

                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: assetTypeLabels,
                            datasets: [{
                                data: assetTypeValues,
                                backgroundColor: [
                                    '#3b82f6',
                                    '#10b981',
                                    '#f59e0b',
                                    '#ef4444',
                                    '#8b5cf6',
                                    '#06b6d4'
                                ],
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 20,
                                        usePointStyle: true,
                                        color: isDark ? '#e2e8f0' : '#374151'
                                    }
                                }
                            }
                        }
                    });
                },

                renderAssetStatusChart() {
                    const ctx = document.getElementById('assetStatusChart').getContext('2d');
                    const isDark = document.documentElement.classList.contains('dark');

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Available', 'Borrowed', 'Under Inspection'],
                            datasets: [{
                                label: 'Assets',
                                data: [{{ $availableAssets->count() }}, {{ $borrowedAssets }}, {{ $underInspectionAssets }}],
                                backgroundColor: [
                                    '#10b981',
                                    '#3b82f6',
                                    '#f59e0b'
                                ],
                                borderRadius: 4,
                                borderSkipped: false
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        color: isDark ? '#94a3b8' : '#6b7280'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        color: isDark ? '#94a3b8' : '#6b7280'
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                }
            }
        }
    </script>
</body>
</html>
