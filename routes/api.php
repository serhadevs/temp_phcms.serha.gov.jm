<?php

use App\Http\Controllers\Api\Auth;
use App\Http\Controllers\Api\PermitApplicationApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;



//Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show'])->middleware('web');
// Route to get authenticated user details
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//  Route::get('/applicant/{permit_no}', [PermitApplicationApi::class, 'fetchApplications']);
// Route for user login
Route::post('loginapi', [Auth::class, 'login']);
Route::post('/login/user', [Auth::class, 'loginuser']);
Route::get('/verify-permit/{permit_no}', [PermitApplicationApi::class, 'verifyPermit']);
// Route::post('/verify-permit/retrieve', [PermitApplicationApi::class, 'permitRetrieval'])->name('verify.retrieval');
// Route::get('/verify-permit/download/{id}', [PermitApplicationApi::class, 'downloadCertificate'])->name('verify.download');
 Route::post('/generate-verification-link/{permitNo}', [PermitApplicationApi::class, 'generateLink']);


// Routes that require authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/applicant/{permit_no}', [PermitApplicationApi::class, 'fetchApplications']);
    Route::post('/logout',[Auth::class,'logout']);
});
