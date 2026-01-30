<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AgencyController;

Route::middleware(['auth.api'])
    ->controller(AgencyController::class)
    ->prefix('catalog')
    ->name('agencies.')
    ->group(function () {
        Route::get('/agencies','index')->name('index')->middleware('permission:agencies.index');
        Route::get('/agencies/{agencyId}','show')->name('show')->middleware('permission:agencies.show');
        Route::post('/agencies','store')->name('store')->middleware('permission:agencies.store');
        Route::patch('/agencies/{agencyId}','update')->name('update')->middleware('permission:agencies.update');
        Route::delete('/agencies/{agencyId}','destroy')->name('destroy')->middleware('permission:agencies.destroy');
        Route::get('/companies/{companyId}/agencies','byCompany')->name('byCompany')->middleware('permission:agencies.index');
    });
