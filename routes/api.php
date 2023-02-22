<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\PasswordResetController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/send-reset-password-email',[PasswordResetController::class,'sent_reset_password_email']);
Route::post('/register',[UserController::class,'register']);
Route::post('/login',[UserController::class,'login']);
// Route::post('/store','App\Http\Controllers\Api\UserController@store');
Route::get('/get',[UserController::class,'index']);
Route::delete('delete/{id}',[UserController::class,'destroy']);
Route::put('update/{id}',[UserController::class,'update']);
Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/logout',[UserController::class,'logout']);
    Route::get('/loggeduser',[UserController::class,'logged_user']);
    Route::post('/changepassword',[UserController::class,'change_password']);
});
Route::post('/image',[ImageController::class, 'imageStore']);
Route::get('/get',[ImageController::class,'get']);