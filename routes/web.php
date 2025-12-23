<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FasilitasUmumController;
use App\Http\Controllers\SyaratFasilitasController;
use App\Http\Controllers\PetugasFasilitasController;
use App\Http\Controllers\PembayaranFasilitasController;
use App\Http\Controllers\PeminjamanFasilitasController;

/*
|--------------------------------------------------------------------------
| DEFAULT
|--------------------------------------------------------------------------
*/
Route::redirect('/', '/login');

/*
|--------------------------------------------------------------------------
| ROUTE TAMU (BELUM LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('guest.only')->group(function () {

    Route::get('/login', [AuthController::class, 'loginForm'])
        ->name('login.form');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.process');

    Route::get('/register', [AuthController::class, 'registerForm'])
        ->name('register.form');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.process');
});

/*
|--------------------------------------------------------------------------
| ROUTE WAJIB LOGIN
|--------------------------------------------------------------------------
*/
Route::middleware('checkLogin')->group(function () {

    /*
    | DASHBOARD
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    | PROFIL USER (SEMUA ROLE)
    */
    Route::get('/profil', [UserController::class, 'profil'])
        ->name('user.profil');

    Route::post('/profil/update', [UserController::class, 'updateProfil'])
        ->name('user.updateProfil');

    /*
    |--------------------------------------------------------------------------
    | USER MANAGEMENT (ADMIN ONLY 🔐)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {

        Route::resource('user', UserController::class);

        Route::get('/user/check-email', [UserController::class, 'checkEmail'])
            ->name('user.checkEmail');
    });

    /*
    |--------------------------------------------------------------------------
    | WARGA
    |--------------------------------------------------------------------------
    */
    Route::resource('warga', WargaController::class);

    Route::get('/warga/check-ktp', [WargaController::class, 'checkKTP'])
        ->name('warga.checkKTP');

    Route::get('/warga/check-email', [WargaController::class, 'checkEmail'])
        ->name('warga.checkEmail');

    /*
    |--------------------------------------------------------------------------
    | FASILITAS & TRANSAKSI
    |--------------------------------------------------------------------------
    */
    Route::resource('fasilitas', FasilitasUmumController::class)
        ->parameters(['fasilitas' => 'fasilitas']);

    Route::resource('peminjaman', PeminjamanFasilitasController::class)
        ->parameters(['peminjaman' => 'peminjaman']);

    Route::resource('pembayaran', PembayaranFasilitasController::class)
        ->parameters(['pembayaran' => 'pembayaran']);

    Route::resource('syarat', SyaratFasilitasController::class)
        ->parameters(['syarat' => 'syarat']);

    Route::resource('petugas', PetugasFasilitasController::class)
        ->parameters(['petugas' => 'petugas']);

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});
