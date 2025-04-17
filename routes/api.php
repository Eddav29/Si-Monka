<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PenggunaController;
use App\Http\Controllers\Api\ProgramController;
use App\Http\Controllers\Api\MonevKeuanganController;
use App\Http\Controllers\Api\KeuanganController;
use App\Http\Controllers\Api\JadwalProgramController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('pengguna', [PenggunaController::class, 'list']);
    Route::get('monev-keuangan', [MonevKeuanganController::class, 'list']);
    Route::get('keuangan', [KeuanganController::class, 'list']);
    Route::get('jadwal-program', [JadwalProgramController::class, 'list']);
    Route::get('program', [ProgramController::class, 'list']);
});

