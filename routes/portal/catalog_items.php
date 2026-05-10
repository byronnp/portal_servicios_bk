<?php

use App\Http\Controllers\Api\CatalogItemController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth.api'])
    ->controller(CatalogItemController::class)
    ->prefix('catalog/catalog-items')
    ->name('catalog-items.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:catalog-items.index');
        Route::get('/catalog-types/{catalogTypeId}', 'byCatalogType')->name('byCatalogType')->middleware('permission:catalog-items.index');
        Route::get('/{catalogItemId}', 'show')->name('show')->middleware('permission:catalog-items.index');
        Route::post('/', 'store')->name('store')->middleware('permission:catalog-items.store');
        Route::patch('/{catalogItemId}', 'update')->name('update')->middleware('permission:catalog-items.update');
        Route::delete('/{catalogItemId}', 'destroy')->name('destroy')->middleware('permission:catalog-items.destroy');
    });
