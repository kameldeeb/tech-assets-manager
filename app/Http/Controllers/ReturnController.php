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

        $returnService->processReturn(
            loanId: $loanId,
            conditionAtReturn: $request->condition_at_return
        );

        return redirect()->back()
            ->with('success', 'Asset returned successfully.');
    }
}