<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InstanceController;

Route::middleware(['auth.api'])
    ->controller(InstanceController::class)
    ->prefix('catalog/instances')
    ->name('instances.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:instances.index');
        Route::get('/{instanceId}', 'show')->name('show')->middleware('permission:instances.show');
        Route::post('/', 'store')->name('store')->middleware('permission:instances.store');
        Route::patch('/{instanceId}', 'update')->name('update')->middleware('permission:instances.update');
        Route::delete('/{instanceId}', 'destroy')->name('destroy')->middleware('permission:instances.destroy');
    });
