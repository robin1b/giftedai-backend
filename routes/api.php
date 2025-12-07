<?php

use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\BlogGenerationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::prefix('v1')->group(function () {

        Route::apiResource('/posts', PostController::class);
        Route::get('/blog-generations', [BlogGenerationController::class, 'index']);
        Route::post('/generate', [BlogGenerationController::class, 'generate'])
            ->middleware('throttle:ai-generate');
        Route::post('/generate/save', [BlogGenerationController::class, 'saveAsBlogPost']);
    });
});
Route::prefix('v1')->group(function () {
    Route::get('/public-posts', [PostController::class, 'publicIndex']);
    Route::get('/public-posts/{id}', [PostController::class, 'publicShow']);
});
require __DIR__ . '/auth.php';
