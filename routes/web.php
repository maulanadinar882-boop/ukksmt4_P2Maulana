<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\AppealController;
use App\Http\Controllers\AdminAppealController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==================== GUEST ROUTES (TIDAK PERLU LOGIN) ====================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ==================== PROTECTED ROUTES (SEMUA ROLE) ====================
Route::middleware(['auth:all'])->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ==================== ROUTES UNTUK ADMIN SAJA ====================
    Route::middleware(['auth:Admin'])->group(function () {
        
        // Manajemen User
        Route::resource('/user', UserController::class);
        Route::get('/user/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('user.toggle-status');
        Route::get('/user/export', [UserController::class, 'export'])->name('user.export');
        Route::get('/user/export-pdf', [UserController::class, 'exportPdf'])->name('user.export.pdf');
        
        // Manajemen Tarif
        Route::resource('/tarif', TarifController::class);
        Route::get('/tarif/export', [TarifController::class, 'export'])->name('tarif.export');
        Route::get('/tarif/export-pdf', [TarifController::class, 'exportPdf'])->name('tarif.export.pdf');
        Route::get('/tarif/jenis-kendaraan', [TarifController::class, 'getJenisKendaraan'])->name('tarif.jenis');
        
        // Manajemen Area Parkir
        Route::resource('/area', AreaController::class);
        Route::post('/area/{id}/reset', [AreaController::class, 'resetTerisi'])->name('area.reset');
        Route::get('/area/export', [AreaController::class, 'export'])->name('area.export');
        Route::get('/area/export-pdf', [AreaController::class, 'exportPdf'])->name('area.export.pdf');
        
        // Manajemen Kendaraan
        Route::resource('/kendaraan', KendaraanController::class);
        Route::get('/kendaraan/search', [KendaraanController::class, 'search'])->name('kendaraan.search');
        Route::get('/kendaraan/export', [KendaraanController::class, 'export'])->name('kendaraan.export');
        Route::get('/kendaraan/export-pdf', [KendaraanController::class, 'exportPdf'])->name('kendaraan.export.pdf');
        
        // Log Aktivitas
        Route::get('/log', [LogController::class, 'index'])->name('log.index');
        Route::get('/log/export', [LogController::class, 'exportExcel'])->name('log.export');
        
        // Manajemen Shift
        Route::get('/shift', [ShiftController::class, 'index'])->name('shift.index');
        Route::post('/shift', [ShiftController::class, 'store'])->name('shift.store');
        Route::put('/shift/{id}', [ShiftController::class, 'update'])->name('shift.update');
        Route::delete('/shift/{id}', [ShiftController::class, 'destroy'])->name('shift.destroy');
        Route::post('/shift/assign', [ShiftController::class, 'assignShift'])->name('shift.assign');
        Route::delete('/shift/unassign/{id}', [ShiftController::class, 'unassignShift'])->name('shift.unassign');
        
        // Admin Appeal Management
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('/appeal', [AdminAppealController::class, 'index'])->name('appeal.index');
            Route::get('/appeal/{id}/respond', [AdminAppealController::class, 'respond'])->name('appeal.respond');
            Route::post('/appeal/{id}/process', [AdminAppealController::class, 'process'])->name('appeal.process');
        });
    });

    // ==================== ROUTES UNTUK PETUGAS SAJA ====================
    Route::middleware(['auth:Petugas'])->group(function () {
        
        // Transaksi Parkir
        Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
        Route::post('/transaksi/masuk', [TransaksiController::class, 'masuk'])->name('transaksi.masuk');
        Route::post('/transaksi/keluar', [TransaksiController::class, 'keluar'])->name('transaksi.keluar');
        Route::get('/transaksi/struk/{id}', [TransaksiController::class, 'struk'])->name('transaksi.struk');
        Route::get('/transaksi/cari-kendaraan', [TransaksiController::class, 'cariKendaraan'])->name('transaksi.cari');
        
        // Shift Petugas
        Route::get('/shift-saya', [ShiftController::class, 'myShift'])->name('shift.my-shift');
    });

    // ==================== ROUTES UNTUK OWNER SAJA ====================
    Route::middleware(['auth:Owner'])->group(function () {
        
        // Laporan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');
        Route::get('/laporan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
        Route::get('/laporan/harian', [LaporanController::class, 'laporanHarian'])->name('laporan.harian');
        Route::get('/laporan/bulanan', [LaporanController::class, 'laporanBulanan'])->name('laporan.bulanan');
        Route::get('/laporan/tahunan', [LaporanController::class, 'laporanTahunan'])->name('laporan.tahunan');
        
        // Appeal Owner
        Route::resource('/appeal', AppealController::class);
    });
});