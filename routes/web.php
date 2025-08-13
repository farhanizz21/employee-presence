<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;

// Master Controllers
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\Master\PegawaiController;
use App\Http\Controllers\Master\JabatanController;
use App\Http\Controllers\Master\GrupController;
use App\Http\Controllers\Master\BonusPotonganController;

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\GajianController;

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
    return auth()->check()
        ? redirect()->route('absensi.index')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    // Logout
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Absensi
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::post('/absensi/ganti-pegawai', [AbsensiController::class, 'gantiPegawai'])->name('absensi.gantiPegawai');
    Route::post('/absensi/ganti-jabatan', [AbsensiController::class, 'gantiJabatan'])->name('absensi.gantiJabatan');



    
    Route::resource('gajian', GajianController::class);

    // Resource routes (user, pegawai, jabatan, grup)
    Route::resource('user', UserController::class);
    Route::get('/pegawai/search', [PegawaiController::class, 'search'])->name('pegawai.search');
    Route::resource('pegawai', PegawaiController::class);
    Route::resource('jabatan', JabatanController::class);
    Route::resource('grup', GrupController::class);

    // Bonus Potongan Custom Routes
    Route::get ('bonuspotongan/{uuid}/edit-system', [BonusPotonganController::class, 'edit_system'])
        ->name('bonuspotongan.edit_system');
    Route::put('bonuspotongan/{uuid}/update-system', [BonusPotonganController::class, 'update_system'])
        ->name('bonuspotongan.update_system');
    Route::get('bonuspotongan/{uuid}/edit-non-system', [BonusPotonganController::class, 'edit_non_system'])
        ->name('bonuspotongan.edit_non_system');
    Route::put('bonuspotongan/{uuid}/update-non-system', [BonusPotonganController::class, 'update_non_system'])
        ->name('bonuspotongan.update_non_system');
    Route::resource('bonuspotongan', BonusPotonganController::class);
});