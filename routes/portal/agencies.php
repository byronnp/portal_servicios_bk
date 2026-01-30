<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AgencyController;

Route::middleware(['auth:api'])
    ->controller(AgencyController::class)
    ->prefix('catalog')
    ->name('companies.')
    ->group(function () {
        Route::get('/agencies','index')->name('index');
        Route::get('/agencies/{agencyId}','show')->name('show');
        Route::post('/agencies','store')->name('store');
        Route::patch('/agencies/{agencyId}','update')->name('update');
        Route::delete('/agencies/{agencyId}','destroy')->name('destroy');
        Route::get('/companies/{companyId}/agencies','byCompany')->name('byCompany');
    });
