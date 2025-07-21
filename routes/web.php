<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

Route::redirect('/', 'login', 301);

/* Auth Routes */
Auth::routes(['register' => false, 'reset' => false, 'verify' => false, 'confirm' => false]);
/* Auth Routes */

/* Common Routes */
Route::middleware('auth')->group(function () {
    Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');

    /* Role routes */
    Route::get('role/check-name-unique/{role?}', [RoleController::class, 'checkNameUnique'])->name('role.check-name-unique');
    Route::resource('role', RoleController::class)->except(['show']);
    /* Role routes */

    /* User routes */
    Route::get('user/check-email-unique/{user?}', [UserController::class, 'checkEmailUnique'])->name('user.check-email-unique');
    Route::get('user/check-phone-unique/{user?}', [UserController::class, 'checkPhoneUnique'])->name('user.check-phone-unique');
    Route::resource('user', UserController::class)->except(['show']);
    /* User routes */

    /* Order routes */
    Route::resource('order', OrderController::class)->except(['show']);
    /* Order routes */

    /* Profile */
    Route::post('profile/update-password', [ProfileController::class, 'update_password'])->name('profile.update-password');
    Route::resource('profile', ProfileController::class)->only(['index', 'update']);
    /* Profile */
});
/* Common Routes */

/* Global Routes Without Auth */
Route::get('states', function (Request $request) {
    return response()->json(["status" => true, "data" => getStates($request->id)]);
})->name("globals.states");

Route::get('cities', function (Request $request) {
    return response()->json(["status" => true, "data" => getCities($request->id)]);
})->name("globals.cities");
/* Global Routes Without Auth */
