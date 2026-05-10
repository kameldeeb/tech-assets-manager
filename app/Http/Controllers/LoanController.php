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
                ->with('success', 'Asset has been successfully assigned to the employee.');
        } catch (DuplicateAssetTypeLoanException $exception) {
            return redirect()->back()
                ->with('error', 'This employee already has an active loan for this type of asset.');
        } catch (\Throwable $exception) {
            return redirect()->back()
                ->with('error', 'Assignment failed: ' . $exception->getMessage());
        }
    }
}