<?php

namespace App\Http\Controllers\Pegawai; // Sesuaikan jika namespace abang beda

use App\Http\Controllers\Controller;
use App\Models\Pegawai\Pegawai;
use App\Models\Pegawai\SuratKeputusan; 
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. STATISTIK UMUM
        $totalPegawai = Pegawai::count();
        $totalSK = SuratKeputusan::count();

        // =================================================================
        // 2. LOGIKA PENSIUN (AKURAT <= 1 TAHUN)
        // =================================================================
        $kandidatPensiun = Pegawai::whereIn('jenis_pegawai', ['PNS', 'PPPK', 'PPPK Paruh Waktu'])
            ->whereNotNull('tanggal_lahir')
            ->get()
            ->filter(function ($p) {
                // SET BUP (Harus sama persis dengan yang di View)
                $batas = 58; 
                // Jika jabatannya Kepala Dinas baru 60
                if (stripos($p->jabatan, 'Kepala Dinas') !== false) {
                    $batas = 60;
                }

                $tglPensiun = Carbon::parse($p->tanggal_lahir)->addYears($batas);
                
                // Batas Peringatan: Hari Ini ditambah persis 1 Tahun
                $batasPeringatan = Carbon::now()->addYear();

                // TRUE jika tanggal pensiunnya itu <= 1 Tahun dari sekarang (atau malah sudah lewat)
                return $tglPensiun->lessThanOrEqualTo($batasPeringatan);
            })
            ->sortBy(function ($p) {
                $batas = stripos($p->jabatan, 'Kepala Dinas') !== false ? 60 : 58;
                return Carbon::parse($p->tanggal_lahir)->addYears($batas);
            });

        $pensiunTahunIni = $kandidatPensiun->count();
        $listPensiun = $kandidatPensiun->take(10); // Ambil 10 teratas untuk tabel


        // =================================================================
        // 3. LOGIKA KENAIKAN PANGKAT (PNS ONLY)
        // =================================================================
        // H-60 dari 4 Tahun (Muncul 2 bulan sebelum hari H)
        $batasNaikPangkat = Carbon::now()->subYears(4)->addDays(60); 
        
        $queryNaikPangkat = Pegawai::where('jenis_pegawai', 'PNS')
            ->whereNotNull('tmt_pangkat_terakhir')
            ->whereDate('tmt_pangkat_terakhir', '<=', $batasNaikPangkat);

        $naikPangkatSegera = $queryNaikPangkat->count();
        $listNaikPangkat = $queryNaikPangkat->orderBy('tmt_pangkat_terakhir', 'asc')->limit(10)->get();


        // =================================================================
        // 4. LOGIKA GAJI BERKALA (PNS & PPPK)
        // =================================================================
        // H-60 dari 2 Tahun (Muncul 2 bulan sebelum hari H)
        $batasKGB = Carbon::now()->subYears(2)->addDays(60); 
        
        $queryGajiBerkala = Pegawai::whereIn('jenis_pegawai', ['PNS', 'PPPK'])
            ->whereNotNull('tmt_gaji_berkala_terakhir')
            ->whereDate('tmt_gaji_berkala_terakhir', '<=', $batasKGB);

        $naikGajiSegera = $queryGajiBerkala->count();
        $listGajiBerkala = $queryGajiBerkala->orderBy('tmt_gaji_berkala_terakhir', 'asc')->limit(10)->get();


        // 5. KIRIM DATA KE VIEW
        return view('dashboard', compact(
            'totalPegawai', 
            'totalSK', 
            'pensiunTahunIni', 
            'naikPangkatSegera',
            'naikGajiSegera', 
            'listNaikPangkat',
            'listGajiBerkala',
            'listPensiun'
        ));
    }
}