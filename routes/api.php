<?php

use App\Http\Controllers\Api_ForgotPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api_DashboardController;
use App\Http\Controllers\Api_LoginController;
use App\Http\Controllers\Api_ComplaintAppointmentController;
use App\Http\Controllers\Api_ComplaintMonitoringController;
use App\Http\Controllers\Api_ComplaintDamageController;
use App\Http\Controllers\Api_ComplaintViolationController;
use App\Http\Controllers\Api_ComplaintController;
use App\Http\Controllers\Api_RoutineInspectionController;
use App\Http\Controllers\Api_UserController;
use App\Http\Controllers\Api_PanicController;
use App\Http\Controllers\Api_QuartersLeaveController;
use App\Http\Controllers\Api_JohorPayController;
use App\Http\Controllers\Api_PaymentNoticeController;
use App\Http\Controllers\Api_PaymentRecordController;
use App\Http\Controllers\Api_MaintenanceTransactionController;
use App\Http\Controllers\Api_TenantLeaveController;
use App\Http\Controllers\Api_IntegrasiIspeksIncomingController;
use App\Http\Controllers\Api_IntegrasiIspeksOutgoingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('loginAppsInspector', [Api_LoginController::class, 'authForInspection'])->name('auth.loginAppsInspector'); // Apps Penguatkuasa
Route::post('loginAppsTenants', [Api_LoginController::class, 'authForTenants'])->name('auth.loginAppsTenants'); // Apps Penghuni
Route::post('forgotPassword', [Api_ForgotPasswordController::class, 'forgotPassword']);

// DASHBOARD
Route::middleware('auth:sanctum')->get('dashboardInspector', [Api_DashboardController::class, 'dashboardInspector']);
Route::middleware('auth:sanctum')->get('dashboardTenants', [Api_DashboardController::class, 'dashboardTenants']);

// --------------------------------------------------------------------------------------------------------
// APPS PENGUATKUASA : PEMANTAUAN ADUAN
// --------------------------------------------------------------------------------------------------------
// count
Route::middleware('auth:sanctum')->get('countComplaintDamageInspectionCompletedByUserId', [Api_ComplaintMonitoringController::class, 'countComplaintDamageInspectionCompletedByUserId']);
Route::middleware('auth:sanctum')->get('countComplaintViolationInspectionCompletedByUserId', [Api_ComplaintMonitoringController::class, 'countComplaintViolationInspectionCompletedByUserId']);

Route::middleware('auth:sanctum')->get('countComplaintDamageInspectionPendingByUserId', [Api_ComplaintMonitoringController::class, 'countComplaintDamageInspectionPendingByUserId']);
Route::middleware('auth:sanctum')->get('countComplaintViolationInspectionPendingByUserId', [Api_ComplaintMonitoringController::class, 'countComplaintViolationInspectionPendingByUserId']);

// Pemantauan > Aduan Baru List (Apps Penguatkuasa) match
Route::middleware('auth:sanctum')->get('getComplaintDamageInspectionPendingMenuByUserId', [Api_ComplaintMonitoringController::class, 'getComplaintDamageInspectionPendingMenuByUserId']);
Route::middleware('auth:sanctum')->get('getComplaintViolationInspectionPendingMenuByUserId', [Api_ComplaintMonitoringController::class, 'getComplaintViolationInspectionPendingMenuByUserId']);

// Pemantauan > Aduan Ditolak (Apps Penguatkuasa) match
Route::middleware('auth:sanctum')->get('getComplaintDamageInspectionRejectedList', [Api_ComplaintMonitoringController::class, 'getComplaintDamageInspectionRejectedList']);
Route::middleware('auth:sanctum')->get('getComplaintViolationInspectionRejectedList', [Api_ComplaintMonitoringController::class, 'getComplaintViolationInspectionRejectedList']);

// Pemantauan > Aduan Selesai List (Apps Penguatkuasa) match
Route::middleware('auth:sanctum')->get('getComplaintDamageInspectionCompletedMenuByUserId', [Api_ComplaintMonitoringController::class, 'getComplaintDamageInspectionCompletedMenuByUserId']);
Route::middleware('auth:sanctum')->get('getComplaintViolationInspectionCompletedMenuByUserId', [Api_ComplaintMonitoringController::class, 'getComplaintViolationInspectionCompletedMenuByUserId']);

// Pemantauan > Aduan Berulang List (Apps Penguatkuasa) match
Route::middleware('auth:sanctum')->get('getComplaintViolationInspectionActiveMenuByUserId', [Api_ComplaintMonitoringController::class, 'getComplaintViolationInspectionActiveMenuByUserId']);
Route::middleware('auth:sanctum')->get('getComplaintViolationInspectionActiveById', [Api_ComplaintMonitoringController::class, 'getComplaintViolationInspectionActiveById']);
Route::middleware('auth:sanctum')->get('updateComplaintInspectionActive', [Api_ComplaintMonitoringController::class, 'updateComplaintInspectionActive']);

// Pemantauan > Aduan Selenggara (Apps Penguatkuasa)
Route::middleware('auth:sanctum')->get('getComplaintDamageInspectionMaintenanceList', [Api_ComplaintMonitoringController::class, 'getComplaintDamageInspectionMaintenanceList']);

//GetAppointmentList ( notidone)
Route::middleware('auth:sanctum')->get('getComplaintAppointmentList', [Api_ComplaintDamageController::class, 'getComplaintAppointmentList']);
Route::middleware('auth:sanctum')->post('submitComplaintAppointment', [Api_ComplaintDamageController::class, 'submitComplaintAppointment']);
Route::middleware('auth:sanctum')->get('getComplaintAppointmentById', [Api_ComplaintDamageController::class, 'getComplaintAppointmentById']);

//Appointment Cancel
Route::middleware('auth:sanctum')->post('cancelAppointmentByOfficer', [Api_ComplaintAppointmentController::class, 'cancelAppointmentByOfficer']);

// SubmitComplaintInspection (noti ok)
Route::middleware('auth:sanctum')->post('submitComplaintDamageInspection', [Api_ComplaintMonitoringController::class, 'submitComplaintDamageInspection']);
Route::middleware('auth:sanctum')->post('submitComplaintViolationInspection', [Api_ComplaintMonitoringController::class, 'submitComplaintViolationInspection']);

// Submit Aduan Berulang
Route::middleware('auth:sanctum')->post('submitRepeatedComplaintViolationInspection', [Api_ComplaintMonitoringController::class, 'submitRepeatedComplaintViolationInspection']);

// BY ID ---- KIV
// Pemantauan > Aduan Selesai (Apps Penguatkuasa)
Route::middleware('auth:sanctum')->get('getComplaintDamageInspectionCompletedById', [Api_ComplaintMonitoringController::class, 'getComplaintDamageInspectionCompletedById']);
Route::middleware('auth:sanctum')->get('getComplaintViolationInspectionCompletedById', [Api_ComplaintMonitoringController::class, 'getComplaintViolationInspectionCompletedById']);

// Pemantauan > Aduan Baru (Apps Penguatkuasa)
Route::middleware('auth:sanctum')->get('getComplaintDamageInspectionPendingById', [Api_ComplaintMonitoringController::class, 'getComplaintDamageInspectionPendingById']);
Route::middleware('auth:sanctum')->get('getComplaintViolationInspectionPendingById', [Api_ComplaintMonitoringController::class, 'getComplaintViolationInspectionPendingById']);

// Pemantauan > Aduan Ditolak
Route::middleware('auth:sanctum')->get('getComplaintDamageInspectionRejectedById', [Api_ComplaintMonitoringController::class, 'getComplaintDamageInspectionRejectedById']);
Route::middleware('auth:sanctum')->get('getComplaintViolationInspectionRejectedById', [Api_ComplaintMonitoringController::class, 'getComplaintViolationInspectionRejectedById']);

// Pemantauan > Aduan Diselenggara (Apps Penguatkuasa)
Route::middleware('auth:sanctum')->get('getComplaintDamageInspectionMaintenanceById', [Api_ComplaintMonitoringController::class, 'getComplaintDamageInspectionMaintenanceById']);

// Get quarters inventory based on user
Route::middleware('auth:sanctum')->get('getInventoryByUser', [Api_ComplaintDamageController::class, 'getInventoryByUser']);

// --------------------------------------------------------------------------------------------------------
// APPS PENGHUNI : PENYELENGGARAAN > TRANSAKSI PENYELENGGARAAN
// --------------------------------------------------------------------------------------------------------
// Penyelenggaraan > Transaksi Penyelenggaraan
// Tab Untuk Tindakan
Route::middleware('auth:sanctum')->get('getMaintenanceTransactionList', [Api_MaintenanceTransactionController::class, 'getMaintenanceTransactionList']);
Route::middleware('auth:sanctum')->get('getMaintenanceTransactionById', [Api_MaintenanceTransactionController::class, 'getMaintenanceTransactionById']);
Route::middleware('auth:sanctum')->post('submitMaintenanceTransaction', [Api_MaintenanceTransactionController::class, 'submitMaintenanceTransaction']);
// Tab Senarai Terdahulu
Route::middleware('auth:sanctum')->get('getMaintenanceTransactionHistoryList', [Api_MaintenanceTransactionController::class, 'getMaintenanceTransactionHistoryList']);
Route::middleware('auth:sanctum')->get('getMaintenanceTransactionHistoryById', [Api_MaintenanceTransactionController::class, 'getMaintenanceTransactionHistoryById']);

// --------------------------------------------------------------------------------------------------------
// APPS PENGHUNI : ADUAN
// --------------------------------------------------------------------------------------------------------

////1.1 GetPendingComplaintDetailsById
// Route::middleware('auth:sanctum')->get('getPendingComplaintDamageDetailsById', [Api_ComplaintDamageController::class, 'getPendingComplaintDamageDetailsById']);
// Route::middleware('auth:sanctum')->get('getPendingComplaintViolationDetailsById', [Api_ComplaintViolationController::class, 'getPendingComplaintViolationDetailsById']);

// //1.2 GetActiveComplainttDetailsById
// Route::middleware('auth:sanctum')->get('getActiveComplaintDamageDetailsById', [Api_ComplaintDamageController::class, 'getActiveComplaintDamageDetailsById']);
// Route::middleware('auth:sanctum')->get('getActiveComplaintViolationDetailsById', [Api_ComplaintViolationController::class, 'getActiveComplaintViolationDetailsById']);

// //1.3 GetCompletedComplaintDetailsById
// Route::middleware('auth:sanctum')->get('getCompletedComplaintDamageDetailsById', [Api_ComplaintDamageController::class, 'getCompletedComplaintDamageDetailsById']);
// Route::middleware('auth:sanctum')->get('getCompletedComplaintViolationDetailsById', [Api_ComplaintViolationController::class, 'getCompletedComplaintViolationDetailsById']);

// // Aduan > Aduan Baru //REPLACE DKT API PENDING..BYTENANTS
// Route::middleware('auth:sanctum')->get('getPendingComplaintDamageList', [Api_ComplaintDamageController::class, 'getPendingComplaintDamageList']);
// Route::middleware('auth:sanctum')->get('getPendingComplaintDamageList', [Api_ComplaintViolationController::class, 'getPendingComplaintViolationList']);

////1.5 GetActiveComplaintList
// Route::middleware('auth:sanctum')->get('getActiveComplaintDamageList', [Api_ComplaintDamageController::class, 'getActiveComplaintDamageList']);
// Route::middleware('auth:sanctum')->get('getActiveComplaintViolationList', [Api_ComplaintViolationController::class, 'getActiveComplaintViolationList']);

////Aduan > Aduan Selesai  //REPLACE DKT API PENDING..BYTENANTS
// Route::middleware('auth:sanctum')->get('getCompletedComplaintDamageList', [Api_ComplaintDamageController::class, 'getCompletedComplaintDamageList']);
// Route::middleware('auth:sanctum')->get('getCompletedComplaintViolationList', [Api_ComplaintViolationController::class, 'getCompletedComplaintViolationList']);

// --------------------------------------------------------------------------------------------------------
// APPS PENGHUNI : ADUAN
// --------------------------------------------------------------------------------------------------------

// Aduan  --------------------------------------------------------

// Aduan > Baru (match)
// Route::middleware('auth:sanctum')->get('getPendingComplaintListByTenants', [Api_ComplaintController::class, 'getPendingComplaintListByTenants']); //KIV
Route::middleware('auth:sanctum')->get('getActiveComplaintListByTenants', [Api_ComplaintController::class, 'getActiveComplaintListByTenants']);
Route::middleware('auth:sanctum')->get('getActiveComplaintListById', [Api_ComplaintController::class, 'getActiveComplaintListById']);

// Aduan > Selesai (match)
Route::middleware('auth:sanctum')->get('getCompletedComplaintListByTenants', [Api_ComplaintController::class, 'getCompletedComplaintListByTenants']);
Route::middleware('auth:sanctum')->get('getCompletedComplaintListById', [Api_ComplaintController::class, 'getCompletedComplaintListById']);

// Aduan > Ditolak (match)
Route::middleware('auth:sanctum')->get('getRejectedComplaintListByTenants', [Api_ComplaintController::class, 'getRejectedComplaintListByTenants']);
Route::middleware('auth:sanctum')->get('getRejectedComplaintListById', [Api_ComplaintController::class, 'getRejectedComplaintListById']);

// SubmitComplaintForm
Route::middleware('auth:sanctum')->post('submitComplaintDamageForm', [Api_ComplaintDamageController::class, 'submitComplaintDamageForm']);
Route::middleware('auth:sanctum')->post('submitComplaintViolationForm', [Api_ComplaintViolationController::class, 'submitComplaintViolationForm']);

// Temujanji ------------------------------------------------------

// Temujanji > Baru (match)
Route::middleware('auth:sanctum')->get('getPendingAppointmentListByTenants', [Api_ComplaintDamageController::class, 'getPendingAppointmentListByTenants']);
Route::middleware('auth:sanctum')->get('getPendingAppointmentListById', [Api_ComplaintDamageController::class, 'getPendingAppointmentListById']);

// Pengesahan Temujanji Baru (noti ok)
Route::middleware('auth:sanctum')->post('confirmComplaintAppointment', [Api_ComplaintDamageController::class, 'confirmComplaintAppointment']);

// Temujanji > Senarai Temujanji   (match)
Route::middleware('auth:sanctum')->get('getComplaintAppointmentListByTenants', [Api_ComplaintDamageController::class, 'getComplaintAppointmentListByTenants']);
Route::middleware('auth:sanctum')->get('getComplaintAppointmentListById', [Api_ComplaintDamageController::class, 'getComplaintAppointmentListById']);

// Temujanji > Senarai Batal Temujanji (match)
Route::middleware('auth:sanctum')->get('getCancelAppointmentListByTenants', [Api_ComplaintDamageController::class, 'getCancelAppointmentListByTenants']);
Route::middleware('auth:sanctum')->get('getCancelAppointmentListById', [Api_ComplaintDamageController::class, 'getCancelAppointmentListById']);

// Appointment Cancel (noti ok)
Route::middleware('auth:sanctum')->post('cancelAppointmentByTenant', [Api_ComplaintAppointmentController::class, 'cancelAppointmentByTenant']);


// --------------------------------------------------------------------------------------------------------
// APPS PENGUATKUASA : PEMANTAUAN BERKALA
// --------------------------------------------------------------------------------------------------------

// 2. ROUTINE
//2.1 CountRoutineInspectionActiveByUserId
Route::middleware('auth:sanctum')->get('CountRoutineInspectionActiveByUserId', [Api_RoutineInspectionController::class, 'countRoutineInspectionActiveByUserId']);

//2.2 CountRoutineInspectionCompletedByUserId
Route::middleware('auth:sanctum')->get('countRoutineInspectionCompleted', [Api_RoutineInspectionController::class, 'countRoutineInspectionCompletedByUserId']);

//2.2.1 countRoutineInspectionApproved
Route::middleware('auth:sanctum')->get('countRoutineInspectionApproved', [Api_RoutineInspectionController::class, 'countRoutineInspectionApproved']);

//2.3 GetRoutineInspectionActiveList
Route::middleware('auth:sanctum')->get('GetRoutineInspectionActiveList', [Api_RoutineInspectionController::class, 'getRoutineInspectionActiveList']);
Route::middleware('auth:sanctum')->get('GetRoutineInspectionActiveById', [Api_RoutineInspectionController::class, 'getRoutineInspectionActiveById']);

//2.3.1 GetRoutineInspectionInProgressList
Route::middleware('auth:sanctum')->get('GetRoutineInspectionInProgressList', [Api_RoutineInspectionController::class, 'getRoutineInspectionInProgressList']);
Route::middleware('auth:sanctum')->get('GetRoutineInspectionInProgressById', [Api_RoutineInspectionController::class, 'getRoutineInspectionInProgressById']);

//2.3.2 SubmitInProgressRoutineInspection
Route::middleware('auth:sanctum')->post('SubmitInProgressRoutineInspection', [Api_RoutineInspectionController::class, 'submitInProgressRoutineInspection']);

//2.4 GetRoutineInspectionCompletedList
Route::middleware('auth:sanctum')->get('GetRoutineInspectionCompletedList', [Api_RoutineInspectionController::class, 'getRoutineInspectionCompletedList']);
Route::middleware('auth:sanctum')->get('GetRoutineInspectionCompletedById', [Api_RoutineInspectionController::class, 'getRoutineInspectionCompletedById']);

//2.5.1 SubmitRoutineInspection
Route::middleware('auth:sanctum')->post('SubmitRoutineInspection', [Api_RoutineInspectionController::class, 'submitRoutineInspection']);

//2.5.2 getApprovalOfficer
Route::middleware('auth:sanctum')->get('GetApprovalOfficer', [Api_RoutineInspectionController::class, 'getApprovalOfficer']);

//2.5.2 getApprovalStatus
Route::middleware('auth:sanctum')->get('GetApprovalStatus', [Api_RoutineInspectionController::class, 'getApprovalStatus']);

// --------------------------------------------------------------------------------------------------------
// APPS PENGHUNI : NOTIS BAYARAN
// --------------------------------------------------------------------------------------------------------

//Notis Bayaran -> Apps Penghuni
Route::middleware('auth:sanctum')->get('getPaymentNotice', [Api_PaymentNoticeController::class, 'getPaymentNotice']);
Route::middleware('auth:sanctum')->get('getPaymentRecord', [Api_PaymentRecordController::class, 'getPaymentRecord']);

// --------------------------------------------------------------------------------------------------------
//
// --------------------------------------------------------------------------------------------------------

// 3. PROFILE
//3.1 GetUserProfileInfo
Route::middleware('auth:sanctum')->get('getUserProfileInfo', [Api_UserController::class, 'getUserProfileInfo']);

//3.5 GetUserProfileKuartersInfo
Route::middleware('auth:sanctum')->get('getUserProfileKuartersInfo', [Api_UserController::class, 'getUserProfileKuartersInfo']);

//4 PANIC
//4.1 updatePanicStatus
Route::middleware('auth:sanctum')->post('updatePanicStatus', [Api_PanicController::class, 'updatePanicStatus']);

//VACANT
//getVacantForms
Route::middleware('auth:sanctum')->get('getVacantForms', [Api_QuartersLeaveController::class, 'getVacantForms']);
Route::middleware('auth:sanctum')->post('submitVacantForms', [Api_QuartersLeaveController::class, 'submitVacantForms']);

//6 Pemantauan Penghuni Keluar
Route::middleware('auth:sanctum')->get('getPenghuniKeluar', [Api_TenantLeaveController::class, 'getPenghuniKeluar']);
Route::middleware('auth:sanctum')->get('getPenghuniKeluarById', [Api_TenantLeaveController::class, 'getPenghuniKeluarById']);
Route::middleware('auth:sanctum')->post('updatePemantauanPenghuniKeluar', [Api_TenantLeaveController::class, 'updatePemantauanPenghuniKeluar']);

//Integrasi Johor Pay
Route::group(['prefix' => 'jp'], function () {

    Route::get('get_bill', [Api_JohorPayController::class, 'getBill'])->name('get_bill');
    Route::post('payment_process', [Api_JohorPayController::class, 'processPayment'])->name('payment_process');
    Route::get('get_receipt', [Api_JohorPayController::class, 'getReceipt'])->name('get_receipt');

});

//Integrasi Ispeks
Route::group(['prefix' => 'ispeks'], function () {

    Route::get('process_incoming', [Api_IntegrasiIspeksIncomingController::class, 'process_incoming'])->name('process_incoming');
    Route::get('process_outgoing', [Api_IntegrasiIspeksOutgoingController::class, 'process_outgoing'])->name('process_outgoing');

});
