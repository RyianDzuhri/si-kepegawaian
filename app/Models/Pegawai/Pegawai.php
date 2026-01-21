<?php

namespace App\Models\Pegawai;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    // Nama tabel (karena bukan plural otomatis)
    protected $table = 'pegawai';

    // Kolom yang boleh diisi mass assignment
    protected $fillable = [
        'nip',
        'nama',
        'foto_profil',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'jabatan',
        'jenis_pegawai',
        'golongan',
        'pendidikan_terakhir',
        'tmt_pangkat_terakhir',
        'tmt_gaji_berkala_terakhir',
    ];

    /**
     * Relasi:
     * 1 Pegawai memiliki banyak SK
     */
    public function sk()
    {
        return $this->hasMany(SuratKeputusan::class, 'pegawai_id');
    }
}
