<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PenggunaController;
use App\Http\Controllers\API\PekerjaanController;

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
    Route::resource('pekerjaan', PekerjaanController::class)->names([
        'index' => 'pages.pekerjaan',
        'show' => 'pages.pekerjaan.show',
        'create' => 'pages.pekerjaan.create',
        'store' => 'pages.pekerjaan.store',
        'edit' => 'pages.pekerjaan.edit',
        'update' => 'pages.pekerjaan.update',
        'destroy' => 'pages.pekerjaan.destroy',
    ]);

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
    Route::prefix('keuangan')->name('pages.keuangan.')->group(function() {
        Route::get('/monev-keuangan', function () {
            return view('pages.keuangan.monev-keuangan');
        })->name('monev-keuangan');

        Route::get('/data-keuangan', function () {
            return view('pages.keuangan.data-keuangan');
        })->name('data-keuangan');
    });

    // Program route
    Route::get('/program', function () {
        return view('pages.program');
    })->name('program');

    // Jadwal program route
    Route::get('/jadwal-program', function () {
        return view('pages.jadwal-program');
    })->name('pages.jadwal-program');

});