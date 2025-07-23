<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\TempatWisataController;
use App\Http\Controllers\AppsController;
use App\Http\Controllers\ReviewController;

// Autentikasi (login, register, dll)
require __DIR__ . '/auth.php';

// Halaman utama
Route::get('/', [AppsController::class, 'index'])->name('apps.home');
Route::get('/apps/home', [AppsController::class, 'index'])->name('apps.home');
Route::post('/review/{id}', [ReviewController::class, 'store'])->middleware('auth')->name('review.wisata');

// Detail tempat wisata
Route::get('/wisata/{id}', [AppsController::class, 'show'])->name('wisata.detail');

// ROUTE ADMIN

Route::middleware(['auth', 'role:admin'])->group(function () {

    // Dashboard Admin
    Route::get('/home', [AdminController::class, 'index'])->name('admin.home');

    // Manajemen Kategori
    Route::resource('kategori', KategoriController::class)->names('admin.kategori');

    // Manajemen Fasilitas
    Route::resource('fasilitas', FasilitasController::class)
        ->parameters(['fasilitas' => 'fasilitas'])
        ->names('admin.fasilitas');

    // Manajemen Tempat Wisata
    Route::resource('tempat-wisata', TempatWisataController::class)
        ->parameters(['tempat-wisata' => 'tempat_wisata'])
        ->names('admin.tempat-wisata');

    // Edit dan update profil admin
    Route::get('/adminedit', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/update', [AdminController::class, 'update'])->name('admin.updateprofile');
});

// ROUTE WISATAWAN

Route::middleware(['auth', 'role:wisatawan'])->group(function () {

    // Edit dan update profil wisatawan
    Route::get('/profile-user', [AppsController::class, 'edit'])->name('profile.edit');
    Route::put('/profile-user/update', [AppsController::class, 'update'])->name('apps.updateprofile');
});
