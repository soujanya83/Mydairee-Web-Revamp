<?php

use App\Http\Controllers\AccidentsController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileManagerController;



Route::get('/', function () {
    return view('dashboard.university');
});
/* Dashboard */
Route::get('dashboard', function () {
    return redirect('dashboard/analytical');
});
Route::get('dashboard/university', [DashboardController::class, 'university'])->name('dashboard.university');
Route::get('dashboard/analytical', [DashboardController::class, 'analytical'])->name('dashboard.analytical');
Route::get('file-manager/dashboard', [FileManagerController::class, 'dashboard'])->name('file-manager.dashboard');
Route::get('app/calendar', [AppController::class, 'calendar'])->name('app.calendar');
Route::get('app/chat', [AppController::class, 'chat'])->name('app.chat');
Route::get('app/inbox', [AppController::class, 'inbox'])->name('app.inbox');
Route::get('authentication/login', [AuthenticationController::class, 'login'])->name('authentication.login');
Route::get('pages/profile1', [PagesController::class, 'profile1'])->name('pages.profile1');
Route::get('authentication/forgot-password', [AuthenticationController::class, 'forgotPassword'])->name('authentication.forgot-password');
Route::get('authentication/register', [AuthenticationController::class, 'register'])->name('authentication.register');

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
