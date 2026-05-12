<!-- Inventory Tab Content -->
<div class="bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-700 glass-card transition-all duration-300">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-slate-100">Asset Inventory</h2>
            <p class="text-sm text-slate-400 mt-1">Complete list of assets with real-time location and history</p>
        </div>
        <div class="flex space-x-3">
             <a href="/api/reports/stale-assets" target="_blank" class="inline-flex items-center px-4 py-2 border border-slate-600 rounded-lg text-sm font-medium text-slate-100 bg-slate-700 hover:bg-slate-600 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export JSON
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-700">
            <thead class="bg-slate-700">
                <tr>
                    @php
                        // إعدادات الترتيب
                        $sort = request('sort', 'serial_number');
                        $dir = request('direction', 'asc');
                        $nextDir = $dir === 'asc' ? 'desc' : 'asc';
                        
                        $headers = [
                            'type' => 'Asset Info',
                            'status' => 'Status & Condition',
                            'holder' => 'Current Holder',
                            'purchase_date' => 'Time Info',
                            'notes' => 'Technical Notes'
                        ];
                    @endphp

                    @foreach($headers as $key => $label)
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => $key, 'direction' => $nextDir]) }}" class="flex items-center hover:text-white transition">
                                {{ $label }}
                                @if($sort === $key)
                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $dir === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path>
                                    </svg>
                                @endif
                            </a>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-slate-800 divide-y divide-slate-700">
                @foreach($allAssets as $asset)
                    <tr class="hover:bg-slate-700 transition">
                        <!-- معلومات الجهاز -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-slate-100">{{ $asset->assetType->name ?? 'Unknown' }}</div>
                            <div class="text-xs text-slate-400 font-mono">{{ $asset->serial_number }}</div>
                        </td>

                        <!-- الحالة الفنية والتوفر -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusValue = $asset->status->value ?? $asset->status;
                                $statusColor = match($statusValue) {
                                    'available' => 'bg-green-900/50 text-green-300',
                                    'borrowed' => 'bg-blue-900/50 text-blue-300',
                                    'under_inspection' => 'bg-amber-900/50 text-amber-300',
                                    'damaged' => 'bg-red-900/50 text-red-300',
                                    default => 'bg-slate-700 text-slate-300'
                                };
                            @endphp
                            <div class="flex flex-col space-y-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $statusColor }} w-fit shadow-sm">
                                    {{ str_replace('_', ' ', $statusValue) }}
                                </span>
                                <span class="text-xs text-slate-300">
                                    <span class="text-slate-500">Condition:</span> {{ ucwords($asset->condition->value ?? $asset->condition) }}
                                </span>
                            </div>
                        </td>

                        <!-- المسؤول الحالي والموقع -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($statusValue === 'borrowed' && $asset->currentHolder)
                                <div class="text-sm font-medium text-slate-100">{{ $asset->currentHolder->name }}</div>
                                <div class="text-xs text-slate-400">{{ $asset->currentHolder->department->branch->name ?? 'External' }}</div>
                            @else
                                <span class="text-xs text-slate-500 italic flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                    In Warehouse
                                </span>
                            @endif
                        </td>

                        <!-- معلومات الوقت -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-xs text-slate-100">Bought: {{ $asset->purchase_date->format('M d, Y') }}</div>
                            <div class="text-xs {{ $asset->days_in_stock > 365 ? 'text-amber-400' : 'text-slate-400' }} mt-1">
                                In System: {{ $asset->days_in_stock }} Days
                            </div>
                        </td>

                        <!-- الملاحظات التقنية -->
                        <td class="px-6 py-4 text-xs text-slate-400 max-w-xs italic leading-relaxed">
                            {{ $asset->latest_note }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
