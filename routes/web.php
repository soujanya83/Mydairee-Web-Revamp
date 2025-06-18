<?php

use App\Http\Controllers\AccidentsController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileManagerController;
use App\Http\Controllers\HealthyController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ClearCacheAfterLogout;
use Illuminate\Support\Facades\Auth;



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
    Route::post('/logout', function () {
        Auth::logout(); // Logs out the user
        session()->invalidate();      // Invalidate session
        session()->regenerateToken(); // Prevent CSRF issues
        return redirect('login'); // Redirect to login page
    })->name('logout');

    Route::get('/room/{roomid}/children', [RoomController::class, 'showChildren'])->name('room.children');
    Route::get('/edit-child/{id}', [RoomController::class, 'edit_child'])->name('edit_child');
    Route::put('/child/update/{id}', [RoomController::class, 'update_child'])->name('update_child');
    Route::post('/move-children', [RoomController::class, 'moveChildren'])->name('move_children');
    Route::post('/children/delete-selected', [RoomController::class, 'delete_selected_children'])->name('delete_selected_children');

    Route::post('add-children', [RoomController::class, 'add_new_children'])->name('add_children');
    Route::match(['get', 'post'], '/rooms', [RoomController::class, 'rooms_list'])->name('rooms_list');
    Route::post('/room-create', [RoomController::class, 'rooms_create'])->name('room_create');
    Route::delete('/rooms/bulk-delete', [RoomController::class, 'bulkDelete'])->name('rooms.bulk_delete');

    Route::match(['get', 'post'], '/healthy-recipe', [HealthyController::class, 'healthy_recipe'])->name('healthy_recipe');
    Route::get('/recipes/{id}/edit', [HealthyController::class, 'edit'])->name('recipes.edit');
    Route::delete('/recipes/{id}/delete', [HealthyController::class, 'destroy'])->name('recipes.destroy');
    Route::get('/recipes/ingredients', [HealthyController::class, 'recipes_Ingredients'])->name('recipes.Ingredients');
    Route::get('/ingredients/{id}/edit', [HealthyController::class, 'ingredients_edit'])->name('ingredients.edit');
    Route::delete('/ingredients/{id}/delete', [HealthyController::class, 'destroy_ingredent'])->name('ingredients.destroy');
    Route::post('/ingredients', [HealthyController::class, 'ingredients_store'])->name('ingredients.store');
    Route::put('/ingredients/{id}', [HealthyController::class, 'ingredients_update'])->name('ingredients.update');
    Route::post('/recipes/store', [HealthyController::class, 'recipes_store'])->name('recipes.store');

    Route::match(['get', 'post'], '/healthy-menu', [HealthyController::class, 'healthy_menu'])->name('healthy_menu');
    // Route::post('/store-menu', [HealthyController::class, 'store_menu'])->name('menu.store');
    Route::get('/get-recipes-by-type', [HealthyController::class, 'getByType']);
    Route::post('/save-recipes', [HealthyController::class, 'store_menu'])->name('menu.store');
    Route::delete('/menu/{id}', [HealthyController::class, 'menu_destroy'])->name('menu.destroy');



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
