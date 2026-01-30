<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\AgencyController;

Route::middleware(['auth.api'])
    ->controller(CompanyController::class)
    ->prefix('catalog/companies')
    ->name('companies.')
    ->group(function () {
        Route::get('/','index')->name('index')->middleware('permission:companies.index');
        Route::get('/{companyId}/companies','show')->name('show')->middleware('permission:companies.show');
        Route::post('/','store')->name('store')->middleware('permission:companies.store');
        Route::patch('/{companyId}','update')->name('update')->middleware('permission:companies.update');
        Route::delete('/{companyId}','destroy')->name('destroy')->middleware('permission:companies.destroy');
        Route::get('/instances/{instanceId}/companies','byInstance')->name('byInstance')->middleware('permission:companies.index');
    });
