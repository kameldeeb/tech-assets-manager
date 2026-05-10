<?php

namespace App\Services;

use App\Enums\AssetStatus;
use App\Enums\Condition;
use App\Exceptions\DuplicateAssetTypeLoanException;
use App\Models\Asset;
use App\Models\Loan;
use Illuminate\Support\Facades\DB;

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
            $asset = $this->getAssetForLoan($assetId);

            $this->assertAssetIsAvailable($asset);
            $this->assertEmployeeHasNoActiveLoanOfAssetType($employeeId, $asset);

            return $this->createLoan($employeeId, $asset, $conditionAtCheckout);
        });
    }

    public function canIssueLoan(int $employeeId, int $assetId): bool
    {
        try {
            $asset = $this->getAssetForLoan($assetId);
            $this->assertAssetIsAvailable($asset);
            $this->assertEmployeeHasNoActiveLoanOfAssetType($employeeId, $asset);

            return true;
        } catch (DuplicateAssetTypeLoanException) {
            return false;
        } catch (\Throwable) {
            return false;
        }
    }

    private function getAssetForLoan(int $assetId): Asset
    {
        return Asset::with('assetType')
            ->lockForUpdate()
            ->findOrFail($assetId);
    }

    private function assertAssetIsAvailable(Asset $asset): void
    {
        if (! $asset->isAvailable()) {
            throw new \RuntimeException('Asset is not available for loan.');
        }
    }

    private function assertEmployeeHasNoActiveLoanOfAssetType(int $employeeId, Asset $asset): void
    {
        $hasActiveLoan = Loan::where('employee_id', $employeeId)
            ->whereNull('returned_at')
            ->whereHas('asset', function ($query) use ($asset) {
                $query->where('asset_type_id', $asset->asset_type_id);
            })
            ->exists();

        if ($hasActiveLoan) {
            throw new DuplicateAssetTypeLoanException();
        }
    }

    private function createLoan(int $employeeId, Asset $asset, ?string $conditionAtCheckout): Loan
    {
        $conditionAtCheckout = $this->parseCondition($conditionAtCheckout);

        $loan = Loan::create([
            'employee_id' => $employeeId,
            'asset_id' => $asset->id,
            'borrowed_at' => now(),
            'condition_at_checkout' => $conditionAtCheckout,
        ]);

        $asset->update(['status' => AssetStatus::BORROWED]);

        return $loan;
    }

    private function parseCondition(?string $conditionAtCheckout): ?Condition
    {
        return $conditionAtCheckout !== null
            ? Condition::from($conditionAtCheckout)
            : null;
    }
}
