<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\Asset;
use App\Enums\AssetStatus;
use App\Enums\Condition;
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

            if ($asset->status !== AssetStatus::AVAILABLE) {

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

            if ($conditionAtCheckout !== null) {
                $conditionAtCheckout = Condition::from($conditionAtCheckout);
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
                'status' => AssetStatus::BORROWED,
            ]);

            return $loan;
        });
    }
}