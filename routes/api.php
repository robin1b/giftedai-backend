<?php
use App\Http\Controllers\Api\V1\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/status', function () {
    return ["status" => "API is working!"];
});



Route::prefix('v1')->group(function () {
    Route::apiResource('/posts', PostController::class);
});