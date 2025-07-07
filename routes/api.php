<?php

use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\RagisterController;
use App\Http\Controllers\API\ApiResetPassword;
use App\Http\Controllers\API\ApiHealthyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

Route::post('/login', [LoginController::class, 'login']);
Route::post('/store', [RagisterController::class, 'store']);

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




});
