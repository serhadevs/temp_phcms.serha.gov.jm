<?php

use App\Http\Controllers\Api\Auth;
use App\Http\Controllers\Api\PermitApplicationApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;


// Route to get authenticated user details
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Route for user login
Route::middleware(['api.client'])->group(function () {
    Route::post('loginapi', [Auth::class, 'login']);
    Route::post('/login/user', [Auth::class, 'loginuser']);
    Route::post('/forgot-password', [Auth::class, 'sendOtp']);
    Route::post('/verify-otp', [Auth::class, 'verifyOtp']);
    Route::post('/reset-password', [Auth::class, 'resetPassword']);
});

// Route::post('/forgot-password', [Auth::class, 'sendOtp']);
// Route::post('/verify-otp', [Auth::class, 'verifyOtp']);
// Route::post('/reset-password', [Auth::class, 'resetPassword']);

Route::get('/verify-permit/{permit_no}', [PermitApplicationApi::class, 'verifyPermit'])->name('permit.verify');
Route::post('/auth/verify-activation-code', [Auth::class, 'verifyCode']);
Route::post('/auth/set-password', [Auth::class, 'setPassword']);
Route::post('/generate-verification-link/{permitNo}', [PermitApplicationApi::class, 'generateLink']);


// Routes that require authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/applicant/{permit_no}', [PermitApplicationApi::class, 'fetchApplications']);
    Route::get('/verify-permit/download/{id}', [PermitApplicationApi::class, 'downloadCertificate'])->name('api.download');
    Route::post('/logout', [Auth::class, 'logout']);

    //Notifications

});
