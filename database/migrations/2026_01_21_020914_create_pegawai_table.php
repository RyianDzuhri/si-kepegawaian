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
    // Perhatikan: 'pegawai' (tanpa s)
    Schema::create('pegawai', function (Blueprint $table) {
        $table->id();
        $table->string('nip', 20)->unique();
        $table->string('nama');
        $table->string('foto_profil')->nullable();
        $table->string('tempat_lahir');
        $table->date('tanggal_lahir');
        $table->enum('jenis_kelamin', ['L', 'P']);
        $table->string('jabatan');
        $table->string('jenis_pegawai');
        $table->string('golongan')->nullable();
        $table->string('pendidikan_terakhir');
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('pegawai'); // Sesuaikan juga di sini
}
};
