<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            
            // IDENTITAS UTAMA
            $table->string('nip', 20)->unique()->nullable(); // Nullable karena Honorer mungkin belum punya NIP
            $table->string('nik', 16)->unique()->nullable(); // TAMBAHAN: NIK KTP
            $table->string('nama');
            $table->string('foto_profil')->nullable();
            
            // BIODATA
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('agama')->nullable(); // TAMBAHAN
            $table->string('status_pernikahan')->nullable(); // TAMBAHAN (Menikah/Belum/Cerai)
            
            // KONTAK
            $table->string('no_hp', 15)->nullable(); // TAMBAHAN
            $table->string('email')->nullable(); // TAMBAHAN

            // DATA JABATAN
            $table->string('unit_kerja'); // TAMBAHAN (Penting: Dinas/Bagian apa)
            $table->string('jabatan'); 
            $table->string('jenis_pegawai'); // PNS, PPPK, Honorer
            $table->string('golongan')->nullable(); // I/a s/d IV/e
            $table->string('pendidikan_terakhir');

            // NOTIFIKASI / RIWAYAT
            $table->date('tmt_pangkat_terakhir')->nullable();
            $table->date('tmt_gaji_berkala_terakhir')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};