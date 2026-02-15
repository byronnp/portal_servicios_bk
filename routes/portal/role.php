<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RolesController;

Route::middleware(['auth.api'])
    ->controller(RolesController::class)
    ->prefix('roles')
    ->name('roles.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:roles.index');
        Route::get('/{id}', 'show')->name('show')->middleware('permission:roles.show');
        Route::post('/', 'store')->name('store')->middleware('permission:roles.store');
        Route::patch('/{role}', 'update')->name('update')->withTrashed()->middleware('permission:roles.update');
        Route::delete('/{role}', 'destroy')->name('destroy')->withTrashed()->middleware('permission:roles.destroy');
    });
