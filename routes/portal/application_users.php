<?php

use App\Http\Controllers\Api\ApplicationUserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth.api'])
    ->controller(ApplicationUserController::class)
    ->prefix('application-users')
    ->name('application-users.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:application-users.index');
        Route::get('/{assignmentId}', 'show')->name('show')->middleware('permission:application-users.index');
        Route::post('/', 'store')->name('store')->middleware('permission:application-users.store');
        Route::patch('/{assignmentId}', 'update')->name('update')->middleware('permission:application-users.update');
        Route::delete('/{assignmentId}', 'destroy')->name('destroy')->middleware('permission:application-users.destroy');
    });
