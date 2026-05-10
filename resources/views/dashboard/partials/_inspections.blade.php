<!-- Inspections Tab Content -->
<div class="bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-700 glass-card transition-all duration-300">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-slate-100">Inspection Center</h2>
            <p class="text-sm text-slate-400 mt-1">Verify returned assets and update their condition</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-700">
            <thead class="bg-slate-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Asset Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Serial Number</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Employee</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Returned At</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-slate-800 divide-y divide-slate-700">
                @foreach($inspections as $inspection)
                    <tr class="hover:bg-slate-700 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-100">{{ $inspection->asset->assetType->name ?? 'Unknown' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-100">{{ $inspection->asset->serial_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-100">{{ $inspection->loan->employee->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-100">{{ $inspection->created_at->format('M j, Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button @click="openModal({{ $inspection->id }}, '{{ $inspection->asset->serial_number }}')" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                                Verify Condition
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Inspection Modal -->
<div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" @click="closeModal" aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-700">
            <form method="POST" :action="'/inspections/' + selectedInspectionId + '/update'">                @csrf
                <div class="bg-slate-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-900/50 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-slate-100" id="modal-title">
                                Verify Asset Condition
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-slate-400">
                                    Asset: <span x-text="selectedAsset" class="text-slate-100"></span>
                                </p>
                            </div>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="condition" class="block text-sm font-medium text-slate-300">Technical Condition</label>
                                    <select id="condition" name="condition" class="mt-1 block w-full bg-slate-700 border border-slate-600 rounded-md shadow-sm py-2 px-3 text-slate-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        <option value="excellent">Excellent</option>
                                        <option value="good">Good</option>
                                        <option value="fair">Fair</option>
                                        <option value="needs_repair">Needs Repair</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="status" class="block text-sm font-medium text-slate-300">New Status</label>
                                    <select id="status" name="status" class="mt-1 block w-full bg-slate-700 border border-slate-600 rounded-md shadow-sm py-2 px-3 text-slate-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        <option value="available">Available</option>
                                        <option value="maintenance">Maintenance</option>
                                        <option value="retired">Retired</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Update Asset
                    </button>
                    <button type="button" @click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-slate-600 shadow-sm px-4 py-2 bg-slate-700 text-base font-medium text-slate-300 hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- 
<script>
function inspectionsApp() {
    return {
        showModal: false,
        selectedInspectionId: null,
        selectedAsset: '',

        openModal(inspectionId, assetSerial) {
            this.selectedInspectionId = inspectionId;
            this.selectedAsset = assetSerial;
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
            this.selectedInspectionId = null;
            this.selectedAsset = '';
        },

        submitForm() {
            const form = document.querySelector('form');
            form.action = `/inspections/${this.selectedInspectionId}/update`;
            form.submit();
        }
    }
}
</script> -->