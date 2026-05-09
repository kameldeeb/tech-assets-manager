<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Asset;
use App\Models\Loan;

class ReportApiController extends Controller
{
    public function heavyUsage()
    {
        return response()->json(
            Employee::intenseUsers()
                ->with('branch', 'department')
                ->withCount(['loans as recent_loans_count' => function ($query) {
                    $query->where('borrowed_at', '>=', now()->subMonths(6));
                }])
                ->get()
                ->map(function ($employee) {
                    return [
                        'id' => $employee->id,
                        'name' => $employee->name,
                        'email' => $employee->email,
                        'branch' => $employee->branch->name ?? 'Unassigned',
                        'department' => $employee->department->name ?? 'Unassigned',
                        'recent_loans_count' => $employee->recent_loans_count,
                    ];
                })
        );
    }

    public function staleAssets()
    {
        return response()->json(
            Asset::idle()
                ->with('assetType')
                ->get()
                ->map(function ($asset) {
                    return [
                        'id' => $asset->id,
                        'asset_type' => $asset->assetType->name ?? 'Unknown',
                        'serial_number' => $asset->serial_number,
                        'purchase_date' => $asset->purchase_date->format('Y-m-d'),
                        'days_in_stock' => round($asset->days_in_stock),
                    ];
                })
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