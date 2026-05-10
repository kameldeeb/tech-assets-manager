<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enterprise Asset Manager - Hope Center</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
<body x-data="app()" x-cloak class="bg-slate-900 font-sans antialiased transition-colors duration-300">
    <div class="flex h-screen">
        @include('dashboard.partials._sidebar')

        <main class="flex-1 overflow-auto bg-slate-900 transition-colors duration-300">
            <div class="p-8 space-y-8">
                <!-- Header -->
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-100">Dashboard Overview</h1>
                        <p class="mt-2 text-slate-400">Monitor asset allocation and operational metrics</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-slate-400">Current Date</p>
                        <p class="text-lg font-semibold text-slate-100">{{ now()->format('F j, Y') }}</p>
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

                <!-- Inspections Tab -->
                <div x-show="activeTab === 'inspections'" class="space-y-8">
                    @include('dashboard.partials._inspections')
                </div>
            </div>
        </main>
    </div>

    <!-- Flash Notifications -->
    <div x-data="{ 
            show: {{ session('success') || session('error') ? 'true' : 'false' }},
            message: '{{ session('success') ?: session('error') }}',
            type: '{{ session('success') ? 'success' : 'error' }}'
        }"
        x-init="if(show) setTimeout(() => show = false, 5000)"
        x-show="show"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="transform translate-x-full opacity-0"
        x-transition:enter-end="transform translate-x-0 opacity-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="transform translate-x-0 opacity-100"
        x-transition:leave-end="transform translate-x-full opacity-0"
        class="fixed top-5 right-5 z-[100] max-w-sm w-full">
        
        <div :class="type === 'success' ? 'bg-slate-800 border-green-500 shadow-green-500/20' : 'bg-slate-800 border-blue-500 shadow-blue-500/20'" 
            class="border-2 p-4 shadow-2xl rounded-2xl flex items-center glass-card transition-all duration-500 ring-1 ring-white/10 ring-inset">
                    
            <div class="flex-shrink-0">
                <template x-if="type === 'success'">
                    <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </template>
                <template x-if="type === 'error'">
                    <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </template>
            </div>

            <div class="ml-3 flex-1">
                <p class="text-sm font-bold text-slate-100" x-text="message"></p>
            </div>
            
            <button @click="show = false" class="ml-4 text-slate-500 hover:text-slate-300">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('app', () => ({
                activeTab: 'overview',

                showModal: false,
                selectedInspectionId: null,
                selectedAsset: '',

                openModal(id, serial) {
                    this.selectedInspectionId = id;
                    this.selectedAsset = serial;
                    this.showModal = true;
                },

                closeModal() {
                    this.showModal = false;
                    this.selectedInspectionId = null;
                    this.selectedAsset = '';
                },

                init() {
                    this.initTomSelect();
                },

                initTomSelect() {
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
                                text: option.text,
                            })),
                        });
                    }

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
                                text: option.text,
                            })),
                        });
                    }
                },
            }));

            Alpine.data('notifications', (initialData = {}) => ({
                show: initialData.show || false,
                type: initialData.type || 'success',
                message: initialData.message || '',

                close() {
                    this.show = false;
                },

                init() {
                    // Auto-hide after 5 seconds
                    if (this.show) {
                        setTimeout(() => {
                            this.show = false;
                        }, 5000);
                    }
                }
            }));
        });
    </script>
</body>
</html>
