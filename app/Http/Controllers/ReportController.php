<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Employee;
use App\Models\Loan;
use App\Http\Resources\AssetResource;
use App\Http\Resources\EmployeeResource;

class ReportController extends Controller
{
    public function idleAssets()
    {
        return AssetResource::collection(
            Asset::idle()->get()
        );
    }

    public function intenseUsers()
    {
        return EmployeeResource::collection(
            Employee::intenseUsers()->get()
        );
    }

    public function heavyUsage()
    {
        return EmployeeResource::collection(
            Employee::intenseUsers()
                ->with('branch', 'department')
                ->withCount(['loans as recent_loans_count' => function ($query) {
                    $query->where('borrowed_at', '>=', now()->subMonths(6));
                }])
                ->get()
        );
    }

    public function staleAssets()
    {
        return AssetResource::collection(
            Asset::idle()
                ->with('assetType')
                ->get()
        );
    }

    public function branchInventory()
    {
        $inventory = Loan::with(['asset.assetType', 'employee.branch'])
            ->whereNull('returned_at')
            ->where('condition_at_checkout', 'excellent')
            ->get()
            ->groupBy(fn ($loan) => $loan->employee?->branch->name ?? 'Unassigned')
            ->map(function ($loans, $branchName) {
                return [
                    'branch' => $branchName,
                    'asset_types' => $loans
                        ->groupBy(fn ($loan) => $loan->asset->assetType->name ?? 'Unknown')
                        ->map->count()
                        ->toArray(),
                ];
            });

        return response()->json($inventory);
    }
}