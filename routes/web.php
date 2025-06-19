<?php

use App\Http\Controllers\AccidentsController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DailyDiaryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileManagerController;
use App\Http\Controllers\LessonPlanList;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ClearCacheAfterLogout;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ServiceDetailsController;
use App\Models\Child;
use App\Http\Controllers\ObservationController;
use App\Http\Controllers\SurveyController;

// Route::get('/', function () {
//     return view('dashboard.university');
// });


// Route::get('dashboard', function () {
//     return redirect('dashboard/analytical');
// });


Route::get('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('login');
})->name('logout');

Route::get('/username-suggestions', [UserController::class, 'getUsernameSuggestions']);
Route::get('/check-username-exists', [UserController::class, 'checkUsernameExists']);
Route::get('dashboard/analytical', [DashboardController::class, 'analytical'])->name('dashboard.analytical');
Route::get('file-manager/dashboard', [FileManagerController::class, 'dashboard'])->name('file-manager.dashboard');
Route::get('app/calendar', [AppController::class, 'calendar'])->name('app.calendar');
Route::get('app/chat', [AppController::class, 'chat'])->name('app.chat');
Route::get('app/inbox', [AppController::class, 'inbox'])->name('app.inbox');
Route::get('pages/profile1', [PagesController::class, 'profile1'])->name('pages.profile1');

Route::post('create-superadmin', [UserController::class, 'store'])->name('create_superadmin');
Route::post('login', [UserController::class, 'login'])->name('user_login');
Route::get('create-center', [UserController::class, 'create_center'])->name('create_center');
Route::post('store-center', [UserController::class, 'store_center'])->name('center_store');
Route::post('reset-password', [ResetPassword::class, 'reset_password'])->name('reset_password');
Route::get('verify-otp', [ResetPassword::class, 'show_verify_otp'])->name('verify_otp');
// Route::post('verify-otp', [ResetPassword::class, 'verify_otp'])->name('verify_otp.submit');
Route::get('/reset-password-form', [ResetPassword::class, 'showResetForm'])->name('reset_password_form');
Route::post('/reset-password-update', [ResetPassword::class, 'updatePassword'])->name('reset_password.update');
Route::post('/resend-otp', [ResetPassword::class, 'resend_otp'])->name('resend_otp');
Route::post('/verify-otp', [ResetPassword::class, 'verifyOtp'])->name('verify_otp.submit');
Route::get('register', [AuthenticationController::class, 'register'])->name('authentication.register');
Route::get('authentication/forgot-password', [AuthenticationController::class, 'forgotPassword'])->name('authentication.forgot-password');
Route::get('login-page', [AuthenticationController::class, 'login'])->name('login');
Route::get('login', [AuthenticationController::class, 'login'])->name('authentication.login');




// Route group with middleware this middleware use after login
Route::middleware(['web', 'auth', ClearCacheAfterLogout::class])->group(function () {
    Route::get('/', [DashboardController::class, 'university'])->name('dashboard.university');
    // service details 
    Route::get('ServiceDetails', [ServiceDetailsController::class, 'create'])->name('create.serviceDetails');
    Route::post('ServiceDetails', [ServiceDetailsController::class, 'store'])->name('store.serviceDetails');


    Route::get('programPlanList',[LessonPlanList::class,'programPlanList'])->name('programPlanList');
    Route::get('programPlan/create',[LessonPlanList::class,'createForm'])->name('create.programplan');
    Route::post('LessonPlanList/deletedataofprogramplan',[LessonPlanList::class,'deleteProgramPlan'])->name('LessonPlanList.deletedataofprogramplan');
    
    Route::get('programPlan/print/{planId}', [LessonPlanList::class, 'programplanprintpage'])->name('print.programplan');
// ajax 
        Route::post('LessonPlanList/get_room_users', [LessonPlanList::class, 'getRoomUsers'])->name('LessonPlanList.get_room_users');
    Route::post('LessonPlanList/get_room_children', [LessonPlanList::class, 'getRoomChildren'])->name('LessonPlanList.get_room_children');
        Route::post('LessonPlanList/save_program_planinDB', [LessonPlanList::class, 'saveProgramPlan'])->name('LessonPlanList.save_program_planinDB');
    // ajax ends 
    Route::post('programPlan',[LessonPlanList::class,'store'])->name('store.programPlan');

    Route::post('Observation/addActivity',[ObservationController::class,'addActivity'])->name('Observation.addActivity');
Route::post('Observation/addSubActivity',[ObservationController::class,'addSubActivity'])->name(' Observation.addSubActivity');

Route::get('announcements/list',[AnnouncementController::class,'list'])->name('announcements.list');
Route::get('announcements/create',[AnnouncementController::class,'AnnouncementCreate'])->name('announcements.create');
Route::get('announcements/store',[AnnouncementController::class,'AnnouncementStore'])->name('announcements.store');
Route::get('announcements/delete',[AnnouncementController::class,'AnnouncementCreate'])->name('announcements.delete');

Route::get('surveys/list',[SurveyController::class,'list'])->name('survey.list');

// Daily Journel here
Route::get('DailyDiary/list',[DailyDiaryController::class,'list'])->name('dailyDiary.list');
Route::post('dailyDiary/storeBottle',[DailyDiaryController::class,'storeBottle'])->name('dailyDiary.storeBottle');
Route::post('dailyDiary/storeFood',[DailyDiaryController::class,'storeFood'])->name('dailyDiary.storeFood');
Route::post('dailyDiary.storeSleep',[DailyDiaryController::class,'storeSleep'])->name('dailyDiary.storeSleep');
Route::post('dailyDiary/storeToileting',[DailyDiaryController::class,'storeToileting'])->name('dailyDiary.storeToileting');

Route::post('dailyDiary/storeSunscreen',[DailyDiaryController::class,'storeSunscreen'])->name('dailyDiary.storeSunscreen');
Route::post('dailyDiary/getItems',[DailyDiaryController::class,'getItems'])->name('dailyDiary.getItems');
Route::post('dailyDiary/addFoodRecord',[DailyDiaryController::class,'addFoodRecord'])->name('dailyDiary.addFoodRecord');

Route::post('dailyDiary/addSleepRecord',[DailyDiaryController::class,'addSleepRecord'])->name('dailyDiary.addSleepRecord');
Route::post('dailyDiary/addToiletingRecord',[DailyDiaryController::class,'addToiletingRecord'])->name('dailyDiary.addToiletingRecord');
Route::post('dailyDiary/addSunscreenRecord',[DailyDiaryController::class,'addSunscreenRecord'])->name('dailyDiary.addSunscreenRecord');
Route::post('dailyDiary/addBottle',[DailyDiaryController::class,'addBottle'])->name('dailyDiary.addBottle');
Route::post('dailyDiary/deleteBottleTime',[DailyDiaryController::class,'deleteBottleTime'])->name('dailyDiary.deleteBottleTime');

Route::post('dailyDiary/updateBottleTimes',[DailyDiaryController::class,'updateBottleTimes'])->name('dailyDiary.updateBottleTimes');
Route::post('dailyDiary/addToiletingRecord',[DailyDiaryController::class,'addToiletingRecord'])->name('dailyDiary.addToiletingRecord');
Route::post('dailyDiary/addSunscreenRecord',[DailyDiaryController::class,'addSunscreenRecord'])->name('dailyDiary.addSunscreenRecord');
Route::post('dailyDiary/addBottle',[DailyDiaryController::class,'addBottle'])->name('dailyDiary.addBottle');
Route::post('dailyDiary/deleteBottleTime',[DailyDiaryController::class,'deleteBottleTime'])->name('dailyDiary.deleteBottleTime');


Route::get('dailyDiary/viewChildDiary',[DailyDiaryController::class,'viewChildDiary'])->name('dailyDiary.viewChildDiary');

// Daily Journel Ends here 


   

    Route::post('/logout', function () {
        Auth::logout(); // Logs out the user
        session()->invalidate();      // Invalidate session
        session()->regenerateToken(); // Prevent CSRF issues
        return redirect('login'); // Redirect to login page
    })->name('logout');
// service details

    Route::get('/room/{roomid}/children', [RoomController::class, 'showChildren'])->name('room.children');
    Route::get('/edit-child/{id}', [RoomController::class, 'edit_child'])->name('edit_child');
    Route::put('/child/update/{id}', [RoomController::class, 'update_child'])->name('update_child');
    Route::post('/move-children', [RoomController::class, 'moveChildren'])->name('move_children');
    Route::post('/children/delete-selected', [RoomController::class, 'delete_selected_children'])->name('delete_selected_children');

    Route::post('add-children', [RoomController::class, 'add_new_children'])->name('add_children');
    Route::match(['get', 'post'], '/rooms', [RoomController::class, 'rooms_list'])->name('rooms_list');

    Route::get('Observation/getSubjects',[ObservationController::class,'getSubjects'])->name('Observation.getSubjects');

    Route::get('Observation/getActivitiesBySubject',[ObservationController::class,'addSubActivity'])->name('Observation.addSubActivity');

Route::get('Observation/addSubActivity',[ObservationController::class,'getActivitiesBySubject'])->name('Observation.getActivitiesBySubject');
    Route::prefix('settings')->name('settings.')->group(function () {
           
        Route::get('/superadmin_settings', [SettingsController::class, 'superadminSettings'])->name('superadmin_settings');
        Route::delete('/superadmin/{id}', [SettingsController::class, 'destroy'])->name('superadmin.destroy');
        Route::post('/superadmin/store', [SettingsController::class, 'store'])->name('superadmin.store');
        Route::get('/superadmin/{id}/edit', [SettingsController::class, 'edit'])->name('superadmin.edit');
        Route::post('/superadmin/{id}', [SettingsController::class, 'update'])->name('superadmin.update');
        Route::get('/center_settings', [SettingsController::class, 'center_settings'])->name('center_settings');
        Route::post('/center_store', [SettingsController::class, 'center_store'])->name('center_store');
        Route::get('/center/{id}/edit', [SettingsController::class, 'center_edit'])->name('center.edit');
        Route::post('/center/{id}', [SettingsController::class, 'center_update'])->name('center.update');
        Route::delete('/center/{id}', [SettingsController::class, 'destroycenter'])->name('center.destroy');

        Route::get('/staff_settings', [SettingsController::class, 'staff_settings'])->name('staff_settings');

        Route::post('/staff/store', [SettingsController::class, 'staff_store'])->name('staff.store');

        Route::get('/staff/{id}/edit', [SettingsController::class, 'staff_edit'])->name('staff.edit');
        Route::post('/staff/{id}', [SettingsController::class, 'staff_update'])->name('staff.update');




        Route::get('/parent_settings', [SettingsController::class, 'parent_settings'])->name('parent_settings');
        Route::post('/parent/store', [SettingsController::class, 'parent_store'])->name('parent.store');

        Route::get('/parent/{id}/get', [SettingsController::class, 'getParentData']);
        Route::post('/parent/update', [SettingsController::class, 'parent_update'])->name('parent.update');


        Route::get('/profile', [SettingsController::class, 'getprofile_page'])->name('profile');
        Route::post('/upload-profile-image', [SettingsController::class, 'uploadImage'])->name('upload.profile.image');
        Route::post('/profile/update/{id}', [SettingsController::class, 'profileupdate'])->name('profile.update');
        Route::post('/profile/change-password/{id}', [SettingsController::class, 'changePassword'])->name('profile.change-password');
    });

    Route::post('/change-center', [SettingsController::class, 'changeCenter'])->name('change.center');
});






// no used but only use for template pages......after remove this

Route::get('data', [AccidentsController::class, 'index']);
/* Dashboard */
Route::get('dashboard', function () {
    return redirect('dashboard/analytical');
});
// service details


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
