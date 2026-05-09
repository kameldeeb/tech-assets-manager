<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\Asset;
use Illuminate\Support\Facades\DB;
use App\Exceptions\DuplicateAssetTypeLoanException;

class LoanService
{
    public function issueLoan(
        int $employeeId,
        int $assetId,
        ?string $conditionAtCheckout = null
    ): Loan {

        return DB::transaction(function () use (
            $employeeId,
            $assetId,
            $conditionAtCheckout
        ) {

            /*
            |--------------------------------------------------------------------------
            | Lock Asset Row
            |--------------------------------------------------------------------------
            */

            $asset = Asset::with('assetType')
                ->lockForUpdate()
                ->findOrFail($assetId);

            /*
            |--------------------------------------------------------------------------
            | Ensure Asset Is Available
            |--------------------------------------------------------------------------
            */

            if ($asset->status !== 'available') {

                throw new \Exception(
                    'Asset is not available for loan.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Check Existing Active Loan
            |--------------------------------------------------------------------------
            */

            $hasActiveLoan = Loan::where('employee_id', $employeeId)
                ->whereNull('returned_at')
                ->whereHas('asset', function ($query) use ($asset) {

                    $query->where(
                        'asset_type_id',
                        $asset->asset_type_id
                    );
                })
                ->exists();

            /*
            |--------------------------------------------------------------------------
            | Business Rule Violation
            |--------------------------------------------------------------------------
            */

            if ($hasActiveLoan) {

                throw new DuplicateAssetTypeLoanException();
            }

            /*
            |--------------------------------------------------------------------------
            | Create Loan
            |--------------------------------------------------------------------------
            */

            $loan = Loan::create([
                'employee_id' => $employeeId,
                'asset_id' => $asset->id,
                'borrowed_at' => now(),
                'condition_at_checkout' => $conditionAtCheckout,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Update Asset Status
            |--------------------------------------------------------------------------
            */

            $asset->update([
                'status' => 'borrowed'
            ]);

            return $loan;
        });
    }
}