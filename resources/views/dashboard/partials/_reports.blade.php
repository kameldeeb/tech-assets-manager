<!-- Operational Reports -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Heavy Usage Report -->
    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 glass-card transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Heavy Usage</h3>
            <a href="/api/reports/heavy-usage" target="_blank" class="inline-flex items-center px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                </svg>
                JSON
            </a>
        </div>
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Employees with high loan frequency</p>

        @if($heavyUsers->isEmpty())
            <div class="text-center py-8">
                <svg class="mx-auto h-8 w-8 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">No heavy usage detected</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($heavyUsers->take(3) as $employee)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center">
                                <span class="text-xs font-medium text-blue-700 dark:text-blue-300">{{ substr($employee->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $employee->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $employee->branch->name ?? 'Unassigned' }}</p>
                            <p class="text-xs text-blue-600 dark:text-blue-400">{{ $employee->recent_loans_count }} loans (6 months)</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Stale Assets Report -->
    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 glass-card transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Stale Assets</h3>
            <a href="/api/reports/stale-assets" target="_blank" class="inline-flex items-center px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                </svg>
                JSON
            </a>
        </div>
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Assets idle for over a year</p>

        @if($stagnantAssets->isEmpty())
            <div class="text-center py-8">
                <svg class="mx-auto h-8 w-8 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">No stale assets found</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($stagnantAssets->take(3) as $asset)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-amber-100 dark:bg-amber-900/50 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $asset->assetType->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $asset->serial_number }}</p>
                            <p class="text-xs text-amber-600 dark:text-amber-400">{{ round($asset->days_in_stock) }} days idle</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Branch Inventory Report -->
    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 glass-card transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Branch Inventory</h3>
            <a href="/api/reports/branch-inventory" target="_blank" class="inline-flex items-center px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                </svg>
                JSON
            </a>
        </div>
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Asset distribution by branch</p>

        @if($branchInventory->isEmpty())
            <div class="text-center py-8">
                <svg class="mx-auto h-8 w-8 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">No inventory data available</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($branchInventory->take(2) as $inventory)
                    <div>
                        <p class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $inventory['branch'] }}</p>
                        <div class="mt-2 space-y-1">
                            @foreach($inventory['asset_types'] as $type => $count)
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-slate-600 dark:text-slate-400">{{ $type }}</span>
                                    <span class="font-medium text-slate-900 dark:text-slate-100">{{ $count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>