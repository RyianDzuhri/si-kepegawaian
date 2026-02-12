<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pegawai\Pegawai;
use App\Models\Pegawai\SuratKeputusan; // Pastikan Model SK sesuai nama file Anda
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // =================================================================
        // 1. STATISTIK UTAMA (KARTU ATAS)
        // =================================================================
        
        // A. Total Pegawai (Semua dihitung)
        $totalPegawai = Pegawai::count();
        
        // B. Total Arsip SK
        $totalSK = SuratKeputusan::count();

        // C. Hitung Pensiun Tahun Ini (LOGIKA DINAMIS)
        // Kita ambil data PNS & PPPK, lalu filter satu per satu
        // Filter exclude 'Honorer' dan 'PPPK Paruh Waktu' implisit via whereIn
        $pensiunTahunIni = Pegawai::whereIn('jenis_pegawai', ['PNS', 'PPPK'])
            ->get()
            ->filter(function ($p) {
                // Tentukan Batas Usia Pensiun (BUP)
                $batas = 58; 
                // PNS Gol IV -> 60 Thn
                if ($p->jenis_pegawai === 'PNS' && strpos($p->golongan, 'IV') === 0) $batas = 60;
                // PPPK Gol Tinggi -> 60 Thn
                $pppkHigh = ['XIII', 'XIV', 'XV', 'XVI', 'XVII'];
                if ($p->jenis_pegawai === 'PPPK' && in_array($p->golongan, $pppkHigh)) $batas = 60;

                // Cek apakah Tahun Pensiun == Tahun Ini?
                $tglPensiun = Carbon::parse($p->tanggal_lahir)->addYears($batas);
                return $tglPensiun->year === Carbon::now()->year;
            })
            ->count();

        // D. Hitung Waktunya Naik Pangkat (KHUSUS PNS)
        // Syarat: PNS & TMT Pangkat Terakhir <= 4 Tahun yang lalu
        $batasNaikPangkat = Carbon::now()->subYears(4);
        $naikPangkatSegera = Pegawai::where('jenis_pegawai', 'PNS')
            ->whereNotNull('tmt_pangkat_terakhir')
            ->whereDate('tmt_pangkat_terakhir', '<=', $batasNaikPangkat)
            ->count();

        // E. Hitung Waktunya Kenaikan Gaji Berkala (PNS & PPPK) [BARU]
        // Syarat: PNS/PPPK & TMT Gaji Terakhir <= 2 Tahun yang lalu
        $batasKGB = Carbon::now()->subYears(2);
        $naikGajiSegera = Pegawai::whereIn('jenis_pegawai', ['PNS', 'PPPK'])
            ->whereNotNull('tmt_gaji_berkala_terakhir')
            ->whereDate('tmt_gaji_berkala_terakhir', '<=', $batasKGB)
            ->count();


        // =================================================================
        // 2. DATA UNTUK TABEL NOTIFIKASI (TAB)
        // =================================================================

        // A. TAB KENAIKAN PANGKAT (PNS Only)
        $listNaikPangkat = Pegawai::where('jenis_pegawai', 'PNS')
            ->whereNotNull('tmt_pangkat_terakhir')
            ->whereDate('tmt_pangkat_terakhir', '<=', $batasNaikPangkat)
            ->orderBy('tmt_pangkat_terakhir', 'asc') // Yang paling lama menunggu di atas
            ->limit(10)
            ->get();

        // B. TAB GAJI BERKALA (PNS & PPPK)
        // Syarat: TMT Gaji <= 2 Tahun yang lalu
        $listGajiBerkala = Pegawai::whereIn('jenis_pegawai', ['PNS', 'PPPK'])
            ->whereNotNull('tmt_gaji_berkala_terakhir')
            ->whereDate('tmt_gaji_berkala_terakhir', '<=', $batasKGB)
            ->orderBy('tmt_gaji_berkala_terakhir', 'asc')
            ->limit(10)
            ->get();

        // C. TAB PENSIUN (Masa Persiapan Pensiun)
        // Logic: Ambil PNS/PPPK, hitung BUP, ambil yang sisa waktu <= 1 Tahun
        $listPensiun = Pegawai::whereIn('jenis_pegawai', ['PNS', 'PPPK'])
            ->get()
            ->filter(function ($p) {
                // Tentukan BUP
                $batas = 58;
                if ($p->jenis_pegawai === 'PNS' && strpos($p->golongan, 'IV') === 0) $batas = 60;
                $pppkHigh = ['XIII', 'XIV', 'XV', 'XVI', 'XVII'];
                if ($p->jenis_pegawai === 'PPPK' && in_array($p->golongan, $pppkHigh)) $batas = 60;

                $tglPensiun = Carbon::parse($p->tanggal_lahir)->addYears($batas);
                
                // Ambil yang (Hari Ini + 1 Tahun) >= Tanggal Pensiun
                // Artinya: Sudah pensiun atau akan pensiun dalam setahun ke depan
                return Carbon::now()->addYear()->greaterThanOrEqualTo($tglPensiun);
            })
            ->sortBy('tanggal_lahir') // Urutkan dari yang paling tua (mendekati pensiun/sudah lewat)
            ->take(10); // Ambil 10 data saja

        // Kirim ke View
        return view('dashboard', compact(
            'totalPegawai', 
            'totalSK', 
            'pensiunTahunIni', 
            'naikPangkatSegera',
            'naikGajiSegera', // Variabel baru
            'listNaikPangkat',
            'listGajiBerkala',
            'listPensiun'
        ));
    }
}