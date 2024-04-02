<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserApprovalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPolicyController;
use App\Http\Controllers\DistrictManagementController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InventoryResponsibilityController;
use App\Http\Controllers\IncomeAccountCodeController;
use App\Http\Controllers\CashBookReportController;
use App\Http\Controllers\ComplaintAppointmentController;
use App\Http\Controllers\ComplaintMonitoringController;
use App\Http\Controllers\ComplaintTypeController;
use App\Http\Controllers\CollectorStatementController;
use App\Http\Controllers\RadiusController;
use App\Http\Controllers\ApplicationDateController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\PositionGradeTypeController;
use App\Http\Controllers\PositionGradeController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\BlacklistController;
use App\Http\Controllers\QuartersController;
use App\Http\Controllers\QuartersClassController;
use App\Http\Controllers\QuartersCategoryController;
use App\Http\Controllers\QuartersOptionController;
use App\Http\Controllers\SpecialPermissionController;
use App\Http\Controllers\ApplicationScoringCriteriaController;
use App\Http\Controllers\ApplicationScoringController;
use App\Http\Controllers\ApplicationReviewController;
use App\Http\Controllers\ApplicationApprovalController;
use App\Http\Controllers\InvitationPanelController;
use App\Http\Controllers\IndividualStatementReportController;
use App\Http\Controllers\MeetingRegistrationController;
use App\Http\Controllers\MaintenanceTransactionController;
use App\Http\Controllers\MonitoringReportController;
use App\Http\Controllers\EvaluationMeetingController;
use App\Http\Controllers\PlacementController;
use App\Http\Controllers\ReplacementController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\AcceptController;
use App\Http\Controllers\RejectController;
use App\Http\Controllers\RulesViolationComplaintApprovalController;
use App\Http\Controllers\RulesViolationComplaintReportController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DamageComplaintReportController;
use App\Http\Controllers\MaintenanceFeeReportController;
use App\Http\Controllers\MaintenanceFeeComparisonReportController;
use App\Http\Controllers\NoticePaymentReportController;
use App\Http\Controllers\RoutineInspectionController;
use App\Http\Controllers\RoutineInspectionApprovalController;
use App\Http\Controllers\RoutineInspectionTransactionController;
use App\Http\Controllers\RoutineInspectionScheduleController;
use App\Http\Controllers\FinanceOfficerController;
use App\Http\Controllers\PaymentNoticeScheduleController;
use App\Http\Controllers\PaymentNoticeTransactionController;
use App\Http\Controllers\AgencyPaymentNoticeController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\PenaltyController;
use App\Http\Controllers\RulesViolationComplaintAnalysisController;
use App\Http\Controllers\DamageComplaintAnalysisController;
use App\Http\Controllers\QuartersApplicationAnalysisController;
use App\Http\Controllers\QuartersInfoAnalysisController;
use App\Http\Controllers\BlacklistPenaltyRateController;
use App\Http\Controllers\BlacklistPenaltyController;
use App\Http\Controllers\CollectorStatementReportController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\SalesSummaryReportController;
use App\Http\Controllers\JournalAdjustmentController;
use App\Http\Controllers\InternalJournalAdjustmentController;
use App\Http\Controllers\InternalJournalReportController;
use App\Http\Controllers\IspeksIntegrationController;
use App\Http\Controllers\DynamicReportingController;
use App\Http\Controllers\JournalReportController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\SalesPerformanceReportController;
use App\Http\Controllers\SalesEstimationReportController;
use App\Http\Controllers\PaymentRecordController;
use App\Http\Controllers\AuditTrailController;
use App\Http\Controllers\AccountReconciliationIspeksController;
use App\Http\Controllers\AccountReconciliationIgfmasController;
use App\Http\Controllers\TenantsPenaltyReportController;
use App\Http\Controllers\SpecialPermissionReportController;
use App\Http\Controllers\ComplaintAppointmentReportController;
use App\Http\Controllers\MaintenanceReportController;
use App\Http\Controllers\RoutineInspectionReportController;
use App\Http\Controllers\BlacklistPenaltyReportController;
use App\Http\Controllers\CronJobController;
use App\Http\Controllers\IndividualBlacklistPenaltyReportController;
use App\Models\RoutineInspection;
use Illuminate\Support\Facades\Route;


//------------------------------------------------------------------------------------------------------------------------------------------------
// Modul Notification
//------------------------------------------------------------------------------------------------------------------------------------------------
//Notification
Route::group(['prefix' => 'Notification'], function () {
    //PAGE
    Route::get('Senarai', [NotificationController::class, 'index'])->name('notification.index');
    //PROCESS
    Route::get('markAsRead', [NotificationController::class, 'markAsRead'])->name('notification.markAsRead');
    Route::delete('HapusByRow', [NotificationController::class, 'destroyByRow'])->name('notification.deleteByRow');
    //AJAX
    Route::get('ajaxGetNotification', [NotificationController::class, 'ajaxGetNotification'])->name('notification.ajaxGetNotification');
});

//------------------------------------------------------------------------------------------------------------------------------------------------
//Modul Pentadbir Sistem
//------------------------------------------------------------------------------------------------------------------------------------------------
//Pengguna Sistem
Route::group(['prefix' => 'PenggunaSistem'], function () {
    //PAGE
    Route::get('Senarai', [UserController::class, 'index'])->name('user.index');
    Route::get('Papar/{id}', [UserController::class, 'view'])->name('user.view');
    Route::get('RekodBaru', [UserController::class, 'create'])->name('user.create');
    Route::get('Kemaskini/{id}', [UserController::class, 'edit'])->name('user.edit');
    //PROCESS
    Route::post('Simpan', [UserController::class, 'store'])->name('user.store');
    Route::post('Kemaskini', [UserController::class, 'update'])->name('user.update');
    Route::delete('Hapus', [UserController::class, 'destroy'])->name('user.delete');
    Route::get('ResetKatalaluan/{id}', [UserController::class, 'send_link'])->name('user.resetPassword.sendLink');
    //AJAX
    // Route::post('ajaxCheckIcAdmin', [UserController::class, 'ajaxCheckIcAdmin'])->name('user.ajaxCheckIcAdmin');
    Route::post('ajaxCheckIcAdmin', [RegisterController::class, 'ajaxCheckIc'])->name('ajaxCheckIcAdmin');
    Route::post('ajaxProcessDataHrmisAdmin', [RegisterController::class, 'ajaxProcessDataHrmis'])->name('ajaxProcessDataHrmisAdmin');
    Route::post('ajaxGetDataUsersAdmin', [RegisterController::class, 'ajaxGetDataUsers'])->name('ajaxGetDataUsersAdmin');
    Route::post('ajaxGetDataPositionType', [RegisterController::class, 'ajaxGetDataPositionType'])->name('ajaxGetDataPositionTypeAdmin');
    Route::post('ajaxGetDataServiceType', [RegisterController::class, 'ajaxGetDataServiceType'])->name('ajaxGetDataServiceTypeAdmin');
    Route::get('ajaxValidateDelete', [UserController::class, 'validateDelete'])->name('user.validateDelete');
});

//Pengesahan Pengguna Sistem
Route::group(['prefix' => 'PengaktifanAkaun'], function () {
    //PAGE
    Route::get('Senarai', [UserApprovalController::class, 'index'])->name('userApproval.index');
    Route::get('Simpan/{id}', [UserApprovalController::class, 'edit'])->name('userApproval.approval');
    //PROCESS
    Route::post('Kemaskini', [UserApprovalController::class, 'update'])->name('userApproval.update');

});

//Tetapan Peranan
Route::group(['prefix' => 'PolisiPengguna'], function () {
     //PAGE
    Route::get('Senarai', [UserPolicyController::class, 'index'])->name('userPolicy.index');
    Route::get('Papar/{id}', [UserPolicyController::class, 'view'])->name('userPolicy.view');
    Route::get('RekodBaru', [UserPolicyController::class, 'create'])->name('userPolicy.create');
    Route::get('Kemaskini/{id}', [UserPolicyController::class, 'edit'])->name('userPolicy.edit');
    //PROCESS
    Route::post('Simpan', [UserPolicyController::class, 'store'])->name('userPolicy.store');
    Route::post('Kemaskini', [UserPolicyController::class, 'update'])->name('userPolicy.update');
    Route::delete('Hapus', [UserPolicyController::class, 'destroy'])->name('userPolicy.delete');
    //AJAX
    Route::post('ajaxGetSubmenu', [UserPolicyController::class, 'ajaxGetSubmenu'])->name('userPolicy.ajaxGetSubmenu');
    Route::get('ajaxValidateDelete', [UserPolicyController::class, 'validateDelete'])->name('userPolicy.validateDelete');

});

//Pengurusan Daerah
Route::group(['prefix' => 'PengurusanDaerah'], function () {
     //PAGE
    Route::get('Senarai', [DistrictManagementController::class, 'index'])->name('districtManagement.index');
    Route::get('Papar/{id}', [DistrictManagementController::class, 'view'])->name('districtManagement.view');
    Route::get('RekodBaru', [DistrictManagementController::class, 'create'])->name('districtManagement.create');
    Route::get('Kemaskini/{id}', [DistrictManagementController::class, 'edit'])->name('districtManagement.edit');
    //PROCESS
    Route::post('Simpan', [DistrictManagementController::class, 'store'])->name('districtManagement.store');
    Route::post('Kemaskini', [DistrictManagementController::class, 'update'])->name('districtManagement.update');
    Route::delete('Hapus', [DistrictManagementController::class, 'destroy'])->name('districtManagement.delete');
    //AJAX CALLING
    Route::post('ajaxGetUser', [DistrictManagementController::class, 'ajaxGetUser'])->name('districtManagement.ajaxGetUser');
    Route::post('ajaxGetUserData', [DistrictManagementController::class, 'ajaxGetUserData'])->name('districtManagement.ajaxGetUserData');
});

//------------------------------------------------------------------------------------------------------------------------------------------------
//Modul Konfigurasi Sistem
//------------------------------------------------------------------------------------------------------------------------------------------------
//Agensi
Route::group(['prefix' => 'Agensi'], function () {
    //PAGE
   Route::get('Senarai', [AgencyController::class, 'index'])->name('agency.index');
   Route::get('Papar/{id}', [AgencyController::class, 'view'])->name('agency.view');
   Route::get('Kemaskini/{id}', [AgencyController::class, 'edit'])->name('agency.edit');
   //PROCESS
   Route::post('Kemaskini', [AgencyController::class, 'update'])->name('agency.update');
});

//Jabatan
Route::group(['prefix' => 'Jabatan'], function () {
    //PAGE
   Route::get('Senarai', [DepartmentController::class, 'index'])->name('department.index');
   Route::get('Papar/{id}', [DepartmentController::class, 'view'])->name('department.view');
});

//Pegawai
Route::group(['prefix' => 'Pegawai'], function () {
     //PAGE
    Route::get('Senarai', [OfficerController::class, 'index'])->name('officer.index');
    Route::get('Papar/{id}', [OfficerController::class, 'view'])->name('officer.view');
    Route::get('RekodBaru', [OfficerController::class, 'create'])->name('officer.create');
    Route::get('Kemaskini/{id}', [OfficerController::class, 'edit'])->name('officer.edit');
    //PROCESS
    Route::post('Simpan', [OfficerController::class, 'store'])->name('officer.store');
    Route::post('Kemaskini', [OfficerController::class, 'update'])->name('officer.update');
    Route::delete('Hapus', [OfficerController::class, 'destroy'])->name('officer.delete');
    //AJAX
    Route::get('ajaxValidateDelete', [OfficerController::class, 'validateDelete'])->name('officer.validateDelete');
});

//Inventori
Route::group(['prefix' => 'Inventori'], function () {
    //PAGE
    Route::get('SenaraiKategoriKuarters', [InventoryController::class, 'indexKategoriKuarters'])->name('listQuartersCategoryInventory.index');
    Route::get('Senarai', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('Papar/{quarters_cat_id}/{id}', [InventoryController::class, 'view'])->name('inventory.view');
    Route::get('RekodBaru', [InventoryController::class, 'create'])->name('inventory.create');
    Route::get('Kemaskini/{quarters_cat_id}/{id}', [InventoryController::class, 'edit'])->name('inventory.edit');
    //PROCESS
    Route::post('Simpan', [InventoryController::class, 'store'])->name('inventory.store');
    Route::post('Kemaskini', [InventoryController::class, 'update'])->name('inventory.update');
    Route::delete('Hapus', [InventoryController::class, 'destroy'])->name('inventory.delete');
    //AJAX
    Route::get('ajaxValidateDelete', [InventoryController::class, 'validateDelete'])->name('inventory.validateDelete');
});

//Aduan
Route::group(['prefix' => 'JenisAduan'], function () {
     //PAGE
    Route::get('Senarai', [ComplaintTypeController::class, 'index'])->name('complaintType.index');
    Route::get('Papar/{id}', [ComplaintTypeController::class, 'view'])->name('complaintType.view');
    Route::get('RekodBaru', [ComplaintTypeController::class, 'create'])->name('complaintType.create');
    Route::get('Kemaskini/{id}', [ComplaintTypeController::class, 'edit'])->name('complaintType.edit');
    //PROCESS
    Route::post('Simpan', [ComplaintTypeController::class, 'store'])->name('complaintType.store');
    Route::post('Kemaskini', [ComplaintTypeController::class, 'update'])->name('complaintType.update');
    Route::delete('Hapus', [ComplaintTypeController::class, 'destroy'])->name('complaintType.delete');
});

//Kelas Kuarters
Route::group(['prefix' => 'KelasKuarters'], function () {
     //PAGE
    Route::get('Senarai', [QuartersClassController::class, 'index'])->name('quartersClass.index');
    Route::get('Papar/{id}', [QuartersClassController::class, 'view'])->name('quartersClass.view');
    Route::get('RekodBaru', [QuartersClassController::class, 'create'])->name('quartersClass.create');
    Route::get('Kemaskini/{id}', [QuartersClassController::class, 'edit'])->name('quartersClass.edit');
    //PROCESS
    Route::post('Simpan', [QuartersClassController::class, 'store'])->name('quartersClass.store');
    Route::post('Kemaskini', [QuartersClassController::class, 'update'])->name('quartersClass.update');
    Route::delete('Hapus', [QuartersClassController::class, 'destroy'])->name('quartersClass.delete');
    Route::delete('HapusByRow', [QuartersClassController::class, 'destroyByRow'])->name('quartersClass.deleteByRow');
    //AJAX
    Route::get('ajaxSenaraiGred', [QuartersClassController::class, 'gradeList'])->name('quartersClass.gradeList');
    Route::get('ajaxSenaraiKategoriPemohon', [QuartersClassController::class, 'servicesTypeList'])->name('quartersClass.servicesTypeList');
    Route::get('ajaxValidateDelete', [QuartersClassController::class, 'validateDelete'])->name('quartersClass.validateDelete');
});

//Kategori Kuarters || Kategori Kuarters (Lokasi)
Route::group(['prefix' => 'LokasiKuarters'], function () {
     //PAGE
    Route::get('Senarai', [QuartersCategoryController::class, 'index'])->name('quartersCategory.index');
    Route::get('Papar/{id}', [QuartersCategoryController::class, 'view'])->name('quartersCategory.view');
    Route::get('RekodBaru', [QuartersCategoryController::class, 'create'])->name('quartersCategory.create');
    Route::get('Kemaskini/{id}', [QuartersCategoryController::class, 'edit'])->name('quartersCategory.edit');
    //PROCESS
    Route::post('Simpan', [QuartersCategoryController::class, 'store'])->name('quartersCategory.store');
    Route::post('Kemaskini', [QuartersCategoryController::class, 'update'])->name('quartersCategory.update');
    Route::delete('Hapus', [QuartersCategoryController::class, 'destroy'])->name('quartersCategory.delete');
    Route::delete('HapusByRow', [QuartersCategoryController::class, 'destroyByRow'])->name('quartersCategory.deleteByRow');
    //AJAX
    Route::get('ajaxSenaraiInventori', [QuartersCategoryController::class, 'inventoryList'])->name('quartersCategory.inventoryList');
    Route::get('ajaxSenaraiMaklumatSewa', [QuartersCategoryController::class, 'classGradeList'])->name('quartersCategory.classGradeList');
    Route::get('ajaxValidateDelete', [QuartersCategoryController::class, 'validateDelete'])->name('quartersCategory.validateDelete');
});

//Radius
Route::group(['prefix' => 'Radius'], function () {
     //PAGE
    Route::get('Senarai', [RadiusController::class, 'index'])->name('radius.index');
    Route::get('Papar/{id}', [RadiusController::class, 'view'])->name('radius.view');
    Route::get('RekodBaru', [RadiusController::class, 'create'])->name('radius.create');
    Route::get('Kemaskini/{id}', [RadiusController::class, 'edit'])->name('radius.edit');
    //PROCESS
    Route::post('Simpan', [RadiusController::class, 'store'])->name('radius.store');
    Route::post('Kemaskini', [RadiusController::class, 'update'])->name('radius.update');
    Route::delete('Hapus', [RadiusController::class, 'destroy'])->name('radius.delete');
});

//Tarikh Permohonan
Route::group(['prefix' => 'TarikhPermohonan'], function () {
     //PAGE
    Route::get('Senarai', [ApplicationDateController::class, 'index'])->name('applicationDate.index');
    Route::get('Papar/{id}', [ApplicationDateController::class, 'view'])->name('applicationDate.view');
    Route::get('RekodBaru', [ApplicationDateController::class, 'create'])->name('applicationDate.create');
    Route::get('Kemaskini/{id}', [ApplicationDateController::class, 'edit'])->name('applicationDate.edit');
    //PROCESS
    Route::post('Simpan', [ApplicationDateController::class, 'store'])->name('applicationDate.store');
    Route::post('Kemaskini', [ApplicationDateController::class, 'update'])->name('applicationDate.update');
    Route::delete('Hapus', [ApplicationDateController::class, 'destroy'])->name('applicationDate.delete');
});

//Jawatan
Route::group(['prefix' => 'Jawatan'], function () {
     //PAGE
    Route::get('Senarai', [PositionController::class, 'index'])->name('position.index');
    Route::get('Papar/{id}', [PositionController::class, 'view'])->name('position.view');
    Route::get('RekodBaru', [PositionController::class, 'create'])->name('position.create');
    Route::get('Kemaskini/{id}', [PositionController::class, 'edit'])->name('position.edit');
    //PROCESS
    Route::post('Simpan', [PositionController::class, 'store'])->name('position.store');
    Route::post('Kemaskini', [PositionController::class, 'update'])->name('position.update');
    Route::delete('Hapus', [PositionController::class, 'destroy'])->name('position.delete');
});

//Kod/Jenis Jawatan
Route::group(['prefix' => 'KodJawatan'], function () {
     //PAGE
    Route::get('Senarai', [PositionGradeTypeController::class, 'index'])->name('positionGradeType.index');
    Route::get('Papar/{id}', [PositionGradeTypeController::class, 'view'])->name('positionGradeType.view');
    Route::get('RekodBaru', [PositionGradeTypeController::class, 'create'])->name('positionGradeType.create');
    Route::get('Kemaskini/{id}', [PositionGradeTypeController::class, 'edit'])->name('positionGradeType.edit');
    //PROCESS
    Route::post('Simpan', [PositionGradeTypeController::class, 'store'])->name('positionGradeType.store');
    Route::post('Kemaskini', [PositionGradeTypeController::class, 'update'])->name('positionGradeType.update');
    Route::delete('Hapus', [PositionGradeTypeController::class, 'destroy'])->name('positionGradeType.delete');
});

//Gred Jawatan
Route::group(['prefix' => 'GredJawatan'], function () {
     //PAGE
    Route::get('Senarai', [PositionGradeController::class, 'index'])->name('positionGrade.index');
    Route::get('Papar/{id}', [PositionGradeController::class, 'view'])->name('positionGrade.view');
    Route::get('RekodBaru', [PositionGradeController::class, 'create'])->name('positionGrade.create');
    Route::get('Kemaskini/{id}', [PositionGradeController::class, 'edit'])->name('positionGrade.edit');
    //PROCESS
    Route::post('Simpan', [PositionGradeController::class, 'store'])->name('positionGrade.store');
    Route::post('Kemaskini', [PositionGradeController::class, 'update'])->name('positionGrade.update');
    Route::delete('Hapus', [PositionGradeController::class, 'destroy'])->name('positionGrade.delete');
});

//Dokumen
Route::group(['prefix' => 'Dokumen'], function () {
     //PAGE
    Route::get('Senarai', [DocumentController::class, 'index'])->name('document.index');
    Route::get('Papar/{id}', [DocumentController::class, 'view'])->name('document.view');
    Route::get('RekodBaru', [DocumentController::class, 'create'])->name('document.create');
    Route::get('Kemaskini/{id}', [DocumentController::class, 'edit'])->name('document.edit');
    //PROCESS
    Route::post('Simpan', [DocumentController::class, 'store'])->name('document.store');
    Route::post('Kemaskini', [DocumentController::class, 'update'])->name('document.update');
    Route::delete('Hapus', [DocumentController::class, 'destroy'])->name('document.delete');
    //AJAX
    Route::get('ajaxValidateDelete', [DocumentController::class, 'validateDelete'])->name('document.validateDelete');
});

//Senarai Hitam
Route::group(['prefix' => 'SenaraiHitam'], function () {
     //PAGE
    Route::get('Senarai', [BlacklistController::class, 'index'])->name('blacklist.index');
    Route::get('Kemaskini/{id}', [BlacklistController::class, 'edit'])->name('blacklist.edit');
    //PROCESS
    Route::post('Kemaskini', [BlacklistController::class, 'update'])->name('blacklist.update');
    Route::delete('Hapus', [BlacklistController::class, 'destroy'])->name('blacklist.delete');
});

//Tanggungjawab Inventori
Route::group(['prefix' => 'TanggungjawabInventori'], function () {
    //PAGE
   Route::get('Senarai', [InventoryResponsibilityController::class, 'index'])->name('inventoryResponsibility.index');
   Route::get('Papar/{id}', [InventoryResponsibilityController::class, 'view'])->name('inventoryResponsibility.view');
   Route::get('RekodBaru', [InventoryResponsibilityController::class, 'create'])->name('inventoryResponsibility.create');
   Route::get('Kemaskini/{id}', [InventoryResponsibilityController::class, 'edit'])->name('inventoryResponsibility.edit');
   //PROCESS
   Route::post('Simpan', [InventoryResponsibilityController::class, 'store'])->name('inventoryResponsibility.store');
   Route::post('Kemaskini', [InventoryResponsibilityController::class, 'update'])->name('inventoryResponsibility.update');
   Route::delete('Hapus', [InventoryResponsibilityController::class, 'destroy'])->name('inventoryResponsibility.delete');
   // AJAX
   Route::get('ajaxValidateDelete', [InventoryResponsibilityController::class, 'validateDelete'])->name('inventoryResponsibility.validateDelete');
});

//Kategori Kuarters (Lokasi)
Route::group(['prefix' => 'BilanganPilihanLokasiKuarters'], function () {
    //PAGE
   Route::get('Senarai', [QuartersOptionController::class, 'index'])->name('quartersOption.index');
   Route::get('Papar/{id}', [QuartersOptionController::class, 'view'])->name('quartersOption.view');
   Route::get('RekodBaru', [QuartersOptionController::class, 'create'])->name('quartersOption.create');
   Route::get('Kemaskini/{id}', [QuartersOptionController::class, 'edit'])->name('quartersOption.edit');
   //PROCESS
   Route::post('Simpan', [QuartersOptionController::class, 'store'])->name('quartersOption.store');
   Route::post('Kemaskini', [QuartersOptionController::class, 'update'])->name('quartersOption.update');
   Route::delete('Hapus', [QuartersOptionController::class, 'destroy'])->name('quartersOption.delete');
});

//Cron Job
Route::group(['prefix' => 'CronJob'], function () {
   //PAGE
   Route::get('Senarai', [CronJobController::class, 'index'])->name('cronJob.index');
   Route::get('RekodBaru', [CronJobController::class, 'create'])->name('cronJob.create');
   //PROCESS
   Route::post('Simpan', [CronJobController::class, 'store'])->name('cronJob.store');
   Route::delete('Hapus', [CronJobController::class, 'destroy'])->name('cronJob.delete');
});

//Kebenaran Khas
Route::group(['prefix' => 'KebenaranKhas'], function () {
    //PAGE
    Route::get('Senarai', [SpecialPermissionController::class, 'index'])->name('specialPermission.index');
    Route::get('Papar/{id}', [SpecialPermissionController::class, 'view'])->name('specialPermission.view');
    Route::get('RekodBaru', [SpecialPermissionController::class, 'create'])->name('specialPermission.create');
    Route::get('Kemaskini/{id}', [SpecialPermissionController::class, 'edit'])->name('specialPermission.edit');
    //PROCESS
    Route::post('Simpan', [SpecialPermissionController::class, 'store'])->name('specialPermission.store');
    Route::post('Kemaskini', [SpecialPermissionController::class, 'update'])->name('specialPermission.update');
    Route::delete('Hapus', [SpecialPermissionController::class, 'destroy'])->name('specialPermission.delete');
    Route::delete('HapusByRow', [SpecialPermissionController::class, 'destroyByRow'])->name('specialPermission.deleteByRow');
    //AJAX
    Route::post('ajaxCheckIcUser', [SpecialPermissionController::class, 'ajaxCheckIcUser'])->name('specialPermission.ajaxCheckIcUser');
    Route::post('ajaxGetField', [SpecialPermissionController::class, 'ajaxGetField'])->name('specialPermission.ajaxGetField');
});

//------------------------------------------------------------------------------------------------------------------------------------------------
//Modul Kuarters
//------------------------------------------------------------------------------------------------------------------------------------------------
//Kuarters
Route::group(['prefix' => 'Kuarters'], function () {
     //PAGE
    Route::get('SenaraiKategoriKuarters', [QuartersController::class, 'indexKategoriKuarters'])->name('listQuartersCategory.index');
    Route::get('SenaraiKuarters/{quarters_cat_id}', [QuartersController::class, 'indexKuarters'])->name('quarters.index');
    Route::get('Papar/{id}', [QuartersController::class, 'view'])->name('quarters.view');
    Route::get('RekodBaru/{quarters_cat_id}', [QuartersController::class, 'create'])->name('quarters.create');
    Route::get('RekodBaruNoUnit/{quarters_cat_id}', [QuartersController::class, 'addUnitNo'])->name('quarters.addUnitNo');
    Route::get('Kemaskini/{id}/{quarters_cat_id}', [QuartersController::class, 'edit'])->name('quarters.edit');
    //PROCESS
    Route::post('Simpan/{quarters_cat_id}', [QuartersController::class, 'store'])->name('quarters.store');
    Route::post('SimpanNoUnit/{quarters_cat_id}', [QuartersController::class, 'storeUnitNo'])->name('quarters.storeUnitNo');
    Route::post('Kemaskini/{quarters_cat_id}', [QuartersController::class, 'update'])->name('quarters.update');
    Route::delete('Hapus', [QuartersController::class, 'destroy'])->name('quarters.delete');
    Route::delete('Padam', [QuartersController::class, 'destroyImage'])->name('quarters.destroyImage');
    Route::post('KemaskiniStatus/{quarters_cat_id}', [QuartersController::class, 'saveQuartersStatus'])->name('quarters.saveQuartersStatus');
    //AJAX
    Route::post('ajaxGetCategoryQuarters', [QuartersController::class, 'ajaxGetCategoryQuarters'])->name('quarters.ajaxGetCategoryQuarters');
    Route::post('ajaxGetCategoryQuartersData', [QuartersController::class, 'ajaxGetCategoryQuartersData'])->name('quarters.ajaxGetCategoryQuartersData');

});

//------------------------------------------------------------------------------------------------------------------------------------------------
// Modul Pengurusan Permohonan Kuarters
//------------------------------------------------------------------------------------------------------------------------------------------------
//Permohonan Kuarters
// Route::group(['prefix' => 'PermohonanKuarters'], function () {
//      //PAGE
//     Route::get('RekodBaru', [QuartersApplicationController::class, 'create'])->name('application.create');
//     //PROCESS
//     Route::post('Simpan', [QuartersApplicationController::class, 'store'])->name('application.store');
// });

// //Permohonan Greenlane
// Route::group(['prefix' => 'PermohonanGreenlane'], function () {
//      //PAGE
//     Route::get('Senarai', [GreenlaneApplicationController::class, 'index'])->name('applicationGreenlane.index');
//     Route::get('Papar/{id}', [GreenlaneApplicationController::class, 'view'])->name('applicationGreenlane.view');
//     Route::get('RekodBaru', [GreenlaneApplicationController::class, 'create'])->name('applicationGreenlane.create');
//     Route::get('Kemaskini/{id}', [GreenlaneApplicationController::class, 'edit'])->name('applicationGreenlane.edit');
//     //PROCESS
//     Route::post('Simpan', [GreenlaneApplicationController::class, 'store'])->name('applicationGreenlane.store');
//     Route::delete('Hapus', [GreenlaneApplicationController::class, 'destroy'])->name('applicationGreenlane.delete');
//     Route::post('Hantar', [GreenlaneApplicationController::class, 'send'])->name('applicationGreenlane.send');
//     Route::post('Update', [GreenlaneApplicationController::class, 'update'])->name('applicationGreenlane.update');
//     //AJAX
//     Route::post('ajaxGetField', [GreenlaneApplicationController::class, 'ajaxGetField'])->name('applicationGreenlane.ajaxGetField');
//     Route::post('ajaxGetFieldSpouse', [GreenlaneApplicationController::class, 'ajaxGetFieldSpouse'])->name('applicationGreenlane.ajaxGetFieldSpouse');
//     Route::post('ajaxGetTable', [GreenlaneApplicationController::class, 'ajaxGetTable'])->name('applicationGreenlane.ajaxGetTable');
//     Route::post('ajaxGetFieldEpnj', [GreenlaneApplicationController::class, 'ajaxGetFieldEpnj'])->name('applicationGreenlane.ajaxGetFieldEpnj');
//     Route::post('ajaxGetFieldSpouseEpnj', [GreenlaneApplicationController::class, 'ajaxGetFieldSpouseEpnj'])->name('applicationGreenlane.ajaxGetFieldSpouseEpnj');
//     Route::get('ajaxGetDistance', [GreenlaneApplicationController::class, 'ajaxGetDistance'])->name('applicationGreenlane.ajaxGetDistance');
// });

//------------------------------------------------------------------------------------------------------------------------------------------------
// Modul Semakan Permohonan
//------------------------------------------------------------------------------------------------------------------------------------------------
//Kriteria Pemarkahan
Route::group(['prefix' => 'KriteriaPemarkahan'], function () {
    //PAGE
    Route::get('Senarai', [ApplicationScoringCriteriaController::class, 'index'])->name('applicationScoringCriteria.index');
    Route::get('Papar/{id}', [ApplicationScoringCriteriaController::class, 'view'])->name('applicationScoringCriteria.view');
    Route::get('RekodBaru', [ApplicationScoringCriteriaController::class, 'create'])->name('applicationScoringCriteria.create');
    Route::get('Kemaskini/{id}', [ApplicationScoringCriteriaController::class, 'edit'])->name('applicationScoringCriteria.edit');
    //PROCESS
    Route::post('Simpan', [ApplicationScoringCriteriaController::class, 'store'])->name('applicationScoringCriteria.store');
    Route::post('Kemaskini', [ApplicationScoringCriteriaController::class, 'update'])->name('applicationScoringCriteria.update');
    Route::delete('Hapus', [ApplicationScoringCriteriaController::class, 'destroy'])->name('applicationScoringCriteria.delete');
    Route::delete('HapusByCriteria', [ApplicationScoringCriteriaController::class, 'destroyByCriteria'])->name('applicationScoringCriteria.deleteByCriteria');
    Route::delete('HapusBySubcriteria', [ApplicationScoringCriteriaController::class, 'destroyBySubcriteria'])->name('applicationScoringCriteria.deleteBySubcriteria');
});

//Permarkahan Permohonan
Route::group(['prefix' => 'PemarkahanPermohonan'], function () {
    //PAGE
    Route::get('Senarai', [ApplicationScoringController::class, 'index'])->name('applicationScoring.index');
    Route::get('Papar', [ApplicationScoringController::class, 'view'])->name('applicationScoring.view');
    Route::get('MaklumatPermohonan', [ApplicationScoringController::class, 'score'])->name('applicationScoring.score');
    //PROCESS
    Route::post('Simpan', [ApplicationScoringController::class, 'store'])->name('applicationScoring.store');
    Route::get('Kemaskini/{id}', [ApplicationScoringController::class, 'edit'])->name('applicationScoring.edit');
});

//Semakan Permohonan
Route::group(['prefix' => 'SemakanPermohonan'], function () {
     //PAGE
    Route::get('Senarai', [ApplicationReviewController::class, 'index'])->name('applicationReview.index');
    Route::get('Papar', [ApplicationReviewController::class, 'view'])->name('applicationReview.view');
    Route::get('Semakan/{id}', [ApplicationReviewController::class, 'review'])->name('applicationReview.review');
    Route::get('Kemaskini/{id}', [ApplicationReviewController::class, 'edit'])->name('applicationReview.edit');
    //PROCESS
    Route::post('Simpan', [ApplicationReviewController::class, 'store'])->name('applicationReview.store');
});

//Kelulusan Permohonan
Route::group(['prefix' => 'KelulusanPermohonan'], function () {
     //PAGE
    Route::get('Senarai', [ApplicationApprovalController::class, 'index'])->name('applicationApproval.index');
    Route::get('Papar', [ApplicationApprovalController::class, 'view'])->name('applicationApproval.view');
    Route::get('Kemaskini/{id}', [ApplicationApprovalController::class, 'edit'])->name('applicationApproval.edit');
    //PROCESS
    Route::post('Simpan', [ApplicationApprovalController::class, 'store'])->name('applicationApproval.store');
});

//------------------------------------------------------------------------------------------------------------------------------------------------
// Modul Mesyuarat
//------------------------------------------------------------------------------------------------------------------------------------------------
//Panel Luar
Route::group(['prefix' => 'PanelLuar'], function () {
    //PAGE
   Route::get('Senarai', [InvitationPanelController::class, 'index'])->name('invitationPanel.index');
   Route::get('Papar/{id}', [InvitationPanelController::class, 'view'])->name('invitationPanel.view');
   Route::get('RekodBaru', [InvitationPanelController::class, 'create'])->name('invitationPanel.create');
   Route::get('Kemaskini/{id}', [InvitationPanelController::class, 'edit'])->name('invitationPanel.edit');
   //PROCESS
   Route::post('Simpan', [InvitationPanelController::class, 'store'])->name('invitationPanel.store');
   Route::post('Kemaskini', [InvitationPanelController::class, 'update'])->name('invitationPanel.update');
   Route::delete('Hapus', [InvitationPanelController::class, 'destroy'])->name('invitationPanel.delete');
   //AJAX
   Route::get('ajaxValidateDelete', [InvitationPanelController::class, 'validateDelete'])->name('invitationPanel.validateDelete');
});

//Daftar Mesyuarat
Route::group(['prefix' => 'DaftarMesyuarat'], function () {
    //PAGE
    Route::get('Senarai', [MeetingRegistrationController::class, 'index'])->name('meetingRegistration.index');
    Route::get('RekodBaru', [MeetingRegistrationController::class, 'create'])->name('meetingRegistration.create');
    Route::get('Papar', [MeetingRegistrationController::class, 'view'])->name('meetingRegistration.view');
    Route::get('Kemaskini/{id}', [MeetingRegistrationController::class, 'edit'])->name('meetingRegistration.edit');
    Route::get('KemaskiniSurat/{id}', [MeetingRegistrationController::class, 'indexLetter'])->name('meetingRegistration.indexLetter');
    //PROCESS
    Route::post('Simpan', [MeetingRegistrationController::class, 'store'])->name('meetingRegistration.store');
    Route::delete('Hapus', [MeetingRegistrationController::class, 'destroy'])->name('meetingRegistration.delete');
    Route::post('Kemaskini', [MeetingRegistrationController::class, 'update'])->name('meetingRegistration.update');
    Route::any('Surat/{id}/{flag}', [MeetingRegistrationController::class, 'processLetter'])->name('meetingRegistration.processLetter');
    //AJAX
    Route::post('ajaxGetUser', [MeetingRegistrationController::class, 'ajaxGetUser'])->name('meetingRegistration.ajaxGetUser');
    Route::post('ajaxGetApplicationList', [MeetingRegistrationController::class, 'ajaxGetApplicationList'])->name('meetingRegistration.ajaxGetApplicationList');
});

//Mesyuarat Penilaian
Route::group(['prefix' => 'MesyuaratPenilaian'], function () {
     //PAGE
    Route::get('Senarai', [EvaluationMeetingController::class, 'index'])->name('evaluationMeeting.index');
    Route::get('Papar/{application_id}', [EvaluationMeetingController::class, 'edit'])->name('evaluationMeeting.view');
    Route::get('Kemaskini/{id}', [EvaluationMeetingController::class, 'edit'])->name('evaluationMeeting.edit');
    //PROCESS
    Route::post('KemaskiniKehadiran', [EvaluationMeetingController::class, 'updateAttendance'])->name('evaluationMeeting.updateAttendance');
    Route::post('KemaskiniPermohonan', [EvaluationMeetingController::class, 'updateApplication'])->name('evaluationMeeting.updateApplication');
    Route::get('KemaskiniMarkahPermohonan', [EvaluationMeetingController::class, 'updateApplicationScoring'])->name('evaluationMeeting.updateApplicationScoring');
    //AJAX
    Route::post('ajaxGetApplicationList', [EvaluationMeetingController::class, 'ajaxGetApplicationList'])->name('evaluationMeeting.ajaxGetApplicationList');
    Route::post('ajaxGetApplicationById', [EvaluationMeetingController::class, 'ajaxGetApplicationById'])->name('evaluationMeeting.ajaxGetApplicationById');
});

//------------------------------------------------------------------------------------------------------------------------------------------------
// Modul Pengesahan Penempatan

//------------------------------------------------------------------------------------------------------------------------------------------------
//Pengesahan Penempatan
Route::group(['prefix' => 'Penempatan'], function () {
     //PAGE
    Route::get('Senarai', [PlacementController::class, 'index'])->name('placement.index');
    Route::get('SenaraiPenempatanKuarters/{category}', [PlacementController::class, 'listPlacement'])->name('placement.listPlacement');
    Route::get('Pengesahan/{category}', [PlacementController::class, 'bulkPlacement'])->name('placement.bulkPlacement');
    Route::get('Papar/{application}', [PlacementController::class, 'show'])->name('placement.show');
    Route::get('Kemaskini/{application}', [PlacementController::class, 'edit'])->name('placement.edit');
    Route::get('cetak/{application}', [PlacementController::class, 'printPage'])->name('placement.printPage');
    Route::get('PertukaranPenempatan', [PlacementController::class, 'listReplacement'])->name('placement.listReplacement');
    //PROCESS
    Route::post('Kemaskini', [PlacementController::class, 'update'])->name('placement.update');
    Route::post('cetak/{application}', [PlacementController::class, 'cetak'])->name('placement.print');
    Route::post('notifikasi/{application}', [PlacementController::class, 'triggerNotification'])->name('placement.notification');
    Route::post('updateBulk', [PlacementController::class, 'updateBulk'])->name('placement.updateBulk');
    //AJAX
    Route::post('ajaxGetUnitNo', [PlacementController::class, 'ajaxGetUnitNo'])->name('placement.ajaxGetUnitNo');
    Route::post('ajaxAvail', [PlacementController::class, 'ajaxGetAvailableUnitByAddr'])->name('placement.ajaxGetAvailableUnitByAddr');

});

//Pertukaran Penempatan
Route::group(['prefix' => 'PertukaranPenempatan'], function () {
    //PAGE
   Route::get('Senarai', [ReplacementController::class, 'index'])->name('replacement.index');
   Route::get('SenaraiPertukaranPenempatan/{category}', [ReplacementController::class, 'listReplacement'])->name('replacement.listReplacement');
   Route::get('Kemaskini/{application}', [ReplacementController::class, 'edit'])->name('replacement.edit');
   Route::get('Tukar/{tenant}', [ReplacementController::class, 'tukarpage'])->name('replacement.replacepage');

   //PROCESS
   Route::post('Kemaskini', [ReplacementController::class, 'update'])->name('replacement.update');
   Route::post('Tukar/{tenant}', [ReplacementController::class, 'tukarpage'])->name('replacement.replace');

   //AJAX
   Route::post('ajaxgetaddress', [ReplacementController::class, 'ajaxGetAddressByCategory'])->name('replacement.ajaxGetAddressByCategory');
   Route::post('ajaxgetavailablequarters', [ReplacementController::class, 'ajaxGetAvailableQuartersCategory'])->name('replacement.ajaxGetAvailableQuartersCategory');
   Route::post('ajaxgetavailableaddrbycategory', [ReplacementController::class, 'ajaxGetAvailableAddrByCategory'])->name('replacement.ajaxGetAvailableAddrByCategory');
   Route::post('getavailableunitbyaddress', [ReplacementController::class, 'ajaxGetAvailableUnitByAddr'])->name('replacement.getAvailableUnitByAddress');
});

// Setuju Tawaran
Route::group(['prefix' => 'TerimaTawaran'], function () {
    //PAGE
   Route::get('Senarai', [AcceptController::class, 'index'])->name('accept.index');
   Route::get('Papar/{application}', [AcceptController::class, 'show'])->name('accept.show');
});

// Tolak Tawaran
Route::group(['prefix' => 'TolakTawaran'], function () {
    //PAGE
   Route::get('Senarai', [RejectController::class, 'index'])->name('reject.index');
   Route::get('Papar/{application}', [RejectController::class, 'show'])->name('reject.show');
});

//------------------------------------------------------------------------------------------------------------------------------------------------
// Modul Pemantauan
//------------------------------------------------------------------------------------------------------------------------------------------------
//Temujanji Aduan
Route::group(['prefix' => 'TemujanjiAduan'], function () {
   //PAGE
    Route::get('Senarai', [ComplaintAppointmentController::class, 'index'])->name('complaintAppointment.index');
    Route::get('RekodBaru/{id}', [ComplaintAppointmentController::class, 'create'])->name('complaintAppointment.create');
    Route::get('Senarai/{id}', [ComplaintAppointmentController::class, 'view'])->name('complaintAppointment.view');
    Route::get('Kemaskini/{id}',[ComplaintAppointmentController::class, 'edit'])->name('complaintAppointment.edit');
    //PROCESS
    Route::post('Simpan', [ComplaintAppointmentController::class, 'store'])->name('complaintAppointment.store');
    Route::post('Kemaskini', [ComplaintAppointmentController::class, 'update'])->name('complaintAppointment.update');
    Route::post('Batal', [ComplaintAppointmentController::class, 'cancel'])->name('complaintAppointment.cancel_appointment');

    //AJAX
    Route::get('ajaxGetComplaintInventoryAttachmentList', [ComplaintAppointmentController::class, 'ajaxGetComplaintInventoryAttachmentList'])->name('complaintAppointment.ajaxGetComplaintInventoryAttachmentList');
    Route::get('ajaxGetComplaintOthersAttachmentList', [ComplaintAppointmentController::class, 'ajaxGetComplaintOthersAttachmentList'])->name('complaintAppointment.ajaxGetComplaintOthersAttachmentList');
    Route::get('ajaxGetAppointmentList', [ComplaintAppointmentController::class, 'ajaxGetAppointmentList'])->name('complaintAppointment.ajaxGetAppointmentList');

});

//Pemantauan Aduan
Route::group(['prefix' => 'PemantauanAduan'], function () {
    //PAGE
    Route::get('Senarai', [ComplaintMonitoringController::class, 'index'])->name('complaintMonitoring.index');
    Route::get('Kemaskini/{id}', [ComplaintMonitoringController::class, 'edit'])->name('complaintMonitoring.edit');

    Route::get('Papar/AduanSelesai/{id}', [ComplaintMonitoringController::class, 'view_aduan_selesai'])->name('complaintMonitoring.view_aduan_selesai');
    Route::get('Papar/AduanDitolak/{id}', [ComplaintMonitoringController::class, 'view_aduan_ditolak'])->name('complaintMonitoring.view_aduan_ditolak');
    Route::get('Kemaskini/AduanBerulang/{id}', [ComplaintMonitoringController::class, 'view_aduan_berulang'])->name('complaintMonitoring.view_aduan_berulang');
    Route::get('Papar/AduanSelenggara/{id}', [ComplaintMonitoringController::class, 'view_aduan_selenggara'])->name('complaintMonitoring.view_aduan_selenggara');
    Route::get('Papar/PenghuniKeluar/{tenant}', [ComplaintMonitoringController::class, 'view_penghuni_keluar'])->name('complaintMonitoring.view_penghuni_keluar');
    //PROCESS
    Route::post('Simpan', [ComplaintMonitoringController::class, 'store'])->name('complaintMonitoring.store');
    Route::post('Kemaskini', [ComplaintMonitoringController::class, 'update'])->name('complaintMonitoring.update');
    Route::post('Kemaskini/AduanBerulang', [ComplaintMonitoringController::class, 'update_aduan_berulang'])->name('complaintMonitoring.update_aduan_berulang');
    Route::post('Kemaskini/PenghuniKeluar', [ComplaintMonitoringController::class, 'update_penghuni_keluar'])->name('complaintMonitoring.update_penghuni_keluar');
    //AJAX
    Route::get('ajaxGetComplaintInventoryAttachmentList', [ComplaintMonitoringController::class, 'ajaxGetComplaintInventoryAttachmentList'])->name('complaintMonitoring.ajaxGetComplaintInventoryAttachmentList');
    Route::get('ajaxGetComplaintOthersAttachmentList', [ComplaintMonitoringController::class, 'ajaxGetComplaintOthersAttachmentList'])->name('complaintMonitoring.ajaxGetComplaintOthersAttachmentList');
 });

 //Pengesahan Aduan Awam
Route::group(['prefix' => 'PengesahanAduanAwam'], function () {
    //PAGE
     Route::get('Senarai', [RulesViolationComplaintApprovalController::class, 'index'])->name('rulesViolationComplaintApproval.index');
     Route::get('Kemaskini/{id}', [RulesViolationComplaintApprovalController::class, 'create'])->name('rulesViolationComplaintApproval.create');
     Route::get('Papar/{id}', [RulesViolationComplaintApprovalController::class, 'view'])->name('rulesViolationComplaintApproval.view');
     //PROCESS
     Route::post('Simpan', [RulesViolationComplaintApprovalController::class, 'store'])->name('rulesViolationComplaintApproval.store');
     Route::post('Kemaskini', [RulesViolationComplaintApprovalController::class, 'update'])->name('rulesViolationComplaintApproval.update');

 });
//------------------------------------------------------------------------------------------------------------------------------------------------
// Modul Penyewa
//------------------------------------------------------------------------------------------------------------------------------------------------
//Penyewa
Route::group(['prefix' => 'Penghuni'], function () {
    //PAGE
   Route::get('Senarai', [TenantController::class, 'index'])->name('tenant.index');
   Route::get('SenaraiPenghuni/{category}', [TenantController::class, 'tenantList'])->name('tenant.tenantList');
   Route::get('Papar/{category}/{tenant}', [TenantController::class, 'view'])->name('tenant.view');
   Route::get('PengesahanKeluar/{category}/{tenant}', [TenantController::class, 'leaveApproval'])->name('tenant.leaveApproval');

   //PROCESS
   Route::post('PengesahanKeluar/{category}/{tenant}', [TenantController::class, 'leaveApprovalProcess'])->name('tenant.leaveApprovalProcess');
});

//------------------------------------------------------------------------------------------------------------------------------------------------
// Modul Yuran Penyelenggaraan
//------------------------------------------------------------------------------------------------------------------------------------------------

//Yuran Penyelenggaraan Mengikut Kategori Lokasi
Route::group(['prefix' => 'YuranPenyelenggaraan'], function () {
    //PAGE
    Route::get('SenaraiMengikutKategoriLokasi', [MaintenanceFeeReportController::class, 'maintenanceFeeByQuartersCategoryList'])->name('maintenanceFeeByQuartersCategory.index');

});

//------------------------------------------------------------------------------------------------------------------------------------------------
// Modul Penyelenggaraan
//------------------------------------------------------------------------------------------------------------------------------------------------

//Transaksi Penyelenggaraan
Route::group(['prefix' => 'TransaksiPenyelenggaraan'], function () {
    //PAGE
     Route::get('Senarai', [MaintenanceTransactionController::class, 'index'])->name('maintenanceTransaction.index');
     Route::get('Kemaskini/{id}',[MaintenanceTransactionController::class, 'edit'])->name('maintenanceTransaction.edit');
     Route::get('Papar/{id}', [MaintenanceTransactionController::class, 'view'])->name('maintenanceTransaction.view');
     //PROCESS
     Route::post('Kemaskini', [MaintenanceTransactionController::class, 'update'])->name('maintenanceTransaction.update');
    //AJAX
    Route::get('ajaxGetMaintenanceTransactionAttachment', [MaintenanceTransactionController::class, 'ajaxGetMaintenanceTransactionAttachment'])->name('maintenanceTransaction.ajaxGetMaintenanceTransactionAttachment');
    Route::get('ajaxGetComplaintInventoryAttachmentList', [MaintenanceTransactionController::class, 'ajaxGetComplaintInventoryAttachmentList'])->name('maintenanceTransaction.ajaxGetComplaintInventoryAttachmentList');
    Route::get('ajaxGetComplaintOthersAttachmentList', [MaintenanceTransactionController::class, 'ajaxGetComplaintOthersAttachmentList'])->name('maintenanceTransaction.ajaxGetComplaintOthersAttachmentList');


 });

//------------------------------------------------------------------------------------------------------------------------------------------------
// Modul Pemantauan Berkala
//------------------------------------------------------------------------------------------------------------------------------------------------

// Rekod Pemantauan Berkala
Route::group(['prefix' => 'RekodPemantauanBerkala'], function () {
   // PAGE
   Route::get('SenaraiLokasi', [RoutineInspectionController::class, 'index'])->name('routineInspectionRecord.listLocation');
   Route::get('SenaraiPemantauan/{category}', [RoutineInspectionController::class, 'listInspection'])->name('routineInspectionRecord.listInspection');
   Route::get('RekodBaru/{category}', [RoutineInspectionController::class, 'create'])->name('routineInspectionRecord.create');
   Route::get('kemaskini/{inspection}', [RoutineInspectionController::class, 'edit'])->name('routineInspectionRecord.edit');
   Route::get('papar/{inspection}', [RoutineInspectionController::class, 'view'])->name('routineInspectionRecord.view');

   // PROSES
   Route::post('RekodBaru', [RoutineInspectionController::class, 'store'])->name('routineInspectionRecord.store');
   Route::post('kemaskini', [RoutineInspectionController::class, 'update'])->name('routineInspectionRecord.update');
   Route::delete('hapus/', [RoutineInspectionController::class, 'destroy'])->name('routineInspectionRecord.delete');

    //Ajax
   Route::post('ajaxcheckalamat', [RoutineInspectionController::class, 'ajaxcheckalamat'])->name('routineInspectionRecord.ajaxcheckalamat');

});


// Pengesahan Pemantauan Berkala
Route::group(['prefix' => 'PengesahanPemantauanBerkala'], function () {
    // PAGE
    Route::get('SenaraiPemantauan/', [RoutineInspectionApprovalController::class, 'index'])->name('routineInspectionApproval.index');
    Route::get('pengesahan/{inspectionTransaction}', [RoutineInspectionApprovalController::class, 'approval'])->name('routineInspectionApproval.edit');
    Route::get('papar/{inspectionTransaction}', [RoutineInspectionApprovalController::class, 'view'])->name('routineInspectionApproval.view');

    // PROSES
    Route::post('pengesahan', [RoutineInspectionApprovalController::class, 'approvalUpdate'])->name('routineInspectionApproval.approval');
    Route::delete('hapus/', [RoutineInspectionApprovalController::class, 'destroy'])->name('routineInspectionApproval.delete');
 });

// Transaksi Pemantauan Berkala
Route::group(['prefix' => 'TransaksiPemantauanBerkala'], function () {
    // PAGE
    Route::get('SenaraiPemantauan/', [RoutineInspectionTransactionController::class, 'index'])->name('routineInspectionTransaction.index');
    Route::get('kemaskini/{inspection}', [RoutineInspectionTransactionController::class, 'edit'])->name('routineInspectionTransaction.edit');
    Route::get('papar/{inspection}', [RoutineInspectionTransactionController::class, 'view'])->name('routineInspectionTransaction.view');

    // PROSES
    Route::post('kemaskini', [RoutineInspectionTransactionController::class, 'update'])->name('routineInspectionTransaction.update');
    Route::delete('hapus/', [RoutineInspectionTransactionController::class, 'destroy'])->name('routineInspectionTransaction.delete');
});


// Jadual Pemantauan Berkala
Route::group(['prefix' => 'JadualPemantauanBerkala'], function () {
    // PAGE
    Route::get('SenaraiPemantauan/', [RoutineInspectionScheduleController::class, 'index'])->name('routineInspectionSchedule.index');

    // AJAX
    Route::post('getSenaraiPemantauan/', [RoutineInspectionScheduleController::class, 'ajaxGetSenaraiPemantauan'])->name('routineInspectionSchedule.ajaxGetSenaraiPemantauan');

});

//------------------------------------------------------------------------------------------------------------------------------------------------
// Modul Laporan
//------------------------------------------------------------------------------------------------------------------------------------------------

//Laporan Aduan Langgar Peraturan
Route::group(['prefix' => 'LaporanAduanAwam'], function () {
    //PAGE
    Route::match(['get', 'post'], 'Senarai', [RulesViolationComplaintReportController::class, 'rulesViolationComplaintList'])->name('rulesViolationComplaintReport.index');
    Route::view('Cetak_Laporan_Langgar_Peraturan', 'modules.Report.RulesViolationComplaintReport.cetak-pdf');

});

//Laporan Aduan Kerosakan
Route::group(['prefix' => 'LaporanAduanKerosakan'], function () {
    //PAGE
    Route::match(['get', 'post'], 'Senarai', [DamageComplaintReportController::class, 'damageComplaintList'])->name('damageComplaintReport.index');
    Route::view('Cetak_Laporan_Aduan_Kerosakan', 'modules.Report.DamageComplaintReport.cetak-pdf');

});

//Laporan Aduan Kerosakan
Route::group(['prefix' => 'LaporanDendaKerosakan'], function () {
    //PAGE
    Route::get('Senarai', [TenantsPenaltyReportController::class, 'index'])->name('tenantPenaltyReport.index');
    Route::view('Cetak_Laporan_Denda_Kerosakan', 'modules.Report.tenantPenaltyReport.cetak-pdf');
});

//Laporan Kebenaran Khas
Route::group(['prefix' => 'LaporanKebenaranKhas'], function () {
    //PAGE
    Route::match(['get', 'post'], 'Senarai', [SpecialPermissionReportController::class, 'specialPermissionList'])->name('specialPermissionReport.index');
    Route::view('Cetak_Laporan_Kebenaran_Khas', 'modules.Report.SpecialPermission.cetak-pdf');

});


//Laporan Aduan Kerosakan
//Laporan Pemantauan
Route::group(['prefix' => 'LaporanPemantauan'], function () {
    //PAGE
    Route::match(['get', 'post'], 'Senarai', [MonitoringReportController::class, 'index'])->name('monitoringReport.index');
    Route::get('ajaxGetComplaintStatus', [MonitoringReportController::class, 'ajaxGetComplaintStatus'])->name('monitoringReport.ajaxGetComplaintStatus');
});

//Laporan Temujanji Aduan Kerosakan
Route::group(['prefix' => 'LaporanTemujanjiAduan'], function () {
    //PAGE
    Route::get('Senarai', [ComplaintAppointmentReportController::class, 'index'])->name('complaintAppointmentReport.index');
});

//Laporan Penyelenggaraan
Route::group(['prefix' => 'LaporanPenyelenggaraan'], function () {
    //PAGE
    Route::get('Senarai', [MaintenanceReportController::class, 'index'])->name('maintenanceReport.index');
});

//Laporan Pemantauan Berkala
Route::group(['prefix' => 'LaporanPemantauanBerkala'], function () {
    //PAGE
    Route::get('Senarai', [RoutineInspectionReportController::class, 'index'])->name('routineInspectionReport.index');
});

//Laporan Denda Hilang Kelayakan
Route::group(['prefix' => 'LaporanDendaHilangKelayakan'], function () {
    //PAGE
    Route::get('Senarai', [BlacklistPenaltyReportController::class, 'index'])->name('blacklistPenaltyReport.index');
});

//Laporan Denda Hilang Kelayakan
Route::group(['prefix' => 'LaporanDendaHilangKelayakanIndividu'], function () {
    //PAGE
    Route::get('Senarai', [IndividualBlacklistPenaltyReportController::class, 'index'])->name('individualBlacklistPenaltyReport.index');
    Route::get('ajaxCheckTenantIC', [IndividualBlacklistPenaltyController::class, 'ajaxCheckTenantIC'])->name('individualBlacklistPenaltyReport.ajaxCheckTenantIC');
});
//------------------------------------------------------------------------------------------------------------------------------------------------
//Modul Kewangan
//------------------------------------------------------------------------------------------------------------------------------------------------
//Pegawai Kewangan
Route::group(['prefix' => 'UnitKewangan'], function () {
    //PAGE
   Route::get('Senarai', [FinanceOfficerController::class, 'index'])->name('financeOfficer.index');
   Route::get('Papar/{id}', [FinanceOfficerController::class, 'view'])->name('financeOfficer.view');
   Route::get('RekodBaru', [FinanceOfficerController::class, 'create'])->name('financeOfficer.create');
   Route::get('Kemaskini/{id}', [FinanceOfficerController::class, 'edit'])->name('financeOfficer.edit');
   //PROCESS
   Route::post('Simpan', [FinanceOfficerController::class, 'store'])->name('financeOfficer.store');
   Route::post('Kemaskini', [FinanceOfficerController::class, 'update'])->name('financeOfficer.update');
   Route::delete('Hapus', [FinanceOfficerController::class, 'destroy'])->name('financeOfficer.delete');
    //AJAX
   Route::post('ajaxGetPosition', [FinanceOfficerController::class, 'ajaxGetPosition'])->name('financeOfficer.ajaxGetPosition');
   Route::get('ajaxValidateDelete', [FinanceOfficerController::class, 'validateDelete'])->name('financeOfficer.validateDelete');

});

//Maklumat Akaun Bank
Route::group(['prefix' => 'AkaunBank'], function () {
    //PAGE
   Route::get('Senarai', [BankAccountController::class, 'index'])->name('bankAccount.index');
   Route::get('Papar/{id}', [BankAccountController::class, 'view'])->name('bankAccount.view');
   Route::get('RekodBaru', [BankAccountController::class, 'create'])->name('bankAccount.create');
   Route::get('Kemaskini/{id}', [BankAccountController::class, 'edit'])->name('bankAccount.edit');
   //PROCESS
   Route::post('Simpan', [BankAccountController::class, 'store'])->name('bankAccount.store');
   Route::post('Kemaskini', [BankAccountController::class, 'update'])->name('bankAccount.update');
   Route::delete('Hapus', [BankAccountController::class, 'destroy'])->name('bankAccount.delete');
   // AJAX
   Route::get('getPaymentCategories', [BankAccountController::class, 'getPaymentCategories'])->name('bankAccount.getPaymentCategories');

});

//Maklumat Vot Hasil
Route::group(['prefix' => 'MaklumatVotHasil'], function () {
    //PAGE
   Route::get('Senarai', [IncomeAccountCodeController::class, 'index'])->name('incomeAccountCode.index');
   Route::get('Papar/{id}', [IncomeAccountCodeController::class, 'view'])->name('incomeAccountCode.view');
   Route::get('RekodBaru', [IncomeAccountCodeController::class, 'create'])->name('incomeAccountCode.create');
   Route::get('Kemaskini/{id}', [IncomeAccountCodeController::class, 'edit'])->name('incomeAccountCode.edit');
   //PROCESS
   Route::post('Simpan', [IncomeAccountCodeController::class, 'store'])->name('incomeAccountCode.store');
   Route::post('Kemaskini', [IncomeAccountCodeController::class, 'update'])->name('incomeAccountCode.update');
   Route::delete('Hapus', [IncomeAccountCodeController::class, 'destroy'])->name('incomeAccountCode.delete');
   // AJAX
   Route::get('ajaxValidateDelete', [IncomeAccountCodeController::class, 'validateDelete'])->name('incomeAccountCode.validateDelete');
});

//Maklumat Kaedah Bayaran
Route::group(['prefix' => 'KaedahBayaran'], function () {
    //PAGE
   Route::get('Senarai', [PaymentMethodController::class, 'index'])->name('paymentMethod.index');
   Route::get('Papar/{id}', [PaymentMethodController::class, 'view'])->name('paymentMethod.view');
   Route::get('RekodBaru', [PaymentMethodController::class, 'create'])->name('paymentMethod.create');
   Route::get('Kemaskini/{id}', [PaymentMethodController::class, 'edit'])->name('paymentMethod.edit');
   //PROCESS
   Route::post('Simpan', [PaymentMethodController::class, 'store'])->name('paymentMethod.store');
   Route::post('Kemaskini', [PaymentMethodController::class, 'update'])->name('paymentMethod.update');
   Route::delete('Hapus', [PaymentMethodController::class, 'destroy'])->name('paymentMethod.delete');
   // AJAX
   Route::get('ajaxValidateDelete', [PaymentMethodController::class, 'validateDelete'])->name('paymentMethod.validateDelete');

});

//Jadual Notis Bayaran
Route::group(['prefix' => 'JadualNotisBayaran'], function () {
   //PAGE
   Route::get('SenaraiTahun', [PaymentNoticeScheduleController::class, 'listYear'])->name('paymentNoticeSchedule.listYear');
   Route::get('Papar/{year}', [PaymentNoticeScheduleController::class, 'view'])->name('paymentNoticeSchedule.view');
   Route::get('Kemaskini/{year}', [PaymentNoticeScheduleController::class, 'edit'])->name('paymentNoticeSchedule.edit');
   //PROCESS
   Route::post('Kemaskini', [PaymentNoticeScheduleController::class, 'update'])->name('paymentNoticeSchedule.update');
});

//Transaksi Notis Bayaran
Route::group(['prefix' => 'TransaksiNotisBayaran'], function () {
   //PAGE
   Route::get('SenaraiTahun', [PaymentNoticeTransactionController::class, 'listYear'])->name('paymentNoticeTransaction.listYear');
   Route::get('SenaraiJadualNotisBayaran', [PaymentNoticeTransactionController::class, 'listPaymentNoticeSchedule'])->name('paymentNoticeTransaction.listPaymentNoticeSchedule');
   Route::get('SenaraiLokasiBerpenghuni', [PaymentNoticeTransactionController::class, 'listQuartersCategoryWithTenant'])->name('paymentNoticeTransaction.listQuartersCategoryWithTenant');
   Route::match(['get', 'post'], 'SenaraiPenyewa', [PaymentNoticeTransactionController::class, 'listTenant'])->name('paymentNoticeTransaction.listTenant');
   //PROCESS
   Route::post('Proses', [PaymentNoticeTransactionController::class, 'process'])->name('paymentNoticeTransaction.process');
});

//Notis Bayaran Agensi
Route::group(['prefix' => 'NotisBayaranAgensi'], function () {
    //PAGE
    Route::get('SenaraiTahun', [AgencyPaymentNoticeController::class, 'listYear'])->name('agencyPaymentNotice.listYear');
    Route::get('SenaraiNotisBayaran', [AgencyPaymentNoticeController::class, 'listPaymentNotice'])->name('agencyPaymentNotice.listPaymentNotice');
    Route::get('SenaraiAgensi', [AgencyPaymentNoticeController::class, 'listAgencyWithTenant'])->name('agencyPaymentNotice.listAgencyWithTenant');
    Route::match(['get', 'post'], 'SenaraiPenyewa', [AgencyPaymentNoticeController::class, 'listTenant'])->name('agencyPaymentNotice.listTenant');
    Route::get('CetakPdf', [AgencyPaymentNoticeController::class, 'listTenantPdf'])->name('agencyPaymentNotice.listTenantPdf');
});

//Penyesuaian Akaun iSPEKS
Route::group(['prefix' => 'PenyesuaianAkauniSPEKS'], function () {
    //PAGE
    Route::get('Senarai', [AccountReconciliationIspeksController::class, 'listYearMonth'])->name('accountReconciliationIspeks.listYearMonth');
    Route::get('SenaraiTransaksi', [AccountReconciliationIspeksController::class, 'listTransaction'])->name('accountReconciliationIspeks.listTransaction');
    Route::get('RekodBaru', [AccountReconciliationIspeksController::class, 'create'])->name('accountReconciliationIspeks.create');
    Route::get('Kemaskini', [AccountReconciliationIspeksController::class, 'edit'])->name('accountReconciliationIspeks.edit');
    Route::get('Pengesahan', [AccountReconciliationIspeksController::class, 'approval'])->name('accountReconciliationIspeks.approval');
    Route::get('Papar', [AccountReconciliationIspeksController::class, 'view'])->name('accountReconciliationIspeks.view');
    Route::get('Hapus', [AccountReconciliationIspeksController::class, 'delete'])->name('accountReconciliationIspeks.delete');
    Route::delete('HapusByRow', [AccountReconciliationIspeksController::class, 'deleteByRow'])->name('accountReconciliationIspeks.deleteByRow');
    //PROCESS
    Route::post('ProsesFail', [AccountReconciliationIspeksController::class, 'processFile'])->name('accountReconciliationIspeks.processFile');
    Route::post('ProsesPenyesuaian', [AccountReconciliationIspeksController::class, 'processPayment'])->name('accountReconciliationIspeks.processPayment');

});

//Penyesuaian Akaun iGFMAS
Route::group(['prefix' => 'PenyesuaianAkauniGFMAS'], function () {
    //PAGE
    Route::get('Senarai', [AccountReconciliationIgfmasController::class, 'listYearMonth'])->name('accountReconciliationIgfmas.listYearMonth');
    Route::get('SenaraiTransaksi', [AccountReconciliationIgfmasController::class, 'listTransaction'])->name('accountReconciliationIgfmas.listTransaction');
    Route::get('RekodBaru', [AccountReconciliationIgfmasController::class, 'create'])->name('accountReconciliationIgfmas.create');
    Route::get('Kemaskini', [AccountReconciliationIgfmasController::class, 'edit'])->name('accountReconciliationIgfmas.edit');
    Route::get('Pengesahan', [AccountReconciliationIgfmasController::class, 'approval'])->name('accountReconciliationIgfmas.approval');
    Route::get('Papar', [AccountReconciliationIgfmasController::class, 'view'])->name('accountReconciliationIgfmas.view');
    Route::get('Hapus', [AccountReconciliationIgfmasController::class, 'delete'])->name('accountReconciliationIgfmas.delete');
    Route::delete('HapusByRow', [AccountReconciliationIgfmasController::class, 'deleteByRow'])->name('accountReconciliationIgfmas.deleteByRow');
    Route::get('CetakResit', [AccountReconciliationIgfmasController::class, 'getPaymentReceipt'])->name('accountReconciliationIgfmas.getPaymentReceipt');
    //PROCESS
    Route::post('ProsesFail', [AccountReconciliationIgfmasController::class, 'processFile'])->name('accountReconciliationIgfmas.processFile');
    Route::post('ProsesPenyesuaian', [AccountReconciliationIgfmasController::class, 'processPayment'])->name('accountReconciliationIgfmas.processPayment');

});

//Rekod Bayaran
Route::group(['prefix' => 'RekodBayaran'], function () {
    Route::match(['get', 'post'], 'Senarai', [PaymentRecordController::class, 'index'])->name('paymentRecord.index');
});


//PenyataPemungut
Route::group(['prefix' => 'PenyataPemungut'], function () {
   //PAGE
    Route::match(['get', 'post'], 'Senarai', [CollectorStatementController::class, 'index'])->name('collectorStatement.index');
    Route::get('RekodBaru', [CollectorStatementController::class, 'create'])->name('collectorStatement.create');
    Route::get('Kemaskini/{id}/{tab}', [CollectorStatementController::class, 'edit'])->name('collectorStatement.edit');
    Route::match(['get', 'post'] , 'Cetak/{id}', [CollectorStatementController::class, 'generate_pdf'])->name('collectorStatement.generate_pdf');
    //PROCESS
    Route::post('Simpan', [CollectorStatementController::class, 'store'])->name('collectorStatement.store');
    Route::post('Kemaskini', [CollectorStatementController::class, 'update'])->name('collectorStatement.update');
    Route::delete('Batal', [CollectorStatementController::class, 'cancel'])->name('collectorStatement.cancel');
    //AJAX
    Route::get('get-maklumat-bank', [CollectorStatementController::class, 'get_maklumat_bank'])->name('collectorStatement.get_maklumat_bank');
    Route::get('get-kutipan-hasil-by-vot', [CollectorStatementController::class, 'get_kutipan_hasil_by_vot'])->name('collectorStatement.get_kutipan_hasil_by_vot');
    Route::get('senarai-kutipan-penyata-pemungut', [CollectorStatementController::class, 'get_senarai_kutipan_penyata_pemungut'])->name('collectorStatement.get_senarai_kutipan_penyata_pemungut');

});

//JurnalPelarasan
Route::group(['prefix' => 'JurnalPelarasan'], function () {
    //PAGE
   Route::match(['get', 'post'], 'Senarai', [JournalAdjustmentController::class, 'index'])->name('journalAdjustment.index');
   Route::get('RekodBaru', [JournalAdjustmentController::class, 'create'])->name('journalAdjustment.create');
   Route::get('Kemaskini/{id}/{tab}', [JournalAdjustmentController::class, 'edit'])->name('journalAdjustment.edit');
   Route::get('Cetak/{id}', [JournalAdjustmentController::class, 'generate_pdf'])->name('journalAdjustment.generate_pdf');
   //PROCESS
   Route::post('Simpan', [JournalAdjustmentController::class, 'store'])->name('journalAdjustment.store');
   Route::post('Kemaskini', [JournalAdjustmentController::class, 'update'])->name('journalAdjustment.update');
   Route::delete('Batal', [JournalAdjustmentController::class, 'cancel'])->name('journalAdjustment.cancel');
   Route::delete('HapusByRow', [JournalAdjustmentController::class, 'destroyByRow'])->name('journalAdjustment.deleteByRow');
   //AJAX
   Route::get('get_senarai_vot_akaun', [JournalAdjustmentController::class, 'get_senarai_vot_akaun'])->name('journalAdjustment.get_senarai_vot_akaun');
});

//JurnalPelarasanDalaman
Route::group(['prefix' => 'JurnalPelarasanDalaman'], function () {
    //PAGE
   Route::match(['get', 'post'], 'Senarai', [InternalJournalAdjustmentController::class, 'index'])->name('internalJournalAdjustment.index');
   Route::get('RekodBaru', [InternalJournalAdjustmentController::class, 'create'])->name('internalJournalAdjustment.create');
   Route::get('Kemaskini/{id}/{tab}', [InternalJournalAdjustmentController::class, 'edit'])->name('internalJournalAdjustment.edit');
   Route::get('Cetak/{id}', [InternalJournalAdjustmentController::class, 'generate_pdf'])->name('internalJournalAdjustment.generate_pdf');
   //PROCESS
   Route::post('Simpan', [InternalJournalAdjustmentController::class, 'store'])->name('internalJournalAdjustment.store');
   Route::post('Kemaskini', [InternalJournalAdjustmentController::class, 'update'])->name('internalJournalAdjustment.update');
   Route::delete('Batal', [InternalJournalAdjustmentController::class, 'cancel'])->name('internalJournalAdjustment.cancel');
   Route::delete('HapusByRow', [InternalJournalAdjustmentController::class, 'destroyByRow'])->name('internalJournalAdjustment.deleteByRow');
   //AJAX
   Route::get('get_senarai_vot_akaun', [InternalJournalAdjustmentController::class, 'get_senarai_vot_akaun'])->name('internalJournalAdjustment.get_senarai_vot_akaun');
   Route::get('ajaxGetTenant', [InternalJournalAdjustmentController::class, 'ajaxGetTenant'])->name('internalJournalAdjustment.ajaxGetTenant');

});

//Integrasi Ispek - Incoming
Route::group(['prefix' => 'IntegrasiIspeksIncoming'], function () {
   //PAGE
   Route::match(['get', 'post'], 'Senarai', [IspeksIntegrationController::class, 'transaction_list_in'])->name('ispeksIntegrationIncoming.index');
   //PROCESS
   Route::post('ProsesIn', [IspeksIntegrationController::class, 'process_incoming'])->name('ispeksIntegrationIncoming.process');
});

//Integrasi Ispek - Outgoing
Route::group(['prefix' => 'IntegrasiIspeksOutgoing'], function () {
    //PAGE
    Route::match(['get', 'post'], 'Senarai', [IspeksIntegrationController::class, 'transaction_list_out'])->name('ispeksIntegrationOutgoing.index');
    //PROCESS
    Route::post('ProsesOut', [IspeksIntegrationController::class, 'process_outgoing'])->name('ispeksIntegrationOutgoing.process');
});

//Integrasi Ispek
Route::group(['prefix' => 'IntegrasiIspeks'], function () {
    //PAGE
   Route::match(['get', 'post'], 'Senarai', [IspeksIntegrationController::class, 'ispeks_integration_list'])->name('ispeksIntegration.index');
});

//------------------------------------------------------------------------------------------------------------------------------------------------
//Modul Denda
//------------------------------------------------------------------------------------------------------------------------------------------------

//Denda
Route::group(['prefix' => 'Denda'], function () {
    //PAGE
   Route::get('Senarai', [PenaltyController::class, 'index'])->name('penalty.index');
   Route::get('SenaraiDenda/{category}', [PenaltyController::class, 'list_penalty'])->name('penalty.penaltyList');
   Route::get('Papar/{category}/{id}', [PenaltyController::class, 'view'])->name('penalty.view');
   Route::get('RekodBaru/{category}', [PenaltyController::class, 'create'])->name('penalty.create');
   Route::get('Kemaskini/{category}/{id}', [PenaltyController::class, 'edit'])->name('penalty.edit');
   //PROCESS
   Route::post('Simpan', [PenaltyController::class, 'store'])->name('penalty.store');
   Route::post('Kemaskini', [PenaltyController::class, 'update'])->name('penalty.update');
   Route::delete('Hapus', [PenaltyController::class, 'destroy'])->name('penalty.delete');
    //AJAX
    Route::post('ajaxCheckTenantIC', [PenaltyController::class, 'ajaxCheckTenantIC'])->name('penalty.ajaxCheckTenantIC');
});


//Kadar Denda Hilang Kelayakan
Route::group(['prefix' => 'KadarDendaHilangKelayakan'], function () {
    //PAGE
   Route::get('Senarai', [BlacklistPenaltyRateController::class, 'index'])->name('blacklistPenaltyRate.index');
   Route::get('RekodBaru', [BlacklistPenaltyRateController::class, 'create'])->name('blacklistPenaltyRate.create');
   Route::get('Papar/{bpr}', [BlacklistPenaltyRateController::class, 'view'])->name('blacklistPenaltyRate.view');
   Route::get('Kemaskini/{bpr}', [BlacklistPenaltyRateController::class, 'edit'])->name('blacklistPenaltyRate.edit');
   //PROCESS
   Route::post('Simpan', [BlacklistPenaltyRateController::class, 'store'])->name('blacklistPenaltyRate.store');
   Route::post('Kemaskini', [BlacklistPenaltyRateController::class, 'update'])->name('blacklistPenaltyRate.update');
   Route::delete('Hapus', [BlacklistPenaltyRateController::class, 'destroy'])->name('blacklistPenaltyRate.destroy');
   Route::post('HapusKadar', [BlacklistPenaltyRateController::class, 'destroyRate'])->name('blacklistPenaltyRate.destroyRate');
});

//Denda Hilang Kelayakan
Route::group(['prefix' => 'DendaHilangKelayakan'], function () {
    //PAGE
   Route::get('Senarai', [BlacklistPenaltyController::class, 'index'])->name('blacklistPenalty.index');
   Route::get('SenaraiDenda/{category}', [BlacklistPenaltyController::class, 'penalty_list'])->name('blacklistPenalty.penaltyList');
   Route::get('RekodBaru/{category}', [BlacklistPenaltyController::class, 'create'])->name('blacklistPenalty.create');
   Route::get('Papar/{category}/{bp}', [BlacklistPenaltyController::class, 'view'])->name('blacklistPenalty.view');
   Route::get('Kemaskini/{category}/{bp}', [BlacklistPenaltyController::class, 'edit'])->name('blacklistPenalty.edit');
   //PROCESS
   Route::post('Simpan', [BlacklistPenaltyController::class, 'store'])->name('blacklistPenalty.store');
   Route::post('Kemaskini', [BlacklistPenaltyController::class, 'update'])->name('blacklistPenalty.update');
   Route::delete('Hapus', [BlacklistPenaltyController::class, 'destroy'])->name('blacklistPenalty.destroy');
   Route::post('HapusKadar', [BlacklistPenaltyController::class, 'destroyRate'])->name('blacklistPenalty.destroyRate');
    // AJAX
   Route::post('ajaxGetRate', [BlacklistPenaltyController::class, 'ajaxGetRate'])->name('blacklistPenalty.ajaxGetRate');
   Route::get('ajaxCheckTenantIC', [BlacklistPenaltyController::class, 'ajaxCheckTenantIC'])->name('blacklistPenalty.ajaxCheckTenantIC');
});


//------------------------------------------------------------------------------------------------------------------------------------------------
// Modul Laporan Kewangan
//------------------------------------------------------------------------------------------------------------------------------------------------

//Laporan Penyata Pemungut (PP)
Route::group(['prefix' => 'LaporanPenyataPemungut'], function () {
    Route::match(['get', 'post'], 'Senarai', [CollectorStatementReportController::class, 'index'])->name('collectorStatementReport.index');
});

//Laporan Terimaan Hasil (Sales Report)
Route::group(['prefix' => 'LaporanTerimaanHasil'], function () {
    Route::match(['get', 'post'], 'Senarai', [SalesReportController::class, 'index'])->name('salesReport.index');
});

//Laporan Jurnal Pelarasan (Journal Report)
Route::group(['prefix' => 'LaporanJurnalPelarasan'], function () {
    Route::match(['get', 'post'], 'Senarai', [JournalReportController::class, 'index'])->name('journalReport.index');
});

//Laporan Jurnal Pelarasan Dalaman
Route::group(['prefix' => 'LaporanJurnalPelarasanDalaman'], function () {
    Route::match(['get', 'post'], 'Senarai', [InternalJournalReportController::class, 'index'])->name('internalJournalReport.index');
});

//Laporan Ringkasan Terimaan Hasil
Route::group(['prefix' => 'LaporanRingkasanTerimaanHasil'], function () {
    Route::match(['get', 'post'], 'Senarai', [SalesSummaryReportController::class, 'index'])->name('salesSummaryReport.index');
});

//Laporan Perbandingan Yuran Penyelenggaraan
Route::group(['prefix' => 'PerbandinganYuranPenyelenggaraan'], function () {
    Route::match(['get', 'post'], 'Senarai', [MaintenanceFeeComparisonReportController::class, 'index'])->name('maintenanceFeeComparisonReport.index');
});

//Laporan Laporan Penyata Individu
Route::group(['prefix' => 'LaporanPenyataIndividu'], function () {
    Route::match(['get', 'post'], 'Senarai', [IndividualStatementReportController::class, 'index'])->name('individualStatementReport.index');
});

//Laporan Prestasi Terimaan Hasil
Route::group(['prefix' => 'LaporanPrestasiTerimaanHasil'], function () {
    Route::match(['get', 'post'], 'Senarai', [SalesPerformanceReportController::class, 'index'])->name('salesPerformanceReport.index');
});

//Laporan Anggaran Terimaan Hasil
Route::group(['prefix' => 'LaporanAnggaranTerimaanHasil'], function () {
    Route::match(['get', 'post'], 'Senarai', [SalesEstimationReportController::class, 'index'])->name('salesEstimationReport.index');
});

//Laporan Buku Tunai
Route::group(['prefix' => 'LaporanBukuTunai'], function () {
    Route::match(['get', 'post'], 'Senarai', [CashBookReportController::class, 'index'])->name('cashBookReport.index');
});

Route::group(['prefix' => 'LaporanNotisBayaran'], function () {
    Route::match(['get', 'post'], 'Senarai', [NoticePaymentReportController::class, 'index'])->name('noticePaymentReport.index');
});

//------------------------------------------------------------------------------------------------------------------------------------------------
//Modul Analisis
//------------------------------------------------------------------------------------------------------------------------------------------------
Route::group(['prefix' => 'AnalisisAduanAwam'], function () {
   Route::get('AduanAwam', [RulesViolationComplaintAnalysisController::class, 'index'])->name('rulesViolationComplaintAnalysis.index');
});

Route::group(['prefix' => 'AnalisisAduanKerosakan'], function () {
   Route::get('AduanKerosakan', [DamageComplaintAnalysisController::class, 'index'])->name('damageComplaintAnalysis.index');
});

Route::group(['prefix' => 'AnalisisPermohonanKuarters'], function () {
    Route::get('PermohonanKuarters', [QuartersApplicationAnalysisController::class, 'index'])->name('quartersApplicationAnalysis.index');
});

Route::group(['prefix' => 'AnalisisMaklumatKuarters'], function () {
    Route::get('MaklumatKuarters', [QuartersInfoAnalysisController::class, 'index'])->name('quartersInfoAnalysis.index');
});

Route::group(['prefix' => 'LaporanDinamik'], function () {
    //PAGE
    Route::get('Laporan', [DynamicReportingController::class, 'index'])->name('dynamicReport.index');
    Route::post('ajaxGetReport', [DynamicReportingController::class, 'ajaxGetReport'])->name('dynamicReport.ajaxGetReport');
    Route::post('ajaxGetQuartersCategory', [DynamicReportingController::class, 'ajaxGetQuartersCategory'])->name('dynamicReport.ajaxGetQuartersCategory');

   //PROCESS
   Route::post('Laporan', [DynamicReportingController::class, 'report'])->name('dynamicReport.report');

});

//------------------------------------------------------------------------------------------------------------------------------------------------
//Modul Kawalan Audit
//------------------------------------------------------------------------------------------------------------------------------------------------

//Kawalan Audit
Route::group(['prefix' => 'KawalanAudit'], function () {
    Route::match(['get', 'post'], 'Senarai', [AuditTrailController::class, 'index'])->name('auditTrail.index');
    Route::get('Papar/{id}', [AuditTrailController::class, 'view'])->name('auditTrail.view');
    //AJAX
    Route::post('ajaxGetSubmodule', [AuditTrailController::class, 'ajaxGetSubmodule'])->name('auditTrail.ajaxGetSubmodule');

});
