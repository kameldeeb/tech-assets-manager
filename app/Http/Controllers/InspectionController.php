<?php

namespace App\Http\Controllers;

use App\Services\InspectionService;
use App\Http\Requests\CompleteInspectionRequest;
use App\Models\Inspection;
use App\Models\Asset;
use App\Enums\Condition;
use App\Enums\AssetStatus;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules\In;

class InspectionController extends Controller
{
 public function update(Request $request, Inspection $inspection): RedirectResponse
{
    $request->validate([
        'condition' => ['required', 'string'], // سيتم التحقق منها تلقائياً عند الحفظ بفضل الـ Casts في الموديل
        'status' => ['required', 'string'],
        'notes' => ['nullable', 'string', 'max:1000'], // أضفنا التحقق للملاحظات
    ]);

    try {
        \DB::beginTransaction();

        $conditionValue = $request->input('condition');
        $statusValue = $request->input('status');
        $notesValue = $request->input('notes');

        $asset = $inspection->asset;
        if ($asset) {
            $asset->update([
                'condition' => $conditionValue,
                'status' => $statusValue,
            ]);
        }

        $inspection->update([
            'verified_condition' => $conditionValue,
            'new_status' => $statusValue,
            'notes' => $notesValue, // حفظ الملاحظات هنا
            'completed_at' => now(),
            'inspected_at' => now(), // أضفنا تاريخ الفحص الفعلي
            'inspected_by' => auth()->id(), 
        ]);

        \DB::commit();

        return redirect()->route('dashboard')
            ->with('success', 'Asset verified as ' . $conditionValue . ' and set to ' . $statusValue);

    } catch (\Exception $exception) {
        \DB::rollBack();
        return redirect()->route('dashboard')
            ->with('error', 'Error updating inspection: ' . $exception->getMessage());
    }
}

}