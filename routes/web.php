<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;

// Master Controllers
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\Master\PegawaiController;
use App\Http\Controllers\Master\JabatanController;
use App\Http\Controllers\Master\GrupController;
use App\Http\Controllers\Master\BonusPotonganController;
use App\Http\Controllers\Master\HutangController;

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AbsensiUpdateController;
use App\Http\Controllers\GajianController;
use App\Http\Controllers\PayrollController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('user.index')
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
    Route::get('/absensi/rekap2', [AbsensiController::class, 'formRekap'])->name('absensi.rekap2');
    Route::post('/absensi/rekap2', [AbsensiController::class, 'simpanRekap'])->name('absensi.simpanRekap2');
    Route::get('/absensi/periode-by-bulan-tahun', [AbsensiController::class, 'getPeriodeByBulanTahun'])->name('absensi.periodeByBulanTahun');
    Route::get('/absensi/get-periode', [AbsensiController::class, 'getPeriodeByBulanTahun'])->name('absensi.getPeriode');
    Route::get('/absensi/get-data', [AbsensiController::class, 'getAbsensiData'])->name('absensi.getData');

    /*
    |--------------------------------------------------------------------------
    | AbsensiUpdate Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/absensiUpdate', [AbsensiUpdateController::class, 'index'])->name('absensiUpdate.index');
    Route::get('/absensiUpdate/create', [AbsensiUpdateController::class, 'create'])->name('absensiUpdate.create');
    Route::post('/absensiUpdate', [AbsensiUpdateController::class, 'store'])->name('absensiUpdate.store');

    /*
    |--------------------------------------------------------------------------
    | Gajian Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('gajian')->name('gajian.')->group(function () {
        Route::get('/', [GajianController::class, 'index'])->name('index');

        Route::get('/{periode}/pdf', [GajianController::class, 'pdf'])
                    ->name('pdf');

        Route::get('/create', [GajianController::class, 'create'])->name('create');
        Route::post('/store', [GajianController::class, 'store'])->name('store');
        Route::get('/{uuid}/proses', [GajianController::class, 'proses'])->name('proses');
        Route::get('/{uuid}', [GajianController::class, 'show'])
            ->name('show');
        Route::post('/{uuid}/final', [GajianController::class, 'final'])
            ->name('final');
        Route::get('/{periode}/{pegawai}', [GajianController::class, 'detail'])
            ->name('detail');
        // Route::get('/{periode}/{pegawai}/pdf', [GajianController::class, 'pdf'])
        //     ->name('pdf');

        
        Route::post('/update-bonus-potongan', [GajianController::class, 'updateBonusPotongan'])
            ->name('updateBonusPotongan');
        Route::post('/{uuid}/recalculate', [GajianController::class, 'recalculate'])
            ->name('recalculate');
    });

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
    Route::resource('pegawai', PegawaiController::class);
    Route::resource('jabatan', JabatanController::class);
    Route::resource('grup', GrupController::class);
    Route::resource('hutang', HutangController::class);
    
    
    /*
    |--------------------------------------------------------------------------
    | Pegawai Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/pegawai/search', [PegawaiController::class, 'search'])->name('pegawai.search');
    Route::post('pegawai/{uuid}/update-status', [PegawaiController::class, 'updateStatus'])->name('pegawai.updateStatus');
    /*
    |--------------------------------------------------------------------------
    | Jabatan Routes
    |--------------------------------------------------------------------------
    */
    Route::get('jabatan/{uuid}/edit-system', [JabatanController::class, 'edit_system'])->name('jabatan.edit_system');
    Route::put('jabatan/{uuid}/update-system', [JabatanController::class, 'update_system'])->name('jabatan.update_system');

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

    /*
    |--------------------------------------------------------------------------
    | Hutang Routes
    |--------------------------------------------------------------------------
    */
    Route::post('hutang/{uuid}/update-status', [HutangController::class, 'updateStatus'])->name('hutang.updateStatus');

});