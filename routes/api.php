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

Route::get('/topics', [TopicController::class, 'index']);
Route::post('/topics', [TopicController::class, 'store']);

Route::get('topics/{topic}', [TopicController::class, 'show']);
Route::get('topics/{topic}/threads', [TopicController::class, 'show_threads']);

Route::put('topics/{topic}', [TopicController::class, 'update']);
Route::delete('topics/{topic}', [TopicController::class, 'destroy']);

Route::get('/posts', [PostController::class, 'index']);

# Adding posts directly is disabled to preserve the db
# Route::post('/posts', [PostController::class, 'store']);

Route::post('/topics/{topic}', [PostController::class, 'store_thread']);
Route::post('/posts/{post}', [PostController::class, 'store_message']);

Route::get('posts/{post}', [PostController::class, 'show']);
Route::get('posts/{post}/messages', [PostController::class, 'show_messages']);
Route::get('posts/{post}/replies', [PostController::class, 'show_replies']);
Route::get('posts/{post}/reply_to', [PostController::class, 'show_reply_to']);

Route::put('posts/{post}', [PostController::class, 'update']);
Route::delete('posts/{post}', [PostController::class, 'destroy']);


