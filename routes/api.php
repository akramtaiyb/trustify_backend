<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ExpertController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('users', UserController::class);
Route::apiResource('experts', ExpertController::class);
Route::apiResource('publications', PublicationController::class);
Route::apiResource('comments', CommentController::class);
Route::apiResource('votes', VoteController::class);
Route::apiResource('notifications', NotificationController::class);
