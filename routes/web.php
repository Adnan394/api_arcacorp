<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard', [
            'active' => 'dashboard',
        ]);
    });
    Route::get("/", function () {
        return redirect()->route('login');
    });

    Route::resource('user', UserController::class)->names('user');
    Route::get('user/restore/{id}', [UserController::class, 'restore'])->name('user_restore');
    Route::resource('category', CategoryController::class)->names('category');
    Route::get('category/restore/{id}', [CategoryController::class, 'restore'])->name('category_restore');
    Route::resource('role', RoleController::class)->names('role');
    Route::get('role/restore/{id}', [RoleController::class, 'restore'])->name('role_restore');
});

Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'login_store'])->name('login_store');