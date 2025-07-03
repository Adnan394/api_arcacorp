<?php

use App\Models\Category;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PengajuanController;

Route::get('category', [CategoryController::class, 'index']);
Route::post('category', [CategoryController::class, 'store']);
Route::put('category/{id}', [CategoryController::class, 'update']);
Route::get('category/destroy/{id}', [CategoryController::class, 'destroy']);
Route::get('category/restore/{id}', [CategoryController::class, 'restore']);

Route::get('role', [RoleController::class, 'index']);
Route::post('role', [RoleController::class, 'store']);
Route::put('role/{id}', [RoleController::class, 'update']);
Route::get('role/destroy/{id}', [RoleController::class, 'destroy']);
Route::get('role/restore/{id}', [RoleController::class, 'restore']);

Route::get('user', [UserController::class, 'index']);
Route::post('user', [UserController::class, 'store']);
Route::put('user/{id}', [UserController::class, 'update']);
Route::get('user/destroy/{id}', [UserController::class, 'destroy']);
Route::get('user/restore/{id}', [UserController::class, 'restore']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('pengajuan', [PengajuanController::class, 'index']);
    Route::get('pengajuan/get_all', [PengajuanController::class, 'getAll']);
    Route::post('pengajuan', [PengajuanController::class, 'store']);
    Route::post('pengajuan/approve/{id}', [PengajuanController::class, 'approve']);
    Route::post('pengajuan/reject/{id}', [PengajuanController::class, 'reject']);
    Route::get('pengajuan/destroy/{id}', [PengajuanController::class, 'destroy']);
});

Route::post('login', [AuthController::class, 'login']);