<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\InstanceController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\AgencyController;

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});


// Protected routes
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'me']);

    // Session management routes
    Route::get('/sessions/active', [AuthController::class, 'getActiveSessions']);
    Route::get('/sessions/history', [AuthController::class, 'getSessionsHistory']);
    Route::post('/sessions/{logId}/force-logout', [AuthController::class, 'forceLogout']);

    // User activation/deactivation routes
    Route::post('/users/{userId}/activate', [AuthController::class, 'activateUser']);
    Route::post('/users/{userId}/deactivate', [AuthController::class, 'deactivateUser']);

    // Company routes
    //Route::apiResource('companies', CompanyController::class);
    //Route::get('/instances/{instanceId}/companies', [CompanyController::class, 'byInstance']);

    // Agency routes
    //Route::apiResource('agencies', AgencyController::class);
    //Route::get('/companies/{companyId}/agencies', [AgencyController::class, 'byCompany']);
});
