<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\PostController;

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

Route::apiResource('topics', TopicController::class);

Route::controller(PostController::class)->group(function () {
    Route::get('posts/{post}/messages', 'showMessages');
    Route::get('posts/{post}/replies', 'showReplies');
    Route::get('posts/{post}/reply_to', 'showReplyTo');
    Route::post('/topics/{topic}', 'storeThread');
    Route::post('/posts/{post}', 'storeMessage');
});

Route::apiResource('posts', PostController::class)->except(['store']);

/*Adding posts directly is disabled to preserve the db
Route::post('/posts', [PostController::class, 'store']);*/
