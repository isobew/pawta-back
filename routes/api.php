<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TaskExportController;

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tasks/export', [TaskExportController::class, 'export']);

    Route::get('/tasks', [TaskController::class, 'index']);
    Route::get('/reminders', [TaskController::class, 'reminders']);
    Route::post('/create-task', [TaskController::class, 'store']);
    Route::put('/update-task/{id}', [TaskController::class, 'update']);
    Route::delete('/delete-task/{id}', [TaskController::class, 'delete']);
    Route::get('/tasks/{id}', [TaskController::class, 'show']);

    Route::get('/boards', [BoardController::class, 'index']);
    Route::post('/create-board', [BoardController::class, 'store']);
    Route::put('/update-board/{id}', [BoardController::class, 'update']);
    Route::delete('/delete-board/{id}', [BoardController::class, 'delete']);
    Route::get('/board/{id}', [BoardController::class, 'show']);

    Route::get('/users', [AdminController::class, 'users']);
    Route::get('/users-list', [AdminController::class, 'index']);
    Route::put('/update-user/{id}', [AdminController::class, 'updateUser']);
    Route::delete('/delete-user/{id}', [AdminController::class, 'deleteUser']);
});
