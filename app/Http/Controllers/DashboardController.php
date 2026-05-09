<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Loan;
use App\Models\Employee;

class DashboardController extends Controller
{
    public function index()
    {
        $borrowedAssets = Asset::where('status', 'borrowed')->count();
        $underInspectionAssets = Asset::where('status', 'under_inspection')->count();

        $activeLoans = Loan::with(['employee', 'asset.assetType'])
            ->whereNull('returned_at')
            ->latest()
            ->get();

        $employees = Employee::orderBy('name')->get();

        $availableAssets = Asset::with('assetType')
            ->where('status', 'available')
            ->get();

        $heavyUsers = Employee::intenseUsers()
            ->with('branch', 'department')
            ->withCount(['loans as recent_loans_count' => function ($query) {
                $query->where('borrowed_at', '>=', now()->subMonths(6));
            }])
            ->get();

        $stagnantAssets = Asset::idle()
            ->with('assetType')
            ->get();

        $branchInventory = Loan::with(['asset.assetType', 'employee.branch'])
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

        // Asset type distribution for charts
        $assetTypeDistribution = Asset::with('assetType')
            ->get()
            ->groupBy(fn ($asset) => $asset->assetType->name ?? 'Unknown')
            ->map->count()
            ->toArray();

        return view('dashboard', compact(
            'borrowedAssets',
            'underInspectionAssets',
            'activeLoans',
            'employees',
            'availableAssets',
            'heavyUsers',
            'stagnantAssets',
            'branchInventory',
            'assetTypeDistribution'
        ));
    }
}