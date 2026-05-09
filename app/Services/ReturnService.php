<?php

namespace App\Services;

use App\Models\Loan;
use Illuminate\Support\Facades\DB;
use App\Exceptions\InvalidReturnOperationException;

class ReturnService
{
    public function processReturn(
        int $loanId,
        ?string $conditionAtReturn = null
    ): Loan {

        return DB::transaction(function () use (
            $loanId,
            $conditionAtReturn
        ) {

            /*
            |--------------------------------------------------------------------------
            | Lock Loan With Asset
            |--------------------------------------------------------------------------
            */

            $loan = Loan::with('asset')
                ->lockForUpdate()
                ->findOrFail($loanId);

            /*
            |--------------------------------------------------------------------------
            | Ensure Loan Is Still Active
            |--------------------------------------------------------------------------
            */

            if ($loan->returned_at !== null) {

                throw new InvalidReturnOperationException();
            }

            /*
            |--------------------------------------------------------------------------
            | Close Loan
            |--------------------------------------------------------------------------
            */

            $loan->update([
                'returned_at' => now(),
                'condition_at_return' => $conditionAtReturn,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Move Asset To Inspection
            |--------------------------------------------------------------------------
            */

            $loan->asset->update([
                'status' => 'under_inspection'
            ]);

            return $loan->fresh();
        });
    }
}