<?php

use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('posts',PostController::class);

// Route::apiResource('users', UserController::class);

Route::prefix('users')
    ->name('users.')
    ->group(function(){
        Route::get('/',[UserController::class,'index'])->name('index');
        Route::post('/',[UserController::class,'store'])->name('store');
        Route::get('/{user}',[UserController::class,'show'])->name('show');
        Route::put('/{user}',[UserController::class,'update'])->name('update');
        Route::delete('/{user}',[UserController::class,'destroy'])->name('destroy');
});

// Route::apiResource('images',ImageController::class);
// // Or

Route::get('images',[ImageController::class,'index']);
Route::get('images/{id}',[ImageController::class,'show']);
Route::post('images',[ImageController::class,'store']);
Route::put('images/{id}',[ImageController::class,'update']);
Route::delete('images/{id}',[ImageController::class,'destroy']);
