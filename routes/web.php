<?php

use App\Http\Controllers\AdvanceSearchController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClinicPermitApplicationController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\DownloadsController;
use App\Http\Controllers\FoodEstablishmentController;
use App\Http\Controllers\FoodEstTestResultController;
use App\Http\Controllers\FoodHandlersClinicController;
use App\Http\Controllers\HealthInterviewController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentReportController;
use App\Http\Controllers\PermitApplicationController;
use App\Http\Controllers\PermitTestResultsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SignOffController;
use App\Http\Controllers\TestDownloads;
use App\Http\Controllers\UserController;
use App\Http\Middleware\printerAuthAttempt;
use App\Models\Downloads;
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

Route::get('/forget-password', [UserController::class, 'forgetPasswordPage'])->name('forget-password');
Route::post('/forget-password', [UserController::class, 'forgetpassword'])->name('forget.password');
Route::get('/reset/{token}', [UserController::class, 'reset']);
Route::post('/reset/{token}', [UserController::class, 'post_reset']);

Route::group(['middleware' => ['auth']], function () {

     //Dashboard Routes
     Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard.dashboard');

     //Permit Application Route
     Route::get("/permit/application", [PermitApplicationController::class, 'newApplication'])->name('food_handlers_permit.newApplication');
     Route::post("/permit/application", [PermitApplicationController::class, 'store'])->name('food_handlers_permit.store');
     Route::get("/permit/filter/{id}", [PermitApplicationController::class, 'index'])->name('permit.index');
     Route::post("/permit/filter", [PermitApplicationController::class, 'customFilterApplications'])->name('permit.index.custom');
     Route::get('/permit/view/{id}', [PermitApplicationController::class, 'viewApplication'])->name('permit.application.view');
     Route::post('/permit/application/edit', [PermitApplicationController::class, 'editApplication'])->name('permit.application.edit');
     Route::post('/permit/application/edit/appointment', [PermitApplicationController::class, 'editPermitAppointment'])->name('permit.application.edit.appointment');

     //Advance Search 

     Route::get("/advance-search/create", [AdvanceSearchController::class, 'create'])->name('advance-search');
     Route::post("/advance-search/show", [AdvanceSearchController::class, 'show']);

     //Food Clinics Routes
     Route::get('/food-handlers-clinics/create', [FoodHandlersClinicController::class, 'create'])->name('food-handlers-clinic.create');
     Route::post('/food-handlers-clinics/create', [FoodHandlersClinicController::class, 'store'])->name('food-handlers-clinic.store');
     Route::get('/food-handlers-clinics/filter/{id}', [FoodHandlersClinicController::class, 'index'])->name('food-handlers-clinic.index');
     Route::post('/food-handlers-clinics/filter/custom', [FoodHandlersClinicController::class, 'customFilter'])->name('food-handlers-clinic.custom.filter');
     Route::get('/food-handlers-clinics/view/{id}', [FoodHandlersClinicController::class, 'show'])->name('food-handlers-clinics.view');
     Route::get('/food-handlers-clinics/edit/{id}', [FoodHandlersClinicController::class, 'edit'])->name('food-handlers-clinics.edit');
     Route::get('/food-handlers-clinics/permit/application/{clinic_app_id}', [ClinicPermitApplicationController::class, 'create'])->name('food-handlers-clinic.permit.application');
     Route::post('/food-handlers-clinics/update', [FoodHandlersClinicController::class, 'update'])->name('food-handlers-clinic.update');

     //Payment Routes
     Route::get("/payments/create", [PaymentController::class, 'create'])->name('payments.create');
     Route::post("/payments/create", [PaymentController::class, 'registerNewPayment'])->name('payments.create.store');
     Route::get("/payments/search/{app_id}/{price_id}/", [PaymentController::class, 'searchApplication'])->name('payments.search');
     Route::get("/payments", [PaymentController::class, 'index'])->name('payments.index');
     Route::get("/payments/create/{app_id}/{app_t_id}", [PaymentController::class, 'createFromApplication'])->name('payments.create.application');
     Route::get("/payments/applications/filter/{id}", [PaymentController::class, 'filterOutstandingPayments'])->name('payment.application.filter');
     Route::post("/payments/applications/filter", [PaymentController::class, 'customFilterOutstandingPayments'])->name('payments.applications.filter.custom');
     Route::get("/payments/receipt/print/{id}", [PaymentController::class, 'printReceipt'])->name('payment.receipt.print');
     Route::get("/payments/index/filter/{id}", [PaymentController::class, 'filterProcessedPayments'])->name('payments.index.filter');
     Route::post("/payments/index/filter", [PaymentController::class, 'customFilterProcessedPayments'])->name('payments.index.filter.custom');
     Route::get("/payments/cancellations", [PaymentController::class, 'outstandingCancellations'])->name('payments.cancellation.outstanding');
     Route::post("/payments/cancellations/request", [PaymentController::class, 'requestCancelPayment'])->name('payments.cancellations.request');
     Route::post("/payments/cancellations/approve", [PaymentController::class, 'approveCancelPaymentRequest'])->name('payments.cancellations.approve');

     //Sign off Routes
     Route::get('/sign-off', [SignOffController::class, 'index'])->name('sign-off');
     Route::get('/sign-off/create/{id}', [SignOffController::class, 'create'])->name('sign-off.create');
     Route::post('/sign-off/show-applications/{id}', [SignOffController::class, 'fetchApplications'])->name('show-applications');
     Route::post('/sign-off/approve', [SignOffController::class, 'approve'])->name('sign-off.approve');

     //Test Results
     Route::get('/test-center/test-results/permits/{id}/create', [PermitTestResultsController::class, 'permitResults'])->name('test-results.permit');
     Route::post('/test-center/test-results/permits/create', [PermitTestResultsController::class, 'addPermitResults'])->name('test-results.permit.add');
     Route::get('/test-center/test-results/permit/filter/{id}', [PermitTestResultsController::class, 'index'])->name('test-results.permit.index');
     Route::post('/test-center/test-results/permit/filter', [PermitTestResultsController::class, 'customFilterProcessedResults'])->name('test-results.permit.filter.custom');

     Route::get('/test-center/test-results/food-establishments', [FoodEstTestResultController::class, 'index'])->name('test-results.food-est.index');
     Route::get('/test-center/test-results/food-establishments/create/{id}', [FoodEstTestResultController::class, 'create'])->name('test-results.food-est.create');
     Route::get('/test-center/test-results/food-establishments/outstanding', [FoodEstTestResultController::class, 'outstanding'])->name('test-results.food-est.outstanding');

     //Report
     Route::get('/report/payment', [PaymentReportController::class, 'index'])->name('reports.payment.index');
     Route::post('/report/payment', [PaymentReportController::class, 'showReport'])->name('reports.payment.show');

     //Renewals
     Route::get('/permit/application/renewal/{id}', [PermitApplicationController::class, 'renewal'])->name('food_handlers_permit.renewal');
     Route::post('/permit/application/renewal', [PermitApplicationController::class, 'storeRenewal'])->name('food_handlers_permit.renew');
     Route::get('/food-establishments/renewal/{id}', [FoodEstablishmentController::class, 'renewal'])->name('food-establishment.renewal');
     Route::post('/food-establishments/renewal', [FoodEstablishmentController::class, 'storeRenewal'])->name('food-establishment.renew');


     Route::get('/settings/users', [UserController::class, 'index'])->name('users');
     Route::get('/settings/users/reset-password/{id}',[UserController::class,'resetpassword']);
     Route::get('/settings/users/restore/{id}',[UserController::class,'restore']);
     Route::get('/change-password',[UserController::class, 'changepasswordMe']);
     Route::post('/password-change',[UserController::class, 'store']);
     Route::get('/settings/user/create',[UserController::class, 'createuser'])->name('user.create');
     Route::post('/settings/user/add',[UserController::class, 'addUser'])->name('users.add');
     Route::get('/settings/users/online',[UserController::class, 'onlineUsers'])->name('users.online');
     Route::get('/settings/users/loginusers',[UserController::class, 'loginUsersLocations']);
     Route::put('/settings/users/loginusers',[UserController::class, 'loginUsersLocations']);

     //Health Interview Routes
     Route::get("/health-interview/create/{app_type_id}/{app_id}", [HealthInterviewController::class, 'create'])->name('health-interview.create');
     Route::post('/health-interview/store', [HealthInterviewController::class, 'store'])->name('health-interview.store');
     Route::get('/health-interview/filter/{id}', [HealthInterviewController::class, 'index'])->name('health-interview.index');
     Route::get("/health-interview/outstanding/filter/{app_type_id}/{filter_id}", [HealthInterviewController::class, 'outstandingApplications'])->name('health-interview.outstanding');
     Route::post("/health-interview/filter", [HealthInterviewController::class, 'customFilterIndex'])->name('health-interview.processed.custom');
     Route::post("/health-interview/outstanding/", [HealthInterviewController::class, 'customFilterOutstanding'])->name('health-interview.outstanding.custom');

     //Establishments Routes

     //     Route::post("/food-establishment/{id}/edit", 'FoodEstablishmentController@edit');
     //     Route::resource('/food-establishment', 'FoodEstablishmentController');
     //     Route::get("/food-establishment/renew/{id}", "FoodEstablishmentController@renew");
     //     Route::post("/food-establishment/renew/{id}", "FoodEstablishmentController@storeRenewal");

     Route::get('/food-establishments/filter/{id}', [FoodEstablishmentController::class, 'index'])->name('food-establishment.filter');
     Route::post('/food-establishments/filter', [FoodEstablishmentController::class, 'indexCustom'])->name('food-establishment.filter.custom');
     Route::get('/food-establishments/create', [FoodEstablishmentController::class, 'create'])->name('food-establishment.create');
     Route::post('/food-establishments/create', [FoodEstablishmentController::class, 'store'])->name('food-establishment.create.store');
     Route::get('/food-establishments/view/{id}', [FoodEstablishmentController::class, 'view'])->name('food-establishment.view');
     Route::post('/food-establishments/edit', [FoodEstablishmentController::class, 'edit'])->name('food-establishment.edit');
     Route::post('/food-establishments/edit/operators', [FoodEstablishmentController::class, 'editOperators'])->name('food-establishment.edit.operators');
     Route::post('/food-establishments/delete/operators', [FoodEstablishmentController::class, 'deleteOperator'])->name('food-establishment.delete.operators');
     Route::get('/food-establishments/edit/{id}', [FoodEstablishmentController::class, 'getEdit']);

     //Test Exports
     Route::get('/test/downloads', [TestDownloads::class, 'index']);

     //Download routes
     Route::post('/downloads/foodhandlers', [DownloadsController::class, 'customFilterFHand'])->name('downloads.foodhandlers.custom');
     Route::get('/downloads/foodhandlers/filter/{id}', [DownloadsController::class, 'food_handlers'])->name('downloads.foodhandlers.filter');
     Route::get('/downloads/food-establishments/filter/{id}', [DownloadsController::class, 'food_est'])->name('downloads.food_est.index');
     Route::post('/downloads/food-establishments/filter', [DownloadsController::class, 'customFilterFoodEst'])->name('downloads.food_est.custom');
     Route::get('/downloads/tourist-establishments/filter/{id}', [DownloadsController::class, 'tourist_est'])->name('downloads.tourist_est');
     Route::post('downloads/tourist-establishments/filter', [DownloadsController::class, 'customFilterTourEst'])->name('downloads.tourist_est.custom');
     Route::post('/downloads/package', [DownloadsController::class, 'downloadZip'])->middleware(printerAuthAttempt::class);
     Route::delete('/downloads/deleteAll', [DownloadsController::class, 'deleteAll'])->name('downloads.delete.multiple');
     Route::delete('/downloads/delete/{id}/{app_type}', [DownloadsController::class, 'destroyPrintable'])->name('downloads.delete.printable.applications');
     Route::delete('/downloads/{id}', [DownloadsController::class, 'destroy'])->name('downloads.delete.single');

     // Route::get('/food-establishments/view',[FoodEstablishmentController::class,'view']);
     // Route::get('food-establishments',[FoodEstablishmentController::class, 'showApplications']);

     //Reports 

     Route::get('/reports/general-report', [ReportController::class, 'index']);
     Route::post('/reports/general-report', [ReportController::class, 'generalReport'])->name('reports.general');

     //Test Centre Routes

     //Route::get("/test-centre/test-results/food-establishments",[FoodEstResultController::class,'index']);

     //Switch Locations




     //Logout Routes
     Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
