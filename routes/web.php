<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\NoAccessController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('', function () { return view('welcome'); });
// Route::get('/', [HomeController::class, 'contentIndex'])->where('pageSlug', '.*');

// Auth::routes();
Auth::routes(['register' => false]);
Route::get('', [HomeController::class, 'index']);
Route::post('/inquiries', [InquiryController::class, 'store'])->name('inquiries.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', function () { return redirect('/admin/dashboard'); });
    Route::get('/admin/dashboard', [HomeController::class, 'dashboardIndex'])->middleware('admin:dashboard:view');

    Route::get('/admin/inquiries', [InquiryController::class, 'allInquiriesView'])->middleware('admin:inquiries:view')->name('inquiries.index');

    Route::get('/no-access', [NoAccessController::class, 'index'])->name('no-access');

    Route::get('/admin/users', [UserController::class, 'allUsersView'])->middleware('admin:users:view')->name('user.index');
    Route::get('/admin/add-user', [UserController::class, 'addUserView'])->middleware('admin:users:add')->name('user.add');
    Route::post('/user', [UserController::class, 'store'])->middleware('admin:users:add');
    Route::get('/admin/user/{id}', [UserController::class, 'editUserView'])->middleware('admin:users:edit')->name('user.edit');
    Route::put('/user-profile/{id}', [UserController::class, 'updateUser'])->middleware('admin:users:edit');
    Route::put('/user-password/{id}', [UserController::class, 'updateUserPassword'])->middleware('admin:users:edit');
    // Route::delete('/user/{id}', [UserController::class, 'deleteUser'])->middleware('admin:users:delete');
});
