<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UMKMController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/admin/logout', [AdminAuthController::class, 'logout']);
    Route::get('/admin/me', [AdminAuthController::class, 'me']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/categories', [CategoryController::class, 'index']);   // Get all categories
    Route::get('/categories/{id}', [CategoryController::class, 'show']); // Get category by ID
    Route::post('/categories', [CategoryController::class, 'store']);   // Create new category
    Route::put('/categories/{id}', [CategoryController::class, 'update']); // Update category
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']); // Delete category

    Route::put('/umkms/{id}', [UMKMController::class, 'update']); // Update UMKM
    Route::delete('/umkms/{id}', [UMKMController::class, 'destroy']); // Delete UMKM
});

Route::post('/umkms', [UMKMController::class, 'store']);   // Create new UMKM
Route::get('/umkms', [UMKMController::class, 'index']);   // Get all UMKM
Route::get('/umkms/{id}', [UMKMController::class, 'show']); // Get UMKM by ID