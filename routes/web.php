<?php

use App\Http\Controllers\AdvanceSearchController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarberCosmetApplicationsController;
use App\Http\Controllers\BarberCosmetTestResultController;
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
use App\Http\Controllers\SummaryReportController;
use App\Http\Controllers\SwimmingPoolsApplicationController;
use App\Http\Controllers\TestDownloads;
use App\Http\Controllers\TrainingManualsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SwitchFacilityController;
use App\Http\Controllers\TouristEstApplicationsController;
use App\Http\Controllers\TouristEstTestResultController;
use App\Http\Middleware\printerAuthAttempt;
use App\Models\Downloads;
use App\Models\PermitTestResults;
use App\Models\SwimmingPoolsApplications;
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

Route::group(['middleware' => ['auth','prevent-back-history']], function () {

     //Dashboard Routes
     Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard.dashboard');
   //   Route::get('/dashboard', [Dashboard::class, 'fetchAppointments'])->name('dashboard.fetchappointments');

     //Permit Application Route
     Route::get("/permit/application", [PermitApplicationController::class, 'newApplication'])->name('food_handlers_permit.newApplication');
     Route::post("/permit/application", [PermitApplicationController::class, 'store'])->name('food_handlers_permit.store');
     Route::get("/permit/filter/{id}", [PermitApplicationController::class, 'index'])->name('permit.index');
     Route::post("/permit/filter", [PermitApplicationController::class, 'customFilterApplications'])->name('permit.index.custom');
     Route::get('/permit/view/{id}', [PermitApplicationController::class, 'viewApplication'])->name('permit.application.view');
     Route::put('/permit/application/update/{id}', [PermitApplicationController::class, 'editApplication'])->name('permit.application.update');
     Route::post('/permit/application/edit/appointment', [PermitApplicationController::class, 'editPermitAppointment'])->name('permit.application.edit.appointment');
     Route::get('/permit/application/edit/{id}', [PermitApplicationController::class, 'editView'])->name('permit.application.view.edit');
     Route::delete('/permit/application/delete/{id}', [PermitApplicationController::class, 'destroy'])->name('permit.application.delete');

     //Barber Cosmetics Routes
     Route::get('/barber-cosmet/create', [BarberCosmetApplicationsController::class, 'create'])->name('barber-cosmet.create');
     Route::post('/barber-cosmet/store', [BarberCosmetApplicationsController::class, 'store'])->name('barber-cosmet.store');
     Route::get('/barber-cosmet/filter/{id}', [BarberCosmetApplicationsController::class, 'index'])->name('barber-cosmet.index');
     Route::post('/barber-cosmet/filter', [BarberCosmetApplicationsController::class , 'customIndex'])->name('baber-cosmet.custom.filter');
     Route::get('/barber-cosmet/view/{id}', [BarberCosmetApplicationsController::class, 'show'])->name('barber-cosmet.view');

     //Edit Health Cert Applications
     Route::put('/barber-cosmet/edit/applicant', [BarberCosmetApplicationsController::class, 'editApplicant'])->name('barber-cosmet.edit.applicant');
     Route::put('/barber-cosmet/edit/employment', [BarberCosmetApplicationsController::class, 'editEmp'])->name('barber-cosmet.edit.employment');
     Route::put('/barber-cosmet/edit/appointment', [BarberCosmetApplicationsController::class, 'editAppointment'])->name('barber-cosmet.edit.appointments');
     Route::get('/barber-cosmet/edit/{id}', [BarberCosmetApplicationsController::class, 'edit'])->name('barber-cosmet.edit');

     //Tourist Establishment Route
     Route::get('/tourist-establishments/create', [TouristEstApplicationsController::class, 'create'])->name('tourist-establishments.create');
     Route::get('/tourist-establishments/filter/{id}', [TouristEstApplicationsController::class , 'index'])->name('tourist-establishments.index.filter');
     Route::post('/tourist-establishments/filter', [TouristEstApplicationsController::class , 'customIndex'])->name('tourist-establishments.index.custom.filter');
     Route::post('/tourist-establishments/store', [TouristEstApplicationsController::class , 'store'])->name('tourist-establishments.store');
     Route::get('/tourist-establishments/view/{id}', [TouristEstApplicationsController::class , 'view'])->name('tourist-establishments.view');
     Route::put('/tourist-establishments/update', [TouristEstApplicationsController::class , 'update'])->name('tourist-establishments.update');
     Route::get('/tourist-establishments/edit/{id}', [TouristEstApplicationsController::class, 'edit'])->name('tourist-establishments.edit');
     Route::get('/tourist-establishments/managers/create/{tourist_est_id}', [TouristEstApplicationsController::class, 'createManager'])->name('tourist-establishments.manager.create');
     Route::post('/tourist-establishments/managers/store', [TouristEstApplicationsController::class, 'storeManager'])->name('tourist-establishment.managers.store');
     Route::get('/tourist-establishments/managers/edit/{id}', [TouristEstApplicationsController::class, 'editManager'])->name('tourist-establishments.managers.edit');
     Route::put('/tourist-establishments/managers/update', [TouristEstApplicationsController::class, 'updateManager'])->name('tourist-establishments.manager.update');
     Route::put('/tourist-establishments/services/update', [TouristEstApplicationsController::class , 'updateService'])->name('tourist-establishments.services.update');
     Route::delete('/tourist-establishments/services/delete', [TouristEstApplicationsController::class, 'deleteService'])->name('tourist-establishments.services.delete');
     Route::delete('/tourist-establishments/managers/delete', [TouristEstApplicationsController::class, 'deleteManager'])->name('tourist-establishments.managers.delete');
     Route::post('/tourist-establishments/services/add', [TouristEstApplicationsController::class, 'storeService'])->name('tourist-establishments.services.add');

     //Advance Search 

     Route::get("/advance-search/create", [AdvanceSearchController::class, 'create'])->name('advance-search');
     Route::post("/advance-search/show", [AdvanceSearchController::class, 'show'])->name('advance.search.show');

     //Food Clinics Routes
     Route::get('/food-handlers-clinics/create', [FoodHandlersClinicController::class, 'create'])->name('food-handlers-clinic.create');
     Route::post('/food-handlers-clinics/create', [FoodHandlersClinicController::class, 'store'])->name('food-handlers-clinic.store');
     Route::get('/food-handlers-clinics/filter/{id}', [FoodHandlersClinicController::class, 'index'])->name('food-handlers-clinic.index');
     Route::post('/food-handlers-clinics/filter/custom', [FoodHandlersClinicController::class, 'customFilter'])->name('food-handlers-clinic.custom.filter');
     Route::get('/food-handlers-clinics/view/{id}', [FoodHandlersClinicController::class, 'show'])->name('food-handlers-clinics.view');
     Route::get('/food-handlers-clinics/edit/{id}', [FoodHandlersClinicController::class, 'edit'])->name('food-handlers-clinics.edit');
     Route::get('/food-handlers-clinics/permit/application/{clinic_app_id}', [ClinicPermitApplicationController::class, 'create'])->name('food-handlers-clinic.permit.application');
     Route::post('/food-handlers-clinics/update', [FoodHandlersClinicController::class, 'update'])->name('food-handlers-clinic.update');
     Route::get('/food-handlers-clinics/renewal/{id}', [FoodHandlersClinicController::class, 'renewal'])->name('food-handlers-clinic.renewal');
     Route::post('/food-handlers-clinics/renew', [FoodHandlersClinicController::class, 'renew'])->name('food-handlers-clinic.renew');

     //Swimming Pools Application
     Route::get('/swimming-pools/create', [SwimmingPoolsApplicationController::class, 'create'])->name('swimming-pools.create');
     Route::post('/swimming-pools/store', [SwimmingPoolsApplicationController::class, 'store'])->name('swimming-pools.store');
     Route::get('/swimming-pools/filter/{id}', [SwimmingPoolsApplicationController::class, 'index'])->name('swimming-pools.index.filter');
     Route::post('/swimming-pools/filter', [SwimmingPoolsApplicationController::class, 'customIndex'])->name('swimming-pools.custom.filter');
     Route::get('/swimming-pools/edit/{id}', [SwimmingPoolsApplicationController::class, 'edit'])->name('swimming-pools.edit');
     Route::put('/swimming-pools/update/{id}', [SwimmingPoolsApplicationController::class, 'update'])->name('swimming-pools.update');

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
     Route::get('/sign-off/food-establishments',[SignOffController::class, 'viewSignOffs'])->name('sign-off-establishments');

     //Test Results
     //Food Handlers Permit
     Route::get('/test-results/permits/{id}/create', [PermitTestResultsController::class, 'permitResults'])->name('test-results.permit');
     Route::post('/test-results/permits/create', [PermitTestResultsController::class, 'addPermitResults'])->name('test-results.permit.add');
     Route::get('/test-results/permit/filter/{id}', [PermitTestResultsController::class, 'index'])->name('test-results.permit.index');
     Route::post('/test-results/permit/filter', [PermitTestResultsController::class, 'customFilterProcessedResults'])->name('test-results.permit.filter.custom');
     Route::get('/test-results/permit/outstanding/filter/{id}', [PermitTestResultsController::class , 'outstanding'])->name('test-results.permit.outstanding');
     Route::post('/test-results/permit/outstanding/filter', [PermitTestResultsController::class, 'outstandingCustom'])->name('test-results.permit.outstanding.custom');

     //Food Establishment Results
     Route::get('/test-results/food-establishments/filter/{id}', [FoodEstTestResultController::class, 'index'])->name('test-results.food-est.index');
     Route::post('/test-results/food-establishments/filter', [FoodEstTestResultController::class, 'customIndex'])->name('test-results.food-est.custom.filter');
     Route::get('/test-results/food-establishments/create/{id}', [FoodEstTestResultController::class, 'create'])->name('test-results.food-est.create');
     Route::get('/test-results/food-establishments/outstanding/filter/{id}', [FoodEstTestResultController::class, 'outstanding'])->name('test-results.food-est.outstanding');
     Route::post('/test-results/food-establishments/outstanding', [FoodEstTestResultController::class, 'outstandingCustom'])->name('test-results.food-est.outstanding.custom.filter');
     Route::post('/test-results/food-establishments', [FoodEstTestResultController::class, 'store'])->name('test-results.food-est.store');
     Route::get('/test-results/food-establishments/edit/{id}', [FoodEstTestResultController::class, 'edit'])->name('test-results.food-est.edit');
     Route::post('/test-results/food-establishments/update', [FoodEstTestResultController::class, 'update'])->name('test-results.food-est.update');

     //Baber/Cosmet Results
     Route::get('/test-results/barber-cosmet/filter/{id}', [BarberCosmetTestResultController::class, 'index'])->name('test-results.barber-cosmet.processed');
     Route::post('/test-results/barber-cosmet/filter', [BarberCosmetTestResultController::class , 'customIndex'])->name('test-results.barber-cosmet.processed.custom');
     Route::get('/test-results/barber-cosmet/outstanding/filter/{id}', [BarberCosmetTestResultController::class, 'outstanding'])->name('test-results.barber-cosmet.outstanding');
     Route::post('/test-results/barber-cosmet/outstanding/filter', [BarberCosmetTestResultController::class, 'customOutstanding'])->name('test-results.barber-cosmet.custom.outstanding');
     Route::get('/test-results/barber-cosmet/edit/{id}', [BarberCosmetTestResultController::class, 'edit'])->name('test-results.barber-cosmet.edit');
     Route::put('/test-results/barber-cosmet/update/{id}', [BarberCosmetTestResultController::class, 'update'])->name('test-results.barber-cosmet.update');
     Route::get('/test-results/barber-cosmet/create/{id}', [BarberCosmetTestResultController::class, 'create'])->name('test-results.barber-cosmet.create');
     Route::post('/test-results/barber-cosmet/store/{id}', [BarberCosmetTestResultController::class, 'store'])->name('test-results.barber-cosmet.store');

     //Tourist Establishment Results
     Route::get('/test-results/tourist-establishments/filter/{id}', [TouristEstTestResultController::class, 'index'])->name('test-results.tourist-establishments.index.filter');
     Route::post('/test-results/tourist-establishments/filter', [TouristEstTestResultController::class, 'customIndex'])->name('test-results.tourist-establishments.custom.filter');
     Route::get('/test-results/tourist-establishments/create/{id}', [TouristEstTestResultController::class , 'create'])->name('test-results.tourist-establishments.create');
     Route::post('/test-results/tourist-establishments/store/{id}', [TouristEstTestResultController::class, 'store'])->name('test-results.tourist-establishments.store');
     Route::get('/test-results/tourist-establishments/edit/{id}', [TouristEstTestResultController::class , 'edit'])->name('test-results.tourist-establishments.edit');
     Route::put('/test-results/tourist-establishments/update/{id}', [TouristEstTestResultController::class , 'update'])->name('test-results.tourist-establishments.update');
     Route::get('/test-results/tourist-establishments/outstanding/filter/{id}', [TouristEstTestResultController::class, 'outstanding'])->name('test-results.tourist-establishments.outstanding.filter');
     Route::post('/test-results/tourist-establishments/outstanding/filter', [TouristEstTestResultController::class, 'outstandingCustom'])->name('test-results.test-establishments.outstanding.custom');

     //Report
     Route::get('/report/payment', [PaymentReportController::class, 'index'])->name('reports.payment.index');
     Route::post('/report/payment', [PaymentReportController::class, 'showReport'])->name('reports.payment.show');

         //Summary Report
      Route::get('/report/summary-report', [SummaryReportController::class, 'create'])->name('reports.summary.index');
      Route::post('/report/summary-report/show', [SummaryReportController::class, 'show'])->name('report.summary.show');

     //Renewals
     Route::get('/permit/application/renewal/{id}', [PermitApplicationController::class, 'renewal'])->name('food_handlers_permit.renewal');
     Route::post('/permit/application/renewal', [PermitApplicationController::class, 'storeRenewal'])->name('food_handlers_permit.renew');
     Route::get('/food-establishments/renewal/{id}', [FoodEstablishmentController::class, 'renewal'])->name('food-establishment.renewal');
     Route::post('/food-establishments/renewal', [FoodEstablishmentController::class, 'storeRenewal'])->name('food-establishment.renew');
     Route::get('/barber-cosmet/renewal/{id}', [BarberCosmetApplicationsController::class, 'renewal'])->name('barber-cosmet.application.renewal');
     Route::post('/barber-cosmet/renew/{id}', [BarberCosmetApplicationsController::class , 'renew'])->name('barber-cosmet.application.renew');
     Route::get('/tourist-establishments/renewal/{id}', [TouristEstApplicationsController::class, 'renewal'])->name('tourist-establishments.renewal');
     Route::post('/tourist-establishments/renew/{id}', [TouristEstApplicationsController::class, 'renew'])->name('tourist-establishments.renew');
     Route::get('/swimming-pools/renewal/{id}', [SwimmingPoolsApplicationController::class, 'renewal'])->name('swimming-pools.renewal');
     Route::post('/swimming-pools/renew/{id}', [SwimmingPoolsApplicationController::class, 'renew'])->name('swimming-pools.renew');
     Route::get('/food-handlers-clinics/renewal/{id}', [FoodHandlersClinicController::class, 'renewal'])->name('food-handlers-clinics.renewal');
     Route::post('/food-handlers-clinics/renew/{id}', [FoodHandlersClinicController::class, 'renew'])->name('food-handlers-clinics.renew');


     Route::get('/settings/users', [UserController::class, 'index'])->name('users');
     Route::get('/settings/users/reset-password/{id}', [UserController::class, 'resetpassword']);
     Route::get('/settings/users/restore/{id}', [UserController::class, 'restore']);
     Route::get('/change-password', [UserController::class, 'changepasswordMe'])->name('user.changepassword');
     Route::post('/password-change', [UserController::class, 'store']);
     Route::get('/settings/user/create', [UserController::class, 'createuser'])->name('user.create');
     Route::post('/settings/user/add', [UserController::class, 'addUser'])->name('users.add');
     Route::get('/settings/users/online', [UserController::class, 'onlineUsers'])->name('users.online');
     Route::get('/settings/users/loginusers', [UserController::class, 'loginUsersLocations']);
     Route::put('/settings/users/loginusers', [UserController::class, 'loginUsersLocations']);
     Route::get('/settings/user/edit/{id}',[UserController::class, 'viewEditForm'])->name('users.edit');
     Route::post('/settings/user/update/{id}',[UserController::class, 'editUser'])->name('users.update');
     Route::put('/settings/user/deactivate/{id}',[UserController::class,'destroy'])->name('users.destroy');
     

     //Health Interview Routes
     Route::get("/health-interview/create/{app_type_id}/{app_id}", [HealthInterviewController::class, 'create'])->name('health-interview.create');
     Route::post('/health-interview/store', [HealthInterviewController::class, 'store'])->name('health-interview.store');
     Route::get('/health-interview/filter/{id}', [HealthInterviewController::class, 'index'])->name('health-interview.index');
     Route::get("/health-interview/outstanding/filter/{app_type_id}/{filter_id}", [HealthInterviewController::class, 'outstandingApplications'])->name('health-interview.outstanding');
     Route::post("/health-interview/filter", [HealthInterviewController::class, 'customFilterIndex'])->name('health-interview.processed.custom');
     Route::post("/health-interview/outstanding/", [HealthInterviewController::class, 'customFilterOutstanding'])->name('health-interview.outstanding.custom');

   
     //Food Establishments Route
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
     Route::get('/manual-run/food-est-job', [TestDownloads::class, 'writeAllFoodEstablishments']);
     Route::get('/test/tourist-establishments', [TestDownloads::class, 'testTourist']);

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

   //Training Manual Page
     Route::get('/training-manuals',[TrainingManualsController::class,'index'])->name("training.manuals");

     //Switch Location 
      Route::get('/switch-location',[SwitchFacilityController::class, 'index'])->name('switch.location');
      Route::post('/switch-location',[SwitchFacilityController::class,'update'])->name('switch.update');


     //Logout Routes
     Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


 

     
});
