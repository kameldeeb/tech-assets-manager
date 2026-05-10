<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [
        DashboardController::class,
        'index'
    ])->name('dashboard');

    Route::post('/loans', [
        LoanController::class,
        'store'
    ]);

    Route::post('/returns/{loanId}', [
        ReturnController::class,
        'store'
    ]);

    Route::post('/inspections', [
        InspectionController::class,
        'store'
    ]);

    Route::post('/inspections/{inspection}/update', [
        InspectionController::class,
        'update'
    ])->name('inspections.update');

    Route::get('/reports/stagnant-assets', [
        ReportController::class,
        'idleAssets'
    ]);

    Route::get('/reports/heavy-usage', [
        ReportController::class,
        'intenseUsers'
    ]);
});

require __DIR__.'/auth.php';