<?php

use App\Http\Controllers\BackPanel\AuthController;
use App\Http\Controllers\BackPanel\CategoryController;
use App\Http\Controllers\BackPanel\DashboardController;
use App\Http\Controllers\Backpanel\OtpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);


Route::middleware(['auth.api'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    // Route::post('/logout',[AuthController::class,'logout']);

    Route::post('/verifyotp', [OtpController::class, 'verifyOtp']);
    Route::post('/resetpassword', [AuthController::class, 'resetPassword']);
    Route::post('/addcategory', [CategoryController::class, 'save']);
    Route::get('/getcategories', [CategoryController::class, 'list']);
    Route::post('/deletecategory', [CategoryController::class, 'delete']);
    // Route::post('/updatepassword', [ProfileController::class, 'updatePassword']);
    // Route::post('/updateprofile', [ProfileController::class, 'updateProfile']);
    // Route::post('/emailverification', [OtpController::class, 'emailVerificationOtp']);
});


// Route::get('/token', function (Request $request) {
//     $token = $request->header('Authorization');
//     dd($token);
// });