<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::middleware(['auth.api'])
    ->controller(AuthController::class)
    ->group(function () {
        // Current user routes
        Route::get('/me', 'me')->name('user.me');
        Route::patch('/user', 'updateUser')->name('user.update');
        Route::patch('/profile', 'updateProfile')->name('profile.update');

        // Session management routes
        Route::get('/sessions/active', 'getActiveSessions')->name('sessions.active')->middleware('permission:auth.sessions.manage');
        Route::get('/sessions/history', 'getSessionsHistory')->name('sessions.history')->middleware('permission:auth.sessions.manage');
        Route::post('/sessions/{logId}/force-logout', 'forceLogout')->name('sessions.force-logout')->middleware('permission:auth.sessions.manage');

        // User management routes
        Route::get('/users/{userId}', 'getUserById')->name('users.show')->middleware('permission:auth.users.show');
        Route::patch('/users/{userId}', 'updateUserById')->name('users.update')->middleware('permission:auth.users.update');
        Route::patch('/users/{userId}/profile', 'updateProfileById')->name('users.profile.update')->middleware('permission:auth.profile.update');
        Route::post('/users/{userId}/activate', 'activateUser')->name('users.activate')->middleware('permission:auth.users.activate');
        Route::post('/users/{userId}/deactivate', 'deactivateUser')->name('users.deactivate')->middleware('permission:auth.users.deactivate');
    });
