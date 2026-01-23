<?php

use App\Http\Controllers\Pegawai\DashboardController;
use App\Http\Controllers\Pegawai\ManajemenPegawaiController;
use App\Http\Controllers\Pegawai\SKController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard');


// Rute untuk melihat Manajemen Pegawai

Route::get('/pegawai', [ManajemenPegawaiController::class, 'index'])->name('manajemen-pegawai');
Route::get('/pegawai/tambah', [ManajemenPegawaiController::class, 'create'])->name('tambah-pegawai');
Route::post('/pegawai/store', [ManajemenPegawaiController::class, 'store'])->name('simpan-pegawai');
Route::get('/pegawai/{id}', [ManajemenPegawaiController::class, 'show'])->name('tampil-pegawai');
Route::get('/pegawai/{id}/edit', [ManajemenPegawaiController::class, 'edit'])->name('edit-pegawai');
Route::put('/pegawai/{id}', [ManajemenPegawaiController::class, 'update'])->name('update-pegawai');
Route::delete('/pegawai/{id}', [ManajemenPegawaiController::class, 'destroy'])->name('hapus-pegawai');

// Rute untuk melihat Arsip SK

Route::get('/sk', [SKController::class, 'index'])->name('arsip-sk');
Route::get('/sk/tambah', [SKController::class, 'create'])->name('tambah-sk');
Route::post('/sk/store', [SKController::class, 'store'])->name('simpan-sk');