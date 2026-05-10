<?php

namespace App\Http\Controllers;

use App\Services\ReturnService;
use App\Http\Requests\ReturnLoanRequest;

class ReturnController extends Controller
{
    public function store(
        ReturnLoanRequest $request,
        ReturnService $returnService,
        int $loanId
    ) {

        try {
            $returnService->processReturn(
                loanId: $loanId
            );

            return redirect()->back()
                ->with('success', 'Asset returned successfully and sent for inspection.');
        } catch (\Exception $exception) {
            return redirect()->back()
                ->with('error', 'Failed to return asset: ' . $exception->getMessage());
        }
    }
}