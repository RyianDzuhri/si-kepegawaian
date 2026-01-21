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
    // Perhatikan: 'sk' (tanpa s)
    Schema::create('sk', function (Blueprint $table) {
        $table->id();
        
        // PENTING:
        // Karena nama tabel pegawainya 'pegawai' (bukan pegawais),
        // kita harus tulis spesifik di dalam constrained('pegawai')
        $table->foreignId('pegawai_id')->constrained('pegawai')->onDelete('cascade');
        
        $table->string('jenis_sk');
        $table->string('nomor_sk');
        $table->date('tanggal_sk');
        $table->date('tmt_sk');
        $table->string('file_sk')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('sk'); // Sesuaikan juga di sini
}
};
