<?php

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

Route::get('get-data/{slug}', function (Request $request) {
    $form = \App\Models\Form::with(['titles', 'images', 'rich_texts'])->where('slug', $request->slug)->first();
    if (empty($form)) {
        return response()->json(["status" => false, "message" => "Form not found."], 404);
    }

    foreach ($form->images as &$image) {
        $image->image = getStoredImage($image->image);
    }
    return response()->json(["status" => true, "message" => "Form found.", "data" => $form]);
});
