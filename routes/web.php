<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PenggunaController;
use App\Http\Controllers\Api\MonevKeuanganController;
use App\Http\Controllers\Api\DataKeuanganController;
use App\Http\Controllers\Api\ProgramController;
use App\Http\Controllers\Api\JadwalProgramController;
use App\Http\Controllers\Api\KeuanganController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});
Route::group(['middleware' => ['auth:sanctum', 'verified']], function() {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    // Pekerjaan route
    Route::get('/pekerjaan', function () {
        return view('pages.pekerjaan');
    })->name('pages.pekerjaan');

    // Pengguna route
    Route::resource('pengguna', PenggunaController::class)->names([
        'index' => 'pages.pengguna',
        'show' => 'pages.pengguna.show',
        'create' => 'pages.pengguna.create',
        'store' => 'pages.pengguna.store',
        'edit' => 'pages.pengguna.edit',
        'update' => 'pages.pengguna.update',
        'destroy' => 'pages.pengguna.destroy',
    ]);

    // Keuangan routes
    Route::resource('keuangan/monev-keuangan',MonevKeuanganController::class)->names([
        'index' => 'pages.keuangan.monev-keuangan',
        'show' => 'pages.keuangan.monev-keuangan.show',
        'create' => 'pages.keuangan.monev-keuangan.create',
        'store' => 'pages.keuangan.monev-keuangan.store',
        'edit' => 'pages.keuangan.monev-keuangan.edit',
        'update' => 'pages.keuangan.monev-keuangan.update',
        'destroy' => 'pages.keuangan.monev-keuangan.destroy',
    ]);

    Route::resource('keuangan/data-keuangan', DataKeuanganController::class)->names([
        'index' => 'pages.keuangan.data-keuangan',
        'show' => 'pages.keuangan.data-keuangan.show',
        'create' => 'pages.keuangan.data-keuangan.create',
        'store' => 'pages.keuangan.data-keuangan.store',
        'edit' => 'pages.keuangan.data-keuangan.edit',
        'update' => 'pages.keuangan.data-keuangan.update',
        'destroy' => 'pages.keuangan.data-keuangan.destroy',
    ]);

    // Program route
    Route::resource('program', ProgramController::class)->names([
        'index' => 'pages.program',
        'show' => 'pages.program.show',
        'create' => 'pages.program.create',
        'store' => 'pages.program.store',
        'edit' => 'pages.program.edit',
        'update' => 'pages.program.update',
        'destroy' => 'pages.program.destroy',
    ]);

    // Jadwal program route
    Route::resource('jadwal-program', JadwalProgramController::class)->names([
        'index' => 'pages.jadwal-program',
        'show' => 'pages.jadwal-program.show',
        'create' => 'pages.jadwal-program.create',
        'store' => 'pages.jadwal-program.store',
        'edit' => 'pages.jadwal-program.edit',
        'update' => 'pages.jadwal-program.update',
        'destroy' => 'pages.jadwal-program.destroy',
    ]);

});