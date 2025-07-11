<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PublicationController as ApiPublicationController;
use App\Http\Controllers\Api\DashboardController as ApiDashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API Routes untuk Testing
Route::prefix('test')->group(function () {
    // Auth API
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    
    // Dashboard API
    Route::get('/dashboard', [ApiDashboardController::class, 'index'])->middleware('auth:sanctum');
    
    // Publication API
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/publications', [ApiPublicationController::class, 'index']);
        Route::post('/publications', [ApiPublicationController::class, 'store']);
        Route::get('/publications/{id}', [ApiPublicationController::class, 'show']);
        Route::put('/publications/{id}', [ApiPublicationController::class, 'update']);
        Route::delete('/publications/{id}', [ApiPublicationController::class, 'destroy']);
        
        // Review API
        Route::post('/publications/{id}/approve', [ApiPublicationController::class, 'approve']);
        Route::post('/publications/{id}/reject', [ApiPublicationController::class, 'reject']);
        
        // Admin Review API
        Route::get('/admin/review', [ApiDashboardController::class, 'adminReview']);
        Route::post('/admin/review/{id}/approve', [ApiDashboardController::class, 'adminApprove']);
        Route::post('/admin/review/{id}/reject', [ApiDashboardController::class, 'adminReject']);
        
        // Dosen Review API
        Route::get('/dosen/review', [ApiDashboardController::class, 'dosenReview']);
        Route::post('/dosen/review/{id}/approve', [ApiDashboardController::class, 'dosenApprove']);
        Route::post('/dosen/review/{id}/reject', [ApiDashboardController::class, 'dosenReject']);
    });
}); 