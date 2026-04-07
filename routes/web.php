<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/toastr', [NotificationController::class, 'index']);
Route::get('/success', [NotificationController::class, 'success']);
Route::get('/error', [NotificationController::class, 'error']);
Route::get('/info', [NotificationController::class, 'info']);
Route::get('/warning', [NotificationController::class, 'warning']);

// Users routes
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::post('/users/toggle-status/{id}', [UserController::class, 'toggleStatus'])->name('users.toggleStatus'); // POST
Route::post('/users/delete/{id}', [UserController::class, 'delete'])->name('users.delete');  // POST
Route::get('/users/export', [UserController::class, 'exportCsv'])->name('users.export');