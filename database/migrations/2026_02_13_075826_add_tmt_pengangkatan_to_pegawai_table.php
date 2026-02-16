<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pegawai', function (Blueprint $table) {
            // Menambahkan kolom TMT Pengangkatan (Boleh kosong/nullable dulu biar aman utk data lama)
            $table->date('tmt_pengangkatan')->nullable()->after('pendidikan_terakhir');
        });
    }

    public function down()
    {
        Schema::table('pegawai', function (Blueprint $table) {
            $table->dropColumn('tmt_pengangkatan');
        });
    }
};
