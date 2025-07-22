<?php

use App\Http\Controllers\FormController;
use App\Http\Controllers\HomeController;
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
    Route::redirect('dashboard', 'form')->name('dashboard');

    /* Role routes */
    Route::get('role/check-name-unique/{role?}', [RoleController::class, 'checkNameUnique'])->name('role.check-name-unique');
    Route::resource('role', RoleController::class)->except(['show']);
    /* Role routes */

    /* User routes */
    Route::get('user/check-email-unique/{user?}', [UserController::class, 'checkEmailUnique'])->name('user.check-email-unique');
    Route::get('user/check-phone-unique/{user?}', [UserController::class, 'checkPhoneUnique'])->name('user.check-phone-unique');
    Route::resource('user', UserController::class)->except(['show']);
    /* User routes */

    /* User routes */
    Route::get('form/check-name-unique/{form?}', [FormController::class, 'checkNameUnique'])->name('form.check-name-unique');
    Route::get('form/{form}/titles', [FormController::class, 'title_get'])->name('form.titles.item_get');
    Route::post('form/{form}/titles', [FormController::class, 'title_add'])->name('form.titles.item_add');
    Route::put('form/{form}/titles/{detailid}', [FormController::class, 'title_update'])->name('form.titles.item_update');
    Route::delete('form/{form}/titles/{detailid}', [FormController::class, 'title_delete'])->name('form.titles.item_delete');
    Route::get('form/{form}/images', [FormController::class, 'image_get'])->name('form.images.item_get');
    Route::post('form/{form}/images', [FormController::class, 'image_add'])->name('form.images.item_add');
    Route::put('form/{form}/images/{detailid}', [FormController::class, 'image_update'])->name('form.images.item_update');
    Route::delete('form/{form}/images/{detailid}', [FormController::class, 'image_delete'])->name('form.images.item_delete');
    Route::get('form/{form}/rich-texts', [FormController::class, 'rich_text_get'])->name('form.rich-texts.item_get');
    Route::post('form/{form}/rich-texts', [FormController::class, 'rich_text_add'])->name('form.rich-texts.item_add');
    Route::put('form/{form}/rich-texts/{detailid}', [FormController::class, 'rich_text_update'])->name('form.rich-texts.item_update');
    Route::delete('form/{form}/rich-texts/{detailid}', [FormController::class, 'rich_text_delete'])->name('form.rich-texts.item_delete');
    Route::resource('form', FormController::class)->except(['show']);
    /* User routes */

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
