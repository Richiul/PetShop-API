<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\MainPageController;
use App\Http\Controllers\OrdersController;
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

Route::controller(UserController::class)->prefix('v1/user')->group(function () {
    Route::post('/login', 'login')->name('user.login')->middleware('authorized.user');
    Route::post('/create', 'register')->name('user.create');
    Route::post('/forgot-password', 'forgotPassword')->name('user.forgot.password');
    Route::post('/reset-password-token', 'resetPasswordToken')->name('user.reset.password.token');
    Route::get('/', 'index')->name('user.view');
    Route::put('/edit', 'edit')->name('user.edit');
    Route::delete('/', 'delete')->name('user.delete');
    Route::get('/logout', 'logout')->name('user.logout')->middleware(['remove.token']);
});

Route::controller(AdminController::class)->prefix('v1/admin')->group(function() {
    Route::post('/create','register')->name('admin.create');
    Route::post('/login','login')->name('admin.login');
    Route::get('/logout', 'logout')->name('admin.logout')->middleware(['remove.token']);
    Route::get('/user-listing', 'index')->name('admin.user.list');
    Route::put('/user-edit/{uuid}', 'edit')->name('admin.user.edit');
    Route::delete('/user-delete/{uuid}', 'delete')->name('admin.user.delete');
});

Route::controller(MainPageController::class)->prefix('v1/main')->group(function() {
    Route::get('/blog','index')->name('main.view.posts');
    Route::get('/blog/{uuid}','post')->name('main.view.post');
    Route::get('/promotions','promotions')->name('main.view.promotions');
});

Route::controller(BrandsController::class)->prefix('v1')->group(function() {
    Route::get('brands','index')->name('brand.view.brands');
    Route::post('/brand/create','create')->name('brand.create');
    Route::put('/brand/{uuid}','edit')->name('brand.edit');
    Route::delete('/brand/{uuid}','delete')->name('brand.delete');
    Route::get('/brand/{uuid}','brand')->name('brand.view.brand');
});

Route::controller(CategoriesController::class)->prefix('v1')->group(function() {
    Route::get('categories','index')->name('category.view.categories');
    Route::post('/category/create','create')->name('category.create');
    Route::put('/category/{uuid}','edit')->name('category.edit');
    Route::delete('/category/{uuid}','delete')->name('category.delete');
    Route::get('/category/{uuid}','category')->name('category.view.category');
});


