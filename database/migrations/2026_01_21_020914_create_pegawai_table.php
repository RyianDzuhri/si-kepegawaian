<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('pegawai', function (Blueprint $table) {
        $table->id();
        $table->string('nip', 20)->unique();
        $table->string('nama');
        $table->string('foto_profil')->nullable();
        $table->string('tempat_lahir');
        $table->date('tanggal_lahir'); // Dipakai untuk notifikasi PENSIUN
        $table->enum('jenis_kelamin', ['L', 'P']);
        $table->string('jabatan');
        $table->string('jenis_pegawai');
        $table->string('golongan')->nullable();
        $table->string('pendidikan_terakhir');

        // --- TAMBAHAN UNTUK FITUR NOTIFIKASI ---
        // Agar sistem tahu kapan kenaikan berikutnya, kita simpan tanggal terakhirnya
        $table->date('tmt_pangkat_terakhir')->nullable(); // Acuan notif Kenaikan Pangkat
        $table->date('tmt_gaji_berkala_terakhir')->nullable(); // Acuan notif Gaji Berkala

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('pegawai'); // Sesuaikan juga di sini
}
};
