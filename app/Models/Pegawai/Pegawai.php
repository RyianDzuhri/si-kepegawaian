<?php

namespace App\Models\Pegawai;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';

    // Update kolom yang boleh diisi
    protected $fillable = [
        // Identitas & Kontak
        'nip', 
        'nik', 
        'nama', 
        'foto_profil',
        'no_hp',
        'email',
        
        // Biodata
        'tempat_lahir', 
        'tanggal_lahir', 
        'jenis_kelamin',
        'agama',
        'status_pernikahan',
        
        // Kepegawaian
        'unit_kerja',
        'jabatan', 
        'jenis_pegawai', 
        'golongan', 
        'pendidikan_terakhir',
        
        // TMT & Notifikasi
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