<?php

use App\Http\Controllers\Api\MenuController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth.api'])
    ->controller(MenuController::class)
    ->prefix('menus')
    ->name('menus.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:menus.index');
        Route::get('/{menuId}', 'show')->name('show')->middleware('permission:menus.index');
        Route::post('/', 'store')->name('store')->middleware('permission:menus.store');
        Route::patch('/{menuId}', 'update')->name('update')->middleware('permission:menus.update');
        Route::delete('/{menuId}', 'destroy')->name('destroy')->middleware('permission:menus.destroy');
    });
