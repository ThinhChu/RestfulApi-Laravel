<?php

// use Illuminate\Http\Request;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TodoListController;

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
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('todo-list', TodoListController::class);
    Route::apiResource('todo-list.task', TaskController::class)->except('show')->shallow();

    Route::apiResource('label', LabelController::class);

    Route::get('/service/connect/{service}', [ServiceController::class, 'connect'])->name('service.connect');
    Route::post('/service/callback', [ServiceController::class, 'callback'])->name('service.callback');
    Route::post('/service/{service}', [ServiceController::class, 'store'])->name('service.store');
});
// Route::get('todo-list/{list}', [TodoListController::class, 'show'])->name('todo-list.show');
// Route::delete('todo-list/{list}', [TodoListController::class, 'destroy'])->name('todo-list.destroy');
// Route::patch('todo-list/{list}', [TodoListController::class, 'update'])->name('todo-list.update');
Route::post('/register', RegisterController::class)->name('user.register');
Route::post('/login', LoginController::class)->name('user.login');

