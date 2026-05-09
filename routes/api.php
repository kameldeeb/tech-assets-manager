<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReportApiController;

Route::get('/reports/heavy-usage', [ReportApiController::class, 'heavyUsage']);
Route::get('/reports/stale-assets', [ReportApiController::class, 'staleAssets']);
Route::get('/reports/branch-inventory', [ReportApiController::class, 'branchInventory']);