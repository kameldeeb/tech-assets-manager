<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Total Assets -->
    <div class="bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-700 glass-card transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-400">Total Assets</p>
                <p class="text-3xl font-bold text-slate-100">{{ $availableAssets->count() + $borrowedAssets + $underInspectionAssets }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-900/50 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Active Loans -->
    <div class="bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-700 glass-card transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-400">Active Loans</p>
                <p class="text-3xl font-bold text-slate-100">{{ $activeLoans->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-green-900/50 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Under Inspection -->
    <div class="bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-700 glass-card transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-400">Under Inspection</p>
                <p class="text-3xl font-bold text-slate-100">{{ $underInspectionAssets }}</p>
            </div>
            <div class="w-12 h-12 bg-amber-900/50 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>