<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(UserController::class)->group(function () {
    Route::post('v1/user/login', 'login')->middleware('authorized.user');
    Route::post('v1/user/create', 'register');
    Route::post('v1/user/forgot-password', 'forgotPassword');
    Route::post('v1/user/reset-password-token', 'resetPasswordToken');
    Route::get('v1/user', 'index');
    Route::put('v1/user/edit', 'edit');
    Route::delete('v1/user', 'delete');
    Route::get('v1/user/logout', 'logout')->middleware(['remove.token']);
});

Route::controller(AdminController::class)->group(function() {
    Route::post('v1/admin/create','register');
    Route::post('v1/admin/login','login')->middleware('authorized.admin');
    Route::get('v1/admin/logout', 'logout')->middleware(['remove.token']);
    Route::get('v1/admin/user-listing', 'index');
    Route::put('v1/admin/user-edit/{uuid}', 'edit');
    Route::delete('v1/admin/user-delete/{uuid}', 'delete');

});
