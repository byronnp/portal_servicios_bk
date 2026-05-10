<?php

use App\Http\Controllers\Api\CatalogTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth.api'])
    ->controller(CatalogTypeController::class)
    ->prefix('catalog/catalog-types')
    ->name('catalog-types.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:catalog-types.index');
        Route::get('/{catalogTypeId}', 'show')->name('show')->middleware('permission:catalog-types.index');
        Route::post('/', 'store')->name('store')->middleware('permission:catalog-types.store');
        Route::patch('/{catalogTypeId}', 'update')->name('update')->middleware('permission:catalog-types.update');
        Route::delete('/{catalogTypeId}', 'destroy')->name('destroy')->middleware('permission:catalog-types.destroy');
    });
