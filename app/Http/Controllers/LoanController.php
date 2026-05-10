<?php

namespace App\Http\Controllers;

use App\Exceptions\DuplicateAssetTypeLoanException;
use App\Http\Requests\StoreLoanRequest;
use App\Services\LoanService;
use Illuminate\Http\RedirectResponse;

class LoanController extends Controller
{
    public function store(
        StoreLoanRequest $request,
        LoanService $loanService
    ): RedirectResponse {

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
                ->with('error', $exception->getMessage());
        }
    }
}