<?php

use App\Http\Controllers\SwitchPage;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [SwitchPage::class, 'showPage'])->name('dashboard');

Route::get('/manajemen-pegawai', [App\Http\Controllers\ManajemenPegawaiController::class, 'index'])->name('manajemen-pegawai');
