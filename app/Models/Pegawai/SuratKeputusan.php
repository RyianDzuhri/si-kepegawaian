<?php

namespace App\Models\Pegawai;

use App\Models\Pegawai\Pegawai;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKeputusan extends Model
{
    use HasFactory;

    protected $table = 'sk';

    protected $fillable = [
        'pegawai_id',
        'jenis_sk',
        'nomor_sk',
        'tanggal_sk',
        'tmt_sk',
        'file_sk',
    ];

    /**
     * Relasi:
     * Setiap SK dimiliki oleh satu Pegawai
     */
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
