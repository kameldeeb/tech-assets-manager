<?php

namespace App\Http\Controllers;

use App\Services\InspectionService;
use App\Http\Requests\CompleteInspectionRequest;

class InspectionController extends Controller
{
    public function store(
        CompleteInspectionRequest $request,
        InspectionService $inspectionService
    ) {

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
}