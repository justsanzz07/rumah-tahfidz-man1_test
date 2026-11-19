<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PetugasController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Petugas\SetoranController;
use App\Http\Controllers\WaliSantriController;
use Illuminate\Support\Facades\Route;

// Halaman Utama: Portal Pantau Hafalan untuk Orang Tua / Wali Murid (Tanpa Login)
Route::get('/', [WaliSantriController::class, 'index'])->name('wali.index');
Route::get('/pantau-hafalan', [WaliSantriController::class, 'index']);
Route::get('/pantau-hafalan/{siswa}', [WaliSantriController::class, 'show'])->name('wali.show');
Route::get('/api/cari-siswa', [WaliSantriController::class, 'searchApi'])->name('wali.search-api');

// Pencatatan Setoran oleh Petugas / Pendamping
Route::get('/setoran', [SetoranController::class, 'index'])->name('petugas.index');
Route::post('/setoran', [SetoranController::class, 'store'])->name('petugas.store');
Route::get('/api/siswa-by-kelas', [SetoranController::class, 'getSiswa'])->name('petugas.siswa-by-kelas');

// Autentikasi Admin (Dapat diakses via /login maupun /admin/login)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Area Khusus Administrator (Dilindungi Middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Rekapitulasi & Statistik
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::delete('/laporan/{laporan}', [DashboardController::class, 'destroy'])->name('laporan.destroy');
    Route::post('/laporan/clear-demo', [DashboardController::class, 'clearDemo'])->name('laporan.clear-demo');
    Route::get('/laporan/export-csv', [DashboardController::class, 'exportCsv'])->name('laporan.export');

    // Manajemen Siswa
    Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
    Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
    Route::put('/siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
    Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
    Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
    Route::get('/siswa/template', [SiswaController::class, 'downloadTemplate'])->name('siswa.template');

    // Manajemen Petugas
    Route::get('/petugas', [PetugasController::class, 'index'])->name('petugas.index');
    Route::post('/petugas', [PetugasController::class, 'store'])->name('petugas.store');
    Route::get('/petugas/{petugas}/riwayat', [PetugasController::class, 'riwayat'])->name('petugas.riwayat');
    Route::put('/petugas/{petugas}', [PetugasController::class, 'update'])->name('petugas.update');
    Route::delete('/petugas/{petugas}', [PetugasController::class, 'destroy'])->name('petugas.destroy');

    // Pengaturan Aplikasi (Ganti Logo & Ganti Sandi)
    Route::get('/pengaturan', [SettingController::class, 'index'])->name('setting.index');
    Route::post('/pengaturan/logo', [SettingController::class, 'updateLogo'])->name('setting.logo');
    Route::post('/pengaturan/logo-reset', [SettingController::class, 'resetLogo'])->name('setting.logo.reset');
    Route::post('/pengaturan/password', [SettingController::class, 'updatePassword'])->name('setting.password');
});
