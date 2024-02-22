<?php

use App\Http\Controllers\AdvanceSearchController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\FoodEstablishmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentReportController;
use App\Http\Controllers\PermitApplicationController;
use App\Http\Controllers\PermitTestResultsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SignOffController;
use App\Http\Controllers\UserController;
use App\Models\PermitTestResults;
use Illuminate\Support\Facades\Route;







/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

//Users routes for users not logged in

Route::get('/forget-password',[UserController::class, 'forgetPasswordPage'])->name('forget-password');
Route::post('/forget-password',[UserController::class, 'forgetpassword'])->name('forget.password');
Route::get('/reset/{token}',[UserController::class,'reset']);
Route::post('/reset/{token}',[UserController::class,'post_reset']);

Route::group(['middleware' => ['auth']], function () {

     //Dashboard Routes
     Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard.dashboard');

     //Application Route
     Route::get("/permit/application", [PermitApplicationController::class, 'newApplication'])->name('food_handlers_permit.newApplication');
     Route::post("/permit/application", [PermitApplicationController::class, 'store'])->name('food_handlers_permit.store');
     Route::get("/permit/filter/{id}", [PermitApplicationController::class, 'index'])->name('permit.index');
     Route::post("/permit/filter", [PermitApplicationController::class, 'customFilterApplications'])->name('permit.index.custom');
     Route::get('/permit/view/{id}', [PermitApplicationController::class, 'viewApplication'])->name('permit.application.view');
     Route::post('/permit/application/edit', [PermitApplicationController::class, 'editApplication'])->name('permit.application.edit');

     //Advance Search 

     Route::get("/advance-search/create", [AdvanceSearchController::class, 'create'])->name('advance-search');
     Route::post("/advance-search/show", [AdvanceSearchController::class, 'show']);

     //Payment Routes
     Route::get("/payments/create", [PaymentController::class, 'create'])->name('payments.create');
     Route::post("/payments/create", [PaymentController::class, 'registerNewPayment'])->name('payments.create.store');
     Route::get("/payments/search/{app_id}/{app_t_id}/", [PaymentController::class, 'searchApplication'])->name('payments.search');
     Route::get("/payments", [PaymentController::class, 'index'])->name('payments.index');
     Route::get("/payments/create/{app_id}/{app_t_id}", [PaymentController::class, 'createFromApplication'])->name('payments.create.application');
     Route::get("/payments/applications/filter/{id}", [PaymentController::class, 'filterOutstandingPayments'])->name('payment.application.filter');
     Route::post("/payments/applications/filter", [PaymentController::class, 'customFilterOutstandingPayments'])->name('payments.applications.filter.custom');
     Route::get("/payments/receipt/print/{id}", [PaymentController::class, 'printReceipt'])->name('payment.receipt.print');
     Route::get("/payments/index/filter/{id}", [PaymentController::class, 'filterProcessedPayments'])->name('payments.index.filter');
     Route::post("/payments/index/filter", [PaymentController::class, 'customFilterProcessedPayments'])->name('payments.index.filter.custom');
     Route::get("/payments/cancel/{id}",[PaymentController::class, 'destroy']);

     //Sign off Routes
     Route::get('/sign-off', [SignOffController::class, 'index'])->name('sign-off');
     Route::get('/sign-off/create/{id}', [SignOffController::class, 'create'])->name('sign-off.create');
     Route::post('/sign-off/show-applications/{id}', [SignOffController::class, 'fetchApplications'])->name('show-applications');
     
     //Test Results
     Route::get('/test-center/test-results/permits/{id}/create', [PermitTestResultsController::class, 'permitResults'])->name('test-results.permit');
     Route::post('/test-center/test-results/permits/create', [PermitTestResultsController::class, 'addPermitResults'])->name('test-results.permit.add');
     Route::get('/test-center/test-results/permit/filter/{id}', [PermitTestResultsController::class, 'index'])->name('test-results.permit.index');
     Route::post('/test-center/test-results/permit/filter', [PermitTestResultsController::class, 'customFilterProcessedResults'])->name('test-results.permit.filter.custom');

     //Report
     Route::get('/report/payment', [PaymentReportController::class, 'index'])->name('reports.payment.index');
     Route::post('/report/payment', [PaymentReportController::class, 'showReport'])->name('reports.payment.show');

     //User Routes

     Route::get('/settings/users', [UserController::class, 'index'])->name('users');
     Route::get('/settings/users/reset-password/{id}',[UserController::class,'resetpassword']);
     Route::get('/settings/users/restore/{id}',[UserController::class,'restore']);

    
    

     //Establishments Routes

     //     Route::post("/food-establishment/{id}/edit", 'FoodEstablishmentController@edit');
     //     Route::resource('/food-establishment', 'FoodEstablishmentController');
     //     Route::get("/food-establishment/renew/{id}", "FoodEstablishmentController@renew");
     //     Route::post("/food-establishment/renew/{id}", "FoodEstablishmentController@storeRenewal");

     Route::get('/food-establishments',[FoodEstablishmentController::class,'index']);
     // Route::get('/food-establishments/view',[FoodEstablishmentController::class,'view']);
     // Route::get('food-establishments',[FoodEstablishmentController::class, 'showApplications']);

     //Reports 

     // Route::get('/reports/general-report',[ReportController::class,'index']);

     //Test Centre Routes

     //Route::get("/test-centre/test-results/food-establishments",[FoodEstResultController::class,'index']);



     //Logout Routes
     Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
