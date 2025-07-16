<?php

use Illuminate\Support\Facades\Route;

// Master Controllers
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\Master\PegawaiController;
use App\Http\Controllers\Master\JabatanController;
use App\Http\Controllers\Master\GolonganController;

use App\Http\Controllers\AbsensiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home.home');
});

Route::get('/absensi', [AbsensiController::class, 'create'])->name('absensi.create');
Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');

Route::resource('user', UserController::class);
Route::get('/pegawai/search', [PegawaiController::class, 'search'])->name('pegawai.search');
Route::resource('pegawai', PegawaiController::class);
Route::resource('jabatan', JabatanController::class);
Route::resource('golongan', GolonganController::class);