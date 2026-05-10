<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\Employee;
use App\Models\Loan;
use App\Services\BranchService;
use App\Models\Inspection;
use Illuminate\Support\Collection;

class DashboardService
{
    public function __construct(private BranchService $branchService)
    {
    }

    public function getDashboardData(): array
    {
        return [
            'borrowedAssets' => $this->getBorrowedAssetsCount(),
            'underInspectionAssets' => $this->getUnderInspectionAssetsCount(),
            'totalAssets' => $this->getTotalAssetsCount(),
            'activeLoans' => $this->getActiveLoans(),
            'employees' => $this->getEmployees(),
            'availableAssets' => $this->getAvailableAssets(),
            'heavyUsers' => $this->getHeavyUsers(),
            'stagnantAssets' => $this->getStagnantAssets(),
            'branchInventory' => $this->getBranchInventory(),
            'assetTypeDistribution' => $this->getAssetTypeDistribution(),
            'inspections' => $this->getInspections(),
        ];
    }

    private function getBorrowedAssetsCount(): int
    {
        return Asset::where('status', 'borrowed')->count();
    }

    private function getUnderInspectionAssetsCount(): int
    {
        return Asset::where('status', 'under_inspection')->count();
    }

    private function getTotalAssetsCount(): int
    {
        return Asset::count();
    }

    private function getActiveLoans(): Collection
    {
        return Loan::with(['employee', 'asset.assetType'])
            ->whereNull('returned_at')
            ->latest()
            ->get();
    }

    private function getEmployees(): Collection
    {
        return Employee::orderBy('name')->get();
    }

    private function getAvailableAssets(): Collection
    {
        return Asset::with('assetType')
            ->where('status', 'available')
            ->get();
    }

    private function getHeavyUsers(): Collection
    {
        return Employee::intenseUsers()
            ->with('branch', 'department')
            ->withCount(['loans as recent_loans_count' => function ($query) {
                $query->where('borrowed_at', '>=', now()->subMonths(6));
            }])
            ->get();
    }

    private function getStagnantAssets(): Collection
    {
        return Asset::idle()
            ->with('assetType', 'loans')
            ->get()
            ->sortByDesc(fn (Asset $asset) => $asset->days_in_stock)
            ->values();
    }

    private function getAssetTypeDistribution(): array
    {
        return Asset::with('assetType')
            ->get()
            ->groupBy(fn (Asset $asset) => $asset->assetType->name ?? 'Unknown')
            ->map->count()
            ->toArray();
    }

    private function getBranchInventory(): Collection
    {
        return $this->branchService->getInventory();
    }

    private function getInspections(): Collection
    {
        return Inspection::with(['asset.assetType', 'loan.employee'])
            ->whereNull('completed_at')
            ->latest()
            ->get();
    }
}
