<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\AgencyController;

Route::middleware(['auth:api'])
    ->controller(CompanyController::class)
    ->prefix('catalog/companies')
    ->name('companies.')
    ->group(function () {
        // Company routes
        Route::get('/','index')->name('index');
        Route::get('/{companyId}/companies','show')->name('show');
        Route::post('/','store')->name('store');
        Route::patch('/{companyId}','update')->name('update');
        Route::delete('/{companyId}','destroy')->name('destroy');
        Route::get('/instances/{instanceId}/companies','byInstance')->name('byInstance');
    });
