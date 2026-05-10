<?php

namespace App\Http\Controllers;

use App\Services\ReturnService;
use App\Http\Requests\ReturnLoanRequest;
use App\Models\Loan;
use Illuminate\Http\RedirectResponse;

class ReturnController extends Controller
{
    public function store(
        ReturnLoanRequest $request,
        ReturnService $returnService,
        Loan $loanId
    ): RedirectResponse {

        try {
            $returnService->processReturn(
                loanId: $loanId->id
            );

            return redirect()->back()
                ->with('success', 'Asset returned successfully and sent for inspection.');
        } catch (\Exception $exception) {
            return redirect()->back()
                ->with('error', 'Failed to return asset: ' . $exception->getMessage());
        }
    }
}