<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\TagController;
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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login' ]);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);

});

Route::group([
    'middleware' => 'api',
    'prefix' => 'dms'
], function ($router) {
           //--------------------------- Document Routes  ---------------------------//
    Route::get('/documents', [DocumentController::class, 'index']);
    Route::post('/document', [DocumentController::class, 'store']);
    Route::get('/documents/{document}', [DocumentController::class, 'show']);
    Route::post('/documents/{document}', [DocumentController::class, 'update']);
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy']);


             //--------------------------- Tag Routes  ---------------------------//
    Route::get('/tags', [TagController::class, 'index']);
    Route::post('/tag', [TagController::class, 'store']);
    Route::get('/tags/{tag}', [TagController::class, 'show']);
    Route::put('/tags/{tag}', [TagController::class, 'update']);
    Route::delete('/tags/{tag}', [TagController::class, 'destroy']);

                 //--------------------------- Comment Routes  ---------------------------//
                //  Route::post('/comment', [CommentController::class, 'store']);

                 Route::post('/documents/{documentId}/comments', [CommentController::class, 'store']);



                 Route::get('/comments/{comment}', [CommentController::class, 'show']);
                 Route::put('/comments/{comment}', [CommentController::class, 'update']);
                 Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);


});



// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
