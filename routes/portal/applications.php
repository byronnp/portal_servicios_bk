<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApplicationController;

Route::middleware(['auth.api'])
    ->controller(ApplicationController::class)
    ->prefix('catalog')
    ->name('applications.')
    ->group(function () {
        Route::get('/applications','index')->name('index')->middleware('permission:applications.index');
        Route::get('/applications/active','active')->name('active')->middleware('permission:applications.index');
        Route::get('/applications/web','web')->name('web')->middleware('permission:applications.index');
        Route::get('/applications/mobile','mobile')->name('mobile')->middleware('permission:applications.index');
        Route::get('/applications/{applicationId}','show')->name('show')->middleware('permission:applications.show');
        Route::post('/applications','store')->name('store')->middleware('permission:applications.store');
        Route::patch('/applications/{applicationId}','update')->name('update')->middleware('permission:applications.update');
        Route::delete('/applications/{applicationId}','destroy')->name('destroy')->middleware('permission:applications.destroy');
    });
