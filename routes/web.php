<?php

use App\Http\Controllers\AccidentsController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileManagerController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('dashboard.university');
});

/* Dashboard */
Route::get('dashboard', function () {
    return redirect('dashboard/analytical');
});


Route::get('/username-suggestions', [UserController::class, 'getUsernameSuggestions']);
Route::get('/check-username-exists', [UserController::class, 'checkUsernameExists']);
Route::get('/', [DashboardController::class, 'university'])->name('dashboard.university');
Route::get('dashboard/analytical', [DashboardController::class, 'analytical'])->name('dashboard.analytical');
Route::get('file-manager/dashboard', [FileManagerController::class, 'dashboard'])->name('file-manager.dashboard');
Route::get('app/calendar', [AppController::class, 'calendar'])->name('app.calendar');
Route::get('app/chat', [AppController::class, 'chat'])->name('app.chat');
Route::get('app/inbox', [AppController::class, 'inbox'])->name('app.inbox');
Route::get('login', [AuthenticationController::class, 'login'])->name('authentication.login');
Route::get('pages/profile1', [PagesController::class, 'profile1'])->name('pages.profile1');
Route::get('authentication/forgot-password', [AuthenticationController::class, 'forgotPassword'])->name('authentication.forgot-password');
Route::get('register', [AuthenticationController::class, 'register'])->name('authentication.register');

Route::post('create-superadmin', [UserController::class, 'store'])->name('create_superadmin');
Route::post('login', [UserController::class, 'login'])->name('user_login');
Route::get('create-center', [UserController::class, 'create_center'])->name('create_center');
Route::post('store-center', [UserController::class, 'store_center'])->name('center_store');
Route::post('reset-password', [ResetPassword::class, 'reset_password'])->name('reset_password');
Route::get('verify-otp', [ResetPassword::class, 'show_verify_otp'])->name('verify_otp');
// Route::post('verify-otp', [ResetPassword::class, 'verify_otp'])->name('verify_otp.submit');
Route::get('/reset-password-form', [ResetPassword::class, 'showResetForm'])->name('reset_password_form');
Route::post('/reset-password-update', [ResetPassword::class, 'updatePassword'])->name('reset_password.update');

Route::post('/verify-otp', [ResetPassword::class, 'verifyOtp'])->name('verify_otp.submit');








Route::get('data',[AccidentsController::class,'index']);
/* Dashboard */
Route::get('dashboard', function () {
    return redirect('dashboard/analytical');
});
/* App */
Route::get('app', function () {
    return redirect('app/inbox');
});
/* File Manager */
Route::get('file-manager', function () {
    return redirect('file-manager/dashboard');
});
/* Blog */
Route::get('blog', function () {
    return redirect('blog/dashboard');
});
/* UI Elements */
Route::get('ui-elements', function () {
    return redirect('ui-elements/typography');
});
/* Widgets */
Route::get('widgets', function () {
    return redirect('widgets/statistics');
});
/* Authentication */
Route::get('authentication', function () {
    return redirect('authentication/login');
});
/* Pages */
Route::get('pages', function () {
    return redirect('pages/blank-page');
});
/* Forms */
Route::get('forms', function () {
    return redirect('forms/advance-elements');
});
/* Forms */
Route::get('table', function () {
    return redirect('table/basic');
});
/* Charts */
Route::get('charts', function () {
    return redirect('charts/morris');
});
/* Maps */
Route::get('map', function () {
    return redirect('map/google');
});




Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/superadmin_settings', [SettingsController::class, 'superadminSettings'])->name('superadmin_settings');
 
    Route::delete('/superadmin/{id}', [SettingsController::class, 'destroy'])->name('superadmin.destroy');
    
    Route::post('/superadmin/store', [SettingsController::class, 'store'])->name('superadmin.store');

    Route::get('/superadmin/{id}/edit', [SettingsController::class, 'edit'])->name('superadmin.edit');
    Route::post('/superadmin/{id}', [SettingsController::class, 'update'])->name('superadmin.update');



    Route::get('/center_settings', [SettingsController::class, 'center_settings'])->name('center_settings');




});