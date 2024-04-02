<?php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;

use Illuminate\Support\Facades\Route;



Route::middleware('guest')->group(function () {

    Route::get('login', [LoginController::class, 'index'])->name('login');
    Route::post('login/check', [LoginController::class, 'auth'])->name('login.check');
    // Route::get('register', [RegisterController::class, 'index'])->name('register');
    // Route::post('register/store', [RegisterController::class, 'store'])->name('register.store');
    Route::post('ajaxCheckIc', [RegisterController::class, 'ajaxCheckIc'])->name('ajaxCheckIc');
    Route::post('ajaxProcessDataHrmis', [RegisterController::class, 'ajaxProcessDataHrmis'])->name('ajaxProcessDataHrmis');
    Route::post('ajaxGetDataUsers', [RegisterController::class, 'ajaxGetDataUsers'])->name('ajaxGetDataUsers');
    Route::post('ajaxGetDataPositionType', [RegisterController::class, 'ajaxGetDataPositionType'])->name('ajaxGetDataPositionType');
    Route::post('ajaxGetDataServiceType', [RegisterController::class, 'ajaxGetDataServiceType'])->name('ajaxGetDataServiceType');
    Route::post('ajaxGetDataUsersOffice', [RegisterController::class, 'ajaxGetDataUsersOffice'])->name('ajaxGetDataUsersOffice');

    Route::get('lupa/katalaluan', [ForgotPasswordController::class, 'index'])->name('forgotPassword');
    Route::post('lupa/katalaluan/send_link', [ForgotPasswordController::class, 'send_link'])->name('forgotPassword.sendLink');

    Route::get('reset/katalaluan/{token}', [ResetPasswordController::class, 'index'])->name('setPassword');
    Route::post('reset/katalaluan/store/{token}', [ResetPasswordController::class, 'store'])->name('setPassword.store');

});

Route::middleware('auth')->group(function () {

    Route::get('Pengguna', [UserController::class, 'view_by_user'])->name('user.viewByUser');

    Route::get('/', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('submenu/{mid}', [HomeController::class, 'submenu'])->name('submenu');

    Route::get('{any}', [HomeController::class, 'index'])->name('index');

    Route::name('logout')->post('logout', [LoginController::class, 'logout']);

    //------------------------------------------------------------------------------------------------------------------------------------------------
    // Dashboard
    //------------------------------------------------------------------------------------------------------------------------------------------------
    // Ajax Dashboard
    Route::post('ajaxGetQuartersByCondition', [DashboardController::class, 'ajaxGetQuartersByCondition'])->name('dashboard.ajaxGetQuartersByCondition');
    Route::post('ajaxGetQuartersNoByCondition', [DashboardController::class, 'ajaxGetQuartersNoByCondition'])->name('dashboard.ajaxGetQuartersNoByCondition');
    Route::post('ajaxGetQuartersAvailability', [DashboardController::class, 'ajaxGetQuartersAvailability'])->name('dashboard.ajaxGetQuartersAvailability');
    Route::post('ajaxGetQuartersTotal', [DashboardController::class, 'ajaxGetQuartersTotal'])->name('dashboard.ajaxGetQuartersTotal');
    Route::post('ajaxGetTenantAll', [DashboardController::class, 'ajaxGetQuartersWithTenant'])->name('dashboard.ajaxGetTenantAll');
    Route::post('ajaxGetComplaint', [DashboardController::class, 'ajaxGetComplaint'])->name('dashboard.ajaxGetComplaint');

});
