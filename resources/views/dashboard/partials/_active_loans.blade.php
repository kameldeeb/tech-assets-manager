<!-- Active Loans Table -->
<div class="bg-slate-800 rounded-xl shadow-sm border border-slate-700 glass-card overflow-hidden transition-all duration-300">
    <div class="px-6 py-4 border-b border-slate-700">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-100">Active Loans</h2>
                <p class="text-sm text-slate-400 mt-1">Current asset assignments</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-slate-700 text-slate-300">
                {{ $activeLoans->count() }} active
            </span>
        </div>
    </div>

    <div class="overflow-x-auto">
        @if($activeLoans->isNotEmpty())
            <table class="min-w-full divide-y divide-slate-700">
                <thead class="bg-slate-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Asset</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Checked Out</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Condition</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-slate-800 divide-y divide-slate-700">
                    @foreach($activeLoans as $loan)
                        <tr class="hover:bg-slate-700 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-slate-600 flex items-center justify-center">
                                            <span class="text-sm font-medium text-slate-300">{{ substr($loan->employee->name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-slate-100">{{ $loan->employee->name }}</div>
                                        <div class="text-sm text-slate-400">{{ $loan->employee->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-100">{{ $loan->asset->assetType->name ?? 'Unknown' }}</div>
                                <div class="text-sm text-slate-400">{{ $loan->asset->serial_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $loan->asset->status->value === 'borrowed' ? 'bg-blue-900/50 text-blue-300' : ($loan->asset->status->value === 'under_inspection' ? 'bg-amber-900/50 text-amber-300' : 'bg-slate-700 text-slate-300') }}">
                                    {{ ucwords(str_replace('_', ' ', $loan->asset->status->value)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-100">
                                <div>{{ $loan->borrowed_at->format('M j, Y') }}</div>
                                <div class="text-slate-400">{{ $loan->borrowed_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-100">
                                {{ ucwords(str_replace('_', ' ', $loan->condition_at_checkout->value ?? 'Unknown')) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <form method="POST" action="/returns/{{ $loan->id }}" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                                        Return
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m8-5v2m0 0v2m0-2h2m-2 0h-2"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-slate-100">No active loans</h3>
                <p class="mt-1 text-sm text-slate-400">Get started by assigning an asset to an employee.</p>
            </div>
        @endif
    </div>
</div>