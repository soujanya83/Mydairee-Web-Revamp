<?php

use App\Http\Controllers\AccidentsController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DailyDiaryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DBBackupController;
use App\Http\Controllers\FileManagerController;
use App\Http\Controllers\HeadChecks;
use App\Http\Controllers\LessonPlanList;
use App\Http\Controllers\HealthyController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ObservationsController;
use App\Http\Controllers\LnPcontroller;
use App\Http\Controllers\Qipcontroller;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ClearCacheAfterLogout;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ServiceDetailsController;
use App\Models\Child;
use App\Http\Controllers\ObservationController;
use App\Http\Controllers\ReflectionController;
use App\Http\Controllers\SleepCheckController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\Auth\NotificationController;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Artisan;
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
Route::get('/api/events', [DashboardController::class, 'getEvents']);
Route::get('file-manager/dashboard', [FileManagerController::class, 'dashboard'])->name('file-manager.dashboard');
Route::get('app/calendar', [AppController::class, 'calendar'])->name('app.calendar');
Route::get('app/chat', [AppController::class, 'chat'])->name('app.chat');
Route::get('app/inbox', [AppController::class, 'inbox'])->name('app.inbox');
Route::get('pages/profile1', [PagesController::class, 'profile1'])->name('pages.profile1');

Route::post('create-superadmin', [UserController::class, 'store'])->name('create_superadmin');
Route::post('login-submit', [UserController::class, 'login'])->name('user_login');
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
Route::get('login-page', [AuthenticationController::class, 'login_page'])->name('login');
Route::get('login', [AuthenticationController::class, 'login'])->name('authentication.login');




// Route group with middleware this middleware use after login
Route::middleware(['web', 'auth', ClearCacheAfterLogout::class])->group(function () {
    Route::get('/', [DashboardController::class, 'university'])->name('dashboard.university');
     Route::get('users/birthday', [DashboardController::class, 'getUser'])->name('users..birthday');
    // service details
    Route::get('ServiceDetails', [ServiceDetailsController::class, 'create'])->name('create.serviceDetails');
    Route::post('ServiceDetails', [ServiceDetailsController::class, 'store'])->name('store.serviceDetails');


    Route::get('programPlanList', [LessonPlanList::class, 'programPlanList'])->name('programPlanList');
    Route::get('LessonPlanList/filter-program-plans', [LessonPlanList::class, 'filterProgramPlan'])->name('filter-program-plans');
    Route::get('programPlan/create', [LessonPlanList::class, 'createForm'])->name('create.programplan');
    Route::post('LessonPlanList/deletedataofprogramplan', [LessonPlanList::class, 'deleteProgramPlan'])->name('LessonPlanList.deletedataofprogramplan');

    Route::get('programPlan/print/{planId}', [LessonPlanList::class, 'programplanprintpage'])->name('print.programplan');
    // ajax
    Route::post('LessonPlanList/get_room_users', [LessonPlanList::class, 'getRoomUsers'])->name('LessonPlanList.get_room_users');
    Route::post('LessonPlanList/get_room_children', [LessonPlanList::class, 'getRoomChildren'])->name('LessonPlanList.get_room_children');
    Route::post('LessonPlanList/save_program_planinDB', [LessonPlanList::class, 'saveProgramPlan'])->name('LessonPlanList.save_program_planinDB');
    // ajax ends
    Route::post('programPlan', [LessonPlanList::class, 'store'])->name('store.programPlan');

    Route::post('Observation/addActivity', [ObservationController::class, 'addActivity'])->name('Observation.addActivity');
    Route::post('Observation/addSubActivity', [ObservationController::class, 'addSubActivity'])->name(' Observation.addSubActivity');

    Route::get('announcements/list', [AnnouncementController::class, 'list'])->name('announcements.list');
        Route::get('announcements/Filterlist', [AnnouncementController::class, 'Filterlist'])->name('announcements.Filterlist');
    Route::get('announcements/create/{id?}', [AnnouncementController::class, 'AnnouncementCreate'])->name('announcements.create');
    Route::post('announcements/store', [AnnouncementController::class, 'AnnouncementStore'])->name('announcements.store');
    Route::delete('announcements/delete', [AnnouncementController::class, 'AnnouncementDelete'])->name('announcements.delete');
    Route::get('announcements/view/{annid}', [AnnouncementController::class, 'AnnouncementView'])->name('announcements.view');
        Route::get('announcements/events', [DashboardController::class, 'getEvents'])->name('announcements.events');

    // headchecks
    Route::get('headChecks', [HeadChecks::class, 'index'])->name('headChecks');
    Route::post('headchecks/store', [HeadChecks::class, 'headchecksStore'])->name('headchecks.store');
    Route::post('headchecks/getCenterRooms', [HeadChecks::class, 'getCenterRooms'])->name('headchecks.getCenterRooms');
    Route::post('headcheckdelete', [HeadChecks::class, 'headcheckDelete'])->name('headcheck.delete');


    Route::get('/notifications/mark-all-read', function () {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back();
    })->name('notifications.markAllRead');

    Route::post('/notifications/read/{id}', function ($id) {
        $notification = DatabaseNotification::find($id);

        if ($notification && $notification->notifiable_id == Auth::id()) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    })->name('notifications.read');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.all');



    Route::get('sleepcheck/list', [SleepCheckController::class, 'getSleepChecksList'])->name('sleepcheck.list');
        Route::get('sleepcheck/filter-sleep-list-by-child', [SleepCheckController::class, 'fetchSleepChecks'])->name('sleepcheck.filter-sleep-list-by-child');
    Route::post('sleepcheck/save', [SleepCheckController::class, 'sleepcheckSave'])->name('sleepcheck.save');
    Route::post('sleepcheck/update', [SleepCheckController::class, 'sleepcheckUpdate'])->name('sleepcheck.update');
    Route::post('sleepcheck/delete', [SleepCheckController::class, 'sleepcheckDelete'])->name('sleepcheck.delete');

    // Accidents
    Route::get('Accidents/list', [AccidentsController::class, 'AccidentsList'])->name('Accidents.list');
        Route::get('Accidents/filter-by-child', [AccidentsController::class, 'filterByChild'])->name('filter-by-child');
    Route::post('Accidents/getCenterRooms', [AccidentsController::class, 'getCenterRooms'])->name('Accidents.getCenterRooms');
    Route::put('Accidents/update/{id}', [AccidentsController::class, 'AccidentsUpdate'])->name('accidents.update');
    Route::get('Accidents/details', [AccidentsController::class, 'getAccidentDetails'])->name('Accidents.details');
    Route::post('Accidents/sendEmail', [AccidentsController::class, 'sendEmail'])->name('Accidents.sendEmail');
    Route::get('Accidents/create', [AccidentsController::class, 'create'])->name('Accidents.create');
    Route::get('Accidents/edit', [AccidentsController::class, 'AccidentEdit'])->name('Accidents.edit');
    Route::post('Accident/saveAccident', [AccidentsController::class, 'saveAccident'])->name('Accidents.saveAccident');
    Route::post('Accident/getChildDetails', [AccidentsController::class, 'getChildDetails'])->name('Accident/getChildDetails');





    // Route::get('surveys/list', [SurveyController::class, 'list'])->name('survey.list');

    // Daily Journel here
    Route::get('DailyDiary/list', [DailyDiaryController::class, 'list'])->name('dailyDiary.list');
    Route::post('dailyDiary/storeBottle', [DailyDiaryController::class, 'storeBottle'])->name('dailyDiary.storeBottle');
    Route::post('dailyDiary/storeFood', [DailyDiaryController::class, 'storeFood'])->name('dailyDiary.storeFood');
    Route::post('dailyDiary.storeSleep', [DailyDiaryController::class, 'storeSleep'])->name('dailyDiary.storeSleep');
    Route::post('dailyDiary/storeToileting', [DailyDiaryController::class, 'storeToileting'])->name('dailyDiary.storeToileting');

    Route::post('dailyDiary/storeSunscreen', [DailyDiaryController::class, 'storeSunscreen'])->name('dailyDiary.storeSunscreen');
    Route::post('dailyDiary/getItems', [DailyDiaryController::class, 'getItems'])->name('dailyDiary.getItems');
    Route::post('dailyDiary/addFoodRecord', [DailyDiaryController::class, 'addFoodRecord'])->name('dailyDiary.addFoodRecord');

    Route::post('dailyDiary/addSleepRecord', [DailyDiaryController::class, 'addSleepRecord'])->name('dailyDiary.addSleepRecord');
    Route::post('dailyDiary/addToiletingRecord', [DailyDiaryController::class, 'addToiletingRecord'])->name('dailyDiary.addToiletingRecord');
    Route::post('dailyDiary/addSunscreenRecord', [DailyDiaryController::class, 'addSunscreenRecord'])->name('dailyDiary.addSunscreenRecord');
    Route::post('dailyDiary/addBottle', [DailyDiaryController::class, 'addBottle'])->name('dailyDiary.addBottle');
    Route::post('dailyDiary/deleteBottleTime', [DailyDiaryController::class, 'deleteBottleTime'])->name('dailyDiary.deleteBottleTime');

    Route::post('dailyDiary/updateBottleTimes', [DailyDiaryController::class, 'updateBottleTimes'])->name('dailyDiary.updateBottleTimes');
    // Route::post('dailyDiary/addToiletingRecord', [DailyDiaryController::class, 'addToiletingRecord'])->name('dailyDiary.addToiletingRecord');
    // Route::post('dailyDiary/addSunscreenRecord', [DailyDiaryController::class, 'addSunscreenRecord'])->name('dailyDiary.addSunscreenRecord');
    // Route::post('dailyDiary/addBottle', [DailyDiaryController::class, 'addBottle'])->name('dailyDiary.addBottle');
    // Route::post('dailyDiary/deleteBottleTime', [DailyDiaryController::class, 'deleteBottleTime'])->name('dailyDiary.deleteBottleTime');


    Route::get('dailyDiary/viewChildDiary', [DailyDiaryController::class, 'viewChildDiary'])->name('dailyDiary.viewChildDiary');

    // Route::post('/activities/breakfast', [DailyDiaryController::class, 'storeBreakfast']);
    // Route::post('/activities/morning-tea', [DailyDiaryController::class, 'storeMorningTea']);
    // Route::post('/activities/lunch', [DailyDiaryController::class, 'storeLunch']);
    // Route::post('/activities/sleep', [DailyDiaryController::class, 'storeSleep']);
    // Route::post('/activities/afternoon-tea', [DailyDiaryController::class, 'storeAfternoonTea']);
    // Route::post('/activities/snacks', [DailyDiaryController::class, 'storeSnacks']);
    // Route::post('/activities/sunscreen', [DailyDiaryController::class, 'storeSunscreen']);
    // Route::post('/activities/toileting', [DailyDiaryController::class, 'storeToileting']);
    // Route::post('/activities/bottle', [DailyDiaryController::class, 'storeBottle']);

    // Daily Journel Ends here
    Route::get('/backup-now', [DBBackupController::class, 'runBackup']);




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
    Route::put('/update/child{id}', [RoomController::class, 'update_child_progress'])->name('update_child_progress');
    Route::post('/move-children', [RoomController::class, 'moveChildren'])->name('move_children');
    Route::post('/children/delete-selected', [RoomController::class, 'delete_selected_children'])->name('delete_selected_children');

    Route::post('add-children', [RoomController::class, 'add_new_children'])->name('add_children');
    Route::match(['get', 'post'], '/rooms', [RoomController::class, 'rooms_list'])->name('rooms_list');
    Route::post('/room-create', [RoomController::class, 'rooms_create'])->name('room_create');
    Route::delete('/rooms/bulk-delete', [RoomController::class, 'bulkDelete'])->name('rooms.bulk_delete');
    Route::get('/childrens-list', [RoomController::class, 'childrens_list'])->name('childrens_list');
    Route::get('/childrens-edit/{id}', [RoomController::class, 'childrens_edit'])->name('children.edit');
    Route::delete('/childrens-delete/{id}', [RoomController::class, 'children_destroy'])->name('children.destroy');

    // recipe
    Route::match(['get', 'post'], '/healthy-recipe', [HealthyController::class, 'healthy_recipe'])->name('healthy_recipe');
    Route::get('/recipes/{id}/edit', [HealthyController::class, 'edit'])->name('recipes.edit');
    Route::delete('/recipes/{id}/delete', [HealthyController::class, 'destroy'])->name('recipes.destroy');
    Route::get('/recipes/ingredients', [HealthyController::class, 'recipes_Ingredients'])->name('recipes.Ingredients');
    Route::get('/ingredients/{id}/edit', [HealthyController::class, 'ingredients_edit'])->name('ingredients.edit');
    Route::delete('/ingredients/{id}/delete', [HealthyController::class, 'destroy_ingredent'])->name('ingredients.destroy');
    Route::post('/ingredients', [HealthyController::class, 'ingredients_store'])->name('ingredients.store');
    Route::put('/ingredients/{id}', [HealthyController::class, 'ingredients_update'])->name('ingredients.update');
    Route::post('/recipes/store', [HealthyController::class, 'recipes_store'])->name('recipes.store');
    Route::post('/recipes/{id}/update', [HealthyController::class, 'update'])->name('recipes.update');

    Route::match(['get', 'post'], '/healthy-menu', [HealthyController::class, 'healthy_menu'])->name('healthy_menu');
    // Route::post('/store-menu', [HealthyController::class, 'store_menu'])->name('menu.store');
    Route::get('/get-recipes-by-type', [HealthyController::class, 'getByType']);
    Route::post('/save-recipes', [HealthyController::class, 'store_menu'])->name('menu.store');
    Route::delete('/menu/{id}', [HealthyController::class, 'menu_destroy'])->name('menu.destroy');

// settings
    Route::post('/change-center', [SettingsController::class, 'changeCenter'])->name('change.center');


    Route::post('add-children', [RoomController::class, 'add_new_children'])->name('add_children');
    Route::match(['get', 'post'], '/rooms', [RoomController::class, 'rooms_list'])->name('rooms_list');

    Route::get('Observation/getSubjects', [ObservationController::class, 'getSubjects'])->name('Observation.getSubjects');

    Route::get('Observation/getActivitiesBySubject', [ObservationController::class, 'getActivitiesBySubject'])->name('Observation.getActivitiesBySubject');


    Route::get('Observation/addSubActivity', [ObservationController::class, 'addSubActivity'])->name('Observation.addSubActivity');

    Route::prefix('settings')->name('settings.')->group(function () {

        Route::get('/superadmin_settings', [SettingsController::class, 'superadminSettings'])->name('superadmin_settings');
        Route::get('/filter-admins', [SettingsController::class, 'filterByAdminName'])->name('filter-admins');
        Route::delete('/superadmin/{id}', [SettingsController::class, 'destroy'])->name('superadmin.destroy');
        Route::post('/superadmin/store', [SettingsController::class, 'store'])->name('superadmin.store');
        Route::get('/superadmin/{id}/edit', [SettingsController::class, 'edit'])->name('superadmin.edit');
        Route::post('/superadmin/{id}', [SettingsController::class, 'update'])->name('superadmin.update');
        Route::get('/center_settings', [SettingsController::class, 'center_settings'])->name('center_settings');
        Route::post('/center_store', [SettingsController::class, 'center_store'])->name('center_store');
        Route::get('/center/{id}/edit', [SettingsController::class, 'center_edit'])->name('center.edit');
        Route::post('/center/{id}', [SettingsController::class, 'center_update'])->name('center.update');
        Route::delete('/center/{id}', [SettingsController::class, 'destroycenter'])->name('center.destroy');
        // filter
Route::get('filter-centers', [SettingsController::class, 'filterbycentername'])->name('filter-centers');
// filter by center ends 


        Route::get('/staff_settings', [SettingsController::class, 'staff_settings'])->name('staff_settings');
        Route::get('filter-staffs', [SettingsController::class, 'filterStaffByName'])->name('filter-staffs');
        Route::post('/staff/store', [SettingsController::class, 'staff_store'])->name('staff.store');
      

        Route::get('/staff/{id}/edit', [SettingsController::class, 'staff_edit'])->name('staff.edit');
        Route::post('/staff/{id}', [SettingsController::class, 'staff_update'])->name('staff.update');
        Route::put('/settings/update-permissions/{user}', [SettingsController::class, 'updateUserPermissions'])->name('update_user_permissions');




        Route::get('/parent_settings', [SettingsController::class, 'parent_settings'])->name('parent_settings');
        Route::get('/filter-parents', [SettingsController::class, 'filterByParentName']);

        Route::get('/manage_permissions', [SettingsController::class, 'manage_permissions'])->name('manage_permissions');
        Route::get('user/permissions', [SettingsController::class, 'user_permissions'])->name('allusers_permissions');
        Route::post('/parent/store', [SettingsController::class, 'parent_store'])->name('parent.store');
        Route::post('/assign-permissions', [SettingsController::class, 'assign_user_permissions'])->name('assign_permissions');
        Route::get('permissions-assigned', [SettingsController::class, 'assigned_permissions'])->name('assigned_permissions');

        Route::get('/parent/{id}/get', [SettingsController::class, 'getParentData']);
        Route::post('/parent/update', [SettingsController::class, 'parent_update'])->name('parent.update');


        Route::get('/profile', [SettingsController::class, 'getprofile_page'])->name('profile');
        Route::post('/upload-profile-image', [SettingsController::class, 'uploadImage'])->name('upload.profile.image');
        Route::post('/profile/update/{id}', [SettingsController::class, 'profileupdate'])->name('profile.update');
        Route::post('/profile/change-password/{id}', [SettingsController::class, 'changePassword'])->name('profile.change-password');
    });


    Route::prefix('observation')->name('observation.')->group(function () {

        Route::get('/index', [ObservationsController::class, 'index'])->name('index');
        Route::get('/get-children', [ObservationsController::class, 'getChildren'])->name('get-children');
        Route::get('/get-staff', [ObservationsController::class, 'getStaff'])->name('get-staff');
        Route::post('/filters', [ObservationsController::class, 'applyFilters'])->name('filters');
        Route::get('/view', [ObservationsController::class, 'index'])->name('view');
        Route::get('/print/{id}', [ObservationsController::class, 'print'])->name('print');


        Route::get('/addnew', [ObservationsController::class, 'storepage'])->name('addnew');
        Route::get('/addnew/{id?}/{tab?}/{tab2?}', [ObservationsController::class, 'storepage'])->name('addnew.optional');


        Route::get('/get-children', [ObservationsController::class, 'getChildren'])->name('get.children');
        Route::get('/get-rooms', [ObservationsController::class, 'getrooms'])->name('get.rooms');
        Route::post('/store', [ObservationsController::class, 'store'])->name('store');
        Route::post('/refine-text', [ObservationsController::class, 'refine'])->name('refine.text');

        Route::delete('/observation-media/{id}', [ObservationsController::class, 'destroyimage']);

        Route::post('/montessori/store', [ObservationsController::class, 'storeMontessoriData'])->name('montessori.store');
        Route::post('/eylf/store', [ObservationsController::class, 'storeEylfData'])->name('eylf.store');
        Route::post('/devmilestone/store', [ObservationsController::class, 'storeDevMilestone'])->name('devmilestone.store');
        Route::post('/status/update', [ObservationsController::class, 'updateStatus'])->name('status.update');
        Route::get('/view/{id}', [ObservationsController::class, 'view'])->name('view');
        Route::get('/observationslink', [ObservationsController::class, 'linkobservationdata']);
        Route::post('/submit-selectedoblink', [ObservationsController::class, 'storelinkobservation']);
    });


    Route::prefix('reflection')->name('reflection.')->group(function () {

        Route::get('/index', [ReflectionController::class, 'index'])->name('index');

        Route::get('/addnew', [ReflectionController::class, 'storepage'])->name('addnew');
        Route::get('/addnew/{id?}', [ReflectionController::class, 'storepage'])->name('addnew.optional');
        Route::get('/print/{id?}', [ReflectionController::class, 'print'])->name('print');


        Route::post('/store', [ReflectionController::class, 'store'])->name('store');

        Route::delete('/reflection-media/{id}', [ReflectionController::class, 'destroyimage']);

        Route::post('/status/update', [ReflectionController::class, 'updateStatus'])->name('status.update');

        Route::delete('/delete/{id}', [ReflectionController::class, 'destroy'])->name('delete');

        Route::post('/filters', [ReflectionController::class, 'applyFilters'])->name('filters');
    });


    Route::prefix('snapshot')->name('snapshot.')->group(function () {

        Route::get('/index', [ObservationsController::class, 'snapshotindex'])->name('index');
        Route::get('/addnew', [ObservationsController::class, 'snapshotindexstorepage'])->name('addnew');
        Route::get('/addnew/{id?}', [ObservationsController::class, 'snapshotindexstorepage'])->name('addnew.optional');
        Route::post('/store', [ObservationsController::class, 'snapshotstore'])->name('store');
        Route::delete('/snapshot-media/{id}', [ObservationsController::class, 'snapshotdestroyimage']);
        Route::post('/status/update', [ObservationsController::class, 'snapshotupdateStatus'])->name('status.update');
        Route::delete('snapshotsdelete/{id}', [ObservationsController::class, 'snapshotsdelete'])->name('snapshots.snapshotsdelete');
    });



    Route::prefix('learningandprogress')->name('learningandprogress.')->group(function () {

        Route::get('/index', [LnPcontroller::class, 'index'])->name('index');
        Route::get('/lnpdata/{id?}', [LnPcontroller::class, 'lnpData'])->name('lnpdata');
        Route::post('/update-assessment-status', [LnPcontroller::class, 'updateAssessmentStatus'])->name('update.assessment.status');
    });

    Route::prefix('qip')->name('qip.')->group(function () {

        Route::get('/index', [Qipcontroller::class, 'index'])->name('index');
        Route::get('/addnew', [Qipcontroller::class, 'addnew'])->name('addnew');
        Route::post('/update-name', [QipController::class, 'updateName'])->name('update.name');
        Route::get('/{id}/area/{area}', [QipController::class, 'viewArea'])->name('area.view');
        Route::get('/{qip}/element/{element}', [QipController::class, 'viewElement'])->name('element.view');
        Route::get('/{qip}/standard/{standard}/edit', [QipController::class, 'editStandard'])->name('standard.edit');
        Route::post('/discussion/send', [QipController::class, 'sendDiscussion'])->name('discussion.send');
    });
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
