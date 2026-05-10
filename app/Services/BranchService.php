<?php

namespace App\Services;

use App\Models\Loan;
use Illuminate\Support\Collection;

class BranchService
{
    public function getInventory(): Collection
    {
        return Loan::with(['asset.assetType', 'employee.branch'])
            ->whereNull('returned_at')
            ->where('condition_at_checkout', 'excellent')
            ->get()
            ->groupBy(fn ($loan) => $loan->employee?->branch->name ?? 'Unassigned')
            ->map(function ($loans, string $branchName) {
                return [
                    'branch' => $branchName,
                    'asset_types' => $loans
                        ->groupBy(fn ($loan) => $loan->asset->assetType->name ?? 'Unknown')
                        ->map->count()
                        ->toArray(),
                ];
            });
    }
}
