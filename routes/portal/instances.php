<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InstanceController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\AgencyController;

Route::middleware(['auth:api'])
    ->controller(InstanceController::class)
    ->prefix('catalog/instances')
    ->name('instances.')
    ->group(function () {
        // Instance routes
        Route::apiResource('', InstanceController::class);
    });
