<?php

use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\RagisterController;
use App\Http\Controllers\API\ApiResetPassword;
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






// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

Route::post('/login', [LoginController::class, 'login']);
Route::post('/store', [RagisterController::class, 'store']);
Route::post('/forget-password', [ApiResetPassword::class, 'apiResetPassword']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
// Route::get('/login',[LoginController::class,'login'])->name('login');
// });


Route::middleware('auth:sanctum')->group(function () {
        Route::get('/programPlanList',[LessonPlanList::class,'programPlanList'])->name('programPlanList');
    // Route::middleware('auth:sanctum')->get('programPlanList', [LessonPlanList::class, 'programPlanList']);


    Route::post('LessonPlanList/deletedataofprogramplan',[LessonPlanList::class,'deleteProgramPlan'])->name('LessonPlanList.deletedataofprogramplan');
    
    Route::get('/programPlan/print/{planId}', [LessonPlanList::class, 'programplanprintpage']);
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
   
});