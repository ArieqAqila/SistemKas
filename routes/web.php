<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController\WargaController;
use App\Http\Controllers\UserController\PetugasController;
use App\Http\Controllers\UserController\AdminController;

use App\Http\Controllers\KasController\TagihanController;
use App\Http\Controllers\KasController\KasMasukController;
use App\Http\Controllers\KasController\KasKeluarController;
use App\Http\Controllers\KategoriController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\KontenController;

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


Route::middleware('guest')->group(function() {
    Route::get('/', function () {
        return view('login');
    })->name('login');

    Route::post('/authenticate', [LoginController::class, 'login'])->name('proses-login');
});

Route::middleware('auth')->group(function() {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::middleware('auth', 'hak_akses:warga')->group(function() {
    Route::get('/warga', function () {
        return view('warga/index');
    })->name('dashboard-warga');
});

Route::middleware('auth', 'hak_akses:petugas,admin')->group(function() {
    Route::get('/admin', [DashboardController::class, 'index'])->name('dashboard-admin');

    Route::get('/admin/data-tagihan', [TagihanController::class, 'index'])->name('index-tagihan');
    Route::post('/admin/data-tagihan', [TagihanController::class, 'store'])->name('store-tagihan');
    
    Route::get('/admin/data-konten', [KontenController::class, 'index'])->name('index-konten');
    Route::post('/admin/data-konten', [KontenController::class, 'store'])->name('store-konten');
    Route::get('/admin/data-konten/{konten}', [KontenController::class, 'edit'])->name('edit-konten');
    Route::put('/admin/data-konten/{konten}', [KontenController::class, 'update'])->name('update-konten');
    Route::delete('/admin/data-konten/{konten}', [KontenController::class, 'destroy'])->name('update-konten');
});

Route::middleware('auth', 'hak_akses:admin')->group(function() {
    Route::get('/admin/data-warga', [WargaController::class, 'index'])->name('index-warga');
    Route::post('/admin/data-warga', [WargaController::class, 'store'])->name('store-warga');
    Route::get('/admin/data-warga/{user}', [WargaController::class, 'edit'])->name('edit-warga');
    Route::put('/admin/data-warga/{user}', [WargaController::class, 'update'])->name('update-warga');
    Route::delete('/admin/data-warga/{user}', [WargaController::class, 'destroy'])->name('update-warga');
    
    Route::get('/admin/data-petugas', [PetugasController::class, 'index'])->name('index-petugas');
    Route::post('/admin/data-petugas', [PetugasController::class, 'store'])->name('store-petugas');
    Route::get('/admin/data-petugas/{user}', [PetugasController::class, 'edit'])->name('edit-petugas');
    Route::put('/admin/data-petugas/{user}', [PetugasController::class, 'update'])->name('update-petugas');
    Route::delete('/admin/data-petugas/{user}', [PetugasController::class, 'destroy'])->name('update-petugas');
    
    Route::get('/admin/data-admin', [AdminController::class, 'index'])->name('index-admin');
    Route::post('/admin/data-admin', [AdminController::class, 'store'])->name('store-admin');
    Route::get('/admin/data-admin/{user}', [AdminController::class, 'edit'])->name('edit-admin');
    Route::put('/admin/data-admin/{user}', [AdminController::class, 'update'])->name('update-admin');
    Route::delete('/admin/data-admin/{user}', [AdminController::class, 'destroy'])->name('update-admin');

    Route::get('/admin/data-kategori', [KategoriController::class, 'index'])->name('index-kategori');
    Route::post('/admin/data-kategori', [KategoriController::class, 'store'])->name('store-kategori');
    Route::get('/admin/data-kategori/{kategori}', [KategoriController::class, 'edit'])->name('edit-kategori');
    Route::put('/admin/data-kategori/{kategori}', [KategoriController::class, 'update'])->name('update-kategori');
    Route::delete('/admin/data-kategori/{kategori}', [KategoriController::class, 'destroy'])->name('update-kategori');
    
    Route::get('/admin/data-tagihan/{tagihan}', [TagihanController::class, 'edit'])->name('edit-tagihan');
    Route::put('/admin/data-tagihan/{tagihan}', [TagihanController::class, 'update'])->name('update-tagihan');
    Route::post('/admin/data-tagihan/download', [TagihanController::class, 'downloadLaporan'])->name('downloadLaporan-tagihan');
    
    Route::get('/admin/kas-masuk', [KasMasukController::class, 'index'])->name('index-kas-masuk');
    Route::post('/admin/kas-masuk', [KasMasukController::class, 'store'])->name('store-kas-masuk');
    Route::get('/admin/kas-masuk/{kasMasuk}', [KasMasukController::class, 'edit'])->name('edit-kas-masuk');
    Route::put('/admin/kas-masuk/{kasMasuk}', [KasMasukController::class, 'update'])->name('update-kas-masuk');
    Route::delete('/admin/kas-masuk/{kasMasuk}', [KasMasukController::class, 'destroy'])->name('update-kas-masuk');
    Route::post('/admin/kas-masuk/download', [KasMasukController::class, 'downloadLaporan'])->name('downloadLaporan-kas-masuk');

    Route::get('/admin/kas-keluar', [KasKeluarController::class, 'index'])->name('index-kas-keluar');
    Route::post('/admin/kas-keluar', [KasKeluarController::class, 'store'])->name('store-kas-keluar');
    Route::get('/admin/kas-keluar/{kasKeluar}', [KasKeluarController::class, 'edit'])->name('edit-kas-keluar');
    Route::put('/admin/kas-keluar/{kasKeluar}', [KasKeluarController::class, 'update'])->name('update-kas-keluar');
    Route::delete('/admin/kas-keluar/{kasKeluar}', [KasKeluarController::class, 'destroy'])->name('update-kas-keluar');
    Route::post('/admin/kas-keluar/download', [KasKeluarController::class, 'downloadLaporan'])->name('downloadLaporan-kas-keluar');
}); 