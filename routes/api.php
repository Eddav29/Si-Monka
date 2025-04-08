<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\PekerjaanController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\JadwalProgramController;
use App\Http\Controllers\MonevKeuanganController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('programs', ProgramController::class);
Route::get('pekerjaan/{pekerjaanId}/programs', [ProgramController::class, 'getByPekerjaanId']);

// Pekerjaan routes
Route::apiResource('pekerjaan', PekerjaanController::class);

// Keuangan routes
Route::apiResource('keuangan', KeuanganController::class);
Route::get('pekerjaan/{pekerjaanId}/keuangan', [KeuanganController::class, 'getByPekerjaanId']);

// JadwalProgram routes
Route::apiResource('jadwal-program', JadwalProgramController::class);
Route::get('program/{programId}/jadwal', [JadwalProgramController::class, 'getByProgramId']);

// MonevKeuangan routes
Route::apiResource('monev-keuangan', MonevKeuanganController::class);
Route::get('pekerjaan/{pekerjaanId}/monev-keuangan', [MonevKeuanganController::class, 'getByPekerjaanId']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::get('/logout', [AuthController::class, 'logout']);
    // If you want to protect routes with authentication later, move them inside this group
});