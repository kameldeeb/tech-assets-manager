<!-- Inventory Tab Content -->
<div class="bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-700 glass-card transition-all duration-300">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-slate-100">Asset Inventory</h2>
            <p class="text-sm text-slate-400 mt-1">Complete list of all assets in the system</p>
        </div>
        <a href="/api/reports/stale-assets" target="_blank" class="inline-flex items-center px-4 py-2 border border-slate-600 rounded-lg text-sm font-medium text-slate-100 bg-slate-700 hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
            </svg>
            Export JSON
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-700">
            <thead class="bg-slate-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Asset Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Serial Number</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Purchase Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Days in Stock</th>
                </tr>
            </thead>
            <tbody class="bg-slate-800 divide-y divide-slate-700">
                @foreach($stagnantAssets as $asset)
                    <tr class="hover:bg-slate-700 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-100">{{ $asset->assetType->name ?? 'Unknown' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-100">{{ $asset->serial_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $asset->status->value === 'available' ? 'bg-green-900/50 text-green-300' : ($asset->status->value === 'borrowed' ? 'bg-blue-900/50 text-blue-300' : ($asset->status->value === 'under_inspection' ? 'bg-amber-900/50 text-amber-300' : 'bg-slate-700 text-slate-300')) }}">
                                {{ ucwords(str_replace('_', ' ', $asset->status->value)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-100">{{ $asset->purchase_date->format('M j, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-100">{{ round($asset->days_in_stock) }} days</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>