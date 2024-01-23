<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

use App\Http\Controllers\UserController\{WargaController, PetugasController, AdminController};
use App\Http\Controllers\KasController\{TagihanController, KasMasukController, KasKeluarController};
use App\Http\Controllers\{KategoriController, DashboardController, LoginController, KontenController, RiwayatController};

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

/* if (app()->isLocal()) {
    URL::forceScheme('https');
} */

Route::middleware('guest')->group(function() {
    Route::get('/', [LoginController::class, 'loginPage'])->name('login');
    Route::post('/authenticate', [LoginController::class, 'login'])->name('proses-login');
});

Route::middleware('auth')->group(function() {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::middleware('auth', 'hak_akses:warga,admin,petugas')->group(function() {
    Route::get('/warga/kegiatan/{konten}', [KontenController::class, 'viewKegiatan'])->name('view-kegiatan'); 
});

Route::middleware('auth', 'hak_akses:warga,admin')->group(function() {
    Route::get('/warga/reset-password', [WargaController::class, 'resetPasswordPage'])->name('reset-password');
    Route::post('/warga/reset-password', [WargaController::class, 'resetPassword'])->name('reseting-password');
});

Route::middleware('auth', 'hak_akses:warga,admin', 'is_first_login')->prefix('/warga')->group(function() {
    Route::get('/', [DashboardController::class, 'home'])->name('dashboard-warga');
    Route::get('/profile', [WargaController::class, 'profileEditPage'])->name('profile-warga');
    Route::post('/profile', [WargaController::class, 'profileEdit'])->name('profile-edit');
    Route::get('/saldo-kas', [RiwayatController::class, 'riwayatSaldoKas'])->name('riwayat-saldo');
});

Route::middleware('auth', 'hak_akses:petugas,admin')->prefix('/admin')->group(function() {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard-admin');

    Route::get('/data-tagihan', [TagihanController::class, 'index'])->name('index-tagihan');
    Route::post('/data-tagihan', [TagihanController::class, 'store'])->name('store-tagihan');

    Route::get('/data-konten', [KontenController::class, 'index'])->name('index-konten');
    Route::post('/data-konten', [KontenController::class, 'store'])->name('store-konten');
    Route::get('/data-konten/{konten}', [KontenController::class, 'edit'])->name('edit-konten');
    Route::put('/data-konten/{konten}', [KontenController::class, 'update'])->name('update-konten');
    Route::delete('/data-konten/{konten}', [KontenController::class, 'destroy'])->name('update-konten');
});

Route::middleware('auth', 'hak_akses:admin')->prefix('/admin')->group(function() {
    Route::get('/data-warga', [WargaController::class, 'index'])->name('index-warga');
    Route::post('/data-warga', [WargaController::class, 'store'])->name('store-warga');
    Route::get('/data-warga/{user}', [WargaController::class, 'edit'])->name('edit-warga');
    Route::put('/data-warga/{user}', [WargaController::class, 'update'])->name('update-warga');
    Route::delete('/data-warga/{user}', [WargaController::class, 'destroy'])->name('update-warga');
    
    Route::get('/data-petugas', [PetugasController::class, 'index'])->name('index-petugas');
    Route::post('/data-petugas', [PetugasController::class, 'store'])->name('store-petugas');
    Route::get('/data-petugas/{user}', [PetugasController::class, 'edit'])->name('edit-petugas');
    Route::put('/data-petugas/{user}', [PetugasController::class, 'update'])->name('update-petugas');
    Route::delete('/data-petugas/{user}', [PetugasController::class, 'destroy'])->name('update-petugas');
    
    Route::get('/data-admin', [AdminController::class, 'index'])->name('index-admin');
    Route::post('/data-admin', [AdminController::class, 'store'])->name('store-admin');
    Route::get('/data-admin/{user}', [AdminController::class, 'edit'])->name('edit-admin');
    Route::put('/data-admin/{user}', [AdminController::class, 'update'])->name('update-admin');
    Route::delete('/data-admin/{user}', [AdminController::class, 'destroy'])->name('update-admin');

    Route::get('/data-kategori', [KategoriController::class, 'index'])->name('index-kategori');
    Route::post('/data-kategori', [KategoriController::class, 'store'])->name('store-kategori');
    Route::get('/data-kategori/{kategori}', [KategoriController::class, 'edit'])->name('edit-kategori');
    Route::put('/data-kategori/{kategori}', [KategoriController::class, 'update'])->name('update-kategori');
    Route::delete('/data-kategori/{kategori}', [KategoriController::class, 'destroy'])->name('update-kategori');
    
    Route::get('/data-tagihan/{tagihan}', [TagihanController::class, 'edit'])->name('edit-tagihan');
    Route::put('/data-tagihan/{tagihan}', [TagihanController::class, 'update'])->name('update-tagihan');
    Route::post('/data-tagihan/download', [TagihanController::class, 'downloadLaporan'])->name('downloadLaporan-tagihan');
    
    Route::get('/kas-masuk', [KasMasukController::class, 'index'])->name('index-kas-masuk');
    Route::post('/kas-masuk', [KasMasukController::class, 'store'])->name('store-kas-masuk');
    Route::get('/kas-masuk/{kasMasuk}', [KasMasukController::class, 'edit'])->name('edit-kas-masuk');
    Route::put('/kas-masuk/{kasMasuk}', [KasMasukController::class, 'update'])->name('update-kas-masuk');
    Route::delete('/kas-masuk/{kasMasuk}', [KasMasukController::class, 'destroy'])->name('update-kas-masuk');
    Route::post('/kas-masuk/reset', [KasMasukController::class, 'truncateTable'])->name('truncate-kas-masuk');
    Route::post('/kas-masuk/download', [KasMasukController::class, 'downloadLaporan'])->name('downloadLaporan-kas-masuk');

    Route::get('/kas-keluar', [KasKeluarController::class, 'index'])->name('index-kas-keluar');
    Route::post('/kas-keluar', [KasKeluarController::class, 'store'])->name('store-kas-keluar');
    Route::get('/kas-keluar/{kasKeluar}', [KasKeluarController::class, 'edit'])->name('edit-kas-keluar');
    Route::put('/kas-keluar/{kasKeluar}', [KasKeluarController::class, 'update'])->name('update-kas-keluar');
    Route::delete('/kas-keluar/{kasKeluar}', [KasKeluarController::class, 'destroy'])->name('update-kas-keluar');
    Route::post('/kas-keluar/reset', [KasKeluarController::class, 'truncateTable'])->name('truncate-kas-masuk');
    Route::post('/kas-keluar/download', [KasKeluarController::class, 'downloadLaporan'])->name('downloadLaporan-kas-keluar');
}); 