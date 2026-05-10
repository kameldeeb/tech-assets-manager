<!-- Asset Assignment Card -->
<div class="bg-slate-800/50 rounded-xl p-6 shadow-xl border border-slate-700/50 backdrop-blur-sm transition-all duration-300">
    
    <!-- Header -->
    <div class="flex items-center space-x-3 mb-8">
        <div class="p-2 bg-blue-900/30 rounded-lg">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-100">Assign New Asset</h2>
            <p class="text-sm text-slate-400">Setup a new equipment loan for an employee</p>
        </div>
    </div>

    <form method="POST" action="/loans" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- Employee Selection -->
            <div class="space-y-2">
                <label for="employee_id" class="flex items-center text-sm font-semibold text-slate-300">
                    <svg class="w-4 h-4 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Target Employee
                </label>
                <div class="ts-control-dark">
                    <select id="employee_id" name="employee_id" required class="tom-select-custom">
                        <option value="">Search for an employee...</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->email }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Asset Selection (Fixed Relation) -->
            <div class="space-y-2">
                <label for="asset_id" class="flex items-center text-sm font-semibold text-slate-300">
                    <svg class="w-4 h-4 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Available Device
                </label>
                <div class="ts-control-dark">
                    <select id="asset_id" name="asset_id" required class="tom-select-custom">
                        <option value="">Search by type or serial...</option>
                        @foreach($availableAssets as $asset)
                            {{-- UX Improvement: Showing Asset Type clearly --}}
                            <option value="{{ $asset->id }}">
                                {{ $asset->assetType->name }} — {{ $asset->serial_number }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Condition Selection -->
        <div class="space-y-2">
            <label for="condition_at_checkout" class="flex items-center text-sm font-semibold text-slate-300">
                <svg class="w-4 h-4 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Current Technical State
            </label>
            <select id="condition_at_checkout" name="condition_at_checkout" required class="w-full rounded-lg border border-slate-600 bg-slate-700 text-sm text-slate-100 shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all px-4 py-3">
                <option value="excellent">Excellent - Brand New / Mint</option>
                <option value="good" selected>Good - Minor Signs of Use</option>
                <option value="fair">Fair - Visible Wear</option>
                <option value="needs_repair">Needs Repair</option>
            </select>
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
        <button type="submit" class="w-full inline-flex items-center justify-center gap-x-2 rounded-md bg-indigo-600 px-4 py-4 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all duration-200">
            <svg class="h-5 w-5 text-indigo-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
            </svg>
            Complete Assignment
        </button>
        </div>
    </form>
</div>

<style>
    /* Global Overrides for Dark Mode TomSelect */
    .ts-control {
        background-color: #0f172a !important; /* slate-900 */
        border-color: #334155 !important; /* slate-700 */
        color: #f1f5f9 !important;
        border-radius: 0.75rem !important;
        padding: 0.75rem !important;
        box-shadow: none !important;
    }

    .ts-control input { color: #f1f5f9 !important; }

    .ts-dropdown {
        background-color: #1e293b !important; /* slate-800 */
        color: #f1f5f9 !important;
        border-color: #334155 !important;
        border-radius: 0.75rem !important;
        margin-top: 5px !important;
        padding: 5px !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5) !important;
    }

    .ts-dropdown .option {
        padding: 10px 12px !important;
        border-radius: 0.5rem !important;
        color: #cbd5e1 !important;
    }

    .ts-dropdown .active {
        background-color: #2563eb !important; /* blue-600 */
        color: white !important;
    }

    .focus .ts-control {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2) !important;
    }
</style>