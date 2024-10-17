<?php

use App\Http\Controllers\Api\Auth;
use App\Http\Controllers\Api\PermitApplicationApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route to get authenticated user details
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route for user login
Route::post('/login', [Auth::class, 'login']);
Route::post('/login/user', [Auth::class, 'loginuser']);


// Routes that require authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/applicant/{permit_no}', [PermitApplicationApi::class, 'fetchApplications']);
    Route::post('/logout',[Auth::class,'logout']);
});
