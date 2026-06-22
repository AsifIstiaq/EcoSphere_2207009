<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin']);
Route::get('/register', [AuthController::class, 'showRegister']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/logout', [AuthController::class, 'logout']);

Route::middleware(['auth.custom'])->group(function () {

    Route::get('/citizen/dashboard', function () {
        return view('citizen.dashboard');
    });

    Route::get('/worker/dashboard', function () {
        return view('worker.dashboard');
    });

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    });
});