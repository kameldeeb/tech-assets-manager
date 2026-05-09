<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\Asset;
use App\Models\Inspection;
use Illuminate\Support\Facades\DB;
use App\Exceptions\InvalidInspectionStateException;

class InspectionService
{
    public function completeInspection(
        int $assetId,
        int $loanId,
        int $inspectorId,
        string $result,
        ?string $notes = null
    ): Inspection {

        return DB::transaction(function () use (
            $assetId,
            $loanId,
            $inspectorId,
            $result,
            $notes
        ) {

            /*
            |--------------------------------------------------------------------------
            | Lock Asset
            |--------------------------------------------------------------------------
            */

            $asset = Asset::lockForUpdate()
                ->findOrFail($assetId);

            /*
            |--------------------------------------------------------------------------
            | Ensure Asset Is Under Inspection
            |--------------------------------------------------------------------------
            */

            if ($asset->status !== 'under_inspection') {

                throw new InvalidInspectionStateException();
            }

            /*
            |--------------------------------------------------------------------------
            | Create Inspection Record
            |--------------------------------------------------------------------------
            */

            $inspection = Inspection::create([
                'asset_id' => $assetId,
                'loan_id' => $loanId,
                'inspected_by' => $inspectorId,
                'result' => $result,
                'notes' => $notes,
                'inspected_at' => now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Determine Final Asset Status
            |--------------------------------------------------------------------------
            */

            $finalStatus = match ($result) {

                'excellent', 'good'
                    => 'available',

                'damaged', 'maintenance_required'
                    => 'maintenance',

                default
                    => throw new \InvalidArgumentException(
                        'Invalid inspection result.'
                    )
            };

            /*
            |--------------------------------------------------------------------------
            | Update Asset Status
            |--------------------------------------------------------------------------
            */

            $asset->update([
                'status' => $finalStatus
            ]);

            return $inspection;
        });
    }
}