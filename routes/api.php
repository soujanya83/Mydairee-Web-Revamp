<?php

use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\RagisterController;
use App\Http\Controllers\API\ApiResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



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
