<!-- Operational Reports -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Heavy Usage Report -->
    <div class="bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-700 glass-card transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-slate-100">Heavy Usage</h3>
            <a href="/api/reports/heavy-usage" target="_blank" class="inline-flex items-center px-3 py-2 border border-slate-600 rounded-lg text-sm font-medium text-slate-300 bg-slate-700 hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                </svg>
                JSON
            </a>
        </div>
        <p class="text-sm text-slate-400 mb-4">Employees with high loan frequency</p>

        @if($heavyUsers->isEmpty())
            <div class="text-center py-8">
                <svg class="mx-auto h-8 w-8 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <p class="mt-2 text-sm text-slate-400">No heavy usage detected</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($heavyUsers->take(3) as $employee)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-900/50 rounded-full flex items-center justify-center">
                                <span class="text-xs font-medium text-blue-300">{{ substr($employee->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-100">{{ $employee->name }}</p>
                            <p class="text-xs text-slate-400">{{ $employee->branch->name ?? 'Unassigned' }}</p>
                            <p class="text-xs text-blue-400">{{ $employee->recent_loans_count }} loans (6 months)</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Stale Assets Report -->
    <div class="bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-700 glass-card transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-slate-100">Stale Assets</h3>
            <span class="text-[10px] font-bold px-2 py-1 bg-amber-900/30 text-amber-400 rounded-full uppercase tracking-tighter">Oldest First</span>
        </div>
        
        <p class="text-sm text-slate-400 mb-4">Assets idle for over a year, sorted by longest duration in stock.</p>

        @if($stagnantAssets->isEmpty())
            <div class="text-center py-8">
                <p class="text-sm text-slate-500">No stale assets found</p>
            </div>
        @else
            <div class="space-y-4 max-h-[320px] overflow-y-auto pr-2 custom-scrollbar">
                @foreach($stagnantAssets as $asset)
                    <div class="flex items-start space-x-3 p-2 rounded-lg hover:bg-slate-700/40 transition-all border border-transparent hover:border-slate-600">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-amber-900/40 rounded-full flex items-center justify-center border border-amber-800/50">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-center">
                                <p class="text-sm font-bold text-slate-100">{{ $asset->assetType->name }}</p>
                                <span class="text-[15px] text-amber-500 font-mono">
                                    @if($asset->idle_duration['y'] > 0)
                                        {{ $asset->idle_duration['y'] }}y
                                    @endif
                                    @if($asset->idle_duration['m'] > 0)
                                        {{ $asset->idle_duration['m'] }}m
                                    @endif
                                    {{ $asset->idle_duration['d'] }}d
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 font-mono">{{ $asset->serial_number }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($stagnantAssets->count() > 4)
                <div class="mt-4 pt-3 border-t border-slate-700/50 flex justify-between items-center text-[10px] text-slate-500">
                    <span>Total Stale: {{ $stagnantAssets->count() }} items</span>
                    <span class="animate-pulse">Scroll to see more ↓</span>
                </div>
            @endif
        @endif
    </div>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>

    <!-- Branch Inventory Report -->
    <div class="bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-700 glass-card transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-slate-100">Branch Inventory</h3>
            <a href="/api/reports/branch-inventory" target="_blank" class="inline-flex items-center px-3 py-2 border border-slate-600 rounded-lg text-sm font-medium text-slate-300 bg-slate-700 hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                </svg>
                JSON
            </a>
        </div>
        <p class="text-sm text-slate-400 mb-4">Asset distribution by branch</p>

        @if($branchInventory->isEmpty())
            <div class="text-center py-8">
                <svg class="mx-auto h-8 w-8 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <p class="mt-2 text-sm text-slate-400">No inventory data available</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($branchInventory->take(2) as $inventory)
                    <div>
                        <p class="text-sm font-medium text-slate-100">{{ $inventory['branch'] }}</p>
                        <div class="mt-2 space-y-1">
                            @foreach($inventory['asset_types'] as $type => $count)
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-slate-400">{{ $type }}</span>
                                    <span class="font-medium text-slate-100">{{ $count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>