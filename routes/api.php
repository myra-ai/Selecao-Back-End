<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;

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

// Rotas públicas
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Rotas públicas para listagem de comentários
Route::get('/comments', [CommentController::class, 'index']); // Lista todos os comentários



// Rotas protegidas pelo middleware auth:sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/updateProfile', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/comments/{id}', [CommentController::class, 'update']);
    Route::get('/comments/{id}/history', [CommentController::class, 'history']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });

    // Rotas protegidas para criar comentários
    Route::post('/comments', [CommentController::class, 'store']); // Cria um novo comentário
});
