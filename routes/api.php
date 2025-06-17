<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\UserManagementController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    $request->user()->currentAccessToken()->delete();
    return response()->json(['message' => 'Logged out successfully']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/dashboard', function (Request $request) {
        return $request->user();
    });

    Route::get('/admin/dashboard', function () {
        return auth()->user();
    });
});


Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
    Route::get('/users', [UserManagementController::class, 'index']);
    Route::get('/users/{id}', [UserManagementController::class, 'show']);
    Route::put('/users/{id}', [UserManagementController::class, 'update']);
    Route::post('/users/{user}/role', action: [UserManagementController::class, 'updateRole']);
    Route::post('/users/{user}/reset-password', [UserManagementController::class, 'resetPassword']);
});

//*Experience Routes
Route::post('/create-experience', [ExperienceController::class, 'store'])->middleware('auth:sanctum');
Route::post('/admin/experience/{id}/publish-status', [ExperienceController::class, 'updatePublishStatus'])->middleware('auth:sanctum')->name('experience.publish-status');
Route::post('/admin/update-experience/{id}', [ExperienceController::class, 'update'])->middleware('auth:sanctum')->name('experience.update');
Route::post('/admin/delete-experience/{id}', [ExperienceController::class, 'delete'])->middleware('auth:sanctum')->name('experience.delete');

//*Blog Routes
Route::post('/create-blog', [BlogController::class, 'store'])->middleware('auth:sanctum');
Route::post('/admin/blog/{id}/publish-status', [BlogController::class, 'updatePublishStatus'])->middleware('auth:sanctum')->name('blogs.publish-status');
Route::post('/admin/update-blog/{id}', [BlogController::class, 'update'])->middleware('auth:sanctum')->name('blogs.update');
Route::post('/admin/delete-blog/{id}', [BlogController::class, 'destroy'])->middleware('auth:sanctum')->name('blog.delete'); 
