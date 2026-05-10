<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\Inspection;
use Illuminate\Support\Facades\DB;
use App\Exceptions\InvalidReturnOperationException;

class ReturnService
{
    public function processReturn(
        int $loanId
    ): Loan {

        return DB::transaction(function () use (
            $loanId
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
            ]);

            /*
            |--------------------------------------------------------------------------
            | Move Asset To Inspection
            |--------------------------------------------------------------------------
            */

            $loan->asset->update([
                'status' => 'under_inspection'
            ]);

            /*
            |--------------------------------------------------------------------------
            | Create Inspection Record
            |--------------------------------------------------------------------------
            */

            Inspection::create([
                'asset_id' => $loan->asset_id,
                'loan_id' => $loan->id,
                'inspected_by' => null, // Will be set when inspected
            ]);

            return $loan->fresh();
        });
    }
}