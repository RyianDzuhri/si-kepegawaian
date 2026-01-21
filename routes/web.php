<?php

use App\Http\Controllers\SwitchPage;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

// Rute untuk melihat Daftar Pegawai
Route::get('/pegawai', function () {
    return view('pegawai.index');
});

// Rute untuk melihat Detail Pegawai (Yang layout Atas-Bawah tadi)
// Kita pakai dummy URL dulu '/pegawai/detail'
Route::get('/pegawai/detail', function () {
    // Kita butuh data dummy object biar tidak error saat dipanggil viewnya
    // Ini cuma trik sementara ya, nanti diganti Controller
    $pegawai = new stdClass();
    $pegawai->nama = "Budi Santoso, S.Kom";
    $pegawai->nip = "19850101 201001 1 001";
    $pegawai->tempat_lahir = "Jakarta";
    $pegawai->tanggal_lahir = "1985-01-01";
    $pegawai->jenis_kelamin = "L";
    $pegawai->jabatan = "Pranata Komputer";
    $pegawai->jenis_pegawai = "PNS";
    $pegawai->golongan = "III/a";
    $pegawai->pendidikan_terakhir = "S1 Teknik Informatika";
    $pegawai->tmt_pangkat_terakhir = "2022-01-01";
    $pegawai->sk = []; // Kosongkan dulu array SK-nya

    return view('pegawai.show', compact('pegawai'));
});

// Rute untuk menampilkan form tambah pegawai
Route::get('/pegawai/create', function () {
    return view('pegawai.create');
});

Route::get('/pegawai/edit', function () {
    // Pura-pura mengambil data dari database untuk diedit
    $pegawai = new stdClass();
    $pegawai->id = 1;
    $pegawai->nip = "19850101 201001 1 001";
    $pegawai->nama = "Budi Santoso, S.Kom";
    $pegawai->tempat_lahir = "Jakarta";
    $pegawai->tanggal_lahir = "1985-01-01";
    $pegawai->jenis_kelamin = "L"; // Laki-laki, otomatis terpilih di select option
    $pegawai->jabatan = "Pranata Komputer";
    $pegawai->jenis_pegawai = "PNS";
    $pegawai->golongan = "III/a";
    $pegawai->pendidikan_terakhir = "S1 Teknik Informatika";
    $pegawai->tmt_pangkat_terakhir = "2022-01-01";
    $pegawai->tmt_gaji_berkala_terakhir = "2024-03-01";
    
    return view('pegawai.edit', compact('pegawai'));
});

Route::get('/pegawai/detail', function () {
    $pegawai = new stdClass();
    $pegawai->id = 1; // Tambahkan ID juga biar aman
    $pegawai->nama = "Budi Santoso, S.Kom";
    $pegawai->nip = "19850101 201001 1 001";
    $pegawai->tempat_lahir = "Jakarta";
    $pegawai->tanggal_lahir = "1985-01-01";
    $pegawai->jenis_kelamin = "L";
    $pegawai->jabatan = "Pranata Komputer";
    $pegawai->jenis_pegawai = "PNS";
    $pegawai->golongan = "III/a";
    $pegawai->pendidikan_terakhir = "S1 Teknik Informatika";
    $pegawai->foto_profil = null; // Tambahkan ini biar gambar tidak error
    
    // DATA TANGGAL PENTING
    $pegawai->tmt_pangkat_terakhir = "2022-01-01";
    
    // --- TAMBAHKAN BARIS INI YANG TADI KETINGGALAN ---
    $pegawai->tmt_gaji_berkala_terakhir = "2024-03-01"; 
    // -------------------------------------------------

    $pegawai->sk = []; 

    return view('pegawai.show', compact('pegawai'));
});

// Rute Arsip SK
Route::get('/sk', function () {
    return view('sk.index');
});

Route::get('/sk/create', function () {
    return view('sk.create');
});

Route::get('/dashboard', [SwitchPage::class, 'showPage'])->name('dashboard');

Route::get('/manajemen-pegawai', [App\Http\Controllers\Pegawai\ManajemenPegawaiController::class, 'index'])->name('manajemen-pegawai');
