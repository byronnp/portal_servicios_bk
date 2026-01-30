<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InstanceController;


Route::middleware(['auth:api'])
    ->controller(InstanceController::class)
    ->prefix('catalog/instances')
    ->name('instances.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{instanceId}', 'show')->name('show');
        Route::post('/', 'store')->name('store');
        Route::patch('/{instanceId}', 'update')->name('update');
        Route::delete('/{instanceId}', 'destroy')->name('destroy');
        // Instance routes
        //Route::apiResource('', InstanceController::class);
    });
