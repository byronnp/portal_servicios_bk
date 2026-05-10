<?php

use App\Http\Controllers\Api\PermissionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth.api'])
    ->controller(PermissionController::class)
    ->prefix('permissions')
    ->name('permissions.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:permissions.index');
        Route::get('/{permissionId}', 'show')->name('show')->middleware('permission:permissions.index');
        Route::post('/', 'store')->name('store')->middleware('permission:permissions.store');
        Route::patch('/{permissionId}', 'update')->name('update')->middleware('permission:permissions.update');
        Route::delete('/{permissionId}', 'destroy')->name('destroy')->middleware('permission:permissions.destroy');
    });
