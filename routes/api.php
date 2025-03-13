<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('auth:api')->group(function () {
    Route::post('/articles', [ArticleController::class, 'create']);
    Route::put('/articles/{article}', [ArticleController::class, 'update']);
    Route::delete('/articles/{article}', [ArticleController::class, 'delete']);
    Route::post('/articles/{article}/vote', [ArticleController::class, 'vote']);
    Route::get('/my-articles', [ArticleController::class, 'myArticles']);
});

Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/top-categories', [CategoryController::class, 'top']);