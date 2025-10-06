<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;

// Master Controllers
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\Master\PegawaiController;
use App\Http\Controllers\Master\JabatanController;
use App\Http\Controllers\Master\BonusPotonganController;

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\GajianController;
use App\Http\Controllers\PayrollController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
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

    /*
    |--------------------------------------------------------------------------
    | Absensi Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::post('/absensi/ganti-pegawai', [AbsensiController::class, 'gantiPegawai'])->name('absensi.gantiPegawai');
    Route::post('/absensi/ganti-jabatan', [AbsensiController::class, 'gantiJabatan'])->name('absensi.gantiJabatan');
    Route::post('/absensi/ganti-shift-jobdesk', [AbsensiController::class, 'gantiShiftJobdesk'])->name('absensi.gantiShiftJobdesk');
    Route::post('/absensi/update-cell', [AbsensiController::class, 'updateCell'])->name('absensi.updateCell');
    Route::post('/absensi/{uuid}/update-status', [AbsensiController::class, 'updateStatus'])->name('absensi.updateStatus');
    Route::get('/absensi/rekap', [AbsensiController::class, 'formRekap'])->name('absensi.rekap');
    Route::post('/absensi/rekap', [AbsensiController::class, 'simpanRekap'])->name('absensi.simpanRekap');
    Route::get('/absensi/periode-by-bulan-tahun', [AbsensiController::class, 'getPeriodeByBulanTahun'])->name('absensi.periodeByBulanTahun');
    Route::get('/absensi/get-periode', [AbsensiController::class, 'getPeriodeByBulanTahun'])->name('absensi.getPeriode');
    Route::get('/absensi/get-data', [AbsensiController::class, 'getAbsensiByPeriode'])->name('absensi.getData');
    Route::get('/absensi/get-data', [AbsensiController::class, 'getAbsensiData'])->name('absensi.getData');

    /*
    |--------------------------------------------------------------------------
    | Gajian Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/gajian/cetak/{uuid}', [GajianController::class, 'cetakSlip'])->name('gajian.cetak');
    Route::resource('gajian', GajianController::class);
    Route::get('/gajian/preview/{periode_uuid}', [GajianController::class,'preview'])->name('gajian.preview');
    Route::post('/gajian/save-draft/{periode_uuid}', [GajianController::class,'saveDraft'])->name('gajian.saveDraft');
    Route::post('/gajian/finalize/{periode_uuid}', [GajianController::class,'finalize'])->name('gajian.finalize');
    Route::get('/gajian/slip/{uuid}', [GajianController::class,'slip'])->name('gajian.slip');
    Route::get('/gajian/generate', [GajianController::class, 'generatePayroll'])->name('gajian.generate');
    Route::get('gajian/get-periodes', [GajianController::class, 'getPeriodes'])->name('gajian.getPeriodes');
    Route::get('gajian/get-data', [GajianController::class, 'getGajianData'])->name('gajian.getGajianData');
    Route::post('/gajian/store', [GajianController::class, 'store'])->name('gajian.store');

    /*
    |--------------------------------------------------------------------------
    | Payroll Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/payroll/slip/{uuid}', [PayrollController::class, 'showSlip'])->name('payroll.slip');
    Route::get('/payroll/slip/{uuid}/export', [PayrollController::class, 'exportSlip'])->name('payroll.slip.export');
    Route::get('/payroll/report', [PayrollController::class, 'report'])->name('payroll.report');

    /*
    |--------------------------------------------------------------------------
    | Master Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('user', UserController::class);
    Route::get('/pegawai/search', [PegawaiController::class, 'search'])->name('pegawai.search');
    Route::resource('pegawai', PegawaiController::class);
    Route::resource('jabatan', JabatanController::class);

    /*
    |--------------------------------------------------------------------------
    | Bonus Potongan Routes
    |--------------------------------------------------------------------------
    */
    Route::get('bonuspotongan/{uuid}/edit-system', [BonusPotonganController::class, 'edit_system'])->name('bonuspotongan.edit_system');
    Route::put('bonuspotongan/{uuid}/update-system', [BonusPotonganController::class, 'update_system'])->name('bonuspotongan.update_system');
    Route::get('bonuspotongan/{uuid}/edit-non-system', [BonusPotonganController::class, 'edit_non_system'])->name('bonuspotongan.edit_non_system');
    Route::put('bonuspotongan/{uuid}/update-non-system', [BonusPotonganController::class, 'update_non_system'])->name('bonuspotongan.update_non_system');
    Route::resource('bonuspotongan', BonusPotonganController::class);

});
