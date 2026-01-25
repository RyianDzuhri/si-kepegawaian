<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pegawai\Pegawai;
use App\Models\Pegawai\SuratKeputusan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // --- 1. STATISTIK UTAMA (KARTU ATAS) ---
        $totalPegawai = Pegawai::count();
        $totalSK = SuratKeputusan::count();

        // Hitung Pegawai Pensiun Tahun Ini (Usia 60)
        // Logic: Lahir pada tahun (Sekarang - 60)
        $tahunLahirPensiun = Carbon::now()->subYears(60)->year;
        $pensiunTahunIni = Pegawai::whereYear('tanggal_lahir', $tahunLahirPensiun)->count();

        // Hitung Pegawai yang Waktunya Naik Pangkat (Sudah 4 tahun dari TMT terakhir)
        $batasNaikPangkat = Carbon::now()->subYears(4);
        $naikPangkatSegera = Pegawai::whereDate('tmt_pangkat_terakhir', '<=', $batasNaikPangkat)->count();


        // --- 2. DATA UNTUK TABEL NOTIFIKASI (TAB) ---

        // A. TAB KENAIKAN PANGKAT
        // Ambil pegawai yang TMT Pangkatnya sudah lewat 4 tahun
        $listNaikPangkat = Pegawai::whereDate('tmt_pangkat_terakhir', '<=', $batasNaikPangkat)
            ->orderBy('tmt_pangkat_terakhir', 'asc') // Urutkan dari yang paling telat
            ->limit(10) // Ambil 10 saja biar tidak penuh
            ->get();

        // B. TAB GAJI BERKALA (KGB)
        // Ambil pegawai yang TMT Gaji-nya sudah lewat 2 tahun
        $batasKGB = Carbon::now()->subYears(2);
        $listGajiBerkala = Pegawai::whereDate('tmt_gaji_berkala_terakhir', '<=', $batasKGB)
            ->orderBy('tmt_gaji_berkala_terakhir', 'asc')
            ->limit(10)
            ->get();

        // C. TAB PENSIUN (Persiapan Pensiun)
        // Ambil pegawai yang usianya di atas 58 tahun (Mendekati 60)
        $batasUsiaPensiun = Carbon::now()->subYears(58);
        $listPensiun = Pegawai::whereDate('tanggal_lahir', '<=', $batasUsiaPensiun)
            ->orderBy('tanggal_lahir', 'asc') // Yang paling tua di atas
            ->limit(10)
            ->get();

        // Kirim semua variabel ke View
        return view('dashboard', compact(
            'totalPegawai', 
            'totalSK', 
            'pensiunTahunIni', 
            'naikPangkatSegera',
            'listNaikPangkat',
            'listGajiBerkala',
            'listPensiun'
        ));
    }
}