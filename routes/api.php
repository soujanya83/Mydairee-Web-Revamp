<?php

use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\RagisterController;
use App\Http\Controllers\API\ApiResetPassword;
use App\Http\Controllers\API\ApiHealthyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LessonPlanList;
use App\Http\Middleware\ClearCacheAfterLogout;
use App\Http\Controllers\API\ServiceDetailsController;
use App\Http\Controllers\API\AnnouncementController;
use App\Http\Controllers\API\HeadChecks;
use App\Http\Controllers\API\SleepCheckController;
use App\Http\Controllers\API\AccidentsController;
use App\Http\Controllers\API\RoomController;
use App\Http\Controllers\API\ObservationController;
use App\Http\Controllers\API\ObservationsController;
use App\Http\Controllers\API\ReflectionController;
use App\Http\Controllers\API\DailyDiaryController;
use App\Http\Controllers\API\SettingsController;






Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

Route::post('/login', [LoginController::class, 'login']);
Route::post('/store', [RagisterController::class, 'store']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
// Route::get('/login',[LoginController::class,'login'])->name('login');
// });


Route::middleware('auth:sanctum')->group(function () {


  Route::get('/centers',[LessonPlanList::class,'centers'])->name('centers');
    // program plan
        Route::get('/programPlanList',[LessonPlanList::class,'programPlanList'])->name('programPlanList');
    Route::post('LessonPlanList/deletedataofprogramplan',[LessonPlanList::class,'deleteProgramPlan'])->name('LessonPlanList.deletedataofprogramplan');
    Route::get('/programPlan/print', [LessonPlanList::class, 'programplanprintpage']);
    Route::post('/LessonPlanList/get_room_users', [LessonPlanList::class, 'getRoomUsers'])->name('LessonPlanList.get_room_users');
    Route::post('/LessonPlanList/get_room_children', [LessonPlanList::class, 'getRoomChildren'])->name('LessonPlanList.get_room_children');
    Route::post('/LessonPlanList/save_program_planinDB', [LessonPlanList::class, 'saveProgramPlan'])->name('LessonPlanList.save_program_planinDB');
    Route::get('/programPlan/create',[LessonPlanList::class,'createForm'])->name('create.programplan');
    Route::post('/programPlan',[LessonPlanList::class,'store'])->name('store.programPlan');

    // service details 
        Route::get('ServiceDetails', [ServiceDetailsController::class, 'create'])->name('create.serviceDetails');
    Route::post('ServiceDetails', [ServiceDetailsController::class, 'store'])->name('store.serviceDetails');

    // annoucement
    Route::get('announcements/list',[AnnouncementController::class,'list'])->name('announcements.list');
Route::get('announcements/create/{id?}',[AnnouncementController::class,'AnnouncementCreate'])->name('announcements.create');
Route::post('announcements/store',[AnnouncementController::class,'AnnouncementStore'])->name('announcements.store');
Route::delete('announcements/delete',[AnnouncementController::class,'AnnouncementDelete'])->name('announcements.delete');
Route::get('announcements/view',[AnnouncementController::class,'AnnouncementView'])->name('announcements.view');

// headchecks
Route::get('headChecks',[HeadChecks::class,'index'])->name('headChecks');
Route::post('headchecks/store',[HeadChecks::class,'headchecksStore'])->name('headchecks.store');
Route::post('headchecks/getCenterRooms',[HeadChecks::class,'getCenterRooms'])->name('headchecks.getCenterRooms');
Route::post('headcheckdelete',[HeadChecks::class,'headcheckDelete'])->name('headcheck.delete');

// sleep checks
Route::get('sleepcheck/list',[SleepCheckController::class,'getSleepChecksList'])->name('sleepcheck.list');
Route::post('sleepcheck/save',[SleepCheckController::class,'sleepcheckSave'])->name('sleepcheck.save');
Route::post('sleepcheck/update',[SleepCheckController::class,'sleepcheckUpdate'])->name('sleepcheck.update');
Route::post('sleepcheck/delete',[SleepCheckController::class,'sleepcheckDelete'])->name('sleepcheck.delete');

// Accidents
Route::get('Accidents/list',[AccidentsController::class,'AccidentsList'])->name('Accidents.list');
Route::post('Accidents/getCenterRooms',[AccidentsController::class,'getCenterRooms'])->name('Accidents.getCenterRooms');
Route::put('Accidents/update/{id}',[AccidentsController::class,'AccidentsUpdate'])->name('accidents.update');
Route::get('Accidents/details',[AccidentsController::class,'getAccidentDetails'])->name('Accidents.details');
Route::post('Accidents/sendEmail',[AccidentsController::class,'sendEmail'])->name('Accidents.sendEmail');
Route::get('Accidents/create',[AccidentsController::class,'create'])->name('Accidents.create');
Route::get('Accidents/edit',[AccidentsController::class,'AccidentEdit'])->name('Accidents.edit');
Route::post('Accident/saveAccident',[AccidentsController::class,'saveAccident'])->name('Accidents.saveAccident');
Route::post('Accident/getChildDetails',[AccidentsController::class,'getChildDetails'])->name('Accident/getChildDetails');

// rooms
    Route::get('/room/{roomid}/children', [RoomController::class, 'showChildren'])->name('room.children');
    Route::get('/edit-child/{id}', [RoomController::class, 'edit_child'])->name('edit_child');
    Route::post('/child/update', [RoomController::class, 'update_child'])->name('update_child');
    Route::post('/move-children', [RoomController::class, 'moveChildren'])->name('move_children');
    Route::post('/children/delete-selected', [RoomController::class, 'delete_selected_children'])->name('delete_selected_children');

    Route::post('add-children', [RoomController::class, 'add_new_children'])->name('add_children');
    Route::match(['get', 'post'], '/rooms', [RoomController::class, 'rooms_list'])->name('rooms_list');
    Route::post('/room-create', [RoomController::class, 'rooms_create'])->name('room_create');
    Route::delete('/rooms/bulk-delete', [RoomController::class, 'bulkDelete'])->name('rooms.bulk_delete');

    // observation
        Route::post('Observation/addActivity', [ObservationController::class, 'addActivity'])->name('Observation.addActivity');
    Route::post('Observation/addSubActivity', [ObservationController::class, 'addSubActivity'])->name(' Observation.addSubActivity');
      Route::get('Observation/getSubjects', [ObservationController::class, 'getSubjects'])->name('Observation.getSubjects');
    Route::get('Observation/getActivitiesBySubject', [ObservationController::class, 'getActivitiesBySubject'])->name('Observation.getActivitiesBySubject');
    

    // observations
       Route::prefix('observation')->name('observation.')->group(function () {

        Route::get('/index', [ObservationsController::class, 'index'])->name('index');
        Route::get('/get-children', [ObservationsController::class, 'getChildren'])->name('get-children');
        Route::get('/get-staff', [ObservationsController::class, 'getStaff'])->name('get-staff');
        Route::post('/filters', [ObservationsController::class, 'applyFilters'])->name('filters');
        Route::get('/view', [ObservationsController::class, 'index'])->name('view');
        Route::get('/print', [ObservationsController::class, 'print'])->name('print');


        Route::get('/addnew', [ObservationsController::class, 'storepage'])->name('addnew');
        Route::get('/addnew/{id?}/{tab?}/{tab2?}', [ObservationsController::class, 'storepage'])->name('addnew.optional');


        Route::get('/get-children', [ObservationsController::class, 'getChildren'])->name('get.children');
        Route::get('/get-rooms', [ObservationsController::class, 'getrooms'])->name('get.rooms');
        Route::post('/store', [ObservationsController::class, 'store'])->name('store');
        Route::post('/refine-text', [ObservationsController::class, 'refine'])->name('refine.text');

        Route::post('/observation-media', [ObservationsController::class, 'destroyimage']);

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
        Route::get('/print', [ReflectionController::class, 'print'])->name('print');


        Route::post('/store', [ReflectionController::class, 'store'])->name('store');

        Route::post('/reflection-media', [ReflectionController::class, 'destroyimage']);

        Route::post('/status/update', [ReflectionController::class, 'updateStatus'])->name('status.update');

        Route::delete('/delete/{id}', [ReflectionController::class, 'destroy'])->name('delete');

        Route::post('/filters', [ReflectionController::class, 'applyFilters'])->name('filters');
    });


        Route::prefix('settings')->name('settings.')->group(function () {

        Route::get('/superadmin_settings', [SettingsController::class, 'superadminSettings'])->name('superadmin_settings');
        Route::post('/superadmin', [SettingsController::class, 'destroy'])->name('superadmin.destroy');
        Route::post('/superadmin/store', [SettingsController::class, 'store'])->name('superadmin.store');
        Route::get('/superadmin/edit', [SettingsController::class, 'edit'])->name('superadmin.edit');
        Route::post('/superadmin/update', [SettingsController::class, 'update'])->name('superadmin.update');
        Route::get('/center_settings', [SettingsController::class, 'center_settings'])->name('center_settings');
        Route::post('/center_store', [SettingsController::class, 'center_store'])->name('center_store');
        Route::get('/center/{id}/edit', [SettingsController::class, 'center_edit'])->name('center.edit');
        Route::post('/center/{id}', [SettingsController::class, 'center_update'])->name('center.update');
        Route::delete('/center/{id}/destroy', [SettingsController::class, 'destroycenter'])->name('center.destroy');

        Route::get('/staff_settings', [SettingsController::class, 'staff_settings'])->name('staff_settings');

        Route::post('/staff/store', [SettingsController::class, 'staff_store'])->name('staff.store');

        Route::get('/staff/{id}/edit', [SettingsController::class, 'staff_edit'])->name('staff.edit');
        Route::post('/staff/{id}', [SettingsController::class, 'staff_update'])->name('staff.update');
        Route::post('/settings/update-permissions', [SettingsController::class, 'updateUserPermissions'])->name('update_user_permissions');




        Route::get('/parent_settings', [SettingsController::class, 'parent_settings'])->name('parent_settings');
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


});

Route::post('/forget-password', [ApiResetPassword::class, 'apiResetPassword']);
Route::post('/verify-otp', [ApiResetPassword::class, 'apiVerifyOtp']);
Route::post('/resend-otp', [ApiResetPassword::class, 'apiResendOtp']);
Route::post('/reset-password-update', [ApiResetPassword::class, 'apiUpdatePassword']);


Route::middleware('auth:sanctum')->group(function () {
    Route::match(['get', 'post'], '/healthy-menu', [ApiHealthyController::class, 'apiHealthyMenu']);
    Route::get('/get-recipes-by-type', [ApiHealthyController::class, 'apiGetRecipesByType']);
    Route::post('/save-recipes', [ApiHealthyController::class, 'apiStoreMenu']);
    Route::delete('/menu/{id}', [ApiHealthyController::class, 'apiMenuDestroy']);
    Route::match(['get', 'post'], '/healthy-recipes', [ApiHealthyController::class, 'apiHealthyRecipe']);
    Route::get('/recipe/edit/{id}', [ApiHealthyController::class, 'apiEditRecipe']);
    Route::delete('/recipe/delete/{id}', [ApiHealthyController::class, 'apiDestroyRecipe']);
    Route::post('/recipe/store', [ApiHealthyController::class, 'apiStoreRecipe']);
    Route::get('/ingredients', [ApiHealthyController::class, 'apiRecipeIngredients']);
    Route::get('/ingredients/edit/{id}', [ApiHealthyController::class, 'apiEditIngredient']);
    Route::delete('/ingredient/{id}', [ApiHealthyController::class, 'destroyIngredient']);
    Route::post('/ingredient/store', [ApiHealthyController::class, 'ingredientsStore']);
    Route::get('/meal-types', [ApiHealthyController::class, 'getUniqueMealTypes']);


    // Daily Journel here
    Route::get('DailyDiary/list', [DailyDiaryController::class, 'list'])->name('dailyDiary.list');
    Route::post('dailyDiary/storeBottle', [DailyDiaryController::class, 'storeBottle'])->name('dailyDiary.storeBottle');
    Route::post('dailyDiary/storeFood', [DailyDiaryController::class, 'storeFood'])->name('dailyDiary.storeFood');
    Route::post('dailyDiary/storeSleep', [DailyDiaryController::class, 'storeSleep'])->name('dailyDiary.storeSleep');
    Route::post('dailyDiary/storeToileting', [DailyDiaryController::class, 'storeToileting'])->name('dailyDiary.storeToileting');

    Route::post('dailyDiary/storeSunscreen', [DailyDiaryController::class, 'storeSunscreen'])->name('dailyDiary.storeSunscreen');
    // Route::post('dailyDiary/getItems', [DailyDiaryController::class, 'getItems'])->name('dailyDiary.getItems');
    Route::post('dailyDiary/addFoodRecord', [DailyDiaryController::class, 'addFoodRecord'])->name('dailyDiary.addFoodRecord');

    // Route::post('dailyDiary/addSleepRecord', [DailyDiaryController::class, 'addSleepRecord'])->name('dailyDiary.addSleepRecord');
    // Route::post('dailyDiary/addToiletingRecord', [DailyDiaryController::class, 'addToiletingRecord'])->name('dailyDiary.addToiletingRecord');
    // Route::post('dailyDiary/addSunscreenRecord', [DailyDiaryController::class, 'addSunscreenRecord'])->name('dailyDiary.addSunscreenRecord');
    Route::post('dailyDiary/addBottle', [DailyDiaryController::class, 'addBottle'])->name('dailyDiary.addBottle');
    // Route::post('dailyDiary/deleteBottleTime', [DailyDiaryController::class, 'deleteBottleTime'])->name('dailyDiary.deleteBottleTime');

    // Route::post('dailyDiary/updateBottleTimes', [DailyDiaryController::class, 'updateBottleTimes'])->name('dailyDiary.updateBottleTimes');
    // Route::post('dailyDiary/addToiletingRecord', [DailyDiaryController::class, 'addToiletingRecord'])->name('dailyDiary.addToiletingRecord');
    // Route::post('dailyDiary/addSunscreenRecord', [DailyDiaryController::class, 'addSunscreenRecord'])->name('dailyDiary.addSunscreenRecord');
    // Route::post('dailyDiary/addBottle', [DailyDiaryController::class, 'addBottle'])->name('dailyDiary.addBottle');
    // Route::post('dailyDiary/deleteBottleTime', [DailyDiaryController::class, 'deleteBottleTime'])->name('dailyDiary.deleteBottleTime');


    Route::get('dailyDiary/viewChildDiary', [DailyDiaryController::class, 'viewChildDiary'])->name('dailyDiary.viewChildDiary');

    Route::post('/activities/breakfast', [DailyDiaryController::class, 'storeBreakfast']);
    Route::post('/activities/morning-tea', [DailyDiaryController::class, 'storeMorningTea']);
    Route::post('/activities/lunch', [DailyDiaryController::class, 'storeLunch']);
    Route::post('/activities/sleep', [DailyDiaryController::class, 'storeSleep']);
    Route::post('/activities/afternoon-tea', [DailyDiaryController::class, 'storeAfternoonTea']);
    Route::post('/activities/snacks', [DailyDiaryController::class, 'storeSnacks']);
    Route::post('/activities/sunscreen', [DailyDiaryController::class, 'storeSunscreen']);
    Route::post('/activities/toileting', [DailyDiaryController::class, 'storeToileting']);
    Route::post('/activities/bottle', [DailyDiaryController::class, 'storeBottle']);

    // Daily Journel Ends here


});
