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
use App\Http\Controllers\API\QipController;
use App\Http\Controllers\API\ReEnrolmentController;
use App\Http\Controllers\API\RoomController;
use App\Http\Controllers\API\ObservationController;
use App\Http\Controllers\API\ObservationsController;
use App\Http\Controllers\API\ReflectionController;
use App\Http\Controllers\API\DailyDiaryController;
use App\Http\Controllers\API\SettingsController;
use App\Http\Controllers\API\LnPcontroller;
use App\Http\Controllers\API\Dashboard;
use App\Http\Controllers\API\ParentSlideshowController;
use App\Http\Controllers\API\ApiResetPasswordController; 
use App\Http\Controllers\API\UserProfileController; 
use App\Http\Controllers\API\DeviceController;
use App\Http\Controllers\API\GlobalRoomsChildrenController;
use App\Http\Controllers\API\NotificationApiController;
use App\Http\Controllers\API\NotesController;
use App\Http\Controllers\API\PublicHolidayController;
use App\Http\Controllers\API\ApiWifiIPController;
use App\Http\Controllers\API\ApiPTMController;
use App\Http\Controllers\API\RecycleBinController as ApiRecycleBinController;
use App\Http\Controllers\API\ProgramPlanApiController;
use App\Http\Controllers\API\ObservationApiController;
 
Route::prefix('v1')->name('v1.')->group(function () {
Route::middleware('auth:sanctum')->group(function () {
        // Child details and status toggle
        Route::get('/children/{id}/details', [\App\Http\Controllers\API\ChildDetailsController::class, 'show']);
        Route::patch('/children/{id}/toggle-status', [\App\Http\Controllers\API\ChildDetailsController::class, 'toggleStatus']);
    Route::post('/save-fcm-token', [DeviceController::class, 'saveToken']);
    Route::post('/test-fcm', [DeviceController::class, 'testNotification']);
    Route::patch('/user/notification-preference', [DeviceController::class, 'updateNotificationPreference']);

    // New API: Get all permissions
    Route::get('settings/all-permissions', [SettingsController::class, 'all_permissions']);
});

Route::middleware('auth:sanctum')->group(function () {
	Route::get('user/profile-picture', [UserProfileController::class, 'getProfilePicture']);
	Route::post('user/profile-picture', [UserProfileController::class, 'updateProfilePicture']);
	Route::get('user/profile', [UserProfileController::class, 'getProfile']);
	Route::patch('user/profile', [UserProfileController::class, 'updateProfile']);
    Route::prefix('notes')->name('notes.')->group(function () {
        Route::get('/', [NotesController::class, 'index'])->name('index');
        Route::post('/', [NotesController::class, 'store'])->name('store');
        Route::get('/{id}', [NotesController::class, 'show'])->name('show');
        Route::post('/update', [NotesController::class, 'update'])->name('update');
        Route::delete('/{id}', [NotesController::class, 'destroy'])->name('destroy');
    });
    Route::get('global-rooms', [GlobalRoomsChildrenController::class, 'getCenterRooms']);
    Route::get('global-userrooms',[GlobalRoomsChildrenController::class, 'getUserCenterRooms']);
    Route::get('global-children', [GlobalRoomsChildrenController::class, 'getRoomChildren']);
    Route::get('global-room-staff/{roomId}', [GlobalRoomsChildrenController::class, 'getRoomStaff']);
    Route::get('global-center-staff/{centerId}', [GlobalRoomsChildrenController::class, 'getCenterStaff']);
    Route::get('global-child-parents/{childId}', [GlobalRoomsChildrenController::class, 'getChildParents']);
    Route::get('global-parent-children/{parentId}', [GlobalRoomsChildrenController::class, 'getParentChildren']);
    Route::post('rooms/update', [RoomController::class, 'rooms_update']);
});

Route::middleware('auth:sanctum')->post('user/change-password', [ApiResetPasswordController::class, 'changePassword']);

Route::post('password/request-reset', [ApiResetPasswordController::class, 'requestReset']);
Route::post('password/verify-otp', [ApiResetPasswordController::class, 'verifyOtp']);
Route::post('password/update', [ApiResetPasswordController::class, 'updatePassword']);




// Public Holiday API (CRUD & filter)

Route::middleware('auth:sanctum')->group(function () {
    // List/filter holidays
    Route::get('getholidays', [PublicHolidayController::class, 'index']); // supports ?month=, ?date=

    // Add new holiday
    Route::post('createholidays', [PublicHolidayController::class, 'store']);

    // Edit holiday
    Route::put('editholidays/{id}', [PublicHolidayController::class, 'update']);

    // Delete single holiday
    Route::delete('deleteholidays/{id}', [PublicHolidayController::class, 'destroy']);

    // Bulk delete
    Route::post('bulkdeleteholidays', [PublicHolidayController::class, 'deleteSelected']);
});


Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});    

Route::post('/login', [LoginController::class, 'login']);
Route::post('/store', [RagisterController::class, 'store']);



Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('qip')->name('qip.')->group(function () {
        Route::get('/index', [QipController::class, 'index'])->name('index');
        Route::post('/addnew', [QipController::class, 'addnost'])->name('addnew');
        Route::post('/update-name', [QipController::class, 'updateName'])->name('update.name');
        Route::delete('/delete/{id}', [QipController::class, 'destroy'])->name('delete');
        Route::get('/{id}/area/{area}', [QipController::class, 'viewArea'])->name('area.view');
        Route::post('/discussion/send', [QipController::class, 'sendDiscussion'])->name('discussion.send');
    });
});

Route::prefix('re-enrollment')->name('re-enrollment.')->group(function () {
    Route::get('/index', [ReEnrolmentController::class, 'dashboard'])->name('index');
    Route::get('/form', [ReEnrolmentController::class, 'createForm'])->name('form');
    Route::post('/store', [ReEnrolmentController::class, 'storeForm'])->name('store');
    Route::get('/form-options', [ReEnrolmentController::class, 'formOptions'])->name('form-options');
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/formdashboard', [ReEnrolmentController::class, 'dashboard'])->name('dashboard');
        Route::get('/details/{id?}', [ReEnrolmentController::class, 'getDetails'])->name('details');
        Route::post('/send-email', [ReEnrolmentController::class, 'sendEnrollmentEmail'])->name('send-email');
        Route::post('/filter', [ReEnrolmentController::class, 'filterSubmissions'])->name('filter');
        Route::get('/print/{id}', [ReEnrolmentController::class, 'printSubmission'])->name('print');
    });
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
// Route::get('/login',[LoginController::class,'login'])->name('login');
// });

Route::middleware('auth:sanctum')->prefix('recycle')->name('recycle.')->group(function () {
    Route::get('/modules', [ApiRecycleBinController::class, 'modules'])->name('modules');
    Route::get('/program-plans', [ApiRecycleBinController::class, 'programPlans'])->name('program-plans');
    Route::get('/observations', [ApiRecycleBinController::class, 'observations'])->name('observations');
    Route::get('/reflections', [ApiRecycleBinController::class, 'reflections'])->name('reflections');
    Route::get('/snapshots', [ApiRecycleBinController::class, 'snapshots'])->name('snapshots');

    Route::post('/program-plans/{id}/restore', [ApiRecycleBinController::class, 'restoreProgramPlan'])->name('program-plans.restore');
    Route::delete('/program-plans/{id}', [ApiRecycleBinController::class, 'forceDeleteProgramPlan'])->name('program-plans.force-delete');

    Route::post('/observations/{id}/restore', [ApiRecycleBinController::class, 'restoreObservation'])->name('observations.restore');
    Route::delete('/observations/{id}', [ApiRecycleBinController::class, 'forceDeleteObservation'])->name('observations.force-delete');

    Route::post('/reflections/{id}/restore', [ApiRecycleBinController::class, 'restoreReflection'])->name('reflections.restore');
    Route::delete('/reflections/{id}', [ApiRecycleBinController::class, 'forceDeleteReflection'])->name('reflections.force-delete');

    Route::post('/snapshots/{id}/restore', [ApiRecycleBinController::class, 'restoreSnapshot'])->name('snapshots.restore');
    Route::delete('/snapshots/{id}', [ApiRecycleBinController::class, 'forceDeleteSnapshot'])->name('snapshots.force-delete');
});


Route::middleware('auth:sanctum')->group(function () {

            Route::get('announcements/events', [Dashboard::class, 'getEvents'])->name('announcements.events');
             Route::get('parent-dashboard', [Dashboard::class, 'parentDashboard'])->name('parent.dashboard');
             Route::post('parent-dashboard/selected-child', [Dashboard::class, 'saveSelectedChild'])->name('parent.dashboard.selected-child');
             Route::get('parent-dashboard/selected-child', [Dashboard::class, 'getSelectedChild'])->name('parent.dashboard.selected-child');
             Route::post('user/selected-center', [Dashboard::class, 'saveSelectedCenter'])->name('user.selected-center.store');
             Route::get('user/selected-center', [Dashboard::class, 'getSelectedCenter'])->name('user.selected-center.show');
            
             Route::get('universal-dashboard', [Dashboard::class, 'universalDashboard'])->name('dashboard.universal');
// Route::get('/username-suggestions', [UserController::class, 'getUsernameSuggestions']);
// Route::get('/check-username-exists', [UserController::class, 'checkUsernameExists']);
Route::get('dashboard/analytical', [Dashboard::class, 'analytical'])->name('dashboard.analytical');
Route::get('/api/events', [Dashboard::class, 'getEvents']);
//   Route::get('/dashboard', [Dashboard::class, 'university']);
    Route::get('/newdashboard', [Dashboard::class, 'newdashboard']);
     Route::get('/dashboard', [Dashboard::class, 'university'])->name('dashboard.university');
     Route::get('users/birthday', [Dashboard::class, 'getUser'])->name('users..birthday');
     Route::get('/api/events', [Dashboard::class, 'getEvents']);

           Route::get('/slideshow', [ParentSlideshowController::class, 'getSlideshowData']);



        Route::prefix('snapshot')->name('snapshot.')->group(function () {

        Route::get('/index', [ObservationsController::class, 'snapshotindex'])->name('index');
        Route::get('/mernindex', [ObservationsController::class, 'mernsnapshotindex'])->name('mernindex');
        Route::post('/mernsnapshotfilters', [ObservationsController::class, 'mernsnapshotapplyFilters'])->name('mernsnapshotfilters');

        Route::get('/addnew', [ObservationsController::class, 'snapshotindexstorepage'])->name('addnew');
        // Route::get('/addnew/{id?}', [ObservationsController::class, 'snapshotindexstorepage'])->name('addnew.optional');
        Route::post('/store', [ObservationsController::class, 'snapshotstore'])->name('store');
        // API endpoint for snapshot PDF print
        Route::get('/print/{id}', [ObservationsController::class, 'print_snapshots'])->name('print');
        Route::delete('/snapshot-media/{id}', [ObservationsController::class, 'snapshotdestroyimage']);
        Route::post('/status/update', [ObservationsController::class, 'snapshotupdateStatus'])->name('status.update');
        Route::delete('snapshotsdelete/{id}', [ObservationsController::class, 'snapshotsdelete'])->name('snapshots.snapshotsdelete');
    });

       Route::prefix('learningandprogress')->name('learningandprogress.')->group(function () {

        Route::get('/index', [LnPcontroller::class, 'index'])->name('index');
        Route::get('/lnpdata', [LnPcontroller::class, 'lnpData'])->name('lnpdata');
        Route::post('/update-assessment-status', [LnPcontroller::class, 'updateAssessmentStatus'])->name('update.assessment.status');
    });

  Route::get('/centers',[LessonPlanList::class,'centers'])->name('centers');
    // program plan
        Route::get('/programPlanList',[LessonPlanList::class,'programPlanList'])->name('programPlanList');
        Route::get('/mernprogramPlanList',[LessonPlanList::class,'mernprogramPlanList'])->name('mernprogramPlanList');
                Route::get('/LessonPlanList/filter-program-plans', [LessonPlanList::class, 'filterProgramPlan'])->name('filter-program-plans');
                Route::get('/LessonPlanList/mernfilter-program-plans', [LessonPlanList::class, 'mernfilterProgramPlan'])->name('mernfilter-program-plans');
                Route::post('/LessonPlanList/mernfilter-program-plans', [LessonPlanList::class, 'mernfilterProgramPlan'])->name('mernfilter-program-plans');
                Route::post('/LessonPlanList/filter-program-plans', [LessonPlanList::class, 'filterProgramPlan'])->name('filter-program-plans.post');
        Route::get('/programPlan/{id}', [LessonPlanList::class, 'getProgramPlanById'])->where('id', '[0-9]+');
    Route::post('LessonPlanList/deletedataofprogramplan',[LessonPlanList::class,'deleteProgramPlan'])->name('LessonPlanList.deletedataofprogramplan');
    Route::get('/programPlan/print', [LessonPlanList::class, 'programplanprintpage']);
    Route::get('/program-plan/pdf/{id}', [LessonPlanList::class, 'generatePDF']);
    Route::post('/LessonPlanList/get_room_users', [LessonPlanList::class, 'getRoomUsers'])->name('LessonPlanList.get_room_users');
    Route::post('/LessonPlanList/get_room_children', [LessonPlanList::class, 'getRoomChildren'])->name('LessonPlanList.get_room_children');
    Route::get('/LessonPlanList/eylf', [LessonPlanList::class, 'getProgramPlanEylf'])->name('LessonPlanList.eylf');
    Route::get('/LessonPlanList/eylf-full', [LessonPlanList::class, 'getProgramPlanEylfFull'])->name('LessonPlanList.eylf_full');
    Route::get('/LessonPlanList/montessori', [LessonPlanList::class, 'getProgramPlanMontessori'])->name('LessonPlanList.montessori');
    Route::post('/LessonPlanList/subactivities', [LessonPlanList::class, 'getProgramPlanSubActivities'])->name('LessonPlanList.subactivities');
    Route::post('/LessonPlanList/eylf-subactivities', [LessonPlanList::class, 'getProgramPlanEylfSubActivities'])->name('LessonPlanList.eylf_subactivities');
    Route::post('/LessonPlanList/save_program_planinDB', [LessonPlanList::class, 'saveProgramPlan'])->name('LessonPlanList.save_program_planinDB');
    Route::post('/programPlan/autosave', [LessonPlanList::class, 'programplanAutosave'])->name('programplan.autosave');
    Route::post('/programplan/MonthYear', [LessonPlanList::class, 'programplanMonthYear'])->name('programplan.MonthYear');
    Route::get('/programPlan/create',[LessonPlanList::class,'createForm'])->name('create.programplan');
    Route::post('/programPlan',[LessonPlanList::class,'store'])->name('store.programPlan');
Route::post('/update-program-plan-status',[LessonPlanList::class,'updatestatus'])->name('update-program-plan-status');

    // New dedicated Program Plan API create flow: subject -> module -> submodule
    Route::prefix('program-plan')->name('program-plan.')->group(function () {
        Route::get('/subjects', [ProgramPlanApiController::class, 'subjects'])->name('subjects');
        Route::get('/modules', [ProgramPlanApiController::class, 'modules'])->name('modules');
        Route::get('/submodules', [ProgramPlanApiController::class, 'subModules'])->name('submodules');
        Route::post('/store', [ProgramPlanApiController::class, 'store'])->name('store');
    });
    

    // service details 
        Route::get('ServiceDetails', [ServiceDetailsController::class, 'index'])->name('create.serviceDetails');
    Route::post('ServiceDetails', [ServiceDetailsController::class, 'store'])->name('store.serviceDetails');

    // annoucement
    Route::get('announcements/list',[AnnouncementController::class,'list'])->name('announcements.list');
    Route::get('announcements/mernlist',[AnnouncementController::class,'mernlist'])->name('announcements.mernlist');
    Route::get('announcements/filterlist',[AnnouncementController::class,'Filterlist'])->name('announcements.Filterlist');
Route::get('announcements/create',[AnnouncementController::class,'AnnouncementCreate'])->name('announcements.create');
Route::post('announcements/store',[AnnouncementController::class,'AnnouncementStore'])->name('announcements.store');
Route::delete('announcements/delete',[AnnouncementController::class,'AnnouncementDelete'])->name('announcements.delete');
Route::get('announcements/view',[AnnouncementController::class,'AnnouncementView'])->name('announcements.view');

// headchecks
Route::get('headChecks',[HeadChecks::class,'index'])->name('headChecks');
Route::post('headchecks/store',[HeadChecks::class,'headchecksStore'])->name('headchecks.store');
Route::post('headchecks/getCenterRooms',[HeadChecks::class,'getCenterRooms'])->name('headchecks.getCenterRooms');
Route::post('headcheckdelete',[HeadChecks::class,'headcheckDelete'])->name('headcheck.delete');
Route::get('headChecks/print', [HeadChecks::class, 'print'])->middleware('auth:sanctum');
Route::get('headChecks/view', [HeadChecks::class, 'weekTable'])->middleware('auth:sanctum');


// sleep checks
Route::get('sleepcheck/list',[SleepCheckController::class,'getSleepChecksList'])->name('sleepcheck.list');
Route::get('mernsleepcheck/list',[SleepCheckController::class,'getmernSleepChecksList'])->name('mernsleepcheck.list');
Route::post('sleepcheck/save',[SleepCheckController::class,'sleepcheckSave'])->name('sleepcheck.save');
Route::post('sleepcheck/update',[SleepCheckController::class,'sleepcheckUpdate'])->name('sleepcheck.update');
Route::post('sleepcheck/delete',[SleepCheckController::class,'sleepcheckDelete'])->name('sleepcheck.delete');
Route::post('sleepcheck/bulk-save', [SleepCheckController::class, 'bulkSave'])->name('sleepcheck.bulk_save');

// Accidents
Route::match(['get', 'post'], 'Accidents/list',[AccidentsController::class,'AccidentsList'])->name('Accidents.list');
Route::match(['get', 'post'], 'Accidents/mernlist',[AccidentsController::class,'mernAccidentsList'])->name('Accidents.mernlist');
Route::post('Accidents/getCenterRooms',[AccidentsController::class,'getCenterRooms'])->name('Accidents.getCenterRooms');
Route::put('Accidents/update/{id}',[AccidentsController::class,'AccidentsUpdate'])->name('accidents.update');
Route::match(['get', 'post'], 'Accidents/details',[AccidentsController::class,'getAccidentDetails'])->name('Accidents.details');
Route::post('Accidents/sendEmail',[AccidentsController::class,'sendEmail'])->name('Accidents.sendEmail');
Route::match(['get', 'post'], 'Accidents/downloadPdf', [AccidentsController::class, 'downloadPdf'])->name('Accidents.downloadPdf');
Route::match(['get', 'post'], 'Accidents/create',[AccidentsController::class,'create'])->name('Accidents.create');
Route::match(['get', 'post'], 'Accidents/edit',[AccidentsController::class,'AccidentEdit'])->name('Accidents.edit');
Route::get('accidents/form-data', [AccidentsController::class, 'create'])->name('accidents.form-data');
Route::post('Accident/saveAccident',[AccidentsController::class,'saveAccident'])->name('Accidents.saveAccident');
Route::post('Accident/getChildDetails',[AccidentsController::class,'getChildDetails'])->name('Accident/getChildDetails');
    Route::post('Accident/delete', [AccidentsController::class, 'AccidentDelete'])->name('Accident.delete');
// rooms
    Route::get('/room/{roomid}/children', [RoomController::class, 'showChildren'])->name('room.children');
    Route::get('/edit-child/{id}', [RoomController::class, 'edit_child'])->name('edit_child');
    Route::post('/child/update', [RoomController::class, 'update_child'])->name('update_child');
    Route::post('/move-children', [RoomController::class, 'moveChildren'])->name('move_children');
    Route::post('/children/delete-selected', [RoomController::class, 'delete_selected_children'])->name('delete_selected_children');
    Route::post('/children/filter', [RoomController::class, 'filterChildren'])->name('children.filter');
    Route::get('/staffs', [RoomController::class, 'staffs'])->name('staffs');
    Route::post('/room/assign-staff', [RoomController::class, 'assignStaffToRoom'])->name('room.assign_staff');
    Route::post('add-children', [RoomController::class, 'add_new_children'])->name('add_children');
    Route::match(['get', 'post'], '/rooms', [RoomController::class, 'rooms_list'])->name('rooms_list');

    Route::post('/room-create', [RoomController::class, 'rooms_create'])->name('room_create');
    Route::post('/rooms/bulk-delete', [RoomController::class, 'bulkDelete'])->name('rooms.bulk_delete');

    Route::prefix('observation-api')->name('observation-api.')->group(function () {
        Route::get('/form-data/{id?}/{activeTab?}/{activesubTab?}', [ObservationApiController::class, 'formData'])->name('form-data');
        Route::post('/store', [ObservationApiController::class, 'storeObservation'])->name('store');
        Route::get('/view/{id}', [ObservationApiController::class, 'show'])->name('view');
        Route::post('/status/update', [ObservationApiController::class, 'updateObservationStatus'])->name('status.update');

        Route::get('/subjects', [ObservationApiController::class, 'getSubjects'])->name('subjects');
        Route::get('/modules', [ObservationApiController::class, 'getActivitiesBySubject'])->name('modules');
        Route::get('/submodules', [ObservationApiController::class, 'getSubActivitiesByActivity'])->name('submodules');

        Route::prefix('devmilestone')->name('devmilestone.')->group(function () {
            Route::get('/subjects', [ObservationApiController::class, 'getDevelopmentSubjects'])->name('subjects');
            Route::get('/modules', [ObservationApiController::class, 'getDevelopmentModules'])->name('modules');
            Route::get('/submodules', [ObservationApiController::class, 'getDevelopmentSubModules'])->name('submodules');
        });

        Route::post('/montessori', [ObservationApiController::class, 'saveMontessoriData'])->name('montessori.save');
        Route::post('/eylf', [ObservationApiController::class, 'saveEylfData'])->name('eylf.save');
        Route::post('/development-milestone', [ObservationApiController::class, 'saveDevelopmentMilestone'])->name('development-milestone.save');

        Route::get('/link/observation', [ObservationApiController::class, 'linkObservationData'])->name('link.observation');
        Route::post('/link/observation', [ObservationApiController::class, 'storeLinkedObservation'])->name('link.observation.store');

        Route::get('/link/reflection', [ObservationApiController::class, 'linkReflectionData'])->name('link.reflection');
        Route::post('/link/reflection', [ObservationApiController::class, 'storeLinkedReflection'])->name('link.reflection.store');

        Route::get('/link/program-plan', [ObservationApiController::class, 'linkProgramPlanData'])->name('link.program-plan');
        Route::post('/link/program-plan', [ObservationApiController::class, 'storeLinkedProgramPlan'])->name('link.program-plan.store');
    });

    // observation
        Route::post('Observation/addActivity', [ObservationController::class, 'addActivity'])->name('Observation.addActivity');
    Route::post('Observation/addSubActivity', [ObservationController::class, 'addSubActivity'])->name(' Observation.addSubActivity');
    Route::post('Observation/updateActivity', [ObservationController::class, 'updateActivity'])->name('Observation.updateActivity');
    Route::post('Observation/updateSubActivity', [ObservationController::class, 'updateSubActivity'])->name('Observation.updateSubActivity');
    Route::post('Observation/deleteActivity', [ObservationController::class, 'deleteActivity'])->name('Observation.deleteActivity');
    Route::post('Observation/deleteSubActivity', [ObservationController::class, 'deleteSubActivity'])->name('Observation.deleteSubActivity');
      Route::get('Observation/getSubjects', [ObservationController::class, 'getSubjects'])->name('Observation.getSubjects');
    Route::get('Observation/getActivitiesBySubject', [ObservationController::class, 'getActivitiesBySubject'])->name('Observation.getActivitiesBySubject');
    Route::get('Observation/getSubActivitiesByActivity', [ObservationController::class, 'getSubActivitiesByActivity'])->name('Observation.getSubActivitiesByActivity');
    

    // observations

    Route::prefix('observation')->name('observation')->group(function () {

    // Observation comments API
    Route::get('/{observationId}/comments', [ObservationsController::class, 'listComments']);
    Route::post('/{observationId}/comments', [ObservationsController::class, 'addComment']);
    Route::delete('/{observationId}/comments/{commentId}', [ObservationsController::class, 'deleteComment']);
    Route::delete('/delete/{id}', [ObservationsController::class, 'destroy'])->name('delete');

        Route::get('/index', [ObservationsController::class, 'index'])->name('index');
        Route::get('/mernindex', [ObservationsController::class, 'mernindex'])->name('mernindex');
        Route::get('/get-children', [ObservationsController::class, 'getChildren'])->name('get-children');
        Route::get('/get-staff', [ObservationsController::class, 'getStaff'])->name('get-staff');
        Route::post('/filters', [ObservationsController::class, 'applyFilters'])->name('filters');
        Route::post('/mernfilters', [ObservationsController::class, 'mernapplyFilters'])->name('mernfilters');
        Route::get('/view', [ObservationsController::class, 'index'])->name('view');
        Route::get('/print', [ObservationsController::class, 'print'])->name('print');


        Route::get('/addnew', [ObservationsController::class, 'storepage'])->name('addnew');
        // Route::get('/addnew/{id?}/{tab?}/{tab2?}', [ObservationsController::class, 'storepage'])->name('addnew.optional');


        Route::get('/get-children', [ObservationsController::class, 'getChildren'])->name('get.children');
        Route::get('/get-rooms', [ObservationsController::class, 'getrooms'])->name('get.rooms');
        Route::post('/store', [ObservationsController::class, 'store'])->name('store');
        Route::post('/refine-text', [ObservationsController::class, 'refine'])->name('refine.text');


        
        // Share observation via email (API)
        Route::post('/share', [ObservationsController::class, 'shareObservationApi']);
        Route::delete('/observation-media/{id}', [ObservationsController::class, 'destroyimage']);
        Route::post('/observation-media', [ObservationsController::class, 'destroyimage']);

        Route::post('/montessori/store', [ObservationsController::class, 'storeMontessoriData'])->name('montessori.store');
        Route::post('/eylf/store', [ObservationsController::class, 'storeEylfData'])->name('eylf.store');
        Route::post('/devmilestone/store', [ObservationsController::class, 'storeDevMilestone'])->name('devmilestone.store');
        Route::post('/status/update', [ObservationsController::class, 'updateStatus'])->name('status.update');
        Route::get('/view/{id}', [ObservationsController::class, 'view'])->name('view');
        Route::get('/observationslink', [ObservationsController::class, 'linkobservationdata']);
        Route::post('/submit-selectedoblink', [ObservationsController::class, 'storelinkobservation']);
        
       Route::get('/reflectionslink', [ObservationsController::class, 'linkreflectiondata']);
        Route::post('/submit-selectedreflink', [ObservationsController::class, 'storelinkreflection']);

        Route::get('/programplanslink', [ObservationsController::class, 'linkprogramplandata']);
        Route::post('/submit-selectedpplink', [ObservationsController::class, 'storelinkprogramplan']);
    });


        Route::prefix('reflection')->name('reflection.')->group(function () {

        Route::get('/index', [ReflectionController::class, 'index'])->name('index');
        Route::get('/mernindex', [ReflectionController::class, 'mernindex'])->name('mernindex');

        Route::get('/addnew', [ReflectionController::class, 'storepage'])->name('addnew');
        Route::get('/addnew/{id?}', [ReflectionController::class, 'storepage'])->name('addnew.optional');
        Route::get('/view/{id}', [ReflectionController::class, 'showById'])->whereNumber('id')->name('view');
        Route::get('/print', [ReflectionController::class, 'print'])->name('print');


        Route::post('/store', [ReflectionController::class, 'store'])->name('store');

        Route::delete('/reflection-media/{id}', [ReflectionController::class, 'destroyimage']);
        Route::post('/reflection-media', [ReflectionController::class, 'destroyimage']);

        Route::post('/status/update', [ReflectionController::class, 'updateStatus'])->name('status.update');

        Route::delete('/delete/{id}', [ReflectionController::class, 'destroy'])->name('delete');

        Route::post('/filters', [ReflectionController::class, 'applyFilters'])->name('filters');
    });


        Route::prefix('settings')->name('settings.')->group(function () {
       Route::post('/updateStatusSuperadmin', [SettingsController::class, 'updateStatusSuperadmin'])->name('updateStatusSuperadmin');
        Route::get('/superadmin_settings', [SettingsController::class, 'superadminSettings'])->name('superadmin_settings');
        Route::post('/superadmin/delete', [SettingsController::class, 'destroy'])->name('superadmin.destroy');
        Route::post('/superadmin/store', [SettingsController::class, 'store'])->name('superadmin.store');
        Route::get('/superadmin/edit', [SettingsController::class, 'edit'])->name('superadmin.edit');
        Route::post('/superadmin/update', [SettingsController::class, 'update'])->name('superadmin.update');
        Route::get('/center_settings', [SettingsController::class, 'center_settings'])->name('center_settings');
        Route::post('/center_store', [SettingsController::class, 'center_store'])->name('center_store');
        Route::get('/center/edit', [SettingsController::class, 'center_edit'])->name('center.edit');
        Route::post('/center', [SettingsController::class, 'center_update'])->name('center.update');
        Route::post('/center/status/{id}', [SettingsController::class, 'changeCenterStatus'])->name('center.changeStatus');
        Route::delete('/center/{id}/destroy', [SettingsController::class, 'destroycenter'])->name('center.destroy');
        Route::post('/center/logo/update', [SettingsController::class, 'updateCenterLogo'])->name('center.logo.update');
 
        Route::get('/staff_settings', [SettingsController::class, 'staff_settings'])->name('staff_settings');

        Route::post('/staff/store', [SettingsController::class, 'staff_store'])->name('staff.store');

        Route::get('/staff/{id}/edit', [SettingsController::class, 'staff_edit'])->name('staff.edit');
        Route::post('/staff/update', [SettingsController::class, 'staff_update'])->name('staff.update');
        Route::delete('/staff/destroy/{id}', [SettingsController::class, 'staff_destroy'])->name('staff.destroy');
        Route::post('/settings/update-permissions', [SettingsController::class, 'updateUserPermissions'])->name('update_user_permissions');
        Route::post('/staff/wifi-access', [ApiWifiIPController::class, 'userwifiChangeStatus'])->name('staff.wifi-access');




        Route::get('/parent_settings', [SettingsController::class, 'parent_settings'])->name('parent_settings');
        Route::get('/manage_permissions', [SettingsController::class, 'manage_permissions'])->name('manage_permissions');
        Route::post('user/permissions', [SettingsController::class, 'show'])->name('allusers_permissions');
        Route::post('/parent/store', [SettingsController::class, 'parent_store'])->name('parent.store');
        Route::post('/assign-permissions', [SettingsController::class, 'assign_user_permissions'])->name('assign_permissions');
        Route::get('permissions-assigned', [SettingsController::class, 'assigned_permissions'])->name('assigned_permissions');
        Route::get('/roles', [SettingsController::class, 'role_list'])->name('roles.list');
        Route::post('/roles', [SettingsController::class, 'role_store'])->name('roles.store');
        Route::get('/roles/{id}', [SettingsController::class, 'role_show'])->name('roles.show');
        Route::post('/roles/{id}/permissions', [SettingsController::class, 'role_update_permissions'])->name('roles.permissions.update');
        Route::delete('/roles/{id}', [SettingsController::class, 'role_destroy'])->name('roles.destroy');

        Route::get('/parent/{id}/get', [SettingsController::class, 'getParentData']);
        Route::post('/parent/update', [SettingsController::class, 'parent_update'])->name('parent.update');
        Route::delete('/parent/destroy/{id}', [SettingsController::class, 'parent_destroy'])->name('parent.destroy');


        Route::get('/profile', [SettingsController::class, 'getprofile_page'])->name('profile');
        Route::post('/upload-profile-image', [SettingsController::class, 'uploadImage'])->name('upload.profile.image');
        Route::post('/profile/update/{id}', [SettingsController::class, 'profileupdate'])->name('profile.update');
        Route::post('/profile/change-password/{id}', [SettingsController::class, 'changePassword'])->name('profile.change-password');

        // Parent Email & Track Mails
        Route::post('/parent/send-email', [SettingsController::class, 'sendEmailToParent'])->name('parent.send-email');
        Route::get('/parent/track-mails', [SettingsController::class, 'trackMails'])->name('parent.track-mails');

        // WiFi / IP Management
        Route::prefix('ip-manage')->name('ip-manage.')->group(function () {
            Route::get('/', [ApiWifiIPController::class, 'index'])->name('index');
            Route::post('/store', [ApiWifiIPController::class, 'store'])->name('store');
            Route::get('/{id}', [ApiWifiIPController::class, 'show'])->name('show');
            Route::post('/{id}', [ApiWifiIPController::class, 'update'])->name('update');
            Route::post('/{id}/toggle', [ApiWifiIPController::class, 'toggleStatus'])->name('toggle');
            Route::delete('/{id}', [ApiWifiIPController::class, 'destroy'])->name('destroy');
        });
    });


});

Route::post('/forget-password', [ApiResetPassword::class, 'apiResetPassword']);
Route::post('/verify-otp', [ApiResetPassword::class, 'apiVerifyOtp']);
Route::post('/resend-otp', [ApiResetPassword::class, 'apiResendOtp']);
Route::post('/reset-password-update', [ApiResetPassword::class, 'apiUpdatePassword']);



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/ingredient-types', [ApiHealthyController::class, 'ingredientTypesIndex']);
    Route::post('/ingredient-types', [ApiHealthyController::class, 'ingredientTypesStore']);
    Route::post('/ingredient-types/{id}', [ApiHealthyController::class, 'ingredientTypesUpdate']);
    Route::delete('/ingredient-types/{id}', [ApiHealthyController::class, 'ingredientTypesDestroy']);
    Route::post('/ingredients/move-type', [ApiHealthyController::class, 'moveIngredientToType']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::match(['get', 'post'], '/healthy-menu', [ApiHealthyController::class, 'apiHealthyMenu']);
    Route::get('/get-recipes-by-type', [ApiHealthyController::class, 'apiGetRecipesByType']);
    Route::post('/save-recipes', [ApiHealthyController::class, 'apiStoreMenu']);
    Route::delete('/menu/{id}', [ApiHealthyController::class, 'apiMenuDestroy']);
    Route::match(['get', 'post'], '/healthy-recipes', [ApiHealthyController::class, 'apiHealthyRecipe']);
    Route::get('/recipe/edit/{id}', [ApiHealthyController::class, 'apiEditRecipe']);
    Route::post('/recipe/update', [ApiHealthyController::class, 'apiUpdateRecipe']);
    Route::post('/recipe/update/{id}', [ApiHealthyController::class, 'apiUpdateRecipe']);
    Route::delete('/recipe/delete/{id}', [ApiHealthyController::class, 'apiDestroyRecipe']);
    Route::post('/recipe/store', [ApiHealthyController::class, 'apiStoreRecipe']);
    Route::get('/ingredients', [ApiHealthyController::class, 'apiRecipeIngredients']);
    Route::get('/ingredients/edit/{id}', [ApiHealthyController::class, 'apiEditIngredient']);
    Route::post('/ingredient/update', [ApiHealthyController::class, 'apiUpdateIngredient']);
    Route::delete('/ingredient/{id}', [ApiHealthyController::class, 'destroyIngredient']);
    Route::post('/ingredient/store', [ApiHealthyController::class, 'ingredientsStore']);
    Route::get('/meal-types', [ApiHealthyController::class, 'getUniqueMealTypes']);


    // Daily Journel here
    Route::match(['get', 'post'],'DailyDiary/list', [DailyDiaryController::class, 'list'])->name('dailyDiary.apilist');
    Route::match(['get', 'post'],'mernDailyDiary/list', [DailyDiaryController::class, 'mernlist'])->name('merndailyDiary.apilist');

    Route::prefix('ptm')->group(function () {
        Route::get('/', [ApiPTMController::class, 'index']);
        Route::post('/', [ApiPTMController::class, 'store']);
        Route::get('/ptmroom', [ApiPTMController::class, 'getrooms']);
        Route::get('/children', [ApiPTMController::class, 'getChildren']);
        Route::get('/staff', [ApiPTMController::class, 'getStaff']);
        Route::get('/events', [ApiPTMController::class, 'getPtmEvents']);
        Route::get('/slots', [ApiPTMController::class, 'getSlots']);
        Route::get('/date-slots/{ptmid}', [ApiPTMController::class, 'getPtmDateSlots']);
        Route::post('/reschedule', [ApiPTMController::class, 'reschedulePtm']);
        Route::get('/details/{id}', [ApiPTMController::class, 'ptmDetails']);
        Route::get('/{ptm}/edit', [ApiPTMController::class, 'edit']);
        Route::post('/{ptm}/publish', [ApiPTMController::class, 'directPublish']);
        Route::post('/{ptm}/reschedule-staff/{childid}', [ApiPTMController::class, 'resupdateFromStaff']);
        Route::post('/{ptm}/bulk-reschedule', [ApiPTMController::class, 'bulkResupdate']);
        Route::delete('/{ptm}', [ApiPTMController::class, 'delete']);
        Route::get('/{ptm}', [ApiPTMController::class, 'view']);
    });
    Route::post('dailyDiary/storeBottle', [DailyDiaryController::class, 'storeBottle'])->name('dailyDiary.storeBottle');
    Route::post('dailyDiary/storeFood', [DailyDiaryController::class, 'storeFood'])->name('dailyDiary.storeFood');
    Route::post('dailyDiary/storeSleep', [DailyDiaryController::class, 'storeSleep'])->name('dailyDiary.storeSleep');
    Route::post('dailyDiary/storeToileting', [DailyDiaryController::class, 'storeToileting'])->name('dailyDiary.storeToileting');
    Route::post('dailyDiary/storeToiletingmern', [DailyDiaryController::class, 'storeToiletingMern3'])->name('dailyDiary.storeToiletingmern3');
    Route::post('dailyDiary/storeSunscreen', [DailyDiaryController::class, 'storeSunscreen'])->name('dailyDiary.storeSunscreen');
    // Route::post('dailyDiary/getItems', [DailyDiaryController::class, 'getItems'])->name('dailyDiary.getItems');
    Route::post('dailyDiary/addFoodRecord', [DailyDiaryController::class, 'addFoodRecord'])->name('dailyDiary.addFoodRecord');

    // Route::post('dailyDiary/addSleepRecord', [DailyDiaryController::class, 'addSleepRecord'])->name('dailyDiary.addSleepRecord');
    // Route::post('dailyDiary/addToiletingRecord', [DailyDiaryController::class, 'addToiletingRecord'])->name('dailyDiary.addToiletingRecord');
    // Route::post('dailyDiary/addSunscreenRecord', [DailyDiaryController::class, 'addSunscreenRecord'])->name('dailyDiary.addSunscreenRecord');
    // Route::post('dailyDiary/addBottle', [DailyDiaryController::class, 'addBottle'])->name('dailyDiary.addBottle');
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
    Route::post('/activities/late-snacks', [DailyDiaryController::class, 'storeSnacks']);
    Route::post('/activities/sunscreen', [DailyDiaryController::class, 'storeSunscreen']);
    Route::post('/activities/toileting', [DailyDiaryController::class, 'storeToileting']);
    Route::post('/activities/bottle', [DailyDiaryController::class, 'storeBottle']);

    Route::match(['post', 'delete'], '/activities/breakfast/delete/{id?}', [DailyDiaryController::class, 'deleteBreakfast'])->name('dailyDiary.deleteBreakfast');
    Route::match(['post', 'delete'], '/activities/morning-tea/delete/{id?}', [DailyDiaryController::class, 'deleteMorningTea'])->name('dailyDiary.deleteMorningTea');
    Route::match(['post', 'delete'], '/activities/lunch/delete/{id?}', [DailyDiaryController::class, 'deleteLunch'])->name('dailyDiary.deleteLunch');
    Route::match(['post', 'delete'], '/activities/afternoon-tea/delete/{id?}', [DailyDiaryController::class, 'deleteAfternoonTea'])->name('dailyDiary.deleteAfternoonTea');
    Route::match(['post', 'delete'], '/activities/late-snacks/delete/{id?}', [DailyDiaryController::class, 'deleteSnacks'])->name('dailyDiary.deleteSnacks');
    Route::match(['post', 'delete'], '/activities/sunscreen/delete/{id?}', [DailyDiaryController::class, 'deleteSunscreen'])->name('dailyDiary.deleteSunscreen');
    Route::match(['post', 'delete'], '/activities/toileting/delete/{id?}', [DailyDiaryController::class, 'deleteToileting'])->name('dailyDiary.deleteToileting');
    Route::match(['post', 'delete'], '/activities/sleep/delete/{id?}', [DailyDiaryController::class, 'deleteSleep'])->name('dailyDiary.deleteSleep');
    Route::match(['post', 'delete'], '/activities/bottle/delete/{id?}', [DailyDiaryController::class, 'deleteBottle'])->name('dailyDiary.deleteBottle');

    // Daily Journel Ends here


});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/notifications', [NotificationApiController::class, 'index']);
    Route::post('/notifications/read/{id}', [NotificationApiController::class, 'markAsRead']);
    Route::post('/notifications/mark-all-read', [NotificationApiController::class, 'markAllRead']);
});

Route::get('/test-parent-notification', function () {
    $service = app(\App\Services\Firebase\FirebaseNotificationService::class);
    $childIds = [230]; // Replace with real test child IDs
    $moduleType = 'observation'; // or 'reflection', 'diary', etc.
    $moduleId = 1381; // Replace with a real module record ID
    $createdBy = 1; // Replace with the user id who created the record
    $results = \App\Http\Controllers\API\DeviceController::notifyParentsModuleCreated(
        $childIds,
        $moduleType,
        $moduleId,
        $createdBy,
        $service
    );
    return $results;
});
});

// Recycle bin API endpoints for mobile/app clients
Route::middleware('auth:sanctum')->prefix('recycle')->name('recycle.')->group(function () {
    Route::post('/program-plan/{id}/restore', [\App\Http\Controllers\API\RecycleBinController::class, 'restoreProgramPlan']);
    Route::delete('/program-plan/{id}', [\App\Http\Controllers\API\RecycleBinController::class, 'forceDeleteProgramPlan']);

    Route::post('/observation/{id}/restore', [\App\Http\Controllers\API\RecycleBinController::class, 'restoreObservation']);
    Route::delete('/observation/{id}', [\App\Http\Controllers\API\RecycleBinController::class, 'forceDeleteObservation']);

    Route::post('/reflection/{id}/restore', [\App\Http\Controllers\API\RecycleBinController::class, 'restoreReflection']);
    Route::delete('/reflection/{id}', [\App\Http\Controllers\API\RecycleBinController::class, 'forceDeleteReflection']);
 
    Route::post('/snapshot/{id}/restore', [\App\Http\Controllers\API\RecycleBinController::class, 'restoreSnapshot']);
    Route::delete('/snapshot/{id}', [\App\Http\Controllers\API\RecycleBinController::class, 'forceDeleteSnapshot']);
});