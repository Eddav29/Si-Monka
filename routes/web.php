<?php

use Illuminate\Support\Facades\Route;

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
    Route::get('/pengguna', function () {
        return view('pages.pengguna');
    })->name('pages.pengguna');

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