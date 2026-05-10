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
    public function store(
        CompleteInspectionRequest $request,
        InspectionService $inspectionService
    ): RedirectResponse {

        $inspectionService->completeInspection(
            assetId: $request->asset_id,
            loanId: $request->loan_id,
            inspectorId: auth()->id(),
            result: $request->result,
            notes: $request->notes
        );

        return redirect()->back()
            ->with('success', 'Inspection completed successfully.');
    }

    public function update(Request $request, Inspection $inspection): RedirectResponse
    {
        $request->validate([
            'condition' => ['required', new In(Condition::values())],
            'status' => ['required', new In(AssetStatus::values())],
        ]);

        try {
            \DB::beginTransaction(); // استخدام Transaction لضمان تنفيذ كل العمليات أو إلغائها معاً

            $conditionValue = $request->input('condition');
            $statusValue = $request->input('status');

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
                'completed_at' => now(),
                'inspected_by' => auth()->id() ?? 1, // إسناد المفتش لتجنب خطأ الـ Null
            ]);

            \DB::commit();

            return redirect()->route('dashboard')
                ->with('success', 'Asset verification completed and asset is now ' . $statusValue);

        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->route('dashboard')
                ->with('error', 'Critical Error: ' . $exception->getMessage());
        }
    }
}