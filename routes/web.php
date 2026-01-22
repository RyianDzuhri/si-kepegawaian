<?php

use App\Http\Controllers\Pegawai\ManajemenPegawaiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
// Rute untuk melihat Daftar Pegawai

Route::get('/pegawai', [ManajemenPegawaiController::class, 'index'])->name('manajemen-pegawai');
Route::get('/pegawai/tambah', [ManajemenPegawaiController::class, 'create'])->name('tambah-pegawai');
Route::post('/pegawai/store', [ManajemenPegawaiController::class, 'store'])->name('simpan-pegawai');
