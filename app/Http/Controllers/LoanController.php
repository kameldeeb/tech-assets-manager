<?php

namespace App\Http\Controllers;

use App\Exceptions\DuplicateAssetTypeLoanException;
use App\Services\LoanService;
use App\Http\Requests\StoreLoanRequest;

class LoanController extends Controller
{
    public function store(
        StoreLoanRequest $request,
        LoanService $loanService
    ) {

        try {
            $loanService->issueLoan(
                employeeId: $request->employee_id,
                assetId: $request->asset_id,
                conditionAtCheckout: $request->condition_at_checkout
            );

            return redirect()->back()
                ->with('success', 'Loan created successfully.');
        } catch (DuplicateAssetTypeLoanException $exception) {
            return redirect()->back()
                ->withErrors(['employee_id' => $exception->getMessage()]);
        }
    }
}